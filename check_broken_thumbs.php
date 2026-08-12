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

$galleries = GameGallery::where('type', 'video')->get();
$broken = [];
foreach($galleries as $g) {
    if (!urlExists($g->thumbnail)) {
        $broken[] = $g->id . " | " . $g->thumbnail;
    }
}
file_put_contents('broken_thumbs.txt', implode("\n", $broken));
echo "Found " . count($broken) . " broken thumbnails.\n";
