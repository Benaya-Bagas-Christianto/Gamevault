<?php
$url_base = 'https://shared.akamai.steamstatic.com/store_item_assets/steam/apps/257109786/974f93db1b97f206de6f21d585524d598cdbe137/';

$formats = [
    'movie.jpg',
    'movie_max.jpg',
    'movie_600x337.jpg',
    'movie.293x165.jpg',
    'movie_480.jpg',
    'movie.814x458.jpg'
];

foreach ($formats as $fmt) {
    $url = $url_base . $fmt;
    $headers = get_headers($url, 1);
    $status = $headers[0] ?? '';
    echo "$fmt: $status\n";
}
