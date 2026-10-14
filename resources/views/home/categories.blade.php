@extends('layouts.app')

@section('title', 'Kategori & Eksplorasi — GameVault')

@section('content')
<style>
    /* ════ CATEGORIES RESPONSIVE SYSTEM (Sharp Box Gaming Style) ════ */
    .category-page-container {
        padding-top: 32px;
        padding-bottom: 64px;
        width: 100%;
        max-width: 1380px;
        margin: 0 auto;
        padding-left: 24px;
        padding-right: 24px;
    }

    .cat-top-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .cat-page-title {
        font-size: 1.5rem;
        font-weight: 800;
        margin: 0;
        color: var(--c-ink);
        letter-spacing: 0.5px;
    }

    /* Mobile Quick Genre Scroll Bar */
    .cat-mobile-quick-bar {
        display: none;
        gap: 8px;
        overflow-x: auto;
        white-space: nowrap;
        padding-bottom: 12px;
        margin-bottom: 16px;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
    }
    .cat-mobile-quick-bar::-webkit-scrollbar {
        display: none;
    }
    .cat-quick-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        background: var(--c-surface-soft);
        border: 1px solid var(--c-hairline);
        color: var(--c-mute);
        font-size: 0.82rem;
        font-weight: 700;
        text-decoration: none;
        border-radius: 0;
        flex-shrink: 0;
        transition: all 0.2s ease;
    }
    .cat-quick-chip.active {
        background: rgba(118, 185, 0, 0.15);
        border-color: var(--c-primary);
        color: var(--c-primary);
    }

    /* Main 2-Col Layout */
    .category-main-flex {
        display: flex;
        gap: 32px;
        align-items: flex-start;
    }

    /* Filter Modal Overlay (Mobile) */
    .filter-modal-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.75);
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.3s;
    }
    .filter-modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    /* Sidebar Filter (Desktop) */
    .sidebar-filter {
        width: 280px;
        flex-shrink: 0;
        background: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        border-radius: 0;
        padding: 20px;
        position: sticky;
        top: 90px;
        max-height: calc(100vh - 120px);
        overflow-y: auto;
    }
    .sidebar-filter::-webkit-scrollbar {
        width: 5px;
    }
    .sidebar-filter::-webkit-scrollbar-thumb {
        background: var(--c-hairline-strong);
        border-radius: 0;
    }

    .filter-header-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--c-hairline);
    }
    .filter-title {
        font-size: 1rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--c-ink);
        margin: 0;
    }
    .filter-reset-btn {
        background: none;
        border: none;
        color: var(--c-primary);
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        padding: 4px 6px;
    }
    .filter-reset-btn:hover {
        text-decoration: underline;
    }

    .filter-search-box {
        position: relative;
        margin-bottom: 20px;
    }
    .filter-search-box i {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--c-mute);
        font-size: 13px;
    }
    .filter-search-input {
        width: 100%;
        padding: 9px 12px 9px 34px;
        background: var(--c-surface-elevated);
        border: 1px solid var(--c-hairline-strong);
        border-radius: 0;
        color: var(--c-ink);
        font-size: 13.5px;
        outline: none;
        transition: border-color 0.2s;
    }
    .filter-search-input:focus {
        border-color: var(--c-primary);
    }

    .filter-section-title {
        font-size: 13px;
        font-weight: 800;
        text-transform: uppercase;
        margin: 0 0 10px 0;
        color: var(--c-primary);
        letter-spacing: 0.5px;
    }
    .filter-checkbox-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .filter-check-item {
        display: flex;
        align-items: center;
        gap: 9px;
        cursor: pointer;
        font-size: 13px;
        color: var(--c-body);
        user-select: none;
        transition: color 0.15s;
    }
    .filter-check-item:hover {
        color: var(--c-ink);
    }
    .filter-check-item input[type="checkbox"] {
        accent-color: var(--c-primary);
        width: 16px;
        height: 16px;
        border-radius: 0;
        cursor: pointer;
    }

    /* Mobile Drawer Specifics */
    .sidebar-mobile-drawer-close {
        display: none;
    }
    .sidebar-mobile-bottom-actions {
        display: none;
    }

    /* ════ MOBILE RESPONSIVE ADAPTATIONS (<= 768px) ════ */
    @media (max-width: 768px) {
        .category-page-container {
            padding-top: 14px;
            padding-bottom: 48px;
            padding-left: 12px !important;
            padding-right: 12px !important;
        }

        .cat-top-header {
            margin-bottom: 12px;
        }
        .cat-page-title {
            font-size: 1.25rem;
        }

        .cat-mobile-quick-bar {
            display: flex;
        }

        .category-main-flex {
            gap: 0;
        }

        /* Mobile Filter Button (Sharp Gaming Look) */
        .mobile-filter-trigger-btn {
            padding: 8px 16px;
            background: var(--c-primary);
            color: #000;
            border: none;
            border-radius: 0;
            font-weight: 800;
            font-size: 13px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            letter-spacing: 0.3px;
            box-shadow: 0 2px 10px rgba(118,185,0,0.3);
        }

        /* Mobile Bottom Drawer (Sharp Box Style) */
        .sidebar-filter {
            position: fixed !important;
            bottom: -100% !important;
            left: 0 !important;
            right: 0 !important;
            top: auto !important;
            width: 100% !important;
            background: var(--c-surface-elevated) !important;
            border: none !important;
            border-top: 2px solid var(--c-primary) !important;
            border-radius: 0 !important;
            z-index: 1001 !important;
            padding: 18px 16px 20px !important;
            transition: bottom 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            max-height: 82vh !important;
            overflow-y: auto !important;
            box-shadow: 0 -8px 30px rgba(0,0,0,0.8);
        }
        .sidebar-filter.active {
            bottom: 0 !important;
        }

        .sidebar-mobile-drawer-close {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 1px solid var(--c-hairline);
        }
        .sidebar-mobile-drawer-title {
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .sidebar-mobile-close-btn {
            background: none;
            border: none;
            color: var(--c-mute);
            font-size: 20px;
            cursor: pointer;
            padding: 4px;
        }

        /* Sticky bottom action inside drawer */
        .sidebar-mobile-bottom-actions {
            display: flex;
            position: sticky;
            bottom: -20px;
            left: 0;
            right: 0;
            background: var(--c-surface-elevated);
            padding: 12px 0 0 0;
            margin-top: 20px;
            border-top: 1px solid var(--c-hairline);
            gap: 10px;
            z-index: 10;
        }
        .sidebar-apply-btn {
            flex: 1;
            padding: 11px;
            background: var(--c-primary);
            color: #000;
            font-weight: 800;
            font-size: 13.5px;
            border: none;
            border-radius: 0;
            cursor: pointer;
            text-transform: uppercase;
        }
        .sidebar-clear-btn {
            padding: 11px 16px;
            background: var(--c-surface-dark);
            color: var(--c-mute);
            border: 1px solid var(--c-hairline);
            border-radius: 0;
            font-weight: 700;
            font-size: 13px;
            cursor: pointer;
        }
    }
</style>

<!-- Mobile Filter Modal Overlay -->
<div class="filter-modal-overlay" onclick="closeMobileFilter()"></div>

<div class="category-page-container">
    
    {{-- Header --}}
    <div class="cat-top-header">
        <h2 class="cat-page-title">Eksplorasi <span style="color: var(--c-primary);">Katalog</span></h2>
        
        <button type="button" class="mobile-filter-trigger-btn mobile-only" onclick="openMobileFilter()">
            <i class="fas fa-filter"></i>
            <span>Filter Kategori</span>
            @if(count($genreDipilih) > 0 || count($hargaDipilih) > 0)
                <span style="background:#000; color:var(--c-primary); font-size:10px; padding:1px 5px; font-weight:800;">{{ count($genreDipilih) + count($hargaDipilih) }}</span>
            @endif
        </button>
    </div>

    {{-- Mobile Quick Genres Scroll Bar --}}
    @php
        $popularGenres = ['Aksi', 'RPG', 'Petualangan', 'Strategi', 'Simulasi', 'Open World', 'Shooter'];
    @endphp
    <div class="cat-mobile-quick-bar">
        <a href="javascript:void(0)" onclick="quickFilterGenre('all')" class="cat-quick-chip {{ empty($genreDipilih) ? 'active' : '' }}">
            <i class="fas fa-layer-group"></i> Semua
        </a>
        @foreach($popularGenres as $pg)
            <a href="javascript:void(0)" onclick="quickFilterGenre('{{ $pg }}')" class="cat-quick-chip {{ in_array($pg, $genreDipilih) ? 'active' : '' }}">
                {{ $pg }}
            </a>
        @endforeach
    </div>

    <div class="category-main-flex">
        
        {{-- SIDEBAR FILTER (Desktop Sticky, Mobile Bottom Drawer) --}}
        <aside class="sidebar-filter">
            
            {{-- Mobile Drawer Header --}}
            <div class="sidebar-mobile-drawer-close">
                <div class="sidebar-mobile-drawer-title">
                    <i class="fas fa-sliders" style="color: var(--c-primary);"></i> Filter Game
                </div>
                <button type="button" class="sidebar-mobile-close-btn" onclick="closeMobileFilter()" title="Tutup">&times;</button>
            </div>

            <div class="filter-header-bar desktop-only">
                <h3 class="filter-title">Filter</h3>
                <button type="button" onclick="resetFilters()" class="filter-reset-btn">Reset</button>
            </div>

            {{-- Live Search Genre --}}
            <div class="filter-search-box">
                <i class="fas fa-search"></i>
                <input type="text" id="kategoriSearchInput" onkeyup="filterByTitle()" placeholder="Cari genre..." class="filter-search-input">
            </div>

            {{-- 1. GENRE FILTER --}}
            <div style="margin-bottom: 22px;">
                <h4 class="filter-section-title">Genre</h4>
                <div class="filter-checkbox-list">
                    <label class="filter-check-item">
                        <input type="checkbox" id="semuaGenre" {{ empty($genreDipilih) ? 'checked' : '' }} onchange="toggleSemuaGenre(this)">
                        <strong style="color: var(--c-ink);">Semua Genre</strong>
                    </label>

                    @foreach($allGenres as $g)
                    <label class="filter-check-item genre-item">
                        <input type="checkbox" name="genre" value="{{ $g }}" {{ in_array($g, $genreDipilih) ? 'checked' : '' }} onchange="applyFilters()">
                        <span class="genre-label">{{ $g }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- 2. HARGA FILTER --}}
            <div style="margin-bottom: 20px;">
                <h4 class="filter-section-title">Harga</h4>
                <div class="filter-checkbox-list">
                    <label class="filter-check-item">
                        <input type="checkbox" id="semuaHarga" {{ empty($hargaDipilih) ? 'checked' : '' }} onchange="toggleSemuaHarga(this)">
                        <strong style="color: var(--c-ink);">Semua Harga</strong>
                    </label>

                    @php
                    $hargaOptions = [
                        'gratis' => 'Gratis (Free)',
                        '<100'=> 'Di bawah Rp 100.000',
                        '100-250' => 'Rp 100.000 - Rp 250.000',
                        '250-500' => 'Rp 250.000 - Rp 500.000',
                        '>500' => 'Di atas Rp 500.000'
                    ];
                    @endphp
                    @foreach($hargaOptions as $val => $label)
                    <label class="filter-check-item">
                        <input type="checkbox" name="harga" value="{{ $val }}" {{ in_array($val, $hargaDipilih) ? 'checked' : '' }} onchange="applyFilters()">
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- Mobile Drawer Bottom Buttons --}}
            <div class="sidebar-mobile-bottom-actions">
                <button type="button" class="sidebar-clear-btn" onclick="resetFilters()">Reset</button>
                <button type="button" class="sidebar-apply-btn" onclick="closeMobileFilter()">Terapkan Filter</button>
            </div>

        </aside>

        {{-- MAIN CONTENT --}}
        <div style="flex:1; width:100%; min-width:0;">
            <div style="margin-bottom:14px; display:flex; justify-content:space-between; align-items:center; font-size:13.5px; color:var(--c-mute);">
                <p style="margin:0;"><strong style="color:var(--c-primary);" id="gameCountText">{{ $games->total() }}</strong> Game ditemukan</p>
            </div>

            <div id="category-grid-container" style="transition:opacity 0.2s;">
                @include('home.partials.category_grid', ['games' => $games])
            </div>
            
        </div>
    </div>
</div>

<script>
    let filterTimeout;
    
    function openMobileFilter() {
        document.querySelector('.filter-modal-overlay').classList.add('active');
        document.querySelector('.sidebar-filter').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeMobileFilter() {
        document.querySelector('.filter-modal-overlay').classList.remove('active');
        document.querySelector('.sidebar-filter').classList.remove('active');
        document.body.style.overflow = '';
    }

    function filterByTitle() {
        let input = document.getElementById('kategoriSearchInput').value.toLowerCase();
        let labels = document.querySelectorAll('.genre-item');
        labels.forEach(label => {
            let text = label.querySelector('.genre-label').innerText.toLowerCase();
            label.style.display = text.includes(input) ? 'flex' : 'none';
        });
    }

    function toggleSemuaGenre(checkbox) {
        if (checkbox.checked) {
            document.querySelectorAll('input[name="genre"]').forEach(cb => cb.checked = false);
            applyFilters();
        } else {
            checkbox.checked = true;
        }
    }

    function toggleSemuaHarga(checkbox) {
        if (checkbox.checked) {
            document.querySelectorAll('input[name="harga"]').forEach(cb => cb.checked = false);
            applyFilters();
        } else {
            checkbox.checked = true;
        }
    }

    function resetFilters() {
        document.querySelectorAll('input[type="checkbox"]').forEach(cb => cb.checked = false);
        document.getElementById('semuaGenre').checked = true;
        document.getElementById('semuaHarga').checked = true;
        applyFilters();
    }

    function quickFilterGenre(genre) {
        document.querySelectorAll('input[name="genre"]').forEach(cb => cb.checked = false);
        if (genre !== 'all') {
            const target = document.querySelector(`input[name="genre"][value="${genre}"]`);
            if (target) target.checked = true;
            document.getElementById('semuaGenre').checked = false;
        } else {
            document.getElementById('semuaGenre').checked = true;
        }
        applyFilters();
    }

    function updateUrlAndFetch() {
        let selectedGenres = Array.from(document.querySelectorAll('input[name="genre"]:checked')).map(cb => cb.value);
        let selectedPrices = Array.from(document.querySelectorAll('input[name="harga"]:checked')).map(cb => cb.value);

        document.getElementById('semuaGenre').checked = selectedGenres.length === 0;
        document.getElementById('semuaHarga').checked = selectedPrices.length === 0;

        let url = new URL(window.location.href);
        url.searchParams.delete('genre[]');
        url.searchParams.delete('harga[]');
        url.searchParams.delete('page');

        selectedGenres.forEach(g => url.searchParams.append('genre[]', g));
        selectedPrices.forEach(p => url.searchParams.append('harga[]', p));

        window.history.pushState({}, '', url);

        let container = document.getElementById('category-grid-container');
        container.style.opacity = '0.4';
        container.style.pointerEvents = 'none';

        fetch(url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(res => res.text())
        .then(html => {
            container.innerHTML = html;
            container.style.opacity = '1';
            container.style.pointerEvents = 'auto';

            // Also update quick bar active state
            document.querySelectorAll('.cat-quick-chip').forEach(chip => chip.classList.remove('active'));
            if (selectedGenres.length === 0) {
                const allChip = document.querySelector('.cat-quick-chip');
                if (allChip) allChip.classList.add('active');
            } else if (selectedGenres.length === 1) {
                document.querySelectorAll('.cat-quick-chip').forEach(chip => {
                    if (chip.innerText.trim() === selectedGenres[0]) chip.classList.add('active');
                });
            }
        })
        .catch(err => {
            window.location.reload();
        });
    }

    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(updateUrlAndFetch, 300);
    }

    // Smooth Scroll to Top helper
    function smoothScrollToTop(duration = 600) {
        const start = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
        const startTime = performance.now();

        function scroll() {
            const currentTime = performance.now();
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const easeProgress = progress < 0.5 ? 4 * progress * progress * progress : 1 - Math.pow(-2 * progress + 2, 3) / 2;
            const position = start * (1 - easeProgress);
            
            window.scrollTo(0, position);
            document.documentElement.scrollTop = position;
            document.body.scrollTop = position;
            
            if (progress < 1) {
                requestAnimationFrame(scroll);
            } else {
                window.scrollTo(0, 0);
            }
        }
        requestAnimationFrame(scroll);
    }

    // Handle Pagination Clicks
    document.addEventListener('click', function(e) {
        const pageLink = e.target.closest('#pagination-container a');
        if (pageLink && pageLink.closest('#category-grid-container')) {
            e.preventDefault();
            let container = document.getElementById('category-grid-container');
            let paginationContainer = document.getElementById('pagination-container');
            if (!container || container.style.pointerEvents === 'none') return;
            
            container.style.pointerEvents = 'none';
            if (paginationContainer) paginationContainer.style.pointerEvents = 'none';
            
            smoothScrollToTop(600);
            
            setTimeout(() => {
                if (paginationContainer) paginationContainer.style.opacity = '0.4';
                container.style.opacity = '0.4';

                fetch(pageLink.href, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.text())
                    .then(html => {
                        container.innerHTML = html;
                        container.style.opacity = '1';
                        container.style.pointerEvents = 'auto';
                        window.history.pushState({}, '', pageLink.href);
                    })
                    .catch(() => window.location.href = pageLink.href);
            }, 650);
        }
    });
</script>
@endsection
