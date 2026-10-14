<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Game;
use App\Models\GameGallery;
use Illuminate\Support\Facades\Http;

echo "Mengambil video dari Steam API...\n";

$games = Game::all();
foreach($games as $game) {
    $video = GameGallery::where('game_id', $game->id)->where('type', 'video')->first();
    if(!$video && $game->steam_appid) {
        echo "Mengambil video untuk {$game->title} (AppID: {$game->steam_appid})...\n";
        $response = Http::get("https://store.steampowered.com/api/appdetails", [
            'appids' => $game->steam_appid,
            'cc' => 'id',
            'l' => 'indonesian'
        ]);
        if($response->successful()) {
            $data = $response->json();
            $appData = $data[$game->steam_appid]['data'] ?? null;
            if($appData && isset($appData['movies'])) {
                $movie = $appData['movies'][0];
                $path = $movie['hls_h264'] ?? ($movie['dash_h264'] ?? null);
                if($path) {
                    GameGallery::create([
                        'game_id' => $game->id,
                        'type' => 'video',
                        'path' => $path,
                        'thumbnail' => $movie['thumbnail'] ?? null
                    ]);
                    echo "=> Berhasil menambahkan video untuk {$game->title}\n";
                } else {
                    echo "=> Tidak ada video format HLS/DASH ditemukan.\n";
                }
            } else {
                echo "=> Game ini tidak memiliki trailer di Steam.\n";
            }
        }
    }
}
echo "Selesai.\n";
