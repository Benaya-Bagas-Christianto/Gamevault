<?php
$data = file_get_contents('https://store.steampowered.com/api/appdetails?appids=292030&cc=id&l=indonesian');
$json = json_decode($data, true);
$po = $json['292030']['data']['price_overview'] ?? null;
echo "Price Overview:\n";
print_r($po);
