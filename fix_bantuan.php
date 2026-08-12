<?php
$f = 'resources/views/bantuan.blade.php';
$content = file_get_contents($f);
$content = preg_replace('/class="card-dark" style="([^"]*)"/', 'style="background:var(--c-surface-soft);border:1px solid var(--c-hairline);$1"', $content);
file_put_contents($f, $content);
echo "Fixed $f\n";
