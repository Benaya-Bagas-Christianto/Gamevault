<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$game = \App\Models\Game::where('title', 'like', '%Witcher 3%')->first();
echo json_encode($game->packages, JSON_PRETTY_PRINT);
