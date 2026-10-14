<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Game;
use App\Models\GameGallery;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

echo "Memulai sinkronisasi pembaruan data game dari Steam...\n";

$games = Game::whereNotNull('steam_appid')->get();

foreach ($games as $game) {
    echo "Mengupdate {$game->title} (AppID: {$game->steam_appid})...\n";
    
    $response = Http::get("https://store.steampowered.com/api/appdetails", [
        'appids' => $game->steam_appid,
        'cc' => 'id',
        'l' => 'indonesian'
    ]);
    
    if ($response->successful()) {
        $data = $response->json();
        if (isset($data[$game->steam_appid]['success']) && $data[$game->steam_appid]['success']) {
            $appData = $data[$game->steam_appid]['data'];
            
            // Update Data Utama
            $price = 0;
            $originalPrice = null;
            $discountPercent = 0;
            
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
                foreach ($appData['genres'] as $g) {
                    $genres[] = $g['description'];
                }
            }
            
            $desc = $appData['detailed_description'] ?? ($appData['short_description'] ?? $game->description);
            
            $releaseDate = $game->release_date;
            if (isset($appData['release_date']['date']) && !empty($appData['release_date']['date'])) {
                try {
                    $releaseDate = Carbon::parse($appData['release_date']['date'])->format('Y-m-d');
                } catch (\Exception $e) {}
            }
            
            $game->update([
                'title' => $appData['name'],
                'description' => $desc,
                'price' => $price,
                'original_price' => $originalPrice,
                'discount_percent' => $discountPercent,
                'genre' => $genres,
                'cover_image' => $appData['header_image'] ?? $game->cover_image,
                'developer' => isset($appData['developers']) ? implode(', ', $appData['developers']) : $game->developer,
                'publisher' => isset($appData['publishers']) ? implode(', ', $appData['publishers']) : $game->publisher,
                'release_date' => $releaseDate,
                'pc_requirements' => $appData['pc_requirements'] ?? null,
            ]);
            
            // Hapus semua galeri lama untuk diganti dengan yang lengkap dari Steam
            GameGallery::where('game_id', $game->id)->delete();
            
            // Masukkan Video
            if (isset($appData['movies'])) {
                foreach ($appData['movies'] as $movie) {
                    $path = $movie['hls_h264'] ?? ($movie['dash_h264'] ?? ($movie['mp4']['max'] ?? null));
                    if ($path) {
                        GameGallery::create([
                            'game_id' => $game->id,
                            'type' => 'video',
                            'path' => $path,
                            'thumbnail' => $movie['thumbnail'] ?? null
                        ]);
                    }
                }
            }
            
            // Masukkan Semua Screenshots
            if (isset($appData['screenshots'])) {
                foreach ($appData['screenshots'] as $ss) {
                    GameGallery::create([
                        'game_id' => $game->id,
                        'type' => 'image',
                        'path' => $ss['path_full'] ?? $ss['path_thumbnail']
                    ]);
                }
            }
            
            echo "=> Sukses update {$game->title} beserta " . count($appData['screenshots'] ?? []) . " screenshots!\n";
        } else {
            echo "=> Gagal mendapatkan data detail dari Steam.\n";
        }
    }
    // Delay sedikit agar tidak terkena rate limit Steam
    usleep(500000); 
}

echo "Semua game selesai diperbarui!\n";
