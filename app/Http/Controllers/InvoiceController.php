<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function download(Request $request, Transaction $transaction)
    {
        // Only the owner can download
        abort_if($transaction->user_id !== Auth::id(), 403);
        abort_if(!in_array($transaction->status, ['paid', 'success']), 404, 'Invoice hanya tersedia untuk transaksi yang sudah dibayar.');

        $user = Auth::user();
        
        // Check if user has set up PIN
        if (!$user->pin) {
            return response()->json([
                'success' => false,
                'message' => 'Silakan buat PIN di halaman profil terlebih dahulu untuk mengunduh invoice.'
            ], 400);
        }

        // Validate PIN input
        $request->validate([
            'pin' => 'required|digits:6',
        ]);

        // Verify PIN matches
        if (!\Illuminate\Support\Facades\Hash::check($request->pin, $user->pin)) {
            return response()->json([
                'success' => false,
                'message' => 'PIN yang Anda masukkan salah. Silakan coba lagi.'
            ], 401);
        }

        // PIN verified, generate PDF
        $transaction->load(['details.game', 'user']);

        $pdf = Pdf::loadView('pdf.invoice', compact('transaction'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('invoice-' . $transaction->order_id . '.pdf');
    }
}
