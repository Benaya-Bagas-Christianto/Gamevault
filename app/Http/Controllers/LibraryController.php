<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Library;
use Illuminate\Http\Request;

class LibraryController extends Controller
{
    public function index()
    {
        $libraryItems = Auth::user()->libraries()
            ->whereHas('game', function ($query) {
                $query->where('type', 'game');
            })
            ->with(['game', 'transactionDetail.transaction'])
            ->latest()
            ->get();

        return view('library.index', compact('libraryItems'));
    }

    public function toggleFavorite(Library $library)
    {
        abort_if($library->user_id !== Auth::id(), 403);
        
        $library->is_favorite = !$library->is_favorite;
        $library->save();

        return response()->json([
            'success' => true,
            'is_favorite' => $library->is_favorite
        ]);
    }

    public function showLicense(Request $request, Library $library)
    {
        abort_if($library->user_id !== Auth::id(), 403);

        $user = Auth::user();

        if (!$user->pin) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan buat PIN di halaman profil terlebih dahulu untuk melihat license key.'
            ], 400);
        }

        $request->validate([
            'pin' => 'required|digits:6',
        ]);

        if (!\Illuminate\Support\Facades\Hash::check($request->pin, $user->pin)) {
            return response()->json([
                'success' => false,
                'message' => 'PIN yang Anda masukkan salah. Silakan coba lagi.'
            ], 401);
        }

        // Return the license key
        return response()->json([
            'success' => true,
            'license_key' => $library->transactionDetail ? $library->transactionDetail->license_key : 'FREE-LICENSE-GAME'
        ]);
    }

    public function claimFree(Request $request, \App\Models\Game $game)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu.'], 401);
        }

        if ($game->price > 0) {
            return response()->json(['success' => false, 'message' => 'Game ini tidak gratis.'], 400);
        }

        $user = Auth::user();
        $exists = Library::where('user_id', $user->id)->where('game_id', $game->id)->exists();

        if ($exists) {
            return response()->json([
                'success' => true,
                'message' => $game->title . ' sudah ada di library Anda.',
                'title' => $game->title
            ]);
        }

        $transaction = \App\Models\Transaction::create([
            'user_id' => $user->id,
            'order_id' => 'FREE-' . strtoupper(uniqid()),
            'status' => 'paid',
            'total_amount' => 0,
            'payment_type' => 'free',
            'paid_at' => now(),
        ]);

        $detail = \App\Models\TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'game_id' => $game->id,
            'price' => 0,
            'license_key' => 'FREE-' . strtoupper(uniqid()),
        ]);

        Library::create([
            'user_id' => $user->id,
            'game_id' => $game->id,
            'transaction_detail_id' => $detail->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => $game->title . ' has been added to your account. It is now available in your GameVault Library.',
            'title' => $game->title
        ]);
    }
}
