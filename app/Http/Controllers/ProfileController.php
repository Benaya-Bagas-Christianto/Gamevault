<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Mail\OtpMail;
use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Rules\StrongPin;

class ProfileController extends Controller
{
    public function index()
    {
        $user         = Auth::user();
        $transactions = $user->transactions()->with('details.game')->latest()->get();
        return view('profile.index', compact('user', 'transactions'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:100',
        ]);

        $user->update(['name' => $request->name]);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar_base64' => 'required|string',
        ]);

        $user = Auth::user();
        $data = $request->avatar_base64;

        // Decode base64
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $matches)) {
            $data = substr($data, strpos($data, ',') + 1);
        }

        $imageData = base64_decode($data);

        // Delete old avatar
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $filename = 'avatars/' . $user->id . '-' . time() . '.jpg';
        Storage::disk('public')->makeDirectory('avatars');

        // Save and resize using Intervention Image
        $manager = new ImageManager(new Driver());
        $image = $manager->decodeBinary($imageData)
            ->cover(300, 300)
            ->encodeUsingFileExtension('jpg', 90);

        Storage::disk('public')->put($filename, $image->toString());

        $user->update(['avatar' => $filename]);

        return response()->json([
            'success' => true,
            'avatar_url' => $user->avatar_url,
        ]);
    }

    // --- AJAX PASSWORD METHODS ---
    public function ajaxSendPasswordOtp(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->letters()->numbers()],
        ]);
        $user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Password saat ini salah.']);
        }
        
        // Generate and send OTP
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otpCodes()->where('type', 'update_password')->delete();
        \App\Models\OtpCode::create([
            'user_id' => $user->id, 'email' => $user->email, 'code' => $code, 'type' => 'update_password', 'expires_at' => now()->addMinutes(5)
        ]);
        try { \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($code, $user->email)); } catch (\Exception $e) { return response()->json(['status' => 'error', 'message' => 'Gagal mengirim OTP.']); }
        
        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim ke email Anda.']);
    }

    public function ajaxVerifyPasswordAndUpdate(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string',
            'code'             => 'required|string|size:6',
        ]);
        $user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check($request->current_password, $user->password)) {
            return response()->json(['status' => 'error', 'message' => 'Password saat ini salah.']);
        }

        $otp = \App\Models\OtpCode::where('user_id', $user->id)->where('email', $user->email)->where('code', $request->code)->where('type', 'update_password')->whereNull('used_at')->where('expires_at', '>', now())->first();
        if (!$otp) return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa.']);
        
        $otp->update(['used_at' => now()]);
        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);
        try { \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SecurityNotificationMail('Perubahan Password', 'Password akun GameVault Anda baru saja diubah.')); } catch (\Exception $e) {}
        
        return response()->json(['status' => 'success', 'message' => 'Password berhasil diubah.']);
    }

    // --- AJAX PIN METHODS ---
    public function ajaxSendPinOtp(Request $request)
    {
        $request->validate([
            'old_pin' => 'required|digits:6',
            'new_pin' => ['required', 'digits:6', 'confirmed', new \App\Rules\StrongPin]
        ]);
        $user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check($request->old_pin, $user->pin)) {
            return response()->json(['status' => 'error', 'message' => 'PIN lama salah.']);
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otpCodes()->where('type', 'update_pin')->delete();
        \App\Models\OtpCode::create([
            'user_id' => $user->id, 'email' => $user->email, 'code' => $code, 'type' => 'update_pin', 'expires_at' => now()->addMinutes(5)
        ]);
        try { \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($code, $user->email)); } catch (\Exception $e) { return response()->json(['status' => 'error', 'message' => 'Gagal mengirim OTP.']); }
        
        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim.']);
    }

    public function ajaxVerifyPinAndUpdate(Request $request)
    {
        $request->validate([
            'old_pin' => 'required|digits:6',
            'new_pin' => 'required|digits:6',
            'code'    => 'required|string|size:6',
        ]);
        $user = Auth::user();
        if (!\Illuminate\Support\Facades\Hash::check($request->old_pin, $user->pin)) {
            return response()->json(['status' => 'error', 'message' => 'PIN lama salah.']);
        }

        $otp = \App\Models\OtpCode::where('user_id', $user->id)->where('email', $user->email)->where('code', $request->code)->where('type', 'update_pin')->whereNull('used_at')->where('expires_at', '>', now())->first();
        if (!$otp) return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa.']);
        
        $otp->update(['used_at' => now()]);
        $user->update(['pin' => \Illuminate\Support\Facades\Hash::make($request->new_pin)]);
        try { \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SecurityNotificationMail('Perubahan PIN', 'PIN akun GameVault Anda baru saja diubah.')); } catch (\Exception $e) {}
        
        return response()->json(['status' => 'success', 'message' => 'PIN berhasil diubah.']);
    }

    // --- AJAX EMAIL METHODS ---
    public function ajaxSendEmailOtp(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,' . Auth::id(),
        ]);
        $user = Auth::user();
        
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otpCodes()->where('type', 'update_email')->delete();
        \App\Models\OtpCode::create([
            'user_id' => $user->id, 'email' => $user->email, 'code' => $code, 'type' => 'update_email', 'expires_at' => now()->addMinutes(5)
        ]);
        try { \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($code, $user->email)); } catch (\Exception $e) { return response()->json(['status' => 'error', 'message' => 'Gagal mengirim OTP ke email lama.']); }
        
        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim ke email lama Anda.']);
    }

    public function ajaxVerifyEmailAndUpdate(Request $request)
    {
        $request->validate([
            'new_email' => 'required|email|unique:users,email,' . Auth::id(),
            'code'      => 'required|string|size:6',
        ]);
        $user = Auth::user();
        
        $otp = \App\Models\OtpCode::where('user_id', $user->id)->where('email', $user->email)->where('code', $request->code)->where('type', 'update_email')->whereNull('used_at')->where('expires_at', '>', now())->first();
        if (!$otp) return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa.']);
        
        $otp->update(['used_at' => now()]);
        
        $old_email = $user->email;
        $user->update(['email' => $request->new_email, 'email_changed_at' => now()]);
        
        try { \Illuminate\Support\Facades\Mail::to($request->new_email)->send(new \App\Mail\SecurityNotificationMail('Berhasil Ganti Email', 'Email akun GameVault Anda telah berhasil diubah ke email ini.')); } catch (\Exception $e) {}
        
        return response()->json(['status' => 'success', 'message' => 'Email berhasil diubah.']);
    }
    
    // --- AJAX FORGOT PIN METHODS ---
    public function ajaxSendForgotPinOtp(Request $request)
    {
        $user = Auth::user();
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otpCodes()->where('type', 'forgot_pin')->delete();
        \App\Models\OtpCode::create([
            'user_id' => $user->id, 'email' => $user->email, 'code' => $code, 'type' => 'forgot_pin', 'expires_at' => now()->addMinutes(5)
        ]);
        try { \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\OtpMail($code, $user->email)); } catch (\Exception $e) { return response()->json(['status' => 'error', 'message' => 'Gagal mengirim OTP.']); }
        return response()->json(['status' => 'success', 'message' => 'OTP berhasil dikirim ke email Anda.']);
    }

    public function ajaxVerifyForgotPin(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);
        $user = Auth::user();
        $otp = \App\Models\OtpCode::where('user_id', $user->id)->where('email', $user->email)->where('code', $request->code)->where('type', 'forgot_pin')->whereNull('used_at')->where('expires_at', '>', now())->first();
        if (!$otp) return response()->json(['status' => 'error', 'message' => 'Kode OTP salah atau kedaluwarsa.']);
        
        $otp->update(['used_at' => now()]);
        // Set session so they can reset pin
        session(['forgot_pin_verified' => true]);
        return response()->json(['status' => 'success', 'message' => 'OTP valid. Silakan masukkan PIN baru.']);
    }

    public function ajaxResetPin(Request $request)
    {
        if (!session('forgot_pin_verified')) return response()->json(['status' => 'error', 'message' => 'Akses ditolak.']);
        
        $request->validate(['new_pin' => ['required', 'digits:6', 'confirmed', new \App\Rules\StrongPin]]);
        $user = Auth::user();
        $user->update(['pin' => \Illuminate\Support\Facades\Hash::make($request->new_pin)]);
        session()->forget('forgot_pin_verified');
        
        try { \Illuminate\Support\Facades\Mail::to($user->email)->send(new \App\Mail\SecurityNotificationMail('Perubahan PIN', 'PIN Transaksi akun Anda baru saja diatur ulang (Reset).')); } catch (\Exception $e) {}
        
        return response()->json(['status' => 'success', 'message' => 'PIN berhasil diatur ulang!']);
    }
    public function checkStatuses(Request $request) {
        $ids = $request->ids;
        if(!$ids) return response()->json([]);

        $pending_ids = \App\Models\Transaction::whereIn('id', $ids)->where('status', 'pending')->pluck('id');
        if (count($pending_ids) > 0) {
            \Midtrans\Config::$serverKey = config('services.midtrans.server_key');
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production');
            
            foreach ($pending_ids as $id) {
                try {
                    $statusResponse = \Midtrans\Transaction::status($id);
                    if (isset($statusResponse->transaction_status) && $statusResponse->transaction_status != 'pending') {
                        $fakeRequest = new Request();
                        $fakeRequest->initialize([], [], [], [], [], ['CONTENT_TYPE' => 'application/json'], json_encode($statusResponse));
                        $checkoutCtrl = new CheckoutController();
                        $checkoutCtrl->notification($fakeRequest);
                    }
                } catch (\Exception $e) {
                }
            }
        }

        $statuses = \App\Models\Transaction::whereIn('id', $ids)->pluck('status', 'id');
        return response()->json($statuses);
    }

    public function getTransactionDetail($id)
    {
        $trx = \App\Models\Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$trx) {
            return response()->json(['status' => 'error', 'message' => 'Transaksi tidak ditemukan.'], 404);
        }

        $details = \App\Models\TransactionDetail::with('game')
            ->where('transaction_id', $id)
            ->get();

        $totalItems = $details->count();
        // Assume 'refund_status' != 'none' means requested or processed
        $refundedItems = $details->whereIn('refund_status', ['requested', 'approved'])->count();
        $refund_state = 'None';
        $adjusted_total = $trx->total_amount;
        
        if ($refundedItems > 0) {
            $refund_state = ($refundedItems == $totalItems) ? 'Full' : 'Partial';
            $adjusted_total = $details->where('refund_status', 'none')->sum('price');
        }

        $mappedItems = $details->map(function($detail) {
            return [
                'name' => $detail->game->title ?? $detail->game->name,
                'image' => $detail->game->image ?? $detail->game->cover_image,
                'harga_saat_beli' => $detail->price,
                'is_refunded' => in_array($detail->refund_status, ['requested', 'approved']) ? 1 : 0
            ];
        });

        return response()->json([
            'status' => 'success',
            'transaksi' => [
                'id' => $trx->id,
                'created_at' => $trx->created_at->format('d M Y, H:i'),
                'status' => $trx->status,
                'total_bayar' => $adjusted_total,
                'refund_state' => $refund_state
            ],
            'items' => $mappedItems
        ]);
    }

    public function cancelOrder($id)
    {
        $trx = \App\Models\Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($trx) {
            $trx->update(['status' => 'failed']);
            return redirect()->to(url()->previous() . '#history')->with('success', 'Pesanan berhasil dibatalkan.');
        }

        return redirect()->to(url()->previous() . '#history')->with('error', 'Pesanan tidak ditemukan atau sudah tidak bisa dibatalkan.');
    }

    public function cancelDuplicates($id)
    {
        $trx = \App\Models\Transaction::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$trx) return response()->json(['status' => 'error']);

        $gameIds = \App\Models\TransactionDetail::where('transaction_id', $trx->id)->pluck('game_id')->toArray();

        if (!empty($gameIds)) {
            $otherTrxIds = \App\Models\TransactionDetail::whereIn('game_id', $gameIds)
                ->where('transaction_id', '!=', $trx->id)
                ->whereHas('transaction', function($q) {
                    $q->where('user_id', Auth::id())->where('status', 'pending');
                })
                ->pluck('transaction_id')
                ->toArray();

            if (!empty($otherTrxIds)) {
                \App\Models\Transaction::whereIn('id', $otherTrxIds)->update(['status' => 'failed']);
            }
        }

        return response()->json(['status' => 'success', 'canceled_ids' => $otherTrxIds ?? []]);
    }
}
