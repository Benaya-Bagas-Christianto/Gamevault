<?php
$f = 'public/css/app.css';
$content = file_get_contents($f);

// Replace variables
$replacements = [
    '/--c-ink:\s*#[0-9a-fA-F]{6};/' => '--c-ink:              #ffffff;',
    '/--c-canvas:\s*#[0-9a-fA-F]{6};/' => '--c-canvas:           #030800;',
    '/--c-surface-soft:\s*#[0-9a-fA-F]{6};/' => '--c-surface-soft:     rgba(30, 40, 25, 0.4);',
    '/--c-surface-elevated:\s*#[0-9a-fA-F]{6};/' => '--c-surface-elevated: #111111;',
    '/--c-hairline:\s*#[0-9a-fA-F]{6};/' => '--c-hairline:         rgba(118, 185, 0, 0.3);',
    '/--c-hairline-strong:\s*#[0-9a-fA-F]{6};/' => '--c-hairline-strong:  rgba(118, 185, 0, 0.6);',
    '/--c-body:\s*#[0-9a-fA-F]{6};/' => '--c-body:             #b0b0b0;'
];

foreach ($replacements as $pattern => $replacement) {
    $content = preg_replace($pattern, $replacement, $content);
}

// Add body animation
$bodyAnimation = '
body::before {
  content: "";
  position: fixed;
  top: 0; left: 0; right: 0; bottom: 0;
  background: radial-gradient(circle at 50% 50%, rgba(118,185,0,0.3) 0%, rgba(0,0,0,0) 65%);
  z-index: -2;
  animation: pulse-glow 8s infinite alternate ease-in-out;
}
body::after {
  content: "";
  position: fixed;
  top: 0; left: 0;
  width: 16px; height: 16px;
  background-color: var(--c-primary);
  z-index: -1;
}

@keyframes pulse-glow {
  0% { transform: scale(0.9); opacity: 0.6; }
  100% { transform: scale(1.1); opacity: 1; }
}
';

$content = str_replace('min-height: 100vh;'."\n".'}', 'min-height: 100vh;'."\n".'}' . "\n" . $bodyAnimation, $content);
file_put_contents($f, $content);

$bladeFiles = [
    'resources/views/home/partials/category_grid.blade.php',
    'resources/views/home/index.blade.php',
    'resources/views/home/store.blade.php',
    'resources/views/bantuan.blade.php'
];

foreach ($bladeFiles as $f) {
    if(file_exists($f)){
        $b = file_get_contents($f);
        $b = str_replace('background:var(--c-surface-soft);border-radius:', 'background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius:', $b);
        $b = str_replace('background:var(--c-surface-soft);border:1px solid', 'background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border:1px solid', $b);
        file_put_contents($f, $b);
    }
}
echo "Done.\n";
