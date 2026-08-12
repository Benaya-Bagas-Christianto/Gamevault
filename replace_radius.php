<?php
$f = 'd:\Laragon\laragon\www\tokoGame\resources\views\game\show.blade.php';
$c = file_get_contents($f);
$c = preg_replace('/border-radius:\s*(12px|8px|6px|4px|10px)/i', 'border-radius: var(--r-sm)', $c);
file_put_contents($f, $c);
