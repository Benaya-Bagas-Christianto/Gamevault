<?php
$lines = file('d:/Laragon/laragon/www/tokoGame/public/css/app.css');
echo "=== MODAL/OVERLAY CSS ===\n";
foreach($lines as $k => $v) {
    if(stripos($v, 'modal') !== false || stripos($v, 'overlay') !== false) {
        echo ($k+1).': '.trim($v)."\n";
    }
}

echo "\n=== app.blade.php: ALL ELEMENTS WITH position:fixed ===\n";
$blade = file('d:/Laragon/laragon/www/tokoGame/resources/views/layouts/app.blade.php');
foreach($blade as $k => $v) {
    if(stripos($v, 'position:fixed') !== false || stripos($v, 'position: fixed') !== false) {
        echo ($k+1).': '.trim($v)."\n";
    }
}
