<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Transaction;
use App\Models\User;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGames        = Game::count();
        $totalUsers        = User::where('role', 'user')->count();
        $totalTransactions = Transaction::count();
        $totalRevenue      = Transaction::where('status', 'paid')->sum('total_amount');
        $pendingRefunds    = TransactionDetail::where('refund_status', 'requested')->count();

        // Monthly revenue (last 12 months)
        $monthlyRevenue = Transaction::where('status', 'paid')
            ->where('paid_at', '>=', now()->subMonths(11)->startOfMonth())
            ->select(
                DB::raw('YEAR(paid_at) as year'),
                DB::raw('MONTH(paid_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Build 12-month chart data
        $chartLabels = [];
        $chartData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $chartLabels[] = $date->format('M Y');
            $record = $monthlyRevenue->first(fn ($r) =>
                $r->year == $date->year && $r->month == $date->month
            );
            $chartData[] = $record ? (float) $record->total : 0;
        }

        // Recent transactions
        $recentTransactions = Transaction::with('user')
            ->latest()
            ->take(10)
            ->get();

        // Best selling games
        $bestSellers = Game::where('type', 'game')
            ->withCount(['transactionDetails as sold_count' => function ($q) {
                $q->whereHas('transaction', fn ($t) => $t->where('status', 'paid'));
            }])
            ->orderByDesc('sold_count')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'totalGames',
            'totalUsers',
            'totalTransactions',
            'totalRevenue',
            'pendingRefunds',
            'chartLabels',
            'chartData',
            'recentTransactions',
            'bestSellers'
        ));
    }
}
