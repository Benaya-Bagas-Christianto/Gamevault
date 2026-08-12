<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$game = App\Models\Game::where('title', 'like', '%Odyssey%')->first();
if ($game) {
    echo "Min:\n" . $game->pc_requirements_minimum . "\n";
    echo "Rec:\n" . $game->pc_requirements_recommended . "\n";
}
