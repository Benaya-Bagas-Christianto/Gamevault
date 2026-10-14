@extends('layouts.app')

@section('title', $name . ' - Developer/Publisher')

@section('content')

{{-- ===================== SLIDER COMPONENT MACRO ===================== --}}
@php
if (!function_exists('renderGameCard')) {
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
        <div class="slider-item" style="background:var(--c-surface-soft);backdrop-filter:blur(12px);-webkit-backdrop-filter:blur(12px);border-radius: 0;overflow:hidden;border:1px solid var(--c-hairline);display:flex;flex-direction:column;position:relative;transition:border-color 0.2s;"  >
            <a href="'.route('game.show', $game->slug).'" style="display:block;position:relative;overflow:hidden;aspect-ratio:460/215;">
                '.$statusHtml.$rankHtml.$badge.$img.'
            </a>
            <div style="padding:var(--sp-xl);flex:1;display:flex;flex-direction:column;">
                <div style="display:flex;flex-wrap:wrap;gap:var(--sp-xxs);margin-bottom:var(--sp-sm);">
                    '.$genreHtml.'
                </div>
                <div style="color:var(--c-ink);font-weight:700;font-size:16px;margin-bottom:var(--sp-xs);line-height:1.2;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                    <a href="'.route('game.show', $game->slug).'" style="color:inherit;text-decoration:none;"  >'.$game->title.'</a>
                </div>
                <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-top:auto;gap:var(--sp-md);">
                    '.$ratingHtml.'
                    <div style="display:flex;align-items:center;">
                        '.$formattedPrice.'
                    </div>
                </div>
            </div>
        </div>
        ';
    }
}
@endphp

<style>
    .creator-banner {
        position: relative;
        background: linear-gradient(135deg, #16202d 0%, #1a2a3a 100%);
        padding: 40px 0;
        margin-bottom: 24px;
        overflow: hidden;
    }
    .creator-banner-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0.15;
        z-index: 0;
        filter: blur(10px);
    }
    .creator-banner-content {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 24px;
    }
    .creator-logo {
        width: 120px;
        height: 120px;
        background: #000;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid var(--c-hairline);
    }
    .creator-logo i {
        font-size: 3rem;
        color: var(--c-mute);
    }
    .creator-title {
        font-size: 2.5rem;
        font-weight: 800;
        color: #fff;
        margin: 0 0 8px 0;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .creator-subtitle {
        color: var(--c-primary);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 1px;
    }
    .creator-nav {
        background: rgba(0,0,0,0.5);
        border-top: 1px solid rgba(255,255,255,0.1);
        border-bottom: 1px solid rgba(255,255,255,0.1);
        display: flex;
        align-items: center;
        padding: 0;
        margin-bottom: 32px;
    }
    .creator-nav-item {
        padding: 16px 24px;
        color: var(--c-mute);
        text-decoration: none;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 1px;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }
    .creator-nav-item:hover, .creator-nav-item.active {
        color: #fff;
        border-bottom-color: var(--c-primary);
        background: rgba(255,255,255,0.05);
    }
</style>

<div class="creator-banner">
    @if($featuredGames->first() && $featuredGames->first()->cover_image)
        <img src="{{ $featuredGames->first()->cover_image_url }}" class="creator-banner-bg" alt="">
    @endif
    <div class="container creator-banner-content">
        <div class="creator-logo">
            <i class="fas fa-building"></i>
        </div>
        <div>
            <h1 class="creator-title">{{ $name }}</h1>
            <div class="creator-subtitle">
                Official {{ $isPublisher ? 'Publisher' : 'Developer' }} Page
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="creator-nav">
        <a href="#" class="creator-nav-item active">Featured</a>
        <a href="#" class="creator-nav-item">All Games ({{ $games->count() }})</a>
    </div>

    @if($games->isEmpty())
        <div style="padding:40px; text-align:center; background:var(--c-surface-soft); border:1px solid var(--c-hairline);">
            <div style="font-size:2rem; color:var(--c-mute); margin-bottom:16px;"><i class="fas fa-ghost"></i></div>
            <h3 style="color:var(--c-ink); font-weight:700;">No games found</h3>
            <p style="color:var(--c-mute);">We couldn't find any games by this creator.</p>
        </div>
    @else
        
        @if($featuredGames->count() > 0)
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--c-ink); margin-bottom: 20px; text-transform: uppercase;">
                    Featured Releases
                </h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:24px;">
                    @foreach($featuredGames as $idx => $game)
                        {!! renderGameCard($game, null, $ownedIds, $cartIds, $wishlistIds) !!}
                    @endforeach
                </div>
            </div>
        @endif

        @if($otherGames->count() > 0)
            <div style="margin-bottom: 40px;">
                <h3 style="font-size: 1.2rem; font-weight: 700; color: var(--c-ink); margin-bottom: 20px; text-transform: uppercase;">
                    More from {{ $name }}
                </h3>
                <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:24px;">
                    @foreach($otherGames as $idx => $game)
                        {!! renderGameCard($game, null, $ownedIds, $cartIds, $wishlistIds) !!}
                    @endforeach
                </div>
            </div>
        @endif

    @endif
</div>

@endsection
