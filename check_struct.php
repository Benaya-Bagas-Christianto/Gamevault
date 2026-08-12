<?php
$lines = file('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
foreach($lines as $k => $v) {
    if(preg_match('/(@push|@endpush|<script|<\/script|<style|<\/style|@section|@endsection)/i', $v)) {
        echo ($k+1) . ': ' . trim($v) . "\n";
    }
}
