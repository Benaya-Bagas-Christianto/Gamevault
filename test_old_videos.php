<?php
$url = "http://video.akamai.steamstatic.com/store_trailers/256790450/movie_max.mp4";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch);
$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP Status for movie_max.mp4: " . $code . "\n";

$url2 = "http://video.akamai.steamstatic.com/store_trailers/256790450/movie_max.webm";
$ch2 = curl_init($url2);
curl_setopt($ch2, CURLOPT_NOBODY, true);
curl_setopt($ch2, CURLOPT_FOLLOWLOCATION, true);
curl_exec($ch2);
$code2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
curl_close($ch2);
echo "HTTP Status for movie_max.webm: " . $code2 . "\n";
