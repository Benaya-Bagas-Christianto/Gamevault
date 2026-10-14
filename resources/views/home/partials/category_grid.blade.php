<style>
    .cat-games-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
        gap: 20px;
    }
    .cat-game-card {
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
    .cat-game-card:hover {
        border-color: var(--c-primary);
        transform: translateY(-2px);
    }
    .cat-card-thumb-wrap {
        display: block;
        position: relative;
        overflow: hidden;
        aspect-ratio: 460/215;
        background: #000;
    }
    .cat-card-thumb-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.4s ease;
    }
    .cat-card-thumb-wrap:hover img {
        transform: scale(1.04);
    }
    .cat-card-body {
        padding: 16px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .cat-card-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        margin-bottom: 8px;
    }
    .cat-card-tag {
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
    .cat-card-title {
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
    .cat-card-meta {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: auto;
        margin-bottom: 10px;
        font-size: 11.5px;
        font-weight: 700;
        color: var(--c-mute);
    }
    .cat-card-pricing {
        margin-bottom: 14px;
        display: flex;
        align-items: baseline;
        flex-wrap: wrap;
        gap: 6px;
    }
    .cat-price-original {
        color: var(--c-mute);
        font-size: 11.5px;
        text-decoration: line-through;
        opacity: 0.8;
        white-space: nowrap;
    }
    .cat-price-current {
        color: var(--c-ink);
        font-size: 15px;
        font-weight: 800;
        white-space: nowrap;
    }
    .cat-price-free {
        color: var(--c-primary);
        font-size: 14px;
        font-weight: 800;
        white-space: nowrap;
        text-transform: uppercase;
    }
    .cat-card-actions {
        display: flex;
        gap: 6px;
    }
    .cat-cart-btn {
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
    .cat-wishlist-btn {
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

    @media (max-width: 768px) {
        .cat-games-grid {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 10px !important;
        }
        .cat-card-body {
            padding: 10px 10px 12px !important;
        }
        .cat-card-title {
            font-size: 13px !important;
            line-height: 1.3 !important;
            margin-bottom: 6px !important;
        }
        .cat-card-tag {
            font-size: 9px !important;
            padding: 1px 5px !important;
        }
        .cat-card-meta {
            font-size: 10.5px !important;
            margin-bottom: 8px !important;
        }
        .cat-price-original {
            font-size: 10px !important;
        }
        .cat-price-current,
        .cat-price-free {
            font-size: 13px !important;
        }
        .cat-cart-btn {
            height: 34px !important;
            font-size: 11.5px !important;
            padding: 0 6px !important;
        }
        .cat-wishlist-btn {
            width: 34px !important;
            height: 34px !important;
        }
        .cat-wishlist-btn i {
            font-size: 12px !important;
        }
    }
</style>

@if($games->isEmpty())
<div style="text-align:center;padding:70px 20px;background:var(--c-surface-soft);backdrop-filter:blur(12px);border-radius: 0;border:1px dashed var(--c-hairline-strong);">
    <i class="fas fa-search" style="font-size:3rem;color:var(--c-hairline-strong);display:block;margin-bottom:14px;"></i>
    <h4 style="color:var(--c-body);margin-bottom:8px;font-size:1.1rem;">Tidak ada game yang cocok</h4>
    <p style="color:var(--c-mute);font-size:0.88rem;max-width:400px;margin:0 auto 16px;">Coba ubah kombinasi filter atau hapus pilihan genre untuk melihat katalog lainnya.</p>
    <button onclick="resetFilters()" class="btn-primary" style="display:inline-block;padding:9px 22px;border:none;border-radius:0;font-weight:700;font-size:0.88rem;cursor:pointer;">
        <i class="fas fa-redo me-2"></i>Reset Filter
    </button>
</div>
@else
<div class="cat-games-grid">
    @foreach($games as $game)
    <div class="cat-game-card game-card" data-game-id="{{ $game->id }}">
        <a href="{{ route('game.show', $game->slug) }}" class="cat-card-thumb-wrap">
            @if($game->cover_image)
                <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" loading="lazy" style="{{ in_array($game->id, $ownedIds) ? 'filter: grayscale(1) opacity(0.7);' : '' }}" onerror="this.outerHTML='<div style=\'width:100%;height:100%;background:var(--c-surface-soft);display:flex;align-items:center;justify-content:center;\'><i class=\'fas fa-gamepad\' style=\'font-size:2rem;color:var(--c-mute);\'></i></div>'">
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

        <div class="cat-card-body">
            <div class="cat-card-tags">
                @foreach(array_slice($game->genre ?? [], 0, 1) as $genre)
                    <span class="cat-card-tag">{{ $genre }}</span>
                @endforeach
            </div>

            <a href="{{ route('game.show', $game->slug) }}" style="text-decoration:none;">
                <h3 class="cat-card-title">{{ $game->title }}</h3>
            </a>
            
            @php
                $avgRating = $game->reviews_count > 0 ? ($game->reviews_avg_rating ?? round($game->reviews->avg('rating'), 1)) : 0;
            @endphp
            <div class="cat-card-meta">
                <i class="fas fa-star" style="color:#fbbf24;"></i>
                <span>{{ $avgRating ?: 'Baru' }}</span>
            </div>

            <div class="cat-card-pricing">
                @if($game->price == 0)
                    <span class="cat-price-free">GRATIS</span>
                @else
                    @if($game->discount_percent > 0)
                        <span class="cat-price-original">Rp {{ number_format($game->original_price, 0, ',', '.') }}</span>
                    @endif
                    <span class="cat-price-current">Rp {{ number_format($game->price, 0, ',', '.') }}</span>
                @endif
            </div>

            <div class="cat-card-actions">
                <button class="cart-btn btn {{ in_array($game->id, $cartIds) ? 'btn-primary' : 'btn-outline' }} cat-cart-btn" data-game-slug="{{ $game->slug }}">
                    <i class="fas fa-shopping-cart"></i>
                    <span>{{ in_array($game->id, $cartIds) ? 'Di Keranjang' : 'Beli' }}</span>
                </button>
                <button class="wishlist-btn btn {{ in_array($game->id, $wishlistIds) ? 'btn-primary' : 'btn-outline' }} cat-wishlist-btn" data-game-slug="{{ $game->slug }}" title="Wishlist">
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

<script>
    const gameCountEl = document.getElementById('gameCountText');
    if (gameCountEl) {
        gameCountEl.textContent = '{{ $games->total() }}';
    }
</script>
@endif
