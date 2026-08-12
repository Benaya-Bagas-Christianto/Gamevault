@extends('layouts.admin')

@section('page_title', 'Manajemen Transaksi')

@push('styles')
<style>
.acc-detail-table { width: 100%; border-collapse: collapse; font-size: 12.5px; background: #1f1f1f; }
.acc-detail-table th { padding: 10px 14px; background: #151515; color: var(--nvidia-green); font-weight: 700; text-align: left; font-size: 11px; text-transform: uppercase; letter-spacing: .5px; border-bottom: 1px solid var(--border-color); }
.acc-detail-table td { padding: 12px 14px; border-bottom: 1px solid var(--border-color); color: var(--text-light); }
.acc-detail-table tr:last-child td { border-bottom: none; }
.trx-row-toggle { cursor: pointer; }
.trx-row-toggle:hover td { background: rgba(118,185,0,.06) !important; }
.toggle-icon { transition: transform .25s; display: inline-block; }
.expanded .toggle-icon { transform: rotate(180deg); }
</style>
@endpush

@push('styles')
<style>
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
    gap: 14px;
}
.game-mobile-header { display: flex; gap: 14px; }
.game-mobile-info { flex: 1; }
.game-mobile-title { font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
.game-mobile-dev { font-size: 11.5px; color: #9ca3af; margin-bottom: 8px; }
.game-mobile-price { font-size: 15px; font-weight: 800; color: var(--nvidia-green); }
.game-mobile-footer { display: flex; justify-content: space-between; align-items: center; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px; }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1><i class="fa-solid fa-receipt" style="color:var(--nvidia-green);"></i> Manajemen Transaksi</h1>
        <p>Riwayat & detail semua transaksi</p>
    </div>
    <a href="{{ route('admin.transactions.report.pdf', request()->query()) }}"
       class="btn-primary" target="_blank">
        <i class="fa-solid fa-file-pdf"></i> Download Laporan PDF
    </a>
</div>

{{-- Filter Bar --}}
<div class="admin-card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.transactions.index') }}" class="filter-bar">
        <i class="fa-solid fa-filter" style="color:#9ca3af;"></i>

        <select name="status" class="form-control">
            <option value="">Semua Status</option>
            <option value="paid"    {{ request('status') === 'paid'    ? 'selected' : '' }}>Paid</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="failed"  {{ request('status') === 'failed'  ? 'selected' : '' }}>Failed</option>
        </select>

        <input type="text" name="search" class="form-control"
               placeholder="Cari order ID atau nama user..."
               value="{{ request('search') }}">

        <input type="date" name="date_from" class="form-control" style="max-width:160px;"
               value="{{ request('date_from') }}" placeholder="Dari">
        <input type="date" name="date_to" class="form-control" style="max-width:160px;"
               value="{{ request('date_to') }}" placeholder="Sampai">

        <button type="submit" class="btn-primary btn-sm">
            <i class="fa-solid fa-search"></i> Filter
        </button>
        @if(request()->hasAny(['status','search','date_from','date_to']))
        <a href="{{ route('admin.transactions.index') }}" class="btn-outline btn-sm">
            <i class="fa-solid fa-xmark"></i> Reset
        </a>
        @endif
    </form>
</div>

{{-- Transactions Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-list"></i>
            Daftar Transaksi
            <span style="background:#252525;color:#9ca3af;font-size:11px;font-weight:700;padding:2px 8px;border-radius: 0;margin-left:4px;border:1px solid var(--border-color);">
                {{ $transactions->total() }} transaksi
            </span>
        </div>
        <div style="font-size:12px;color:#9ca3af;">Klik baris untuk lihat detail item</div>
    </div>

    <div class="table-wrap desktop-table-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:30px;"></th>
                    <th>Order ID</th>
                    <th>User</th>
                    <th style="text-align:center;">Items</th>
                    <th>Total</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Dibayar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transactions as $trx)
                @php
                    $st = $trx->status ?? 'pending';
                    $cls = match($st) {
                        'paid'    => 'status-paid',
                        'failed'  => 'status-failed',
                        default   => 'status-pending',
                    };
                    $detailId = 'detail_' . $trx->id;
                    $items = $trx->details ?? $trx->transactionDetails ?? collect();
                @endphp

                {{-- Main row --}}
                <tr class="trx-row-toggle" data-detail="{{ $detailId }}" id="row_{{ $trx->id }}">
                    <td>
                        <i class="fa-solid fa-chevron-down toggle-icon" style="color:#9ca3af;font-size:12px;"></i>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:12px;font-weight:700;background:#252525;color:#f3f4f6;padding:3px 8px;border-radius: 0;border:1px solid var(--border-color);">
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
                    <td style="text-align:center;">
                        <span style="background:rgba(118,185,0,.12);color:var(--nvidia-green-d);font-weight:700;font-size:12px;padding:2px 9px;border-radius: 0;">
                            {{ $items->count() }}
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:800;">Rp {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}</span>
                    </td>
                    <td>
                        <span style="font-size:12px;color:#6b7280;font-weight:600;text-transform:capitalize;">
                            {{ $trx->payment_type ?? $trx->payment_method ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $cls }}">{{ ucfirst($st) }}</span>
                    </td>
                    <td class="action-cell" style="font-size:12px;color:#6b7280;">
                        {{ $trx->paid_at ? \Carbon\Carbon::parse($trx->paid_at)->format('d M Y H:i') : '-' }}
                    </td>
                </tr>

                {{-- Detail accordion row --}}
                <tr id="{{ $detailId }}" class="accordion-row" style="display:none;">
                    <td colspan="8" style="padding:0;">
                        <div class="accordion-content" style="display:block;">
                            @if($items->count() > 0)
                            <table class="acc-detail-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Game</th>
                                        <th>Harga Satuan</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($items as $i => $item)
                                    <tr>
                                        <td style="color:#9ca3af;">{{ $i+1 }}</td>
                                        <td>
                                            <div style="display:flex;align-items:center;gap:8px;">
                                                @if(isset($item->game->cover_image) && $item->game->cover_image)
                                                    <img src="{{ $item->game->cover_image_url }}" style="width:28px;height:38px;border-radius: 0;object-fit:cover;" alt="">
                                                @endif
                                                <span style="font-weight:600;">{{ $item->game->title ?? $item->game_title ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>Rp {{ number_format($item->price ?? 0, 0, ',', '.') }}</td>
                                        <td>{{ $item->quantity ?? 1 }}</td>
                                        <td style="font-weight:700;color:var(--nvidia-green-d);">
                                            Rp {{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                            <div style="padding:16px;color:#9ca3af;font-size:13px;text-align:center;">
                                <i class="fa-solid fa-box-open"></i> Tidak ada item
                            </div>
                            @endif
                        </div>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="8" class="td-empty">
                        <div class="empty-state">
                            <i class="fa-solid fa-receipt"></i>
                            <p>Tidak ada transaksi ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS VIEW --}}
    <div class="mobile-cards-wrap">
        @forelse($transactions as $trx)
        @php
            $st = $trx->status ?? 'pending';
            $cls = match($st) {
                'paid'    => 'status-paid',
                'failed'  => 'status-failed',
                default   => 'status-pending',
            };
            $items = $trx->details ?? $trx->transactionDetails ?? collect();
            $detailId = 'mob_detail_' . $trx->id;
        @endphp
        <div class="game-mobile-card">
            <div class="game-mobile-header">
                @if($trx->user)
                    <img src="{{ $trx->user->avatar_url }}" alt="{{ $trx->user->name }}" style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:1px solid var(--nvidia-green);">
                @else
                    <div style="width:48px;height:48px;border-radius:50%;background:#252525;display:flex;align-items:center;justify-content:center;font-weight:700;">U</div>
                @endif
                <div class="game-mobile-info">
                    <div class="game-mobile-title">{{ $trx->user->name ?? '-' }}</div>
                    <div class="game-mobile-dev">{{ $trx->user->email ?? '' }}</div>
                    <div class="game-mobile-price">Rp {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}</div>
                </div>
                <div style="text-align:right;">
                    <span class="status-badge {{ $cls }}">{{ ucfirst($st) }}</span>
                </div>
            </div>
            
            <div class="game-mobile-footer" style="flex-direction:column; align-items:stretch; gap:12px;">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-family:monospace;font-size:12px;font-weight:700;background:#252525;color:#f3f4f6;padding:3px 8px;border:1px solid var(--border-color);">
                        #{{ $trx->order_id ?? $trx->id }}
                    </div>
                    <div style="font-size:12px;color:#9ca3af;">
                        <i class="fa-solid fa-calendar"></i> {{ $trx->paid_at ? \Carbon\Carbon::parse($trx->paid_at)->format('d M Y') : '-' }}
                    </div>
                </div>
                
                <button type="button" class="btn-outline btn-sm trx-mob-toggle" data-detail="{{ $detailId }}" style="justify-content:center; width: 100%;">
                    Lihat Detail ({{ $items->count() }} Item) <i class="fa-solid fa-chevron-down mob-toggle-icon"></i>
                </button>
                
                <div id="{{ $detailId }}" class="mob-accordion-content" style="display:none; padding-top:12px; border-top:1px dashed var(--border-color); margin-top: 4px;">
                    @if($items->count() > 0)
                        @foreach($items as $item)
                        <div style="display:flex; justify-content:space-between; margin-bottom:8px; font-size:13px;">
                            <div style="color:var(--text-primary); line-height: 1.4;">
                                <span style="color:#9ca3af; font-weight:700;">{{ $item->quantity ?? 1 }}x</span> 
                                {{ $item->game->title ?? $item->game_title ?? '-' }}
                            </div>
                            <div style="font-weight:700; color:var(--nvidia-green);">
                                Rp {{ number_format(($item->price ?? 0) * ($item->quantity ?? 1), 0, ',', '.') }}
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div style="text-align:center; color:#9ca3af; font-size:13px;"><i class="fa-solid fa-box-open"></i> Tidak ada item</div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fa-solid fa-receipt"></i>
            <p>Tidak ada transaksi ditemukan</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($transactions->hasPages())
    <div class="pagination">
        {{ $transactions->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script>
document.querySelectorAll('.trx-row-toggle').forEach(row => {
    row.addEventListener('click', function () {
        const detailId = this.dataset.detail;
        const detailRow = document.getElementById(detailId);
        const icon = this.querySelector('.toggle-icon');
        if (!detailRow) return;
        const isVisible = detailRow.style.display !== 'none';
        detailRow.style.display = isVisible ? 'none' : 'table-row';
        if (icon) icon.style.transform = isVisible ? '' : 'rotate(180deg)';
        this.classList.toggle('expanded', !isVisible);
    });
});
document.querySelectorAll('.trx-mob-toggle').forEach(btn => {
    btn.addEventListener('click', function () {
        const detailId = this.dataset.detail;
        const detailRow = document.getElementById(detailId);
        const icon = this.querySelector('.mob-toggle-icon');
        if (!detailRow) return;
        const isVisible = detailRow.style.display !== 'none';
        detailRow.style.display = isVisible ? 'none' : 'block';
        if (icon) icon.style.transform = isVisible ? '' : 'rotate(180deg)';
    });
});
</script>
@endpush
