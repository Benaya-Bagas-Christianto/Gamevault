<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Game;
use Illuminate\Support\Facades\Http;

class SyncSteamPrices extends Command
{
    protected $signature = 'steam:sync-prices';
    protected $description = 'Sync game prices and discounts from Steam API';

    public function handle()
    {
        $this->info('Starting Price Sync...');
        
        $games = Game::whereNotNull('steam_appid')->get();
        $total = $games->count();
        $this->info("Found $total games to sync.");

        $updated = 0;
        foreach ($games as $game) {
            $this->info("Checking price for: {$game->title} ({$game->steam_appid})");
            
            try {
                $response = Http::get("https://store.steampowered.com/api/appdetails", [
                    'appids' => $game->steam_appid,
                    'cc' => 'id',
                    'filters' => 'price_overview'
                ]);

                if ($response->successful()) {
                    $data = $response->json();
                    if (isset($data[$game->steam_appid]['success']) && $data[$game->steam_appid]['success']) {
                        $appData = $data[$game->steam_appid]['data'];
                        
                        if (isset($appData['price_overview'])) {
                            $po = $appData['price_overview'];
                            $price = $po['final'] / 100;
                            $discountPercent = $po['discount_percent'] ?? 0;
                            $originalPrice = $po['initial'] / 100;

                            $game->update([
                                'price' => $price,
                                'original_price' => $originalPrice,
                                'discount_percent' => $discountPercent
                            ]);
                            $updated++;
                        } elseif (isset($appData['is_free']) && $appData['is_free']) {
                            $game->update([
                                'price' => 0,
                                'original_price' => 0,
                                'discount_percent' => 0
                            ]);
                            $updated++;
                        }
                    }
                }
            } catch (\Exception $e) {
                $this->error("Failed to sync {$game->title}: " . $e->getMessage());
            }

            usleep(500000); // 0.5s sleep to prevent rate limit
        }

        $this->info("Price Sync Complete! Updated $updated games.");
    }
}
