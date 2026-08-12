@extends('layouts.app')

@section('title', 'Keranjang Belanja — GameVault')

@section('content')

<div style="background:var(--c-canvas);border-bottom:1px solid var(--c-hairline);padding:12px 0;">
    <div class="container">
        <nav style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--c-mute);">
            <a href="{{ url('/') }}" style="color:var(--c-primary);text-decoration:none;">Home</a>
            <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
            <span style="color:var(--c-body);">Keranjang Belanja</span>
        </nav>
    </div>
</div>

<div class="container" style="padding-top:40px;padding-bottom:64px;">

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:32px;">
        <h1 style="font-size:1.8rem;font-weight:800;color:var(--c-ink);margin:0;">Keranjang Belanja</h1>
        @if(count($cartGroups))
        <span style="background:var(--c-primary);color:#000;font-size:0.8rem;font-weight:700;padding:4px 12px;border-radius: 0;">{{ count($cartGroups) }} item</span>
        @endif
    </div>

    @if(empty($cartGroups))
    {{-- Empty State --}}
    <div style="text-align:center;padding:100px 20px;background:var(--c-canvas);border:1px solid var(--c-hairline);border-radius: 0;border:1px dashed var(--c-hairline-strong);">
        <div style="width:96px;height:96px;background:linear-gradient(135deg,var(--c-surface-soft),var(--c-canvas));border-radius: 0;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
            <i class="fas fa-shopping-cart" style="font-size:2.5rem;color:var(--c-hairline-strong);"></i>
        </div>
        <h3 style="color:var(--c-ink);margin-bottom:12px;font-weight:700;">Keranjangmu masih kosong</h3>
        <p style="color:var(--c-body);margin-bottom:32px;font-size:0.95rem;">Jelajahi ribuan game dan temukan yang kamu suka!</p>
        <a href="{{ url('/') }}" class="btn-primary" style="display:inline-block;padding:12px 32px;text-decoration:none;font-size:1rem;font-weight:700;border-radius: 0;">
            <i class="fas fa-store me-2"></i>Mulai Belanja
        </a>
    </div>
    @else

    <style>
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 24px;
        }
        @media (max-width: 992px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 768px) {
            .cart-item-row {
                flex-direction: column !important;
            }
            .cart-item-cover {
                width: 100% !important;
                padding: 0 !important;
            }
            .cart-item-cover img {
                width: 100% !important;
                height: 100% !important;
                object-fit: cover !important;
                aspect-ratio: 16/9;
            }
            .cart-item-body {
                flex-direction: column !important;
                padding: 16px !important;
                gap: 16px;
            }
            .cart-item-info {
                padding-right: 0 !important;
            }
            .cart-item-price {
                align-items: flex-start !important;
                text-align: left !important;
                width: 100%;
            }
            .cart-price-block {
                width: 100%;
                justify-content: space-between;
            }
            .cart-checkout-actions {
                flex-direction: column !important;
                align-items: stretch !important;
            }
            .cart-checkout-actions button, .cart-checkout-actions a {
                width: 100% !important;
                text-align: center;
                order: 2;
            }
            .cart-checkout-actions button#checkout-btn-left {
                order: 1;
            }
            .mobile-total-box {
                display: flex !important;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 8px;
                padding-bottom: 12px;
                border-bottom: 1px solid var(--c-hairline);
                width: 100%;
                order: 0;
            }
        }
        @media (min-width: 769px) {
            .mobile-total-box {
                display: none !important;
            }
        }
    </style>

    <div class="cart-layout">
        {{-- LEFT COLUMN --}}
        <div style="display:flex;flex-direction:column;height:100%;">
            <div id="cart-items-container" style="display:flex;flex-direction:column;gap:16px;">
                @foreach($cartGroups as $group)
                @php 
                    $isBundle = $group['is_bundle'];
                    $game = $isBundle ? $group['parent'] : $group['item'];
                    $title = $isBundle ? $group['title'] : $game->title;
                    $price = $isBundle ? $group['price'] : $game->price;
                    $originalPrice = $isBundle ? $group['original_price'] : $game->original_price;
                    $discountPercent = 0;
                    if ($originalPrice > 0) {
                        $discountPercent = round((($originalPrice - $price) / $originalPrice) * 100);
                    }
                @endphp
                <div class="cart-item-row" data-game-slug="{{ $game->slug }}" style="display:flex;align-items:stretch;background:var(--c-surface-soft);border-radius: 0;overflow:hidden;position:relative;">
                    {{-- Cover --}}
                    <a href="{{ route('game.show', $game->slug) }}" class="cart-item-cover" style="flex-shrink:0;width:280px;display:flex;align-items:flex-start;background:transparent;padding:16px 16px 16px 16px;">
                        @if($game->cover_image)
                            <img src="{{ $game->cover_image_url }}" alt="{{ $title }}" style="width:100%;height:auto;object-fit:contain;display:block;">
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a1a,#0d2200);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-gamepad" style="color:rgba(118,185,0,0.5);font-size:2rem;"></i>
                            </div>
                        @endif
                    </a>

                    {{-- Info & Price --}}
                    <div class="cart-item-body" style="flex:1;display:flex;align-items:flex-start;justify-content:space-between;padding:16px 24px;">
                        {{-- Info --}}
                        <div class="cart-item-info" style="min-width:0;padding-right:16px;display:flex;flex-direction:column;height:100%;justify-content:space-between;">
                            <div>
                                <a href="{{ route('game.show', $game->slug) }}" style="text-decoration:none;">
                                    <h5 style="color:var(--c-ink);font-weight:700;font-size:1.2rem;margin-bottom:8px;line-height:1.2;">{{ $title }}</h5>
                                </a>
                                @if($isBundle)
                                    <div style="color:var(--c-mute);font-size:0.85rem;margin-bottom:8px;">
                                        Includes {{ $group['items']->count() }} items: 
                                        {{ $group['items']->pluck('title')->implode(', ') }}
                                    </div>
                                @endif
                                <i class="fab fa-windows" style="color:var(--c-mute);font-size:1rem;margin-bottom:8px;display:block;"></i>
                            </div>
                        </div>

                        {{-- Price Block --}}
                        <div class="cart-item-price" style="text-align:right;flex-shrink:0;display:flex;flex-direction:column;align-items:flex-end;justify-content:space-between;height:100%;">
                            @if($price == 0)
                                <div style="color:var(--c-primary);font-size:1.25rem;font-weight:800;margin-top:4px;">GRATIS</div>
                            @else
                                <div class="cart-price-block" style="display:flex;align-items:center;gap:4px;background:#000;padding:2px;border-radius: 0;margin-top:4px;">
                                    @if($discountPercent > 0)
                                        <div style="background:var(--c-accent-green);color:var(--c-black);font-size:1.2rem;font-weight:800;padding:6px 8px;height:100%;display:flex;align-items:center;">
                                            -{{ $discountPercent }}%
                                        </div>
                                        <div style="text-align:right;padding:0 12px;display:flex;flex-direction:column;justify-content:center;">
                                            <div style="color:var(--c-mute);font-size:0.75rem;text-decoration:line-through;opacity:0.8;line-height:1;">Rp {{ number_format($originalPrice, 0, ',', '.') }}</div>
                                            <div style="color:var(--c-primary);font-size:1.05rem;font-weight:700;line-height:1;margin-top:4px;">Rp {{ number_format($price, 0, ',', '.') }}</div>
                                        </div>
                                    @else
                                        <div style="color:var(--c-primary);font-size:1.05rem;font-weight:700;padding:0 12px;line-height:1;display:flex;align-items:center;height:100%;">Rp {{ number_format($price, 0, ',', '.') }}</div>
                                    @endif
                                </div>
                            @endif

                            {{-- Remove Link --}}
                            <div style="display:flex;gap:12px;font-size:0.75rem;color:var(--c-mute);margin-top:16px;">
                                <span style="cursor:pointer;transition:color 0.2s;" onmouseenter="this.style.color='var(--c-body)';" onmouseleave="this.style.color='var(--c-mute)';">Tambah</span>
                                <span>|</span>
                                @if($isBundle)
                                <button class="remove-bundle-btn" data-game-slug="{{ $game->slug }}" style="background:none;border:none;color:var(--c-mute);text-decoration:none;cursor:pointer;padding:0;transition:color 0.2s;" onmouseenter="this.style.color='var(--c-body)';" onmouseleave="this.style.color='var(--c-mute)';">Hapus</button>
                                @else
                                <button class="remove-cart-btn" data-game-slug="{{ $game->slug }}" style="background:none;border:none;color:var(--c-mute);text-decoration:none;cursor:pointer;padding:0;transition:color 0.2s;" onmouseenter="this.style.color='var(--c-body)';" onmouseleave="this.style.color='var(--c-mute)';">Hapus</button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Purchase Progress Bar --}}
            <div style="margin-top:16px;background:var(--c-surface-soft);padding:16px 20px;border-radius: 0;border:1px solid rgba(255,255,255,0.05);">
                <div style="font-size:0.95rem;font-weight:700;color:var(--c-primary);margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-rocket"></i> Ayo, selangkah lagi game ini jadi milikmu!
                </div>
                <div style="font-size:0.85rem;color:var(--c-body);margin-bottom:16px;">
                    Selesaikan pembayaran sekarang untuk segera mengunduh dan mulai petualangan barumu di game impian!
                </div>
                <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:var(--c-mute);margin-bottom:8px;">
                    <span>Progres Checkout</span>
                    <span>Langkah 1 dari 2 (Keranjang)</span>
                </div>
                <div style="width:100%;height:8px;background:rgba(0,0,0,0.4);border-radius: 0;overflow:hidden;position:relative;box-shadow:inset 0 1px 3px rgba(0,0,0,0.5);">
                    <div style="width:50%;height:100%;background:linear-gradient(90deg, var(--c-primary), #a8ff33);border-radius: 0;box-shadow:0 0 10px rgba(118,185,0,0.5);"></div>
                </div>
            </div>

            <div style="margin-top:16px;display:flex;align-items:center;justify-content:space-between;background:var(--c-surface-soft);padding:16px 20px;border-radius: 0;flex-wrap:wrap;gap:16px;">
                <div class="cart-checkout-actions" style="display:flex;gap:12px;align-items:center;width:100%;flex-wrap:wrap;">
                    <div class="mobile-total-box">
                        <span style="font-size:0.9rem;color:var(--c-body);">Total Perkiraan</span>
                        <span style="font-size:1.2rem;color:var(--c-ink);font-weight:700;">Rp {{ number_format($total,0,',','.') }}</span>
                    </div>
                    <a href="{{ url('/') }}" style="padding:10px 20px;text-decoration:none;font-size:0.9rem;font-weight:600;color:var(--c-body);background:rgba(255,255,255,0.1);border-radius: 0;transition:background 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.15)';" onmouseleave="this.style.background='rgba(255,255,255,0.1)';">Lanjut Belanja</a>
                    <button type="button" id="checkout-btn-left" onclick="startCheckout(this)" style="padding:10px 20px;text-decoration:none;font-size:0.9rem;font-weight:700;background:var(--c-primary);color:#000;border:none;border-radius: 0;cursor:pointer;">Lanjut ke Pembayaran</button>
                </div>
                <button type="button" onclick="showSweetAlert('Info', 'Fitur hapus semua segera hadir!', 'info')" style="background:none;border:none;color:var(--c-mute);font-size:0.75rem;cursor:pointer;padding:0;" onmouseenter="this.style.color='var(--c-body)';" onmouseleave="this.style.color='var(--c-mute)';">Hapus Semua Item</button>
            </div>

            @if(isset($recommendations) && $recommendations->count() > 0)
            <div style="margin-top:auto;">
                <h6 style="color:var(--c-mute);font-size:0.85rem;font-weight:700;margin-bottom:16px;text-transform:uppercase;letter-spacing:1px;margin-top:0;">RECOMMENDATIONS FOR YOU</h6>
                <div style="display:grid;grid-template-columns:repeat(3, 1fr);gap:16px;">
                    @foreach($recommendations as $game)
                    <a href="{{ route('game.show', $game->slug) }}" style="display:block;text-decoration:none;background:var(--c-surface-soft);border-radius: 0;overflow:hidden;position:relative;transition:transform 0.2s;" onmouseenter="this.style.transform='translateY(-2px)';" onmouseleave="this.style.transform='translateY(0)';">
                        <div style="width:100%;aspect-ratio:16/9;position:relative;">
                            @if($game->cover_image)
                                <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" style="width:100%;height:100%;object-fit:cover;">
                            @else
                                <div style="width:100%;height:100%;background:#111;display:flex;align-items:center;justify-content:center;">
                                    <i class="fas fa-gamepad" style="color:rgba(118,185,0,0.3);font-size:2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div style="padding:12px;background:var(--c-surface-soft);">
                            <div style="display:flex;align-items:center;justify-content:space-between;">
                                @if($game->price == 0)
                                    <div style="color:var(--c-primary);font-weight:700;font-size:0.9rem;">GRATIS</div>
                                @else
                                    <div style="display:flex;align-items:center;gap:4px;background:#000;padding:2px;border-radius: 0;width:100%;">
                                        @if($game->discount_percent > 0)
                                            <div style="background:var(--c-accent-green);color:var(--c-black);font-size:0.9rem;font-weight:800;padding:4px 6px;display:flex;align-items:center;">
                                                -{{ $game->discount_percent }}%
                                            </div>
                                            <div style="text-align:right;padding:0 8px;flex:1;">
                                                <div style="color:var(--c-mute);font-size:0.65rem;text-decoration:line-through;opacity:0.8;line-height:1;">Rp {{ number_format($game->original_price, 0, ',', '.') }}</div>
                                                <div style="color:var(--c-primary);font-size:0.85rem;font-weight:700;line-height:1;margin-top:2px;">Rp {{ number_format($game->price, 0, ',', '.') }}</div>
                                            </div>
                                        @else
                                            <div style="color:var(--c-primary);font-size:0.85rem;font-weight:700;padding:4px 8px;text-align:right;flex:1;">Rp {{ number_format($game->price, 0, ',', '.') }}</div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- RIGHT COLUMN --}}
        <div style="display:flex;flex-direction:column;height:100%;">
            {{-- Summary Box --}}
            <div class="desktop-only" style="background:var(--c-surface-soft);padding:16px;border-radius: 0;margin-bottom:16px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:8px;">
                    <span style="font-size:0.9rem;color:var(--c-body);">Total Perkiraan</span>
                    <span style="font-size:1.05rem;color:var(--c-ink);font-weight:700;" id="total-display">Rp {{ number_format($total,0,',','.') }}</span>
                </div>
                <div style="font-size:0.7rem;color:var(--c-mute);margin-bottom:16px;line-height:1.4;">
                    Pajak penjualan akan dihitung saat checkout bila berlaku.
                </div>
                <button type="button" id="checkout-btn-right" onclick="startCheckout(this)" style="display:block;width:100%;text-align:center;padding:10px;font-size:0.9rem;font-weight:700;text-decoration:none;border-radius: 0;border:none;cursor:pointer;background:var(--c-primary);color:#000;">
                    Lanjut ke Pembayaran
                </button>
            </div>

            {{-- Digital License Box (Steam Style) --}}
            <div class="desktop-only" style="background:var(--c-surface-soft);padding:24px;border-radius: 0;display:flex;flex-direction:column;align-items:center;text-align:center;margin-bottom:16px;">
                <img src="{{ asset('images/digital_license.png') }}" alt="Digital License" style="width:100%;max-width:160px;height:auto;margin-bottom:16px;border-radius: 0;box-shadow:0 8px 24px rgba(0,0,0,0.5);">
                <div style="font-size:0.75rem;color:var(--c-mute);line-height:1.5;">
                    Pembelian produk digital memberikan lisensi untuk produk tersebut di platform kami.<br><br>
                    Untuk syarat & ketentuan lengkap, silakan lihat <a href="#" style="color:var(--c-body);text-decoration:underline;">Perjanjian Pelanggan</a>.
                </div>
            </div>

            {{-- Payment Methods / Trust Box --}}
            <div style="background:var(--c-surface-soft);padding:16px;border-radius: 0;">
                <div style="font-size:0.85rem;color:var(--c-body);margin-bottom:12px;display:flex;align-items:center;gap:6px;">
                    <i class="fas fa-shield-alt" style="color:var(--c-primary);"></i> Transaksi Aman
                </div>
                <div style="font-size:0.7rem;color:var(--c-mute);line-height:1.4;margin-bottom:12px;">
                    Platform kami mendukung berbagai metode pembayaran terpercaya untuk kemudahan Anda.
                </div>
                <div style="display:flex;gap:6px;flex-wrap:wrap;align-items:center;background:rgba(0,0,0,0.2);padding:12px;border-radius: 0;">
                    <img src="{{ asset('images/mastercard.png') }}" height="20" alt="Mastercard" style="object-fit:contain;background:#fff;border-radius: 0;padding:2px;">
                    <img src="{{ asset('images/visa.png') }}" height="16" alt="Visa" style="object-fit:contain;background:#fff;border-radius: 0;padding:2px;">
                    <img src="{{ asset('images/dana.png') }}" height="20" alt="Dana" style="object-fit:contain;background:#fff;border-radius: 0;padding:2px;">
                    <img src="{{ asset('images/gopay.png') }}" height="16" alt="GoPay" style="object-fit:contain;background:#fff;border-radius: 0;padding:2px;">
                    <img src="{{ asset('images/qris.png') }}" height="16" alt="QRIS" style="object-fit:contain;background:#fff;border-radius: 0;padding:2px;">
                    <img src="{{ asset('images/alfamart.png') }}" height="16" alt="Alfamart" style="object-fit:contain;background:#fff;border-radius: 0;padding:2px;">
                    <img src="{{ asset('images/indomaret.png') }}" height="16" alt="Indomaret" style="object-fit:contain;background:#fff;border-radius: 0;padding:2px;">
                </div>
            </div>
        </div>
    </div>

    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    document.querySelectorAll('.remove-cart-btn, .remove-bundle-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const slug = this.dataset.gameSlug;
            const isBundle = this.classList.contains('remove-bundle-btn');
            const row = document.querySelector(`.cart-item-row[data-game-slug="${slug}"]`);
            const summaryItem = document.querySelector(`.summary-item[data-game-slug="${slug}"]`);

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin" style="font-size:0.85rem;"></i>';

            const url = isBundle ? `/cart/${slug}/bundle` : `/cart/${slug}`;
            fetch(url, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN':csrfToken,'Accept':'application/json','Content-Type':'application/json'}
            })
            .then(r => r.json())
            .then(data => {
                if (data.success || data.status) {
                    // Animate row out
                    row.style.transition = 'all 0.35s';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(20px)';
                    if (summaryItem) { summaryItem.style.display = 'none'; }

                    setTimeout(() => {
                        row.remove();
                        // Update totals
                        const newTotal = data.total ?? 0;
                        const formatted = newTotal === 0 ? 'Rp 0' : 'Rp ' + parseInt(newTotal).toLocaleString('id-ID');
                        const subEl = document.getElementById('subtotal-display');
                        const totEl = document.getElementById('total-display');
                        if (subEl) subEl.textContent = formatted;
                        if (totEl) totEl.textContent = formatted;

                        // Update badge
                        if (typeof updateBadge === 'function') {
                            updateBadge('cart-count', data.count);
                        }

                        // Check if empty
                        const remaining = document.querySelectorAll('.cart-item-row');
                        if (!remaining.length) {
                            // Hide controls & reward bar
                            document.querySelectorAll('.cart-layout').forEach(el => el.remove());
                            const container = document.querySelector('[style*="padding-top:40px"]');
                            if (container) {
                                container.insertAdjacentHTML('beforeend', `
                                    <div style="text-align:center;padding:100px 20px;background:var(--c-canvas);border:1px dashed var(--c-hairline-strong);border-radius: 0;">
                                        <div style="width:96px;height:96px;background:linear-gradient(135deg,var(--c-surface-soft),var(--c-canvas));border-radius: 0;display:flex;align-items:center;justify-content:center;margin:0 auto 24px;">
                                            <i class="fas fa-shopping-cart" style="font-size:2.5rem;color:var(--c-hairline-strong);"></i>
                                        </div>
                                        <h3 style="color:var(--c-ink);margin-bottom:12px;font-weight:700;">Keranjangmu sudah kosong</h3>
                                        <p style="color:var(--c-body);margin-bottom:32px;font-size:0.95rem;">Jelajahi ribuan game dan temukan yang kamu suka!</p>
                                        <a href="/" class="btn-primary" style="display:inline-block;padding:12px 32px;text-decoration:none;font-size:1rem;font-weight:700;border-radius: 0;">
                                            <i class="fas fa-store me-2"></i>Mulai Belanja
                                        </a>
                                    </div>`);
                            }
                        }
                    }, 350);

                    if (typeof showCenterPopup==='function') showCenterPopup(data.message||'Item dihapus dari keranjang', true);
                } else {
                    this.disabled = false;
                    this.innerHTML = '<i class="fas fa-trash-alt" style="font-size:0.85rem;"></i>';
                    if (typeof showCenterPopup==='function') showCenterPopup(data.message||'Gagal menghapus item', false);
                }
            })
            .catch(() => {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-trash-alt" style="font-size:0.85rem;"></i>';
                if (typeof showCenterPopup==='function') showCenterPopup('Terjadi kesalahan', false);
            });
        });
    });
});
</script>

@push('scripts')
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
function startCheckout(btnElement) {
    const btn = btnElement || document.getElementById('checkout-btn-right');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    const oldText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

    fetch('{{ route("checkout.token") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.error) {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-lock me-2"></i>Lanjut ke Pembayaran';
            if (typeof showCenterPopup === 'function') showCenterPopup(data.error, false);
            return;
        }

        if (data.is_free) {
            btn.innerHTML = '<i class="fas fa-check me-2"></i>Berhasil';
            if (typeof showCenterPopup === 'function') showCenterPopup(data.message || 'Game gratis berhasil diklaim!', true);
            setTimeout(() => {
                window.location.href = data.redirect || '{{ route("library.index") }}';
            }, 1000);
            return;
        }

        btn.innerHTML = '<i class="fas fa-lock me-2"></i>Lanjut ke Pembayaran';
        btn.disabled = false;

        window.snap.pay(data.snap_token, {
            onSuccess: function(result) {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Menyimpan...';
                // Finalize: simpan ke library, hapus dari cart
                fetch('{{ route("checkout.finalize") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
                    body: JSON.stringify({ order_id: data.order_id, payment_type: result.payment_type || 'unknown' })
                })
                .then(r => r.json())
                .then(() => {
                    window.location.href = '{{ route("library.index") }}';
                })
                .catch(() => {
                    window.location.href = '{{ route("library.index") }}';
                });
            },
            onPending: function(result) {
                if (typeof showCenterPopup === 'function') showCenterPopup('Menunggu pembayaran diselesaikan.', true);
            },
            onError: function(result) {
                if (typeof showCenterPopup === 'function') showCenterPopup('Pembayaran gagal, silakan coba lagi.', false);
            },
            onClose: function() {
                if (typeof showCenterPopup === 'function') showCenterPopup('Jendela pembayaran ditutup.', false);
            }
        });
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-lock me-2"></i>Lanjut ke Pembayaran';
        if (typeof showCenterPopup === 'function') showCenterPopup('Terjadi kesalahan koneksi.', false);
    });
}
</script>
@endpush

@endsection
