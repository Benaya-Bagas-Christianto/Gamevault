<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
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

echo "Fixing prices for base games...\n";
foreach ($appIds as $appId) {
    try {
        $response = Http::timeout(10)->get("https://store.steampowered.com/api/appdetails?appids={$appId}&cc=id&l=indonesian");
        $data = $response->json();
        
        if (isset($data[$appId]['success']) && $data[$appId]['success']) {
            $appData = $data[$appId]['data'];
            
            $price = 0;
            $discountPercent = 0;
            $originalPrice = null;
            
            if (isset($appData['is_free']) && $appData['is_free']) {
                $price = 0;
            } elseif (isset($appData['price_overview'])) {
                $po = $appData['price_overview'];
                $price = $po['final'] / 100;
                
                if (isset($po['discount_percent']) && $po['discount_percent'] > 0) {
                    $discountPercent = $po['discount_percent'];
                    $originalPrice = $po['initial'] / 100;
                } elseif (isset($po['initial']) && $po['initial'] > $po['final']) {
                    $originalPrice = $po['initial'] / 100;
                    $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
                }
            }
            
            $title = $appData['name'];
            $game = Game::where('title', $title)->first();
            if ($game) {
                $game->update([
                    'price' => $price,
                    'original_price' => $originalPrice,
                    'discount_percent' => $discountPercent
                ]);
                echo "Updated $title: Price=$price, Orig=$originalPrice, Disc=$discountPercent%\n";
            }
        }
    } catch (\Exception $e) {
        echo "Error on $appId: " . $e->getMessage() . "\n";
    }
}
echo "Done!\n";
