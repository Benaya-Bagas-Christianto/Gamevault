<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use App\Models\GameGallery;
use Illuminate\Support\Facades\Http;

$appIds = [
    271590, 1091500, 1245620, 1174180, 1086940, 1593500, 2050650, 1888930, 1817070, 1817190,
    1151640, 2420110, 1190460, 2215430, 1222670, 252490, 230410, 1085660, 1172470, 730,
    578080, 1623730, 440, 1938090, 1063730, 2358720, 550, 304930, 1599340, 236390,
    2246340, 553850, 105600, 2694490, 431960, 291550, 4000, 359550, 552990, 346110,
    413150, 242760, 1491000, 238960, 218620, 291480, 901583, 899770, 340, 381210,
    990080, 438100, 292030, 945360, 1097150, 49520, 227300, 892970, 739630, 444090,
    1203220, 320, 70, 582010, 227940, 252950, 108600, 1966720, 1517290, 1240440,
    648800, 386360, 444200, 10, 240, 3164500, 755790, 400, 550650, 322330,
    239140, 477160, 251570, 100, 1665460, 417910, 433850, 594650, 814380, 1116170,
    220, 107410, 1326470, 219990, 255710, 261550, 8930, 304050, 289070, 377160,
    504230, 211820
];

function fetchAndSaveGalleries($appId) {
    try {
        $response = Http::get("https://store.steampowered.com/api/appdetails", [
            'appids' => $appId,
            'cc' => 'id',
            'l' => 'indonesian'
        ]);

        if (!$response->successful()) return;
        $data = $response->json();
        if (!isset($data[$appId]['success']) || !$data[$appId]['success']) return;
        
        $appData = $data[$appId]['data'];
        $model = Game::where('title', $appData['name'])->first();
        if ($model) {
            echo "Updating galleries for {$model->title}...\n";
            GameGallery::where('game_id', $model->id)->delete();

            if (isset($appData['movies'])) {
                foreach ($appData['movies'] as $movie) {
                    $path = $movie['mp4']['max'] ?? ($movie['webm']['max'] ?? ($movie['mp4']['480'] ?? ($movie['webm']['480'] ?? ($movie['hls_h264'] ?? null))));
                    if ($path) {
                        GameGallery::create([
                            'game_id' => $model->id,
                            'type' => 'video',
                            'path' => $path,
                            'thumbnail' => $movie['thumbnail'] ?? null
                        ]);
                    }
                }
            }
            if (isset($appData['screenshots'])) {
                foreach ($appData['screenshots'] as $ss) {
                    GameGallery::create([
                        'game_id' => $model->id,
                        'type' => 'image',
                        'path' => $ss['path_full'] ?? $ss['path_thumbnail']
                    ]);
                }
            }

            if (isset($appData['dlc'])) {
                $count = 0;
                foreach ($appData['dlc'] as $dlcId) {
                    if ($count >= 10) break;
                    fetchAndSaveGalleries($dlcId);
                    $count++;
                    usleep(500000); // 0.5 sec sleep
                }
            }
        }
    } catch (\Exception $e) {
        echo "Error on $appId: " . $e->getMessage() . "\n";
    }
}

$limit = 100;
$total = 0;
foreach ($appIds as $appId) {
    if ($total >= $limit) break;
    fetchAndSaveGalleries($appId);
    $total++;
    usleep(500000);
}
echo "All done!\n";
