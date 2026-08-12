<?php
$file = 'd:/Laragon/laragon/www/tokoGame/app/Http/Controllers/ProfileController.php';
$content = file_get_contents($file);

$newMethods = <<<EOT

    // --- AJAX FORGOT PIN METHODS ---
    public function ajaxSendForgotPinOtp(Request \$request)
    {
        \$user = Auth::user();
        \$code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        \$user->otpCodes()->where('type', 'forgot_pin')->delete();
        \App\Models\OtpCode::create([
            'user_id' => \$user->id, 'email' => \$user->email, 'code' => \$code, 'type' => 'forgot_pin', 'expires_at' => now()->addMinutes(5)
        ]);
        try { \Illuminate\Support\Facades\Mail::to(\$user->email)->send(new \App\Mail\OtpMail(\$code, \$user->email)); } catch (\Exception \$e) { return response()->json(['status' => 'error', 'message' => 'Gagal mengirim OTP.']); }
        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim ke email Anda.']);
    }

    public function ajaxVerifyForgotPin(Request \$request)
    {
        \$request->validate(['code' => 'required|string|size:6']);
        \$user = Auth::user();
        \$otp = \App\Models\OtpCode::where('user_id', \$user->id)->where('email', \$user->email)->where('code', \$request->code)->where('type', 'forgot_pin')->whereNull('used_at')->where('expires_at', '>', now())->first();
        if (!\$otp) return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa.']);
        
        \$otp->update(['used_at' => now()]);
        // Set session so they can reset pin
        session(['forgot_pin_verified' => true]);
        return response()->json(['status' => 'success', 'message' => 'OTP valid. Silakan masukkan PIN baru.']);
    }

    public function ajaxResetPin(Request \$request)
    {
        if (!session('forgot_pin_verified')) return response()->json(['status' => 'error', 'message' => 'Akses ditolak.']);
        
        \$request->validate(['new_pin' => ['required', 'digits:6', 'confirmed', new \App\Rules\StrongPin]]);
        \$user = Auth::user();
        \$user->update(['pin' => \Illuminate\Support\Facades\Hash::make(\$request->new_pin)]);
        session()->forget('forgot_pin_verified');
        
        try { \Illuminate\Support\Facades\Mail::to(\$user->email)->send(new \App\Mail\SecurityNotificationMail('Perubahan PIN', 'PIN Transaksi akun Anda baru saja diatur ulang (Reset).')); } catch (\Exception \$e) {}
        
        return response()->json(['status' => 'success', 'message' => 'PIN berhasil diatur ulang!']);
    }
EOT;

$content = str_replace("public function checkStatuses(Request \$request)", $newMethods . "\n    public function checkStatuses(Request \$request)", $content);

file_put_contents($file, $content);
echo "ProfileController updated with Forgot PIN methods.\n";
