<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Game;
use App\Models\GameGallery;
use Carbon\Carbon;

class Import2KGames extends Command
{
    protected $signature = 'steam:2k';
    protected $description = 'Import 2K games and DLCs from Steam API';

    protected $appIds = [
        // Borderlands
        729040, 49520, 397540, 1286680,
        // BioShock
        409710, 409720, 8870,
        // Mafia
        1030840, 1030830, 360430,
        // XCOM / Midnight Suns
        200510, 268500, 882100, 368260,
        // Civilization
        8930, 289070, 65980,
        // NBA & WWE
        1919590, 2338770, 1942660, 2315690,
        // Others
        1577120, 67370, 57900
    ];

    public function handle()
    {
        $this->info('Starting 2K Games Sync (Resumable)...');
        $this->appIds = array_unique($this->appIds);

        foreach ($this->appIds as $appId) {
            if (Game::where('steam_appid', $appId)->exists()) {
                $this->info("Game {$appId} already exists. Skipping main fetch.");
                $gameModel = Game::where('steam_appid', $appId)->first();
            } else {
                $this->info("Fetching Game AppID: {$appId}");
                $gameModel = $this->fetchAndSaveApp($appId, 'game');
            }
            
            if ($gameModel && isset($gameModel->steam_dlc_ids)) {
                $dlcIds = $gameModel->steam_dlc_ids;
                foreach ($dlcIds as $dlcId) {
                    if (!Game::where('steam_appid', $dlcId)->exists()) {
                        $this->info("   -> Fetching DLC AppID: {$dlcId}");
                        $this->fetchAndSaveApp($dlcId, 'dlc', $gameModel->id);
                        usleep(500000);
                    } else {
                        $this->info("   -> DLC {$dlcId} already exists.");
                    }
                }
            }
            usleep(500000);
        }

        $this->info("2K Sync Complete!");
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
            
            $steamType = $appData['type'] ?? 'game';
            $type = $defaultType;
            if ($steamType === 'dlc' || $steamType === 'music') {
                $type = 'dlc';
            } elseif ($steamType === 'game') {
                $type = 'game';
            }

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

            $genres = [];
            if (isset($appData['genres'])) {
                foreach ($appData['genres'] as $g) {
                    $genres[] = $g['description'];
                }
            }

            $desc = $appData['detailed_description'] ?? ($appData['short_description'] ?? 'No description.');

            $releaseDate = now();
            if (isset($appData['release_date']['date']) && !empty($appData['release_date']['date'])) {
                try {
                    $releaseDate = Carbon::parse($appData['release_date']['date'])->format('Y-m-d');
                } catch (\Exception $e) {
                }
            }

            $pcRequirements = $appData['pc_requirements'] ?? null;

            $packages = [];
            if (isset($appData['package_groups']) && count($appData['package_groups']) > 0) {
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
                    'developer' => isset($appData['developers']) ? implode(', ', $appData['developers']) : '2K',
                    'publisher' => isset($appData['publishers']) ? implode(', ', $appData['publishers']) : '2K',
                    'release_date' => $releaseDate,
                    'is_active' => true,
                    'type' => $type,
                    'parent_id' => $parentId,
                    'steam_appid' => $appId,
                    'pc_requirements' => $pcRequirements,
                    'packages' => $packages,
                ]
            );

            GameGallery::where('game_id', $model->id)->delete();
            
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
