<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$game = App\Models\Game::where('title', 'like', '%Red Dead Redemption 2%')->first();
echo "Game: " . $game->title . "\n";
echo "Dev: " . $game->developer . "\n";
echo "Pub: " . $game->publisher . "\n";
echo "Date: " . $game->release_date . "\n";
