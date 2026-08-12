<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Game;
use App\Models\GameGallery;
use Carbon\Carbon;

class SyncSteamGames extends Command
{
    protected $signature = 'steam:sync';
    protected $description = 'Sync curated games, DLCs, and Soundtracks from Steam API';

    // List of curated popular Steam App IDs
    protected $appIds = [
        271590, 1091500, 1245620, 1174180, 1086940, 1593500, 2050650, 1888930, 1817070, 1817190,
        1151640, 2420110, 1190460, 2215430, 1222670, 252490, 230410, 1085660, 1172470, 730,
        578080, 1623730, 440, 1938090, 1063730, 2358720, 550, 304930, 1599340, 236390,
        2246340, 553850, 105600, 2694490, 431960, 291550, 4000, 359550, 552990, 346110,
        413150, 242760, 1491000, 238960, 218620, 291480, 901583, 899770, 340, 381210,
        990080, 438100, 292030, 945360, 1097150, 49520, 227300, 892970, 739630, 444090,
        1203220, 320, 70, 582010, 227940, 252950, 108600, 1966720, 1517290, 1240440,
        648800, 386360, 444200, 10, 240, 3164500, 755790, 400, 550650, 322330,
        239140, 477160, 251570, 100, 1665460, 417910, 433850, 594650, 814380, 1116170,
        220, 107410, 1326470, 219990, 255710, 261550, 8930, 304050, 289070, 377160,
        504230, 211820
    ];

    public function handle()
    {
        $this->info('Starting Steam API Sync...');
        
        Game::query()->delete();
        $this->info('Cleared old games.');

        $totalGames = 0;
        $targetGames = 100;

        foreach ($this->appIds as $appId) {
            if ($totalGames >= $targetGames) {
                break;
            }

            $this->info("Fetching Game AppID: {$appId}");
            $gameModel = $this->fetchAndSaveApp($appId, 'game');
            
            if ($gameModel) {
                $totalGames++;
                $this->info("Main Games count: $totalGames / $targetGames");
                
                if (isset($gameModel->steam_dlc_ids)) {
                    $dlcIds = $gameModel->steam_dlc_ids;
                    foreach ($dlcIds as $dlcId) {
                        $this->info("   -> Fetching DLC/Music AppID: {$dlcId}");
                        $this->fetchAndSaveApp($dlcId, 'dlc', $gameModel->id);
                        sleep(1);
                    }
                }
            }
            sleep(1);
        }

        $this->info("Sync Complete! Total Main Games: $totalGames");
    }

    private function fetchAndSaveApp($appId, $defaultType, $parentId = null)
    {
        try {
            $response = Http::get("https://store.steampowered.com/api/appdetails", [
                'appids' => $appId,
                'cc' => 'id',
                'l' => 'indonesian'
            ]);

            if (!$response->successful()) {
                $this->error("Failed to fetch API for $appId");
                return null;
            }

            $data = $response->json();
            
            if (!isset($data[$appId]['success']) || !$data[$appId]['success']) {
                $this->warn("App $appId not found or success=false on Steam.");
                return null;
            }

            $appData = $data[$appId]['data'];
            
            // Check type
            $steamType = $appData['type'] ?? 'game';
            $type = $defaultType;
            if ($steamType === 'dlc' || $steamType === 'music') {
                $type = 'dlc';
            } elseif ($steamType === 'game') {
                $type = 'game';
            }

            // Price
            $price = 0;
            $discountPercent = 0;
            $originalPrice = null;
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

            // Genres
            $genres = [];
            if (isset($appData['genres'])) {
                foreach ($appData['genres'] as $g) {
                    $genres[] = $g['description'];
                }
            }

            // Desc - Full HTML
            $desc = $appData['detailed_description'] ?? ($appData['short_description'] ?? 'No description.');

            // Release date
            $releaseDate = now();
            if (isset($appData['release_date']['date']) && !empty($appData['release_date']['date'])) {
                try {
                    $releaseDate = Carbon::parse($appData['release_date']['date'])->format('Y-m-d');
                } catch (\Exception $e) {
                }
            }

            // PC Requirements
            $pcRequirements = $appData['pc_requirements'] ?? null;

            $packages = [];
            if (isset($appData['package_groups']) && count($appData['package_groups']) > 0) {
                // Usually group 0 contains the purchase subs (editions)
                $subs = $appData['package_groups'][0]['subs'] ?? [];
                foreach ($subs as $sub) {
                    $pkgDiscountPct = (int)$sub['percent_savings'];
                    if ($pkgDiscountPct === 0 && isset($sub['percent_savings_text'])) {
                        $pkgDiscountPct = (int)preg_replace('/[^0-9]/', '', $sub['percent_savings_text']);
                    }
                    $pkgPrice = $sub['price_in_cents_with_discount'] / 100;
                    $pkgOriginalPrice = $pkgPrice;
                    if ($pkgDiscountPct > 0) {
                        $pkgOriginalPrice = round($pkgPrice / (1 - ($pkgDiscountPct / 100)));
                    }
                    
                    // Parse the option_text (e.g. "Red Dead Redemption 2 - <span class="...">Rp 879 000</span> Rp 219 750")
                    $name = $sub['option_text'];
                    $name = preg_replace('/ - <span class="discount_original_price">.*?<\/span>.*/', '', $name);
                    $name = preg_replace('/ - Rp.*/', '', $name);

                    $packages[] = [
                        'id' => $sub['packageid'],
                        'name' => $name,
                        'price' => $pkgPrice,
                        'original_price' => $pkgOriginalPrice,
                        'discount_percent' => $pkgDiscountPct
                    ];
                }
            }

            $model = Game::updateOrCreate(
                ['title' => $appData['name']],
                [
                    'description' => $desc,
                    'price' => $price,
                    'original_price' => $originalPrice,
                    'discount_percent' => $discountPercent,
                    'genre' => $genres,
                    'cover_image' => $appData['header_image'] ?? null,
                    'developer' => isset($appData['developers']) ? implode(', ', $appData['developers']) : null,
                    'publisher' => isset($appData['publishers']) ? implode(', ', $appData['publishers']) : null,
                    'release_date' => $releaseDate,
                    'is_active' => true,
                    'type' => $type,
                    'parent_id' => $parentId,
                    'pc_requirements' => $pcRequirements,
                    'packages' => $packages,
                ]
            );

            // Sync Galleries
            GameGallery::where('game_id', $model->id)->delete();
            
            // Movies
            if (isset($appData['movies'])) {
                foreach ($appData['movies'] as $movie) {
                    $path = $movie['mp4']['max'] ?? ($movie['webm']['max'] ?? ($movie['mp4']['480'] ?? ($movie['webm']['480'] ?? ($movie['hls_h264'] ?? null))));
                    if ($path) {
                        GameGallery::create([
                            'game_id' => $model->id,
                            'type' => 'video',
                            'path' => $path,
                            'thumbnail' => $movie['thumbnail'] ?? null
                        ]);
                    }
                }
            }

            // Screenshots
            if (isset($appData['screenshots'])) {
                foreach ($appData['screenshots'] as $ss) {
                    GameGallery::create([
                        'game_id' => $model->id,
                        'type' => 'image',
                        'path' => $ss['path_full'] ?? $ss['path_thumbnail']
                    ]);
                }
            }

            if (isset($appData['dlc'])) {
                $model->steam_dlc_ids = $appData['dlc'];
            }

            return $model;

        } catch (\Exception $e) {
            $this->error("Error processing $appId: " . $e->getMessage());
            return null;
        }
    }
}
