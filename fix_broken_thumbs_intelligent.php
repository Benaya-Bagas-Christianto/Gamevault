<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

$videos = DB::table('game_galleries')->where('type', 'video')->get();
echo "Checking " . count($videos) . " videos for broken thumbnails...\n";

$fixedCount = 0;
$stillBroken = 0;

$formats = [
    'movie_max.jpg',
    'movie_600x337.jpg',
    'movie.293x165.jpg',
    'movie.jpg',
];

foreach ($videos as $video) {
    try {
        $headers = get_headers($video->thumbnail, 1);
        $status = $headers[0] ?? '';
        
        // If it's already working, skip
        if (strpos($status, '200') !== false) {
            continue;
        }
        
        // It's broken (404/403). Try finding the working format
        $workingUrl = null;
        
        // Find the base URL by stripping the filename before the ?
        $parsed = parse_url($video->thumbnail);
        $path = $parsed['path'];
        $query = isset($parsed['query']) ? '?' . $parsed['query'] : '';
        $dirname = dirname($path); // e.g. /store_item_assets/.../974f93db1b97f206de6f21d585524d598cdbe137
        
        $schemeHost = $parsed['scheme'] . '://' . $parsed['host'];
        
        foreach ($formats as $fmt) {
            $testUrl = $schemeHost . $dirname . '/' . $fmt . $query;
            $testHeaders = @get_headers($testUrl, 1);
            $testStatus = $testHeaders[0] ?? '';
            
            if (strpos($testStatus, '200') !== false) {
                $workingUrl = $testUrl;
                break;
            }
        }
        
        if ($workingUrl) {
            DB::table('game_galleries')
                ->where('id', $video->id)
                ->update(['thumbnail' => $workingUrl]);
            echo "Fixed ID {$video->id}: $workingUrl\n";
            $fixedCount++;
        } else {
            echo "Still broken ID {$video->id}: {$video->thumbnail}\n";
            $stillBroken++;
        }
        
    } catch (\Exception $e) {
        echo "Error on ID {$video->id}: " . $e->getMessage() . "\n";
    }
}

echo "\nDone! Fixed: $fixedCount, Still broken: $stillBroken\n";
