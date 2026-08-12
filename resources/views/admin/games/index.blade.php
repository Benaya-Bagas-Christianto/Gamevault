@extends('layouts.admin')

@section('page_title', 'Kelola Game')

@push('styles')
<style>
@media (max-width: 900px) {
    .desktop-table-wrap { display: none !important; }
    .mobile-cards-wrap { display: block !important; }

    /* 1. Mencegah container utama melar */
    .mobile-cards-wrap {
        max-width: 100%;
        overflow-x: hidden;
    }
    .game-mobile-card {
        width: 100%;
        max-width: 100%;
        overflow: hidden !important;
        box-sizing: border-box;
    }
    
    /* 2. KUNCI UTAMA: Memaksa elemen dalam Flexbox agar diizinkan menyusut */
    .game-mobile-card * {
        min-width: 0 !important; 
    }
    
    /* 3. Memaksa teks yang panjang untuk turun baris (wrap) */
    .game-mobile-title,
    .game-mobile-dev {
        white-space: normal !important;
        word-break: break-word !important;
        overflow-wrap: break-word !important;
        line-height: 1.4;
    }

    /* 4. Pengaman Pagination (jika jumlah halamannya banyak, ini bisa bikin melar juga) */
    .pagination {
        flex-wrap: wrap !important;
        justify-content: center !important;
    }

    /* 5. Pengaman Filter Bar agar input tidak memanjang menembus batas */
    .filter-bar {
        flex-wrap: wrap !important;
    }
    .filter-bar .form-control, 
    .filter-bar select.form-control {
        min-width: 0 !important;
        flex: 1 1 100% !important;
    }
}

@media (min-width: 901px) {
    .mobile-cards-wrap { display: none !important; }
}

/* Base Mobile Card Styles (applied when visible) */
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
.game-mobile-title { font-size: 15px; font-weight: 800; color: var(--text-primary); margin-bottom: 4px; }
.game-mobile-dev { font-size: 11.5px; color: #9ca3af; margin-bottom: 8px; }
.game-mobile-footer,
.game-mobile-card > div[style*="gap:8px"] { 
    display: flex; 
    flex-direction: row;
    flex-wrap: wrap; 
    gap: 12px; 
    border-top: 1px solid rgba(255,255,255,0.05); 
    padding-top: 12px; 
    width: 100%;
}
</style>
@endpush

@section('content')

<div class="page-header">
    <div class="page-header-left">
        <h1><i class="fa-solid fa-dice-d6" style="color:var(--nvidia-green);"></i> Kelola Game</h1>
        <p>Daftar semua game yang tersedia di GameVault</p>
    </div>
    <a href="{{ route('admin.games.create') }}" class="btn-primary">
        <i class="fa-solid fa-plus"></i> Tambah Game
    </a>
</div>

{{-- Filter Bar --}}
<div class="admin-card" style="margin-bottom:20px;">
    <form method="GET" action="{{ route('admin.games.index') }}" class="filter-bar">
        <i class="fa-solid fa-filter" style="color:#9ca3af;"></i>
        
        <select name="status" class="form-control">
            <option value="">Semua Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Aktif</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Nonaktif</option>
        </select>

        <input type="text" name="search" class="form-control"
               placeholder="Cari judul game..."
               value="{{ request('search') }}">

        <button type="submit" class="btn-primary btn-sm">
            <i class="fa-solid fa-search"></i> Filter
        </button>
        
        @if(request()->hasAny(['status', 'search']))
        <a href="{{ route('admin.games.index') }}" class="btn-outline btn-sm">
            <i class="fa-solid fa-xmark"></i> Reset
        </a>
        @endif
    </form>
</div>

{{-- Games Table --}}
<div class="admin-card">
    <div class="admin-card-header">
        <div class="admin-card-title">
            <i class="fa-solid fa-list"></i>
            Semua Game
            <span style="background:#f0f0f0;color:#666;font-size:11px;font-weight:700;padding:2px 8px;border-radius: 0;margin-left:4px;">
                {{ $games->total() }} total
            </span>
        </div>
    </div>

    <div id="game-data-container">
        <div class="table-wrap desktop-table-wrap" id="table-games">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cover</th>
                    <th>Judul</th>
                    <th>Harga</th>
                    <th>Genre</th>
                    <th>Status</th>
                    <th style="text-align:right;">Terjual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($games as $game)
                <tr>
                    <td>
                        <span style="font-size:12px;color:#9ca3af;font-weight:600;">#{{ $game->id }}</span>
                    </td>
                    <td>
                        @if($game->cover_image)
                            <img src="{{ $game->cover_image_url }}"
                                 alt="{{ $game->title }}"
                                 style="width:72px;height:40px;object-fit:cover;border-radius: 0;border:1px solid var(--border-color);">
                        @else
                            <div style="width:72px;height:40px;border-radius: 0;background:#252525;display:flex;align-items:center;justify-content:center;border:1px solid var(--border-color);">
                                <i class="fa-solid fa-image" style="color:#6b7280;font-size:14px;"></i>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div style="font-weight:700;font-size:14px;">{{ $game->title }}</div>
                        <div style="font-size:11px;color:#9ca3af;margin-top:2px;">
                            {{ $game->developer ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <span style="font-weight:700;color:var(--nvidia-green);">
                            Rp {{ number_format($game->price ?? 0, 0, ',', '.') }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;flex-wrap:wrap;gap:4px;max-width:200px;">
                            @php
                                $genres = is_array($game->genre) ? $game->genre
                                    : (is_string($game->genre) ? json_decode($game->genre, true) ?? [] : []);
                            @endphp
                            @foreach(array_slice($genres, 0, 3) as $g)
                                <span style="background:rgba(118,185,0,.15);color:var(--nvidia-green);font-size:10px;font-weight:600;padding:2px 7px;border-radius: 0;border:1px solid rgba(118,185,0,.2);">
                                    {{ $g }}
                                </span>
                            @endforeach
                            @if(count($genres) > 3)
                                <span style="background:#252525;color:#9ca3af;font-size:10px;padding:2px 7px;border-radius: 0;border:1px solid #333;">
                                    +{{ count($genres) - 3 }}
                                </span>
                            @endif
                        </div>
                    </td>
                    <td>
                        @if($game->is_active)
                            <span class="status-badge status-active">Aktif</span>
                        @else
                            <span class="status-badge status-inactive">Nonaktif</span>
                        @endif
                    </td>
                    <td style="text-align:right;">
                        <span style="font-weight:700;">{{ number_format($game->sold_count ?? 0) }}</span>
                    </td>
                    <td class="action-cell">
                        <div class="action-group" style="justify-content:flex-end; width:100%;">
                            <a href="{{ route('admin.games.edit', $game->slug) }}"
                               class="btn-outline btn-sm btn-icon" title="Edit">
                                <i class="fa-solid fa-pen"></i>
                            </a>
                            <button type="button"
                                class="btn-danger btn-sm btn-icon"
                                title="Hapus"
                                onclick="openDeleteModal('{{ $game->slug }}', '{{ addslashes($game->title) }}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="td-empty">
                        <div class="empty-state">
                            <i class="fa-solid fa-dice-d6"></i>
                            <p>Tidak ada game ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS VIEW --}}
    <div class="mobile-cards-wrap">
        @forelse($games as $game)
        <div class="game-mobile-card" style="padding:0; overflow:hidden;">
            @if($game->cover_image)
                <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" style="width:100%; height:180px; object-fit:cover; object-position:center top; border-bottom:1px solid var(--border-color);">
            @else
                <div style="width:100%; height:180px; background:#252525; display:flex; align-items:center; justify-content:center; border-bottom:1px solid var(--border-color);">
                    <i class="fa-solid fa-dice-d6" style="font-size:48px; color:#555;"></i>
                </div>
            @endif
            
            <div style="padding:16px; display:flex; flex-direction:column; gap:12px;">
                <div>
                    <div style="font-size:16px; font-weight:800; color:var(--text-primary); margin-bottom:4px; line-height:1.4;">{{ $game->title }}</div>
                    <div style="font-size:12px; color:#9ca3af;">{{ $game->developer ?? 'Unknown Dev' }}</div>
                </div>
                
                <div style="display:flex; justify-content:space-between; align-items:center;">
                    <div style="font-size:18px; font-weight:800; color:var(--nvidia-green);">
                        Rp {{ number_format($game->price ?? 0, 0, ',', '.') }}
                    </div>
                    @if($game->is_active)
                        <span style="background:rgba(118,185,0,.15); color:var(--nvidia-green-d); font-weight:700; font-size:11px; padding:4px 8px; border-radius:var(--radius-sm); border: 1px solid rgba(118,185,0,.3);">AKTIF</span>
                    @else
                        <span style="background:rgba(220,38,38,.15); color:#ef4444; font-weight:700; font-size:11px; padding:4px 8px; border-radius:var(--radius-sm); border: 1px solid rgba(220,38,38,.3);">NONAKTIF</span>
                    @endif
                </div>

                <div style="display:flex; gap:16px; font-size:12px; color:#9ca3af; padding-top:12px; border-top:1px solid rgba(255,255,255,0.05);">
                    <div><i class="fa-solid fa-hashtag" style="color:var(--nvidia-green);"></i> ID: <strong style="color:var(--text-primary);">{{ $game->id }}</strong></div>
                    <div><i class="fa-solid fa-arrow-trend-up" style="color:var(--nvidia-green);"></i> Terjual: <strong style="color:var(--text-primary);">{{ number_format($game->sold_count ?? 0) }}</strong></div>
                </div>
                
                <div style="display:flex; gap:8px; margin-top:4px;">
                    <a href="{{ route('admin.games.edit', $game->slug) }}" class="btn-primary" style="flex:1; justify-content:center; padding:10px;">
                        <i class="fa-solid fa-pen"></i> Edit
                    </a>
                    <button type="button" class="btn-danger" style="flex:1; justify-content:center; padding:10px;" onclick="openDeleteModal('{{ $game->slug }}', '{{ addslashes($game->title) }}')">
                        <i class="fa-solid fa-trash"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
        @empty
        <div class="empty-state">
            <i class="fa-solid fa-dice-d6"></i>
            <p>Tidak ada game ditemukan</p>
        </div>
        @endforelse
    </div>
    </div>

    @if($games->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($games->onFirstPage())
            <li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-angle-left"></i></span></li>
        @else
            <li class="page-item"><a class="page-link" href="{{ $games->previousPageUrl() }}" rel="prev"><i class="fa-solid fa-angle-left"></i></a></li>
        @endif

        {{-- Custom Number Pages (Max 4) --}}
        @php
            $start = max(1, $games->currentPage() - 1);
            $end = min($start + 3, $games->lastPage());
            if ($end - $start < 3) {
                $start = max(1, $end - 3);
            }
        @endphp
        
        @for ($i = $start; $i <= $end; $i++)
            @if ($i == $games->currentPage())
                <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $games->url($i) }}">{{ $i }}</a></li>
            @endif
        @endfor

        {{-- Next Page Link --}}
        @if ($games->hasMorePages())
            <li class="page-item"><a class="page-link" href="{{ $games->nextPageUrl() }}" rel="next"><i class="fa-solid fa-angle-right"></i></a></li>
        @else
            <li class="page-item disabled"><span class="page-link"><i class="fa-solid fa-angle-right"></i></span></li>
        @endif
    </ul>
    @endif
</div>

{{-- DELETE MODAL --}}
<div class="modal-overlay" id="deleteModal">
    <div class="modal-box">
        <div class="modal-icon">
            <i class="fa-solid fa-trash"></i>
        </div>
        <div class="modal-title" id="deleteModalTitle">Hapus Game?</div>
        <p class="modal-desc">
            Game yang dihapus tidak dapat dikembalikan. Pastikan game ini tidak memiliki transaksi aktif.
        </p>
        <div class="modal-actions">
            <button type="button" class="btn-outline" onclick="closeDeleteModal()">
                <i class="fa-solid fa-xmark"></i> Batal
            </button>
            <form id="deleteForm" method="POST"
                  data-base-action="{{ route('admin.games.destroy', '__ID__') }}"
                  style="margin:0;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-danger">
                    <i class="fa-solid fa-trash"></i> Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

@endsection
