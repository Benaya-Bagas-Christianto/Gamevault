@extends('layouts.app')

@section('title', 'Library Game')

@section('content')
<style>
/* Library Specific Styles */
.library-layout {
    display: flex;
    gap: 32px;
    align-items: flex-start;
}
.library-main {
    flex: 1;
    min-width: 0;
    padding-bottom: 48px;
}
.library-sidebar {
    width: 280px;
    flex-shrink: 0;
    background: rgba(0,0,0,0.4);
    backdrop-filter: blur(8px);
    padding: 24px;
    border-radius: 0;
    border: 1px solid var(--c-hairline);
    position: sticky;
    top: 100px;
    height: fit-content;
    margin-bottom: 48px;
}

/* Header & Tabs */
.library-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 24px;
}
.library-header h1 {
    font-size: 2.2rem;
    font-weight: 800;
    margin: 0;
}
.library-tabs {
    display: flex;
    align-items: center;
    gap: 24px;
    border-bottom: 1px solid var(--c-hairline);
    margin-bottom: 24px;
    padding-bottom: 8px;
}
.library-tab {
    font-size: 1rem;
    color: var(--c-mute);
    text-decoration: none;
    font-weight: 600;
    position: relative;
    padding-bottom: 8px;
}
.library-tab.active {
    color: var(--c-ink);
}
.library-tab.active::after {
    content: '';
    position: absolute;
    bottom: -9px;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--c-ink);
    border-radius: 0;
}

/* Controls */
.library-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.library-sort {
    color: var(--c-mute);
    font-size: 0.9rem;
    cursor: pointer;
}
.library-sort span {
    color: var(--c-ink);
    font-weight: 600;
}
.library-view-toggles {
    display: flex;
    gap: 8px;
}
.library-view-btn {
    background: var(--c-surface-soft);
    border: none;
    color: var(--c-mute);
    width: 32px;
    height: 32px;
    border-radius: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}
.library-view-btn.active {
    background: rgba(255,255,255,0.1);
    color: var(--c-ink);
}

/* Grid & Cards */
.epic-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}
@media(max-width: 1200px) { .epic-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 768px) { .epic-grid { grid-template-columns: repeat(1, 1fr); } }

.epic-card {
    display: flex;
    flex-direction: column;
    gap: 12px;
    position: relative;
    min-width: 0;
}
.epic-cover-wrap {
    aspect-ratio: 16/9;
    border-radius: 0;
    background: var(--c-surface-soft);
    position: relative;
    box-shadow: 0 4px 10px rgba(0,0,0,0.3);
}
.epic-cover-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    border-radius: 0;
    transition: filter 0.2s, transform 0.2s;
}
.epic-card:hover .epic-cover-wrap img {
    filter: brightness(1.1);
}

/* Three dots menu overlay */
.epic-menu-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 28px;
    height: 28px;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(4px);
    border: none;
    border-radius: 0;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
    z-index: 10;
}
.epic-card:hover .epic-menu-btn {
    opacity: 1;
}
.epic-dropdown {
    position: absolute;
    top: 40px;
    right: 8px;
    background: var(--c-surface-elevated);
    border: 1px solid var(--c-hairline-strong);
    border-radius: 0;
    padding: 8px 0;
    min-width: 150px;
    z-index: 20;
    box-shadow: 0 10px 25px rgba(0,0,0,0.5);
    display: none;
}
.epic-dropdown.show {
    display: block;
}
.epic-dropdown-item {
    display: block;
    padding: 8px 16px;
    color: var(--c-ink);
    text-decoration: none;
    font-size: 0.85rem;
    cursor: pointer;
}
.epic-dropdown-item:hover {
    background: var(--c-surface-soft);
}

/* Card Info */
.epic-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
    min-width: 0;
}
.epic-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: var(--c-ink);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.epic-subtext {
    font-size: 0.75rem;
    color: var(--c-mute);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.epic-action {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    color: var(--c-mute);
    margin-top: 4px;
    cursor: pointer;
    border: none;
    background: none;
    padding: 0;
    white-space: nowrap;
}
.epic-action:hover {
    color: var(--c-ink);
}

/* Sidebar */
.epic-sidebar-title {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 16px;
    color: var(--c-ink);
}
.epic-search {
    width: 100%;
    background: var(--c-surface-soft);
    border: 1px solid var(--c-hairline);
    padding: 10px 12px 10px 36px;
    border-radius: 0;
    color: var(--c-ink);
    font-size: 0.9rem;
}
.epic-search-wrap {
    position: relative;
    margin-bottom: 24px;
}
.epic-search-wrap i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--c-mute);
    font-size: 0.9rem;
}
.epic-filter-group {
    border-top: 1px solid var(--c-hairline);
    padding: 16px 0;
}
.epic-filter-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--c-mute);
    font-size: 0.9rem;
    cursor: pointer;
}
.epic-filter-header:hover {
    color: var(--c-ink);
}
.epic-filter-header i {
    transition: transform 0.2s;
}
.epic-filter-header.open i {
    transform: rotate(180deg);
}
.epic-filter-content {
    display: none;
    padding-top: 12px;
    padding-bottom: 4px;
    color: var(--c-mute);
    font-size: 0.85rem;
}
.epic-filter-content.open {
    display: block;
}
.epic-filter-item {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 8px;
    cursor: pointer;
}
.epic-filter-item:hover {
    color: var(--c-ink);
}
.epic-filter-item input[type="checkbox"] {
    accent-color: var(--c-primary);
}

@media(max-width: 900px) {
    .library-layout { flex-direction: column; }
    .library-sidebar { width: 100%; }
}
</style>

<div class="container section">
    <div class="library-header">
        <h1>Library</h1>
        <i class="fas fa-sync-alt" style="color:var(--c-mute); cursor:pointer; font-size:1.1rem;" onclick="location.reload()"></i>
    </div>
    
    <div class="library-tabs">
        <a href="#" class="library-tab active" onclick="switchTab(this, 'All', event)">All</a>
        <a href="#" class="library-tab" onclick="switchTab(this, 'Favorites', event)">Favorites</a>
        <a href="#" class="library-tab"><i class="fas fa-plus"></i></a>
    </div>

    <div class="library-layout">
        <!-- Main Content -->
        <div class="library-main">
            <div class="library-controls">
                <div class="library-sort">
                    Sort by: <span>Recently Purchased <i class="fas fa-chevron-down" style="font-size:0.7rem; margin-left:4px;"></i></span>
                </div>
            </div>

            @if($libraryItems->isEmpty())
                <div class="card text-center" style="padding: 64px 20px; background:var(--c-surface-soft); border:1px dashed var(--c-hairline);">
                    <div style="font-size:48px;color:var(--c-hairline-strong);margin-bottom:16px"><i class="fa fa-gamepad"></i></div>
                    <h2 class="t-heading-md mb-sm">Library kamu kosong</h2>
                    <p class="text-mute mb-lg">Beli game sekarang dan koleksi ribuan judul menarik!</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">Eksplor Toko</a>
                </div>
            @else
                <div class="epic-grid">
                    @foreach($libraryItems as $item)
                        @php 
                            $game = $item->game; 
                            $detail = $item->transactionDetail;
                            $transaction = $detail->transaction;
                            
                            $gameYear = $game->release_date ? $game->release_date->format('Y') : '';
                            $gamePrice = $game->price;
                            $priceTier = 'free';
                            if ($gamePrice > 0 && $gamePrice < 100000) $priceTier = 'under_100k';
                            elseif ($gamePrice >= 100000) $priceTier = 'over_100k';
                            
                            $gameType = strtolower($game->type ?? 'base_game');
                            if($gameType == 'game') $gameType = 'base_game';
                        @endphp
                        <div class="epic-card"
                             data-genres="{{ json_encode($game->genre ?? []) }}"
                             data-type="{{ $gameType }}"
                             data-year="{{ $gameYear }}"
                             data-price-tier="{{ $priceTier }}"
                             data-title="{{ htmlspecialchars(strtolower($game->title)) }}"
                             data-favorite="{{ $item->is_favorite ? '1' : '0' }}">
                            <div class="epic-cover-wrap">
                                <a href="{{ route('game.show', $game->slug) }}">
                                    @if($game->cover_image)
                                        <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}">
                                    @else
                                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; background:#222; border-radius: 0;"><i class="fa fa-image" style="font-size:2rem;color:#555;"></i></div>
                                    @endif
                                </a>
                                
                                <button class="epic-menu-btn" onclick="toggleDropdown('dropdown-{{ $detail->id }}', event)">
                                    <i class="fas fa-ellipsis-h"></i>
                                </button>
                                
                                <div id="dropdown-{{ $detail->id }}" class="epic-dropdown">
                                    <div class="epic-dropdown-item" onclick="openLicensePinModal('{{ $item->id }}')"><i class="fas fa-key me-2"></i> Lihat License Key</div>
                                    <div class="epic-dropdown-item fav-btn-{{ $item->id }}" onclick="toggleFavorite('{{ $item->id }}')">
                                        @if($item->is_favorite)
                                            <i class="fas fa-star text-warning me-2"></i> Hapus dari Favorit
                                        @else
                                            <i class="far fa-star me-2"></i> Tambah ke Favorit
                                        @endif
                                    </div>
                                    <div class="epic-dropdown-item" onclick="openInvoiceModal('{{ $transaction->id }}')"><i class="fas fa-file-invoice me-2"></i> Download Invoice</div>
                                    @if($detail->canRefund())
                                        <a href="{{ route('refund.show', $detail->id) }}" class="epic-dropdown-item text-warning"><i class="fas fa-undo me-2"></i> Request Refund</a>
                                    @endif
                                    <a href="{{ route('game.show', $game->slug) }}" class="epic-dropdown-item"><i class="fas fa-store me-2"></i> Ke Halaman Toko</a>
                                </div>
                            </div>
                            
                            <div class="epic-info">
                                <div class="epic-title" title="{{ $game->title }}">{{ $game->title }}</div>
                                <div class="epic-subtext">Dibeli: {{ $transaction->paid_at?->format('d M Y') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Sidebar Filters -->
        <div class="library-sidebar">
            <h2 class="epic-sidebar-title">Filters</h2>
            
            <div class="epic-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" id="filterSearch" class="epic-search" placeholder="Title" onkeyup="applyFilters()">
            </div>

            <div class="epic-filter-group">
                <div class="epic-filter-header" onclick="toggleFilter(this)">
                    <span>Genre</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="epic-filter-content">
                    @php
                        $allGenres = [
                            'Aksi', 'Akses Dini', 'Animasi dan Pemodelan', 'Balapan', 
                            'Desain & Ilustrasi', 'F2P', 'Indie', 'Kasual', 'Kekerasan', 
                            'MMO', 'Olahraga', 'Pengeditan Foto', 'Petualangan', 'RPG', 
                            'Simulasi', 'Strategi', 'Utilitas'
                        ];
                    @endphp
                    @foreach($allGenres as $genre)
                        <label class="epic-filter-item">
                            <input type="checkbox" class="filter-cb" data-group="genre" value="{{ htmlspecialchars($genre) }}" onchange="applyFilters()"> {{ $genre }}
                        </label>
                    @endforeach
                </div>
            </div>
            <div class="epic-filter-group">
                <div class="epic-filter-header" onclick="toggleFilter(this)">
                    <span>Kategori Game</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="epic-filter-content">
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="type" value="base_game" onchange="applyFilters()"> Base Game</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="type" value="dlc" onchange="applyFilters()"> DLC / Expansion</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="type" value="bundle" onchange="applyFilters()"> Bundle</label>
                </div>
            </div>
            <div class="epic-filter-group">
                <div class="epic-filter-header" onclick="toggleFilter(this)">
                    <span>Tahun Rilis</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="epic-filter-content">
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="year" value="2024" onchange="applyFilters()"> 2024</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="year" value="2023" onchange="applyFilters()"> 2023</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="year" value="older" onchange="applyFilters()"> 2022 & Sebelumnya</label>
                </div>
            </div>
            <div class="epic-filter-group" style="border-bottom: 1px solid var(--c-hairline);">
                <div class="epic-filter-header" onclick="toggleFilter(this)">
                    <span>Tingkat Harga</span>
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="epic-filter-content">
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="price" value="free" onchange="applyFilters()"> Gratis (Free to Play)</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="price" value="under_100k" onchange="applyFilters()"> Di Bawah Rp 100.000</label>
                    <label class="epic-filter-item"><input type="checkbox" class="filter-cb" data-group="price" value="over_100k" onchange="applyFilters()"> Di Atas Rp 100.000</label>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Invoice PIN Modal -->
<div id="invoicePinModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--c-surface-elevated); padding:var(--sp-xl); border-radius: 0; width:100%; max-width:400px; border:1px solid var(--c-hairline-strong);">
        <h3 style="margin-top:0; color:var(--c-ink); font-size:1.2rem; font-weight:700;">Masukkan PIN Keamanan</h3>
        <p style="color:var(--c-mute); font-size:0.9rem; margin-bottom:var(--sp-lg);">Silakan masukkan 6 digit PIN akun Anda untuk mengunduh invoice ini.</p>
        <form id="invoicePinForm">
            @csrf
            <div style="margin-bottom:var(--sp-lg);">
                <input type="password" id="pinInput" name="pin" required maxlength="6" pattern="\d{6}" placeholder="••••••" style="width:100%; padding:var(--sp-md); background:var(--c-canvas); border:1px solid var(--c-hairline); color:var(--c-ink); border-radius: 0; font-size:1.2rem; text-align:center; letter-spacing:8px;">
                <p id="pinError" style="color:var(--c-error); font-size:0.85rem; margin-top:var(--sp-sm); display:none;"></p>
            </div>
            <div style="display:flex; gap:var(--sp-md); justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeInvoiceModal()" style="padding:var(--sp-sm) var(--sp-lg);">Batal</button>
                <button type="submit" class="btn btn-primary" id="downloadBtn" style="padding:var(--sp-sm) var(--sp-lg);">Download</button>
            </div>
        </form>
    </div>
</div>

<!-- License PIN Modal -->
<div id="licensePinModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--c-surface-elevated); padding:var(--sp-xl); border-radius: 0; width:100%; max-width:400px; border:1px solid var(--c-hairline-strong);">
        <h3 style="margin-top:0; color:var(--c-ink); font-size:1.2rem; font-weight:700;">Masukkan PIN Lisensi</h3>
        <p style="color:var(--c-mute); font-size:0.9rem; margin-bottom:var(--sp-lg);">Silakan masukkan 6 digit PIN akun Anda untuk melihat license key ini.</p>
        <form id="licensePinForm">
            @csrf
            <div style="margin-bottom:var(--sp-lg);">
                <input type="password" id="licensePinInput" name="pin" required maxlength="6" pattern="\d{6}" placeholder="••••••" style="width:100%; padding:var(--sp-md); background:var(--c-canvas); border:1px solid var(--c-hairline); color:var(--c-ink); border-radius: 0; font-size:1.2rem; text-align:center; letter-spacing:8px;">
                <p id="licensePinError" style="color:var(--c-error); font-size:0.85rem; margin-top:var(--sp-sm); display:none;"></p>
            </div>
            <div style="display:flex; gap:var(--sp-md); justify-content:flex-end;">
                <button type="button" class="btn btn-outline" onclick="closeLicensePinModal()" style="padding:var(--sp-sm) var(--sp-lg);">Batal</button>
                <button type="submit" class="btn btn-primary" id="viewLicenseBtn" style="padding:var(--sp-sm) var(--sp-lg);">Lihat</button>
            </div>
        </form>
    </div>
</div>

<!-- License Key Modal -->
<div id="licenseModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:var(--c-surface-elevated); padding:var(--sp-xl); border-radius: 0; width:100%; max-width:400px; border:1px solid var(--c-hairline-strong); text-align:center;">
        <h3 style="margin-top:0; color:var(--c-ink); font-size:1.2rem; font-weight:700;">License Key Anda</h3>
        <p style="color:var(--c-mute); font-size:0.9rem; margin-bottom:var(--sp-xl);">Gunakan kunci ini untuk mengaktifkan game di platform terkait.</p>
        
        <div id="licenseKeyDisplay" style="background:var(--c-canvas); border:1px dashed var(--c-primary); color:var(--c-primary); padding:var(--sp-lg); font-family:monospace; font-size:1.1rem; letter-spacing:2px; font-weight:800; border-radius: 0; margin-bottom:var(--sp-xl); user-select:all;">
            XXXXX-XXXXX-XXXXX
        </div>
        
        <button type="button" class="btn btn-primary w-full" onclick="closeLicenseModal()">Tutup</button>
    </div>
</div>

<script>
// Invoice Modal Logic
let currentTransactionId = null;

function openInvoiceModal(transactionId) {
    currentTransactionId = transactionId;
    const modal = document.getElementById('invoicePinModal');
    const pinInput = document.getElementById('pinInput');
    const pinError = document.getElementById('pinError');
    
    pinInput.value = '';
    pinError.style.display = 'none';
    pinError.textContent = '';
    
    modal.style.display = 'flex';
    setTimeout(() => pinInput.focus(), 100);
}

function closeInvoiceModal() {
    document.getElementById('invoicePinModal').style.display = 'none';
    currentTransactionId = null;
}

// Handle form submission with AJAX
document.getElementById('invoicePinForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const pinInput = document.getElementById('pinInput');
    const pinError = document.getElementById('pinError');
    const downloadBtn = document.getElementById('downloadBtn');
    const pin = pinInput.value;
    
    // Validate PIN format
    if (pin.length !== 6 || !/^\d{6}$/.test(pin)) {
        pinError.textContent = 'PIN harus 6 digit angka.';
        pinError.style.display = 'block';
        return;
    }
    
    // Disable button and show loading
    downloadBtn.disabled = true;
    downloadBtn.textContent = 'Memproses...';
    pinError.style.display = 'none';
    
    try {
        const formData = new FormData();
        formData.append('pin', pin);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        const response = await fetch(`/invoice/${currentTransactionId}/download`, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        if (response.ok && response.headers.get('content-type').includes('application/pdf')) {
            // Success - Download PDF
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `invoice-${currentTransactionId}.pdf`;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);
            
            // Close modal and show success
            closeInvoiceModal();
            showSweetAlert('Berhasil!', 'Invoice berhasil diunduh!', 'success');
        } else {
            // Error - Show message
            const data = await response.json();
            pinError.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
            pinError.style.display = 'block';
            pinInput.value = '';
            pinInput.focus();
        }
    } catch (error) {
        console.error('Error:', error);
        pinError.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
        pinError.style.display = 'block';
    } finally {
        // Re-enable button
        downloadBtn.disabled = false;
        downloadBtn.textContent = 'Download';
    }
});

// License Modal Logic
function openLicenseModal(key) {
    const modal = document.getElementById('licenseModal');
    document.getElementById('licenseKeyDisplay').innerText = key;
    modal.style.display = 'flex';
}

function closeLicenseModal() {
    document.getElementById('licenseModal').style.display = 'none';
}

// Dropdown Logic
function toggleDropdown(id, event) {
    event.preventDefault();
    event.stopPropagation();
    
    const dropdown = document.getElementById(id);
    const isShowing = dropdown.classList.contains('show');
    
    // Close all other dropdowns
    document.querySelectorAll('.epic-dropdown').forEach(el => {
        el.classList.remove('show');
    });
    
    if (!isShowing) {
        dropdown.classList.add('show');
    }
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (!e.target.closest('.epic-menu-btn') && !e.target.closest('.epic-dropdown')) {
        document.querySelectorAll('.epic-dropdown').forEach(el => el.classList.remove('show'));
    }
});

// Toggle Sidebar Filters
function toggleFilter(headerEl) {
    headerEl.classList.toggle('open');
    const content = headerEl.nextElementSibling;
    if (content) {
        content.classList.toggle('open');
    }
}

let currentTab = 'All';

function switchTab(el, tabName, event) {
    if(event) event.preventDefault();
    document.querySelectorAll('.library-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    currentTab = tabName;
    applyFilters();
}

async function toggleFavorite(libraryId) {
    try {
        const formData = new FormData();
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        const response = await fetch(`/library/${libraryId}/favorite`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const data = await response.json();
        if(data.success) {
            document.querySelectorAll(`.fav-btn-${libraryId}`).forEach(btn => {
                if(data.is_favorite) {
                    btn.innerHTML = '<i class="fas fa-star text-warning me-2"></i> Hapus dari Favorit';
                } else {
                    btn.innerHTML = '<i class="far fa-star me-2"></i> Tambah ke Favorit';
                }
            });
            const card = document.querySelector(`.fav-btn-${libraryId}`).closest('.epic-card');
            if(card) {
                card.dataset.favorite = data.is_favorite ? '1' : '0';
            }
            applyFilters();
            showSweetAlert('Berhasil!', data.is_favorite ? 'Game ditambahkan ke favorit.' : 'Game dihapus dari favorit.', 'success');
        }
    } catch(e) {
        console.error(e);
        showSweetAlert('Error!', 'Gagal mengubah status favorit.', 'error');
    }
}

let currentLibraryId = null;
function openLicensePinModal(libraryId) {
    currentLibraryId = libraryId;
    document.getElementById('licensePinModal').style.display = 'flex';
    document.getElementById('licensePinInput').value = '';
    document.getElementById('licensePinError').style.display = 'none';
    setTimeout(() => document.getElementById('licensePinInput').focus(), 100);
}
function closeLicensePinModal() {
    document.getElementById('licensePinModal').style.display = 'none';
    currentLibraryId = null;
}

document.getElementById('licensePinForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const pin = document.getElementById('licensePinInput').value;
    const pinError = document.getElementById('licensePinError');
    const viewBtn = document.getElementById('viewLicenseBtn');
    
    if (pin.length !== 6 || !/^\d{6}$/.test(pin)) {
        pinError.textContent = 'PIN harus 6 digit angka.';
        pinError.style.display = 'block';
        return;
    }
    
    viewBtn.disabled = true;
    viewBtn.textContent = 'Memproses...';
    pinError.style.display = 'none';
    
    try {
        const formData = new FormData();
        formData.append('pin', pin);
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        
        const response = await fetch(`/library/${currentLibraryId}/license`, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        });
        
        const data = await response.json();
        if (data.success) {
            closeLicensePinModal();
            openLicenseModal(data.license_key);
        } else {
            pinError.textContent = data.message || 'Terjadi kesalahan.';
            pinError.style.display = 'block';
            document.getElementById('licensePinInput').value = '';
            document.getElementById('licensePinInput').focus();
        }
    } catch (error) {
        pinError.textContent = 'Terjadi kesalahan jaringan.';
        pinError.style.display = 'block';
    } finally {
        viewBtn.disabled = false;
        viewBtn.textContent = 'Lihat';
    }
});

// Filtering Logic
function applyFilters() {
    const searchStr = document.getElementById('filterSearch').value.toLowerCase();
    
    // Get checked values
    const checkedBoxes = Array.from(document.querySelectorAll('.filter-cb:checked'));
    const filters = {
        genre: checkedBoxes.filter(cb => cb.dataset.group === 'genre').map(cb => cb.value.toLowerCase()),
        type: checkedBoxes.filter(cb => cb.dataset.group === 'type').map(cb => cb.value.toLowerCase()),
        year: checkedBoxes.filter(cb => cb.dataset.group === 'year').map(cb => cb.value),
        price: checkedBoxes.filter(cb => cb.dataset.group === 'price').map(cb => cb.value)
    };

    const cards = document.querySelectorAll('.epic-card');
    
    cards.forEach(card => {
        let show = true;
        
        // Title Search
        const title = card.dataset.title || '';
        if (searchStr && !title.includes(searchStr)) show = false;
        
        // Genre Filter (card needs to have AT LEAST ONE of the selected genres)
        if (show && filters.genre.length > 0) {
            try {
                const cardGenres = JSON.parse(card.dataset.genres || '[]').map(g => g.toLowerCase());
                if (!filters.genre.some(g => cardGenres.includes(g))) show = false;
            } catch(e) { show = false; }
        }
        
        // Type Filter
        if (show && filters.type.length > 0) {
            if (!filters.type.includes(card.dataset.type)) show = false;
        }
        
        // Year Filter
        if (show && filters.year.length > 0) {
            const y = parseInt(card.dataset.year);
            let yearMatch = false;
            if (filters.year.includes('2024') && y === 2024) yearMatch = true;
            if (filters.year.includes('2023') && y === 2023) yearMatch = true;
            if (filters.year.includes('older') && y <= 2022) yearMatch = true;
            if (!yearMatch) show = false;
        }
        
        // Price Filter
        if (show && filters.price.length > 0) {
            if (!filters.price.includes(card.dataset.priceTier)) show = false;
        }

        // Favorites Tab
        if (show && currentTab === 'Favorites') {
            if (card.dataset.favorite !== '1') show = false;
        }
        
        card.style.display = show ? 'flex' : 'none';
    });
}
</script>
@endsection
