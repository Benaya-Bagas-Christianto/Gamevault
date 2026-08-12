<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

\App\Models\Game::where('title', 'like', '%Witcher 3%')->update(['discount_percent' => 80]);

echo "Updated!\n";
