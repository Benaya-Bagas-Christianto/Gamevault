<?php
$lines = file('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
foreach($lines as $k => $v) {
    if(stripos($v, 'Ganti Email') !== false) {
        echo ($k+1).': '.trim($v)."\n";
    }
}
