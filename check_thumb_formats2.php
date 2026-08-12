<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$videos = DB::table('game_galleries')->where('type', 'video')->get();

$formats = [];
foreach ($videos as $video) {
    // get filename
    $basename = basename(parse_url($video->thumbnail, PHP_URL_PATH));
    
    // strip query string if any
    if (strpos($basename, '?') !== false) {
        $basename = explode('?', $basename)[0];
    }

    if (!isset($formats[$basename])) {
        $formats[$basename] = 0;
    }
    $formats[$basename]++;
}

print_r($formats);
