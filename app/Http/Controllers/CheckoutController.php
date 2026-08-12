<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Mail\LicenseKeyMail;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Library;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized  = config('services.midtrans.is_sanitized');
        Config::$is3ds        = config('services.midtrans.is_3ds');
    }

    /**
     * Helper to calculate discount
     */
    private function calculateDiscount($cartItems)
    {
        $discount = 0;
        $baseGameIds = $cartItems->where('type', 'game')->pluck('id')->toArray();
        $discountedItems = [];

        foreach ($cartItems as $item) {
            if ($item->type === 'dlc' && in_array($item->parent_id, $baseGameIds)) {
                if (!in_array($item->id, $discountedItems)) {
                    $discount += $item->price * 0.15; // 15% off DLC
                    $discountedItems[] = $item->id;
                }
                if (!in_array($item->parent_id, $discountedItems)) {
                    $parentPrice = $cartItems->firstWhere('id', $item->parent_id)->price;
                    $discount += $parentPrice * 0.15; // 15% off Base Game
                    $discountedItems[] = $item->parent_id;
                }
            }
        }
        return $discount;
    }

    public function index()
    {
        $cartItems = Auth::user()->cartGames()->active()->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong.');
        }

        $subtotal = $cartItems->sum('price');
        $discount = $this->calculateDiscount($cartItems);
        $total = $subtotal - $discount;

        return view('checkout.index', compact('cartItems', 'total', 'subtotal', 'discount'));
    }

    /**
     * AJAX: Create transaction & return Snap token (no page redirect)
     */
    public function getToken(Request $request)
    {
        $user      = Auth::user();
        $cartItems = $user->cartGames()->active()->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['error' => 'Keranjang kamu kosong.'], 422);
        }

        $subtotal   = $cartItems->sum('price');
        $discount   = $this->calculateDiscount($cartItems);
        $total      = $subtotal - $discount;
        $orderId    = 'TG-' . strtoupper(Str::random(12)) . '-' . time();

        // Create transaction
        $transaction = Transaction::create([
            'user_id'      => $user->id,
            'order_id'     => $orderId,
            'status'       => 'pending',
            'total_amount' => $total,
        ]);

        // Create transaction details
        foreach ($cartItems as $game) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'game_id'        => $game->id,
                'price'          => $game->price,
            ]);
        }
        
        $itemDetails = $cartItems->map(fn ($game) => [
            'id'       => (string) $game->id,
            'price'    => (int) $game->price,
            'quantity' => 1,
            'name'     => substr($game->title, 0, 50),
        ])->toArray();

        if ($discount > 0) {
            $itemDetails[] = [
                'id'       => 'DISCOUNT',
                'price'    => -(int) $discount,
                'quantity' => 1,
                'name'     => 'Bundle Discount (15%)',
            ];
        }

        if ($total <= 0) {
            $this->markPaid($transaction, 'free');
            return response()->json([
                'is_free'  => true,
                'message'  => 'Game gratis berhasil diklaim!',
                'redirect' => route('library.index')
            ]);
        }

        // Build Midtrans payload
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $transaction->update(['payment_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage());
            $transaction->delete();
            return response()->json(['error' => 'Terjadi kesalahan payment gateway: ' . $e->getMessage()], 500);
        }

        return response()->json([
            'snap_token'   => $snapToken,
            'order_id'     => $orderId,
            'client_key'   => config('services.midtrans.client_key'),
        ]);
    }

    /**
     * AJAX: Finalize payment after Midtrans onSuccess callback
     */
    public function finalize(Request $request)
    {
        $orderId = $request->input('order_id');
        $transaction = Transaction::where('order_id', $orderId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($transaction->status === 'paid') {
            $request->session()->flash('success', 'Pembayaran berhasil! Game telah ditambahkan ke library Anda.');
            return response()->json(['success' => true, 'message' => 'Sudah diproses.']);
        }

        // Verify status with Midtrans API directly before trusting the frontend
        try {
            $statusResponse = \Midtrans\Transaction::status($orderId);
            $transactionStatus = $statusResponse->transaction_status ?? '';
            $fraudStatus = $statusResponse->fraud_status ?? 'accept';
            $paymentType = $statusResponse->payment_type ?? 'unknown';

            if (($transactionStatus === 'capture' || $transactionStatus === 'settlement') && $fraudStatus === 'accept') {
                $this->markPaid($transaction, $paymentType);
                $request->session()->flash('success', 'Pembayaran berhasil! Game telah ditambahkan ke library Anda.');
                return response()->json(['success' => true, 'message' => 'Pembayaran berhasil!']);
            }
        } catch (\Exception $e) {
            Log::error('Midtrans API verification failed in finalize: ' . $e->getMessage());
        }

        return response()->json([
            'success' => false, 
            'message' => 'Pembayaran belum terverifikasi dari server. Jika Anda sudah membayar, silakan tunggu beberapa saat.'
        ]);
    }

    public function process(Request $request)
    {
        $user      = Auth::user();
        $cartItems = $user->cartGames()->active()->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Keranjang kamu kosong.');
        }

        $subtotal   = $cartItems->sum('price');
        $discount   = $this->calculateDiscount($cartItems);
        $total      = $subtotal - $discount;
        $orderId    = 'TG-' . strtoupper(Str::random(12)) . '-' . time();

        // Create transaction
        $transaction = Transaction::create([
            'user_id'      => $user->id,
            'order_id'     => $orderId,
            'status'       => 'pending',
            'total_amount' => $total,
        ]);

        // Create transaction details
        foreach ($cartItems as $game) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'game_id'        => $game->id,
                'price'          => $game->price,
            ]);
        }
        
        $itemDetails = $cartItems->map(fn ($game) => [
            'id'       => (string) $game->id,
            'price'    => (int) $game->price,
            'quantity' => 1,
            'name'     => substr($game->title, 0, 50),
        ])->toArray();

        if ($discount > 0) {
            $itemDetails[] = [
                'id'       => 'DISCOUNT',
                'price'    => -(int) $discount,
                'quantity' => 1,
                'name'     => 'Bundle Discount (15%)',
            ];
        }

        if ($total <= 0) {
            $this->markPaid($transaction, 'free');
            return redirect()->route('library.index')->with('success', 'Game gratis berhasil diklaim!');
        }

        // Build Midtrans payload
        $params = [
            'transaction_details' => [
                'order_id'     => $orderId,
                'gross_amount' => (int) $total,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email'      => $user->email,
            ],
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            $transaction->update(['payment_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans error: ' . $e->getMessage());
            $transaction->delete();
            return back()->with('error', 'Terjadi kesalahan payment gateway. Coba lagi.');
        }

        return view('checkout.payment', compact('snapToken', 'transaction', 'cartItems', 'total'));
    }

    /**
     * Midtrans webhook callback
     */
    public function callback(Request $request)
    {
        try {
            $notification = new Notification();

            $orderId           = $notification->order_id;
            $transactionStatus = $notification->transaction_status;
            $paymentType       = $notification->payment_type;
            $fraudStatus       = $notification->fraud_status ?? 'accept';
            $grossAmount       = $notification->gross_amount;
            $signatureKey      = $notification->signature_key;
            $statusCode        = $notification->status_code;

            // Verify the signature key to prevent spoofing
            $serverKey = config('services.midtrans.server_key');
            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

            if ($expectedSignature !== $signatureKey) {
                Log::warning("Midtrans invalid signature: Expected {$expectedSignature}, got {$signatureKey}");
                return response()->json(['message' => 'Invalid signature'], 403);
            }

            $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

            if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
                if ($fraudStatus === 'accept') {
                    $this->markPaid($transaction, $paymentType);
                }
            } elseif ($transactionStatus === 'pending') {
                $transaction->update(['status' => 'pending']);
            } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
                $transaction->update(['status' => 'failed']);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans callback error: ' . $e->getMessage());
            return response()->json(['message' => 'Error'], 500);
        }

        return response()->json(['message' => 'OK']);
    }

    private function markPaid(Transaction $transaction, string $paymentType): void
    {
        if ($transaction->status === 'paid') return;

        $transaction->update([
            'status'       => 'paid',
            'payment_type' => $paymentType,
            'paid_at'      => now(),
        ]);

        $user = $transaction->user;

        foreach ($transaction->details as $detail) {
            // Generate license key
            $licenseKey = strtoupper(implode('-', str_split(Str::random(20), 5)));
            $detail->update(['license_key' => $licenseKey]);

            // Add to library
            Library::firstOrCreate([
                'user_id' => $user->id,
                'game_id' => $detail->game_id,
            ], [
                'transaction_detail_id' => $detail->id,
            ]);

            // Remove from cart and wishlist
            $user->carts()->where('game_id', $detail->game_id)->delete();
            $user->wishlists()->where('game_id', $detail->game_id)->delete();
        }

        // Send license email
        try {
            Mail::to($user->email)->send(new LicenseKeyMail($transaction, $user));
        } catch (\Exception $e) {
            Log::error('License email error: ' . $e->getMessage());
        }
    }
}
