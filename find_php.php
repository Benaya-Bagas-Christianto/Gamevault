<?php
$lines = file('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
foreach($lines as $k => $v) {
    if(strpos($v, '<?php') !== false) {
        echo ($k+1) . ': ' . $v;
    }
}
