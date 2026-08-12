@extends('layouts.app')

@section('title', 'Toko Game')

@section('content')
<div class="container store-container" style="padding-top:40px;padding-bottom:64px;">
    <style>
        .store-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; gap: 16px; }
        .store-form { display: flex; gap: 12px; align-items: center; }
        .store-search-input { padding: 8px 16px; border-radius: 0; border: 1px solid var(--c-hairline-strong); background: var(--c-canvas); color: var(--c-ink); outline: none; flex: 1; min-width: 150px; }
        .store-sort-select { padding: 8px 36px 8px 16px; border-radius: 0; border: 1px solid var(--c-hairline-strong); background-color: var(--c-canvas); color: var(--c-ink); outline: none; cursor: pointer; appearance: none; -webkit-appearance: none; background-image: url('data:image/svg+xml;utf8,<svg fill=%22%23ffffff%22 height=%2224%22 viewBox=%220 0 24 24%22 width=%2224%22 xmlns=%22http://www.w3.org/2000/svg%22><path d=%22M7 10l5 5 5-5z%22/></svg>'); background-repeat: no-repeat; background-position: right 8px center; }
        
        @media (max-width: 768px) {
            .store-header { flex-direction: column; align-items: flex-start; margin-bottom: 16px; }
            .store-form { width: 100%; flex-wrap: wrap; }
            .store-search-input { width: 100%; flex: 1 1 100%; }
            .store-sort-select { width: 100%; flex: 1 1 100%; }
            .container.store-container { padding-top: 16px !important; }
        }
    </style>
    <div class="store-header">
        <h2 class="t-heading-lg desktop-only">Game<span class="text-green">Vault</span></h2>
        
        <form action="{{ route('store') }}" method="GET" class="store-form">
            @if(request('genres'))
                @foreach(request('genres') as $g)
                    <input type="hidden" name="genres[]" value="{{ $g }}">
                @endforeach
            @endif
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari game..." class="store-search-input">
            <select name="sort" onchange="this.form.submit()" class="store-sort-select">
                <option value="newest" {{ request('sort','newest')=='newest'?'selected':'' }}>Terbaru</option>
                <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>Harga Terendah</option>
                <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>Harga Tertinggi</option>
                <option value="title" {{ request('sort')=='title'?'selected':'' }}>Judul A-Z</option>
            </select>
        </form>
    </div>

    @if(request('search') || request('genres'))
    <div style="margin-bottom:24px;padding:12px 16px;background:var(--c-surface-soft);border-left:3px solid var(--c-primary);border-radius: 0;display:flex;align-items:center;justify-content:space-between;">
        <span style="color:var(--c-body);font-size:0.95rem;">
            @if(request('search')) Pencarian: "<strong class="text-green">{{ request('search') }}</strong>" @endif
            @if(request('genres')) Kategori: <strong class="text-green">{{ implode(', ', request('genres')) }}</strong> @endif
            — {{ $games->total() }} game ditemukan
        </span>
        <a href="{{ route('store') }}" style="color:var(--c-primary);font-size:0.85rem;text-decoration:none;"><i class="fas fa-times me-1"></i>Reset</a>
    </div>
    @endif

    <div class="col-12" id="store-grid" style="scroll-margin-top: 100px;">
        @if($games->isEmpty())
        <div style="text-align:center;padding:80px 20px;background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius: 0;border:1px dashed var(--c-hairline-strong);">
            <i class="fas fa-search" style="font-size:3.5rem;color:var(--c-hairline-strong);display:block;margin-bottom:16px;"></i>
            <h4 style="color:var(--c-body);margin-bottom:8px;">Tidak ada game ditemukan</h4>
            <a href="{{ route('store') }}" class="btn-primary" style="display:inline-block;padding:10px 24px;text-decoration:none;margin-top:16px;">
                <i class="fas fa-redo me-2"></i>Lihat Semua Game
            </a>
        </div>
        @else
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:24px;">
            @foreach($games as $game)
            <div class="game-card" data-game-id="{{ $game->id }}" style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius: 0;overflow:hidden;border:1px solid var(--c-hairline);display:flex;flex-direction:column;position:relative;transition:border-color 0.2s;" onmouseenter="this.style.borderColor='var(--c-primary)';" onmouseleave="this.style.borderColor='var(--c-hairline)';">
                <a href="{{ route('game.show', $game->slug) }}" style="display:block;position:relative;overflow:hidden;aspect-ratio:16/9;">
                    @if($game->cover_image)
                        <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s; {{ in_array($game->id, $ownedIds) ? 'filter: grayscale(1) opacity(0.7);' : '' }}" onmouseenter="this.style.transform='scale(1.04)';" onmouseleave="this.style.transform='scale(1)';">
                    @else
                        <div style="width:100%;height:100%;background:var(--c-surface-soft);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;">
                            <i class="fas fa-gamepad" style="font-size:2.5rem;color:var(--c-mute);"></i>
                        </div>
                    @endif
                    @if(in_array($game->id, $ownedIds))
                    <div class="in-library-label" style="top: 8px; left: 8px;">
                        <i class="fas fa-check label-icon"></i>
                        <span class="label-text">DI LIBRARY</span>
                    </div>
                    @elseif(in_array($game->id, $cartIds ?? []))
                    <div class="status-label-cart" style="top: 8px; left: 8px;">
                        <i class="fas fa-shopping-cart label-icon"></i>
                        <span class="label-text">DI KERANJANG</span>
                    </div>
                    @elseif(in_array($game->id, $wishlistIds ?? []))
                    <div class="status-label-wishlist" style="top: 8px; left: 8px;">
                        <i class="fas fa-heart label-icon"></i>
                        <span class="label-text">WISHLIST</span>
                    </div>
                    @endif
                    @if($game->discount_percent > 0)
                    <div style="position:absolute;top:8px;right:8px;background:var(--c-primary);color:#000;font-size:12px;font-weight:800;padding:4px 8px;border-radius: 0;z-index:2;">-{{ $game->discount_percent }}%</div>
                    @elseif($game->price == 0)
                    <div style="position:absolute;top:8px;right:8px;background:var(--c-accent-green-pale);color:#000;font-size:11px;font-weight:800;padding:4px 10px;border-radius: 0;letter-spacing:0.5px;text-transform:uppercase;z-index:2;">GRATIS</div>
                    @endif
                </a>
                <div style="padding:24px;flex:1;display:flex;flex-direction:column;">
                    <div style="display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px;">
                        @foreach(array_slice($game->genre ?? [], 0, 2) as $genre)
                            <span class="badge" style="background:var(--c-surface-soft);color:var(--c-body);font-size:11px;padding:4px 10px;border-radius: 0;text-transform:uppercase;font-weight:700;">{{ $genre }}</span>
                        @endforeach
                    </div>
                    <a href="{{ route('game.show', $game->slug) }}" style="text-decoration:none;margin-bottom:8px;">
                        <h6 style="color:var(--c-ink);font-weight:700;font-size:17px;line-height:1.47;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-family:var(--font);">{{ $game->title }}</h6>
                    </a>
                    
                    @php
                        $avgRating = $game->reviews_count > 0 ? ($game->reviews_avg_rating ?? round($game->reviews->avg('rating'), 1)) : 0;
                    @endphp
                    <div style="display:flex;align-items:center;gap:4px;margin-top:auto;margin-bottom:12px;">
                        @if(request('sort', 'newest') == 'newest' && $game->release_date)
                            <i class="fas fa-calendar-alt" style="color:var(--c-mute);font-size:12px;"></i>
                            <span style="color:var(--c-body);font-size:12px;font-weight:700;">{{ $game->release_date->format('M Y') }}</span>
                        @else
                            <i class="fas fa-star" style="color:#fbbf24;font-size:12px;"></i>
                            <span style="color:var(--c-body);font-size:12px;font-weight:700;">{{ $avgRating ?: 'Baru' }}</span>
                        @endif
                    </div>

                    <div style="margin-bottom:16px;display:flex;align-items:center;flex-wrap:wrap;gap:8px;">
                        @if($game->price == 0)
                            <span style="color:var(--c-primary);font-size:16px;font-weight:800;white-space:nowrap;">GRATIS</span>
                        @else
                            @if($game->discount_percent > 0)
                                <span style="color:var(--c-mute);font-size:13px;text-decoration:line-through;opacity:0.8;white-space:nowrap;">Rp {{ number_format($game->original_price, 0, ',', '.') }}</span>
                            @endif
                            <span style="color:var(--c-ink);font-size:16px;font-weight:700;white-space:nowrap;">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                        @endif
                    </div>

                    <div style="display:flex;gap:8px;">
                        <button class="cart-btn btn {{ in_array($game->id, $cartIds) ? 'btn-primary' : 'btn-outline' }}" data-game-slug="{{ $game->slug }}"
                            style="flex:1;padding:8px;font-size:14px;height:40px;">
                            <i class="fas fa-shopping-cart me-1"></i>{{ in_array($game->id, $cartIds) ? 'Di Keranjang' : 'Keranjang' }}
                        </button>
                        <button class="wishlist-btn btn {{ in_array($game->id, $wishlistIds) ? 'btn-primary' : 'btn-outline' }}" data-game-slug="{{ $game->slug }}"
                            style="width:40px;height:40px;padding:0;">
                            <i class="{{ in_array($game->id, $wishlistIds) ? 'fas' : 'far' }} fa-heart" style="font-size:14px;"></i>
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($games->hasPages())
        <div id="pagination-container" style="margin-top:40px;display:flex;justify-content:center;opacity:1;transition:opacity 0.3s;">
            {{ $games->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
        @endif
        @endif
    </div>
</div>

<style>
    * {
        scroll-behavior: smooth !important;
    }
    
    html {
        scroll-behavior: smooth !important;
    }
    
    body {
        scroll-behavior: smooth !important;
    }
    
    @media (prefers-reduced-motion: no-preference) {
        :root {
            scroll-behavior: smooth !important;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Smooth Scroll Function (Manual Implementation for Better Control)
    function smoothScrollToTop(duration = 800) {
        const start = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
        const startTime = performance.now();

        function scroll() {
            const currentTime = performance.now();
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function (easeInOutCubic) untuk smooth animation
            const easeProgress = progress < 0.5
                ? 4 * progress * progress * progress
                : 1 - Math.pow(-2 * progress + 2, 3) / 2;
            
            const position = start * (1 - easeProgress);
            
            // Force scroll to both window and document
            window.scrollTo(0, position);
            document.documentElement.scrollTop = position;
            document.body.scrollTop = position;
            
            if (progress < 1) {
                requestAnimationFrame(scroll);
            } else {
                // Ensure we're at absolute top
                window.scrollTo(0, 0);
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
            }
        }
        
        requestAnimationFrame(scroll);
    }

    document.addEventListener('click', function(e) {
        // Pagination AJAX with Smooth Scroll
        const pageLink = e.target.closest('#pagination-container a');
        if (pageLink && pageLink.closest('#store-grid')) {
            e.preventDefault();
            const grid = document.getElementById('store-grid');
            const paginationContainer = document.getElementById('pagination-container');
            if (!grid) return;
            
            // Prevent re-clicking while loading
            if (grid.style.pointerEvents === 'none') return;
            
            // Disable pointer events immediately
            grid.style.pointerEvents = 'none';
            if (paginationContainer) {
                paginationContainer.style.pointerEvents = 'none';
            }
            
            // Log current scroll position for debugging
            console.log('Current scroll:', window.pageYOffset);
            
            // Start smooth scroll animation to top (800ms duration)
            smoothScrollToTop(800);
            
            // Wait for scroll animation to complete, then load content
            setTimeout(() => {
                console.log('After scroll:', window.pageYOffset);
                
                // Add fade effect
                if (paginationContainer) {
                    paginationContainer.style.opacity = '0.4';
                }
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
            }, 850); // Wait 850ms (scroll duration + 50ms buffer)
            
            return;
        }
    });
});
</script>
@endsection
