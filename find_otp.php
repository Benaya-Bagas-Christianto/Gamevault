<?php
$c = file('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
$count = 0;
foreach($c as $k=>$v){
    if(stripos($v,'otp')!==false || stripos($v,'btn-otp')!==false) {
        echo ($k+1).': '.trim($v)."\n";
        if(++$count >= 30) break;
    }
}
