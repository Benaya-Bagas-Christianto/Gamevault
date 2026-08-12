<?php
foreach(glob('d:/Laragon/laragon/www/tokoGame/storage/framework/views/*.php') as $f) {
    if(strpos(file_get_contents($f), 'Ganti Email') !== false) {
        echo $f . "\n";
    }
}
