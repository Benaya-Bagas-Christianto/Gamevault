<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
use App\Models\Game;
$g = Game::where('slug', 'watch-dogs-2')->first();
if($g) {
    print_r($g->galleries()->get()->toArray());
}
