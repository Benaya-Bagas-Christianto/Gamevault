@if($games->isEmpty())
<div style="text-align:center;padding:80px 20px;background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius: 0;border:1px dashed var(--c-hairline-strong);">
    <i class="fas fa-search" style="font-size:3.5rem;color:var(--c-hairline-strong);display:block;margin-bottom:16px;"></i>
    <h4 style="color:var(--c-body);margin-bottom:8px;">Tidak ada game ditemukan</h4>
    <a href="{{ route('categories') }}" class="btn-primary" style="display:inline-block;padding:10px 24px;text-decoration:none;margin-top:16px;">
        <i class="fas fa-redo me-2"></i>Reset Filter
    </a>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:24px;">
    @foreach($games as $game)
    <div class="game-card" data-game-id="{{ $game->id }}" style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius: 0;overflow:hidden;border:1px solid var(--c-hairline);display:flex;flex-direction:column;position:relative;transition:border-color 0.2s;" onmouseenter="this.style.borderColor='var(--c-primary)';" onmouseleave="this.style.borderColor='var(--c-hairline)';">
        <a href="{{ route('game.show', $game->slug) }}" style="display:block;position:relative;overflow:hidden;aspect-ratio:16/9;">
            @if($game->cover_image)
                <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s; {{ in_array($game->id, $ownedIds) ? 'filter: grayscale(1) opacity(0.7);' : '' }}" onmouseenter="this.style.transform='scale(1.04)';" onmouseleave="this.style.transform='scale(1)';" onerror="this.outerHTML='<div style=\'width:100%;height:100%;background:var(--c-surface-soft);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:8px;\'><i class=\'fas fa-gamepad\' style=\'font-size:2.5rem;color:var(--c-mute);\'></i></div>'">
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
                <i class="fas fa-star" style="color:#fbbf24;font-size:12px;"></i>
                <span style="color:var(--c-body);font-size:12px;font-weight:700;">{{ $avgRating ?: 'Baru' }}</span>
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

<script>
    // Update game count
    const gameCountEl = document.getElementById('gameCountText');
    if (gameCountEl) {
        gameCountEl.textContent = '{{ $games->total() }}';
    }
</script>
@endif
