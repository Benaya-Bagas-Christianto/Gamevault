<?php
$file = 'd:/Laragon/laragon/www/tokoGame/routes/web.php';
$content = file_get_contents($file);

$routes = <<<EOT
    Route::post('/profile/ajax/pin/forgot-otp', [ProfileController::class, 'ajaxSendForgotPinOtp'])->name('profile.ajax.pin.forgot.send');
    Route::post('/profile/ajax/pin/forgot-verify', [ProfileController::class, 'ajaxVerifyForgotPin'])->name('profile.ajax.pin.forgot.verify');
    Route::post('/profile/ajax/pin/reset', [ProfileController::class, 'ajaxResetPin'])->name('profile.ajax.pin.reset');
EOT;

$content = str_replace(
    "Route::post('/profile/ajax/email/verify-and-update', [ProfileController::class, 'ajaxVerifyEmailAndUpdate'])->name('profile.ajax.email.verify');", 
    "Route::post('/profile/ajax/email/verify-and-update', [ProfileController::class, 'ajaxVerifyEmailAndUpdate'])->name('profile.ajax.email.verify');\n" . $routes, 
    $content
);

file_put_contents($file, $content);
echo "Routes added to web.php";
