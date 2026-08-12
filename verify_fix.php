<?php
$c = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');

// Check: are there any remaining modal-overlay class references?
$count = substr_count($c, 'class="modal-overlay"');
echo "Remaining modal-overlay class instances: $count\n";

// Check: profile-modal exists?
$count2 = substr_count($c, 'class="profile-modal"');
echo "profile-modal class instances: $count2\n";

// Check: tab-content elements with display:none
preg_match_all('/id="tab-[^"]*"[^>]*display:none/', $c, $m);
echo "Tab content with display:none: ".count($m[0])."\n";
foreach($m[0] as $match) echo "  - $match\n";

// Lines around button
$lines = file('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
echo "\n=== GANTI EMAIL BUTTON ===\n";
foreach($lines as $k => $v) {
    if(stripos($v, 'btn-ganti-email') !== false || stripos($v, 'Ganti Email') !== false) {
        echo ($k+1).': '.trim($v)."\n";
    }
}
