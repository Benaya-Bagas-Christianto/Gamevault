<?php
$c = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index_backup.blade.php');
$p = strpos($c, '<script');
if($p !== false) {
    file_put_contents('d:/Laragon/laragon/www/tokoGame/extracted_scripts.txt', substr($c, $p));
    echo 'Saved.';
} else {
    echo 'Not found.';
}
