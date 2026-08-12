<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GameGallery;

$galleries = GameGallery::where('type', 'video')->orderBy('id', 'desc')->take(10)->get();
foreach($galleries as $g) {
    echo $g->game_id . " | " . $g->path . "\n";
}
