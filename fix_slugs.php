<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use Illuminate\Support\Str;

$games = Game::all();
$fixed = 0;
foreach ($games as $game) {
    if (empty($game->slug)) {
        echo "Missing slug for: " . $game->title . "\n";
        $game->slug = Str::slug($game->title);
        
        if (empty($game->slug)) {
            $game->slug = 'game-' . $game->id;
        }
        
        $game->save();
        $fixed++;
    }
}
echo "Fixed $fixed slugs.\n";
