<?php
$data = file_get_contents('https://store.steampowered.com/api/appdetails?appids=377160&cc=id&l=indonesian');
$json = json_decode($data, true);
$subs = $json['377160']['data']['package_groups'][0]['subs'] ?? [];
print_r($subs);
