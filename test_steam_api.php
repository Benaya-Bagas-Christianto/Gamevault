<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

function fetchShortDescription($appId) {
    $url = "https://store.steampowered.com/api/appdetails?appids={$appId}&l=indonesian";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    if ($data && isset($data[$appId]['success']) && $data[$appId]['success']) {
        return $data[$appId]['data']['short_description'] ?? null;
    }
    return null;
}

// Extract app ID from a cover image URL
$coverUrl = "https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/292030/header.jpg";
preg_match('/apps\/(\d+)\//', $coverUrl, $matches);
if (isset($matches[1])) {
    $appId = $matches[1];
    echo "App ID: $appId\n";
    $shortDesc = fetchShortDescription($appId);
    echo "Short Desc:\n$shortDesc\n";
}
