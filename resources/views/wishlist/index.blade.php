@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<div class="container section">
  <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;">
      <div style="width:64px;height:64px;background:var(--c-surface-soft);border-radius: 50%;overflow:hidden;">
          <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <h1 class="t-display-md" style="margin:0;letter-spacing:1px;text-transform:uppercase;">{{ auth()->user()->username ?? auth()->user()->name }}'S WISHLIST</h1>
  </div>

  <div style="display:flex; gap:8px; margin-bottom:24px; flex-wrap:wrap;">
      <input type="text" placeholder="Search by name or tag" style="flex:1; background:var(--c-surface-soft); border:1px solid rgba(255,255,255,0.1); color:var(--c-body); padding:8px 12px; border-radius: 0; font-size:0.85rem; outline:none;">
      <button style="background:var(--c-surface-soft); border:1px solid rgba(255,255,255,0.1); color:var(--c-body); padding:8px 16px; border-radius: 0; font-size:0.85rem; cursor:pointer; transition:background 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.1)';" onmouseleave="this.style.background='var(--c-surface-soft)';">Options <i class="fas fa-caret-down" style="margin-left:4px;font-size:0.75rem;"></i></button>
      <button style="background:var(--c-surface-soft); border:1px solid rgba(255,255,255,0.1); color:var(--c-body); padding:8px 16px; border-radius: 0; font-size:0.85rem; cursor:pointer; transition:background 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.1)';" onmouseleave="this.style.background='var(--c-surface-soft)';">Sort by: <strong style="color:var(--c-ink);">Price</strong> <i class="fas fa-caret-down" style="margin-left:4px;font-size:0.75rem;"></i></button>
  </div>

  <style>
      .wishlist-row {
          display: flex;
          background: var(--c-surface-soft);
          border-radius: 0;
          margin-bottom: 16px;
          position: relative;
          overflow: hidden;
      }
      .wishlist-row-cover {
          width: 292px;
          flex-shrink: 0;
          display: block;
          aspect-ratio: 16/9;
          align-self: flex-start;
      }
      .wishlist-row-body {
          flex: 1;
          padding: 12px 16px;
          display: flex;
          justify-content: space-between;
      }
      .wishlist-row-info {
          display: flex;
          flex-direction: column;
          flex: 1;
      }
      .wishlist-row-actions {
          display: flex;
          flex-direction: column;
          justify-content: space-between;
          align-items: flex-end;
          width: 260px;
          flex-shrink: 0;
          padding-left: 16px;
      }
      .wishlist-tag {
          background: rgba(255,255,255,0.05);
          border: 1px solid rgba(255,255,255,0.1);
          color: var(--c-mute);
          padding: 2px 8px;
          font-size: 0.8rem;
          border-radius: 0;
          margin-right: 4px;
          margin-bottom: 4px;
          display: inline-block;
          cursor: pointer;
          font-weight: 600;
          text-transform: uppercase;
      }
      .wishlist-tag:hover {
          background: rgba(255,255,255,0.1);
          color: var(--c-ink);
      }
      .wishlist-meta {
          font-size: 0.8rem;
          margin-top: auto;
          display: flex;
          flex-direction: column;
          gap: 6px;
      }
      .wishlist-meta-row {
          display: flex;
          align-items: center;
          gap: 16px;
      }
      .wishlist-meta-label {
          color: var(--c-mute);
          opacity: 0.8;
          text-transform: uppercase;
          font-weight: 700;
      }
      .wishlist-price-btn {
          display: flex;
          align-items: stretch;
          margin-top: auto;
          height: 36px;
      }
      .wishlist-price-block {
          display: flex;
          align-items: stretch;
          background: #000;
          border-radius: 0;
          overflow: hidden;
      }
      @media (max-width: 768px) {
          .wishlist-row {
              flex-direction: column;
          }
          .wishlist-row-cover {
              width: 100%;
          }
          .wishlist-row-body {
              flex-direction: column;
              gap: 16px;
          }
          .wishlist-row-actions {
              width: 100%;
              align-items: flex-start;
              padding-left: 0;
          }
          .wishlist-price-btn {
              width: 100%;
              justify-content: flex-end;
              margin-top: 12px;
          }
          .remove-wishlist-btn-wrapper {
              position: absolute;
              top: 6px;
              right: 6px;
              z-index: 10;
          }
          .remove-wishlist-btn {
              background: rgba(0,0,0,0.7) !important;
              backdrop-filter: blur(4px);
              -webkit-backdrop-filter: blur(4px);
              border: 1px solid var(--c-hairline) !important;
              border-radius: 50% !important;
              width: 36px;
              height: 36px;
              display: flex !important;
              align-items: center;
              justify-content: center;
              padding: 0 !important;
          }
          .remove-wishlist-btn .text-remove {
              display: none;
          }
      }
  </style>

  @if($wishlistItems->isEmpty())
    <div class="card text-center" style="padding: 64px 20px;background:var(--c-surface-soft);margin-bottom:64px;">
      <div style="font-size:48px;color:rgba(255,255,255,0.05);margin-bottom:16px"><i class="fa fa-heart-crack"></i></div>
      <h2 class="t-heading-md mb-sm" style="color:var(--c-ink);">Wishlist masih kosong</h2>
      <p class="text-mute mb-lg">Kamu belum menambahkan game apa pun ke wishlist.</p>
      <a href="{{ route('home') }}" style="background:var(--c-primary);color:#000;padding:10px 24px;border-radius: 0;text-decoration:none;font-weight:700;">Mulai Belanja</a>
    </div>
  @else
    <div class="wishlist-list">
      @foreach($wishlistItems as $game)
        <div class="wishlist-row">
            <a href="{{ route('game.show', $game->slug) }}" class="wishlist-row-cover">
                @if($game->cover_image)
                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" style="width:100%;height:100%;object-fit:cover;">
                @else
                    <div style="width:100%;height:100%;background:#111;display:flex;align-items:center;justify-content:center;">
                        <i class="fa fa-image" style="font-size:2rem;color:var(--c-mute);"></i>
                    </div>
                @endif
            </a>
            <div class="wishlist-row-body">
                <div class="wishlist-row-info">
                    <h3 style="font-size:1.4rem;font-weight:600;margin:0 0 4px 0;">
                        <a href="{{ route('game.show', $game->slug) }}" style="color:var(--c-ink);text-decoration:none;">{{ $game->title }}</a>
                    </h3>
                    
                    <div style="font-size:0.8rem;color:var(--c-mute);margin-bottom:12px;display:flex;align-items:center;gap:8px;font-weight:700;">
                        MY CATEGORIES: <div style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);padding:2px 8px;border-radius:0;cursor:pointer;">+</div>
                    </div>

                    <div class="wishlist-meta">
                        <div class="wishlist-meta-row">
                            <div>
                                <span class="wishlist-meta-label">RELEASE DATE:</span>
                                <span style="color:var(--c-mute);margin-left:4px;">{{ $game->release_date ? $game->release_date->format('d/m/Y') : '-' }}</span>
                            </div>
                            <div>
                                <span class="wishlist-meta-label">OVERALL REVIEWS:</span>
                                <span style="color:{{ $game->review_color ?? '#66C0F4' }};margin-left:4px;font-weight:600;">
                                    {{ $game->review_tier ?? 'NO REVIEWS' }}
                                </span>
                            </div>
                        </div>

                        <div class="wishlist-meta-row" style="align-items:flex-start;">
                            <span class="wishlist-meta-label" style="margin-top:4px;">USER TAGS:</span>
                            <div style="display:flex;flex-wrap:wrap;">
                                @foreach(array_slice($game->genre ?? [], 0, 5) as $g)
                                    <span class="wishlist-tag">{{ $g }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="wishlist-row-actions">
                    <div style="font-size:0.75rem;color:var(--c-mute);display:flex;align-items:center;gap:12px;">
                        Added on {{ \Carbon\Carbon::parse($game->pivot->created_at ?? now())->format('d/m/Y') }} 
                        <div class="remove-wishlist-btn-wrapper">
                            <button class="remove-wishlist-btn" data-game-slug="{{ $game->slug }}" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:var(--c-mute);padding:4px 8px;border-radius:0;cursor:pointer;font-size:0.75rem;display:flex;align-items:center;gap:4px;transition:background 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.1)';" onmouseleave="this.style.background='rgba(255,255,255,0.05)';">
                                <span style="color:var(--c-primary);">✖</span> <span class="text-remove">remove</span>
                            </button>
                        </div>
                    </div>

                    <div class="wishlist-price-btn">
                        @if($game->price == 0)
                            <div style="color:var(--c-primary);font-size:1.1rem;font-weight:700;background:#000;padding:0 16px;display:flex;align-items:center;justify-content:center;border-radius:0;">GRATIS</div>
                        @else
                            <div class="wishlist-price-block">
                                @if($game->discount_percent > 0)
                                    <div style="background:var(--c-accent-green);color:#000;font-size:1.1rem;font-weight:800;padding:0 8px;display:flex;align-items:center;justify-content:center;">
                                        -{{ $game->discount_percent }}%
                                    </div>
                                    <div style="text-align:right;padding:0 10px;display:flex;flex-direction:column;justify-content:center;white-space:nowrap;background:#000;">
                                        <div style="color:var(--c-mute);font-size:0.7rem;text-decoration:line-through;opacity:0.8;line-height:1;margin-bottom:2px;">Rp {{ number_format($game->original_price, 0, ',', '.') }}</div>
                                        <div style="color:var(--c-primary);font-size:0.95rem;font-weight:700;line-height:1;">Rp {{ number_format($game->price, 0, ',', '.') }}</div>
                                    </div>
                                @else
                                    <div style="color:var(--c-primary);font-size:1rem;font-weight:700;background:#000;padding:0 16px;display:flex;align-items:center;justify-content:center;white-space:nowrap;">
                                        Rp {{ number_format($game->price, 0, ',', '.') }}
                                    </div>
                                @endif
                            </div>
                        @endif

                        @if(in_array($game->id, $cartIds))
                            <a href="{{ route('cart.index') }}" style="background:rgba(255,255,255,0.05);color:var(--c-ink);border:1px solid rgba(255,255,255,0.1);padding:0 16px;border-radius: 0;font-weight:700;font-size:0.95rem;white-space:nowrap;cursor:pointer;text-decoration:none;display:flex;align-items:center;justify-content:center;transition:background 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.1)';" onmouseleave="this.style.background='rgba(255,255,255,0.05)';">
                                Di Keranjang
                            </a>
                        @else
                            <button class="cart-btn" data-prevent-global="true" data-game-slug="{{ $game->slug }}" style="background:var(--c-primary);color:#000;border:none;padding:0 16px;border-radius: 0;font-weight:700;font-size:0.95rem;cursor:pointer;white-space:nowrap;transition:transform 0.1s;" onmousedown="this.style.transform='scale(0.97)';" onmouseup="this.style.transform='scale(1)';">
                                Add to Cart
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ── Hapus dari Wishlist ──────────────────────────────────────────
    document.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const slug = this.dataset.gameSlug;
            const card = this.closest('.wishlist-row');

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/wishlist/${slug}`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN':csrfToken,'Accept':'application/json','Content-Type':'application/json'},
                body: JSON.stringify({_token: csrfToken})
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && !data.in_wishlist) {
                    removeCard(card, data.count, 'wishlist-count');
                    if (typeof showCenterPopup==='function') showCenterPopup('Dihapus dari wishlist', true);
                    else if (typeof showToast==='function') showToast('Dihapus dari wishlist', 'success');
                } else if (data.in_wishlist) {
                    // It was re-added (toggle), just re-add to wishlist is unexpected here; restore button
                    this.disabled = false;
                    this.innerHTML = 'remove';
                } else {
                    this.disabled = false;
                    this.innerHTML = 'remove';
                    if (typeof showCenterPopup==='function') showCenterPopup(data.error || 'Gagal menghapus', false);
                }
            })
            .catch(() => {
                this.disabled = false;
                this.innerHTML = 'remove';
                if (typeof showCenterPopup==='function') showCenterPopup('Terjadi kesalahan', false);
            });
        });
    });

    // ── Pindah ke Keranjang (cart-btn) ──────────────────────────────
    document.querySelectorAll('.cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const slug = this.dataset.gameSlug;
            const card = this.closest('.wishlist-row');
            const originalHTML = this.innerHTML;

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/cart/${slug}`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN':csrfToken,'Accept':'application/json','Content-Type':'application/json'}
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                    if (typeof showCenterPopup==='function') showCenterPopup(data.error, false);
                    return;
                }

                if (typeof updateBadge === 'function') updateBadge('cart-count', data.count);

                if (data.in_cart) {
                    if (typeof showCenterPopup==='function') showCenterPopup(data.message, true);
                    // Ubah tombol jadi Di Keranjang
                    const actionDiv = this.parentElement;
                    const url = "{{ route('cart.index') }}";
                    actionDiv.innerHTML = `
                        <a href="${url}" style="background:rgba(255,255,255,0.05);color:var(--c-mute);border:1px solid rgba(255,255,255,0.1);padding:0 16px;border-radius: 0;font-weight:700;font-size:0.95rem;white-space:nowrap;cursor:pointer;text-decoration:none;display:flex;align-items:center;justify-content:center;transition:background 0.2s, color 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.1)';this.style.color='#fff';" onmouseleave="this.style.background='rgba(255,255,255,0.05)';this.style.color='var(--c-mute)';">
                            Di Keranjang
                        </a>
                    `;
                } else {
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                }
            })
            .catch((error) => {
                console.error(error);
                this.disabled = false;
                this.innerHTML = originalHTML;
                if (typeof showCenterPopup==='function') showCenterPopup('Terjadi kesalahan', false);
            });
        });
    });

    // ── Helper: animasikan penghapusan kartu ────────────────────────
    function removeCard(card, count, badgeId) {
        card.style.transition = 'all 0.35s';
        card.style.opacity = '0';
        card.style.transform = 'scale(0.9)';

        setTimeout(() => {
            card.remove();
            if (count !== null && badgeId && typeof updateBadge === 'function') {
                updateBadge(badgeId, count);
            }

            const remaining = document.querySelectorAll('.wishlist-row');
            if (!remaining.length) {
                const grid = document.querySelector('.wishlist-list');
                if (grid) grid.remove();
                const container = document.querySelector('.container.section');
                container.insertAdjacentHTML('beforeend', `
                    <div class="card text-center" style="padding: 64px 20px;margin-bottom:64px;background:var(--c-surface-soft);">
                      <div style="font-size:48px;color:rgba(255,255,255,0.05);margin-bottom:16px"><i class="fa fa-heart-crack"></i></div>
                      <h2 class="t-heading-md mb-sm">Wishlist masih kosong</h2>
                      <p class="text-mute mb-lg">Kamu belum menambahkan game apa pun ke wishlist.</p>
                      <a href="/" class="btn btn-primary">Mulai Belanja</a>
                    </div>
                `);
            }
        }, 350);
    }
});
</script>
@endsection
