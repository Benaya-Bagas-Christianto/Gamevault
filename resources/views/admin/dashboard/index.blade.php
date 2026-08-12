@extends('layouts.admin')

@section('page_title', 'Dashboard')

@push('styles')
<style>
    .dash-grid {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 22px;
        margin-bottom: 24px;
    }
    @media (max-width: 1100px) { .dash-grid { grid-template-columns: 1fr; } }

    @media (max-width: 900px) {
        .desktop-table-wrap { display: none !important; }
        .mobile-cards-wrap { display: block !important; }
    }
    @media (min-width: 901px) {
        .mobile-cards-wrap { display: none !important; }
    }
    /* Mobile Card Styles */
    .game-mobile-card {
        background: #111;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-md);
        padding: 16px;
        margin-bottom: 16px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .game-mobile-header { display: flex; align-items: flex-start; gap: 14px; }
    .game-mobile-info { flex: 1; min-width: 0; }
    .game-mobile-title { font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .game-mobile-dev { font-size: 11.5px; color: #9ca3af; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .game-mobile-footer { display: flex; flex-direction: column; gap: 12px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px; }
    
    .bestseller-mobile-card {
        background: #111;
        border: 1px solid var(--border-color);
        padding: 12px;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 12px;
    }
</style>
@endpush

@section('content')

{{-- Pending Refund Alert --}}
@if(isset($pendingRefunds) && $pendingRefunds > 0)
<div class="alert alert-warning" role="alert">
    <i class="fa-solid fa-triangle-exclamation"></i>
    <div>
        Ada <strong>{{ $pendingRefunds }}</strong> permintaan refund menunggu persetujuan.
        <a href="{{ route('admin.refunds.index') }}">Lihat sekarang &rarr;</a>
    </div>
</div>
@endif

{{-- ===================== STAT CARDS ===================== --}}
<div class="stat-grid">

    <div class="stat-card" style="--card-accent:#76b900; --icon-bg:rgba(118,185,0,.12); --icon-color:#76b900;">
        <div class="stat-icon"><i class="fa-solid fa-dice-d6"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total Game & DLC</div>
            <div class="stat-value">{{ number_format($totalGames ?? 0) }}</div>
            <div class="stat-sub">Game tersedia di katalog</div>
        </div>
    </div>

    <div class="stat-card" style="--card-accent:#6366f1; --icon-bg:rgba(99,102,241,.12); --icon-color:#6366f1;">
        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total User</div>
            <div class="stat-value">{{ number_format($totalUsers ?? 0) }}</div>
            <div class="stat-sub">Pengguna terdaftar</div>
        </div>
    </div>

    <div class="stat-card" style="--card-accent:#f59e0b; --icon-bg:rgba(245,158,11,.12); --icon-color:#f59e0b;">
        <div class="stat-icon"><i class="fa-solid fa-receipt"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ number_format($totalTransactions ?? 0) }}</div>
            <div class="stat-sub">Transaksi selesai</div>
        </div>
    </div>

    <div class="stat-card" style="--card-accent:#10b981; --icon-bg:rgba(16,185,129,.12); --icon-color:#10b981;">
        <div class="stat-icon"><i class="fa-solid fa-money-bill-trend-up"></i></div>
        <div class="stat-info">
            <div class="stat-label">Total Revenue</div>
            <div class="stat-value" style="font-size:20px;">
                Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
            </div>
            <div class="stat-sub">Pendapatan total</div>
        </div>
    </div>

</div>

{{-- ===================== CHART + BEST SELLERS ===================== --}}
<div class="dash-grid">

    {{-- Revenue Chart --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fa-solid fa-chart-line"></i> Grafik Revenue (30 Hari Terakhir)
            </div>
        </div>
        <div class="admin-card-body">
            <div class="chart-wrap">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    {{-- Best Sellers --}}
    <div class="admin-card">
        <div class="admin-card-header">
            <div class="admin-card-title">
                <i class="fa-solid fa-trophy"></i> Best Sellers
            </div>
        </div>
        <div class="table-wrap desktop-table-wrap" id="table-bestsellers">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Game</th>
                        <th style="text-align:right;">Terjual</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bestSellers ?? [] as $i => $game)
                    <tr>
                        <td>
                            @php $rank = $i + 1; @endphp
                            <span class="rank-badge {{ $rank === 1 ? 'gold' : ($rank === 2 ? 'silver' : ($rank === 3 ? 'bronze' : '')) }}">
                                {{ $rank }}
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;align-items:center;gap:8px;">
                                @if($game->cover_image)
                                    <img src="{{ $game->cover_image_url }}" class="img-cover" alt="">
                                @else
                                    <div style="width:72px;height:40px;border-radius: 0;background:#252525;display:flex;align-items:center;justify-content:center;color:#6b7280; border: 1px solid var(--border-color);">
                                        <i class="fa-solid fa-image" style="font-size:12px;"></i>
                                    </div>
                                @endif
                                <span style="font-weight:600;font-size:13px;">{{ $game->title }}</span>
                            </div>
                        </td>
                        <td class="action-cell" style="text-align:right;">
                            <span class="text-green">{{ number_format($game->sold_count ?? 0) }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="td-empty">
                            <div class="empty-state">
                                <i class="fa-solid fa-trophy"></i>
                                <p>Belum ada data penjualan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- MOBILE BEST SELLERS --}}
        <div class="mobile-cards-wrap">
            @forelse($bestSellers ?? [] as $i => $game)
            <div class="bestseller-mobile-card">
                @php $rank = $i + 1; @endphp
                <span class="rank-badge {{ $rank === 1 ? 'gold' : ($rank === 2 ? 'silver' : ($rank === 3 ? 'bronze' : '')) }}">
                    {{ $rank }}
                </span>
                @if($game->cover_image)
                    <img src="{{ $game->cover_image_url }}" style="width:48px;height:64px;object-fit:cover;border:1px solid var(--border-color);" alt="">
                @else
                    <div style="width:48px;height:64px;background:#252525;display:flex;align-items:center;justify-content:center;color:#6b7280;border:1px solid var(--border-color);">
                        <i class="fa-solid fa-image"></i>
                    </div>
                @endif
                <div style="flex: 1; min-width: 0;">
                    <div style="font-size:13px; font-weight:700; color:var(--text-primary); display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden;">
                        {{ $game->title }}
                    </div>
                    <div style="font-size:12px; font-weight:700; color:var(--nvidia-green); margin-top:4px;">
                        <i class="fa-solid fa-arrow-trend-up"></i> {{ number_format($game->sold_count ?? 0) }} Terjual
                    </div>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <i class="fa-solid fa-trophy"></i>
                <p>Belum ada data penjualan</p>
            </div>
            @endforelse
        </div>
    </div>

</div>{{-- /.dash-grid --}}

{{-- ===================== RECENT TRANSACTIONS ===================== --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-clock-rotate-left"></i> Transaksi Terbaru
        </div>
        <a href="{{ route('admin.transactions.index') }}" class="btn-outline btn-sm">
            Lihat Semua <i class="fa-solid fa-arrow-right"></i>
        </a>
    </div>
    <div class="table-wrap desktop-table-wrap" id="table-recent-trx">
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentTransactions ?? [] as $trx)
                <tr>
                    <td>
                        <span style="font-family:monospace;font-size:12px;font-weight:600;background:#252525;padding:3px 8px;border-radius: 0;color:#f3f4f6;">
                            #{{ $trx->order_id ?? $trx->id }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @if($trx->user)
                                <img src="{{ $trx->user->avatar_url }}" style="width:32px;height:32px;border-radius: 50%;object-fit:cover;border:1px solid var(--nvidia-green);" alt="{{ $trx->user->name }}">
                            @else
                                <span class="avatar-initial" style="border-radius:50%;">U</span>
                            @endif
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $trx->user->name ?? '-' }}</div>
                                <div style="font-size:11px;color:#9ca3af;">{{ $trx->user->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-weight:700;">Rp {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        @php
                            $st = $trx->status ?? 'pending';
                            $cls = match($st) {
                                'paid'    => 'status-paid',
                                'failed'  => 'status-failed',
                                default   => 'status-pending',
                            };
                        @endphp
                        <span class="status-badge {{ $cls }}">{{ ucfirst($st) }}</span>
                    </td>
                    <td class="text-muted" style="font-size:13px;">
                        {{ $trx->paid_at ? \Carbon\Carbon::parse($trx->paid_at)->format('d M Y H:i') : '-' }}
                    </td>
                    <td class="action-cell">
                        <a href="{{ route('admin.transactions.index', ['search' => $trx->order_id ?? $trx->id]) }}" class="btn-outline btn-sm btn-icon" style="width: 100%; justify-content: center;">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="td-empty">
                        <div class="empty-state">
                            <i class="fa-solid fa-receipt"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE RECENT TRANSACTIONS --}}
    <div class="mobile-cards-wrap">
        @forelse($recentTransactions ?? [] as $trx)
        @php
            $st = $trx->status ?? 'pending';
            $cls = match($st) {
                'paid'    => 'status-paid',
                'failed'  => 'status-failed',
                default   => 'status-pending',
            };
        @endphp
        <div class="game-mobile-card">
            <div class="game-mobile-header">
                @if($trx->user)
                    <img src="{{ $trx->user->avatar_url }}" alt="{{ $trx->user->name }}" style="width:40px;height:40px;border-radius:50%;object-fit:cover;border:1px solid var(--nvidia-green);">
                @else
                    <div style="width:40px;height:40px;border-radius:50%;background:#252525;display:flex;align-items:center;justify-content:center;font-weight:700;">U</div>
                @endif
                <div class="game-mobile-info">
                    <div class="game-mobile-title" style="font-size:14px; margin-bottom:0;">{{ $trx->user->name ?? '-' }}</div>
                    <div class="game-mobile-dev" style="margin-bottom:0;">{{ $trx->user->email ?? '' }}</div>
                </div>
                <span class="status-badge {{ $cls }}" style="padding:2px 6px; font-size:10px;">{{ ucfirst($st) }}</span>
            </div>
            
            <div style="font-size:14px; font-weight:800; color:var(--text-primary);">
                Rp {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}
            </div>
            
            <div class="game-mobile-footer" style="padding-top:10px; flex-direction:row;">
                <div style="font-family:monospace;font-size:11px;font-weight:700;background:#252525;color:#f3f4f6;padding:2px 6px;border:1px solid var(--border-color);">
                    #{{ $trx->order_id ?? $trx->id }}
                </div>
                <div style="font-size:11px;color:#9ca3af;">
                    {{ $trx->paid_at ? \Carbon\Carbon::parse($trx->paid_at)->format('d M Y') : '-' }}
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            <p>Belum ada transaksi</p>
        </div>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function () {
    const labels = @json($chartLabels ?? []);
    const data   = @json($chartData   ?? []);

    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;

    new Chart(ctx.getContext('2d'), {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Revenue (Rp)',
                data: data,
                borderColor: '#76b900',
                borderWidth: 2.5,
                backgroundColor: 'rgba(118,185,0,0.15)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#76b900',
                pointBorderColor: '#1c1c1c',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { intersect: false, mode: 'index' },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0a0a0a',
                    titleColor: '#76b900',
                    bodyColor: '#fff',
                    cornerRadius: 8,
                    padding: 12,
                    callbacks: {
                        label: ctx => 'Rp ' + ctx.raw.toLocaleString('id-ID')
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255,255,255,.15)' },
                    ticks: { font: { size: 11 }, color: '#9ca3af' }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255,255,255,.15)' },
                    ticks: {
                        font: { size: 11 }, color: '#9ca3af',
                        callback: v => 'Rp ' + (v / 1000000).toFixed(1) + 'jt'
                    }
                }
            }
        }
    });
})();
</script>
@endpush
