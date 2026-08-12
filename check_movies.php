<?php
require 'vendor/autoload.php';
use Illuminate\Support\Facades\Http;
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$r = Http::get('https://store.steampowered.com/api/appdetails?appids=447040&cc=id')->json();
if(isset($r['447040']['data']['movies'])) {
    foreach($r['447040']['data']['movies'] as $movie) {
        print_r($movie);
        break;
    }
}
