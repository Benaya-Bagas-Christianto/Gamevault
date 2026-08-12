<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('games')->truncate();
DB::table('transaction_details')->truncate();
DB::table('carts')->truncate();
DB::table('wishlists')->truncate();
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Database wiped!\n";
