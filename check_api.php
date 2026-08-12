<?php
$r = json_decode(file_get_contents("https://store.steampowered.com/api/appdetails?appids=271590"), true);
print_r($r["271590"]["data"]["pc_requirements"]);
