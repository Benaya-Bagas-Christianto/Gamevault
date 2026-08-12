<?php
$c = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
preg_match('/@push\(\'scripts\'\)(.*?)@endpush/is', $c, $m);
file_put_contents('test_script.html', $m[0]);
