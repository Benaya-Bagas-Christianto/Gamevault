@extends('layouts.app')

@section('title', 'Library Game')

@section('content')
<style>
/* Library Specific Styles */
.library-layout {
    display: flex;
    gap: 32px;
    align-items: flex-start;
}
.library-main {
    flex: 1;
    min-width: 0;
    padding-bottom: 48px;
}
.library-sidebar {
    width: 280px;
    flex-shrink: 0;
    background: rgba(0,0,0,0.5);
    backdrop-filter: blur(8px);
    padding: 24px;
    border-radius: 0;
    border: 1px solid var(--c-hairline);
    position: sticky;
    top: 90px;
    height: fit-content;
    margin-bottom: 48px;
}

/* Header & Tabs */
.library-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.library-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.library-header h1 {
    font-size: 2.2rem;
    font-weight: 800;
    margin: 0;
    letter-spacing: -0.5px;
}
.library-count-badge {
    background: rgba(118,185,0,0.15);
    border: 1px solid var(--c-primary);
    color: var(--c-primary);
    font-size: 0.75rem;
    font-weight: 700;
    padding: 3px 10px;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-radius: 0;
}
.library-tabs {
    display: flex;
    align-items: center;
    gap: 20px;
    border-bottom: 1px solid var(--c-hairline);
    margin-bottom: 24px;
    padding-bottom: 0px;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}
.library-tabs::-webkit-scrollbar {
    display: none;
}
.library-tab {
    font-size: 0.95rem;
    color: var(--c-mute);
    text-decoration: none;
    font-weight: 600;
    position: relative;
    padding-bottom: 12px;
    white-space: nowrap;
    transition: color 0.2s;
    cursor: pointer;
}
.library-tab:hover {
    color: var(--c-ink);
}
.library-tab.active {
    color: var(--c-ink);
    font-weight: 700;
}
.library-tab.active::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--c-primary);
    border-radius: 0;
}

/* Controls */
.library-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 12px;
    flex-wrap: wrap;
}
.library-sort {
    color: var(--c-mute);
    font-size: 0.85rem;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
}
.library-sort span {
    color: var(--c-ink);
    font-weight: 700;
}

/* Mobile Toolbar (Search + Filter Trigger) */
.library-mobile-toolbar {
    display: none;
    gap: 8px;
    margin-bottom: 16px;
    width: 100%;
}
.library-mobile-search-wrap {
    flex: 1;
    position: relative;
}
.library-mobile-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--c-mute);
    font-size: 0.85rem;
}
.library-mobile-search {
    width: 100%;
    background: var(--c-surface-soft);
    border: 1px solid var(--c-hairline);
    padding: 10px 12px 10px 34px;
    border-radius: 0;
    color: var(--c-ink);
    font-size: 0.85rem;
    outline: none;
    box-sizing: border-box;
}
.library-mobile-search:focus {
    border-color: var(--c-primary);
}
.library-mobile-filter-btn {
    background: var(--c-surface-soft);
    border: 1px solid var(--c-hairline);
    color: var(--c-ink);
    padding: 0 14px;
    font-size: 0.85rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
    border-radius: 0;
    cursor: pointer;
    white-space: nowrap;
}
.library-mobile-filter-btn.active {
    border-color: var(--c-primary);
    color: var(--c-primary);
    background: rgba(118,185,0,0.1);
}
.filter-active-dot {
    width: 6px;
    height: 6px;
    background: var(--c-primary);
    display: inline-block;
}

/* Grid & Cards */
.epic-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
}
@media(max-width: 1200px) { 
    .epic-grid { grid-template-columns: repeat(2, 1fr); gap: 16px; } 
}
@media(max-width: 768px) { 
    .epic-grid { 
        grid-template-columns: repeat(2, 1fr) !important; 
        gap: 12px !important; 
    } 
    .library-mobile-toolbar {
        display: flex;
    }
    .library-sidebar {
        display: none !important;
    }
    .library-layout {
        flex-direction: column;
        gap: 0;
    }
    .library-header h1 {
        font-size: 1.6rem;
    }
}
@media(max-width: 440px) {
    .epic-grid {
        grid-template-columns: repeat(2, 1fr) !important;
        gap: 8px !important;
    }
}

.epic-card {
    display: flex;
    flex-direction: column;
    position: relative;
    min-width: 0;
    background: var(--c-surface-soft);
    border: 1px solid var(--c-hairline);
    border-radius: 0;
    transition: transform 0.2s, border-color 0.2s;
    overflow: visible;
}
.epic-card:hover {
    border-color: rgba(255,255,255,0.25);
}
.epic-cover-wrap {
    aspect-ratio: 460/215;
    border-radius: 0;
    background: #000;
    position: relative;
    overflow: hidden;
}
.epic-cover-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    border-radius: 0;
    transition: filter 0.2s, transform 0.2s;
    display: block;
}
.epic-card:hover .epic-cover-wrap img {
    filter: brightness(1.1);
}

/* Fav Badge on Cover */
.epic-fav-indicator {
    position: absolute;
    top: 6px;
    left: 6px;
    background: rgba(0,0,0,0.75);
    border: 1px solid #eab308;
    color: #eab308;
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.75rem;
    border-radius: 0;
    z-index: 5;
}

/* Three dots menu overlay */
.epic-menu-btn {
    position: absolute;
    top: 6px;
    right: 6px;
    width: 30px;
    height: 30px;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.15);
    border-radius: 0;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s, background 0.2s;
    z-index: 10;
}
.epic-card:hover .epic-menu-btn {
    opacity: 1;
}
@media(max-width: 768px) {
    .epic-menu-btn {
        opacity: 1 !important;
        width: 28px;
        height: 28px;
        background: rgba(0,0,0,0.8);
    }
}

.epic-dropdown {
    position: absolute;
    top: 40px;
    right: 6px;
    background: #0a0a0a;
    border: 1px solid var(--c-primary);
    border-radius: 0;
    padding: 6px 0;
    min-width: 170px;
    z-index: 40;
    box-shadow: 0 10px 30px rgba(0,0,0,0.85);
    display: none;
}
.epic-dropdown.show {
    display: block;
}
.epic-dropdown-item {
    display: flex;
    align-items: center;
    padding: 9px 14px;
    color: var(--c-ink);
    text-decoration: none;
    font-size: 0.8rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.15s, color 0.15s;
    border-radius: 0;
}
.epic-dropdown-item:hover {
    background: rgba(118,185,0,0.15);
    color: var(--c-primary);
}

/* Card Info */
.epic-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    padding: 10px 12px 12px 12px;
    min-width: 0;
    flex: 1;
    background: var(--c-surface-soft);
}
@media(max-width: 768px) {
    .epic-info {
        padding: 8px 8px 10px 8px;
        gap: 3px;
    }
}
.epic-title {
    font-size: 0.9rem;
    font-weight: 700;
    color: var(--c-ink);
    line-height: 1.25;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    height: 2.5em;
}
@media(max-width: 768px) {
    .epic-title {
        font-size: 0.8rem;
        height: 2.5em;
    }
}
.epic-subtext {
    font-size: 0.72rem;
    color: var(--c-mute);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    margin-top: auto;
}
.epic-quick-action-bar {
    display: flex;
    gap: 6px;
    margin-top: 6px;
}
.epic-key-btn {
    flex: 1;
    background: rgba(255,255,255,0.06);
    border: 1px solid var(--c-hairline);
    color: var(--c-mute);
    font-size: 0.72rem;
    font-weight: 600;
    padding: 5px 8px;
    border-radius: 0;
    cursor: pointer;
    text-align: center;
    white-space: nowrap;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
}
.epic-key-btn:hover {
    background: rgba(118,185,0,0.12);
    border-color: var(--c-primary);
    color: var(--c-primary);
}

/* Sidebar */
.epic-sidebar-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--c-ink);
    letter-spacing: 0.5px;
}
.epic-search {
    width: 100%;
    background: var(--c-surface-soft);
    border: 1px solid var(--c-hairline);
    padding: 10px 12px 10px 36px;
    border-radius: 0;
    color: var(--c-ink);
    font-size: 0.85rem;
    box-sizing: border-box;
}
.epic-search:focus {
    border-color: var(--c-primary);
    outline: none;
}
.epic-search-wrap {
    position: relative;
    margin-bottom: 20px;
}
.epic-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--c-mute);
    font-size: 0.85rem;
}
.epic-filter-group {
    border-top: 1px solid var(--c-hairline);
    padding: 14px 0;
}
.epic-filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--c-mute);
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: color 0.2s;
}
.epic-filter-header:hover {
    color: var(--c-ink);
}
.epic-filter-header i {
    transition: transform 0.2s;
    font-size: 0.75rem;
}
.epic-filter-header.open i {
    transform: rotate(180deg);
}
.epic-filter-content {
    display: none;
    padding-top: 12px;
    padding-bottom: 4px;
    color: var(--c-mute);
    font-size: 0.82rem;
    max-height: 220px;
    overflow-y: auto;
}
.epic-filter-content.open {
    display: block;
}
.epic-filter-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    cursor: pointer;
    user-select: none;
}
.epic-filter-item:hover {
    color: var(--c-ink);
}
.epic-filter-item input[type="checkbox"] {
    accent-color: var(--c-primary);
    width: 14px;
    height: 14px;
    border-radius: 0;
}

/* Mobile Filter Drawer Modal */
.mobile-filter-drawer {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.75);
    backdrop-filter: blur(6px);
    z-index: 99999;
    align-items: flex-end;
    justify-content: center;
}
.mobile-filter-drawer.open {
    display: flex;
}
.mobile-filter-sheet {
    background: #0d0d0d;
    border-top: 2px solid var(--c-primary);
    width: 100%;
    max-height: 82vh;
    overflow-y: auto;
    padding: 20px;
    border-radius: 0;
    box-shadow: 0 -10px 40px rgba(0,0,0,0.9);
    animation: slideUpSheet 0.25s ease-out;
}
@keyframes slideUpSheet {
    from { transform: translateY(100%); }
    to { transform: translateY(0); }
}
.mobile-filter-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
    padding-bottom: 12px;
    border-bottom: 1px solid var(--c-hairline);
}
.mobile-filter-title {
    font-size: 1.1rem;
    font-weight: 800;
    margin: 0;
    color: var(--c-ink);
    display: flex;
    align-items: center;
    gap: 8px;
}
.mobile-filter-footer {
    display: flex;
    gap: 10px;
    margin-top: 20px;
    padding-top: 16px;
    border-top: 1px solid var(--c-hairline);
    position: sticky;
    bottom: 0;
    background: #0d0d0d;
}
</style>

<div class="container section">
    {{-- Header --}}
    <div class="library-header">
        <div class="library-header-left">
            <h1>Library</h1>
            <span class="library-count-badge" id="libraryTotalBadge">{{ $libraryItems->count() }} GAME</span>
        </div>
        <div style="display:flex;align-items:center;gap:12px;">
            <button class="btn btn-outline" style="padding:6px 12px;font-size:0.8rem;border-radius:0;" onclick="location.reload()" title="Refresh Library">
                <i class="fas fa-sync-alt me-1"></i> Refresh
            </button>
        </div>
    </div>
    
    {{-- Tabs --}}
    <div class="library-tabs">
        <a class="library-tab active" onclick="switchTab(this, 'All', event)">Semua Game ({{ $libraryItems->count() }})</a>
        <a class="library-tab" onclick="switchTab(this, 'Favorites', event)">Favorit ({{ $libraryItems->where('is_favorite', true)->count() }})</a>
    </div>

    {{-- Mobile Toolbar (Search & Filter Sheet Trigger) --}}
    <div class="library-mobile-toolbar">
        <div class="library-mobile-search-wrap">
            <i class="fas fa-search"></i>
            <input type="text" id="filterSearchMobile" class="library-mobile-search" placeholder="Cari game di library..." onkeyup="syncSearchAndApply(this.value)">
        </div>
        <button type="button" class="library-mobile-filter-btn" id="mobileFilterBtn" onclick="openMobileFilterDrawer()">
            <i class="fas fa-sliders-h"></i> Filter
            <span id="mobileFilterBadgeDot" class="filter-active-dot" style="display:none;"></span>
        </button>
    </div>

    <div class="library-layout">
        <!-- Main Content -->
        <div class="library-main">
            <div class="library-controls">
                <div class="library-sort">
                    Urutan: <span>Terbaru Dibeli <i class="fas fa-chevron-down" style="font-size:0.65rem; margin-left:4px;"></i></span>
                </div>
                <div id="filterResultCount" style="font-size:0.8rem;color:var(--c-mute);">
                    Menampilkan <strong id="visibleCardCount" style="color:var(--c-ink);">{{ $libraryItems->count() }}</strong> game
                </div>
            </div>

            @if($libraryItems->isEmpty())
                <div class="card text-center" style="padding: 64px 20px; background:var(--c-surface-soft); border:1px dashed var(--c-hairline); border-radius:0;">
                    <div style="font-size:48px;color:var(--c-hairline-strong);margin-bottom:16px"><i class="fa fa-gamepad"></i></div>
                    <h2 class="t-heading-md mb-sm" style="color:var(--c-ink);">Library kamu kosong</h2>
                    <p class="text-mute mb-lg">Beli game sekarang dan koleksi ribuan judul menarik!</p>
                    <a href="{{ route('home') }}" class="btn btn-primary" style="border-radius:0;">Eksplor Toko</a>
                </div>
            @else
                <div class="epic-grid" id="libraryGameGrid">
                    @foreach($libraryItems as $item)
                        @php 
                            $game = $item->game; 
                            $detail = $item->transactionDetail;
                            $transaction = $detail ? $detail->transaction : null;
                            
                            $gameYear = $game->release_date ? $game->release_date->format('Y') : '';
                            $gamePrice = $game->price;
                            $priceTier = 'free';
                            if ($gamePrice > 0 && $gamePrice < 100000) $priceTier = 'under_100k';
                            elseif ($gamePrice >= 100000) $priceTier = 'over_100k';
                            
                            $gameType = strtolower($game->type ?? 'base_game');
                            if($gameType == 'game') $gameType = 'base_game';
                        @endphp
                        <div class="epic-card"
                             data-genres="{{ json_encode($game->genre ?? []) }}"
                             data-type="{{ $gameType }}"
                             data-year="{{ $gameYear }}"
                             data-price-tier="{{ $priceTier }}"
                             data-title="{{ htmlspecialchars(strtolower($game->title)) }}"
                             data-favorite="{{ $item->is_favorite ? '1' : '0' }}">
                            
                            <div class="epic-cover-wrap">
                                <a href="{{ route('game.show', $game->slug) }}" style="display:block;width:100%;height:100%;">
                                    @if($game->cover_image)
                                        <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" loading="lazy">
                                    @else
                                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#181818; border-radius: 0;">
                                            <i class="fa fa-image" style="font-size:2rem;color:#444;"></i>
                                        </div>
                                    @endif
                                </a>

                                {{-- Favorite Star Badge --}}
                                <div class="epic-fav-indicator fav-star-{{ $item->id }}" style="{{ $item->is_favorite ? '' : 'display:none;' }}" title="Favorit">
                                    <i class="fas fa-star"></i>
                                </div>
                                
                                {{-- 3-Dots Action Button --}}
                                <button type="button" class="epic-menu-btn" onclick="toggleDropdown('dropdown-{{ $detail ? $detail->id : $item->id }}', event)" title="Menu Game">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                
                                <div id="dropdown-{{ $detail ? $detail->id : $item->id }}" class="epic-dropdown">
                                    <div class="epic-dropdown-item" onclick="openLicensePinModal('{{ $item->id }}')">
                                        <i class="fas fa-key me-2 text-primary" style="width:16px;"></i> License Key
                                    </div>
                                    <div class="epic-dropdown-item fav-btn-{{ $item->id }}" onclick="toggleFavorite('{{ $item->id }}')">
                                        @if($item->is_favorite)
                                            <i class="fas fa-star text-warning me-2" style="width:16px;"></i> Hapus Favorit
                                        @else
                                            <i class="far fa-star me-2" style="width:16px;"></i> Tambah Favorit
                                        @endif
                                    </div>
                                    @if($transaction)
                                        <div class="epic-dropdown-item" onclick="openInvoiceModal('{{ $transaction->id }}')">
                                            <i class="fas fa-file-invoice me-2" style="width:16px;"></i> Invoice PDF
                                        </div>
                                    @endif
                                    @if($detail && $detail->canRefund())
                                        <a href="{{ route('refund.show', $detail->id) }}" class="epic-dropdown-item text-warning">
                                            <i class="fas fa-undo me-2" style="width:16px;"></i> Request Refund
                                        </a>
                                    @endif
                                    <a href="{{ route('game.show', $game->slug) }}" class="epic-dropdown-item">
                                        <i class="fas fa-external-link-alt me-2" style="width:16px;"></i> Halaman Toko
                                    </a>
                                </div>
                            </div>
                            
                            <div class="epic-info">
                                <a href="{{ route('game.show', $game->slug) }}" style="text-decoration:none;">
                                    <div class="epic-title" title="{{ $game->title }}">{{ $game->title }}</div>
                                </a>
                                <div class="epic-subtext">
                                    @if($transaction && $transaction->paid_at)
                                        Dibeli {{ $transaction->paid_at->format('d M Y') }}
                                    @else
                                        Koleksi Game
                                    @endif
                                </div>
                                <div class="epic-quick-action-bar">
                                    <button type="button" class="epic-key-btn" onclick="openLicensePinModal('{{ $item->id }}')">
                                        <i class="fas fa-key"></i> Key
                                    </button>
                                    <a href="{{ route('game.show', $game->slug) }}" class="epic-key-btn" style="text-decoration:none;">
                                        <i class="fas fa-play"></i> Main
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Desktop Sidebar Filters -->
        <div class="library-sidebar">
            <h2 class="epic-sidebar-title">Filters</h2>
            
            <div class="epic-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="filterSearch" class="epic-search" placeholder="Cari judul..." onkeyup="syncSearchAndApply(this.value)">
            </div>

            <div class="epic-filter-group">
                <div class="epic-filter-header open" onclick="toggleFilter(this)">
                    <span>Genre Game</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="epic-filter-content open">
                    @php
                        $allGenres = [
                            'Aksi', 'Akses Dini', 'Animasi dan Pemodelan', 'Balapan', 
                            'Desain & Ilustrasi', 'F2P', 'Indie', 'Kasual', 'Kekerasan', 
                            'MMO', 'Olahraga', 'Pengeditan Foto', 'Petualangan', 'RPG', 
                            'Simulasi', 'Strategi', 'Utilitas'
                        ];
                    @endphp
                    @foreach($allGenres as $genre)
                        <label class="epic-filter-item">
                            <input type="checkbox" class="filter-cb" data-group="genre" value="{{ htmlspecialchars($genre) }}" onchange="applyFilters()"> {{ $genre }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="epic-filter-group">
                <div class="epic-filter-header open" onclick="toggleFilter(this)">
                    <span>Kategori Game</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="epic-filter-content open">
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="type" value="base_game" onchange="applyFilters()"> Base Game</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="type" value="dlc" onchange="applyFilters()"> DLC / Expansion</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="type" value="bundle" onchange="applyFilters()"> Bundle</label>
                </div>
            </div>
            <div class="epic-filter-group">
                <div class="epic-filter-header" onclick="toggleFilter(this)">
                    <span>Tahun Rilis</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="epic-filter-content">
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="year" value="2024" onchange="applyFilters()"> 2024</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="year" value="2023" onchange="applyFilters()"> 2023</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="year" value="older" onchange="applyFilters()"> 2022 & Sebelumnya</label>
                </div>
            </div>
            <div class="epic-filter-group" style="border-bottom: 1px solid var(--c-hairline);">
                <div class="epic-filter-header" onclick="toggleFilter(this)">
                    <span>Tingkat Harga</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="epic-filter-content">
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="price" value="free" onchange="applyFilters()"> Gratis (Free to Play)</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="price" value="under_100k" onchange="applyFilters()"> Di Bawah Rp 100.000</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="price" value="over_100k" onchange="applyFilters()"> Di Atas Rp 100.000</label>
                </div>
            </div>

            <div style="margin-top:16px;">
                <button type="button" class="btn btn-outline w-full" style="padding:6px;font-size:0.8rem;border-radius:0;" onclick="resetAllFilters()">
                    Reset Filter
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Mobile Filter Sheet / Drawer -->
<div id="mobileFilterDrawer" class="mobile-filter-drawer" onclick="if(event.target===this) closeMobileFilterDrawer()">
    <div class="mobile-filter-sheet">
        <div class="mobile-filter-header">
            <h3 class="mobile-filter-title"><i class="fas fa-sliders-h text-primary"></i> Filter Library</h3>
            <button type="button" style="background:none;border:none;color:var(--c-mute);font-size:1.2rem;cursor:pointer;" onclick="closeMobileFilterDrawer()">✕</button>
        </div>

        <div style="margin-bottom:16px;">
            <div style="font-weight:700;font-size:0.85rem;color:var(--c-ink);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">Kategori Game</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                <label style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);padding:6px 12px;font-size:0.8rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <input type="checkbox" class="filter-cb-mobile" data-group="type" value="base_game"> Base Game
                </label>
                <label style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);padding:6px 12px;font-size:0.8rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <input type="checkbox" class="filter-cb-mobile" data-group="type" value="dlc"> DLC
                </label>
                <label style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);padding:6px 12px;font-size:0.8rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <input type="checkbox" class="filter-cb-mobile" data-group="type" value="bundle"> Bundle
                </label>
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <div style="font-weight:700;font-size:0.85rem;color:var(--c-ink);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">Genre Favorit</div>
            <div style="display:flex;flex-wrap:wrap;gap:6px;max-height:160px;overflow-y:auto;padding-right:4px;">
                @foreach($allGenres as $genre)
                    <label style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);padding:5px 10px;font-size:0.78rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                        <input type="checkbox" class="filter-cb-mobile" data-group="genre" value="{{ htmlspecialchars($genre) }}"> {{ $genre }}
                    </label>
                @endforeach
            </div>
        </div>

        <div style="margin-bottom:16px;">
            <div style="font-weight:700;font-size:0.85rem;color:var(--c-ink);margin-bottom:8px;text-transform:uppercase;letter-spacing:1px;">Tingkat Harga</div>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                <label style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);padding:6px 12px;font-size:0.8rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <input type="checkbox" class="filter-cb-mobile" data-group="price" value="free"> Gratis
                </label>
                <label style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);padding:6px 12px;font-size:0.8rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <input type="checkbox" class="filter-cb-mobile" data-group="price" value="under_100k"> &lt; Rp 100k
                </label>
                <label style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);padding:6px 12px;font-size:0.8rem;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <input type="checkbox" class="filter-cb-mobile" data-group="price" value="over_100k"> &gt; Rp 100k
                </label>
            </div>
        </div>

        <div class="mobile-filter-footer">
            <button type="button" class="btn btn-outline" style="flex:1;border-radius:0;padding:10px;" onclick="resetMobileFilters()">Reset</button>
            <button type="button" class="btn btn-primary" style="flex:2;border-radius:0;padding:10px;font-weight:700;" onclick="applyMobileFiltersAndClose()">Terapkan Filter</button>
        </div>
    </div>
</div>

<!-- Invoice PIN Modal -->
<div id="invoicePinModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--c-surface-elevated); padding:24px; border-radius: 0; width:100%; max-width:400px; border:1px solid var(--c-hairline-strong); box-shadow:0 12px 35px rgba(0,0,0,0.8);">
        <h3 style="margin-top:0; color:var(--c-ink); font-size:1.15rem; font-weight:700;"><i class="fas fa-lock me-2 text-primary"></i> Masukkan PIN Keamanan</h3>
        <p style="color:var(--c-mute); font-size:0.85rem; margin-bottom:16px;">Masukkan 6 digit PIN akun Anda untuk mengunduh invoice ini.</p>
        <form id="invoicePinForm">
            @csrf
            <div style="margin-bottom:16px;">
                <input type="password" id="pinInput" name="pin" required maxlength="6" pattern="\d{6}" inputmode="numeric" autocomplete="one-time-code" placeholder="••••••" style="width:100%; padding:12px; background:var(--c-canvas); border:1px solid var(--c-hairline); color:var(--c-ink); border-radius: 0; font-size:1.2rem; text-align:center; letter-spacing:8px; box-sizing:border-box;">
                <p id="pinError" style="color:var(--c-error); font-size:0.82rem; margin-top:8px; display:none;"></p>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeInvoiceModal()" style="padding:8px 16px; border-radius:0;">Batal</button>
                <button type="submit" class="btn btn-primary" id="downloadBtn" style="padding:8px 20px; border-radius:0; font-weight:700;">Download</button>
            </div>
        </form>
    </div>
</div>

<!-- License PIN Modal -->
<div id="licensePinModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--c-surface-elevated); padding:24px; border-radius: 0; width:100%; max-width:400px; border:1px solid var(--c-hairline-strong); box-shadow:0 12px 35px rgba(0,0,0,0.8);">
        <h3 style="margin-top:0; color:var(--c-ink); font-size:1.15rem; font-weight:700;"><i class="fas fa-key me-2 text-primary"></i> Masukkan PIN Lisensi</h3>
        <p style="color:var(--c-mute); font-size:0.85rem; margin-bottom:16px;">Masukkan 6 digit PIN akun Anda untuk melihat license key ini.</p>
        <form id="licensePinForm">
            @csrf
            <div style="margin-bottom:16px;">
                <input type="password" id="licensePinInput" name="pin" required maxlength="6" pattern="\d{6}" inputmode="numeric" autocomplete="one-time-code" placeholder="••••••" style="width:100%; padding:12px; background:var(--c-canvas); border:1px solid var(--c-hairline); color:var(--c-ink); border-radius: 0; font-size:1.2rem; text-align:center; letter-spacing:8px; box-sizing:border-box;">
                <p id="licensePinError" style="color:var(--c-error); font-size:0.82rem; margin-top:8px; display:none;"></p>
            </div>
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeLicensePinModal()" style="padding:8px 16px; border-radius:0;">Batal</button>
                <button type="submit" class="btn btn-primary" id="viewLicenseBtn" style="padding:8px 20px; border-radius:0; font-weight:700;">Lihat</button>
            </div>
        </form>
    </div>
</div>

<!-- License Key Modal -->
<div id="licenseModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.85); backdrop-filter:blur(6px); z-index:99999; align-items:center; justify-content:center; padding:16px;">
    <div style="background:var(--c-surface-elevated); padding:24px; border-radius: 0; width:100%; max-width:400px; border:1px solid var(--c-primary); text-align:center; box-shadow:0 12px 35px rgba(0,0,0,0.9);">
        <h3 style="margin-top:0; color:var(--c-ink); font-size:1.15rem; font-weight:700;"><i class="fas fa-check-circle me-2 text-primary"></i> License Key Anda</h3>
        <p style="color:var(--c-mute); font-size:0.82rem; margin-bottom:16px;">Gunakan kunci ini untuk mengaktifkan game di platform terkait.</p>
        
        <div id="licenseKeyDisplay" style="background:var(--c-canvas); border:1px dashed var(--c-primary); color:var(--c-primary); padding:14px; font-family:monospace; font-size:1.05rem; letter-spacing:2px; font-weight:800; border-radius: 0; margin-bottom:12px; user-select:all; word-break:break-all;">
            XXXXX-XXXXX-XXXXX
        </div>

        <button type="button" id="copyKeyBtn" class="btn btn-outline w-full" style="border-radius:0; padding:10px; font-size:0.85rem; font-weight:700; margin-bottom:12px;" onclick="copyLicenseKey(this)">
            <i class="fas fa-copy me-1"></i> Salin License Key
        </button>
        
        <button type="button" class="btn btn-primary w-full" style="border-radius:0; padding:10px; font-weight:700;" onclick="closeLicenseModal()">Tutup</button>
    </div>
</div>

<script>
// Invoice Modal Logic
let currentTransactionId = null;

function openInvoiceModal(transactionId) {
    currentTransactionId = transactionId;
    const modal = document.getElementById('invoicePinModal');
    const pinInput = document.getElementById('pinInput');
    const pinError = document.getElementById('pinError');
    
    pinInput.value = '';
    pinError.style.display = 'none';
    pinError.textContent = '';
    
    modal.style.display = 'flex';
    setTimeout(() => pinInput.focus(), 150);
}

function closeInvoiceModal() {
    document.getElementById('invoicePinModal').style.display = 'none';
    currentTransactionId = null;
}

// Handle form submission with AJAX
document.getElementById('invoicePinForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const pinInput = document.getElementById('pinInput');
    const pinError = document.getElementById('pinError');
    const downloadBtn = document.getElementById('downloadBtn');
    const pin = pinInput.value;
    
    if (pin.length !== 6 || !/^\d{6}$/.test(pin)) {
        pinError.textContent = 'PIN harus 6 digit angka.';
        pinError.style.display = 'block';
        return;
    }
    
    downloadBtn.disabled = true;
    downloadBtn.textContent = 'Memproses...';
    pinError.style.display = 'none';
    
    try {
        const formData = new FormData();
        formData.append('pin', pin);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        const response = await fetch(`/invoice/${currentTransactionId}/download`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok && response.headers.get('content-type').includes('application/pdf')) {
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `invoice-${currentTransactionId}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            closeInvoiceModal();
            if (typeof showSweetAlert === 'function') {
                showSweetAlert('Berhasil!', 'Invoice berhasil diunduh!', 'success');
            } else if (typeof showToast === 'function') {
                showToast('Invoice berhasil diunduh!', 'success');
            }
        } else {
            const data = await response.json();
            pinError.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
            pinError.style.display = 'block';
            pinInput.value = '';
            pinInput.focus();
        }
    } catch (error) {
        console.error('Error:', error);
        pinError.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
        pinError.style.display = 'block';
    } finally {
        downloadBtn.disabled = false;
        downloadBtn.textContent = 'Download';
    }
});

// License Modal Logic
function openLicenseModal(key) {
    const modal = document.getElementById('licenseModal');
    document.getElementById('licenseKeyDisplay').innerText = key;
    modal.style.display = 'flex';
}

function closeLicenseModal() {
    document.getElementById('licenseModal').style.display = 'none';
}

function copyLicenseKey(btn) {
    const key = document.getElementById('licenseKeyDisplay').innerText.trim();
    if (navigator.clipboard) {
        navigator.clipboard.writeText(key).then(() => {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check text-primary me-1"></i> Tersalin!';
            btn.style.borderColor = 'var(--c-primary)';
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.style.borderColor = '';
            }, 2000);
        });
    } else {
        const temp = document.createElement('textarea');
        temp.value = key;
        document.body.appendChild(temp);
        temp.select();
        document.execCommand('copy');
        document.body.removeChild(temp);
        btn.innerHTML = '<i class="fas fa-check text-primary me-1"></i> Tersalin!';
        setTimeout(() => { btn.innerHTML = '<i class="fas fa-copy me-1"></i> Salin License Key'; }, 2000);
    }
}

// Dropdown Logic
function toggleDropdown(id, event) {
    event.preventDefault();
    event.stopPropagation();
    
    const dropdown = document.getElementById(id);
    if (!dropdown) return;
    const isShowing = dropdown.classList.contains('show');
    
    document.querySelectorAll('.epic-dropdown').forEach(el => {
        el.classList.remove('show');
    });
    
    if (!isShowing) {
        dropdown.classList.add('show');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.epic-menu-btn') && !e.target.closest('.epic-dropdown')) {
        document.querySelectorAll('.epic-dropdown').forEach(el => el.classList.remove('show'));
    }
});

// Toggle Sidebar Filters
function toggleFilter(headerEl) {
    headerEl.classList.toggle('open');
    const content = headerEl.nextElementSibling;
    if (content) {
        content.classList.toggle('open');
    }
}

let currentTab = 'All';

function switchTab(el, tabName, event) {
    if(event) event.preventDefault();
    document.querySelectorAll('.library-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    currentTab = tabName;
    applyFilters();
}

async function toggleFavorite(libraryId) {
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        const response = await fetch(`/library/${libraryId}/favorite`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const data = await response.json();
        if(data.success) {
            document.querySelectorAll(`.fav-btn-${libraryId}`).forEach(btn => {
                if(data.is_favorite) {
                    btn.innerHTML = '<i class="fas fa-star text-warning me-2" style="width:16px;"></i> Hapus Favorit';
                } else {
                    btn.innerHTML = '<i class="far fa-star me-2" style="width:16px;"></i> Tambah Favorit';
                }
            });
            
            // Toggle star indicator on card cover
            document.querySelectorAll(`.fav-star-${libraryId}`).forEach(star => {
                star.style.display = data.is_favorite ? 'flex' : 'none';
            });

            const card = document.querySelector(`.fav-btn-${libraryId}`)?.closest('.epic-card');
            if(card) {
                card.dataset.favorite = data.is_favorite ? '1' : '0';
            }
            applyFilters();
            
            if (typeof showSweetAlert === 'function') {
                showSweetAlert('Berhasil!', data.is_favorite ? 'Game ditambahkan ke favorit.' : 'Game dihapus dari favorit.', 'success');
            } else if (typeof showToast === 'function') {
                showToast(data.is_favorite ? 'Ditambahkan ke favorit' : 'Dihapus dari favorit', 'success');
            }
        }
    } catch(e) {
        console.error(e);
        if (typeof showSweetAlert === 'function') {
            showSweetAlert('Error!', 'Gagal mengubah status favorit.', 'error');
        }
    }
}

let currentLibraryId = null;
function openLicensePinModal(libraryId) {
    currentLibraryId = libraryId;
    document.getElementById('licensePinModal').style.display = 'flex';
    document.getElementById('licensePinInput').value = '';
    document.getElementById('licensePinError').style.display = 'none';
    setTimeout(() => document.getElementById('licensePinInput').focus(), 150);
}
function closeLicensePinModal() {
    document.getElementById('licensePinModal').style.display = 'none';
    currentLibraryId = null;
}

document.getElementById('licensePinForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const pin = document.getElementById('licensePinInput').value;
    const pinError = document.getElementById('licensePinError');
    const viewBtn = document.getElementById('viewLicenseBtn');
    
    if (pin.length !== 6 || !/^\d{6}$/.test(pin)) {
        pinError.textContent = 'PIN harus 6 digit angka.';
        pinError.style.display = 'block';
        return;
    }
    
    viewBtn.disabled = true;
    viewBtn.textContent = 'Memproses...';
    pinError.style.display = 'none';
    
    try {
        const formData = new FormData();
        formData.append('pin', pin);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        const response = await fetch(`/library/${currentLibraryId}/license`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const data = await response.json();
        if (data.success) {
            closeLicensePinModal();
            openLicenseModal(data.license_key);
        } else {
            pinError.textContent = data.message || 'Terjadi kesalahan.';
            pinError.style.display = 'block';
            document.getElementById('licensePinInput').value = '';
            document.getElementById('licensePinInput').focus();
        }
    } catch (error) {
        pinError.textContent = 'Terjadi kesalahan jaringan.';
        pinError.style.display = 'block';
    } finally {
        viewBtn.disabled = false;
        viewBtn.textContent = 'Lihat';
    }
});

// Search Sync
function syncSearchAndApply(val) {
    const desktopSearch = document.getElementById('filterSearch');
    const mobileSearch = document.getElementById('filterSearchMobile');
    if (desktopSearch && desktopSearch.value !== val) desktopSearch.value = val;
    if (mobileSearch && mobileSearch.value !== val) mobileSearch.value = val;
    applyFilters();
}

// Mobile Filter Sheet Logic
function openMobileFilterDrawer() {
    // Sync desktop checkboxes to mobile checkboxes
    const desktopChecked = Array.from(document.querySelectorAll('.filter-cb:checked')).map(cb => cb.dataset.group + ':' + cb.value);
    document.querySelectorAll('.filter-cb-mobile').forEach(mcb => {
        mcb.checked = desktopChecked.includes(mcb.dataset.group + ':' + mcb.value);
    });
    document.getElementById('mobileFilterDrawer').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeMobileFilterDrawer() {
    document.getElementById('mobileFilterDrawer').classList.remove('open');
    document.body.style.overflow = '';
}

function applyMobileFiltersAndClose() {
    // Sync mobile checkboxes back to desktop checkboxes
    const mobileChecked = Array.from(document.querySelectorAll('.filter-cb-mobile:checked')).map(cb => cb.dataset.group + ':' + cb.value);
    document.querySelectorAll('.filter-cb').forEach(cb => {
        cb.checked = mobileChecked.includes(cb.dataset.group + ':' + cb.value);
    });
    closeMobileFilterDrawer();
    applyFilters();
}

function resetMobileFilters() {
    document.querySelectorAll('.filter-cb-mobile').forEach(cb => cb.checked = false);
}

function resetAllFilters() {
    document.querySelectorAll('.filter-cb').forEach(cb => cb.checked = false);
    document.querySelectorAll('.filter-cb-mobile').forEach(cb => cb.checked = false);
    const desktopSearch = document.getElementById('filterSearch');
    const mobileSearch = document.getElementById('filterSearchMobile');
    if (desktopSearch) desktopSearch.value = '';
    if (mobileSearch) mobileSearch.value = '';
    applyFilters();
}

// Filtering Logic
function applyFilters() {
    const desktopSearch = document.getElementById('filterSearch');
    const mobileSearch = document.getElementById('filterSearchMobile');
    const searchStr = (desktopSearch?.value || mobileSearch?.value || '').toLowerCase();
    
    // Get checked values from both sidebar or active filters
    const checkedBoxes = Array.from(document.querySelectorAll('.filter-cb:checked'));
    const filters = {
        genre: checkedBoxes.filter(cb => cb.dataset.group === 'genre').map(cb => cb.value.toLowerCase()),
        type: checkedBoxes.filter(cb => cb.dataset.group === 'type').map(cb => cb.value.toLowerCase()),
        year: checkedBoxes.filter(cb => cb.dataset.group === 'year').map(cb => cb.value),
        price: checkedBoxes.filter(cb => cb.dataset.group === 'price').map(cb => cb.value)
    };

    // Update active badge indicator on mobile
    const totalFilters = checkedBoxes.length;
    const dot = document.getElementById('mobileFilterBadgeDot');
    const filterBtn = document.getElementById('mobileFilterBtn');
    if (dot && filterBtn) {
        if (totalFilters > 0) {
            dot.style.display = 'inline-block';
            filterBtn.classList.add('active');
        } else {
            dot.style.display = 'none';
            filterBtn.classList.remove('active');
        }
    }

    const cards = document.querySelectorAll('.epic-card');
    let visibleCount = 0;
    
    cards.forEach(card => {
        let show = true;
        
        // Title Search
        const title = card.dataset.title || '';
        if (searchStr && !title.includes(searchStr)) show = false;
        
        // Genre Filter
        if (show && filters.genre.length > 0) {
            try {
                const cardGenres = JSON.parse(card.dataset.genres || '[]').map(g => g.toLowerCase());
                if (!filters.genre.some(g => cardGenres.includes(g))) show = false;
            } catch(e) { show = false; }
        }
        
        // Type Filter
        if (show && filters.type.length > 0) {
            if (!filters.type.includes(card.dataset.type)) show = false;
        }
        
        // Year Filter
        if (show && filters.year.length > 0) {
            const y = parseInt(card.dataset.year);
            let yearMatch = false;
            if (filters.year.includes('2024') && y === 2024) yearMatch = true;
            if (filters.year.includes('2023') && y === 2023) yearMatch = true;
            if (filters.year.includes('older') && y <= 2022) yearMatch = true;
            if (!yearMatch) show = false;
        }
        
        // Price Filter
        if (show && filters.price.length > 0) {
            if (!filters.price.includes(card.dataset.priceTier)) show = false;
        }

        // Favorites Tab
        if (show && currentTab === 'Favorites') {
            if (card.dataset.favorite !== '1') show = false;
        }
        
        card.style.display = show ? 'flex' : 'none';
        if (show) visibleCount++;
    });

    const countEl = document.getElementById('visibleCardCount');
    if (countEl) countEl.textContent = visibleCount;
}
</script>
@endsection
