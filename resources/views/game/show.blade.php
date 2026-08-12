@extends('layouts.app')

@section('title', $game->title . ' — GameVault')

@section('content')

{{-- Breadcrumb --}}
<div style="background:var(--c-surface-dark);border-bottom:1px solid var(--c-hairline);padding:12px 0;">
    <div class="container">
        <nav style="display:flex;align-items:center;gap:8px;font-size:0.85rem;color:var(--c-mute);">
            <a href="{{ url('/') }}" style="color:var(--c-primary);text-decoration:none;">Home</a>
            <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
            <a href="{{ url('/') }}" style="color:var(--c-primary);text-decoration:none;">Toko</a>
            <i class="fas fa-chevron-right" style="font-size:0.65rem;"></i>
            <span style="color:var(--c-body);">{{ $game->title }}</span>
        </nav>
    </div>
</div>

{{-- ==================== GAME DETAIL ==================== --}}
<div class="container" style="padding-top:48px;padding-bottom:64px;">

    <style>
        .media-player-container {
            position: relative;
            background: #000;
            border-radius: 0;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.5);
            aspect-ratio: 16/9;
            margin-bottom: 16px;
            border: 1px solid var(--c-hairline);
        }
        .media-player-container video, .media-player-container img.main-media {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: none;
        }
        .media-player-container .active {
            display: block !important;
        }
        .media-thumbnails {
            display: flex;
            gap: 8px;
            overflow-x: auto;
            padding-bottom: 8px;
        }
        .media-thumbnails::-webkit-scrollbar {
            display: none;
        }
        .media-thumb {
            flex: 0 0 120px;
            aspect-ratio: 16/9;
            border-radius: 0;
            overflow: hidden;
            cursor: pointer;
            position: relative;
            border: 2px solid transparent;
            transition: all 0.2s;
        }
        .media-thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .media-thumb.active, .media-thumb:hover {
            border-color: var(--c-primary);
        }
        .media-thumb .play-icon {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
            color: rgba(255,255,255,0.8);
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
        }
        
        .game-header-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 32px;
            margin-bottom: 48px;
        }
        .game-header-grid > div {
            min-width: 0;
        }
        @media (max-width: 1100px) {
            .game-header-grid {
                grid-template-columns: 1fr 340px;
            }
        }
        @media (max-width: 992px) {
            .game-header-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    {{-- Main Detail Section --}}
    <div class="game-header-grid">

        {{-- Left: Media Gallery --}}
        <div>
            @php
                $galleries = isset($game->galleries) ? $game->galleries->sortBy(function($media) {
                    return in_array($media->type, ['video', 'external_mp4', 'youtube']) ? 1 : 2;
                })->values() : collect([]);
            @endphp
            @if($galleries->count() > 0)
                <div class="media-player-container" id="main-media-player">
                    @foreach($galleries as $idx => $media)
                        @if($media->type == 'video' || $media->type == 'external_mp4')
                            <video class="{{ $idx == 0 ? 'active' : '' }}" src="{{ filter_var($media->path, FILTER_VALIDATE_URL) ? $media->path : Storage::url($media->path) }}" controls {{ $idx == 0 ? 'autoplay muted' : '' }} data-index="{{ $idx }}"></video>
                        @elseif($media->type == 'youtube')
                            <iframe class="{{ $idx == 0 ? 'active' : '' }}" data-index="{{ $idx }}" src="{{ str_replace('watch?v=', 'embed/', $media->path) }}" frameborder="0" allowfullscreen style="width:100%;height:100%;display:none;"></iframe>
                        @else
                            <img class="main-media {{ $idx == 0 ? 'active' : '' }}" src="{{ filter_var($media->path, FILTER_VALIDATE_URL) ? $media->path : Storage::url($media->path) }}" data-index="{{ $idx }}">
                        @endif
                    @endforeach
                </div>
                <div class="thumbnails-wrapper" style="margin-top:8px;">
                    <div class="media-thumbnails" id="mediaThumbnailsContainer" style="scroll-behavior: smooth; display:flex; gap:8px; overflow-x:auto;">
                        @foreach($galleries as $idx => $media)
                            <div class="media-thumb {{ $idx == 0 ? 'active' : '' }}" onclick="switchMedia(<?php echo $idx; ?>)">
                                @if($media->type == 'video' || $media->type == 'external_mp4' || $media->type == 'youtube')
                                    <i class="fas fa-play play-icon"></i>
                                    <div style="width:100%; height:100%; background:#111; overflow:hidden;">
                                        @if($media->thumbnail)
                                            <img src="{{ filter_var($media->thumbnail, FILTER_VALIDATE_URL) ? $media->thumbnail : Storage::url($media->thumbnail) }}" style="width:100%; height:100%; object-fit:cover;">
                                        @else
                                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#555;"><i class="fas fa-video fa-2x"></i></div>
                                        @endif
                                    </div>
                                @else
                                    <img src="{{ filter_var($media->path, FILTER_VALIDATE_URL) ? $media->path : Storage::url($media->path) }}">
                                @endif
                            </div>
                        @endforeach
                    </div>

                    <div class="thumbnail-slider-controls" style="display:flex; align-items:stretch; height: 18px; margin-top:8px; gap:4px;">
                        <button class="thumb-nav-btn prev" onclick="navigateMedia(-1)" style="flex-shrink:0; width:36px; background:var(--c-surface-soft); color:var(--c-mute); border:none; border-radius:0; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; font-size:10px;"><i class="fas fa-chevron-left"></i></button>
                        
                        <div class="slider-track" id="customScrollTrack" style="flex-grow:1; background:var(--c-surface-soft); position:relative; overflow:hidden; border-radius:0; cursor:pointer;">
                            <div class="slider-thumb" id="customScrollThumb" style="position:absolute; left:0; top:0; height:100%; width:15%; background:rgba(255,255,255,0.2); border-radius:0; transition:background 0.2s;"></div>
                        </div>
                        
                        <button class="thumb-nav-btn next" onclick="navigateMedia(1)" style="flex-shrink:0; width:36px; background:var(--c-surface-soft); color:var(--c-mute); border:none; border-radius:0; cursor:pointer; transition:all 0.2s; display:flex; align-items:center; justify-content:center; font-size:10px;"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                
                <style>
                    .thumb-nav-btn:hover {
                        background: var(--c-primary) !important;
                        color: #000 !important;
                    }
                    .slider-thumb:hover, .slider-track:hover .slider-thumb {
                        background: rgba(118,185,0,0.5) !important;
                    }
                </style>
                <script>
                    let currentMediaIndex = 0;
                    const totalMedia = {{ $galleries->count() }};

                    function navigateMedia(direction) {
                        let newIndex = currentMediaIndex + direction;
                        if (newIndex < 0) newIndex = totalMedia - 1;
                        if (newIndex >= totalMedia) newIndex = 0;
                        
                        switchMedia(newIndex);
                    }
                    
                    document.addEventListener('DOMContentLoaded', function() {
                        const container = document.getElementById('mediaThumbnailsContainer');
                        const thumb = document.getElementById('customScrollThumb');
                        const track = document.getElementById('customScrollTrack');
                        
                        function updateThumb() {
                            if (!container || !thumb || !track) return;
                            const scrollRatio = container.scrollLeft / (container.scrollWidth - container.clientWidth || 1);
                            
                            // Calculate thumb width dynamically based on visible area ratio
                            const visibleRatio = Math.min(1, container.clientWidth / (container.scrollWidth || 1));
                            let thumbWidthPct = visibleRatio * 100;
                            if(thumbWidthPct < 10) thumbWidthPct = 10; // minimum thumb width 10%
                            
                            thumb.style.width = thumbWidthPct + '%';
                            
                            // Max left position is 100% - thumbWidthPct
                            const maxLeft = 100 - thumbWidthPct;
                            thumb.style.left = (scrollRatio * maxLeft) + '%';
                        }
                        
                        if (container) {
                            container.addEventListener('scroll', updateThumb);
                            window.addEventListener('resize', updateThumb);
                            // Initial call
                            setTimeout(updateThumb, 100);
                        }
                    });
                    
                    function switchMedia(index) {
                        currentMediaIndex = index;
                        const container = document.getElementById('main-media-player');
                        const items = container.querySelectorAll('video, img.main-media');
                        const thumbs = document.querySelectorAll('.media-thumb');
                        const thumbsContainer = document.getElementById('mediaThumbnailsContainer');
                        
                        items.forEach(item => {
                            item.classList.remove('active');
                            if(item.tagName === 'VIDEO') item.pause();
                        });
                        thumbs.forEach(thumb => thumb.classList.remove('active'));
                        
                        const target = container.querySelector('[data-index="'+index+'"]');
                        if (target) {
                            target.classList.add('active');
                            if(target.tagName === 'VIDEO') {
                                target.muted = false; // unmute when manually clicked
                                target.play();
                            }
                        }
                        
                        thumbs[index].classList.add('active');
                        
                        // Scroll thumbnail into view
                        if (thumbsContainer && thumbs[index]) {
                            const containerRect = thumbsContainer.getBoundingClientRect();
                            const thumbRect = thumbs[index].getBoundingClientRect();
                            
                            if (thumbRect.left < containerRect.left) {
                                thumbsContainer.scrollBy({ left: thumbRect.left - containerRect.left - 10, behavior: 'smooth' });
                            } else if (thumbRect.right > containerRect.right) {
                                thumbsContainer.scrollBy({ left: thumbRect.right - containerRect.right + 10, behavior: 'smooth' });
                            }
                        }
                    }
                </script>
            @else
                <div class="media-player-container">
                    @if($game->cover_image)
                        <img class="main-media active" src="{{ $game->cover_image_url }}">
                    @else
                        <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#111;">
                            <i class="fas fa-gamepad" style="font-size:4rem;color:#333;"></i>
                        </div>
                    @endif
                </div>
            @endif

            <script src="https://cdn.jsdelivr.net/npm/hls.js@1"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const videos = document.querySelectorAll('video');
                    videos.forEach(video => {
                        const src = video.getAttribute('src');
                        if (src && src.includes('.m3u8')) {
                            if (Hls.isSupported()) {
                                const hls = new Hls();
                                hls.loadSource(src);
                                hls.attachMedia(video);
                            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                                // Fallback for Safari which supports HLS natively
                                video.src = src;
                            }
                        }
                    });
                });
            </script>

            {{-- Wishlist Button --}}
            <div style="margin-top:16px;">
                @if($isOwned)
                    <button disabled style="width:100%;padding:12px 24px;border:1px solid var(--c-hairline);background:var(--c-surface-soft);color:var(--c-mute);border-radius: 0;cursor:not-allowed;display:flex;align-items:center;justify-content:center;gap:8px;font-weight:600;opacity:0.6;">
                        <i class="fas fa-check-circle"></i>
                        Sudah Dimiliki
                    </button>
                @else
                    <button id="add-to-wishlist-btn" data-game-slug="{{ $game->slug }}"
                        style="<?php echo 'width:100%;padding:12px 24px;border:1px solid ' . ($isInWishlist ? 'var(--c-primary)' : 'var(--c-hairline)') . ';background:' . ($isInWishlist ? 'rgba(118,185,0,0.1)' : 'var(--c-surface-soft)') . ';color:' . ($isInWishlist ? 'var(--c-primary)' : 'var(--c-body)') . ';border-radius: 0;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:8px;font-weight:600;'; ?>">
                        <i class="{{ $isInWishlist ? 'fas' : 'far' }} fa-heart"></i>
                        {{ $isInWishlist ? 'Tersimpan di Wishlist' : 'Tambah ke Wishlist' }}
                    </button>
                @endif
            </div>
        </div>

        {{-- Right: Game Info & Cover Image --}}
        <div style="display:flex;flex-direction:column;height:100%;">
            <div style="border-radius: 0;overflow:hidden;box-shadow:0 10px 30px rgba(0,0,0,0.5); margin-bottom: 24px; flex-shrink:0;">
                @if($game->cover_image)
                    <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" style="width:100%;display:block;aspect-ratio:16/9;object-fit:cover;background:#111;">
                @else
                    <div style="width:100%;aspect-ratio:16/9;background:linear-gradient(135deg,#1a1a1a,#0d2200);display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;">
                        <i class="fas fa-gamepad" style="font-size:3rem;color:rgba(118,185,0,0.5);"></i>
                    </div>
                @endif
            </div>

            {{-- Title --}}
            <h1 style="font-size:clamp(1.25rem,2.5vw,1.75rem);font-weight:800;color:var(--c-ink);line-height:1.2;margin-bottom:12px;word-break:break-word;flex-shrink:0;">{{ $game->title }}</h1>

            {{-- Rating --}}
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;flex-wrap:wrap;flex-shrink:0;">
                <div style="display:flex;gap:2px;">
                    @for($s=1;$s<=5;$s++)
                        @if($s <= floor($avgRating))
                            <i class="fas fa-star" style="color:#f59e0b;font-size:0.9rem;"></i>
                        @elseif($s - $avgRating < 1 && $s - $avgRating > 0)
                            <i class="fas fa-star-half-alt" style="color:#f59e0b;font-size:0.9rem;"></i>
                        @else
                            <i class="far fa-star" style="color:var(--c-hairline-strong);font-size:0.9rem;"></i>
                        @endif
                    @endfor
                </div>
                <span style="color:var(--c-ink);font-weight:700;font-size:0.9rem;">{{ number_format($avgRating, 1) }}</span>
                <span style="color:var(--c-mute);font-size:0.8rem;">({{ $reviewsCount }} ulasan)</span>
                @if($isOwned)
                    <span style="background:rgba(118,185,0,0.12);color:var(--c-primary);border:1px solid rgba(118,185,0,0.3);padding:2px 8px;border-radius: 0;font-size:0.7rem;font-weight:700;">
                        <i class="fas fa-check-circle me-1"></i>DIMILIKI
                    </span>
                @endif
            </div>

            {{-- Short Description --}}
            @if(!empty($game->short_description))
                <p style="color:var(--c-body);font-size:0.85rem;line-height:1.6;margin-bottom:16px;flex-shrink:0;">
                    {!! html_entity_decode($game->short_description) !!}
                </p>
            @elseif(!empty($game->description))
                <p style="color:var(--c-body);font-size:0.85rem;line-height:1.6;margin-bottom:16px;flex-shrink:0;">
                    {{ \Illuminate\Support\Str::limit(strip_tags(html_entity_decode($game->description)), 250) }}
                </p>
            @endif

            {{-- Genre Badges --}}
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;flex-shrink:0;">
                @foreach($game->genre ?? [] as $genre)
                    <a href="{{ url('/') }}?genres[]={{ $genre }}" style="text-decoration:none;"><span class="badge" style="font-size:0.7rem;padding:4px 8px;">{{ $genre }}</span></a>
                @endforeach
            </div>

            {{-- Dev/Publisher Info --}}
            <div style="flex-shrink:0;">
                <div style="display:grid; grid-template-columns: 110px 1fr; gap: 8px 12px; font-size: 0.75rem; align-items: start;">
                    @if(isset($game->release_date))
                    <div style="color:#556772; text-transform:uppercase;">Release Date:</div>
                    <div style="color:#8f98a0;">{{ \Carbon\Carbon::parse($game->release_date)->format('d M, Y') }}</div>
                    @endif
                    
                    @if(isset($game->developer))
                    <div style="color:#556772; text-transform:uppercase;">Developer:</div>
                    @php
                        $devs = explode(', ', $game->developer);
                        $hasMoreDevs = count($devs) > 2;
                        $shortDevs = implode(', ', array_slice($devs, 0, 2));
                        $moreDevs = $hasMoreDevs ? ', ' . implode(', ', array_slice($devs, 2)) : '';
                    @endphp
                    <div>
                        <span id="dev-short">
                            <a href="{{ route('creator.show', $game->developer) }}" style="color:var(--c-primary); text-decoration:none;" onmouseenter="this.style.textDecoration='underline'" onmouseleave="this.style.textDecoration='none'">{{ $shortDevs }}</a>
                            @if($hasMoreDevs)
                            <span id="dev-ellipsis">...</span> <a href="javascript:void(0)" id="dev-plus" onclick="document.getElementById('dev-more').style.display='inline'; document.getElementById('dev-ellipsis').style.display='none'; this.style.display='none';" style="color:var(--c-mute); background:rgba(255,255,255,0.1); padding:0 4px; border-radius:2px; text-decoration:none; display:inline-block; line-height:1.2;">+</a>
                            @endif
                        </span>
                        @if($hasMoreDevs)
                        <span id="dev-more" style="display:none;">
                            <a href="{{ route('creator.show', $game->developer) }}" style="color:var(--c-primary); text-decoration:none;" onmouseenter="this.style.textDecoration='underline'" onmouseleave="this.style.textDecoration='none'">{{ $moreDevs }}</a>
                            <a href="javascript:void(0)" onclick="document.getElementById('dev-more').style.display='none'; document.getElementById('dev-ellipsis').style.display='inline'; document.getElementById('dev-plus').style.display='inline-block';" style="color:var(--c-mute); background:rgba(255,255,255,0.1); padding:0 4px; border-radius:2px; text-decoration:none; display:inline-block; line-height:1.2; margin-left:4px;">-</a>
                        </span>
                        @endif
                    </div>
                    @endif
                    
                    @if(isset($game->publisher))
                    <div style="color:#556772; text-transform:uppercase;">Publisher:</div>
                    @php
                        $pubs = explode(', ', $game->publisher);
                        $hasMorePubs = count($pubs) > 2;
                        $shortPubs = implode(', ', array_slice($pubs, 0, 2));
                        $morePubs = $hasMorePubs ? ', ' . implode(', ', array_slice($pubs, 2)) : '';
                    @endphp
                    <div>
                        <span id="pub-short">
                            <a href="{{ route('creator.show', $game->publisher) }}" style="color:var(--c-primary); text-decoration:none;" onmouseenter="this.style.textDecoration='underline'" onmouseleave="this.style.textDecoration='none'">{{ $shortPubs }}</a>
                            @if($hasMorePubs)
                            <span id="pub-ellipsis">...</span> <a href="javascript:void(0)" id="pub-plus" onclick="document.getElementById('pub-more').style.display='inline'; document.getElementById('pub-ellipsis').style.display='none'; this.style.display='none';" style="color:var(--c-mute); background:rgba(255,255,255,0.1); padding:0 4px; border-radius:2px; text-decoration:none; display:inline-block; line-height:1.2;">+</a>
                            @endif
                        </span>
                        @if($hasMorePubs)
                        <span id="pub-more" style="display:none;">
                            <a href="{{ route('creator.show', $game->publisher) }}" style="color:var(--c-primary); text-decoration:none;" onmouseenter="this.style.textDecoration='underline'" onmouseleave="this.style.textDecoration='none'">{{ $morePubs }}</a>
                            <a href="javascript:void(0)" onclick="document.getElementById('pub-more').style.display='none'; document.getElementById('pub-ellipsis').style.display='inline'; document.getElementById('pub-plus').style.display='inline-block';" style="color:var(--c-mute); background:rgba(255,255,255,0.1); padding:0 4px; border-radius:2px; text-decoration:none; display:inline-block; line-height:1.2; margin-left:4px;">-</a>
                        </span>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    {{-- ================= GAMEVAULT-STYLE BUY SECTIONS ================= --}}
    <div style="margin-bottom: 40px;">
        
        @if($isOwned)
        {{-- IN LIBRARY Banner --}}
        <div style="background: rgba(118, 185, 0, 0.1); border: 1px solid var(--c-primary); border-radius: 0; padding: 16px 20px; display: flex; align-items: center; gap: 16px; margin-bottom: 24px;">
            <div style="background: var(--c-primary); color: #000; font-size: 0.75rem; font-weight: 800; padding: 4px 10px; border-radius: 0; display: flex; align-items: center; gap: 6px;">
                <i class="fas fa-bars"></i> DI LIBRARY
            </div>
            <div style="color: var(--c-primary); font-size: 1.1rem; font-weight: 600;">
                {{ $game->title }} sudah ada di library kamu.
            </div>
            <div style="margin-left: auto;">
                <a href="{{ route('library.index') }}" class="btn-primary" style="padding: 8px 16px; font-weight: 600; border-radius: 0; text-decoration: none; display: inline-block;">Lihat di Library</a>
            </div>
        </div>
        @endif
        @if(empty($game->packages) || count($game->packages) == 0 || $game->price == 0)
            {{-- Base game purchase/claim box --}}
            <div class="card-dark" style="border: 1px solid var(--c-primary); border-radius: 0; padding: 16px 16px 12px 16px; margin-bottom: 36px; position: relative;">
                <h3 style="color:var(--c-ink); font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; word-break:break-word;">
                    @if($game->price == 0) Mainkan @else Beli @endif {{ $game->title }}
                </h3>
                
                @if($game->original_price > $game->price || $game->discount_percent > 0)
                <div style="color: var(--c-primary); font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">PENAWARAN SPESIAL! Promo terbatas</div>
                @endif
                
                <div style="display: flex; justify-content: flex-end; align-items: flex-end; position: absolute; bottom: -17px; right: 16px; z-index: 10;">
                    <div style="display: flex; align-items: center; background: #000; border-radius: 2px; padding: 2px; height: 38px; box-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                        @if($game->price == 0)
                             <div style="color: #c6d4df; font-size: 0.95rem; font-weight: 600; padding: 0 16px;">Free To Play</div>
                             @if($isOwned)
                                <button style="height: 100%; border-radius: 2px; padding: 0 16px; font-weight: 500; border:none; cursor:not-allowed; background:#333; color:#aaa;">Sudah Dimiliki</button>
                             @else
                                <button onclick="claimFreeGame('{{ $game->slug }}')" style="height: 100%; border-radius: 2px; padding: 0 16px; background: linear-gradient(to right, #75b022 5%, #588a1b 95%); color: #000; font-weight: 700; border:none; cursor:pointer;" onmouseenter="this.style.background='linear-gradient(to right, #8ed629 5%, #6aa621 95%)'; this.style.color='#000';" onmouseleave="this.style.background='linear-gradient(to right, #75b022 5%, #588a1b 95%)'; this.style.color='#000';">
                                    Tambah ke Library
                                </button>
                             @endif
                        @else
                            @if($game->original_price > 0 && $game->original_price > $game->price)
                                @php
                                    $usedDiscount = $game->discount_percent > 0 ? $game->discount_percent : round((($game->original_price - $game->price) / $game->original_price) * 100);
                                @endphp
                                <div style="background: var(--c-primary); color: #000; font-size: 1.4rem; font-weight: 800; padding: 0 8px; line-height: 34px; height: 34px;">-{{ $usedDiscount }}%</div>
                                <div style="display: flex; flex-direction: column; align-items: flex-end; justify-content: center; padding: 0 10px; background: #000; height: 34px;">
                                    <div style="color: var(--c-mute); text-decoration: line-through; font-size: 0.75rem; line-height: 1.2;">Rp {{ number_format($game->original_price, 0, ',', '.') }}</div>
                                    <div style="color: var(--c-primary); font-size: 0.95rem; font-weight: 600; line-height: 1.2;">Rp {{ number_format($game->price, 0, ',', '.') }}</div>
                                </div>
                            @else
                                <div style="color:var(--c-ink); font-size: 1.1rem; font-weight: 700; padding: 0 16px; background: #000; height: 34px; line-height: 34px;">Rp {{ number_format($game->price, 0, ',', '.') }}</div>
                            @endif
                            @if($isOwned)
                                <button style="height: 34px; border-radius: 0; padding: 0 16px; font-weight: 700; border:none; cursor:not-allowed; background:#333; color:#aaa; font-size: 0.95rem;">Sudah Dimiliki</button>
                            @else
                                <button class="cart-btn btn-primary" data-game-slug="{{ $game->slug }}" style="height: 34px; border-radius: 0; padding: 0 16px; color: #000; font-weight: 700; border:none; cursor:pointer; font-size: 0.95rem;">
                                    @if($isInCart) Di Keranjang @else Tambah ke Keranjang @endif
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        @endif

        @if($game->packages && is_array($game->packages) && count($game->packages) > 0)
            @foreach($game->packages as $package)
            <div class="card-dark" style="border: 1px solid var(--c-primary); border-radius: 0; padding: 16px 16px 12px 16px; margin-bottom: 36px; position: relative;">
                <h3 style="color:var(--c-ink); font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; word-break:break-word;">
                    Beli {{ $package['name'] }}
                </h3>
                
                @if($package['discount_percent'] > 0)
                <div style="color: var(--c-primary); font-size: 0.85rem; font-weight: 600; margin-bottom: 4px;">PENAWARAN SPESIAL! Promo terbatas</div>
                @endif
                
                @if(strpos(strtolower($package['name']), 'edition') !== false || strpos(strtolower($package['name']), 'bundle') !== false)
                <div style="color: var(--c-mute); font-size: 0.85rem; margin-bottom: 4px;">
                    <span style="color: var(--c-primary); font-weight: 600;">Includes:</span> {{ $package['name'] }}
                </div>
                @endif
                
                <div style="display: flex; justify-content: flex-end; align-items: flex-end; position: absolute; bottom: -17px; right: 16px; z-index: 10;">
                    <div style="display: flex; align-items: center; background: #000; border-radius: 2px; padding: 2px; height: 38px; box-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                        @if($package['price'] == 0)
                             <div style="color: #c6d4df; font-size: 0.95rem; font-weight: 600; padding: 0 16px;">Free To Play</div>
                             <button onclick="claimFreeGame('{{ $game->slug }}')" style="height: 100%; border-radius: 2px; padding: 0 16px; background: linear-gradient(to right, #75b022 5%, #588a1b 95%); color: #000; font-weight: 700; border:none; cursor:pointer;" onmouseenter="this.style.background='linear-gradient(to right, #8ed629 5%, #6aa621 95%)'; this.style.color='#000';" onmouseleave="this.style.background='linear-gradient(to right, #75b022 5%, #588a1b 95%)'; this.style.color='#000';">
                                 Tambah ke Library
                             </button>
                        @else
                            @if($package['discount_percent'] > 0)
                                <div style="background: var(--c-primary); color: #000; font-size: 1.4rem; font-weight: 800; padding: 0 8px; line-height: 34px; height: 34px;">-{{ $package['discount_percent'] }}%</div>
                                <div style="display: flex; flex-direction: column; align-items: flex-end; justify-content: center; padding: 0 10px; background: #000; height: 34px;">
                                    <div style="color: var(--c-mute); text-decoration: line-through; font-size: 0.75rem; line-height: 1.2;">Rp {{ number_format($package['original_price'], 0, ',', '.') }}</div>
                                    <div style="color: var(--c-primary); font-size: 0.95rem; font-weight: 600; line-height: 1.2;">Rp {{ number_format($package['price'], 0, ',', '.') }}</div>
                                </div>
                            @else
                                <div style="color:var(--c-ink); font-size: 1.1rem; font-weight: 700; padding: 0 16px; background: #000; height: 34px; line-height: 34px;">Rp {{ number_format($package['price'], 0, ',', '.') }}</div>
                            @endif
                            <button class="cart-btn btn-primary" data-game-slug="{{ $game->slug }}" style="height: 34px; border-radius: 0; padding: 0 16px; color: #000; font-weight: 700; border:none; cursor:pointer; font-size: 0.95rem;">
                                @if($isInCart)
                                    Di Keranjang
                                @else
                                    Tambah ke Keranjang
                                @endif
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        {{-- Bundle Buy Box (If has DLC) --}}
        @if($game->dlcs && $game->dlcs->count() > 0)
        @php
            $bundleOriginalPrice = $game->price + $game->dlcs->sum('price');
            $bundleDiscountPrice = $bundleOriginalPrice * 0.85; // 15% discount
        @endphp
        <div class="card-dark" style="border: 1px solid var(--c-primary); border-radius: 0; padding: 16px 16px 12px 16px; margin-bottom: 36px; position: relative;">
            <h3 style="color:var(--c-ink); font-size: 1.35rem; font-weight: 700; margin: 0 0 4px 0; word-break:break-word;">
                Beli {{ $game->title }} BUNDLE <span style="font-size: 0.85rem; color: var(--c-mute); font-weight: 600;">(?)</span>
            </h3>
            
            <div style="color: var(--c-mute); font-size: 0.85rem; margin-bottom: 4px;">
                Beli bundle ini untuk menghemat 15% dari {{ $game->dlcs->count() + 1 }} item!
            </div>
            
            <div style="display: flex; justify-content: flex-end; align-items: flex-end; position: absolute; bottom: -17px; right: 16px; z-index: 10;">
                <button style="height: 34px; padding: 0 16px; background: #000; color: var(--c-primary); font-weight: 600; border: 1px solid var(--c-primary); border-radius: 2px; margin-right: 2px; cursor: pointer; align-self: center; margin-bottom: 2px;" onmouseenter="this.style.background='var(--c-primary)'; this.style.color='#000';" onmouseleave="this.style.background='#000'; this.style.color='var(--c-primary)';">Bundle info</button>
                <div style="display: flex; align-items: center; background: #000; border-radius: 2px; padding: 2px; height: 38px; box-shadow: 0 2px 10px rgba(0,0,0,0.5);">
                    <div style="color: var(--c-mute); font-size: 1.1rem; font-weight: 600; padding: 0 8px; line-height: 34px; height: 34px;">-15%</div>
                    <div style="background: var(--c-primary); color: #000; font-size: 1.4rem; font-weight: 800; padding: 0 8px; line-height: 34px; height: 34px;">-85%</div>
                    <div style="display: flex; flex-direction: column; align-items: flex-end; justify-content: center; padding: 0 10px; background: #000; height: 34px;">
                        <div style="color: var(--c-mute); text-decoration: line-through; font-size: 0.75rem; line-height: 1.2;">Rp {{ number_format($bundleOriginalPrice, 0, ',', '.') }}</div>
                        <div style="color: var(--c-primary); font-size: 0.95rem; font-weight: 600; line-height: 1.2;">Rp {{ number_format($bundleDiscountPrice, 0, ',', '.') }}</div>
                    </div>
                    <button class="btn-primary" onclick="addAllToCart('{{ $game->slug }}')" style="height: 34px; border-radius: 0; padding: 0 16px; color: #000; font-weight: 700; border:none; cursor:pointer; font-size: 0.95rem;">
                        Tambah ke Keranjang
                    </button>
                </div>
            </div>
        </div>

        {{-- Content For This Game --}}
        <div class="card-dark" style="margin-top: 32px; border: 1px solid var(--c-primary); border-radius: 0; overflow: hidden; padding: 24px; margin-bottom:32px;">
            <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 16px;">
                <h2 style="color:var(--c-ink); font-size: 1.2rem; font-weight: 700; margin: 0; text-transform: uppercase;">Content For This Game</h2>
                <a href="{{ route('game.dlc', $game->slug) }}" style="color: var(--c-ink); text-decoration: none; font-size: 0.85rem; background: var(--c-surface-soft); padding: 4px 12px; border-radius: 0; border: 1px solid var(--c-hairline);">Browse all ({{ $game->dlcs->count() }})</a>
            </div>
            
            <div style="background: var(--c-surface-dark); border-left: 4px solid var(--c-primary); border-top: 1px solid var(--c-hairline); border-right: 1px solid var(--c-hairline);">
                @foreach($game->dlcs->take(5) as $idx => $dlc)
                    <div style="display: flex; align-items: center; justify-content: space-between; padding: 8px 16px; background: {{ $idx % 2 == 0 ? 'var(--c-surface-soft)' : 'transparent' }}; border-bottom: 1px solid var(--c-hairline); transition: background 0.2s;" onmouseenter="this.style.background='rgba(118,185,0,0.1)';" onmouseleave="this.style.background='{{ $idx % 2 == 0 ? 'var(--c-surface-soft)' : 'transparent' }}';">
                        <div style="color: var(--c-ink); font-size: 0.95rem; font-weight: 500; min-width:0; word-break:break-word;">{{ $dlc->title }}</div>
                        <div style="display: flex; align-items: center; gap: 16px;">
                            @if($dlc->price == 0)
                                <div style="color: var(--c-primary); font-size: 0.95rem; font-weight: 600;">Free</div>
                            @else
                                <div style="color:var(--c-ink); font-size: 0.95rem; font-weight: 600;">Rp {{ number_format($dlc->price, 0, ',', '.') }}</div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                {{-- Bottom Bar --}}
                <div style="background: var(--c-canvas); display: flex; justify-content: flex-end; align-items: center; padding: 12px 16px;">
                    <div style="color: var(--c-mute); font-size: 0.8rem; text-transform: uppercase; font-weight:600;">
                        SHOWING 1 - {{ min(5, $game->dlcs->count()) }} OF {{ $game->dlcs->count() }}
                    </div>
                    <button onclick="window.location.href='{{ route('game.dlc', $game->slug) }}'" style="background: var(--c-surface-soft); color: var(--c-ink); font-weight: 600; font-size: 0.8rem; border: 1px solid var(--c-hairline); padding: 4px 12px; margin-left: 12px; border-radius: 0; cursor: pointer;" onmouseenter="this.style.background='var(--c-surface-elevated)';" onmouseleave="this.style.background='var(--c-surface-soft)';">SEE ALL <i class="fas fa-caret-down" style="margin-left: 4px;"></i></button>
                </div>
            </div>
        </div>
        @endif
    </div>


    {{-- Description --}}
    @if($game->description)
    <div class="card-dark steam-description" style="padding:32px;margin-bottom:32px;border-radius: 0;">
        <h3 style="font-size:1.2rem;font-weight:700;color:var(--c-ink);margin-bottom:20px;padding-bottom:12px;border-bottom:1px solid var(--c-hairline);">
            <i class="fas fa-align-left me-2" style="color:var(--c-primary);"></i>Deskripsi Game
        </h3>
        <div style="color:var(--c-body);line-height:1.8;font-size:0.95rem;">
            {!! $game->description !!}
        </div>
    </div>
    <style>
        .steam-description img {
            max-width: 100%;
            height: auto;
            border-radius: 0;
            margin: 10px 0;
        }
        .steam-description h1, .steam-description h2, .steam-description h3 {
            color:var(--c-ink);
            margin-top: 24px;
        }
        .steam-description ul, .steam-description ol {
            margin-left: 20px;
        }
    </style>
    @endif

    {{-- PC Requirements --}}
    @if($game->pc_requirements && (isset($game->pc_requirements['minimum']) || isset($game->pc_requirements['recommended'])))
    <div class="card-dark pc-requirements" style="padding:32px;margin-bottom:32px;border-radius: 0;background:var(--c-surface-dark);border:1px solid var(--c-hairline);">
        <h3 style="font-size:1.2rem;font-weight:700;color:var(--c-ink);margin-bottom:24px;padding-bottom:12px;border-bottom:1px solid var(--c-hairline);">
            <i class="fas fa-desktop me-2" style="color:var(--c-primary);"></i>System Requirements (PC)
        </h3>
        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(300px, 1fr));gap:32px;">
            @if(isset($game->pc_requirements['minimum']))
            <div>
                {!! $game->pc_requirements['minimum'] !!}
            </div>
            @endif
            @if(isset($game->pc_requirements['recommended']))
            <div>
                {!! $game->pc_requirements['recommended'] !!}
            </div>
            @endif
        </div>
    </div>
    <style>
        .pc-requirements strong {
            color: var(--c-ink);
            font-weight: 700;
        }
        .pc-requirements ul {
            list-style: none;
            padding-left: 0;
            margin-top: 12px;
        }
        .pc-requirements li {
            color: var(--c-mute);
            font-size: 0.9rem;
            margin-bottom: 8px;
            line-height: 1.6;
        }
    </style>
    @endif

    {{-- Related Games --}}
    @if($relatedGames->count())
    <div style="position: relative; margin-bottom: 40px;">
        {{-- Dark Container Box --}}
        <div style="background: rgba(0,0,0,0.2); padding: 24px 32px; border: 1px solid var(--c-hairline); border-radius: 0; position: relative;">
            
            {{-- Left Arrow (Inside Padding) --}}
            <button id="btn-scroll-left" style="border-radius: 0; background: transparent; color: var(--c-mute); border: none; width: 32px; position: absolute; left: 0; top: 0; bottom: 0; z-index: 10; cursor: pointer; transition: color 0.2s; display: flex; align-items: center; justify-content: center;" onmouseenter="this.style.color='#fff';" onmouseleave="this.style.color='var(--c-mute)';">
                <svg width="28" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="15 20 6 12 15 4"></polyline></svg>
            </button>

            <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 16px;">
                <h3 style="font-size:0.95rem; font-weight:600; color: var(--c-mute); margin: 0; text-transform: uppercase; letter-spacing: 1px;">
                    Game Serupa
                </h3>
                <a href="{{ route('store') }}" style="background: rgba(255,255,255,0.05); color: var(--c-mute); font-size: 0.85rem; padding: 4px 12px; border-radius: 0; text-decoration: none; transition: background 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.1)';" onmouseleave="this.style.background='rgba(255,255,255,0.05)';">
                    Lihat Semua
                </a>
            </div>
            
            <div id="related-games-container" style="display:flex; gap:12px; overflow-x: hidden; scroll-behavior: smooth;" class="custom-scroll">
                @foreach($relatedGames as $related)
                <a href="{{ route('game.show', $related->slug) }}" style="text-decoration:none; flex: 0 0 calc(25% - 9px); display: flex; flex-direction: column; background: #000; box-shadow: 0 2px 5px rgba(0,0,0,0.5); border-radius: 0; transition: filter 0.2s;" onmouseenter="this.style.filter='brightness(1.2)';" onmouseleave="this.style.filter='brightness(1)';">
                    <div style="aspect-ratio:16/9; overflow:hidden; border-radius: 0;">
                        @if($related->cover_image)
                            <img src="{{ $related->cover_image_url }}" alt="{{ $related->title }}" style="width:100%; height:100%; object-fit:cover; border-radius: 0;">
                        @else
                            <div style="width:100%; height:100%; background:linear-gradient(135deg,#1a1a1a,#0d2200); display:flex; align-items:center; justify-content:center; border-radius: 0;">
                                <i class="fas fa-gamepad" style="font-size:1.8rem; color:rgba(118,185,0,0.4);"></i>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Price Bar (Attached directly below image) --}}
                    <div style="display:flex; align-items:center; justify-content: flex-end; height: 32px; background: #000; border-radius: 0;">
                        @if($related->price == 0)
                            <span style="color: var(--c-ink); font-size: 0.85rem; font-weight: 600; padding: 0 12px;">Free To Play</span>
                        @else
                            @if($related->discount_percent > 0)
                                <div style="display: flex; align-items: center; width: 100%; height: 100%; border-radius: 0;">
                                    <div style="background: var(--c-primary); color: #000; font-weight: 800; font-size: 0.95rem; padding: 0 8px; height: 100%; display: flex; align-items: center; border-radius: 0;">-{{ $related->discount_percent }}%</div>
                                    <div style="display: flex; flex-direction: column; justify-content: center; align-items: flex-end; flex-grow: 1; background: rgba(255,255,255,0.05); height: 100%; padding-right: 12px; border-radius: 0;">
                                        <span style="color: var(--c-mute); font-size: 0.65rem; text-decoration: line-through;">Rp {{ number_format($related->original_price ?? $related->price, 0, ',', '.') }}</span>
                                        <span style="color: var(--c-primary); font-size: 0.85rem; font-weight: 600; line-height: 1;">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            @else
                                <span style="color: var(--c-ink); font-size: 0.85rem; font-weight: 600; padding: 0 12px;">Rp {{ number_format($related->price, 0, ',', '.') }}</span>
                            @endif
                        @endif
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Right Arrow (Inside Padding) --}}
            <button id="btn-scroll-right" style="border-radius: 0; background: transparent; color: var(--c-mute); border: none; width: 32px; position: absolute; right: 0; top: 0; bottom: 0; z-index: 10; cursor: pointer; transition: color 0.2s; display: flex; align-items: center; justify-content: center;" onmouseenter="this.style.color='#fff';" onmouseleave="this.style.color='var(--c-mute)';">
                <svg width="28" height="42" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="square" stroke-linejoin="miter"><polyline points="9 20 18 12 9 4"></polyline></svg>
            </button>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('related-games-container');
            const btnLeft = document.getElementById('btn-scroll-left');
            const btnRight = document.getElementById('btn-scroll-right');
            
            if(container && btnLeft && btnRight) {
                btnLeft.addEventListener('click', function() {
                    container.scrollBy({ left: -(container.clientWidth), behavior: 'smooth' });
                });
                btnRight.addEventListener('click', function() {
                    container.scrollBy({ left: container.clientWidth, behavior: 'smooth' });
                });
            }
        });
    </script>
    @endif

    {{-- Reviews Section --}}
    <div class="card-dark" style="padding:32px;border-radius: 0;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid var(--c-hairline);flex-wrap:wrap;gap:12px;">
            <h3 style="font-size:1.2rem;font-weight:700;color:var(--c-ink);margin:0;">
                <i class="fas fa-star me-2" style="color:#f59e0b;"></i>Ulasan Pengguna
                <span style="background:var(--c-surface-soft);color:var(--c-body);font-size:0.8rem;padding:3px 10px;border-radius: 0;margin-left:8px;">{{ $reviewsCount }}</span>
            </h3>
                <div style="display:flex;gap:12px;align-items:center;">
                    <div style="display:flex;align-items:center;gap:4px;">
                        <i class="fas fa-thumbs-up" style="color:#66C0F4;font-size:1.2rem;"></i>
                    </div>
                    <div style="display:flex;flex-direction:column;">
                        <span style="font-weight:800;font-size:1rem;color:var(--c-ink);line-height:1;">{{ $game->review_tier ?? 'BELUM ADA' }}</span>
                        @if($reviewsCount > 0)
                        <span style="color:var(--c-mute);font-size:0.75rem;">{{ $reviewsCount }} Ulasan</span>
                        @endif
                    </div>
                </div>
        </div>

        {{-- Review Form (if can review) --}}
        @if($canReview && !$userReview)
        <div style="background:var(--c-surface-soft);border:1px solid var(--c-hairline);border-radius: 0;padding:24px;margin-bottom:28px;">
            <h5 style="font-weight:700;color:var(--c-ink);margin-bottom:16px;font-size:1rem;"><i class="fas fa-pen me-2" style="color:var(--c-primary);"></i>Tulis Ulasanmu</h5>
            <form action="{{ route('review.store', $game->slug) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom:20px;">
                    <label class="form-label" style="color:var(--c-body);font-weight:700;margin-bottom:12px;display:block;">Apakah Anda merekomendasikan game ini?</label>
                    <div id="thumb-rating" style="display:flex;gap:12px;">
                        <label style="cursor:pointer;flex:1;">
                            <input type="radio" name="rating" value="5" style="display:none;" required onchange="updateThumbSelect(this)">
                            <div class="thumb-btn" style="background:var(--c-surface-dark);border:2px solid var(--c-hairline-strong);border-radius: 0;padding:16px;text-align:center;transition:all 0.2s;">
                                <i class="fas fa-thumbs-up" style="font-size:2rem;color:var(--c-mute);margin-bottom:8px;transition:color 0.2s;"></i>
                                <div style="color:var(--c-ink);font-weight:700;">Ya</div>
                            </div>
                        </label>
                        <label style="cursor:pointer;flex:1;">
                            <input type="radio" name="rating" value="1" style="display:none;" required onchange="updateThumbSelect(this)">
                            <div class="thumb-btn" style="background:var(--c-surface-dark);border:2px solid var(--c-hairline-strong);border-radius: 0;padding:16px;text-align:center;transition:all 0.2s;">
                                <i class="fas fa-thumbs-down" style="font-size:2rem;color:var(--c-mute);margin-bottom:8px;transition:color 0.2s;"></i>
                                <div style="color:var(--c-ink);font-weight:700;">Tidak</div>
                            </div>
                        </label>
                    </div>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label" style="color:var(--c-body);">Ulasan (Opsional)</label>
                    <textarea name="body" class="form-control search-input" rows="4" placeholder="Ceritakan pendapatmu tentang game ini..." style="resize:vertical;height:auto;padding:12px;background:var(--c-surface-dark);"></textarea>
                </div>
                <div style="margin-bottom:20px;">
                    <label class="form-label" style="color:var(--c-body);">Lampirkan Media (Gambar/Video Opsional)</label>
                    <input type="file" name="media" accept="image/*,video/*" class="form-control search-input review-media-input" style="background:var(--c-surface-dark);border-color:var(--c-hairline-strong);color:var(--c-mute);">
                    <div class="media-preview-final" style="margin-top:12px; display:none;"></div>
                </div>
                <button type="submit" class="btn-primary" style="padding:10px 24px;border:none;border-radius: 0;cursor:pointer;font-size:0.9rem;font-weight:600;">
                    <i class="fas fa-paper-plane me-2"></i>Kirim Ulasan
                </button>
            </form>
            <script>
                function updateThumbSelect(radio) {
                    const container = document.getElementById('thumb-rating');
                    const btns = container.querySelectorAll('.thumb-btn');
                    const icons = container.querySelectorAll('.thumb-btn i');
                    
                    // Reset all
                    btns.forEach(btn => {
                        btn.style.borderColor = 'var(--c-hairline-strong)';
                        btn.style.background = 'var(--c-surface-dark)';
                    });
                    icons.forEach(icon => {
                        icon.style.color = 'var(--c-mute)';
                    });

                    // Highlight selected
                    const selectedBtn = radio.nextElementSibling;
                    const selectedIcon = selectedBtn.querySelector('i');
                    if(radio.value == '5') {
                        selectedBtn.style.borderColor = '#66C0F4';
                        selectedBtn.style.background = 'rgba(102,192,244,0.1)';
                        selectedIcon.style.color = '#66C0F4';
                    } else {
                        selectedBtn.style.borderColor = '#A34C25';
                        selectedBtn.style.background = 'rgba(163,76,37,0.1)';
                        selectedIcon.style.color = '#A34C25';
                    }
                }
            </script>
        </div>
        @endif

        {{-- User's existing review --}}
        @if($userReview)
        <div id="user-review-display" style="background:rgba(118,185,0,0.05);border:1px solid rgba(118,185,0,0.2);border-radius: 0;padding:20px;margin-bottom:24px;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:1px solid var(--c-primary);">
                    <div>
                        <div style="font-weight:700;color:var(--c-ink);font-size:0.9rem;">{{ auth()->user()->name }} <span style="background:var(--c-primary);color:#000;font-size:0.65rem;padding:2px 7px;border-radius: 0;margin-left:6px;">Ulasanmu</span></div>
                        <div style="font-size:0.75rem;color:var(--c-mute);">{{ $userReview->created_at->diffForHumans() }}</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    @if($userReview->rating >= 3)
                        <div style="background:rgba(102,192,244,0.1);color:#66C0F4;padding:4px 10px;border-radius: 0;font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-thumbs-up"></i> Direkomendasikan
                        </div>
                    @else
                        <div style="background:rgba(163,76,37,0.1);color:#A34C25;padding:4px 10px;border-radius: 0;font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:6px;">
                            <i class="fas fa-thumbs-down"></i> Tidak Direkomendasikan
                        </div>
                    @endif
                </div>
            </div>
            @if($userReview->body)
            <p style="color:var(--c-body);margin-bottom:16px;line-height:1.6;word-break:break-word;">{{ $userReview->body }}</p>
            @endif
            @if($userReview->media_path)
            <div style="margin-bottom:16px;">
                @if($userReview->media_type == 'video')
                    <video controls style="max-width:100%;max-height:300px;border-radius: 0;border:1px solid var(--c-hairline);" preload="metadata">
                        <source src="{{ asset('storage/' . $userReview->media_path) }}#t={{ $userReview->video_start ?? 0 }},{{ $userReview->video_end ?? '' }}">
                        Browser Anda tidak mendukung tag video.
                    </video>
                @else
                    <a href="{{ asset('storage/' . $userReview->media_path) }}" target="_blank">
                        <img src="{{ asset('storage/' . $userReview->media_path) }}" alt="Review Attachment" style="max-height:200px;border-radius: 0;border:1px solid var(--c-hairline);object-fit:cover;">
                    </a>
                @endif
            </div>
            @endif
            <div style="display:flex;gap:8px;">
                <button type="button" onclick="document.getElementById('edit-review-form').style.display='block'; document.getElementById('user-review-display').style.display='none';" class="btn-outline" style="padding:7px 16px;font-size:0.8rem;cursor:pointer;background:transparent;border:1px solid var(--c-primary);color:var(--c-primary);border-radius: 0;display:inline-flex;align-items:center;gap:6px;">
                    <i class="fas fa-edit"></i>Edit
                </button>
                <form action="{{ route('review.destroy', $userReview->id) }}" method="POST" onsubmit="return confirmAction(event, this, 'Konfirmasi Hapus', 'Hapus ulasan ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-outline-dark" style="padding:7px 16px;font-size:0.8rem;color:#e53935;border-color:rgba(229,57,53,0.3);cursor:pointer;display:inline-flex;align-items:center;gap:6px;" onmouseenter="this.style.background='#e53935';this.style.color='#fff';" onmouseleave="this.style.background='transparent';this.style.color='#e53935';">
                        <i class="fas fa-trash"></i>Hapus
                    </button>
                </form>
            </div>
        </div>

        {{-- Edit Form (Hidden by default) --}}
        <div id="edit-review-form" style="display:none;background:var(--c-surface-soft);border:1px solid var(--c-hairline);border-radius: 0;padding:24px;margin-bottom:28px;">
            <h5 style="font-weight:700;color:var(--c-ink);margin-bottom:16px;font-size:1rem;"><i class="fas fa-edit me-2" style="color:var(--c-primary);"></i>Edit Ulasanmu</h5>
            <form action="{{ route('review.update', $userReview->id) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div style="margin-bottom:20px;">
                    <label class="form-label" style="color:var(--c-body);font-weight:700;margin-bottom:12px;display:block;">Apakah Anda merekomendasikan game ini?</label>
                    <div id="thumb-rating-edit" style="display:flex;gap:12px;">
                        <label style="cursor:pointer;flex:1;">
                            <input type="radio" name="rating" value="5" style="display:none;" required onchange="updateThumbSelectEdit(this)" {{ $userReview->rating >= 3 ? 'checked' : '' }}>
                            <div class="thumb-btn-edit" style="background:{{ $userReview->rating >= 3 ? 'rgba(102,192,244,0.1)' : 'var(--c-surface-dark)' }};border:2px solid {{ $userReview->rating >= 3 ? '#66C0F4' : 'var(--c-hairline-strong)' }};border-radius: 0;padding:16px;text-align:center;transition:all 0.2s;">
                                <i class="fas fa-thumbs-up" style="font-size:2rem;color:{{ $userReview->rating >= 3 ? '#66C0F4' : 'var(--c-mute)' }};margin-bottom:8px;transition:color 0.2s;"></i>
                                <div style="color:var(--c-ink);font-weight:700;">Ya</div>
                            </div>
                        </label>
                        <label style="cursor:pointer;flex:1;">
                            <input type="radio" name="rating" value="1" style="display:none;" required onchange="updateThumbSelectEdit(this)" {{ $userReview->rating < 3 ? 'checked' : '' }}>
                            <div class="thumb-btn-edit" style="background:{{ $userReview->rating < 3 ? 'rgba(163,76,37,0.1)' : 'var(--c-surface-dark)' }};border:2px solid {{ $userReview->rating < 3 ? '#A34C25' : 'var(--c-hairline-strong)' }};border-radius: 0;padding:16px;text-align:center;transition:all 0.2s;">
                                <i class="fas fa-thumbs-down" style="font-size:2rem;color:{{ $userReview->rating < 3 ? '#A34C25' : 'var(--c-mute)' }};margin-bottom:8px;transition:color 0.2s;"></i>
                                <div style="color:var(--c-ink);font-weight:700;">Tidak</div>
                            </div>
                        </label>
                    </div>
                </div>
                <div style="margin-bottom:16px;">
                    <label class="form-label" style="color:var(--c-body);">Ulasan (Opsional)</label>
                    <textarea name="body" class="form-control search-input" rows="4" style="resize:vertical;height:auto;padding:12px;background:var(--c-surface-dark);">{{ $userReview->body }}</textarea>
                </div>
                <div style="margin-bottom:20px;">
                    <label class="form-label" style="color:var(--c-body);">Ganti/Tambah Media (Gambar/Video Opsional)</label>
                    <input type="file" name="media" accept="image/*,video/*" class="form-control search-input review-media-input" style="background:var(--c-surface-dark);border-color:var(--c-hairline-strong);color:var(--c-mute);">
                    <div class="media-preview-final" style="margin-top:12px; display:none;"></div>
                    @if($userReview->media_path)
                        <div style="margin-top:8px;font-size:0.8rem;color:var(--c-mute);">Saat ini sudah ada media terlampir. Unggah yang baru untuk mengganti.</div>
                    @endif
                </div>
                <div style="display:flex;gap:12px;">
                    <button type="submit" class="btn-primary" style="padding:10px 24px;border:none;border-radius: 0;cursor:pointer;font-size:0.9rem;font-weight:600;">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                    <button type="button" onclick="document.getElementById('edit-review-form').style.display='none'; document.getElementById('user-review-display').style.display='block';" class="btn-outline" style="padding:10px 24px;border-radius: 0;cursor:pointer;font-size:0.9rem;font-weight:600;">
                        Batal
                    </button>
                </div>
            </form>
            <script>
                function updateThumbSelectEdit(radio) {
                    const container = document.getElementById('thumb-rating-edit');
                    const btns = container.querySelectorAll('.thumb-btn-edit');
                    const icons = container.querySelectorAll('.thumb-btn-edit i');
                    
                    // Reset all
                    btns.forEach(btn => {
                        btn.style.borderColor = 'var(--c-hairline-strong)';
                        btn.style.background = 'var(--c-surface-dark)';
                    });
                    icons.forEach(icon => {
                        icon.style.color = 'var(--c-mute)';
                    });

                    // Highlight selected
                    const selectedBtn = radio.nextElementSibling;
                    const selectedIcon = selectedBtn.querySelector('i');
                    if(radio.value == '5') {
                        selectedBtn.style.borderColor = '#66C0F4';
                        selectedBtn.style.background = 'rgba(102,192,244,0.1)';
                        selectedIcon.style.color = '#66C0F4';
                    } else {
                        selectedBtn.style.borderColor = '#A34C25';
                        selectedBtn.style.background = 'rgba(163,76,37,0.1)';
                        selectedIcon.style.color = '#A34C25';
                    }
                }
            </script>
        </div>
        @endif

        {{-- Reviews List --}}
        @if($game->reviews && $game->reviews->count())
            <div style="display:flex;flex-direction:column;gap:20px;">
                @foreach($game->reviews->where('id', '!=', optional($userReview)->id) as $review)
                <div style="padding:20px;border:1px solid var(--c-hairline);border-radius: 0;transition:border-color 0.2s;" onmouseenter="this.style.borderColor='var(--c-primary)';" onmouseleave="this.style.borderColor='var(--c-hairline)';">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;flex-wrap:wrap;gap:8px;">
                        <div style="display:flex;align-items:center;gap:10px;">
                            <img src="{{ optional($review->user)->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($review->user->name ?? 'A').'&background=76b900&color=000&bold=true' }}" alt="{{ $review->user->name ?? 'Pengguna' }}" style="width:36px;height:36px;border-radius:50%;object-fit:cover;border:1px solid var(--c-hairline-strong);">
                            <div>
                                <div style="font-weight:600;color:var(--c-ink);font-size:0.9rem;">{{ $review->user->name ?? 'Pengguna' }}</div>
                                <div style="font-size:0.75rem;color:var(--c-mute);">{{ $review->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:6px;">
                            @if($review->rating >= 3)
                                <div style="background:rgba(102,192,244,0.1);color:#66C0F4;padding:4px 10px;border-radius: 0;font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:6px;">
                                    <i class="fas fa-thumbs-up"></i> Direkomendasikan
                                </div>
                            @else
                                <div style="background:rgba(163,76,37,0.1);color:#A34C25;padding:4px 10px;border-radius: 0;font-size:0.8rem;font-weight:700;display:flex;align-items:center;gap:6px;">
                                    <i class="fas fa-thumbs-down"></i> Tidak Direkomendasikan
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($review->body)
                    <p style="color:var(--c-body);margin-bottom:16px;line-height:1.7;font-size:0.9rem;word-break:break-word;">{{ $review->body }}</p>
                    @endif
                    @if($review->media_path)
                    <div style="margin-bottom:16px;">
                        @if($review->media_type == 'video')
                            <video controls style="max-width:100%;max-height:300px;border-radius: 0;border:1px solid var(--c-hairline);" preload="metadata">
                                <source src="{{ asset('storage/' . $review->media_path) }}#t={{ $review->video_start ?? 0 }},{{ $review->video_end ?? '' }}">
                                Browser Anda tidak mendukung tag video.
                            </video>
                        @else
                            <a href="{{ asset('storage/' . $review->media_path) }}" target="_blank">
                                <img src="{{ asset('storage/' . $review->media_path) }}" alt="Review Attachment" style="max-height:200px;border-radius: 0;border:1px solid var(--c-hairline);object-fit:cover;">
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center;padding:40px 20px;color:#888;">
                <i class="far fa-comment" style="font-size:2.5rem;display:block;margin-bottom:12px;"></i>
                Belum ada ulasan untuk game ini.
                @if($canReview && !$userReview) Jadilah yang pertama! @endif
            </div>
        @endif
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // Initialize HLS for videos
    const videos = document.querySelectorAll('video');
    videos.forEach(video => {
        const src = video.getAttribute('src');
        if (src && src.includes('.m3u8')) {
            if (Hls.isSupported()) {
                const hls = new Hls();
                hls.loadSource(src);
                hls.attachMedia(video);
                // Video tags need volume adjustment because sometimes autoplay with sound is blocked
                video.volume = 0.5;
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                // Native Safari support
                video.src = src;
            }
        }
    });

    // Star Rating UI
    const starContainer = document.getElementById('star-rating');
    if (starContainer) {
        const labels = starContainer.querySelectorAll('label');
        labels.forEach((label, idx) => {
            const icon = label.querySelector('i');
            const radio = label.querySelector('input');
            label.addEventListener('mouseenter', () => {
                labels.forEach((l, i) => l.querySelector('i').className = i <= idx ? 'fas fa-star' : 'far fa-star');
                labels.forEach((l, i) => l.querySelector('i').style.color = i <= idx ? '#f59e0b' : '#ddd');
            });
            label.addEventListener('mouseleave', () => {
                const checked = starContainer.querySelector('input:checked');
                const val = checked ? parseInt(checked.value) : 0;
                labels.forEach((l, i) => {
                    l.querySelector('i').className = i < val ? 'fas fa-star' : 'far fa-star';
                    l.querySelector('i').style.color = i < val ? '#f59e0b' : '#ddd';
                });
            });
            radio.addEventListener('change', () => {
                labels.forEach((l, i) => {
                    l.querySelector('i').className = i <= idx ? 'fas fa-star' : 'far fa-star';
                    l.querySelector('i').style.color = i <= idx ? '#f59e0b' : '#ddd';
                });
            });
        });
    }

    // Cart handling is now done globally via app.blade.php

    // Add to Cart for DLCs
    const dlcCartBtns = document.querySelectorAll('.add-to-cart-dlc');
    dlcCartBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const slug = this.dataset.gameSlug;
            fetch(`/cart/${slug}`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},
                body: JSON.stringify({_token: csrfToken})
            }).then(r=>r.json()).then(data => {
                if (data.error) { if (typeof showCenterPopup==='function') showCenterPopup(data.error, false); return; }
                if (data.in_cart) {
                    this.innerHTML = `<i class="fas fa-check"></i>`;
                } else {
                    this.innerHTML = `<i class="fas fa-cart-plus"></i>`;
                }
                if (typeof showCenterPopup==='function') showCenterPopup(data.message, true);
                if (typeof updateBadge==='function') updateBadge('cart-count', data.count);
            }).catch(()=>{});
        });
    });

    // Add to Wishlist
    const wishBtn = document.getElementById('add-to-wishlist-btn');
    if (wishBtn) {
        wishBtn.addEventListener('click', function() {
            const slug = this.dataset.gameSlug;
            const icon = this.querySelector('i');
            fetch(`/wishlist/${slug}`, {
                method: 'POST',
                headers: {'Content-Type':'application/json','X-CSRF-TOKEN':csrfToken,'Accept':'application/json'},
                body: JSON.stringify({_token: csrfToken})
            }).then(r=>r.json()).then(data => {
                if (data.error) { 
                    if (typeof showCenterPopup==='function') showCenterPopup(data.error, false); 
                    return; 
                }
                
                // Update button state based on response
                if (data.in_wishlist) {
                    icon.className = 'fas fa-heart';
                    this.style.borderColor = 'var(--c-primary)';
                    this.style.background = 'rgba(118,185,0,0.1)';
                    this.style.color = 'var(--c-primary)';
                    this.innerHTML = `<i class="fas fa-heart"></i> Tersimpan di Wishlist`;
                } else {
                    icon.className = 'far fa-heart';
                    this.style.borderColor = 'var(--c-hairline)';
                    this.style.background = 'var(--c-surface-soft)';
                    this.style.color = 'var(--c-body)';
                    this.innerHTML = `<i class="far fa-heart"></i> Tambah ke Wishlist`;
                }
                
                if (typeof showCenterPopup==='function') showCenterPopup(data.message, true);
                if (typeof updateBadge==='function') updateBadge('wishlist-count', data.count);
            }).catch(err=>{
                console.error('Wishlist error:', err);
                if (typeof showCenterPopup==='function') showCenterPopup('Terjadi kesalahan', false);
            });
        });
    }
});

// Helper for Bundle/Add all DLCs
function addAllToCart(gameSlug) {
    if (typeof showCenterPopup === 'function') {
        showCenterPopup('Menambahkan bundle ke keranjang...', true);
    }
    fetch(`/cart/${gameSlug}/bundle`, {
        method: 'POST',
        headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content,'Accept':'application/json'},
    }).then(r=>r.json()).then(data => {
        if (data.error) {
            if (typeof showCenterPopup === 'function') showCenterPopup(data.error, false);
            return;
        }
        if (typeof showCenterPopup === 'function') showCenterPopup('Bundle berhasil ditambahkan!', true);
        if (typeof updateBadge === 'function') updateBadge('cart-count', data.count);
        
        // Refresh page after a short delay to reflect UI changes (Cart buttons)
        setTimeout(() => window.location.reload(), 1000);
    }).catch(e => {
        console.error(e);
        if (typeof showCenterPopup === 'function') showCenterPopup('Terjadi kesalahan', false);
    });
}

function claimFreeGame(gameSlug) {
    fetch(`/library/${gameSlug}/claim`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        if(data.success) {
            if (typeof showCenterPopup === 'function') {
                showCenterPopup(data.message, true);
            }
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            if (typeof showCenterPopup === 'function') showCenterPopup(data.message || 'Gagal menambahkan game.', false);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (typeof showCenterPopup === 'function') showCenterPopup('Terjadi kesalahan saat menambahkan game.', false);
    });
}
</script>

@include('game.partials.review_media')

@endsection
