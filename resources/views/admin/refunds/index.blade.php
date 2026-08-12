@extends('layouts.admin')

@section('page_title', 'Manajemen Refund')

@push('styles')
<style>
.refund-reason {
    max-width: 220px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: help;
    display: inline-block;
    vertical-align: middle;
}
.days-badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 9px; border-radius: 0; font-size: 11px; font-weight: 700;
}
.days-ok   { background: rgba(118,185,0,.12);  color: var(--nvidia-green-d); }
.days-warn { background: rgba(245,158,11,.12); color: #d97706; }
.days-late { background: rgba(239,68,68,.12);  color: #dc2626; }
.action-cell { padding-right: 32px !important; min-width: 130px; }

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
.game-mobile-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; }
.game-mobile-info { flex: 1; }
.game-mobile-title { font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
.game-mobile-dev { font-size: 11.5px; color: #9ca3af; margin-bottom: 8px; }
.game-mobile-price { font-size: 15px; font-weight: 800; color: var(--nvidia-green); }
.game-mobile-footer { display: flex; flex-direction: column; gap: 12px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px; }
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1><i class="fa-solid fa-rotate-left" style="color:var(--nvidia-green);"></i> Manajemen Refund</h1>
        <p>Persetujuan dan riwayat permintaan refund</p>
    </div>
    @if(isset($pendingRefunds) && $pendingRefunds->count() > 0)
    <span class="status-badge status-pending" style="font-size:13px;padding:6px 14px;">
        {{ $pendingRefunds->count() }} Menunggu
    </span>
    @endif
</div>

{{-- ===================== SECTION 1: PENDING REFUNDS ===================== --}}
<div class="admin-card" style="margin-bottom:28px;">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-clock" style="color:#d97706;"></i>
            Menunggu Persetujuan
            @if(isset($pendingRefunds) && $pendingRefunds->count() > 0)
            <span style="background:rgba(239,68,68,.12);color:#dc2626;font-size:11px;font-weight:700;padding:2px 8px;border-radius: 0;margin-left:4px;">
                {{ $pendingRefunds->count() }}
            </span>
            @endif
        </div>
    </div>

    <div class="table-wrap desktop-table-wrap" id="table-pending">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Game</th>
                    <th>Order ID</th>
                    <th>Tgl Pembelian</th>
                    <th>Hari</th>
                    <th>Alasan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pendingRefunds ?? [] as $refund)
                @php
                    $purchaseDate = $refund->transaction?->paid_at ?? $refund->created_at;
                    $days = floor($purchaseDate ? \Carbon\Carbon::parse($purchaseDate)->diffInDays(now()) : 0);
                    $daysCls = $days <= 3 ? 'days-ok' : ($days <= 7 ? 'days-warn' : 'days-late');
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <img src="{{ $refund->transaction?->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=U&background=76b900&color=000&bold=true' }}" onerror="this.src='https://ui-avatars.com/api/?name=U&background=76b900&color=000&bold=true'" alt="User" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $refund->transaction?->user?->name ?? '-' }}</div>
                                <div style="font-size:11px;color:#9ca3af;">{{ $refund->transaction?->user?->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @if($refund->game?->cover_image)
                                <img src="{{ $refund->game->cover_image_url }}"
                                     style="width:90px;height:42px;border-radius: 0;object-fit:cover;" alt="">
                            @endif
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $refund->game?->title ?? '-' }}</div>
                                <div style="font-size:11px;color:var(--nvidia-green);font-weight:700;">
                                    Rp {{ number_format($refund->amount ?? $refund->game?->price ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:12px;font-weight:700;background:#252525;color:#f3f4f6;padding:3px 8px;border-radius: 4px;">
                            #{{ $refund->transaction?->order_id ?? $refund->transaction_id ?? '-' }}
                        </span>
                    </td>
                    <td style="font-size:12.5px;color:#6b7280;">
                        {{ $purchaseDate ? \Carbon\Carbon::parse($purchaseDate)->format('d M Y H:i') : '-' }}
                    </td>
                    <td>
                        <span class="days-badge {{ $daysCls }}">
                            <i class="fa-solid fa-calendar-days"></i>
                            {{ $days }} hari
                        </span>
                    </td>
                    <td>
                        <span class="refund-reason" onclick="showSweetAlert('Alasan Refund', '{{ addslashes(str_replace(["\r", "\n"], ' ', $refund->refund_reason ?? '-')) }}', 'warning')" title="Klik untuk lihat selengkapnya" style="cursor: pointer; border-bottom: 1px dashed rgba(255,255,255,0.3);">
                            {{ $refund->refund_reason ?? '-' }}
                        </span>
                    </td>
                    <td class="action-cell">
                        <div class="action-group" style="flex-direction:column;align-items:stretch;gap:6px;">

                            {{-- Approve --}}
                                <form action="{{ route('admin.refunds.approve', $refund->id) }}" method="POST"
                                  onsubmit="return confirmAction(event, this, 'Konfirmasi', 'Setujui refund ini?')">
                                @csrf
                                <button type="submit" class="btn-primary btn-sm" style="width:100%;justify-content:center;">
                                    <i class="fa-solid fa-check"></i> Setujui
                                </button>
                            </form>

                            {{-- Reject --}}
                                <form action="{{ route('admin.refunds.reject', $refund->id) }}" method="POST"
                                  onsubmit="return confirmAction(event, this, 'Konfirmasi', 'Tolak permintaan refund ini?')">
                                @csrf
                                <button type="submit" class="btn-danger btn-sm" style="width:100%;justify-content:center;">
                                    <i class="fa-solid fa-xmark"></i> Tolak
                                </button>
                            </form>

                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="td-empty">
                        <div class="empty-state">
                            <i class="fa-solid fa-circle-check" style="color:var(--nvidia-green);font-size:36px;"></i>
                            <p>Tidak ada refund yang menunggu persetujuan 🎉</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    {{-- MOBILE CARDS VIEW PENDING --}}
    <div class="mobile-cards-wrap">
        @forelse($pendingRefunds ?? [] as $refund)
        @php
            $purchaseDate = $refund->transaction?->paid_at ?? $refund->created_at;
            $days = floor($purchaseDate ? \Carbon\Carbon::parse($purchaseDate)->diffInDays(now()) : 0);
            $daysCls = $days <= 3 ? 'days-ok' : ($days <= 7 ? 'days-warn' : 'days-late');
        @endphp
        <div class="game-mobile-card">
            <div class="game-mobile-header">
                <div style="display:flex; align-items:center; gap:10px;">
                    <img src="{{ $refund->transaction?->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=U&background=76b900&color=000&bold=true' }}" onerror="this.src='https://ui-avatars.com/api/?name=U&background=76b900&color=000&bold=true'" alt="User" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                    <div>
                        <div class="game-mobile-title" style="margin-bottom:0;">{{ $refund->transaction?->user?->name ?? '-' }}</div>
                        <div class="game-mobile-dev" style="margin-bottom:0;">{{ $refund->transaction?->user?->email ?? '' }}</div>
                    </div>
                </div>
                <span class="days-badge {{ $daysCls }}"><i class="fa-solid fa-clock"></i> {{ $days }} hari</span>
            </div>
            
            <div style="display:flex; gap:12px; align-items:center; margin-top:8px;">
                @if($refund->game?->cover_image)
                    <img src="{{ $refund->game->cover_image_url }}" style="width: 100px; height: 46px; border-radius: 0; object-fit: cover;" alt="">
                @endif
                <div>
                    <div style="font-size:13px; font-weight:700; color:var(--text-primary);">{{ $refund->game?->title ?? '-' }}</div>
                    <div style="font-size:14px; font-weight:800; color:var(--nvidia-green);">Rp {{ number_format($refund->amount ?? $refund->game?->price ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            <div style="font-size:13px; color:var(--text-light); border:1px solid rgba(255,255,255,0.05); padding:8px; border-radius:var(--radius-sm); background:rgba(0,0,0,0.2);">
                <div style="color:#9ca3af;font-weight:700;font-size:11px;margin-bottom:4px;text-transform:uppercase;">Alasan Refund</div>
                "{{ $refund->refund_reason ?? 'Tidak ada alasan' }}"
            </div>
            
            <div class="game-mobile-footer">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-family:monospace;font-size:12px;font-weight:700;background:#252525;color:#f3f4f6;padding:3px 8px;border:1px solid var(--border-color);">
                        #{{ $refund->transaction?->order_id ?? '-' }}
                    </div>
                    <div style="font-size:12px;color:#9ca3af;">
                        <i class="fa-solid fa-calendar"></i> Beli: {{ $purchaseDate ? \Carbon\Carbon::parse($purchaseDate)->format('d M Y') : '-' }}
                    </div>
                </div>
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:8px;">
                    <form action="{{ route('admin.refunds.reject', $refund->id) }}" method="POST">
                        @csrf @method('PUT')
                        <button class="btn-danger w-full" style="width:100%; justify-content:center;" onclick="return confirm('Tolak refund ini?')">
                            <i class="fa-solid fa-xmark"></i> Tolak
                        </button>
                    </form>
                    <button class="btn-primary w-full" style="width:100%; justify-content:center;" onclick="openApproveModal({{ $refund->id }}, '{{ addslashes($refund->transaction?->user?->name) }}')">
                        <i class="fa-solid fa-check"></i> Setujui
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fa-solid fa-circle-check" style="color:var(--nvidia-green);font-size:36px;"></i>
            <p>Tidak ada refund yang menunggu persetujuan 🎉</p>
        </div>
        @endforelse
    </div>
</div>

{{-- ===================== SECTION 2: HISTORY ===================== --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-clock-rotate-left"></i>
            Riwayat Refund
        </div>
    </div>

    <div class="table-wrap desktop-table-wrap" id="table-history">
        <table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Game</th>
                    <th>Order ID</th>
                    <th>Jumlah Refund</th>
                    <th>Status</th>
                    <th>Diproses</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($processedRefunds ?? [] as $refund)
                @php
                    $st  = $refund->status ?? 'pending';
                    $cls = match($st) {
                        'approved' => 'status-approved',
                        'rejected' => 'status-rejected',
                        default    => 'status-pending',
                    };
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            <img src="{{ $refund->transaction?->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=U&background=76b900&color=000&bold=true' }}" onerror="this.src='https://ui-avatars.com/api/?name=U&background=76b900&color=000&bold=true'" alt="User" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                            <div>
                                <div style="font-weight:600;font-size:13px;">{{ $refund->transaction?->user?->name ?? '-' }}</div>
                                <div style="font-size:11px;color:#9ca3af;">{{ $refund->transaction?->user?->email ?? '' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:8px;">
                            @if($refund->game?->cover_image)
                                <img src="{{ $refund->game->cover_image_url }}"
                                     style="width:90px;height:42px;border-radius: 0;object-fit:cover;" alt="">
                            @endif
                            <span style="font-weight:600;font-size:13px;">{{ $refund->game?->title ?? '-' }}</span>
                        </div>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:12px;font-weight:700;background:#252525;color:#f3f4f6;padding:3px 8px;border-radius: 4px;">
                            #{{ $refund->transaction?->order_id ?? $refund->transaction_id ?? '-' }}
                        </span>
                    </td>
                    <td>
                        <span style="font-weight:800;color:var(--nvidia-green);">
                            Rp {{ number_format($refund->amount ?? $refund->game?->price ?? 0, 0, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        <span class="status-badge {{ $cls }}">{{ ucfirst($st) }}</span>
                    </td>
                    <td style="font-size:12.5px;color:#6b7280;">
                        {{ $refund->updated_at ? $refund->updated_at->format('d M Y H:i') : '-' }}
                    </td>
                    <td>
                        <span class="refund-reason text-muted" style="font-size:12px; cursor: pointer; border-bottom: 1px dashed rgba(255,255,255,0.3);" onclick="showSweetAlert('Catatan Admin', '{{ addslashes(str_replace(["\r", "\n"], ' ', $refund->admin_note ?? $refund->rejection_reason ?? '-')) }}', 'warning')" title="Klik untuk lihat selengkapnya">
                            {{ $refund->admin_note ?? $refund->rejection_reason ?? '-' }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="td-empty">
                        <div class="empty-state">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                            <p>Belum ada riwayat refund</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS VIEW HISTORY --}}
    <div class="mobile-cards-wrap">
        @forelse($processedRefunds ?? [] as $refund)
        @php
            $st  = $refund->status ?? 'pending';
            $cls = match($st) {
                'approved' => 'status-approved',
                'rejected' => 'status-rejected',
                default    => 'status-pending',
            };
        @endphp
        <div class="game-mobile-card">
            <div class="game-mobile-header">
                <div style="display:flex; align-items:center; gap:10px;">
                    <img src="{{ $refund->transaction?->user?->avatar_url ?? 'https://ui-avatars.com/api/?name=U&background=76b900&color=000&bold=true' }}" onerror="this.src='https://ui-avatars.com/api/?name=U&background=76b900&color=000&bold=true'" alt="User" style="width:36px;height:36px;border-radius:50%;object-fit:cover;">
                    <div>
                        <div class="game-mobile-title" style="margin-bottom:0;">{{ $refund->transaction?->user?->name ?? '-' }}</div>
                        <div class="game-mobile-dev" style="margin-bottom:0;">{{ $refund->transaction?->user?->email ?? '' }}</div>
                    </div>
                </div>
                <span class="status-badge {{ $cls }}">{{ ucfirst($st) }}</span>
            </div>
            
            <div style="display:flex; gap:12px; align-items:center; margin-top:8px;">
                @if($refund->game?->cover_image)
                    <img src="{{ $refund->game->cover_image_url }}" style="width: 100px; height: 46px; border-radius: 0; object-fit: cover;" alt="">
                @endif
                <div>
                    <div style="font-size:13px; font-weight:700; color:var(--text-primary);">{{ $refund->game?->title ?? '-' }}</div>
                    <div style="font-size:14px; font-weight:800; color:var(--nvidia-green);">Rp {{ number_format($refund->amount ?? $refund->game?->price ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
            
            @if($refund->admin_note || $refund->rejection_reason)
            <div style="font-size:12px; color:var(--text-light); border:1px solid rgba(255,255,255,0.05); padding:8px; border-radius:var(--radius-sm); background:rgba(0,0,0,0.2);">
                <div style="color:#9ca3af;font-weight:700;font-size:11px;margin-bottom:4px;text-transform:uppercase;">Catatan Admin</div>
                {{ $refund->admin_note ?? $refund->rejection_reason ?? '-' }}
            </div>
            @endif
            
            <div class="game-mobile-footer">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-family:monospace;font-size:12px;font-weight:700;background:#252525;color:#f3f4f6;padding:3px 8px;border:1px solid var(--border-color);">
                        #{{ $refund->transaction?->order_id ?? $refund->transaction_id ?? '-' }}
                    </div>
                    <div style="font-size:12px;color:#9ca3af;">
                        <i class="fa-solid fa-calendar"></i> {{ $refund->updated_at ? $refund->updated_at->format('d M Y') : '-' }}
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fa-solid fa-clock-rotate-left"></i>
            <p>Belum ada riwayat refund</p>
        </div>
        @endforelse
    </div>
</div>

@endsection
