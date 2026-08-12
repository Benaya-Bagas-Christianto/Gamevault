<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GameGallery;

$galleries = GameGallery::where('type', 'video')->get();
$fixed = 0;

function urlExists($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 3);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code == 200;
}

foreach($galleries as $g) {
    if (strpos($g->thumbnail, 'movie_max.jpg') !== false) {
        if (!urlExists($g->thumbnail)) {
            $newThumb = str_replace('movie_max.jpg', 'movie.jpg', $g->thumbnail);
            if (urlExists($newThumb)) {
                $g->thumbnail = $newThumb;
                $g->save();
                $fixed++;
            }
        }
    }
}
echo "Fixed $fixed empty video thumbnails.\n";
