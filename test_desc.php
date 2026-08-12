<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
$game = Game::find(2704); // Cyberpunk 2077 or similar
echo \Illuminate\Support\Str::limit(strip_tags($game->description), 250);
