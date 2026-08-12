<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GameGallery;

function urlExists($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 2);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $code == 200;
}

$recentGalleries = GameGallery::where('type', 'video')->where('created_at', '>=', now()->subHours(2))->get();
$fixed = 0;
foreach($recentGalleries as $g) {
    if (strpos($g->thumbnail, 'movie.jpg') !== false) {
        if (!urlExists($g->thumbnail)) {
            $fallback = str_replace('movie.jpg', 'movie.293x165.jpg', $g->thumbnail);
            if (urlExists($fallback)) {
                $g->thumbnail = $fallback;
                $g->save();
                $fixed++;
                echo "Fixed thumbnail for ID {$g->id} to 293x165\n";
            } else {
                echo "Failed to fix thumbnail for ID {$g->id} (both movie.jpg and movie.293x165.jpg are 404)\n";
            }
        }
    }
}
echo "Done checking. Fixed $fixed thumbnails.\n";
