<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportUbisoftGames extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:ubisoft';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import popular Ubisoft games from Steam API';

    protected $appIds = [
        359550, // Rainbow Six Siege
        2208920, // AC Valhalla
        812140, // AC Odyssey
        582160, // AC Origins
        2369390, // Far Cry 6
        552520, // Far Cry 5
        220240, // Far Cry 3
        447040, // Watch Dogs 2
        460930, // Ghost Recon Wildlands
        304390, // For Honor
        646910, // The Crew 2
        365590, // The Division
        213670, // South Park: The Stick of Truth
        242550, // Rayman Legends
        235600, // Splinter Cell Blacklist
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Memulai proses import " . count($this->appIds) . " game Ubisoft dari Steam...");

        foreach ($this->appIds as $appId) {
            $this->info("Fetching app ID: {$appId}...");
            $response = Http::get("https://store.steampowered.com/api/appdetails", [
                'appids' => $appId,
                'cc' => 'id'
            ]);

            if (!$response->successful()) {
                $this->error("Gagal mengambil data untuk app ID: {$appId}");
                continue;
            }

            $data = $response->json();
            
            if (!isset($data[$appId]) || !$data[$appId]['success']) {
                $this->error("App ID {$appId} tidak valid atau tidak sukses.");
                continue;
            }

            $gameData = $data[$appId]['data'];

            $slug = Str::slug($gameData['name']);
            if (Game::where('slug', $slug)->exists()) {
                $this->warn("Game '{$gameData['name']}' sudah ada di database. Melewati...");
                continue;
            }

            $price = 0;
            if (isset($gameData['is_free']) && !$gameData['is_free'] && isset($gameData['price_overview'])) {
                $price = $gameData['price_overview']['initial'] / 100;
            }

            $genres = [];
            if (isset($gameData['genres'])) {
                foreach ($gameData['genres'] as $genreObj) {
                    $genres[] = $genreObj['description'];
                }
            }

            $releaseDate = null;
            if (isset($gameData['release_date']) && !$gameData['release_date']['coming_soon']) {
                try {
                    $releaseDate = Carbon::parse($gameData['release_date']['date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $releaseDate = null;
                }
            }

            $coverPath = null;
            if (isset($gameData['header_image'])) {
                try {
                    $imgContent = Http::get($gameData['header_image'])->body();
                    $filename = 'covers/ubisoft_' . $appId . '_' . time() . '.jpg';
                    Storage::disk('public')->put($filename, $imgContent);
                    $coverPath = $filename;
                } catch (\Exception $e) {
                    $this->error("Gagal mendownload cover untuk {$gameData['name']}");
                }
            }

            $game = Game::create([
                'title' => $gameData['name'],
                'slug' => $slug,
                'description' => $gameData['detailed_description'] ?? $gameData['short_description'] ?? '',
                'price' => $price,
                'genre' => $genres,
                'developer' => isset($gameData['developers']) ? implode(', ', $gameData['developers']) : 'Ubisoft',
                'publisher' => isset($gameData['publishers']) ? implode(', ', $gameData['publishers']) : 'Ubisoft',
                'release_date' => $releaseDate,
                'cover_image' => $coverPath,
                'is_active' => true,
            ]);

            if (isset($gameData['movies'])) {
                foreach ($gameData['movies'] as $movie) {
                    if (isset($movie['mp4']['max'])) {
                        $game->galleries()->create([
                            'type' => 'external_mp4',
                            'path' => $movie['mp4']['max']
                        ]);
                    }
                }
            }

            $this->info("Berhasil menambahkan {$gameData['name']}!");
        }

        $this->info("Proses import selesai!");
    }
}
