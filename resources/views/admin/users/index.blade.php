@extends('layouts.admin')

@section('page_title', 'Manajemen User')

@section('content')

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
    gap: 12px;
}
.game-mobile-header { display: flex; align-items: flex-start; gap: 14px; }
.game-mobile-info { flex: 1; }
.game-mobile-title { font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
.game-mobile-dev { font-size: 11.5px; color: #9ca3af; margin-bottom: 8px; }
.game-mobile-footer { display: flex; flex-direction: column; gap: 12px; border-top: 1px solid rgba(255,255,255,0.05); padding-top: 12px; }
</style>
@endpush

<div class="page-header">
    <div class="page-header-left">
        <h1><i class="fa-solid fa-users" style="color:var(--nvidia-green);"></i> Manajemen User</h1>
        <p>Daftar semua pengguna yang terdaftar di GameVault</p>
    </div>
</div>

{{-- Search --}}
<div class="admin-card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.users.index') }}" class="filter-bar">
        <i class="fa-solid fa-magnifying-glass" style="color:#9ca3af;"></i>
        <input type="text" name="search" class="form-control"
               placeholder="Cari nama atau email..."
               value="{{ request('search') }}">
        <button type="submit" class="btn-primary btn-sm">
            <i class="fa-solid fa-search"></i> Cari
        </button>
        @if(request('search'))
        <a href="{{ route('admin.users.index') }}" class="btn-outline btn-sm">
            <i class="fa-solid fa-xmark"></i> Reset
        </a>
        @endif
    </form>
</div>

{{-- Users Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-list"></i>
            Semua User
            <span style="background:#f0f0f0;color:#666;font-size:11px;font-weight:700;padding:2px 8px;border-radius: 0;margin-left:4px;">
                {{ $users->total() }} user
            </span>
        </div>
    </div>

    <div class="table-wrap desktop-table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Bergabung</th>
                    <th style="text-align:center;">Transaksi</th>
                    <th style="text-align:center;">Library</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <span style="font-size:12px;color:#9ca3af;font-weight:600;">#{{ $user->id }}</span>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:10px;">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}"
                                     alt="{{ $user->name }}"
                                     style="width:32px;height:32px;border-radius: 0;object-fit:cover;border:2px solid var(--border-color);">
                            @else
                                <span class="avatar-initial">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </span>
                            @endif
                            <div>
                                <div style="font-weight:700;font-size:13.5px;">{{ $user->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span style="font-size:13px;color:#6b7280;">{{ $user->email }}</span>
                    </td>
                    <td style="font-size:12.5px;color:#6b7280;">
                        {{ $user->created_at->format('d M Y') }}
                    </td>
                    <td style="text-align:center;">
                        <span style="background:rgba(99,102,241,.1);color:#6366f1;font-weight:700;font-size:12px;padding:3px 10px;border-radius: 0;">
                            {{ $user->transactions_count ?? $user->transactions->count() }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <span style="background:rgba(118,185,0,.1);color:var(--nvidia-green-d);font-weight:700;font-size:12px;padding:3px 10px;border-radius: 0;">
                            {{ $user->libraries_count ?? $user->libraries->count() }}
                        </span>
                    </td>
                    <td class="action-cell">
                        <a href="{{ route('admin.users.show', $user->id) }}"
                           class="btn-outline btn-sm">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="td-empty">
                        <div class="empty-state">
                            <i class="fa-solid fa-users"></i>
                            <p>Tidak ada user ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS VIEW USERS --}}
    <div class="mobile-cards-wrap">
        @forelse($users as $user)
        <div class="game-mobile-card">
            <div class="game-mobile-header">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}"
                         alt="{{ $user->name }}"
                         style="width:48px;height:48px;border-radius:50%;object-fit:cover;border:1px solid var(--nvidia-green);">
                @else
                    <div style="width:48px;height:48px;border-radius:50%;background:#252525;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:18px;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="game-mobile-info">
                    <div class="game-mobile-title">{{ $user->name }}</div>
                    <div class="game-mobile-dev">{{ $user->email }}</div>
                    <div style="display:flex; gap:8px;">
                        <span style="background:rgba(99,102,241,.1);color:#6366f1;font-weight:700;font-size:11px;padding:2px 6px;border-radius:var(--radius-sm);">
                            <i class="fa-solid fa-receipt"></i> {{ $user->transactions_count ?? $user->transactions->count() }} Trx
                        </span>
                        <span style="background:rgba(118,185,0,.1);color:var(--nvidia-green-d);font-weight:700;font-size:11px;padding:2px 6px;border-radius:var(--radius-sm);">
                            <i class="fa-solid fa-gamepad"></i> {{ $user->libraries_count ?? $user->libraries->count() }} Game
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="game-mobile-footer">
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-family:monospace;font-size:12px;font-weight:700;background:#252525;color:#f3f4f6;padding:3px 8px;border:1px solid var(--border-color);">
                        #{{ $user->id }}
                    </div>
                    <div style="font-size:12px;color:#9ca3af;">
                        <i class="fa-solid fa-calendar"></i> Gabung: {{ $user->created_at->format('d M Y') }}
                    </div>
                </div>
                <a href="{{ route('admin.users.show', $user->id) }}" class="btn-outline btn-sm" style="justify-content:center;">
                    <i class="fa-solid fa-eye"></i> Lihat Detail User
                </a>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fa-solid fa-users"></i>
            <p>Tidak ada user ditemukan</p>
        </div>
        @endforelse
    </div>

    @if($users->hasPages())
    <div class="pagination">
        {{ $users->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
    @endif
</div>

@endsection
