@extends('layouts.admin')

@section('page_title', 'Detail User')

@section('content')

@push('styles')
<style>
@media (max-width: 900px) {
    #accTrxBody .table-wrap > table > tbody > tr > td:nth-of-type(1)::before { content: "Order ID"; }
    #accTrxBody .table-wrap > table > tbody > tr > td:nth-of-type(2)::before { content: "Total"; }
    #accTrxBody .table-wrap > table > tbody > tr > td:nth-of-type(3)::before { content: "Status"; }
    #accTrxBody .table-wrap > table > tbody > tr > td:nth-of-type(4)::before { content: "Dibayar"; }
    #accTrxBody .table-wrap > table > tbody > tr > td:nth-of-type(5)::before { content: "Invoice"; }
}
</style>
@endpush

{{-- Back Button --}}
<div style="margin-bottom:20px; display:flex; gap: 16px; align-items: center;">
    <a href="{{ route('admin.users.index') }}" class="topbar-icon-btn" title="Kembali ke Daftar User">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <h2 style="font-size: 22px; font-weight: 800; margin: 0;">Detail User</h2>
</div>

{{-- ===================== USER INFO CARD ===================== --}}
<div class="user-info-card">
    <div class="user-avatar-lg">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}"
                 style="width:72px;height:72px;border-radius: 0;object-fit:cover;"
                 alt="{{ $user->name }}">
        @else
            {{ strtoupper(substr($user->name, 0, 1)) }}
        @endif
    </div>
    <div style="flex:1;">
        <h2>{{ $user->name }}</h2>
        <p>{{ $user->email }}</p>
        <div class="user-info-meta">
            <div class="user-meta-item">
                <strong>{{ $user->created_at->format('d M Y') }}</strong>
                Bergabung
            </div>
            <div class="user-meta-item">
                <strong style="color:var(--nvidia-green);">{{ $transactions->count() }}</strong>
                Transaksi
            </div>
            <div class="user-meta-item">
                <strong style="color:#6366f1;">{{ $libraryItems->count() }}</strong>
                Library
            </div>
            <div class="user-meta-item">
                <strong style="color:#f59e0b;">{{ $cartItems->count() }}</strong>
                Di Keranjang
            </div>
        </div>
    </div>
</div>

{{-- ===================== ACCORDION SECTIONS ===================== --}}

{{-- 1. Riwayat Transaksi --}}
<div class="acc-section">
    <div class="acc-header" id="accTrxHeader">
        <div style="display:flex;align-items:center;gap:10px;">
            <i class="fa-solid fa-receipt" style="color:var(--nvidia-green);"></i>
            Riwayat Transaksi
            <span style="background:rgba(118,185,0,.12);color:var(--nvidia-green-d);font-size:11px;font-weight:700;padding:1px 8px;border-radius: 0;">
                {{ $transactions->count() }}
            </span>
        </div>
        <i class="fa-solid fa-chevron-down acc-toggle-icon"></i>
    </div>
    <div class="acc-body" id="accTrxBody">
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Dibayar</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr>
                        <td>
                            <span style="font-family:monospace;font-size:12px;font-weight:700;background:#252525;color:#f3f4f6;padding:3px 8px;border-radius: 0;border:1px solid var(--border-color);">
                                #{{ $trx->order_id ?? $trx->id }}
                            </span>
                        </td>
                        <td>
                            <span style="font-weight:800;">Rp {{ number_format($trx->total_amount ?? 0, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            @php
                                $st = $trx->status ?? 'pending';
                                $cls = match($st) { 'paid' => 'status-paid', 'failed' => 'status-failed', default => 'status-pending' };
                            @endphp
                            <span class="status-badge {{ $cls }}">{{ ucfirst($st) }}</span>
                        </td>
                        <td style="font-size:12.5px;color:#6b7280;">
                            {{ $trx->paid_at ? \Carbon\Carbon::parse($trx->paid_at)->format('d M Y H:i') : '-' }}
                        </td>
                        <td>
                            @if($trx->status === 'paid')
                            <a href="{{ route('admin.transactions.invoice', $trx->id) }}"
                               class="btn-outline btn-sm" target="_blank">
                                <i class="fa-solid fa-file-pdf"></i> Invoice
                            </a>
                            @else
                            <span style="color:#ccc;font-size:12px;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5">
                            <div class="empty-state" style="padding:20px;">
                                <i class="fa-solid fa-receipt"></i>
                                <p>Belum ada transaksi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- 2. Isi Keranjang --}}
<div class="acc-section">
    <div class="acc-header" id="accCartHeader">
        <div style="display:flex;align-items:center;gap:10px;">
            <i class="fa-solid fa-cart-shopping" style="color:#f59e0b;"></i>
            Isi Keranjang
            <span style="background:rgba(245,158,11,.1);color:#d97706;font-size:11px;font-weight:700;padding:1px 8px;border-radius: 0;">
                {{ $cartItems->count() }}
            </span>
        </div>
        <i class="fa-solid fa-chevron-down acc-toggle-icon"></i>
    </div>
    <div class="acc-body" id="accCartBody">
        @if($cartItems->count() > 0)
        <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px;">
            @foreach($cartItems as $item)
            <div style="display:flex;align-items:center;gap:12px;padding:12px;border:1px solid var(--border-color);border-radius: 0;background:#252525;">
                @if($item->game?->cover_image)
                    <img src="{{ $item->game->cover_image_url }}"
                         style="width:72px;height:40px;border-radius: 0;object-fit:cover;border:1px solid var(--border-color);" alt="">
                @else
                    <div style="width:72px;height:40px;border-radius: 0;background:#1c1c1c;display:flex;align-items:center;justify-content:center;border:1px solid var(--border-color);">
                        <i class="fa-solid fa-image" style="color:#6b7280;font-size:14px;"></i>
                    </div>
                @endif
                <div style="flex:1;">
                    <div style="font-weight:700;font-size:13px;line-height:1.3;">
                        {{ $item->game?->title ?? $item->game_title ?? '-' }}
                    </div>
                    <div style="font-size:12px;color:var(--nvidia-green);font-weight:700;margin-top:3px;">
                        Rp {{ number_format($item->game?->price ?? 0, 0, ',', '.') }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state" style="padding:24px;">
            <i class="fa-solid fa-cart-shopping"></i>
            <p>Keranjang kosong</p>
        </div>
        @endif
    </div>
</div>

{{-- 3. Library --}}
<div class="acc-section">
    <div class="acc-header" id="accLibHeader">
        <div style="display:flex;align-items:center;gap:10px;">
            <i class="fa-solid fa-heart" style="color:#6366f1;"></i>
            Library Game
            <span style="background:rgba(99,102,241,.1);color:#6366f1;font-size:11px;font-weight:700;padding:1px 8px;border-radius: 0;">
                {{ $libraryItems->count() }}
            </span>
        </div>
        <i class="fa-solid fa-chevron-down acc-toggle-icon"></i>
    </div>
    <div class="acc-body" id="accLibBody">
        @if($libraryItems->count() > 0)
        <div style="padding:16px 20px;display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;">
            @foreach($libraryItems as $item)
            <div style="display:flex;align-items:center;gap:10px;padding:12px;border:1px solid var(--border-color);border-radius: 0;background:#252525;">
                @if($item->game?->cover_image)
                    <img src="{{ $item->game->cover_image_url }}"
                         style="width:72px;height:40px;border-radius: 0;object-fit:cover;border:1px solid var(--border-color);" alt="">
                @else
                    <div style="width:72px;height:40px;border-radius: 0;background:#1c1c1c;display:flex;align-items:center;justify-content:center;border:1px solid var(--border-color);">
                        <i class="fa-solid fa-image" style="color:#6b7280;font-size:12px;"></i>
                    </div>
                @endif
                <div style="flex:1;">
                    <div style="font-weight:700;font-size:12.5px;line-height:1.3;">
                        {{ $item->game?->title ?? $item->game_title ?? '-' }}
                    </div>
                    @if($item->created_at)
                    <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                        <i class="fa-solid fa-calendar-check" style="font-size:9px;"></i>
                        {{ $item->created_at->format('d M Y') }}
                    </div>
                    @endif
                </div>
                <i class="fa-solid fa-check-circle" style="color:var(--nvidia-green);font-size:14px;"></i>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state" style="padding:24px;">
            <i class="fa-solid fa-heart"></i>
            <p>Library kosong</p>
        </div>
        @endif
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Custom scripts for user details if needed
</script>
@endpush
