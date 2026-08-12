<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$games = App\Models\Game::whereIn('title', ['Black Myth: Wukong', 'Call of Duty®', 'Team Fortress 2'])->get();

foreach($games as $g) {
    echo "Game: " . $g->title . "\n";
    echo "cover_image: " . $g->cover_image . "\n";
    echo "cover_image_url: " . $g->cover_image_url . "\n";
    echo "header_image: " . $g->header_image . "\n";
    echo "--------------------------\n";
}
