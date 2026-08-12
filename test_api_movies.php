<?php
$appId = 1222670; // Sims 4
$json = file_get_contents("https://store.steampowered.com/api/appdetails?appids=$appId&cc=id&l=indonesian");
$data = json_decode($json, true);
$movies = $data[$appId]['data']['movies'] ?? [];
print_r($movies);
