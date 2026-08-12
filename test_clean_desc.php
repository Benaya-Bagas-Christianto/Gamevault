<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
$game = Game::where('title', 'like', '%Witcher 3%')->first();
if ($game) {
    $desc = $game->description;
    
    // Convert common block tags to spaces before stripping tags to avoid mashed words
    $cleanHtml = str_replace(['<br>', '<br/>', '<br />', '</p>', '</h1>', '</h2>', '</h3>', '</div>'], ' ', $desc);
    $cleanHtml = html_entity_decode($cleanHtml);
    
    // Try to cut at "Tentang Game Ini" or "About This Game"
    $parts = preg_split('/(Tentang Game Ini|About This Game)/i', $cleanHtml);
    $short = strip_tags($parts[0]);
    
    if (trim($short) === '') {
        $short = strip_tags($cleanHtml);
    }
    
    // Clean up multiple spaces
    $short = preg_replace('/\s+/', ' ', $short);
    $short = trim($short);
    
    echo "Short Description:\n";
    echo \Illuminate\Support\Str::limit($short, 250) . "\n";
}
