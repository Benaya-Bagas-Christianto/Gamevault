@extends('layouts.app')

@section('title', 'Informasi Bundle ' . $game->title . ' — GameVault')

@section('content')
<style>
    /* ════ BUNDLE PAGE SYSTEM (App-like Sharp Gaming Design) ════ */
    .bundle-page-container {
        padding-top: 20px;
        margin-bottom: 50px;
    }

    /* Top Navigation / Breadcrumb */
    .bundle-top-nav {
        margin-bottom: 16px;
    }
    .bundle-back-link {
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
    .bundle-back-link:hover {
        color: var(--c-primary);
        border-color: var(--c-primary);
        background: var(--c-surface-soft);
    }

    /* Hero Banner */
    .bundle-hero {
        background-color: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        margin-bottom: 24px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.4);
    }
    .bundle-hero-bg {
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
    .bundle-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, var(--c-surface-dark) 45%, rgba(18,22,25,0.75) 100%);
        pointer-events: none;
    }
    .bundle-hero-content {
        position: relative;
        padding: 36px 32px;
        min-height: 160px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        z-index: 2;
    }
    .bundle-hero-tag {
        color: var(--c-primary);
        font-size: 0.82rem;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 6px;
        letter-spacing: 1.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .bundle-hero-title {
        color: #fff;
        font-size: 2.1rem;
        font-weight: 800;
        margin: 0 0 10px 0;
        line-height: 1.2;
        text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        word-break: break-word;
    }
    .bundle-hero-chips {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }
    .bundle-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 0;
        background: rgba(255,255,255,0.06);
        border: 1px solid var(--c-hairline);
        color: var(--c-mute);
    }
    .bundle-chip.highlight {
        background: rgba(118,185,0,0.15);
        border-color: var(--c-primary);
        color: var(--c-primary);
    }

    /* 2-Column Layout on Desktop */
    .bundle-layout {
        display: flex;
        gap: 28px;
        align-items: flex-start;
    }
    .bundle-main {
        flex: 1;
        min-width: 0;
    }
    .bundle-sidebar {
        width: 350px;
        flex-shrink: 0;
        position: sticky;
        top: 24px;
    }

    /* Card Panels */
    .bundle-panel {
        background: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .bundle-panel-header {
        background: rgba(0,0,0,0.3);
        padding: 12px 18px;
        border-bottom: 1px solid var(--c-hairline);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .bundle-panel-title {
        color: var(--c-ink);
        font-size: 0.95rem;
        font-weight: 700;
        text-transform: uppercase;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        letter-spacing: 0.5px;
    }
    .bundle-panel-body {
        padding: 18px;
    }

    /* About Content with Read More */
    .bundle-desc-content {
        color: var(--c-body);
        font-size: 0.92rem;
        line-height: 1.7;
        margin: 0;
    }
    .bundle-desc-content.collapsed {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .bundle-toggle-desc-btn {
        background: transparent;
        border: none;
        color: var(--c-primary);
        font-size: 0.82rem;
        font-weight: 700;
        cursor: pointer;
        padding: 6px 0 0 0;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    /* Items List Container */
    .bundle-items-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    /* Featured Base Game Row */
    .bundle-base-item {
        display: flex;
        align-items: center;
        gap: 14px;
        background: rgba(118,185,0,0.06);
        border: 1px solid rgba(118,185,0,0.35);
        border-radius: 0;
        padding: 12px 14px;
        margin-bottom: 4px;
    }
    .bundle-base-thumb {
        width: 100px;
        height: 54px;
        flex-shrink: 0;
        background: #000;
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        overflow: hidden;
    }
    .bundle-base-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .bundle-base-details {
        flex: 1;
        min-width: 0;
    }
    .bundle-base-tag {
        display: inline-block;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        padding: 2px 6px;
        background: var(--c-primary);
        color: #000;
        border-radius: 0;
        margin-bottom: 3px;
        letter-spacing: 0.5px;
    }
    .bundle-base-title {
        color: #fff;
        font-size: 0.98rem;
        font-weight: 700;
        margin: 0;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bundle-base-price {
        font-size: 0.98rem;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        white-space: nowrap;
    }

    /* DLC Items Rows */
    .bundle-dlc-row {
        display: flex;
        align-items: center;
        gap: 12px;
        background: rgba(0,0,0,0.22);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        padding: 9px 12px;
        transition: all 0.2s ease;
    }
    .bundle-dlc-row:hover {
        background: rgba(255,255,255,0.03);
        border-color: rgba(118,185,0,0.4);
    }
    .bundle-dlc-thumb {
        width: 64px;
        height: 36px;
        flex-shrink: 0;
        background: #000;
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        overflow: hidden;
    }
    .bundle-dlc-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }
    .bundle-dlc-thumb-empty {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--c-mute);
        font-size: 1rem;
    }
    .bundle-dlc-details {
        flex: 1;
        min-width: 0;
    }
    .bundle-dlc-title {
        color: var(--c-ink);
        font-size: 0.88rem;
        font-weight: 600;
        margin: 0;
        line-height: 1.3;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .bundle-dlc-badge {
        font-size: 0.72rem;
        color: var(--c-mute);
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .bundle-dlc-price {
        flex-shrink: 0;
        white-space: nowrap;
        text-align: right;
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--c-ink);
    }

    /* Accordion / Expand Button for 30+ DLCs */
    .bundle-expand-dlc-btn {
        width: 100%;
        padding: 10px 14px;
        background: var(--c-surface-soft);
        border: 1px dashed var(--c-hairline);
        border-radius: 0;
        color: var(--c-primary);
        font-size: 0.85rem;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        margin-top: 6px;
        transition: all 0.2s ease;
    }
    .bundle-expand-dlc-btn:hover {
        background: rgba(118,185,0,0.1);
        border-color: var(--c-primary);
    }

    /* Desktop Sidebar Purchase Box */
    .bundle-desktop-buy-card {
        background: var(--c-surface-dark);
        border: 1px solid var(--c-primary);
        border-radius: 0;
        padding: 22px;
        box-shadow: 0 8px 30px rgba(0,0,0,0.5);
    }
    .bundle-pricing-table {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }
    .bundle-price-line {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 0.88rem;
        color: var(--c-mute);
    }
    .bundle-price-line.discount {
        color: var(--c-primary);
        font-weight: 700;
    }
    .bundle-price-line.total {
        padding-top: 12px;
        border-top: 1px solid var(--c-hairline);
        color: #fff;
        font-size: 1.25rem;
        font-weight: 800;
    }
    .bundle-main-buy-btn {
        width: 100%;
        padding: 13px 20px;
        background: var(--c-primary);
        color: #000;
        border: none;
        border-radius: 0;
        font-size: 0.95rem;
        font-weight: 800;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
    }
    .bundle-main-buy-btn:hover:not(:disabled) {
        filter: brightness(1.12);
        box-shadow: 0 0 15px rgba(118,185,0,0.4);
    }
    .bundle-main-buy-btn:disabled,
    .bundle-main-buy-btn.in-cart {
        background: var(--c-surface-soft) !important;
        color: var(--c-mute) !important;
        cursor: not-allowed !important;
        border: 1px solid var(--c-hairline) !important;
    }

    /* ════ MOBILE STICKY BOTTOM BAR (Native App Feel) ════ */
    .bundle-mobile-sticky-bar {
        display: none;
    }

    /* ════ MOBILE RESPONSIVE ADAPTATIONS (<= 768px) ════ */
    @media (max-width: 768px) {
        .bundle-page-container {
            padding-top: 14px;
            padding-left: 12px;
            padding-right: 12px;
            padding-bottom: 95px; /* Leave space for bottom bar */
            margin-bottom: 20px;
        }

        .bundle-hero {
            margin-bottom: 16px;
        }
        .bundle-hero-content {
            padding: 20px 16px;
            min-height: 110px;
        }
        .bundle-hero-bg {
            width: 75%;
            opacity: 0.2;
        }
        .bundle-hero-tag {
            font-size: 0.72rem;
            letter-spacing: 1px;
            margin-bottom: 4px;
        }
        .bundle-hero-title {
            font-size: 1.35rem;
            margin-bottom: 8px;
        }

        /* 1-Column Layout */
        .bundle-layout {
            flex-direction: column;
            gap: 16px;
        }
        .bundle-main {
            width: 100%;
        }
        .bundle-sidebar {
            width: 100%;
            position: static;
        }

        /* Panels on Mobile */
        .bundle-panel {
            margin-bottom: 14px;
        }
        .bundle-panel-header {
            padding: 10px 14px;
        }
        .bundle-panel-title {
            font-size: 0.88rem;
        }
        .bundle-panel-body {
            padding: 14px;
        }

        /* Base Game Card in Mobile */
        .bundle-base-item {
            padding: 10px 12px;
            gap: 10px;
        }
        .bundle-base-thumb {
            width: 72px;
            height: 42px;
        }
        .bundle-base-title {
            font-size: 0.9rem;
        }
        .bundle-base-price {
            font-size: 0.9rem;
        }

        /* DLC item in Mobile */
        .bundle-dlc-row {
            padding: 8px 10px;
            gap: 10px;
        }
        .bundle-dlc-thumb {
            width: 54px;
            height: 32px;
        }
        .bundle-dlc-title {
            font-size: 0.82rem;
        }
        .bundle-dlc-price {
            font-size: 0.82rem;
        }

        /* Hide desktop sidebar buy card on mobile, replace with inline receipt before footer */
        .bundle-desktop-buy-card {
            padding: 16px;
        }
        .bundle-desktop-buy-card h3 {
            font-size: 1rem;
        }
        .bundle-price-line.total {
            font-size: 1.15rem;
        }

        /* ═══ NATIVE STICKY BOTTOM BAR (Mobile App Feature) ═══ */
        .bundle-mobile-sticky-bar {
            display: flex;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            background: rgba(14, 18, 20, 0.96);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-top: 1px solid var(--c-hairline);
            padding: 10px 14px;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 -4px 20px rgba(0,0,0,0.6);
        }
        .bundle-sticky-info {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .bundle-sticky-label {
            font-size: 0.7rem;
            color: var(--c-mute);
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .bundle-sticky-badge {
            background: rgba(118,185,0,0.18);
            color: var(--c-primary);
            font-size: 0.68rem;
            font-weight: 800;
            padding: 1px 5px;
            border-radius: 0;
        }
        .bundle-sticky-price {
            display: flex;
            align-items: baseline;
            gap: 8px;
        }
        .bundle-sticky-current {
            color: var(--c-primary);
            font-size: 1.15rem;
            font-weight: 800;
        }
        .bundle-sticky-original {
            color: var(--c-mute);
            font-size: 0.75rem;
            text-decoration: line-through;
        }
        .bundle-sticky-btn {
            padding: 10px 18px;
            background: var(--c-primary);
            color: #000;
            border: none;
            border-radius: 0;
            font-size: 0.85rem;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            white-space: nowrap;
            letter-spacing: 0.3px;
            transition: all 0.2s ease;
        }
        .bundle-sticky-btn:disabled,
        .bundle-sticky-btn.in-cart {
            background: var(--c-surface-soft) !important;
            color: var(--c-mute) !important;
            cursor: not-allowed !important;
            border: 1px solid var(--c-hairline) !important;
        }
    }
</style>

<div class="container bundle-page-container">
    
    {{-- Breadcrumb / Header --}}
    <div class="bundle-top-nav">
        <a href="{{ route('game.show', $game->slug) }}" class="bundle-back-link">
            <i class="fas fa-arrow-left"></i> Kembali ke {{ $game->title }}
        </a>
    </div>

    {{-- Hero Section --}}
    <div class="bundle-hero">
        <div class="bundle-hero-bg" style="background-image: url('{{ $game->cover_image_url }}');"></div>
        <div class="bundle-hero-overlay"></div>

        <div class="bundle-hero-content">
            <div class="bundle-hero-tag">
                <i class="fas fa-boxes-stacked"></i> Paket Koleksi Lengkap Resmi
            </div>
            <h1 class="bundle-hero-title">{{ $game->title }} Bundle</h1>
            
            <div class="bundle-hero-chips">
                <span class="bundle-chip highlight"><i class="fas fa-percent"></i> Hemat {{ $bundleDiscountPercent }}%</span>
                <span class="bundle-chip"><i class="fas fa-layer-group"></i> {{ $game->dlcs->count() + 1 }} Item Termasuk</span>
                <span class="bundle-chip"><i class="fas fa-shield-halved"></i> Lisensi Game Resmi</span>
            </div>
        </div>
    </div>

    {{-- Main Layout --}}
    <div class="bundle-layout">
        
        {{-- Left: Content Area --}}
        <div class="bundle-main">
            
            {{-- 1. Tentang Paket Ini (Collapsible Mobile Friendly) --}}
            <div class="bundle-panel">
                <div class="bundle-panel-header">
                    <h2 class="bundle-panel-title">
                        <i class="fas fa-info-circle" style="color: var(--c-primary);"></i>
                        Tentang Paket Bundle Ini
                    </h2>
                </div>
                <div class="bundle-panel-body">
                    <p class="bundle-desc-content collapsed" id="bundle-desc-text">{{ $bundleDescription }}</p>
                    <button type="button" class="bundle-toggle-desc-btn" id="bundle-desc-toggle" onclick="toggleBundleDesc()">
                        <span>Lihat Selengkapnya</span> <i class="fas fa-chevron-down"></i>
                    </button>
                </div>
            </div>

            {{-- 2. Daftar Item yang Termasuk (Base Game + DLC List dengan Expand) --}}
            <div class="bundle-panel">
                <div class="bundle-panel-header">
                    <h2 class="bundle-panel-title">
                        <i class="fas fa-cubes" style="color: var(--c-primary);"></i>
                        Item Termasuk Dalam Paket
                    </h2>
                    <div style="font-size: 0.8rem; color: var(--c-mute); font-weight: 600;">
                        Total <span style="color: var(--c-primary); font-weight: 700;">{{ $game->dlcs->count() + 1 }}</span> Item
                    </div>
                </div>

                <div class="bundle-panel-body">
                    <div class="bundle-items-list">
                        
                        {{-- Game Utama (Base Game) --}}
                        <div class="bundle-base-item">
                            <div class="bundle-base-thumb">
                                @if($game->cover_image)
                                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" loading="lazy">
                                @else
                                    <div class="bundle-dlc-thumb-empty"><i class="fas fa-gamepad"></i></div>
                                @endif
                            </div>
                            <div class="bundle-base-details">
                                <span class="bundle-base-tag">Game Utama (Base Game)</span>
                                <h3 class="bundle-base-title">{{ $game->title }}</h3>
                            </div>
                            <div class="bundle-base-price">
                                @if($game->price == 0)
                                    <span style="color: var(--c-primary);">Free</span>
                                @else
                                    Rp {{ number_format($game->price, 0, ',', '.') }}
                                @endif
                            </div>
                        </div>

                        {{-- DLC Items --}}
                        @php
                            $previewCount = 4; // Show first 4 on mobile, rest collapsed
                            $totalDlcs = $game->dlcs->count();
                        @endphp

                        @foreach($game->dlcs as $idx => $dlc)
                            <div class="bundle-dlc-row {{ $idx >= $previewCount ? 'bundle-extra-dlc' : '' }}" 
                                 style="{{ $idx >= $previewCount ? 'display: none;' : '' }}">
                                <div class="bundle-dlc-thumb">
                                    @if($dlc->cover_image)
                                        <img src="{{ $dlc->cover_image_url }}" alt="{{ $dlc->title }}" loading="lazy">
                                    @else
                                        <div class="bundle-dlc-thumb-empty"><i class="fas fa-puzzle-piece"></i></div>
                                    @endif
                                </div>
                                <div class="bundle-dlc-details">
                                    <h4 class="bundle-dlc-title">{{ $dlc->title }}</h4>
                                    <div class="bundle-dlc-badge">
                                        <i class="fab fa-windows"></i> DLC Resmi #{{ $idx + 1 }}
                                    </div>
                                </div>
                                <div class="bundle-dlc-price">
                                    @if($dlc->price == 0)
                                        <span style="color: var(--c-primary);">Free</span>
                                    @else
                                        Rp {{ number_format($dlc->price, 0, ',', '.') }}
                                    @endif
                                </div>
                            </div>
                        @endforeach

                        {{-- Expand Button if more than 4 DLCs --}}
                        @if($totalDlcs > $previewCount)
                            <button type="button" class="bundle-expand-dlc-btn" id="bundle-expand-dlc-btn" onclick="toggleAllDlcs()">
                                <i class="fas fa-chevron-down"></i>
                                <span>Lihat Semua {{ $totalDlcs }} DLC (+{{ $totalDlcs - $previewCount }} lainnya)</span>
                            </button>
                        @endif

                    </div>
                </div>
            </div>

        </div>

        {{-- Right: Sidebar (On Desktop sits sticky on the right, on Mobile acts as Receipt before footer) --}}
        <div class="bundle-sidebar">
            <div class="bundle-desktop-buy-card">
                <div style="border-bottom: 1px solid var(--c-hairline); padding-bottom: 14px; margin-bottom: 16px;">
                    <h3 style="color: #fff; font-size: 1.1rem; font-weight: 700; margin: 0 0 4px 0; text-transform: uppercase;">
                        Rincian Pembelian Bundle
                    </h3>
                    <div style="color: var(--c-mute); font-size: 0.8rem;">
                        Game dasar + seluruh {{ $game->dlcs->count() }} konten tambahan
                    </div>
                </div>

                <div class="bundle-pricing-table">
                    <div class="bundle-price-line">
                        <span>Total Harga Satuan:</span>
                        <span style="text-decoration: line-through;">Rp {{ number_format($totalOriginalPrice, 0, ',', '.') }}</span>
                    </div>

                    <div class="bundle-price-line discount">
                        <span>Diskon Paket ({{ $bundleDiscountPercent }}%):</span>
                        <span>- Rp {{ number_format($bundleDiscountAmount, 0, ',', '.') }}</span>
                    </div>

                    <div class="bundle-price-line total">
                        <span>Total Bayar:</span>
                        <span style="color: var(--c-primary);">Rp {{ number_format($bundlePrice, 0, ',', '.') }}</span>
                    </div>
                </div>

                <button type="button" 
                        class="bundle-main-buy-btn {{ $isBundleInCart ? 'in-cart' : '' }}" 
                        id="bundle-main-buy-btn"
                        onclick="addBundleToCart('{{ $game->slug }}')"
                        {{ $isBundleInCart ? 'disabled' : '' }}>
                    @if($isBundleInCart)
                        <i class="fas fa-check"></i> Bundle di Keranjang
                    @else
                        <i class="fas fa-cart-plus"></i> Tambah Bundle ke Keranjang
                    @endif
                </button>

                <div style="font-size: 0.75rem; color: var(--c-mute); text-align: center; margin-top: 12px; line-height: 1.5;">
                    <i class="fas fa-shield-alt" style="color: var(--c-primary);"></i>
                    Garansi lisensi resmi & kompatibel dengan akun GameVault Anda.
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ════ STICKY BOTTOM BAR FOR MOBILE SCREENS ════ --}}
<div class="bundle-mobile-sticky-bar" id="bundle-mobile-bar">
    <div class="bundle-sticky-info">
        <div class="bundle-sticky-label">
            <span>Total Bundle</span>
            <span class="bundle-sticky-badge">-{{ $bundleDiscountPercent }}%</span>
        </div>
        <div class="bundle-sticky-price">
            <span class="bundle-sticky-current">Rp {{ number_format($bundlePrice, 0, ',', '.') }}</span>
            <span class="bundle-sticky-original">Rp {{ number_format($totalOriginalPrice, 0, ',', '.') }}</span>
        </div>
    </div>
    
    <button type="button" 
            class="bundle-sticky-btn {{ $isBundleInCart ? 'in-cart' : '' }}" 
            id="bundle-sticky-btn"
            onclick="addBundleToCart('{{ $game->slug }}')"
            {{ $isBundleInCart ? 'disabled' : '' }}>
        @if($isBundleInCart)
            <i class="fas fa-check"></i> Di Keranjang
        @else
            <i class="fas fa-shopping-cart"></i> + Keranjang
        @endif
    </button>
</div>

{{-- Scripts for Mobile Toggle & Cart Actions --}}
<script>
// Toggle Read More Description
function toggleBundleDesc() {
    const textEl = document.getElementById('bundle-desc-text');
    const btnEl = document.getElementById('bundle-desc-toggle');
    if (!textEl || !btnEl) return;

    if (textEl.classList.contains('collapsed')) {
        textEl.classList.remove('collapsed');
        btnEl.innerHTML = '<span>Sembunyikan</span> <i class="fas fa-chevron-up"></i>';
    } else {
        textEl.classList.add('collapsed');
        btnEl.innerHTML = '<span>Lihat Selengkapnya</span> <i class="fas fa-chevron-down"></i>';
    }
}

// Toggle Extra DLCs list
let isDlcsExpanded = false;
function toggleAllDlcs() {
    const extraRows = document.querySelectorAll('.bundle-extra-dlc');
    const toggleBtn = document.getElementById('bundle-expand-dlc-btn');
    const totalCount = {{ $game->dlcs->count() }};
    const hiddenCount = totalCount - {{ $previewCount }};

    isDlcsExpanded = !isDlcsExpanded;

    extraRows.forEach(row => {
        row.style.display = isDlcsExpanded ? 'flex' : 'none';
    });

    if (toggleBtn) {
        if (isDlcsExpanded) {
            toggleBtn.innerHTML = '<i class="fas fa-chevron-up"></i> <span>Tutup Sebagian DLC ▲</span>';
        } else {
            toggleBtn.innerHTML = `<i class="fas fa-chevron-down"></i> <span>Lihat Semua ${totalCount} DLC (+${hiddenCount} lainnya) ▼</span>`;
        }
    }
}

// Add Bundle to Cart (Shared by desktop and mobile sticky buttons)
function addBundleToCart(gameSlug) {
    if (typeof IS_AUTHENTICATED !== 'undefined' && !IS_AUTHENTICATED) {
        if (typeof showLoginRequiredPopup === 'function') {
            showLoginRequiredPopup('keranjang');
        } else if (typeof showSweetAlert === 'function') {
            showSweetAlert('Perhatian!', 'Silakan login terlebih dahulu untuk menambahkan bundle ke keranjang.', 'warning');
        }
        return;
    }

    const mainBtn = document.getElementById('bundle-main-buy-btn');
    const stickyBtn = document.getElementById('bundle-sticky-btn');
    
    if ((mainBtn && mainBtn.disabled) || (stickyBtn && stickyBtn.disabled)) return;

    const originalMainHtml = mainBtn ? mainBtn.innerHTML : '';
    const originalStickyHtml = stickyBtn ? stickyBtn.innerHTML : '';

    if (mainBtn) {
        mainBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menambahkan...';
        mainBtn.disabled = true;
    }
    if (stickyBtn) {
        stickyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Proses...';
        stickyBtn.disabled = true;
    }

    fetch(`/cart/${gameSlug}/bundle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(r => {
        if (r.status === 401) {
            if (typeof showLoginRequiredPopup === 'function') {
                showLoginRequiredPopup('keranjang');
            }
            throw new Error('Unauthenticated');
        }
        return r.json();
    })
    .then(data => {
        if (data.error) {
            if (typeof showCenterPopup === 'function') {
                showCenterPopup(data.error, false);
            }
            if (mainBtn) {
                mainBtn.innerHTML = originalMainHtml;
                mainBtn.disabled = false;
            }
            if (stickyBtn) {
                stickyBtn.innerHTML = originalStickyHtml;
                stickyBtn.disabled = false;
            }
            return;
        }

        // Update both buttons
        if (mainBtn) {
            mainBtn.innerHTML = '<i class="fas fa-check"></i> Bundle di Keranjang';
            mainBtn.classList.add('in-cart');
            mainBtn.disabled = true;
        }
        if (stickyBtn) {
            stickyBtn.innerHTML = '<i class="fas fa-check"></i> Di Keranjang';
            stickyBtn.classList.add('in-cart');
            stickyBtn.disabled = true;
        }

        if (typeof showCenterPopup === 'function') {
            showCenterPopup('Bundle berhasil ditambahkan ke keranjang!', true);
        }
        if (typeof updateBadge === 'function') {
            updateBadge('cart-count', data.count);
        }
    })
    .catch(err => {
        if (mainBtn) {
            mainBtn.innerHTML = originalMainHtml;
            mainBtn.disabled = false;
        }
        if (stickyBtn) {
            stickyBtn.innerHTML = originalStickyHtml;
            stickyBtn.disabled = false;
        }
        if (err.message !== 'Unauthenticated') {
            console.error(err);
            if (typeof showCenterPopup === 'function') {
                showCenterPopup('Terjadi kesalahan saat menambahkan bundle.', false);
            }
        }
    });
}
</script>
@endsection
