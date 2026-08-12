@extends('layouts.app')

@section('title', 'Kategori & Eksplorasi - GameVault')

@section('content')
<style>
    * {
        scroll-behavior: smooth !important;
    }
    
    html {
        scroll-behavior: smooth !important;
    }
    
    body {
        scroll-behavior: smooth !important;
    }
    
    @media (prefers-reduced-motion: no-preference) {
        :root {
            scroll-behavior: smooth !important;
        }
    }
    
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: var(--c-hairline-strong);
        border-radius: 0;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: var(--c-primary);
    }
    
    .mobile-only { display: none !important; }
    @media (max-width: 768px) {
        .mobile-only { display: block !important; }
        .desktop-only { display: none !important; }
        .category-main-flex { gap: 0 !important; }
        .category-main-container { padding-left: 16px !important; padding-right: 16px !important; }
    }
    
    /* Filter Modal styles via Sidebar */
    .filter-modal-overlay {
        position: fixed; top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.7); z-index: 1000;
        opacity: 0; pointer-events: none; transition: opacity 0.3s;
    }
    .filter-modal-overlay.active { opacity: 1; pointer-events: auto; }
    
    @media (max-width: 768px) {
        .sidebar-filter {
            position: fixed !important; bottom: -100% !important; left: 0 !important; right: 0 !important; top: auto !important;
            width: 100% !important; background: var(--c-surface-elevated) !important; z-index: 1001 !important;
            border-top-left-radius: 24px !important; border-top-right-radius: 24px !important;
            padding: 24px !important; transition: bottom 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
            max-height: 85vh !important; overflow-y: auto !important;
        }
        .sidebar-filter.active { bottom: 0 !important; }
        .sidebar-filter-close { display: block !important; }
    }
    .sidebar-filter-close { display: none; margin-bottom: 24px; text-align: right; }
</style>

<!-- Overlay for mobile filter -->
<div class="filter-modal-overlay" onclick="closeMobileFilter()"></div>

<div class="category-main-container" style="padding-top:40px; padding-bottom:64px; width:100%; padding-left:4%; padding-right:4%;">
    
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
        <h2 class="t-heading-lg">Eksplorasi <span class="text-green">Katalog</span></h2>
        <button class="mobile-only btn btn-primary" onclick="openMobileFilter()" style="padding: 8px 16px; border-radius: 8px; font-weight: bold;">
            <i class="fas fa-filter"></i> Filter
        </button>
    </div>

    <div class="category-main-flex" style="display:flex; gap:40px; align-items:flex-start;">
        
        {{-- SIDEBAR FILTER --}}
        <aside class="sidebar-filter custom-scroll" style="width:300px; flex-shrink:0; padding-right:16px; position:sticky; top:100px; max-height:calc(100vh - 140px); overflow-y:auto;">
            
            <div class="sidebar-filter-close">
                <button onclick="closeMobileFilter()" style="background:none; border:none; color:var(--c-ink); font-size:24px; cursor:pointer;">&times;</button>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:baseline; margin-bottom:16px;">
                <h3 style="font-size:18px; font-weight:700;">Filter</h3>
                <button onclick="resetFilters()" style="background:none; border:none; color:var(--c-primary); font-size:13px; font-weight:600; cursor:pointer;">Reset</button>
            </div>

            <div style="position:relative; margin-bottom:24px;">
                <i class="fas fa-search" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--c-body);"></i>
                <input type="text" id="kategoriSearchInput" onkeyup="filterByTitle()" placeholder="Cari genre..."
                    style="width:100%; padding:10px 12px 10px 36px; background:var(--c-surface-elevated); border:1px solid var(--c-hairline-strong); border-radius: 0; color:var(--c-ink); font-size:14px; outline:none;">
            </div>

            {{-- GENRE --}}
            <div style="margin-bottom:28px;">
                <h4 style="font-size:14px; font-weight:700; margin-bottom:12px; color:var(--c-body);">Genre</h4>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:13px; color:var(--c-ink);">
                        <input type="checkbox" id="semuaGenre" {{ empty($genreDipilih) ? 'checked' : '' }} onchange="toggleSemuaGenre(this)" style="accent-color:var(--c-primary); width:16px; height:16px;">
                        <strong>Semua Genre</strong>
                    </label>

                    @foreach($allGenres as $g)
                    <label class="genre-item" style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:13px; color:var(--c-body); transition:color 0.2s;" onmouseenter="this.style.color='var(--c-ink)'" onmouseleave="this.style.color='var(--c-body)'">
                        <input type="checkbox" name="genre" value="{{ $g }}" {{ in_array($g, $genreDipilih) ? 'checked' : '' }} onchange="applyFilters()" style="accent-color:var(--c-primary); width:16px; height:16px;">
                        <span class="genre-label">{{ $g }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- HARGA --}}
            <div style="margin-bottom:28px;">
                <h4 style="font-size:14px; font-weight:700; margin-bottom:12px; color:var(--c-body);">Harga</h4>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:13px; color:var(--c-ink);">
                        <input type="checkbox" id="semuaHarga" {{ empty($hargaDipilih) ? 'checked' : '' }} onchange="toggleSemuaHarga(this)" style="accent-color:var(--c-primary); width:16px; height:16px;">
                        <strong>Semua Harga</strong>
                    </label>

                    @php
                    $hargaOptions = [
                        'gratis' => 'Gratis',
                        '<100'=> 'Di bawah Rp 100.000',
                        '100-250' => 'Rp 100.000 - Rp 250.000',
                        '250-500' => 'Rp 250.000 - Rp 500.000',
                        '>500' => 'Di atas Rp 500.000'
                    ];
                    @endphp
                    @foreach($hargaOptions as $val => $label)
                    <label style="display:flex; align-items:center; gap:10px; cursor:pointer; font-size:13px; color:var(--c-body); transition:color 0.2s;" onmouseenter="this.style.color='var(--c-ink)'" onmouseleave="this.style.color='var(--c-body)'">
                        <input type="checkbox" name="harga" value="{{ $val }}" {{ in_array($val, $hargaDipilih) ? 'checked' : '' }} onchange="applyFilters()" style="accent-color:var(--c-primary); width:16px; height:16px;">
                        <span>{{ $label }}</span>
                    </label>
                    @endforeach
                </div>
            </div>

        </aside>

        {{-- MAIN CONTENT --}}
        <div style="flex:1; width:100%;">
            <div style="margin-bottom:16px; display:flex; justify-content:space-between; align-items:center;">
                <p style="font-size:14px; color:var(--c-body);"><strong style="color:var(--c-ink);" id="gameCountText">{{ $games->total() }}</strong> Game ditemukan</p>
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
            checkbox.checked = true; // Prevent unchecking if no others are selected
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

    function updateUrlAndFetch() {
        let selectedGenres = Array.from(document.querySelectorAll('input[name="genre"]:checked')).map(cb => cb.value);
        let selectedPrices = Array.from(document.querySelectorAll('input[name="harga"]:checked')).map(cb => cb.value);

        // Update 'Semua' checkboxes visual state
        document.getElementById('semuaGenre').checked = selectedGenres.length === 0;
        document.getElementById('semuaHarga').checked = selectedPrices.length === 0;

        // Build URL
        let url = new URL(window.location.href);
        url.searchParams.delete('genre[]');
        url.searchParams.delete('harga[]');
        url.searchParams.delete('page'); // Reset to page 1 on filter change

        selectedGenres.forEach(g => url.searchParams.append('genre[]', g));
        selectedPrices.forEach(p => url.searchParams.append('harga[]', p));

        window.history.pushState({}, '', url);

        // Fetch new data
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
        })
        .catch(err => {
            window.location.reload();
        });
    }

    function applyFilters() {
        clearTimeout(filterTimeout);
        filterTimeout = setTimeout(updateUrlAndFetch, 300);
    }

    // Smooth Scroll Function (Manual Implementation for Better Control)
    function smoothScrollToTop(duration = 800) {
        const start = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;
        const startTime = performance.now();

        function scroll() {
            const currentTime = performance.now();
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            // Easing function (easeInOutCubic) untuk smooth animation
            const easeProgress = progress < 0.5
                ? 4 * progress * progress * progress
                : 1 - Math.pow(-2 * progress + 2, 3) / 2;
            
            const position = start * (1 - easeProgress);
            
            // Force scroll to both window and document
            window.scrollTo(0, position);
            document.documentElement.scrollTop = position;
            document.body.scrollTop = position;
            
            if (progress < 1) {
                requestAnimationFrame(scroll);
            } else {
                // Ensure we're at absolute top
                window.scrollTo(0, 0);
                document.documentElement.scrollTop = 0;
                document.body.scrollTop = 0;
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
            
            // Prevent re-clicking while loading
            if (container.style.pointerEvents === 'none') return;
            
            // Disable pointer events immediately
            container.style.pointerEvents = 'none';
            if (paginationContainer) {
                paginationContainer.style.pointerEvents = 'none';
            }
            
            // Log current scroll position for debugging
            console.log('Current scroll:', window.pageYOffset);
            
            // Start smooth scroll animation to top (800ms duration for more visible animation)
            smoothScrollToTop(800);
            
            // Wait for scroll animation to complete, then load content
            setTimeout(() => {
                console.log('After scroll:', window.pageYOffset);
                
                // Add fade effect
                if (paginationContainer) {
                    paginationContainer.style.opacity = '0.4';
                }
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
            }, 850); // Wait 850ms (scroll duration + 50ms buffer)
        }
    });
</script>
@endsection
