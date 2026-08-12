<?php
use App\Models\Game;

$games = Game::all();
foreach ($games as $game) {
    $genres = $game->genre ?? [];
    $newGenres = [];
    foreach ($genres as $g) {
        $parts = explode(',', $g);
        foreach ($parts as $p) {
            $cleaned = ucwords(trim($p)); // Title Case
            if (!empty($cleaned) && !in_array($cleaned, $newGenres)) {
                $newGenres[] = $cleaned;
            }
        }
    }
    // Only save if there's a difference
    if ($genres !== $newGenres) {
        $game->genre = $newGenres;
        $game->save();
        echo "Updated game: {$game->title}\n";
    }
}
echo "Done.\n";
