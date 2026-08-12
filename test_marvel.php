<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
$game = Game::where('title', 'like', '%Marvel Rivals%')->first();
if ($game) {
    echo "Title: " . $game->title . "\n";
    echo substr(strip_tags(html_entity_decode($game->description)), 0, 300) . "\n";
} else {
    echo "Marvel Rivals not found\n";
}
