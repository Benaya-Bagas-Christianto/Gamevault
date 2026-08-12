<?php
$files = [
    'resources/views/home/partials/category_grid.blade.php',
    'resources/views/home/index.blade.php',
    'resources/views/home/store.blade.php',
    'resources/views/bantuan.blade.php'
];

foreach ($files as $f) {
    if (file_exists($f)) {
        $content = file_get_contents($f);
        
        // For game cards and bantuan cards that I recently changed to var(--c-canvas)
        $content = preg_replace('/style="([^"]*)background:var\(--c-canvas\);([^"]*border:1px solid var\(--c-hairline\)[^"]*)"/', 'style="$1background:var(--c-surface-soft);$2"', $content);
        
        // For index.blade.php renderGameCard specific line
        $content = preg_replace('/style="background:var\(--c-canvas\);border-radius:var\(--r-sm\);overflow:hidden;border:1px solid var\(--c-hairline\);/', 'style="background:var(--c-surface-soft);border-radius:var(--r-sm);overflow:hidden;border:1px solid var(--c-hairline);', $content);
        
        file_put_contents($f, $content);
        echo "Updated $f\n";
    }
}
