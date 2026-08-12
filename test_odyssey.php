<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$game = App\Models\Game::where('title', 'like', '%Odyssey%')->first();
if ($game) {
    echo "Title: " . $game->title . "\n";
    echo "Cover: " . $game->cover_image . "\n";
}
