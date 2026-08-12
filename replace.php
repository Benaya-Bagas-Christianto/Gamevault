<?php
$dirs = ['d:/Laragon/laragon/www/tokoGame/resources/views', 'd:/Laragon/laragon/www/tokoGame/app', 'd:/Laragon/laragon/www/tokoGame/config'];
function replaceInDir($dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            replaceInDir($path);
        } else {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($ext, ['php', 'html', 'js', 'css'])) {
                $content = file_get_contents($path);
                $original = $content;
                // Replace user-facing TokoGame
                $content = str_replace('TokoGame', 'GameVault', $content);
                $content = str_replace('tokogame', 'gamevault', $content);
                $content = str_replace('TOKOGAME', 'GAMEVAULT', $content);
                if ($content !== $original) {
                    file_put_contents($path, $content);
                    echo "Updated: $path\n";
                }
            }
        }
    }
}
foreach ($dirs as $dir) {
    replaceInDir($dir);
}
echo "done\n";
