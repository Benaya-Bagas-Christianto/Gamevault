<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Game;
use App\Models\GameGallery;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;

$publishers = ['Ubisoft', '2K', 'Rockstar Games', 'Bethesda Softworks', 'Havok'];

echo "Melanjutkan sinkronisasi untuk publisher: " . implode(', ', $publishers) . "\n";

function fetchAppDetails($appId, $parentId = null, $type = 'game') {
    if (Game::where('steam_appid', $appId)->exists()) {
        return null; // Sudah ada, skip.
    }
    
    retry:
    try {
        $response = Http::timeout(20)->get("https://store.steampowered.com/api/appdetails", [
            'appids' => $appId,
            'cc' => 'id',
            'l' => 'indonesian'
        ]);
        
        if ($response->status() == 429) {
            echo "⚠️ Terkena Rate Limit Steam! Menunggu 30 detik...\n";
            sleep(30);
            goto retry;
        }
        
        if ($response->successful()) {
            $data = $response->json();
            if (isset($data[$appId]['success']) && $data[$appId]['success']) {
                $appData = $data[$appId]['data'];
                
                $actualType = $type;
                if (isset($appData['type'])) {
                    if ($appData['type'] == 'dlc' || $appData['type'] == 'music') {
                        $actualType = 'dlc';
                    }
                }
                
                $price = 0; $originalPrice = null; $discountPercent = 0;
                if (isset($appData['is_free']) && $appData['is_free']) {
                    $price = 0;
                } elseif (isset($appData['price_overview'])) {
                    $po = $appData['price_overview'];
                    $price = $po['final'] / 100;
                    if (isset($po['discount_percent']) && $po['discount_percent'] > 0) {
                        $discountPercent = $po['discount_percent'];
                        $originalPrice = $po['initial'] / 100;
                    } elseif (isset($po['initial']) && $po['initial'] > $po['final']) {
                        $originalPrice = $po['initial'] / 100;
                        $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
                    }
                }
                
                $genres = [];
                if (isset($appData['genres'])) {
                    foreach ($appData['genres'] as $g) $genres[] = $g['description'];
                }
                
                $desc = $appData['detailed_description'] ?? ($appData['short_description'] ?? 'Tidak ada deskripsi.');
                
                $releaseDate = now();
                if (isset($appData['release_date']['date']) && !empty($appData['release_date']['date'])) {
                    try { $releaseDate = Carbon::parse($appData['release_date']['date'])->format('Y-m-d'); } catch (\Exception $e) {}
                }
                
                $game = Game::create([
                    'title' => $appData['name'], 'description' => $desc, 'price' => $price,
                    'original_price' => $originalPrice, 'discount_percent' => $discountPercent,
                    'genre' => $genres, 'cover_image' => $appData['header_image'] ?? null,
                    'developer' => isset($appData['developers']) ? implode(', ', $appData['developers']) : null,
                    'publisher' => isset($appData['publishers']) ? implode(', ', $appData['publishers']) : null,
                    'release_date' => $releaseDate, 'pc_requirements' => $appData['pc_requirements'] ?? null,
                    'steam_appid' => $appId, 'type' => $actualType, 'parent_id' => $parentId, 'is_active' => true
                ]);
                
                if (isset($appData['movies'])) {
                    foreach ($appData['movies'] as $movie) {
                        $path = $movie['hls_h264'] ?? ($movie['dash_h264'] ?? ($movie['mp4']['max'] ?? null));
                        if ($path) {
                            GameGallery::create(['game_id' => $game->id, 'type' => 'video', 'path' => $path, 'thumbnail' => $movie['thumbnail'] ?? null]);
                        }
                    }
                }
                
                if (isset($appData['screenshots'])) {
                    foreach ($appData['screenshots'] as $ss) {
                        GameGallery::create(['game_id' => $game->id, 'type' => 'image', 'path' => $ss['path_full'] ?? $ss['path_thumbnail']]);
                    }
                }
                echo "✔️ [" . strtoupper($actualType) . "] Berhasil ditambahkan: {$game->title}\n";
                return $appData;
            }
        }
    } catch (ConnectionException $e) {
        echo "⚠️ Koneksi Timeout! Mengulang dalam 5 detik...\n";
        sleep(5);
        goto retry;
    } catch (\Exception $e) {
        echo "⚠️ Error lain: " . $e->getMessage() . "\n";
    }
    return null;
}

foreach ($publishers as $pub) {
    echo "\n\n>>> Mencari Game dari Publisher: {$pub}\n";
    $start = 0;
    while ($start < 100) {
        retry_search:
        try {
            $url = "https://store.steampowered.com/search/results/?query&start={$start}&count=50&dynamic_data=&sort_by=_ASC&snr=1_7_7_151_7&publisher=" . urlencode($pub) . "&infinite=1";
            $response = Http::timeout(20)->get($url);
            if (!$response->successful()) break;
            $data = $response->json();
            
            if (!isset($data['results_html']) || trim($data['results_html']) === '') break;
            
            preg_match_all('/data-ds-appid="(\d+(,\d+)*)"/', $data['results_html'], $matches);
            $appIds = [];
            foreach($matches[1] as $m) {
                $parts = explode(',', $m);
                foreach($parts as $p) $appIds[] = trim($p);
            }
            $appIds = array_unique($appIds);
            
            if (empty($appIds)) break;
            
            foreach ($appIds as $appId) {
                $appData = fetchAppDetails($appId, null, 'game');
                usleep(1500000);
                
                if ($appData && isset($appData['dlc'])) {
                    $dlcCount = 0;
                    foreach ($appData['dlc'] as $dlcId) {
                        fetchAppDetails($dlcId, Game::where('steam_appid', $appId)->first()->id, 'dlc');
                        usleep(1500000);
                        $dlcCount++;
                        if($dlcCount >= 50) {
                            echo "⚠️ Terlalu banyak DLC (>50), melewati sisanya...\n";
                            break;
                        }
                    }
                }
            }
        } catch (ConnectionException $e) {
            echo "⚠️ Koneksi Search Timeout! Mengulang dalam 5 detik...\n";
            sleep(5);
            goto retry_search;
        } catch (\Exception $e) {
            echo "⚠️ Search Error: " . $e->getMessage() . "\n";
            break;
        }
        $start += 50;
    }
}

echo "\n🎉 SEMUA PROSES SINKRONISASI SELESAI 100%!\n";
