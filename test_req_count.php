<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$count = App\Models\Game::whereNotNull('pc_requirements')->count();
echo "Games with PC Requirements: " . $count . "\n";
