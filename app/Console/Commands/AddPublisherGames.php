<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Game;
use App\Models\GameGallery;
use Carbon\Carbon;

class AddPublisherGames extends Command
{
    protected $signature = 'steam:add-publishers';
    protected $description = 'Sync Ubisoft, 2K, and EA games from Steam API with HD assets';

    protected $appIds = [
        // EA Games
        1222670, 1238810, 1517290, 1172380, 1774580, 1238000, 1222690, 1172470, 1238840, 1222680, 1811260, 213340, 1693980,
        // Ubisoft
        359550, 220240, 298110, 552520, 2369390, 812140, 2208920, 289650, 2420110, 2539820, 333920, 2438100, 2438130,
        // 2K Games
        49520, 397540, 7670, 8870, 360430, 1030840, 289070, 8930, 268500, 2315690, 1546990, 1928240
    ];

    public function handle()
    {
        $this->info('Starting Steam API Sync for EA, Ubisoft, and 2K (with HD assets)...');

        $totalGames = 0;

        foreach ($this->appIds as $appId) {
            $this->info("Fetching Game AppID: {$appId}");
            $gameModel = $this->fetchAndSaveApp($appId, 'game');
            
            if ($gameModel) {
                $totalGames++;
                $this->info("Main Games count: $totalGames");
                
                if (isset($gameModel->steam_dlc_ids)) {
                    $dlcIds = $gameModel->steam_dlc_ids;
                    $count = 0;
                    foreach ($dlcIds as $dlcId) {
                        if ($count >= 10) break; // limit to 10 top DLCs per game
                        
                        $this->info("   -> Fetching DLC AppID: {$dlcId}");
                        $this->fetchAndSaveApp($dlcId, 'dlc', $gameModel->id);
                        $count++;
                        usleep(500000); // 500ms delay to respect rate limit
                    }
                }
            }
            usleep(500000); // 500ms delay
        }

        $this->info("Sync Complete! Total Main Games: $totalGames");
    }

    private function urlExists($url) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $code == 200;
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
            $originalPrice = 0;
            $discountPercent = 0;

            if (isset($appData['is_free']) && $appData['is_free']) {
                $price = 0;
            } elseif (isset($appData['price_overview'])) {
                $priceOverview = $appData['price_overview'];
                $price = $priceOverview['final'] / 100;
                $originalPrice = $priceOverview['initial'] / 100;
                $discountPercent = $priceOverview['discount_percent'];
            }

            $desc = $appData['about_the_game'] ?? '';
            $desc = str_replace('movie480.webm', 'movie_max.webm', $desc);
            $desc = str_replace('movie480.mp4', 'movie_max.mp4', $desc);

            $releaseDate = null;
            if (isset($appData['release_date']['date']) && !empty($appData['release_date']['date'])) {
                try {
                    $releaseDate = Carbon::parse($appData['release_date']['date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $releaseDate = null;
                }
            }

            $genres = [];
            if (isset($appData['genres'])) {
                foreach ($appData['genres'] as $g) {
                    $genres[] = $g['description'];
                }
            }

            $dev = isset($appData['developers']) ? implode(', ', $appData['developers']) : 'Unknown';
            $pub = isset($appData['publishers']) ? implode(', ', $appData['publishers']) : 'Unknown';

            $coverImage = $appData['header_image'] ?? '';
            $hdCover = str_replace('header.jpg', 'capsule_616x353.jpg', $coverImage);
            if ($this->urlExists($hdCover)) {
                $coverImage = $hdCover;
            }

            $game = Game::updateOrCreate(
                ['title' => $appData['name']],
                [
                    'description' => $desc,
                    'price' => $price,
                    'original_price' => $originalPrice,
                    'discount_percent' => $discountPercent,
                    'genre' => $genres,
                    'developer' => $dev,
                    'publisher' => $pub,
                    'release_date' => $releaseDate,
                    'cover_image' => $coverImage,
                    'type' => $type,
                    'pc_requirements' => $appData['pc_requirements'] ?? null
                ]
            );

            // Fetch and save galleries
            GameGallery::where('game_id', $game->id)->delete();
            
            if (isset($appData['movies'])) {
                foreach ($appData['movies'] as $movie) {
                    $videoPath = '';
                    $videoThumb = $movie['thumbnail'] ?? '';
                    $videoThumb = preg_replace('/movie_\d+x\d+\.jpg/', 'movie_max.jpg', $videoThumb);
                    $videoThumb = preg_replace('/movie\.\d+x\d+\.jpg/', 'movie_max.jpg', $videoThumb);
                    
                    if (isset($movie['webm']['max'])) {
                        $videoPath = $movie['webm']['max'];
                    } elseif (isset($movie['mp4']['max'])) {
                        $videoPath = $movie['mp4']['max'];
                    } else {
                        // Fallback: build MP4 URL from thumbnail ID
                        if (preg_match('/apps\/(\d+)\//', $videoThumb, $matches)) {
                            $id = $matches[1];
                            $videoPath = "https://video.akamai.steamstatic.com/store_trailers/{$id}/movie_max.mp4";
                        }
                    }
                    
                    GameGallery::create([
                        'game_id' => $game->id,
                        'type' => 'video',
                        'path' => $videoPath,
                        'thumbnail' => $videoThumb
                    ]);
                }
            }

            if (isset($appData['screenshots'])) {
                foreach ($appData['screenshots'] as $ss) {
                    $pathFull = $ss['path_full'] ?? $ss['path_thumbnail'];
                    
                    GameGallery::create([
                        'game_id' => $game->id,
                        'type' => 'image',
                        'path' => $pathFull,
                        'thumbnail' => ''
                    ]);
                }
            }

            $game->steam_dlc_ids = $appData['dlc'] ?? null;

            return $game;
        } catch (\Exception $e) {
            $this->error("Error fetching {$appId}: " . $e->getMessage());
            return null;
        }
    }
}
