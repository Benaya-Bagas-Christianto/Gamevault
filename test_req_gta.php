<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$game = App\Models\Game::where('title', 'like', '%Grand Theft Auto V%')->first();
if ($game) {
    echo "GTA V:\n";
    print_r($game->pc_requirements);
}
