<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Game;
use Illuminate\Support\Facades\Log;

class FetchSteamGames extends Command
{
    protected $signature = 'steam:fetch {--limit=10 : Jumlah game yang ingin diambil}';
    protected $description = 'Mengambil data game populer dari Steam API dan menyimpannya ke database';

    // List of popular app IDs to guarantee good data
    protected $appIds = [
        730, // CS:GO (CS2)
        570, // Dota 2
        271590, // GTA V
        1091500, // Cyberpunk 2077
        1174180, // RDR 2
        1245620, // Elden Ring
        1086940, // Baldur's Gate 3
        105600, // Terraria
        413150, // Stardew Valley
        400, // Portal
        292030, // The Witcher 3
        892970, // Valheim
        252490, // Rust
        230410, // Warframe
        1938090, // Call of Duty
        359550, // Rainbow Six Siege
        289070, // Civilization VI
        394360, // Hearts of Iron IV
        236850, // Europa Universalis IV
    ];

    public function handle()
    {
        $this->info('Mulai mengambil data game dari Steam API...');
        
        $limit = $this->option('limit');
        $appIdsToFetch = array_slice(array_unique($this->appIds), 0, $limit);
        
        foreach ($appIdsToFetch as $appId) {
            $this->info("Fetching App ID: $appId");
            
            try {
                // Fetch from Steam Store API with Indonesian currency
                $response = Http::get("https://store.steampowered.com/api/appdetails?appids={$appId}&cc=id&l=indonesian");
                
                if (!$response->successful()) {
                    $this->error("Gagal mengambil App ID $appId");
                    continue;
                }

                $data = $response->json();
                
                if (!isset($data[$appId]) || !$data[$appId]['success']) {
                    $this->error("Data tidak valid untuk App ID $appId");
                    continue;
                }

                $gameData = $data[$appId]['data'];
                
                // Skip if not a game
                if ($gameData['type'] !== 'game') {
                    $this->warn("Bukan sebuah game. Melewati...");
                    continue;
                }

                $title = $gameData['name'];
                
                // Process Price
                $price = 0;
                if (isset($gameData['price_overview'])) {
                    // Steam price is in cents (e.g. 5000000 = Rp 500.000)
                    $price = $gameData['price_overview']['initial'] / 100;
                }
                
                // Process Genres
                $genres = [];
                if (isset($gameData['genres'])) {
                    foreach ($gameData['genres'] as $genre) {
                        $genres[] = $genre['description'];
                    }
                }
                
                // Process Description (Short)
                $description = strip_tags($gameData['short_description']);
                
                // Process Developers & Publishers
                $developer = isset($gameData['developers']) ? $gameData['developers'][0] : 'Unknown';
                $publisher = isset($gameData['publishers']) ? $gameData['publishers'][0] : 'Unknown';
                
                // Process Release Date
                $releaseDate = null;
                if (isset($gameData['release_date']) && !$gameData['release_date']['coming_soon']) {
                    try {
                        // Attempt to parse date, fallback to null if fails
                        $parsedDate = \Carbon\Carbon::parse($gameData['release_date']['date']);
                        $releaseDate = $parsedDate->format('Y-m-d');
                    } catch (\Exception $e) {
                        $releaseDate = null;
                    }
                }

                // Process Cover Image
                $imageUrl = $gameData['header_image'];
                $imageName = 'games/' . $appId . '_' . basename(parse_url($imageUrl, PHP_URL_PATH));
                
                // Remove query strings from image name if any
                $imageName = preg_replace('/\?.*/', '', $imageName);
                
                // Ensure games directory exists
                if (!Storage::disk('public')->exists('games')) {
                    Storage::disk('public')->makeDirectory('games');
                }

                // Download the image
                if (!Storage::disk('public')->exists($imageName)) {
                    $imageContents = @file_get_contents($imageUrl);
                    if ($imageContents) {
                        Storage::disk('public')->put($imageName, $imageContents);
                    } else {
                        // Fallback if image download fails
                        $imageName = null;
                    }
                }

                // Update or Create Game
                $game = Game::updateOrCreate(
                    ['slug' => Str::slug($title)],
                    [
                        'title' => $title,
                        'description' => $description,
                        'price' => $price,
                        'genre' => $genres,
                        'cover_image' => $imageName,
                        'developer' => $developer,
                        'publisher' => $publisher,
                        'release_date' => $releaseDate,
                        'is_active' => true,
                    ]
                );

                $this->info("✓ Berhasil menyimpan: $title");
                
                // Delay to prevent rate limiting (Steam allows ~200 requests per 5 mins, 1s is safe)
                sleep(1);

            } catch (\Exception $e) {
                $this->error("Terjadi kesalahan pada App ID $appId: " . $e->getMessage());
            }
        }
        
        $this->info('Selesai mengambil data dari Steam!');
    }
}
