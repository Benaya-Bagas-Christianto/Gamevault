<?php
$file = 'd:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php';
$content = file_get_contents($file);

// Replace the offending route calls with '#', so the page can load
$content = str_replace("{{ route('profile.pin.forgot.otp') }}", "/profile/ajax/pin/forgot-otp", $content);
$content = str_replace("{{ route('profile.pin.forgot.verify') }}", "/profile/ajax/pin/forgot-verify", $content);
$content = str_replace("{{ route(\"profile.pin.forgot.otp\") }}", "/profile/ajax/pin/forgot-otp", $content);
$content = str_replace("{{ route(\"profile.pin.forgot.verify\") }}", "/profile/ajax/pin/forgot-verify", $content);

file_put_contents($file, $content);
echo "Routes replaced.";
