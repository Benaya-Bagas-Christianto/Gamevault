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

    <div style="display:flex;align-items:center;gap:12px;margin-bottom:32px;flex-wrap:wrap;">
        <h1 style="font-size:1.8rem;font-weight:800;color:var(--c-ink);margin:0;">Keranjang Belanja</h1>
        @if(count($cartGroups))
        <span style="background:var(--c-primary);color:#000;font-size:0.8rem;font-weight:700;padding:4px 12px;border-radius: 0;">{{ count($cartGroups) }} item</span>
        @endif
    </div>

    {{-- Multi-Select Action Bar (hidden by default) --}}
    <div id="bulk-action-bar" style="display:none;position:sticky;top:70px;z-index:100;background:#0e1f00;border:1px solid var(--c-primary);padding:12px 20px;margin-bottom:16px;border-radius:0;display:none;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:12px;">
            <span id="selected-count-label" style="font-size:0.9rem;color:var(--c-primary);font-weight:700;">0 item dipilih</span>
            <button id="select-all-btn" onclick="selectAllItems()" style="background:rgba(118,185,0,0.1);border:1px solid var(--c-primary);color:var(--c-primary);font-size:0.8rem;font-weight:600;padding:5px 12px;cursor:pointer;border-radius:0;">Pilih Semua</button>
            <button onclick="deselectAll()" style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);color:var(--c-mute);font-size:0.8rem;padding:5px 12px;cursor:pointer;border-radius:0;">Batal</button>
        </div>
        <div style="display:flex;align-items:center;gap:10px;">
            <button id="bulk-buy-btn" onclick="bulkCheckout()" style="background:var(--c-primary);color:#000;font-size:0.85rem;font-weight:700;padding:8px 20px;border:none;cursor:pointer;border-radius:0;"><i class="fas fa-credit-card me-1"></i>Beli Terpilih</button>
            <button id="bulk-delete-btn" onclick="bulkDelete()" style="background:rgba(220,38,38,0.1);border:1px solid #dc2626;color:#f87171;font-size:0.85rem;font-weight:600;padding:8px 20px;cursor:pointer;border-radius:0;"><i class="fas fa-trash me-1"></i>Hapus Terpilih</button>
        </div>
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
        .cart-item-body {
            flex: 1;
            display: grid;
            grid-template-columns: 1fr auto;
            grid-template-areas: 
                "title price"
                "os action";
            padding: 16px 24px;
            gap: 16px;
        }
        .cart-item-title-block { grid-area: title; }
        .cart-price-block-wrapper { grid-area: price; justify-self: end; }
        .cart-os-icons { grid-area: os; align-self: end; }
        .cart-item-actions { grid-area: action; justify-self: end; align-self: end; margin-top:0 !important; }

        @media (max-width: 992px) {
            .cart-layout {
                grid-template-columns: 1fr;
            }
        }
        @media (max-width: 768px) {
            .cart-item-wrapper {
                position: relative;
            }
            .cart-item-checkbox-wrap {
                position: absolute;
                top: 12px;
                left: 12px;
                z-index: 10;
                padding: 0 !important;
                background: rgba(0,0,0,0.65);
                border-radius: 4px;
            }
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
                aspect-ratio: 460/215;
            }
            .cart-item-body {
                grid-template-columns: 1fr auto;
                grid-template-areas: 
                    "title title"
                    "os price"
                    ". action";
                padding: 16px !important;
                gap: 12px 8px !important;
            }
            .cart-price-block-wrapper {
                margin-top: 0 !important;
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
                <div class="cart-item-wrapper" style="display:flex; align-items:stretch; background:var(--c-surface-soft); border-radius:0; overflow:hidden;">
                    {{-- Custom Checkbox --}}
                    <div class="cart-item-checkbox-wrap" style="flex-shrink:0; display:flex; align-items:flex-start; padding:16px 0 16px 16px;">
                        <label style="display:flex;align-items:center;justify-content:center;width:24px;height:24px;background:rgba(255,255,255,0.05);border:1.5px solid rgba(255,255,255,0.25);cursor:pointer;border-radius:0;transition:all 0.2s;" 
                               onmouseenter="this.style.borderColor='var(--c-primary)'" 
                               onmouseleave="this.style.borderColor=this.querySelector('input').checked?'var(--c-primary)':'rgba(255,255,255,0.25)'">
                            <input type="checkbox" class="cart-item-checkbox" data-slug="{{ $game->slug }}" data-price="{{ $price }}" onchange="onCartCheckboxChange(this)" style="position:absolute;opacity:0;width:0;height:0;">
                            <svg class="check-icon" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#76b900" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                        </label>
                    </div>

                    <div class="cart-item-row" data-game-slug="{{ $game->slug }}" data-game-price="{{ $price }}" style="flex:1; display:flex;align-items:stretch;position:relative;">
                    {{-- Cover --}}
                    <a href="{{ route('game.show', $game->slug) }}" class="cart-item-cover" style="flex-shrink:0;width:264px;display:flex;align-items:flex-start;background:transparent;padding:16px;">
                        @if($game->cover_image)
                            <img src="{{ $game->cover_image_url }}" alt="{{ $title }}" style="width:100%;height:auto;object-fit:contain;display:block;">
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a1a,#0d2200);display:flex;align-items:center;justify-content:center;">
                                <i class="fas fa-gamepad" style="color:rgba(118,185,0,0.5);font-size:2rem;"></i>
                            </div>
                        @endif
                    </a>

                    {{-- Info & Price --}}
                    <div class="cart-item-body">
                        {{-- Info --}}
                        <div class="cart-item-title-block" style="min-width:0;display:flex;flex-direction:column;">
                            <a href="{{ route('game.show', $game->slug) }}" style="text-decoration:none;">
                                <h5 style="color:var(--c-ink);font-weight:700;font-size:1.2rem;margin-bottom:8px;line-height:1.2;">{{ $title }}</h5>
                            </a>
                            @if($isBundle)
                                <div style="color:var(--c-mute);font-size:0.85rem;margin-bottom:8px;">
                                    Includes {{ $group['items']->count() }} items: 
                                    {{ $group['items']->pluck('title')->implode(', ') }}
                                </div>
                            @endif
                        </div>
                        
                        {{-- OS Icons --}}
                        <div class="cart-os-icons">
                            <i class="fab fa-windows" style="color:var(--c-mute);font-size:1rem;"></i>
                        </div>

                        {{-- Price Block --}}
                        <div class="cart-price-block-wrapper" style="text-align:right;">
                            @if($price == 0)
                                <div style="color:var(--c-primary);font-size:1.25rem;font-weight:800;margin-top:4px;">GRATIS</div>
                            @else
                                <div class="cart-price-block" style="display:flex;align-items:center;gap:4px;background:#000;padding:2px;border-radius: 0;">
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
                        </div>

                        {{-- Remove Link --}}
                        <div class="cart-item-actions" style="display:flex;gap:12px;font-size:0.75rem;color:var(--c-mute);">
                            @if($isBundle)
                            <button class="remove-bundle-btn" data-game-slug="{{ $game->slug }}" style="background:none;border:none;color:var(--c-mute);text-decoration:none;cursor:pointer;padding:0;transition:color 0.2s;text-decoration:underline;" onmouseenter="this.style.color='var(--c-body)';" onmouseleave="this.style.color='var(--c-mute)';">Hapus</button>
                            @else
                            <button class="remove-cart-btn" data-game-slug="{{ $game->slug }}" style="background:none;border:none;color:var(--c-mute);text-decoration:none;cursor:pointer;padding:0;transition:color 0.2s;text-decoration:underline;" onmouseenter="this.style.color='var(--c-body)';" onmouseleave="this.style.color='var(--c-mute)';">Hapus</button>
                            @endif
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
                        <div style="width:100%;aspect-ratio:460/215;position:relative;">
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

                    if (typeof showCenterPopup === 'function') showCenterPopup(data.message || 'Item dihapus dari keranjang', true);
                    updateBulkBar();
                } else {
                    this.disabled = false;
                    this.innerHTML = 'Hapus';
                    if (typeof showCenterPopup === 'function') showCenterPopup(data.message || 'Gagal menghapus item', false);
                }
            })
            .catch(() => {
                this.disabled = false;
                this.innerHTML = 'Hapus';
                if (typeof showCenterPopup === 'function') showCenterPopup('Terjadi kesalahan', false);
            });
        });
    });

    // --- Multi-select Logic ---
    function updateBulkBar() {
        const checked = document.querySelectorAll('.cart-item-checkbox:checked');
        const bar = document.getElementById('bulk-action-bar');
        const label = document.getElementById('selected-count-label');
        if (checked.length > 0) {
            bar.style.display = 'flex';
            label.textContent = checked.length + ' item dipilih';
        } else {
            bar.style.display = 'none';
        }
    }
    window.onCartCheckboxChange = function(checkbox) {
        const label = checkbox.closest('label');
        const wrapper = checkbox.closest('.cart-item-wrapper');
        const row = wrapper ? wrapper.querySelector('.cart-item-row') : null;
        const icon = label ? label.querySelector('.check-icon') : null;
        if (icon) icon.style.display = checkbox.checked ? 'block' : 'none';
        if (label) {
            label.style.borderColor = checkbox.checked ? 'var(--c-primary)' : 'rgba(255,255,255,0.25)';
            label.style.background = checkbox.checked ? 'rgba(118,185,0,0.15)' : 'rgba(255,255,255,0.05)';
        }
        if (row) row.classList.toggle('is-checked', checkbox.checked);
        updateBulkBar();
    };
    window.selectAllItems = function() {
        document.querySelectorAll('.cart-item-checkbox').forEach(cb => cb.checked = true);
        updateBulkBar();
    };
    window.deselectAll = function() {
        document.querySelectorAll('.cart-item-checkbox').forEach(cb => cb.checked = false);
        updateBulkBar();
    };

    window.bulkDelete = function() {
        const checked = document.querySelectorAll('.cart-item-checkbox:checked');
        if (!checked.length) return;
        const slugs = Array.from(checked).map(cb => cb.dataset.slug);
        const total = slugs.length;
        let done = 0;
        slugs.forEach(slug => {
            const isBundle = document.querySelector(`.remove-bundle-btn[data-game-slug="${slug}"]`) !== null;
            const url = isBundle ? `/cart/${slug}/bundle` : `/cart/${slug}`;
            fetch(url, {
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
            }).then(r => r.json()).then(data => {
                const row = document.querySelector(`.cart-item-row[data-game-slug="${slug}"]`);
                if (row) {
                    const wrapper = row.closest('.cart-item-wrapper');
                    const elementToRemove = wrapper ? wrapper : row;
                    elementToRemove.style.transition = 'all 0.3s';
                    elementToRemove.style.opacity = '0';
                    elementToRemove.style.transform = 'translateX(20px)';
                    setTimeout(() => elementToRemove.remove(), 300);
                }
                if (typeof updateBadge === 'function') updateBadge('cart-count', data.count);
                done++;
                if (done === total) {
                    if (typeof showCenterPopup === 'function') showCenterPopup(total + ' item dihapus dari keranjang', true);
                    updateBulkBar();
                }
            });
        });
    };

    window.bulkCheckout = function() {
        const checked = document.querySelectorAll('.cart-item-checkbox:checked');
        if (!checked.length) return;
        // For now just start normal checkout with all cart items
        startCheckout(document.getElementById('checkout-btn-left'));
    };
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
                
                if (typeof showLoadingPopup === 'function') {
                    showLoadingPopup('Memproses...', 'Mohon tunggu sebentar, sedang menyimpan game ke Library Anda.');
                }

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
