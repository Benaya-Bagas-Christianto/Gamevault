<?php
$json = file_get_contents('https://store.steampowered.com/api/appdetails?appids=1174180&cc=id&l=indonesian');
$data = json_decode($json, true);
print_r($data['1174180']['data']['package_groups'][0]['subs'] ?? 'No subs found');
