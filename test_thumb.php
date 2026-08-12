<?php
$url = "https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/256911304/movie_max.jpg";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP Status for movie_max.jpg: " . $code . "\n";
