<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Base Game
$baseGame = \App\Models\Game::where('title', 'like', 'The Witcher 3: Wild Hunt')->first();
if ($baseGame) {
    $baseGame->update([
        'original_price' => 359999,
        'price' => 71999,
        'discount_percent' => 80
    ]);
}

// Complete Edition
$complete = \App\Models\Game::where('title', 'like', 'The Witcher 3: Wild Hunt - Complete Edition')->first();
if ($complete) {
    $complete->update([
        'original_price' => 449000,
        'price' => 89800,
        'discount_percent' => 80
    ]);
}

// GOTY Commercial License
$goty = \App\Models\Game::where('title', 'like', '%Commercial License%')->first();
if ($goty) {
    $goty->update([
        'original_price' => 4490000,
        'price' => 898000, // 80% discount
        'discount_percent' => 80
    ]);
}

echo "Updated prices and discounts!\n";
