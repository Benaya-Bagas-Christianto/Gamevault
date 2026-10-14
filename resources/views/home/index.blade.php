@extends('layouts.app')

@section('title', 'GameVault — Temukan Game Impianmu')

@section('content')

{{-- ===================== SEARCH BAR ===================== --}}
<section class="container" style="margin-top:var(--sp-xl); position:relative; max-width:800px;">
    <form action="{{ route('store') }}" method="GET" class="search-wrap" style="display:flex;align-items:center;background:var(--c-surface-elevated);border:1px solid var(--c-primary);border-radius: 0;padding:var(--sp-sm) var(--sp-lg);width:100%;box-shadow:0 8px 30px rgba(0,0,0,0.3);transition:border-color 0.3s;" id="mainSearchForm">
        <input type="text" name="search" id="mainSearchInput" value="{{ request('search') }}" placeholder="Search the store..." style="background:transparent;border:none;color:var(--c-ink);outline:none;width:100%;font-size:1rem;padding:var(--sp-xs);font-family:inherit;" autocomplete="off">
        <button type="submit" style="background:transparent;border:none;color:var(--c-primary);cursor:pointer;padding:var(--sp-sm);font-size:1.1rem;margin-left:var(--sp-md);">
            <i class="fas fa-search"></i>
        </button>
    </form>
    
    {{-- DROPDOWN --}}
    <div id="searchSuggestionsBox" style="display:none; position:absolute; top:calc(100% + var(--sp-sm)); left:0; width:100%; background:var(--c-surface-elevated); border:1px solid var(--c-hairline-strong); border-radius: 0; box-shadow:0 12px 40px rgba(0,0,0,0.7); z-index:1000; padding:var(--sp-lg); color:var(--c-ink); overflow:hidden;">
        <div id="searchSuggestionsContent">
            <!-- Content injected via JS -->
        </div>
        <div style="margin-top:var(--sp-lg);">
            <a href="#" onclick="document.getElementById('mainSearchForm').submit(); return false;" class="btn btn-primary w-full" style="display:block; text-align:center; padding:var(--sp-md); text-decoration:none;">Pencarian Lanjutan</a>
        </div>
    </div>
</section>

{{-- ===================== HERO CAROUSEL ===================== --}}
@if($heroGames->count() > 0)
<style>
    .hero-slide {
        position: absolute;
        top: 0;
        height: 100%;
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        border-radius: 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.8);
        background: var(--c-surface-elevated);
        overflow: hidden;
    }
    #hero-carousel-container {
        /* Carousel Dimensions */
        --active-w: min(90vw, 1200px);
        --half-active-w: calc(var(--active-w) / 2);
        --img-ratio: 0.65;
        --peeking-w: calc(var(--active-w) * var(--img-ratio));
        --gap: 20px;
        --carousel-h: calc(var(--active-w) * var(--img-ratio) * 215 / 460);
    }

    .hero-slide.slide-active {
        left: calc(50% - var(--half-active-w));
        width: var(--active-w);
        z-index: 10;
        opacity: 1;
        cursor: pointer;
        filter: grayscale(0) brightness(1);
        transform: scale(1);
        transform-origin: center center;
    }
    .hero-slide.slide-prev {
        left: calc(50% - var(--half-active-w) - var(--gap) - var(--peeking-w));
        width: var(--peeking-w);
        z-index: 5;
        opacity: 0.8;
        cursor: pointer;
        filter: grayscale(1) brightness(0.25);
        transform: scale(0.95);
        transform-origin: right center;
    }
    .hero-slide.slide-next {
        left: calc(50% + var(--half-active-w) + var(--gap));
        width: var(--peeking-w);
        z-index: 5;
        opacity: 0.8;
        cursor: pointer;
        filter: grayscale(1) brightness(0.25);
        transform: scale(0.95);
        transform-origin: left center;
    }
    .hero-slide.slide-hidden {
        left: calc(50% - calc(var(--peeking-w) / 2));
        width: var(--peeking-w);
        z-index: 1;
        opacity: 0;
        pointer-events: none;
        transform: scale(0.7);
        transform-origin: center center;
    }

    .hero-main-img {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 100%;
        transition: width 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    .hero-slide.slide-active .hero-main-img {
        width: 65%;
    }

    .hero-info-box {
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 35%;
        opacity: 0;
        transition: opacity 0.5s ease-in-out;
        visibility: hidden;
        padding: var(--sp-lg) var(--sp-xl);
        display: flex;
        flex-direction: column;
        background: var(--c-surface-elevated);
    }
    .hero-slide.slide-active .hero-info-box {
        opacity: 1;
        visibility: visible;
    }

    .hero-nav-btn {
        position: absolute;
        top: 0;
        bottom: 0;
        width: 80px;
        z-index: 20;
        background: transparent !important;
        border: none !important;
        outline: none !important;
        color: white;
        font-size: 3rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.7;
        transition: opacity 0.3s, transform 0.2s;
        text-shadow: 0 0 15px rgba(0,0,0,1);
    }
    .hero-nav-btn:hover {
        opacity: 1;
        transform: scale(1.1);
        background: transparent !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .hero-nav-btn.left {
        right: calc(50% + var(--half-active-w) + 10px);
    }
    .hero-nav-btn.right {
        left: calc(50% + var(--half-active-w) + 10px);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        #hero-carousel-container {
            --active-w: 95vw;
            --img-ratio: 0.55;
            --carousel-h: 400px;
        }
        .hero-slide.slide-active .hero-main-img {
            width: 100%;
        }
        .hero-info-box {
            display: none !important; /* Hide info box on tablet/mobile for cleaner look, or stack it */
        }
        .hero-slide.slide-active .hero-main-img {
            width: 100% !important;
        }
    }
    
    @media (max-width: 768px) {
        #hero-carousel-container {
            --active-w: 92vw;
            --img-ratio: 1; /* full width */
            --carousel-h: 250px;
            --gap: 10px;
            --peeking-w: 10vw;
        }
        
        .hero-nav-btn {
            width: 40px;
            font-size: 2rem;
        }
        
        .hero-nav-btn.left {
            right: auto;
            left: 10px;
        }
        .hero-nav-btn.right {
            left: auto;
            right: 10px;
        }
    }
</style>
<section style="width:100%; margin-top:var(--sp-xxl); margin-bottom:var(--sp-section);">
    <div class="container">
        <h2 style="font-size:1.5rem;font-weight:800;color:var(--c-ink);margin-bottom:var(--sp-lg);text-transform:uppercase;letter-spacing:1px;">Featured & Recommended</h2>
    </div>
    {{-- Breakout trick to span exactly 100vw regardless of parent padding --}}
    <div style="position:relative; width:100vw; height:var(--carousel-h); overflow:hidden; margin-left:calc(-50vw + 50%);" id="hero-carousel-container">
        
        {{-- PREV / NEXT ARROWS --}}
        <button class="hero-nav-btn left" onclick="event.stopPropagation(); showHeroSlide(currentHeroSlide - 1 < 0 ? heroSlides.length - 1 : currentHeroSlide - 1); resetHeroInterval();">
            <svg width="36" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="15 20 6 12 15 4"></polyline></svg>
        </button>
        <button class="hero-nav-btn right" onclick="event.stopPropagation(); showHeroSlide(currentHeroSlide + 1 >= heroSlides.length ? 0 : currentHeroSlide + 1); resetHeroInterval();">
            <svg width="36" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="9 20 18 12 9 4"></polyline></svg>
        </button>

        @foreach($heroGames as $index => $game)
        {{-- SLIDE --}}
        <div class="hero-slide {{ $index === 0 ? 'slide-active' : ($index === 1 ? 'slide-next' : ($index === count($heroGames)-1 ? 'slide-prev' : 'slide-hidden')) }}" data-slide="{{ $index }}" onclick="<?php echo 'if(this.classList.contains(\'slide-active\')) { window.location.href=\''.route('game.show', $game->slug).'\'; } else { goToHeroSlide('.$index.'); }'; ?>" onmouseenter="playHeroVideo(this)" onmouseleave="pauseHeroVideo(this)">
            
            {{-- LEFT: MAIN IMAGE --}}
            <div class="hero-main-img">
                @if(in_array($game->id, $ownedIds))
                <div class="in-library-label" style="top: var(--sp-lg); left: var(--sp-lg);">
                    <i class="fas fa-check label-icon"></i>
                    <span class="label-text">DI LIBRARY</span>
                </div>
                @elseif(in_array($game->id, $cartIds ?? []))
                <div class="status-label-cart" style="top: var(--sp-lg); left: var(--sp-lg);">
                    <i class="fas fa-shopping-cart label-icon"></i>
                    <span class="label-text">DI KERANJANG</span>
                </div>
                @elseif(in_array($game->id, $wishlistIds ?? []))
                <div class="status-label-wishlist" style="top: var(--sp-lg); left: var(--sp-lg);">
                    <i class="fas fa-heart label-icon"></i>
                    <span class="label-text">WISHLIST</span>
                </div>
                @endif
                
                @if($game->cover_image)
                <img class="hero-poster" src="{{ $game->cover_image_url }}" style="width:100%;height:100%;object-fit:cover; {{ in_array($game->id, $ownedIds) ? 'filter: grayscale(1) opacity(0.7);' : '' }}">
                @else
                <div style="width:100%;height:100%;background:linear-gradient(135deg,#111,#000);"></div>
                @endif
                
                @php $video = $game->galleries->where('type', 'video')->first(); @endphp
                @if($video)
                <video class="hero-video" data-src="{{ $video->path }}" muted loop playsinline style="position:absolute; top:0; left:0; width:100%; height:100%; object-fit:cover; opacity:0; transition:opacity 0.5s ease-in-out; pointer-events:none; z-index:2;"></video>
                @endif
            </div>

            {{-- RIGHT: INFO BOX --}}
            <div class="hero-info-box">
                <h1 style="color:#fff;font-size:1.8rem;font-weight:700;line-height:1.3;margin-bottom:var(--sp-sm);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;flex-shrink:0;">{{ $game->title }}</h1>
                
                {{-- REVIEWS --}}
                @if($game->review_tier && $game->review_tier !== '-')
                <div style="margin-bottom:var(--sp-md); font-size:0.85rem; display:flex; gap:var(--sp-sm); align-items:center;flex-shrink:0;">
                    <span style="color:{{ $game->review_color ?? '#66C0F4' }}; font-weight:700;">{{ $game->review_tier }}</span>
                    @if($game->reviews_count > 0)
                        <span style="color:var(--c-mute);">({{ number_format($game->reviews_count, 0, ',', '.') }} Reviews)</span>
                    @endif
                </div>
                @endif

                {{-- SCREENSHOTS GRID (2x2) --}}
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:var(--sp-xs); margin-bottom:var(--sp-md); flex-shrink:0;">
                    @php
                        $shots = $game->galleries->where('type', 'image')->take(4)->values();
                    @endphp
                    @for($i = 0; $i < 4; $i++)
                        @if(isset($shots[$i]))
                            @php
                                $path = $shots[$i]->path;
                                $imgUrl = filter_var($path, FILTER_VALIDATE_URL) ? $path : asset('storage/' . $path);
                            @endphp
                            <img src="{{ $imgUrl }}" style="width:100%; aspect-ratio:16/9; object-fit:contain;background:#050505; border-radius: 0; background:#000;">
                        @else
                            {{-- Fallback: main image or empty box --}}
                            <div style="width:100%; height:100%; background:var(--c-surface-soft); border-radius: 0; display:flex; align-items:center; justify-content:center;">
                                <i class="fas fa-image" style="color:rgba(255,255,255,0.1); font-size:1.5rem;"></i>
                            </div>
                        @endif
                    @endfor
                </div>

                {{-- TAGS --}}
                <div style="margin-bottom:auto;flex-shrink:0;overflow:hidden;">
                    <div style="font-size:0.8rem; color:var(--c-mute); margin-bottom:var(--sp-xs);">Recommended because you played games tagged with</div>
                    <div style="display:flex; flex-wrap:wrap; gap:var(--sp-xxs);">
                        @if(is_array($game->genre))
                            @foreach(array_slice($game->genre, 0, 4) as $tag)
                            <span style="background:var(--c-surface-soft); color:var(--c-body); font-size:11px; padding:var(--sp-xxs) var(--sp-sm); border-radius: 0; text-transform:capitalize; font-weight:700;">{{ $tag }}</span>
                            @endforeach
                        @endif
                    </div>
                </div>

                {{-- PRICE --}}
                <div style="display:flex; justify-content:flex-end; align-items:flex-end; gap:var(--sp-sm); flex-shrink:0; margin-top:var(--sp-sm); margin-bottom:8px;">
                    @if($game->price == 0)
                        <div style="color:var(--c-primary);font-size:1.1rem;font-weight:900;">GRATIS</div>
                    @else
                        @if($game->discount_percent > 0)
                            <div style="background:var(--c-primary);color:#000;font-size:0.85rem;font-weight:900;padding:var(--sp-xxs) var(--sp-xs);border-radius: 0;">-{{ $game->discount_percent }}%</div>
                            <div style="color:var(--c-mute);font-size:0.85rem;text-decoration:line-through;opacity:0.8;">Rp {{ number_format($game->original_price, 0, ',', '.') }}</div>
                        @endif
                        <div style="color:var(--c-ink);font-size:1.1rem;font-weight:700;">Rp {{ number_format($game->price, 0, ',', '.') }}</div>
                    @endif
                </div>

            </div>

        </div>
        @endforeach
    </div>

    {{-- DOT INDICATORS (OUTSIDE THE SLIDER BOX) --}}
    <div style="display:flex; justify-content:center; gap:var(--sp-sm); margin-top:var(--sp-lg);">
        @foreach($heroGames as $dotIndex => $dotGame)
        <button class="hero-dot {{ $dotIndex === 0 ? 'active' : '' }}" data-index="{{ $dotIndex }}" onclick="event.stopPropagation(); goToHeroSlide({{ $dotIndex }});" style="width:{{ $dotIndex === 0 ? '24px' : '16px' }}; height:10px; border-radius: 0; background:{{ $dotIndex === 0 ? 'rgba(255,255,255,0.8)' : 'rgba(255,255,255,0.2)' }}; border:none; cursor:pointer; transition:all 0.3s; padding:0;" onmouseenter="if(!this.classList.contains('active')) this.style.background='rgba(255,255,255,0.4)';" onmouseleave="if(!this.classList.contains('active')) this.style.background='rgba(255,255,255,0.2)';"></button>
        @endforeach
    </div>

</section>
@endif



{{-- ===================== SLIDER COMPONENT MACRO ===================== --}}
@php
function renderGameCard($game, $rank = null, $ownedIds = [], $cartIds = [], $wishlistIds = []) {
$isOwned = in_array($game->id, $ownedIds);
$inCart = in_array($game->id, $cartIds);
$inWishlist = in_array($game->id, $wishlistIds);
$badge = '';
if ($game->discount_percent > 0) {
    $badge = '<div style="position:absolute;top:var(--sp-sm);right:var(--sp-sm);background:var(--c-primary);color:#000;font-size:12px;font-weight:800;padding:var(--sp-xxs) var(--sp-sm);border-radius: 0;z-index:2;">-'.$game->discount_percent.'%</div>';
} elseif ($game->price == 0) {
    $badge = '<div style="position:absolute;top:var(--sp-sm);right:var(--sp-sm);background:var(--c-accent-green-pale);color:#000;font-size:11px;font-weight:800;padding:var(--sp-xxs) var(--sp-sm);border-radius: 0;letter-spacing:0.5px;text-transform:uppercase;z-index:2;">GRATIS</div>';
}
$imgStyle = 'width:100%;height:100%;object-fit:cover;transition:transform 0.4s;';
if ($isOwned) {
    $imgStyle .= ' filter: grayscale(1) opacity(0.7);';
}
$img = $game->cover_image ? '<img src="' . $game->cover_image_url . '" style="' . $imgStyle . '" onerror="this.outerHTML=\'<div style=\\\'width:100%;height:100%;background:linear-gradient(135deg,#1a1a1a,#0d2200);display:flex;align-items:center;justify-content:center;\\\'><i class=\\\'fas fa-gamepad\\\' style=\\\'font-size:2.5rem;color:rgba(118,185,0,0.5);\\\'></i></div>\'">' : '<div style="width:100%;height:100%;background:linear-gradient(135deg,#1a1a1a,#0d2200);display:flex;align-items:center;justify-content:center;"><i class="fas fa-gamepad" style="font-size:2.5rem;color:rgba(118,185,0,0.5);"></i></div>';

$rankHtml = ($rank && !$isOwned) ? '<div style="position:absolute;top:var(--sp-sm);left:var(--sp-sm);background:#000;color:#fff;width:28px;height:28px;display:flex;align-items:center;justify-content:center;border-radius: 0;font-weight:900;border:1px solid rgba(255,255,255,0.2);z-index:2;box-shadow:0 var(--sp-xxs) var(--sp-sm) rgba(0,0,0,0.5);">'.$rank.'</div>' : '';

$statusHtml = '';
if ($isOwned) {
    $statusHtml = '<div class="in-library-label" style="top: var(--sp-sm);"><i class="fas fa-check label-icon"></i><span class="label-text">DI LIBRARY</span></div>';
} elseif ($inCart) {
    $statusHtml = '<div class="status-label-cart" style="top: var(--sp-sm);"><i class="fas fa-shopping-cart label-icon"></i><span class="label-text">DI KERANJANG</span></div>';
} elseif ($inWishlist) {
    $statusHtml = '<div class="status-label-wishlist" style="top: var(--sp-sm);"><i class="fas fa-heart label-icon"></i><span class="label-text">WISHLIST</span></div>';
}


$genreHtml = '';
if(is_array($game->genre)) {
foreach(array_slice($game->genre, 0, 1) as $g) {
$genreHtml .= '<span class="badge" style="background:var(--c-surface-soft);color:var(--c-body);font-size:11px;padding:var(--sp-xxs) var(--sp-sm);border-radius: 0;text-transform:uppercase;font-weight:700;">'.$g.'</span>';
}
}

$avgRating = $game->reviews_count > 0 ? ($game->reviews_avg_rating ?? round($game->reviews->avg('rating'), 1)) : 0;
$ratingHtml = '<div style="display:flex;align-items:center;gap:var(--sp-xxs);margin-top:auto;"><i class="fas fa-star" style="color:#fbbf24;font-size:12px;"></i><span style="color:var(--c-body);font-size:12px;font-weight:700;">'.($avgRating ?: 'Baru').'</span></div>';

$formattedPrice = '';
if ($game->price == 0) {
    $formattedPrice = '<span style="color:var(--c-primary);font-size:16px;font-weight:800;">GRATIS</span>';
} else {
    if ($game->discount_percent > 0) {
        $formattedPrice = '<span style="color:var(--c-mute);font-size:13px;text-decoration:line-through;opacity:0.8;margin-right:var(--sp-sm);white-space:nowrap;">Rp ' . number_format($game->original_price, 0, ',', '.') . '</span>';
    }
    $formattedPrice .= '<span style="color:var(--c-ink);font-size:16px;font-weight:700;white-space:nowrap;">Rp ' . number_format($game->price, 0, ',', '.') . '</span>';
}

return '
<div class="slider-item" style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius: 0;overflow:hidden;border:1px solid var(--c-hairline);display:flex;flex-direction:column;position:relative;transition:border-color 0.2s;" onmouseenter="this.style.borderColor=\'var(--c-primary)\';" onmouseleave="this.style.borderColor=\'var(--c-hairline)\';">
    <a href="'.route('game.show', $game->slug).'" style="display:block;position:relative;overflow:hidden;aspect-ratio:460/215;">
        '.$statusHtml.$rankHtml.$badge.$img.'
    </a>
    <div style="padding:var(--sp-xl);flex:1;display:flex;flex-direction:column;">
        <div style="display:flex;flex-wrap:wrap;gap:var(--sp-xxs);margin-bottom:var(--sp-sm);">
            '.$genreHtml.'
        </div>
        <a href="'.route('game.show', $game->slug).'" style="text-decoration:none;margin-bottom:var(--sp-sm);">
            <h6 style="color:var(--c-ink);font-weight:700;font-size:17px;line-height:1.47;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;margin:0;">'.$game->title.'</h6>
        </a>
        '.$ratingHtml.'
        <div style="margin-top:var(--sp-lg);display:flex;align-items:center;flex-wrap:wrap;">
            '.$formattedPrice.'
        </div>
    </div>
</div>';
}
@endphp

{{-- ===================== SPECIAL OFFERS (DISCOUNTED) SLIDER ===================== --}}
@if($discountGames->count() > 0)
<section class="container" style="margin-bottom:var(--sp-section);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--sp-lg);">
        <div style="display:flex;align-items:center;gap:var(--sp-md);">
            <div style="width:4px;height:24px;background:var(--c-primary);border-radius: 0;box-shadow:0 0 10px rgba(118,185,0,0.5);"></div>
            <h2 style="font-size:1.5rem;font-weight:800;color:var(--c-ink);margin:0;">Penawaran Spesial</h2>
        </div>
        <div style="display:flex;gap:var(--sp-sm);">
            <button onclick="scrollSlider('discount-slider', -1)" class="slider-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="14 19 7 12 14 5"></polyline></svg>
            </button>
            <button onclick="scrollSlider('discount-slider', 1)" class="slider-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="10 19 17 12 10 5"></polyline></svg>
            </button>
        </div>
    </div>
    <div class="slider-wrapper">
        <div id="discount-slider" class="slider-container hide-scrollbar">
            @foreach($discountGames as $game)
            {!! renderGameCard($game, null, $ownedIds, $cartIds ?? [], $wishlistIds ?? []) !!}
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===================== TRENDING SLIDER ===================== --}}
@if($trendingGames->count() > 0)
<section class="container" style="margin-bottom:var(--sp-section);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--sp-lg);">
        <div style="display:flex;align-items:center;gap:var(--sp-md);">
            <div style="width:4px;height:24px;background:var(--c-primary);border-radius: 0;box-shadow:0 0 10px rgba(118,185,0,0.5);"></div>
            <h2 style="font-size:1.5rem;font-weight:800;color:var(--c-ink);margin:0;">Trending Sekarang</h2>
        </div>
        <div style="display:flex;gap:var(--sp-sm);">
            <button onclick="scrollSlider('trending-slider', -1)" class="slider-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="14 19 7 12 14 5"></polyline></svg>
            </button>
            <button onclick="scrollSlider('trending-slider', 1)" class="slider-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="10 19 17 12 10 5"></polyline></svg>
            </button>
        </div>
    </div>
    <div class="slider-wrapper">
        <div id="trending-slider" class="slider-container hide-scrollbar">
            @foreach($trendingGames as $idx => $game)
            {!! renderGameCard($game, $idx + 1, $ownedIds, $cartIds ?? [], $wishlistIds ?? []) !!}
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ===================== POPULAR SLIDER (HIGHEST RATED) ===================== --}}
@if($popularGames->count() > 0)
<section class="container" style="margin-bottom:var(--sp-section);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--sp-lg);">
        <div style="display:flex;align-items:center;gap:var(--sp-md);">
            <div style="width:4px;height:24px;background:var(--c-primary);border-radius: 0;box-shadow:0 0 10px rgba(118,185,0,0.5);"></div>
            <h2 style="font-size:1.5rem;font-weight:800;color:var(--c-ink);margin:0;">Game Populer</h2>
        </div>
        <div style="display:flex;gap:var(--sp-sm);">
            <button onclick="scrollSlider('popular-slider', -1)" class="slider-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="14 19 7 12 14 5"></polyline></svg>
            </button>
            <button onclick="scrollSlider('popular-slider', 1)" class="slider-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="10 19 17 12 10 5"></polyline></svg>
            </button>
        </div>
    </div>
    <div class="slider-wrapper">
        <div id="popular-slider" class="slider-container hide-scrollbar">
            @foreach($popularGames as $idx => $game)
            {!! renderGameCard($game, $idx + 1, $ownedIds, $cartIds ?? [], $wishlistIds ?? []) !!}
            @endforeach
        </div>
    </div>
</section>
@endif



{{-- ===================== FREE GAMES SLIDER ===================== --}}
@if($freeGames->count() > 0)
<section class="container" style="margin-bottom:var(--sp-section);">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:var(--sp-lg);">
        <div style="display:flex;align-items:center;gap:var(--sp-md);">
            <div style="width:4px;height:24px;background:var(--c-primary);border-radius: 0;box-shadow:0 0 10px rgba(118,185,0,0.5);"></div>
            <h2 style="font-size:1.5rem;font-weight:800;color:var(--c-ink);margin:0;">Game Gratis (Free to Play)</h2>
        </div>
        <div style="display:flex;gap:var(--sp-sm);">
            <button onclick="scrollSlider('free-slider', -1)" class="slider-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="14 19 7 12 14 5"></polyline></svg>
            </button>
            <button onclick="scrollSlider('free-slider', 1)" class="slider-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="10 19 17 12 10 5"></polyline></svg>
            </button>
        </div>
    </div>
    <div class="slider-wrapper">
        <div id="free-slider" class="slider-container hide-scrollbar">
            @foreach($freeGames as $game)
            {!! renderGameCard($game, null, $ownedIds, $cartIds ?? [], $wishlistIds ?? []) !!}
            @endforeach
        </div>
    </div>
</section>
@endif

@endsection

@push('styles')
<style>
    .hero-slide img:hover {
        transform: scale(1) !important;
    }

    /* Clip the slider — clip-path:inset(0) memotong ABSOLUT termasuk anak yang scrollable */
    .slider-wrapper {
        position: relative;
        clip-path: inset(0);
        /* potong apapun di luar wrapper */
        -webkit-clip-path: inset(0);
        /* Safari */
    }

    /* Lebar tepat agar pas 5 card: kurangi nilai gap (4 * 12px = 48px) */
    @media (min-width: 993px) {
        .slider-wrapper .slider-item {
            flex: 0 0 calc((100% - 48px) / 5) !important;
            max-width: calc((100% - 48px) / 5) !important;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
    function playHeroVideo(el) {
        const slide = el.closest('.hero-slide');
        if (!slide.classList.contains('slide-active')) return;
        
        const video = el.querySelector('.hero-video');
        const poster = el.querySelector('.hero-poster');
        if (video) {
            video.style.opacity = '1';
            if (poster) poster.style.opacity = '0';
            
            if (!video.dataset.initialized) {
                video.dataset.initialized = "true";
                const src = video.dataset.src;
                if (src.includes('.m3u8') && typeof Hls !== 'undefined' && Hls.isSupported()) {
                    const hls = new Hls({ startLevel: 1, capLevelToPlayerSize: true, autoStartLoad: true });
                    hls.loadSource(src);
                    hls.attachMedia(video);
                    video.hls = hls;
                    hls.on(Hls.Events.MANIFEST_PARSED, function() {
                        video.play().catch(e => console.error("HLS play failed:", e));
                    });
                } else {
                    video.src = src;
                    video.play().catch(e => console.error("Video play failed:", e));
                }
            } else {
                video.play().catch(e => console.error("Video play failed:", e));
            }
        }
    }
    
    function pauseHeroVideo(el) {
        const video = el.querySelector('.hero-video');
        const poster = el.querySelector('.hero-poster');
        if (video) {
            video.style.opacity = '0';
            if (poster) poster.style.opacity = '1';
            setTimeout(() => {
                if(video.style.opacity === '0') {
                    video.pause();
                }
            }, 500);
        }
    }
</script>
<script>
    // --- Hero Carousel 3D ---
    let currentHeroSlide = 0;
    const heroSlides = document.querySelectorAll('.hero-slide');
    const heroDots = document.querySelectorAll('.hero-dot');
    let heroInterval;

    function stopAllHeroVideos() {
        document.querySelectorAll('.hero-video').forEach(video => {
            video.style.opacity = '0';
            video.pause();
        });
        document.querySelectorAll('.hero-poster').forEach(poster => {
            poster.style.opacity = '1';
        });
    }

    function showHeroSlide(index) {
        if (heroSlides.length === 0) return;
        
        stopAllHeroVideos();

        // Hide all
        heroSlides.forEach((slide, i) => {
            slide.className = 'hero-slide slide-hidden';
            if (heroDots[i]) {
                heroDots[i].classList.remove('active');
                heroDots[i].style.background = 'rgba(255,255,255,0.2)';
                heroDots[i].style.width = '16px';
            }
        });

        // Calculate prev and next
        let prevIndex = index - 1;
        if (prevIndex < 0) prevIndex = heroSlides.length - 1;
        
        let nextIndex = index + 1;
        if (nextIndex >= heroSlides.length) nextIndex = 0;

        if (heroSlides.length > 2) {
            heroSlides[prevIndex].className = 'hero-slide slide-prev';
            heroSlides[nextIndex].className = 'hero-slide slide-next';
        } else if (heroSlides.length === 2) {
            heroSlides[nextIndex].className = 'hero-slide slide-next';
        }
        
        heroSlides[index].className = 'hero-slide slide-active';
        
        if (heroDots[index]) {
            heroDots[index].classList.add('active');
            heroDots[index].style.background = 'rgba(255,255,255,0.8)';
            heroDots[index].style.width = '24px';
        }

        currentHeroSlide = index;
    }

    function goToHeroSlide(index) {
        showHeroSlide(index);
        resetHeroInterval();
    }

    let isHovered = false;

    function resetHeroInterval() {
        clearInterval(heroInterval);
        if (!isHovered) {
            heroInterval = setInterval(() => showHeroSlide(currentHeroSlide + 1 >= heroSlides.length ? 0 : currentHeroSlide + 1), 5000);
        }
    }

    if (heroSlides.length > 0) {
        resetHeroInterval();
        
        const carouselContainer = document.getElementById('hero-carousel-container');
        if (carouselContainer) {
            carouselContainer.addEventListener('mouseenter', () => {
                isHovered = true;
                clearInterval(heroInterval);
            });
            carouselContainer.addEventListener('mouseleave', () => {
                isHovered = false;
                resetHeroInterval();
            });
        }
    }

    // --- Horizontal Slider Logic ---
    function scrollSlider(id, direction) {
        const slider = document.getElementById(id);
        if (!slider) return;

        // Get the width of one item + gap
        const item = slider.querySelector('.slider-item');
        if (!item) return;

        const scrollAmount = item.offsetWidth + 12; // 12px is var(--sp-md) gap

        // Disable snap temporarily for smooth button scroll
        slider.style.scrollSnapType = 'none';

        slider.scrollBy({
            left: direction * scrollAmount * 2, // Scroll 2 items at a time
            behavior: 'smooth'
        });

        // Re-enable snap after animation
        setTimeout(() => {
            slider.style.scrollSnapType = 'x mandatory';
        }, 500);
    }
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('mainSearchInput');
    const searchBox = document.getElementById('searchSuggestionsBox');
    const searchContent = document.getElementById('searchSuggestionsContent');
    let debounceTimer;

    function fetchSuggestions(query) {
        fetch(`{{ route('api.search.suggestions') }}?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                
                // Section title
                const sectionTitle = query ? 'Hasil Pencarian' : 'Pencarian Populer';
                html += `<div style="font-size:12px; font-weight:800; text-transform:uppercase; color:var(--c-mute); margin-bottom:var(--sp-md); letter-spacing:0.5px;">${sectionTitle}</div>`;
                
                // Games list
                if(data.games && data.games.length > 0) {
                    html += `<div style="display:flex; flex-direction:column; gap:var(--sp-sm); margin-bottom:var(--sp-xl);">`;
                    data.games.forEach(game => {
                        html += `
                        <a href="/game/${game.slug}" style="position:relative; display:flex; align-items:center; gap:var(--sp-md); background:var(--c-surface-soft); padding:var(--sp-sm); border-radius: 0; text-decoration:none; color:var(--c-ink); border:1px solid var(--c-hairline); transition:all 0.2s;" onmouseenter="this.style.borderColor='var(--c-primary)'; this.style.transform='translateX(4px)';" onmouseleave="this.style.borderColor='var(--c-hairline)'; this.style.transform='translateX(0)';">
                            <div style="position:relative; width:130px; height:73px; flex-shrink:0;">
                                <img src="${game.cover_image_url}" style="width:100%; height:100%; border-radius: 0; object-fit:cover; ${game.is_owned ? 'filter: grayscale(1) opacity(0.7);' : ''}">
                                ${game.is_owned ? `<div class="in-library-label in-library-label-sm" style="top: var(--sp-xxs); left: var(--sp-xxs);"><i class="fas fa-check label-icon"></i><span class="label-text">DI LIBRARY</span></div>` : ''}
                            </div>
                            <div style="flex:1; display:flex; flex-direction:column; min-width: 0;">
                                <div style="font-size:15px; font-weight:700; text-overflow:ellipsis; overflow:hidden; white-space:nowrap; margin-bottom:var(--sp-xs);">${game.title}</div>
                                <div style="font-size:13px; display:flex; align-items:center; gap:var(--sp-sm);">
                                    ${game.discount_percent > 0 ? `<span style="background:var(--c-primary); color:#000; padding:var(--sp-xxs) var(--sp-xs); border-radius: 0; font-weight:800;">-${game.discount_percent}%</span><span style="text-decoration:line-through; color:var(--c-mute);">Rp ${game.formatted_original}</span>` : ''}
                                    ${game.is_free ? `<span style="color:var(--c-primary); font-weight:700;">GRATIS</span>` : `<span style="color:var(--c-ink); font-weight:700;">Rp ${game.formatted_price}</span>`}
                                </div>
                            </div>
                        </a>
                        `;
                    });
                    html += `</div>`;
                } else {
                    html += `<div style="color:var(--c-mute); font-size:14px; margin-bottom:var(--sp-xl); text-align:center; padding:var(--sp-lg); border:1px dashed var(--c-hairline-strong); border-radius: 0;">Tidak ada hasil ditemukan.</div>`;
                }

                // Browse by / Top genres
                const genreTitle = query ? 'Cari Berdasarkan...' : 'Genre Teratas Anda';
                html += `<div style="font-size:12px; font-weight:800; text-transform:uppercase; color:var(--c-mute); margin-bottom:var(--sp-md); letter-spacing:0.5px;">${genreTitle}</div>`;
                html += `<div style="display:flex; flex-direction:column; gap:var(--sp-sm);">`;
                
                const colors = ['linear-gradient(135deg, rgba(118,185,0,0.2), rgba(0,0,0,0.8))', 'linear-gradient(135deg, rgba(0,185,255,0.2), rgba(0,0,0,0.8))', 'linear-gradient(135deg, rgba(255,50,50,0.2), rgba(0,0,0,0.8))', 'linear-gradient(135deg, rgba(185,0,255,0.2), rgba(0,0,0,0.8))'];
                
                data.genres.forEach((genre, idx) => {
                    const bg = colors[idx % colors.length];
                    html += `
                    <a href="/store?genres[]=${genre}" style="display:flex; align-items:center; justify-content:space-between; height:48px; background:${bg}; padding:0 var(--sp-lg); border-radius: 0; border:1px solid var(--c-hairline); text-decoration:none; color:var(--c-ink); transition:border-color 0.2s;" onmouseenter="this.style.borderColor='var(--c-primary)'" onmouseleave="this.style.borderColor='var(--c-hairline)'">
                        <div style="font-size:13px; font-weight:800; text-transform:uppercase; letter-spacing:1px;">${genre}</div>
                        <i class="fas fa-chevron-right" style="font-size:12px; color:var(--c-primary);"></i>
                    </a>
                    `;
                });
                html += `</div>`;
                
                searchContent.innerHTML = html;
                searchBox.style.display = 'block';
            });
    }

    if(searchInput) {
        searchInput.addEventListener('focus', function() {
            fetchSuggestions(this.value);
        });

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => {
                fetchSuggestions(this.value);
            }, 300);
        });
    }

    // Close when clicking outside
    document.addEventListener('click', function(e) {
        if (searchInput && searchBox) {
            if (!searchInput.contains(e.target) && !searchBox.contains(e.target)) {
                searchBox.style.display = 'none';
            }
        }
    });
});
</script>
@endpush