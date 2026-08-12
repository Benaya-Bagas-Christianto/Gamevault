<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function show(TransactionDetail $detail)
    {
        abort_unless($detail->transaction->user_id === Auth::id(), 403);
        $detail->load(['game', 'transaction']);
        return view('refund.show', compact('detail'));
    }

    public function request(Request $request, TransactionDetail $detail)
    {
        abort_unless($detail->transaction->user_id === Auth::id(), 403);

        if (! $detail->canRefund()) {
            return back()->with('error', 'Permintaan refund tidak valid. Pastikan game dibeli kurang dari 14 hari dan belum di-refund.');
        }

        $request->validate([
            'refund_reason' => 'required|string|min:20|max:500',
        ]);

        $detail->update([
            'refund_status'        => 'requested',
            'refund_reason'        => $request->refund_reason,
            'refund_requested_at'  => now(),
        ]);

        return back()->with('success', 'Permintaan refund kamu telah dikirim. Admin akan segera memprosesnya.');
    }

    public function cancel(Request $request, TransactionDetail $detail)
    {
        abort_unless($detail->transaction->user_id === Auth::id(), 403);

        if ($detail->refund_status === 'requested') {
            $detail->update([
                'refund_status' => 'none',
                'refund_reason' => null,
                'refund_requested_at' => null,
            ]);
            return back()->with('success', 'Permintaan refund dibatalkan.');
        }

        return back()->with('error', 'Tidak dapat membatalkan refund ini.');
    }
}
