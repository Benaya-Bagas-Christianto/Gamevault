<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use App\Models\GameGallery;

$game = Game::where('title', 'like', '%Need for Speed%')->first();
if ($game) {
    echo "Found game: " . $game->title . "\n";
    $galleries = GameGallery::where('game_id', $game->id)->where('type', 'video')->get();
    foreach($galleries as $g) {
        echo $g->thumbnail . "\n";
        
        $ch = curl_init($g->thumbnail);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        echo "Status: $code\n\n";
    }
}
