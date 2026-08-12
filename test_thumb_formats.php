<?php
$urls = [
    "https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/256911304/movie.jpg",
    "https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/256911304/movie.1920x1080.jpg",
    "https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/256911304/movie.600x337.jpg",
    "https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/256911304/movie_max.jpg"
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "$code for $url\n";
}
