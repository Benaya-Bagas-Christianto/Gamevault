<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ImportSteamGames extends Command
{
    protected $signature = 'import:steam {keyword} {--limit=20}';
    protected $description = 'Import games from Steam using a search keyword';

    public function handle()
    {
        $keyword = $this->argument('keyword');
        $limit = $this->option('limit');
        
        $this->info("Mencari game di Steam dengan keyword: '{$keyword}'...");

        // Search Steam store
        $searchResponse = Http::get("https://store.steampowered.com/api/storesearch/", [
            'term' => $keyword,
            'l' => 'english',
            'cc' => 'id'
        ]);

        if (!$searchResponse->successful()) {
            $this->error("Gagal melakukan pencarian di Steam API.");
            return;
        }

        $items = $searchResponse->json('items');
        
        if (empty($items)) {
            $this->warn("Tidak ada game ditemukan untuk keyword '{$keyword}'.");
            return;
        }

        // Limit results
        $items = array_slice($items, 0, $limit);
        $this->info("Menemukan " . count($items) . " game/DLC. Memulai proses import...");

        $imported = 0;

        foreach ($items as $item) {
            $appId = $item['id'];
            $this->info("Fetching data untuk App ID: {$appId} ({$item['name']})...");
            
            // Get detailed info
            $response = Http::get("https://store.steampowered.com/api/appdetails", [
                'appids' => $appId,
                'cc' => 'id'
            ]);

            if (!$response->successful()) {
                $this->error("Gagal mengambil detail app ID: {$appId}");
                continue;
            }

            $data = $response->json();
            
            if (!isset($data[$appId]) || !$data[$appId]['success']) {
                $this->error("App ID {$appId} tidak valid/region locked.");
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
                    $filename = 'covers/steam_' . $appId . '_' . time() . '.jpg';
                    Storage::disk('public')->put($filename, $imgContent);
                    $coverPath = $filename;
                } catch (\Exception $e) {
                    $this->error("Gagal mendownload cover untuk {$gameData['name']}");
                }
            }
            
            // Check if it's DLC
            $type = 'game';
            if (isset($gameData['type']) && $gameData['type'] === 'dlc') {
                $type = 'dlc';
            }

            $game = Game::create([
                'title' => $gameData['name'],
                'slug' => $slug,
                'description' => $gameData['detailed_description'] ?? $gameData['short_description'] ?? '',
                'price' => $price,
                'genre' => $genres,
                'developer' => isset($gameData['developers']) ? implode(', ', $gameData['developers']) : 'Unknown',
                'publisher' => isset($gameData['publishers']) ? implode(', ', $gameData['publishers']) : 'Unknown',
                'release_date' => $releaseDate,
                'cover_image' => $coverPath,
                'is_active' => true,
                'type' => $type
            ]);

            if (isset($gameData['movies'])) {
                foreach ($gameData['movies'] as $movie) {
                    if (isset($movie['hls_h264'])) {
                        $game->galleries()->create([
                            'type' => 'external_mp4',
                            'path' => $movie['hls_h264']
                        ]);
                    } elseif (isset($movie['mp4']['max'])) {
                        $game->galleries()->create([
                            'type' => 'external_mp4',
                            'path' => $movie['mp4']['max']
                        ]);
                    }
                }
            }

            if (isset($gameData['screenshots'])) {
                foreach ($gameData['screenshots'] as $screenshot) {
                    if (isset($screenshot['path_full'])) {
                        $game->galleries()->create([
                            'type' => 'image',
                            'path' => $screenshot['path_full'],
                            'thumbnail' => $screenshot['path_thumbnail'] ?? null
                        ]);
                    }
                }
            }

            $this->info("Berhasil menambahkan {$gameData['name']}!");
            $imported++;
            
            // Sleep to prevent rate limit (max 200 per 5 min)
            usleep(500000); // 0.5s
        }

        $this->info("Proses import selesai! Total berhasil diimport: {$imported}");
    }
}
