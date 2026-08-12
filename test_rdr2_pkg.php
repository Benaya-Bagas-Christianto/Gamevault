<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;
use Illuminate\Support\Facades\Http;

$appId = 1174180;
$response = Http::get("https://store.steampowered.com/api/appdetails", [
    'appids' => $appId,
    'cc' => 'id',
    'l' => 'indonesian'
]);

$data = $response->json();
$appData = $data[$appId]['data'];
echo "API Name: " . $appData['name'] . "\n";
$model = Game::where('title', $appData['name'])->first();
if (!$model) {
    echo "Model not found for title: " . $appData['name'] . "\n";
} else {
    echo "Model found: {$model->title}\n";
    $packages = [];
    if (isset($appData['package_groups']) && count($appData['package_groups']) > 0) {
        $subs = $appData['package_groups'][0]['subs'] ?? [];
        foreach ($subs as $sub) {
            $discountPct = (int)$sub['percent_savings'];
            $price = $sub['price_in_cents_with_discount'] / 100;
            $originalPrice = $price;
            if ($discountPct > 0) {
                $originalPrice = round($price / (1 - ($discountPct / 100)));
            }
            $name = $sub['option_text'];
            $name = preg_replace('/ - <span class="discount_original_price">.*?<\/span>.*/', '', $name);
            $name = preg_replace('/ - Rp.*/', '', $name);
            $packages[] = [
                'id' => $sub['packageid'],
                'name' => $name,
                'price' => $price,
                'original_price' => $originalPrice,
                'discount_percent' => $discountPct
            ];
        }
        $model->packages = $packages;
        $model->save();
        echo "Saved packages!\n";
    }
}
