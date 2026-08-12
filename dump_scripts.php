<?php
$c = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index_backup.blade.php');
preg_match_all('/<script.*?>.*?<\/script>/is', $c, $m);
file_put_contents('scripts_dump.txt', implode("\n\n===================\n\n", $m[0]));
