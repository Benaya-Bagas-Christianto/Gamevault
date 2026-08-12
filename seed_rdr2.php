<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Game;

$g = Game::where('title', 'Red Dead Redemption 2')->first();
if ($g) {
    $g->packages = [
        ['id'=>402134,'name'=>'Red Dead Redemption 2','price'=>219750,'original_price'=>879000,'discount_percent'=>75],
        ['id'=>412454,'name'=>'Red Dead Redemption 2: Ultimate Edition','price'=>290000,'original_price'=>1450000,'discount_percent'=>80]
    ];
    $g->save();
    echo "Saved RDR2 packages!\n";
}
