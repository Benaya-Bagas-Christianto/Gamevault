@extends('layouts.app')

@section('title', 'Downloadable Content for ' . $game->title)

@section('content')
<div class="container" style="padding-top: 30px; margin-bottom: 50px;">
    
    {{-- Breadcrumb / Header --}}
    <div style="margin-bottom: 20px;">
        <a href="{{ route('game.show', $game->slug) }}" style="color: var(--c-mute); text-decoration: none; font-size: 0.85rem; padding: 4px 8px; background: rgba(0,0,0,0.3); border-radius: 2px;">&larr; Kembali ke halaman game</a>
    </div>

    {{-- Hero Section --}}
    <div style="background-color: var(--c-surface-dark); border-radius: 0; margin-bottom: 30px; position: relative; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.5);">
        {{-- Background image that isn't cropped --}}
        <div style="position: absolute; right: 0; top: 0; bottom: 0; width: 50%; background: url('{{ $game->cover_image_url }}') right center / contain no-repeat; opacity: 0.8;"></div>
        {{-- Gradient overlay to blend with background --}}
        <div style="position: absolute; inset: 0; background: linear-gradient(to right, var(--c-surface-dark) 40%, rgba(0,0,0,0) 100%);"></div>

        <div style="position: relative; padding: 60px; min-height: 250px; display: flex; flex-direction: column; justify-content: center; z-index: 2;">
            <div style="color: var(--c-primary); font-size: 1rem; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; letter-spacing: 1px;">Downloadable Content For</div>
            <h1 style="color: #fff; font-size: 2.8rem; font-weight: 700; margin: 0; text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">{{ $game->title }}</h1>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="display: flex; gap: 30px; border-bottom: 1px solid var(--c-hairline); margin-bottom: 30px; padding-bottom: 0;">
        <div style="color: var(--c-ink); font-size: 1.1rem; font-weight: 600; text-transform: uppercase; padding: 12px 24px; border-bottom: 3px solid var(--c-primary); background: rgba(255,255,255,0.05);">Daftar Semua DLC</div>
    </div>

    {{-- Content Area --}}
    <div style="display: flex; gap: 40px;">
        
        @php
            $genresCount = [];
            foreach($game->dlcs as $dlc) {
                if(is_array($dlc->genre)) {
                    foreach($dlc->genre as $g) {
                        $genresCount[$g] = ($genresCount[$g] ?? 0) + 1;
                    }
                } elseif(is_string($dlc->genre)) {
                    $g = $dlc->genre;
                    $genresCount[$g] = ($genresCount[$g] ?? 0) + 1;
                }
            }
            arsort($genresCount); // sort by count descending
        @endphp

        {{-- Filters (Sidebar) --}}
        <div style="width: 280px; flex-shrink: 0;">
            <div style="color: var(--c-mute); font-size: 0.85rem; font-weight: 600; text-transform: uppercase; margin-bottom: 12px; letter-spacing: 1px;">Filter berdasarkan</div>
            
            <div class="filter-btn active" data-genre="all" style="background: rgba(0,0,0,0.2); padding: 12px 16px; border-radius: 2px; color: var(--c-primary); font-size: 1rem; font-weight: 600; display: flex; justify-content: space-between; align-items: center; cursor: pointer; border-left: 3px solid var(--c-primary); margin-bottom: 4px;">
                Semua <span style="background: rgba(255,255,255,0.1); padding: 4px 8px; border-radius: 2px; font-size: 0.85rem;">{{ $game->dlcs->count() }}</span>
            </div>

            @foreach($genresCount as $genre => $count)
                <div class="filter-btn" data-genre="{{ htmlspecialchars($genre) }}" style="padding: 12px 16px; color: var(--c-mute); font-size: 1rem; display: flex; justify-content: space-between; align-items: center; cursor: pointer; transition: background 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.05)';" onmouseleave="if(!this.classList.contains('active')) this.style.background='transparent';">
                    {{ $genre }} <span style="background: rgba(255,255,255,0.05); padding: 4px 8px; border-radius: 2px; font-size: 0.85rem;">{{ $count }}</span>
                </div>
            @endforeach
        </div>

        {{-- DLC List --}}
        <div style="flex-grow: 1;">
            
            <div style="background: rgba(0,0,0,0.3); padding: 12px 20px; border-radius: 2px 2px 0 0; color: var(--c-ink); font-size: 1.1rem; font-weight: 700; border-bottom: 1px solid var(--c-hairline); display: flex; justify-content: space-between;">
                <div>Semua Konten Tambahan</div>
            </div>

            <div style="background: var(--c-surface-dark); padding: 20px; border-radius: 0 0 2px 2px; border: 1px solid var(--c-hairline); border-top: none;">
                @if($game->dlcs->count() > 0)
                    @foreach($game->dlcs as $dlc)
                        @php
                            $inCart = in_array($dlc->id, $cartGameIds);
                            // prepare genres for data attribute
                            $dlcGenres = is_array($dlc->genre) ? $dlc->genre : (is_string($dlc->genre) ? [$dlc->genre] : []);
                        @endphp
                        <div class="dlc-item" data-genres="{{ htmlspecialchars(json_encode($dlcGenres)) }}" style="display: flex; gap: 20px; background: rgba(0,0,0,0.2); padding: 12px; margin-bottom: 12px; transition: background 0.2s; position: relative;" onmouseenter="this.style.background='rgba(255,255,255,0.05)';" onmouseleave="this.style.background='rgba(0,0,0,0.2)';">
                            
                            {{-- Image --}}
                            <div style="width: 160px; height: 75px; flex-shrink: 0; background: #000;">
                                @if($dlc->cover_image)
                                    <img src="{{ $dlc->cover_image_url }}" alt="{{ $dlc->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                @else
                                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; color: #555;"><i class="fas fa-gamepad" style="font-size: 2rem;"></i></div>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div style="flex-grow: 1; display: flex; flex-direction: column; justify-content: center;">
                                <div style="color: var(--c-ink); font-size: 1.15rem; font-weight: 600; margin-bottom: 6px;">{{ $dlc->title }}</div>
                                <div style="color: var(--c-mute); font-size: 0.85rem;">
                                    <i class="fab fa-windows" style="margin-right: 6px;"></i> Base game required
                                </div>
                            </div>

                            {{-- Price & Cart --}}
                            <div style="display: flex; flex-direction: column; justify-content: center; align-items: flex-end; gap: 8px; width: 160px;">
                                @if($dlc->price == 0)
                                    <div style="color: var(--c-primary); font-size: 1.1rem; font-weight: 700;">Gratis</div>
                                @else
                                    <div style="color: var(--c-ink); font-size: 1.1rem; font-weight: 700;">Rp {{ number_format($dlc->price, 0, ',', '.') }}</div>
                                @endif
                                
                                <button class="add-to-cart-dlc" data-game-slug="{{ $dlc->slug }}" style="<?php echo 'padding: 6px 16px; background: ' . ($inCart ? 'var(--c-surface-soft)' : 'var(--c-primary)') . '; color: ' . ($inCart ? 'var(--c-ink)' : '#000') . '; border: none; font-size: 0.85rem; font-weight: 700; cursor: ' . ($inCart ? 'not-allowed' : 'pointer') . '; border-radius: 2px;'; ?>">
                                    @if($inCart)
                                        Di Keranjang
                                    @else
                                        Tambah ke Cart
                                    @endif
                                </button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div style="text-align: center; padding: 40px; color: var(--c-mute);">
                        Belum ada konten tambahan untuk game ini.
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>

{{-- Script for DLC Add to Cart & Filtering --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Filtering Logic
    const filterBtns = document.querySelectorAll('.filter-btn');
    const dlcItems = document.querySelectorAll('.dlc-item');

    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active from all
            filterBtns.forEach(b => {
                b.classList.remove('active');
                b.style.background = 'transparent';
                b.style.borderLeft = 'none';
                b.style.color = 'var(--c-mute)';
            });

            // Set this as active
            this.classList.add('active');
            this.style.background = 'rgba(0,0,0,0.2)';
            this.style.borderLeft = '3px solid var(--c-primary)';
            this.style.color = 'var(--c-primary)';

            const targetGenre = this.dataset.genre;

            // Show/hide items
            dlcItems.forEach(item => {
                if (targetGenre === 'all') {
                    item.style.display = 'flex';
                } else {
                    try {
                        const genres = JSON.parse(item.dataset.genres || '[]');
                        if (genres.includes(targetGenre)) {
                            item.style.display = 'flex';
                        } else {
                            item.style.display = 'none';
                        }
                    } catch(e) {
                        item.style.display = 'none';
                    }
                }
            });
        });
    });

    // Add to Cart Logic
    const dlcButtons = document.querySelectorAll('.add-to-cart-dlc');
    
    dlcButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const slug = this.dataset.gameSlug;
            if (this.innerText.trim() === 'Di Keranjang') return;
            
            const originalText = this.innerText;
            this.innerText = 'Menambahkan...';
            this.disabled = true;

            fetch(`/cart/add/${slug}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.innerText = 'Di Keranjang';
                    this.style.background = 'var(--c-surface-soft)';
                    this.style.color = 'var(--c-ink)';
                    
                    // Update cart counter if exists
                    const counter = document.getElementById('cart-counter');
                    if(counter) {
                        counter.innerText = data.cart_count;
                        counter.style.display = 'inline-block';
                    }
                } else {
                    alert(data.message || 'Gagal menambahkan ke keranjang');
                    this.innerText = originalText;
                    this.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Terjadi kesalahan sistem.');
                this.innerText = originalText;
                this.disabled = false;
            });
        });
    });
});
</script>
@endsection
