<?php
require __DIR__.'/vendor/autoload.php';
$file = __DIR__.'/app/Http/Controllers/ProfileController.php';
$content = file_get_contents($file);

// We want to replace from public function updatePassword down to public function resetPin
$startStr = "    public function updatePassword(Request \$request)";
$endStr = "    public function checkStatuses(Request \$request) {";

$startPos = strpos($content, $startStr);
$endPos = strpos($content, $endStr);

if ($startPos !== false && $endPos !== false) {
    $before = substr($content, 0, $startPos);
    $after = substr($content, $endPos);

    $newMethods = <<<EOT
    // --- AJAX PASSWORD METHODS ---
    public function ajaxSendPasswordOtp(Request \$request)
    {
        \$request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()],
        ]);
        \$user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check(\$request->current_password, \$user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Password saat ini salah.']);
        }
        
        // Generate and send OTP
        \$code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        \$user->otpCodes()->where('type', 'update_password')->delete();
        \App\Models\OtpCode::create([
            'user_id' => \$user->id, 'email' => \$user->email, 'code' => \$code, 'type' => 'update_password', 'expires_at' => now()->addMinutes(5)
        ]);
        try { \Illuminate\Support\Facades\Mail::to(\$user->email)->send(new \App\Mail\OtpMail(\$code, \$user->email)); } catch (\Exception \$e) { return response()->json(['status' => 'error', 'message' => 'Gagal mengirim OTP.']); }
        
        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim ke email Anda.']);
    }

    public function ajaxVerifyPasswordAndUpdate(Request \$request)
    {
        \$request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string',
            'code'             => 'required|string|size:6',
        ]);
        \$user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check(\$request->current_password, \$user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Password saat ini salah.']);
        }

        \$otp = \App\Models\OtpCode::where('user_id', \$user->id)->where('email', \$user->email)->where('code', \$request->code)->where('type', 'update_password')->whereNull('used_at')->where('expires_at', '>', now())->first();
        if (!\$otp) return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa.']);
        
        \$otp->update(['used_at' => now()]);
        \$user->update(['password' => \Illuminate\Support\Facades\Hash::make(\$request->password)]);
        try { \Illuminate\Support\Facades\Mail::to(\$user->email)->send(new \App\Mail\SecurityNotificationMail('Perubahan Password', 'Password akun TokoGame Anda baru saja diubah.')); } catch (\Exception \$e) {}
        
        return response()->json(['status' => 'success', 'message' => 'Password berhasil diubah.']);
    }

    // --- AJAX PIN METHODS ---
    public function ajaxSendPinOtp(Request \$request)
    {
        \$request->validate([
            'old_pin' => 'required|digits:6',
            'new_pin' => ['required', 'digits:6', 'confirmed', new \App\Rules\StrongPin]
        ]);
        \$user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check(\$request->old_pin, \$user->pin)) {
            return response()->json(['status' => 'error', 'message' => 'PIN lama salah.']);
        }

        \$code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        \$user->otpCodes()->where('type', 'update_pin')->delete();
        \App\Models\OtpCode::create([
            'user_id' => \$user->id, 'email' => \$user->email, 'code' => \$code, 'type' => 'update_pin', 'expires_at' => now()->addMinutes(5)
        ]);
        try { \Illuminate\Support\Facades\Mail::to(\$user->email)->send(new \App\Mail\OtpMail(\$code, \$user->email)); } catch (\Exception \$e) { return response()->json(['status' => 'error', 'message' => 'Gagal mengirim OTP.']); }
        
        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim.']);
    }

    public function ajaxVerifyPinAndUpdate(Request \$request)
    {
        \$request->validate([
            'old_pin' => 'required|digits:6',
            'new_pin' => 'required|digits:6',
            'code'    => 'required|string|size:6',
        ]);
        \$user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check(\$request->old_pin, \$user->pin)) {
            return response()->json(['status' => 'error', 'message' => 'PIN lama salah.']);
        }

        \$otp = \App\Models\OtpCode::where('user_id', \$user->id)->where('email', \$user->email)->where('code', \$request->code)->where('type', 'update_pin')->whereNull('used_at')->where('expires_at', '>', now())->first();
        if (!\$otp) return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa.']);
        
        \$otp->update(['used_at' => now()]);
        \$user->update(['pin' => \Illuminate\Support\Facades\Hash::make(\$request->new_pin)]);
        try { \Illuminate\Support\Facades\Mail::to(\$user->email)->send(new \App\Mail\SecurityNotificationMail('Perubahan PIN', 'PIN akun TokoGame Anda baru saja diubah.')); } catch (\Exception \$e) {}
        
        return response()->json(['status' => 'success', 'message' => 'PIN berhasil diubah.']);
    }

    // --- AJAX EMAIL METHODS ---
    public function ajaxSendEmailOtp(Request \$request)
    {
        \$request->validate([
            'new_email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);
        \$user = Auth::user();
        
        \$code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        \$user->otpCodes()->where('type', 'update_email')->delete();
        \App\Models\OtpCode::create([
            'user_id' => \$user->id, 'email' => \$user->email, 'code' => \$code, 'type' => 'update_email', 'expires_at' => now()->addMinutes(5)
        ]);
        try { \Illuminate\Support\Facades\Mail::to(\$user->email)->send(new \App\Mail\OtpMail(\$code, \$user->email)); } catch (\Exception \$e) { return response()->json(['status' => 'error', 'message' => 'Gagal mengirim OTP ke email lama.']); }
        
        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim ke email lama Anda.']);
    }

    public function ajaxVerifyEmailAndUpdate(Request \$request)
    {
        \$request->validate([
            'new_email' => 'required|email|unique:users,email,' . Auth::id(),
            'code'      => 'required|string|size:6',
        ]);
        \$user = Auth::user();
        
        \$otp = \App\Models\OtpCode::where('user_id', \$user->id)->where('email', \$user->email)->where('code', \$request->code)->where('type', 'update_email')->whereNull('used_at')->where('expires_at', '>', now())->first();
        if (!\$otp) return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa.']);
        
        \$otp->update(['used_at' => now()]);
        
        \$old_email = \$user->email;
        \$user->update(['email' => \$request->new_email, 'email_changed_at' => now()]);
        
        try { \Illuminate\Support\Facades\Mail::to(\$request->new_email)->send(new \App\Mail\SecurityNotificationMail('Berhasil Ganti Email', 'Email akun TokoGame Anda telah berhasil diubah ke email ini.')); } catch (\Exception \$e) {}
        
        return response()->json(['status' => 'success', 'message' => 'Email berhasil diubah.']);
    }

EOT;

    $final = $before . $newMethods . $after;
    file_put_contents($file, $final);
    echo "ProfileController updated successfully.";
} else {
    echo "Could not find bounds in ProfileController.";
}
