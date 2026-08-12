<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user', 'details.game'])->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));
            });
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.transactions.index', compact('transactions'));
    }

    public function reportPdf(Request $request)
    {
        $query = Transaction::with(['user', 'details.game'])
            ->where('status', 'paid')
            ->latest('paid_at');

        if ($from = $request->get('from')) {
            $query->whereDate('paid_at', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('paid_at', '<=', $to);
        }

        $transactions = $query->get();
        $totalRevenue = $transactions->sum('total_amount');

        $pdf = Pdf::loadView('pdf.transaction-report', compact('transactions', 'totalRevenue'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('laporan-transaksi-' . now()->format('Y-m-d') . '.pdf');
    }
    public function invoice(Transaction $transaction)
    {
        $transaction->load(['details.game', 'user']);

        $pdf = Pdf::loadView('pdf.invoice', compact('transaction'))
            ->setPaper('A4', 'portrait');

        return $pdf->download('invoice-' . $transaction->order_id . '.pdf');
    }
}
