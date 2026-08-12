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

echo "Fixing packages...\n";
foreach ($appIds as $appId) {
    try {
        $response = Http::timeout(10)->get("https://store.steampowered.com/api/appdetails?appids={$appId}&cc=id&l=indonesian");
        $data = $response->json();
        
        if (isset($data[$appId]['success']) && $data[$appId]['success']) {
            $appData = $data[$appId]['data'];
            
            $packages = [];
            if (isset($appData['package_groups']) && count($appData['package_groups']) > 0) {
                $subs = $appData['package_groups'][0]['subs'] ?? [];
                foreach ($subs as $sub) {
                    $pkgDiscountPct = (int)$sub['percent_savings'];
                    if ($pkgDiscountPct === 0 && isset($sub['percent_savings_text'])) {
                        $pkgDiscountPct = (int)preg_replace('/[^0-9]/', '', $sub['percent_savings_text']);
                    }
                    $pkgPrice = $sub['price_in_cents_with_discount'] / 100;
                    $pkgOriginalPrice = $pkgPrice;
                    if ($pkgDiscountPct > 0) {
                        $pkgOriginalPrice = round($pkgPrice / (1 - ($pkgDiscountPct / 100)));
                    }
                    
                    $name = $sub['option_text'];
                    $name = preg_replace('/ - <span class="discount_original_price">.*?<\/span>.*/', '', $name);
                    $name = preg_replace('/ - Rp.*/', '', $name);

                    $packages[] = [
                        'id' => $sub['packageid'],
                        'name' => $name,
                        'price' => $pkgPrice,
                        'original_price' => $pkgOriginalPrice,
                        'discount_percent' => $pkgDiscountPct
                    ];
                }
            }
            
            if (count($packages) > 0) {
                $title = $appData['name'];
                $game = Game::where('title', $title)->first();
                if ($game) {
                    $game->update(['packages' => $packages]);
                    echo "Updated packages for $title\n";
                }
            }
        }
    } catch (\Exception $e) {
    }
}
echo "Done!\n";
