<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GameGallery;

// Fix thumbnails for games added in the last 2 hours
$recentGalleries = GameGallery::where('type', 'video')
    ->where('created_at', '>=', now()->subHours(2))
    ->get();

$fixed = 0;
foreach($recentGalleries as $g) {
    if (strpos($g->thumbnail, 'movie_max.jpg') !== false) {
        // Just blindly replace it to movie.jpg for recent games since we know movie_max is 404 for them
        $g->thumbnail = str_replace('movie_max.jpg', 'movie.jpg', $g->thumbnail);
        $g->save();
        $fixed++;
    }
}
echo "Instantly fixed $fixed video thumbnails for recent games.\n";
