<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

$videos = DB::table('game_galleries')->where('type', 'video')->get();

echo "Total videos: " . count($videos) . "\n";
$brokenCount = 0;
foreach ($videos as $video) {
    try {
        $headers = get_headers($video->thumbnail, 1);
        $status = $headers[0] ?? '';
        if (strpos($status, '404') !== false || strpos($status, '403') !== false) {
            echo "Broken ID: {$video->id} | {$video->thumbnail}\n";
            $brokenCount++;
            if ($brokenCount >= 10) break; // just check the first 10 broken
        }
    } catch (\Exception $e) {
        // error
    }
}
echo "Found $brokenCount broken (stopped at 10).\n";
