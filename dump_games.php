<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$games = \App\Models\Game::where('title', 'like', '%Witcher 3%')->get();
foreach($games as $g) {
    echo "ID: {$g->id}, Title: {$g->title}, Price: {$g->price}, Orig: {$g->original_price}, Disc: {$g->discount_percent}%\n";
}
