<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$game = \App\Models\Game::where('title', 'like', '%Witcher 3%')->first();
echo json_encode([
    'title' => $game->title,
    'price' => $game->price,
    'original_price' => $game->original_price,
    'discount_percent' => $game->discount_percent
], JSON_PRETTY_PRINT);

