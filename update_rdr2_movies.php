<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use App\Models\GameGallery;

$appId = 1174180;
$json = file_get_contents('https://store.steampowered.com/api/appdetails?appids='.$appId);
$data = json_decode($json, true);
$appData = $data[$appId]['data'];

$model = Game::where('title', 'Red Dead Redemption 2')->first();
if($model) {
    echo "Found game, updating movies...\n";
    GameGallery::where('game_id', $model->id)->where('type', 'video')->delete();
    if (isset($appData['movies'])) {
        foreach ($appData['movies'] as $movie) {
            $path = $movie['mp4']['max'] ?? ($movie['webm']['max'] ?? ($movie['mp4']['480'] ?? ($movie['webm']['480'] ?? ($movie['hls_h264'] ?? null))));
            if ($path) {
                GameGallery::create([
                    'game_id' => $model->id,
                    'type' => 'video',
                    'path' => $path
                ]);
            }
        }
    }
    echo "Done.\n";
} else {
    echo "Game not found.\n";
}
