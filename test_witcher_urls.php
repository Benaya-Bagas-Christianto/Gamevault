<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

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

$testUrls = [
    'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/256927226/movie_max.jpg?t=1674829926',
    'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/256927226/movie.jpg?t=1674829926',
    'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/256927226/movie.293x165.jpg?t=1674829926'
];

foreach ($testUrls as $url) {
    echo "Testing $url: " . (urlExists($url) ? 'OK' : 'FAIL') . "\n";
}
