@extends('layouts.app')

@section('title', 'Toko Game — GameVault')

@section('content')
<style>
    /* ════ STORE RESPONSIVE SYSTEM (Sharp Box Gaming Style) ════ */
    .store-page-wrapper {
        padding-top: 32px;
        padding-bottom: 64px;
    }

    /* Top Controls Bar */
    .store-top-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 20px;
        flex-wrap: wrap;
    }
    .store-brand-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        color: var(--c-ink);
        letter-spacing: 0.5px;
    }
    .store-filter-form {
        display: flex;
        gap: 10px;
        align-items: center;
        flex: 1;
        justify-content: flex-end;
        max-width: 580px;
    }
    .store-search-wrap {
        position: relative;
        flex: 1;
        min-width: 180px;
    }
    .store-search-wrap i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--c-mute);
        font-size: 13px;
        pointer-events: none;
    }
    .store-search-input {
        width: 100%;
        padding: 9px 14px 9px 34px;
        border-radius: 0;
        border: 1px solid var(--c-hairline-strong);
        background: var(--c-canvas);
        color: var(--c-ink);
        font-size: 0.9rem;
        outline: none;
        transition: border-color 0.2s;
    }
    .store-search-input:focus {
        border-color: var(--c-primary);
    }
    .store-sort-select {
        padding: 9px 32px 9px 12px;
        border-radius: 0;
        border: 1px solid var(--c-hairline-strong);
        background-color: var(--c-canvas);
        color: var(--c-ink);
        font-size: 0.9rem;
        font-weight: 600;
        outline: none;
        cursor: pointer;
        appearance: none;
        -webkit-appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg fill="%2376b900" height="20" viewBox="0 0 24 24" width="20" xmlns="http://www.w3.org/2000/svg"><path d="M7 10l5 5 5-5z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 8px center;
        white-space: nowrap;
        flex-shrink: 0;
    }

    /* Quick Genre Chips Bar */
    .store-quick-genres {
        display: flex;
        gap: 8px;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 12px;
        margin-bottom: 20px;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }
    .store-quick-genres::-webkit-scrollbar {
        display: none;
    }
    .store-genre-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: var(--c-surface-soft);
        border: 1px solid var(--c-hairline);
        color: var(--c-mute);
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        border-radius: 0;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .store-genre-chip:hover {
        background: var(--c-surface-elevated);
        color: var(--c-ink);
        border-color: var(--c-primary);
    }
    .store-genre-chip.active {
        background: rgba(118, 185, 0, 0.15);
        border-color: var(--c-primary);
        color: var(--c-primary);
    }

    /* Active Search Alert */
    .store-active-filter-alert {
        margin-bottom: 20px;
        padding: 10px 14px;
        background: var(--c-surface-soft);
        border-left: 3px solid var(--c-primary);
        border-radius: 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        font-size: 0.88rem;
    }

    /* Game Grid */
    .store-games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 20px;
    }

    /* Individual Game Card */
    .store-game-card {
        background: var(--c-surface-soft);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border-radius: 0;
        overflow: hidden;
        border: 1px solid var(--c-hairline);
        display: flex;
        flex-direction: column;
        position: relative;
        transition: border-color 0.2s, transform 0.2s;
    }
    .store-game-card:hover {
        border-color: var(--c-primary);
        transform: translateY(-2px);
    }
    .store-card-thumb-wrap {
        display: block;
        position: relative;
        overflow: hidden;
        aspect-ratio: 460/215;
        background: #000;
    }
    .store-card-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .store-card-thumb-wrap:hover img {
        transform: scale(1.04);
    }

    .store-card-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .store-card-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 8px;
    }
    .store-card-tag {
        background: var(--c-surface-dark);
        color: var(--c-mute);
        font-size: 10px;
        padding: 2px 7px;
        border-radius: 0;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.3px;
        border: 1px solid var(--c-hairline);
    }
    .store-card-title {
        color: var(--c-ink);
        font-weight: 700;
        font-size: 15px;
        line-height: 1.4;
        margin: 0 0 8px 0;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        word-break: break-word;
    }
    .store-card-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: auto;
        margin-bottom: 10px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--c-mute);
    }

    .store-card-pricing {
        margin-bottom: 14px;
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 6px;
    }
    .store-price-original {
        color: var(--c-mute);
        font-size: 11.5px;
        text-decoration: line-through;
        opacity: 0.8;
        white-space: nowrap;
    }
    .store-price-current {
        color: var(--c-ink);
        font-size: 15px;
        font-weight: 800;
        white-space: nowrap;
    }
    .store-price-free {
        color: var(--c-primary);
        font-size: 14px;
        font-weight: 800;
        white-space: nowrap;
        text-transform: uppercase;
    }

    .store-card-actions {
        display: flex;
        gap: 6px;
    }
    .store-cart-btn {
        flex: 1;
        padding: 0 10px;
        height: 38px;
        font-size: 13px;
        font-weight: 700;
        border-radius: 0;
        border: 1px solid transparent;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        white-space: nowrap;
        transition: all 0.2s;
    }
    .store-wishlist-btn {
        width: 38px;
        height: 38px;
        padding: 0;
        border-radius: 0;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    /* ════ MOBILE RESPONSIVE ADAPTATIONS (<= 768px) ════ */
    @media (max-width: 768px) {
        .store-page-wrapper {
            padding-top: 14px;
            padding-bottom: 48px;
        }
        .store-top-bar {
            flex-direction: column;
            align-items: stretch;
            gap: 10px;
            margin-bottom: 12px;
        }
        .store-filter-form {
            max-width: 100%;
            display: flex;
            gap: 8px;
        }
        .store-search-wrap {
            min-width: 0;
            flex: 1;
        }
        .store-search-input {
            padding: 8px 12px 8px 32px;
            font-size: 0.84rem;
        }
        .store-search-wrap i {
            left: 10px;
            font-size: 12px;
        }
        .store-sort-select {
            padding: 8px 26px 8px 10px;
            font-size: 0.82rem;
            max-width: 135px;
        }

        .store-quick-genres {
            margin-bottom: 14px;
            padding-bottom: 8px;
            gap: 6px;
        }
        .store-genre-chip {
            padding: 5px 10px;
            font-size: 0.76rem;
        }

        /* 2-Column Mobile Grid */
        .store-games-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }

        /* Compact Mobile Card Body */
        .store-card-body {
            padding: 10px 10px 12px !important;
        }
        .store-card-title {
            font-size: 13px !important;
            line-height: 1.3 !important;
            margin-bottom: 6px !important;
        }
        .store-card-tag {
            font-size: 9px !important;
            padding: 1px 5px !important;
        }
        .store-card-meta {
            font-size: 10.5px !important;
            margin-bottom: 8px !important;
        }
        .store-price-original {
            font-size: 10px !important;
        }
        .store-price-current,
        .store-price-free {
            font-size: 13px !important;
        }

        .store-cart-btn {
            height: 34px !important;
            font-size: 11.5px !important;
            padding: 0 6px !important;
        }
        .store-wishlist-btn {
            width: 34px !important;
            height: 34px !important;
        }
        .store-wishlist-btn i {
            font-size: 12px !important;
        }
    }
</style>

<div class="container store-page-wrapper">
    
    {{-- Top Controls Bar --}}
    <div class="store-top-bar">
        <h2 class="store-brand-title desktop-only">Toko <span style="color: var(--c-primary);">Game</span></h2>
        
        <form action="{{ route('store') }}" method="GET" class="store-filter-form">
            @if(request('genres'))
                @foreach(request('genres') as $g)
                    <input type="hidden" name="genres[]" value="{{ $g }}">
                @endforeach
            @endif
            
            <div class="store-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul game..." class="store-search-input">
            </div>

            <select name="sort" onchange="this.form.submit()" class="store-sort-select" title="Urutkan Game">
                <option value="newest" {{ request('sort','newest')=='newest'?'selected':'' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>Termurah</option>
                <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>Termahal</option>
                <option value="title" {{ request('sort')=='title'?'selected':'' }}>Judul A-Z</option>
            </select>
        </form>
    </div>

    {{-- Quick Genre Chips (Swipeable Horizontal Bar) --}}
    @php
        $popularQuickGenres = ['Aksi', 'RPG', 'Petualangan', 'Strategi', 'Simulasi', 'Open World', 'Olahraga'];
        $activeGenres = request('genres', []);
    @endphp
    <div class="store-quick-genres">
        <a href="{{ route('store', array_merge(request()->except('genres', 'page'))) }}" 
           class="store-genre-chip {{ empty($activeGenres) ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i> Semua Game
        </a>
        
        @foreach($popularQuickGenres as $pqg)
            @php
                $isSelected = in_array($pqg, $activeGenres);
                $newGenres = $isSelected ? array_diff($activeGenres, [$pqg]) : array_merge($activeGenres, [$pqg]);
                $queryData = array_merge(request()->except('genres', 'page'), !empty($newGenres) ? ['genres' => $newGenres] : []);
            @endphp
            <a href="{{ route('store', $queryData) }}" 
               class="store-genre-chip {{ $isSelected ? 'active' : '' }}">
                {{ $pqg }}
            </a>
        @endforeach
    </div>

    {{-- Active Filter / Search Indicator --}}
    @if(request('search') || request('genres'))
    <div class="store-active-filter-alert">
        <span style="color:var(--c-body);">
            @if(request('search')) Pencarian: "<strong style="color:var(--c-primary);">{{ request('search') }}</strong>" @endif
            @if(request('genres')) Kategori: <strong style="color:var(--c-primary);">{{ implode(', ', request('genres')) }}</strong> @endif
            — {{ $games->total() }} game ditemukan
        </span>
        <a href="{{ route('store') }}" style="color:var(--c-primary);font-size:0.85rem;text-decoration:none;font-weight:700;white-space:nowrap;">
            <i class="fas fa-times me-1"></i>Reset
        </a>
    </div>
    @endif

    {{-- Games Grid Container --}}
    <div id="store-grid" style="scroll-margin-top: 100px;">
        @if($games->isEmpty())
        <div style="text-align:center;padding:70px 20px;background:var(--c-surface-soft);backdrop-filter:blur(12px);border-radius: 0;border:1px dashed var(--c-hairline-strong);">
            <i class="fas fa-search" style="font-size:3rem;color:var(--c-hairline-strong);display:block;margin-bottom:14px;"></i>
            <h4 style="color:var(--c-body);margin-bottom:8px;font-size:1.1rem;">Tidak ada game yang cocok</h4>
            <p style="color:var(--c-mute);font-size:0.88rem;max-width:400px;margin:0 auto 16px;">Coba gunakan kata kunci lain atau reset filter untuk melihat katalog lengkap.</p>
            <a href="{{ route('store') }}" class="btn-primary" style="display:inline-block;padding:9px 22px;text-decoration:none;border-radius:0;font-weight:700;font-size:0.88rem;">
                <i class="fas fa-redo me-2"></i>Lihat Semua Game
            </a>
        </div>
        @else
        <div class="store-games-grid">
            @foreach($games as $game)
            <div class="store-game-card game-card" data-game-id="{{ $game->id }}">
                <a href="{{ route('game.show', $game->slug) }}" class="store-card-thumb-wrap">
                    @if($game->cover_image)
                        <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" loading="lazy" style="{{ in_array($game->id, $ownedIds) ? 'filter: grayscale(1) opacity(0.7);' : '' }}">
                    @else
                        <div style="width:100%;height:100%;background:var(--c-surface-soft);display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-gamepad" style="font-size:2rem;color:var(--c-mute);"></i>
                        </div>
                    @endif

                    @if(in_array($game->id, $ownedIds))
                    <div class="in-library-label" style="top: 6px; left: 6px;">
                        <i class="fas fa-check label-icon"></i>
                        <span class="label-text">DI LIBRARY</span>
                    </div>
                    @elseif(in_array($game->id, $cartIds ?? []))
                    <div class="status-label-cart" style="top: 6px; left: 6px;">
                        <i class="fas fa-shopping-cart label-icon"></i>
                        <span class="label-text">DI KERANJANG</span>
                    </div>
                    @elseif(in_array($game->id, $wishlistIds ?? []))
                    <div class="status-label-wishlist" style="top: 6px; left: 6px;">
                        <i class="fas fa-heart label-icon"></i>
                        <span class="label-text">WISHLIST</span>
                    </div>
                    @endif

                    @if($game->discount_percent > 0)
                    <div style="position:absolute;top:6px;right:6px;background:var(--c-primary);color:#000;font-size:11px;font-weight:800;padding:3px 6px;border-radius:0;z-index:2;">
                        -{{ $game->discount_percent }}%
                    </div>
                    @elseif($game->price == 0)
                    <div style="position:absolute;top:6px;right:6px;background:var(--c-accent-green-pale);color:#000;font-size:10px;font-weight:800;padding:3px 7px;border-radius:0;letter-spacing:0.5px;text-transform:uppercase;z-index:2;">
                        GRATIS
                    </div>
                    @endif
                </a>

                <div class="store-card-body">
                    <div class="store-card-tags">
                        @foreach(array_slice($game->genre ?? [], 0, 1) as $genre)
                            <span class="store-card-tag">{{ $genre }}</span>
                        @endforeach
                    </div>

                    <a href="{{ route('game.show', $game->slug) }}" style="text-decoration:none;">
                        <h3 class="store-card-title">{{ $game->title }}</h3>
                    </a>
                    
                    @php
                        $avgRating = $game->reviews_count > 0 ? ($game->reviews_avg_rating ?? round($game->reviews->avg('rating'), 1)) : 0;
                    @endphp
                    <div class="store-card-meta">
                        @if(request('sort', 'newest') == 'newest' && $game->release_date)
                            <i class="fas fa-calendar-alt" style="color:var(--c-mute);"></i>
                            <span>{{ $game->release_date->format('M Y') }}</span>
                        @else
                            <i class="fas fa-star" style="color:#fbbf24;"></i>
                            <span>{{ $avgRating ?: 'Baru' }}</span>
                        @endif
                    </div>

                    <div class="store-card-pricing">
                        @if($game->price == 0)
                            <span class="store-price-free">GRATIS</span>
                        @else
                            @if($game->discount_percent > 0)
                                <span class="store-price-original">Rp {{ number_format($game->original_price, 0, ',', '.') }}</span>
                            @endif
                            <span class="store-price-current">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <div class="store-card-actions">
                        <button class="cart-btn btn {{ in_array($game->id, $cartIds) ? 'btn-primary' : 'btn-outline' }} store-cart-btn" data-game-slug="{{ $game->slug }}">
                            <i class="fas fa-shopping-cart"></i>
                            <span>{{ in_array($game->id, $cartIds) ? 'Di Keranjang' : 'Beli' }}</span>
                        </button>
                        <button class="wishlist-btn btn {{ in_array($game->id, $wishlistIds) ? 'btn-primary' : 'btn-outline' }} store-wishlist-btn" data-game-slug="{{ $game->slug }}" title="Wishlist">
                            <i class="{{ in_array($game->id, $wishlistIds) ? 'fas' : 'far' }} fa-heart"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($games->hasPages())
        <div id="pagination-container" style="margin-top:36px;display:flex;justify-content:center;opacity:1;transition:opacity 0.3s;">
            {{ $games->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
        @endif
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth Scroll to Top helper
    function smoothScrollToTop(duration = 600) {
        const start = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
        const startTime = performance.now();

        function scroll() {
            const currentTime = performance.now();
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeProgress = progress < 0.5 ? 4 * progress * progress * progress : 1 - Math.pow(-2 * progress + 2, 3) / 2;
            const position = start * (1 - easeProgress);
            
            window.scrollTo(0, position);
            document.documentElement.scrollTop = position;
            document.body.scrollTop = position;
            
            if (progress < 1) {
                requestAnimationFrame(scroll);
            } else {
                window.scrollTo(0, 0);
            }
        }
        requestAnimationFrame(scroll);
    }

    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('#pagination-container a');
        if (pageLink && pageLink.closest('#store-grid')) {
            e.preventDefault();
            const grid = document.getElementById('store-grid');
            const paginationContainer = document.getElementById('pagination-container');
            if (!grid || grid.style.pointerEvents === 'none') return;
            
            grid.style.pointerEvents = 'none';
            if (paginationContainer) paginationContainer.style.pointerEvents = 'none';
            
            smoothScrollToTop(600);
            
            setTimeout(() => {
                if (paginationContainer) paginationContainer.style.opacity = '0.4';
                grid.style.opacity = '0.4';

                fetch(pageLink.href)
                    .then(r => r.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const newGrid = doc.getElementById('store-grid');
                        
                        if (newGrid) {
                            grid.innerHTML = newGrid.innerHTML;
                            grid.style.opacity = '1';
                            grid.style.pointerEvents = 'auto';
                            window.history.pushState({}, '', pageLink.href);
                        }
                    })
                    .catch(() => window.location.href = pageLink.href);
            }, 650);
        }
    });
});
</script>
@endsection
