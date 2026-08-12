<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\RefundApprovedMail;
use App\Models\Library;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RefundController extends Controller
{
    public function index()
    {
        $pendingRefunds = TransactionDetail::with(['game', 'transaction.user'])
            ->where('refund_status', 'requested')
            ->latest('refund_requested_at')
            ->get();

        $processedRefunds = TransactionDetail::with(['game', 'transaction.user'])
            ->whereIn('refund_status', ['approved', 'rejected'])
            ->latest('refund_processed_at')
            ->take(50)
            ->get();

        return view('admin.refunds.index', compact('pendingRefunds', 'processedRefunds'));
    }

    public function approve(TransactionDetail $detail)
    {
        if ($detail->refund_status !== 'requested') {
            return back()->with('error', 'Status refund tidak valid.');
        }

        $detail->update([
            'refund_status'       => 'approved',
            'refund_processed_at' => now(),
        ]);

        // Revoke library access
        Library::where('user_id', $detail->transaction->user_id)
            ->where('game_id', $detail->game_id)
            ->delete();

        // Update transaction status if all details are refunded
        $transaction = $detail->transaction;
        $allRefunded = $transaction->details()->where('refund_status', '!=', 'approved')->doesntExist();
        if ($allRefunded) {
            $transaction->update(['status' => 'refunded']);
        }

        // Send email
        try {
            $user = $detail->transaction->user;
            Mail::to($user->email)->send(new RefundApprovedMail($detail, $user));
        } catch (\Exception $e) {
            // Log but don't fail
        }

        return back()->with('success', 'Refund disetujui dan lisensi game telah dicabut.');
    }

    public function reject(Request $request, TransactionDetail $detail)
    {
        if ($detail->refund_status !== 'requested') {
            return back()->with('error', 'Status refund tidak valid.');
        }

        $detail->update([
            'refund_status'       => 'rejected',
            'refund_processed_at' => now(),
        ]);

        return back()->with('success', 'Refund berhasil ditolak.');
    }
}
