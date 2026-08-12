<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Game;
use Illuminate\Support\Facades\Http;

$games = Game::where('cover_image', 'like', 'covers/steam_%')
             ->orWhere('cover_image', 'like', 'covers/ubisoft_%')
             ->get();

$totalScreenshots = 0;

foreach($games as $game) {
    if (preg_match('/(?:covers\/steam_|covers\/ubisoft_)(\d+)_/', $game->cover_image, $m)) {
        $appId = $m[1];
        
        // Skip if already has images
        if ($game->galleries()->where('type', 'image')->exists()) {
            continue;
        }

        echo "Fetching screenshots for: " . $game->title . " (App ID: $appId)...\n";
        
        $res = Http::get('https://store.steampowered.com/api/appdetails?appids='.$appId.'&cc=id')->json();
        
        if(isset($res[$appId]['data']['screenshots'])) {
            $count = 0;
            foreach ($res[$appId]['data']['screenshots'] as $screenshot) {
                if (isset($screenshot['path_full'])) {
                    $game->galleries()->create([
                        'type' => 'image',
                        'path' => $screenshot['path_full'],
                        'thumbnail' => $screenshot['path_thumbnail'] ?? null
                    ]);
                    $count++;
                    $totalScreenshots++;
                }
            }
            echo "Added $count screenshots.\n";
        }
        usleep(300000); // 0.3s delay to respect Steam rate limits
    }
}

echo "Done! Total screenshots added: $totalScreenshots\n";
