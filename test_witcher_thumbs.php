<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use App\Models\GameGallery;

$game = Game::where('title', 'like', '%Witcher 3%')->first();
if ($game) {
    echo "Found game: " . $game->title . "\n";
    $galleries = GameGallery::where('game_id', $game->id)->where('type', 'video')->get();
    foreach($galleries as $g) {
        echo "ID: " . $g->id . " - " . $g->thumbnail . "\n";
    }
} else {
    echo "Game not found\n";
}
