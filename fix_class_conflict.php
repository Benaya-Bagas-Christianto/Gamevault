<?php
$c = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');

// Replace class names in HTML - modal-overlay -> profile-modal-overlay, modal-box -> profile-modal-box, modal-close -> profile-modal-close
$c = str_replace('class="modal-overlay"', 'class="profile-modal-overlay"', $c);
$c = str_replace("class='modal-overlay'", "class='profile-modal-overlay'", $c);
$c = str_replace('class="modal-box"', 'class="profile-modal-box"', $c);
$c = str_replace('class="modal-box text-center"', 'class="profile-modal-box text-center"', $c);
$c = str_replace('class="modal-close"', 'class="profile-modal-close"', $c);

// Replace in CSS
$c = str_replace('.modal-overlay {', '.profile-modal-overlay {', $c);
$c = str_replace('.modal-overlay.active {', '.profile-modal-overlay.active {', $c);
$c = str_replace('.modal-box {', '.profile-modal-box {', $c);
$c = str_replace('.modal-close {', '.profile-modal-close {', $c);

// Replace in JS - getElementById and style.display calls to openEmailModal etc
// The closeModal function uses getElementById which is fine
// But the className references in showSweetAlert in app.blade uses 'modal-overlay' - that's separate

// Fix JS references to use profile-modal-overlay for showing/hiding
// The functions openEmailModal, closeModal use getElementById which returns the specific element - that's fine
// But make sure closeModal and show functions work with profile-modal-overlay

file_put_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php', $c);
echo "Done. Replaced modal class names.\n";

// Verify
$lines = file('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
foreach($lines as $k => $v) {
    if(stripos($v, 'modal-overlay') !== false || stripos($v, 'modal-box') !== false || stripos($v, 'modal-close') !== false) {
        echo ($k+1).': '.trim($v)."\n";
    }
}
