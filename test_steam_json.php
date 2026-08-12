<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Http;

$response = Http::get("https://store.steampowered.com/api/appdetails", [
    'appids' => 1174180,
    'cc' => 'id',
    'l' => 'indonesian'
]);
$data = $response->json();
$appData = $data[1174180]['data'];
echo "Raw release date string: " . $appData['release_date']['date'] . "\n";
echo "Parsed with strtotime: " . strtotime($appData['release_date']['date']) . "\n";
