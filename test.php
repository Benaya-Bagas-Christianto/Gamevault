<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
foreach(App\Models\GameGallery::where('type', 'video')->limit(5)->get() as $g) {
    echo $g->path . "\n";
}
