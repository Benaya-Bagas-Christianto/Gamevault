<?php
$c = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
echo 'foreach: ' . substr_count($c, '@foreach') . "\n";
echo 'endforeach: ' . substr_count($c, '@endforeach') . "\n";
echo 'if: ' . substr_count($c, '@if') . "\n";
echo 'endif: ' . substr_count($c, '@endif') . "\n";
