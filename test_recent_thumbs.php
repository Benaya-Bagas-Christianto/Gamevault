<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GameGallery;
$recentGalleries = GameGallery::where('type', 'video')->where('created_at', '>=', now()->subHours(2))->get();
foreach($recentGalleries as $g) {
    echo $g->id . ' | ' . $g->thumbnail . "\n";
}
