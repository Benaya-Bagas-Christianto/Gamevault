@extends('layouts.app')

@section('title', 'Downloadable Content for ' . $game->title . ' — GameVault')

@section('content')
<style>
    /* ════ DLC Page Design & Responsive System (Sharp Box Gaming Style) ════ */
    .dlc-page-container {
        padding-top: 24px;
        margin-bottom: 60px;
    }

    /* Back Link */
    .dlc-back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--c-mute);
        text-decoration: none;
        font-size: 0.85rem;
        font-weight: 500;
        padding: 6px 14px;
        background: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        transition: all 0.2s ease;
    }
    .dlc-back-link:hover {
        color: var(--c-primary);
        border-color: var(--c-primary);
        background: var(--c-surface-soft);
        transform: translateX(-2px);
    }

    /* Hero Banner */
    .dlc-hero {
        background-color: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        margin-top: 16px;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 6px 24px rgba(0,0,0,0.4);
    }
    .dlc-hero-bg {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 55%;
        background-repeat: no-repeat;
        background-position: right center;
        background-size: cover;
        opacity: 0.35;
        pointer-events: none;
    }
    .dlc-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, var(--c-surface-dark) 45%, rgba(18,22,25,0.75) 100%);
        pointer-events: none;
    }
    .dlc-hero-content {
        position: relative;
        padding: 40px 36px;
        min-height: 170px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        z-index: 2;
    }
    .dlc-hero-tag {
        color: var(--c-primary);
        font-size: 0.85rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 1.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dlc-hero-title {
        color: #fff;
        font-size: 2.2rem;
        font-weight: 800;
        margin: 0;
        line-height: 1.2;
        text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        word-break: break-word;
    }

    /* Tabs Bar */
    .dlc-tabs-bar {
        display: flex;
        gap: 20px;
        border-bottom: 1px solid var(--c-hairline);
        margin-bottom: 24px;
    }
    .dlc-tab-btn {
        color: var(--c-ink);
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        padding: 12px 20px;
        border-bottom: 3px solid var(--c-primary);
        background: rgba(255,255,255,0.03);
        display: inline-flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.5px;
        border-radius: 0;
    }
    .dlc-tab-badge {
        background: var(--c-primary);
        color: #000;
        font-size: 0.75rem;
        font-weight: 800;
        padding: 2px 8px;
        border-radius: 0;
    }

    /* Main Grid Layout */
    .dlc-layout {
        display: flex;
        gap: 28px;
        align-items: flex-start;
    }
    .dlc-sidebar {
        width: 250px;
        flex-shrink: 0;
    }
    .dlc-main {
        flex: 1;
        min-width: 0;
    }

    /* Sidebar Filters */
    .dlc-sidebar-title {
        color: var(--c-mute);
        font-size: 0.8rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 12px;
        letter-spacing: 1px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .dlc-filter-group {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .filter-btn {
        background: transparent;
        padding: 10px 14px;
        border-radius: 0;
        color: var(--c-mute);
        font-size: 0.92rem;
        font-weight: 600;
        display: flex;
        justify-content: space-between;
        align-items: center;
        cursor: pointer;
        border: 1px solid transparent;
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
        user-select: none;
    }
    .filter-btn:hover {
        background: rgba(255,255,255,0.04);
        color: var(--c-ink);
    }
    .filter-btn.active {
        background: rgba(118, 185, 0, 0.12);
        color: var(--c-primary);
        border-left: 3px solid var(--c-primary);
        border-color: rgba(118, 185, 0, 0.25);
        border-left-color: var(--c-primary);
        border-radius: 0;
    }
    .filter-count {
        background: rgba(255,255,255,0.07);
        padding: 2px 8px;
        border-radius: 0;
        font-size: 0.78rem;
        color: var(--c-mute);
        transition: all 0.2s;
    }
    .filter-btn.active .filter-count {
        background: var(--c-primary);
        color: #000;
        font-weight: 700;
        border-radius: 0;
    }

    /* DLC Items Container */
    .dlc-list-card {
        background: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        overflow: hidden;
    }
    .dlc-list-header {
        background: rgba(0,0,0,0.35);
        padding: 12px 20px;
        color: var(--c-ink);
        font-size: 0.95rem;
        font-weight: 700;
        border-bottom: 1px solid var(--c-hairline);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .dlc-list-body {
        padding: 16px;
    }

    /* DLC Card Item (Desktop Default) */
    .dlc-item {
        display: flex;
        align-items: center;
        gap: 16px;
        background: rgba(0,0,0,0.22);
        padding: 12px 16px;
        margin-bottom: 12px;
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        transition: all 0.2s ease;
    }
    .dlc-item:last-child {
        margin-bottom: 0;
    }
    .dlc-item:hover {
        background: rgba(255,255,255,0.03);
        border-color: rgba(118, 185, 0, 0.35);
    }

    /* On Desktop, top container unwraps its children directly into .dlc-item flex row */
    .dlc-item-top {
        display: contents;
    }
    .dlc-item-thumb {
        width: 140px;
        height: 70px;
        flex-shrink: 0;
        background: #000;
        border-radius: 0;
        overflow: hidden;
        border: 1px solid var(--c-hairline);
    }
    .dlc-item-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        border-radius: 0;
    }
    .dlc-item-thumb-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--c-mute);
        font-size: 1.5rem;
        border-radius: 0;
    }
    .dlc-item-info {
        flex: 1;
        min-width: 0;
    }
    .dlc-item-title {
        color: var(--c-ink);
        font-size: 1.05rem;
        font-weight: 600;
        margin: 0 0 6px 0;
        line-height: 1.3;
        word-break: break-word;
    }
    .dlc-item-badge {
        color: var(--c-mute);
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .dlc-item-action {
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: flex-end;
        gap: 8px;
        flex-shrink: 0;
        min-width: 150px;
    }
    .dlc-item-price {
        white-space: nowrap;
        text-align: right;
    }
    .price-free {
        color: var(--c-primary);
        font-size: 1.05rem;
        font-weight: 700;
    }
    .price-val {
        color: var(--c-ink);
        font-size: 1.05rem;
        font-weight: 700;
    }
    .add-to-cart-dlc {
        padding: 7px 16px;
        background: var(--c-primary);
        color: #000;
        border: none;
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        border-radius: 0;
        transition: all 0.2s ease;
        white-space: nowrap;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }
    .add-to-cart-dlc:hover:not(:disabled) {
        filter: brightness(1.12);
        transform: translateY(-1px);
    }
    .add-to-cart-dlc.in-cart,
    .add-to-cart-dlc:disabled {
        background: var(--c-surface-soft) !important;
        color: var(--c-mute) !important;
        cursor: not-allowed !important;
        transform: none !important;
        border: 1px solid var(--c-hairline) !important;
        border-radius: 0 !important;
    }

    /* Filter Empty State */
    .dlc-empty-filter {
        display: none;
        text-align: center;
        padding: 40px 16px;
        color: var(--c-mute);
    }
    .dlc-empty-filter i {
        font-size: 2.2rem;
        margin-bottom: 12px;
        color: var(--c-hairline);
        display: block;
    }

    /* ════ MOBILE RESPONSIVE ADAPTATIONS (<= 768px) ════ */
    @media (max-width: 768px) {
        .dlc-page-container {
            padding-top: 16px;
            margin-bottom: 40px;
            padding-left: 12px;
            padding-right: 12px;
        }
        .dlc-back-link {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 0;
        }
        .dlc-hero {
            margin-top: 12px;
            margin-bottom: 16px;
            border-radius: 0;
        }
        .dlc-hero-content {
            padding: 20px 16px;
            min-height: 120px;
        }
        .dlc-hero-bg {
            width: 70%;
            opacity: 0.25;
        }
        .dlc-hero-tag {
            font-size: 0.72rem;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .dlc-hero-title {
            font-size: 1.4rem;
        }
        .dlc-tabs-bar {
            margin-bottom: 16px;
        }
        .dlc-tab-btn {
            font-size: 0.88rem;
            padding: 10px 14px;
            border-radius: 0;
        }
        .dlc-tab-badge {
            border-radius: 0;
        }

        /* 1-column layout on mobile */
        .dlc-layout {
            flex-direction: column;
            gap: 16px;
        }
        .dlc-sidebar {
            width: 100%;
        }

        /* Filter buttons become horizontal scrollable sharp boxes */
        .dlc-sidebar-title {
            font-size: 0.75rem;
            margin-bottom: 8px;
        }
        .dlc-filter-group {
            flex-direction: row;
            overflow-x: auto;
            white-space: nowrap;
            gap: 6px;
            padding-bottom: 8px;
            scrollbar-width: thin;
            -webkit-overflow-scrolling: touch;
        }
        .dlc-filter-group::-webkit-scrollbar {
            height: 3px;
        }
        .dlc-filter-group::-webkit-scrollbar-thumb {
            background: var(--c-hairline);
            border-radius: 0;
        }
        .filter-btn {
            flex-shrink: 0;
            padding: 6px 12px;
            border-radius: 0;
            font-size: 0.82rem;
            background: var(--c-surface-dark);
            border: 1px solid var(--c-hairline);
            gap: 8px;
        }
        .filter-btn.active {
            background: rgba(118, 185, 0, 0.16);
            border-color: var(--c-primary);
            border-left: 2px solid var(--c-primary);
            color: var(--c-primary);
            border-radius: 0;
        }
        .filter-count {
            border-radius: 0;
        }

        .dlc-list-card {
            border-radius: 0;
        }
        .dlc-list-header {
            padding: 10px 14px;
            font-size: 0.88rem;
        }
        .dlc-list-body {
            padding: 10px;
        }

        /* DLC item mobile card structure: sharp rectangular boxes */
        .dlc-item {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            padding: 12px;
            margin-bottom: 10px;
            background: var(--c-surface-soft);
            border-radius: 0;
        }
        .dlc-item-top {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }
        .dlc-item-thumb {
            width: 86px;
            height: 50px;
            flex-shrink: 0;
            border-radius: 0;
        }
        .dlc-item-info {
            flex: 1;
            min-width: 0;
        }
        .dlc-item-title {
            font-size: 0.9rem;
            margin: 0 0 4px 0;
            line-height: 1.25;
        }
        .dlc-item-badge {
            font-size: 0.72rem;
        }
        .dlc-item-action {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            min-width: 0;
            padding-top: 10px;
            border-top: 1px solid var(--c-hairline);
            gap: 10px;
        }
        .price-free, .price-val {
            font-size: 0.98rem;
        }
        .add-to-cart-dlc {
            padding: 7px 14px;
            font-size: 0.78rem;
            flex-shrink: 0;
            border-radius: 0;
        }
    }
</style>

<div class="container dlc-page-container">
    
    {{-- Breadcrumb / Header --}}
    <div>
        <a href="{{ route('game.show', $game->slug) }}" class="dlc-back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke {{ $game->title }}
        </a>
    </div>

    {{-- Hero Section --}}
    <div class="dlc-hero">
        <div class="dlc-hero-bg" style="background-image: url('{{ $game->cover_image_url }}');"></div>
        <div class="dlc-hero-overlay"></div>

        <div class="dlc-hero-content">
            <div class="dlc-hero-tag">
                <i class="fas fa-puzzle-piece"></i> Downloadable Content For
            </div>
            <h1 class="dlc-hero-title">{{ $game->title }}</h1>
        </div>
    </div>

    {{-- Tabs Bar --}}
    <div class="dlc-tabs-bar">
        <div class="dlc-tab-btn">
            <i class="fas fa-layer-group" style="color: var(--c-primary);"></i>
            Daftar DLC
            <span class="dlc-tab-badge" id="dlc-badge-count">{{ $game->dlcs->count() }}</span>
        </div>
    </div>

    {{-- Content Area --}}
    <div class="dlc-layout">
        
        @php
            $genresCount = [];
            foreach($game->dlcs as $dlc) {
                if(is_array($dlc->genre)) {
                    foreach($dlc->genre as $g) {
                        $genresCount[$g] = ($genresCount[$g] ?? 0) + 1;
                    }
                } elseif(is_string($dlc->genre)) {
                    $g = $dlc->genre;
                    $genresCount[$g] = ($genresCount[$g] ?? 0) + 1;
                }
            }
            arsort($genresCount);
        @endphp

        {{-- Filters (Sidebar on desktop, horizontal scroll on mobile) --}}
        <div class="dlc-sidebar">
            <div class="dlc-sidebar-title">
                <i class="fas fa-filter" style="color: var(--c-primary);"></i> Filter Kategori
            </div>
            
            <div class="dlc-filter-group">
                <div class="filter-btn active" data-genre="all">
                    <span>Semua</span>
                    <span class="filter-count">{{ $game->dlcs->count() }}</span>
                </div>

                @foreach($genresCount as $genre => $count)
                    <div class="filter-btn" data-genre="{{ htmlspecialchars($genre) }}">
                        <span>{{ $genre }}</span>
                        <span class="filter-count">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- DLC List --}}
        <div class="dlc-main">
            
            <div class="dlc-list-card">
                <div class="dlc-list-header">
                    <div>Semua Konten Tambahan</div>
                    <div style="font-size: 0.8rem; color: var(--c-mute); font-weight: 500;">
                        Menampilkan <span id="dlc-visible-count" style="color: var(--c-primary); font-weight: 700;">{{ $game->dlcs->count() }}</span> item
                    </div>
                </div>

                <div class="dlc-list-body">
                    @if($game->dlcs->count() > 0)
                        @foreach($game->dlcs as $dlc)
                            @php
                                $inCart = in_array($dlc->id, $cartGameIds);
                                $dlcGenres = is_array($dlc->genre) ? $dlc->genre : (is_string($dlc->genre) ? [$dlc->genre] : []);
                            @endphp
                            <div class="dlc-item" data-genres="{{ htmlspecialchars(json_encode($dlcGenres)) }}">
                                
                                <div class="dlc-item-top">
                                    {{-- Image --}}
                                    <div class="dlc-item-thumb">
                                        @if($dlc->cover_image)
                                            <img src="{{ $dlc->cover_image_url }}" alt="{{ $dlc->title }}" loading="lazy">
                                        @else
                                            <div class="dlc-item-thumb-placeholder">
                                                <i class="fas fa-gamepad"></i>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Details --}}
                                    <div class="dlc-item-info">
                                        <h3 class="dlc-item-title">{{ $dlc->title }}</h3>
                                        <div class="dlc-item-badge">
                                            <i class="fab fa-windows" style="color: var(--c-mute);"></i> Perlu game dasar
                                        </div>
                                    </div>
                                </div>

                                {{-- Price & Cart Action --}}
                                <div class="dlc-item-action">
                                    <div class="dlc-item-price">
                                        @if($dlc->price == 0)
                                            <span class="price-free">Gratis</span>
                                        @else
                                            <span class="price-val">Rp {{ number_format($dlc->price, 0, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    
                                    <button type="button" 
                                            class="add-to-cart-dlc {{ $inCart ? 'in-cart' : '' }}" 
                                            data-game-slug="{{ $dlc->slug }}"
                                            {{ $inCart ? 'disabled' : '' }}>
                                        @if($inCart)
                                            <i class="fas fa-check"></i> Di Keranjang
                                        @else
                                            <i class="fas fa-shopping-cart"></i> Tambah ke Cart
                                        @endif
                                    </button>
                                </div>
                            </div>
                        @endforeach

                        {{-- Empty Filter Message --}}
                        <div class="dlc-empty-filter" id="dlc-empty-filter">
                            <i class="fas fa-search"></i>
                            <div>Tidak ada DLC yang cocok dengan kategori yang dipilih.</div>
                        </div>
                    @else
                        <div style="text-align: center; padding: 48px 16px; color: var(--c-mute);">
                            <i class="fas fa-box-open" style="font-size: 2.5rem; margin-bottom: 12px; color: var(--c-hairline); display: block;"></i>
                            Belum ada konten tambahan untuk game ini.
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Script for DLC Add to Cart & Filtering --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Filtering Logic
    const filterBtns = document.querySelectorAll('.filter-btn');
    const dlcItems = document.querySelectorAll('.dlc-item');
    const visibleCountEl = document.getElementById('dlc-visible-count');
    const emptyFilterEl = document.getElementById('dlc-empty-filter');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all
            filterBtns.forEach(b => b.classList.remove('active'));

            // Set this as active
            this.classList.add('active');

            const targetGenre = this.dataset.genre;
            let visibleCount = 0;

            // Show/hide items
            dlcItems.forEach(item => {
                if (targetGenre === 'all') {
                    item.style.display = '';
                    visibleCount++;
                } else {
                    try {
                        const genres = JSON.parse(item.dataset.genres || '[]');
                        if (genres.includes(targetGenre)) {
                            item.style.display = '';
                            visibleCount++;
                        } else {
                            item.style.display = 'none';
                        }
                    } catch(e) {
                        item.style.display = 'none';
                    }
                }
            });

            if (visibleCountEl) {
                visibleCountEl.innerText = visibleCount;
            }

            if (emptyFilterEl) {
                emptyFilterEl.style.display = visibleCount === 0 ? 'block' : 'none';
            }
        });
    });

    // Add to Cart Logic
    const dlcButtons = document.querySelectorAll('.add-to-cart-dlc');
    
    dlcButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            if (typeof IS_AUTHENTICATED !== 'undefined' && !IS_AUTHENTICATED) {
                if (typeof showLoginRequiredPopup === 'function') {
                    showLoginRequiredPopup('keranjang');
                } else if (typeof showSweetAlert === 'function') {
                    showSweetAlert('Perhatian!', 'Silakan login terlebih dahulu untuk menambahkan game ke keranjang.', 'warning');
                }
                return;
            }

            const slug = this.dataset.gameSlug;
            if (this.classList.contains('in-cart') || this.disabled) return;
            
            const originalHtml = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
            this.disabled = true;

            fetch(`/cart/${slug}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (res.status === 401) {
                    if (typeof showLoginRequiredPopup === 'function') {
                        showLoginRequiredPopup('keranjang');
                    }
                    throw new Error('Unauthenticated');
                }
                return res.json();
            })
            .then(data => {
                if (data.in_cart || data.success) {
                    this.innerHTML = '<i class="fas fa-check"></i> Di Keranjang';
                    this.classList.add('in-cart');
                    this.disabled = true;
                    
                    if (typeof showCenterPopup === 'function') {
                        showCenterPopup(data.message || 'Game berhasil ditambahkan ke keranjang!', true);
                    }
                    if (typeof updateBadge === 'function') {
                        updateBadge('cart-count', data.count);
                    }
                } else {
                    if (typeof showCenterPopup === 'function') {
                        showCenterPopup(data.error || data.message || 'Gagal menambahkan ke keranjang', false);
                    }
                    this.innerHTML = originalHtml;
                    this.disabled = false;
                }
            })
            .catch(err => {
                this.innerHTML = originalHtml;
                this.disabled = false;
                if (err.message !== 'Unauthenticated') {
                    console.error(err);
                    if (typeof showCenterPopup === 'function') {
                        showCenterPopup('Terjadi kesalahan sistem.', false);
                    }
                }
            });
        });
    });
});
</script>
@endsection
