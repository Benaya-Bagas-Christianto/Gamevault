<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Game;
use App\Models\GameGallery;
use Carbon\Carbon;

class AddFranchiseGames extends Command
{
    protected $signature = 'steam:add-franchises';
    protected $description = 'Sync Far Cry, Star Wars, Lego, and Bethesda games from Steam API';

    // List of curated popular Steam App IDs for the requested franchises
    protected $appIds = [
        // Far Cry
        13520, 19900, 220240, 298110, 552520, 939960, 2369390, 371660, 233270,
        // Star Wars
        1172380, 1774580, 1237950, 1222730, 32370, 208580, 32430, 32500, 6000, 32470,
        // Lego
        920210, 21000, 213330, 313690, 249130, 647830, 405310, 578330, 21130, 204120, 311770, 214510, 285160,
        // Bethesda
        489830, 611670, 377160, 1151340, 22380, 22370, 379720, 782330, 2280, 201810, 612880, 205100, 403640, 480490, 1716740, 1252330, 268050, 601430
    ];

    public function handle()
    {
        $this->info('Starting Steam API Sync for Franchises...');

        $totalGames = 0;
        $targetGames = 999;

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
                    $count = 0;
                    foreach ($dlcIds as $dlcId) {
                        if ($count >= 20) break;
                        
                        $this->info("   -> Fetching DLC/Music AppID: {$dlcId}");
                        $this->fetchAndSaveApp($dlcId, 'dlc', $gameModel->id);
                        $count++;
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

            // Packages (Editions/Variants)
            $packages = [];
            if (isset($appData['package_groups']) && count($appData['package_groups']) > 0) {
                // Usually group 0 contains the purchase subs (editions)
                $subs = $appData['package_groups'][0]['subs'] ?? [];
                foreach ($subs as $sub) {
                    $discountPct = (int)$sub['percent_savings'];
                    $price = $sub['price_in_cents_with_discount'] / 100;
                    $originalPrice = $price;
                    if ($discountPct > 0) {
                        $originalPrice = round($price / (1 - ($discountPct / 100)));
                    }
                    
                    // Parse the option_text (e.g. "Red Dead Redemption 2 - <span class="...">Rp 879 000</span> Rp 219 750")
                    $name = $sub['option_text'];
                    $name = preg_replace('/ - <span class="discount_original_price">.*?<\/span>.*/', '', $name);
                    $name = preg_replace('/ - Rp.*/', '', $name);

                    $packages[] = [
                        'id' => $sub['packageid'],
                        'name' => $name,
                        'price' => $price,
                        'original_price' => $originalPrice,
                        'discount_percent' => $discountPct
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
