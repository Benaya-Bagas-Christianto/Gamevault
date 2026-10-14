@extends('layouts.app')

@section('title', 'Wishlist Saya')

@section('content')
<style>
  /* Base Wishlist Styles */
  .wishlist-user-header {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 24px;
      flex-wrap: wrap;
  }
  .wishlist-user-avatar {
      width: 56px;
      height: 56px;
      background: var(--c-surface-soft);
      border: 1px solid var(--c-hairline);
      border-radius: 0;
      overflow: hidden;
      flex-shrink: 0;
  }
  .wishlist-user-title {
      margin: 0;
      letter-spacing: 1px;
      text-transform: uppercase;
      font-size: 1.8rem;
      font-weight: 800;
      color: var(--c-ink);
  }
  .wishlist-count-pill {
      background: rgba(118,185,0,0.15);
      border: 1px solid var(--c-primary);
      color: var(--c-primary);
      font-size: 0.75rem;
      font-weight: 700;
      padding: 3px 10px;
      letter-spacing: 1px;
      text-transform: uppercase;
      border-radius: 0;
      margin-left: 8px;
      display: inline-block;
      vertical-align: middle;
  }

  /* Controls */
  .wishlist-controls {
      display: flex;
      gap: 8px;
      margin-bottom: 20px;
      flex-wrap: wrap;
      position: relative;
      align-items: stretch;
  }
  .wishlist-search-input {
      flex: 1;
      background: var(--c-surface-soft);
      border: 1px solid rgba(255,255,255,0.1);
      color: var(--c-body);
      padding: 10px 14px;
      border-radius: 0;
      font-size: 0.85rem;
      outline: none;
      min-width: 200px;
      transition: border-color 0.2s;
  }
  .wishlist-search-input:focus {
      border-color: var(--c-primary);
  }
  .wishlist-ctrl-btn {
      background: var(--c-surface-soft);
      border: 1px solid rgba(255,255,255,0.1);
      color: var(--c-body);
      padding: 8px 16px;
      border-radius: 0;
      font-size: 0.85rem;
      cursor: pointer;
      height: 100%;
      transition: background 0.2s, border-color 0.2s;
      display: flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
  }
  .wishlist-ctrl-btn:hover {
      background: rgba(255,255,255,0.1);
      border-color: rgba(255,255,255,0.2);
  }

  /* Wishlist Item Card */
  .wishlist-item-wrapper {
      display: flex;
      align-items: stretch;
      background: var(--c-surface-soft);
      margin-bottom: 16px;
      position: relative;
      border: 1px solid var(--c-hairline);
      border-radius: 0;
      transition: border-color 0.2s;
  }
  .wishlist-item-wrapper:hover {
      border-color: rgba(255,255,255,0.2);
  }
  .wishlist-checkbox-wrap {
      flex-shrink: 0;
      padding: 16px 0 16px 16px;
      display: flex;
      align-items: flex-start;
  }
  .wishlist-row {
      display: flex;
      flex: 1;
      min-width: 0;
  }
  .wishlist-row-cover {
      width: 260px;
      flex-shrink: 0;
      display: block;
      aspect-ratio: 460/215;
      align-self: flex-start;
      padding: 16px;
      background: transparent;
      position: relative;
  }
  .wishlist-row-cover img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      border-radius: 0;
      display: block;
  }
  .wishlist-row-body {
      flex: 1;
      padding: 16px 16px 16px 0;
      display: flex;
      justify-content: space-between;
      min-width: 0;
  }
  .wishlist-row-info {
      display: flex;
      flex-direction: column;
      flex: 1;
      min-width: 0;
      padding-right: 16px;
  }
  .wishlist-row-info h3 {
      font-size: 1.25rem;
      font-weight: 700;
      margin: 0 0 6px 0;
      color: var(--c-ink);
  }
  .wishlist-row-actions {
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      align-items: flex-end;
      width: 250px;
      flex-shrink: 0;
      padding-left: 16px;
      border-left: 1px solid rgba(255,255,255,0.05);
  }
  .wishlist-tag {
      background: rgba(255,255,255,0.05);
      border: 1px solid rgba(255,255,255,0.1);
      color: var(--c-mute);
      padding: 2px 8px;
      font-size: 0.75rem;
      border-radius: 0;
      margin-right: 4px;
      margin-bottom: 4px;
      display: inline-block;
      cursor: pointer;
      font-weight: 600;
      text-transform: uppercase;
  }
  .wishlist-tag:hover {
      background: rgba(255,255,255,0.1);
      color: var(--c-ink);
  }
  .wishlist-meta {
      font-size: 0.8rem;
      margin-top: auto;
      display: flex;
      flex-direction: column;
      gap: 6px;
  }
  .wishlist-meta-row {
      display: flex;
      align-items: center;
      gap: 16px;
      font-size: 0.8rem;
  }
  .wishlist-meta-label {
      color: var(--c-mute);
      opacity: 0.8;
      text-transform: uppercase;
      font-weight: 700;
      font-size: 0.72rem;
      letter-spacing: 0.5px;
  }
  .wishlist-price-btn {
      display: flex;
      align-items: stretch;
      margin-top: auto;
      height: 38px;
  }
  .wishlist-price-block {
      display: flex;
      align-items: stretch;
      background: #000;
      border: 1px solid var(--c-hairline);
      border-radius: 0;
      overflow: hidden;
  }

  /* Custom Checkbox */
  .wishlist-custom-cb {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 22px;
      height: 22px;
      background: rgba(255,255,255,0.05);
      border: 1.5px solid rgba(255,255,255,0.25);
      cursor: pointer;
      border-radius: 0;
      transition: all 0.2s;
  }

  /* Mobile Sheet Backdrop */
  .wishlist-mobile-backdrop {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.7);
      backdrop-filter: blur(4px);
      z-index: 49;
  }
  .wishlist-mobile-backdrop.show {
      display: block;
  }

  /* Mobile Specific Styles */
  @media (max-width: 768px) {
      .wishlist-user-header {
          gap: 12px;
          margin-bottom: 16px;
      }
      .wishlist-user-avatar {
          width: 44px;
          height: 44px;
      }
      .wishlist-user-title {
          font-size: 1.25rem;
          letter-spacing: 0.5px;
      }
      .wishlist-search-input {
          width: 100%;
          flex: none !important;
      }
      .wishlist-btn-options-container {
          flex: 1;
      }
      .wishlist-btn-sort-container {
          flex: 1.3;
      }
      .wishlist-btn-share {
          flex-shrink: 0;
      }

      /* Options & Sort Bottom Sheets on Mobile */
      #wishlist-menu-options {
          position: fixed !important;
          top: auto !important;
          bottom: 0 !important;
          left: 0 !important;
          right: 0 !important;
          width: 100% !important;
          max-width: 100vw !important;
          background: #0c0c0c !important;
          border-top: 2px solid var(--c-primary) !important;
          border-left: none !important;
          border-right: none !important;
          border-bottom: none !important;
          padding: 20px 16px 32px 16px !important;
          z-index: 100 !important;
          box-shadow: 0 -10px 40px rgba(0,0,0,0.9) !important;
          box-sizing: border-box !important;
          border-radius: 0 !important;
      }
      #wishlist-menu-options > div {
          flex-direction: column !important;
          gap: 20px !important;
      }
      #wishlist-menu-sort {
          position: fixed !important;
          top: auto !important;
          bottom: 0 !important;
          left: 0 !important;
          right: 0 !important;
          width: 100% !important;
          max-width: 100vw !important;
          background: #0c0c0c !important;
          border-top: 2px solid var(--c-primary) !important;
          border-left: none !important;
          border-right: none !important;
          border-bottom: none !important;
          padding: 12px 0 32px 0 !important;
          z-index: 100 !important;
          box-shadow: 0 -10px 40px rgba(0,0,0,0.9) !important;
          box-sizing: border-box !important;
          border-radius: 0 !important;
      }

      /* Card Redesign for Mobile */
      .wishlist-item-wrapper {
          flex-direction: column;
          margin-bottom: 12px;
          border: 1px solid var(--c-hairline);
          background: var(--c-surface-soft);
          padding: 0;
      }
      .wishlist-checkbox-wrap {
          position: absolute;
          top: 8px;
          left: 8px;
          padding: 0 !important;
          z-index: 5;
      }
      .wishlist-custom-cb {
          width: 24px;
          height: 24px;
          background: rgba(0,0,0,0.7);
          border: 1.5px solid rgba(255,255,255,0.4);
      }
      .wishlist-row {
          flex-direction: column;
          padding: 0;
      }
      .wishlist-row-cover {
          width: 100%;
          aspect-ratio: 460/215;
          padding: 0 !important;
          border-bottom: 1px solid var(--c-hairline);
      }
      .wishlist-row-body {
          flex-direction: column;
          gap: 12px;
          padding: 12px 14px 14px 14px !important;
      }
      .wishlist-row-info {
          padding-right: 0;
      }
      .wishlist-row-info h3 {
          font-size: 1.05rem;
          line-height: 1.3;
          margin-bottom: 6px;
      }
      .wishlist-row-actions {
          width: 100%;
          border-left: none;
          padding-left: 0;
          align-items: stretch;
          gap: 10px;
      }
      .added-on-text {
          font-size: 0.72rem;
      }
      .remove-wishlist-btn-wrapper {
          position: absolute;
          top: 8px;
          right: 8px;
          z-index: 5;
      }
      .remove-wishlist-btn {
          background: rgba(0,0,0,0.75) !important;
          border: 1px solid rgba(220,38,38,0.5) !important;
          color: #f87171 !important;
          padding: 5px 10px !important;
          font-size: 0.75rem !important;
          font-weight: 600 !important;
          display: flex !important;
          align-items: center;
          gap: 4px;
      }
      .remove-wishlist-btn .icon-remove {
          color: #f87171 !important;
          font-size: 0.8rem;
      }
      .wishlist-price-btn {
          width: 100%;
          margin-top: 4px;
          height: 40px;
      }
      .wishlist-price-block {
          flex: 1;
      }
      .cart-btn, .wishlist-price-btn a {
          flex: 1.2;
          display: flex;
          align-items: center;
          justify-content: center;
          font-size: 0.85rem !important;
          padding: 0 12px !important;
      }
      .wishlist-tag {
          font-size: 0.7rem;
          padding: 1px 6px;
      }

      /* Mobile Bulk Bar */
      #wishlist-bulk-bar {
          position: fixed !important;
          top: auto !important;
          bottom: 20px !important;
          left: 12px !important;
          right: 12px !important;
          width: auto !important;
          z-index: 9999 !important;
          box-shadow: 0 8px 30px rgba(0,0,0,0.95) !important;
          padding: 10px 14px !important;
          flex-direction: column !important;
          gap: 8px !important;
      }
      #wishlist-bulk-bar > div {
          width: 100% !important;
          justify-content: space-between !important;
      }
      #wishlist-bulk-bar button {
          padding: 8px 12px !important;
          font-size: 0.8rem !important;
      }
  }
</style>

<div class="container" style="padding-top: 12px; padding-bottom: 48px;">
  {{-- Header --}}
  <div class="wishlist-user-header">
      <div class="wishlist-user-avatar">
          <img src="{{ auth()->user()->avatar_url }}" alt="{{ auth()->user()->name }}" style="width:100%;height:100%;object-fit:cover;">
      </div>
      <div>
          <h1 class="wishlist-user-title">
              {{ auth()->user()->username ?? auth()->user()->name }}'S WISHLIST
              <span class="wishlist-count-pill" id="wishlistHeaderCount">{{ $wishlistItems->count() }} GAME</span>
          </h1>
      </div>
  </div>

  {{-- Wishlist Bulk Action Bar --}}
  <div id="wishlist-bulk-bar" style="display:none;position:sticky;top:70px;z-index:100;background:#0e1f00;border:1px solid var(--c-primary);padding:12px 20px;margin-bottom:16px;border-radius:0;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:10px;">
          <span id="wishlist-selected-label" style="font-size:0.85rem;color:var(--c-primary);font-weight:700;">0 item dipilih</span>
          <button onclick="wishlistSelectAll()" style="background:rgba(118,185,0,0.15);border:1px solid var(--c-primary);color:var(--c-primary);font-size:0.75rem;font-weight:600;padding:4px 10px;cursor:pointer;border-radius:0;">Pilih Semua</button>
          <button onclick="wishlistDeselectAll()" style="background:rgba(255,255,255,0.05);border:1px solid var(--c-hairline);color:var(--c-mute);font-size:0.75rem;padding:4px 10px;cursor:pointer;border-radius:0;">Batal</button>
      </div>
      <div style="display:flex;align-items:center;gap:8px;">
          <button onclick="wishlistBulkAddCart()" style="background:var(--c-primary);color:#000;font-size:0.82rem;font-weight:700;padding:8px 16px;border:none;cursor:pointer;border-radius:0;"><i class="fas fa-shopping-cart me-1"></i>+ Keranjang</button>
          <button onclick="wishlistBulkRemove()" style="background:rgba(220,38,38,0.15);border:1px solid #dc2626;color:#f87171;font-size:0.82rem;font-weight:600;padding:8px 16px;cursor:pointer;border-radius:0;"><i class="fas fa-trash-alt me-1"></i>Hapus</button>
      </div>
  </div>

  {{-- Controls --}}
  <div class="wishlist-controls">
      <input type="text" id="wishlist-search" class="wishlist-search-input" placeholder="Cari berdasarkan judul atau tag...">
      
      {{-- OPTIONS DROPDOWN --}}
      <div class="wishlist-btn-options-container" style="position: relative;">
          <button id="wishlist-btn-options" class="wishlist-ctrl-btn">
              <i class="fas fa-sliders-h"></i> Filter <i class="fas fa-caret-down" style="font-size:0.75rem;"></i>
          </button>
          
          <div id="wishlist-menu-options" style="display:none; position:absolute; top:calc(100% + 4px); right:0; background:#000; border:1px solid var(--c-primary); box-shadow: 0 8px 24px rgba(0,0,0,0.85); z-index: 50; width: max-content; max-width: 90vw; padding: 24px;">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; border-bottom:1px solid var(--c-hairline); padding-bottom:8px;">
                  <strong style="font-size:0.9rem; color:var(--c-ink); text-transform:uppercase; letter-spacing:1px;"><i class="fas fa-filter text-primary me-2"></i>Filter Wishlist</strong>
                  <button type="button" onclick="closeAllDropdowns()" style="background:none;border:none;color:var(--c-mute);font-size:1.1rem;cursor:pointer;">✕</button>
              </div>
              <div style="display: flex; gap: 40px; flex-wrap: wrap;">
                  <div style="min-width: 150px;">
                      <div style="color: var(--c-primary); font-size: 0.75rem; font-weight: 800; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 1px;"><i class="fas fa-tags me-2"></i> Diskon</div>
                      <label style="display: flex; align-items: center; gap: 8px; color: var(--c-mute); font-size: 0.85rem; margin-bottom: 10px; cursor: pointer;">
                          <input type="radio" name="filter_discount" value="all" checked style="accent-color: #76b900;"> Semua Item
                      </label>
                      <label style="display: flex; align-items: center; gap: 8px; color: var(--c-mute); font-size: 0.85rem; margin-bottom: 10px; cursor: pointer;">
                          <input type="radio" name="filter_discount" value="any" style="accent-color: #76b900;"> Hanya Yang Diskon
                      </label>
                      <label style="display: flex; align-items: center; gap: 8px; color: var(--c-mute); font-size: 0.85rem; margin-bottom: 10px; cursor: pointer;">
                          <input type="radio" name="filter_discount" value="50" style="accent-color: #76b900;"> Diskon &ge; 50%
                      </label>
                      <label style="display: flex; align-items: center; gap: 8px; color: var(--c-mute); font-size: 0.85rem; margin-bottom: 10px; cursor: pointer;">
                          <input type="radio" name="filter_discount" value="75" style="accent-color: #76b900;"> Diskon &ge; 75%
                      </label>
                  </div>
                  <div style="min-width: 180px;">
                      <div style="color: var(--c-primary); font-size: 0.75rem; font-weight: 800; margin-bottom: 14px; text-transform: uppercase; letter-spacing: 1px;"><i class="fas fa-wallet me-2"></i> Batas Harga</div>
                      <input type="range" id="filter_price_range" min="0" max="1000000" step="50000" value="1000000" style="width: 100%; accent-color: #76b900;">
                      <div id="filter_price_label" style="color: var(--c-mute); font-size: 0.85rem; text-align: center; margin-top: 12px; font-weight: 600;">Semua Harga</div>
                  </div>
              </div>
              <div style="margin-top:16px; padding-top:12px; border-top:1px solid var(--c-hairline); display:flex; justify-content:flex-end;">
                  <button type="button" class="btn btn-primary" style="padding:6px 16px; font-size:0.8rem; border-radius:0; font-weight:700;" onclick="closeAllDropdowns()">Selesai</button>
              </div>
          </div>
      </div>

      {{-- SORT DROPDOWN --}}
      <div class="wishlist-btn-sort-container" style="position: relative;">
          <button id="wishlist-btn-sort" class="wishlist-ctrl-btn">
              Urutan: <strong id="wishlist-sort-label" style="color:var(--c-ink); font-weight: 700;">Terbaru Ditambah</strong> <i class="fas fa-caret-down" style="font-size:0.75rem;"></i>
          </button>
          
          <div id="wishlist-menu-sort" style="display:none; position:absolute; top:calc(100% + 4px); right:0; background:#000; border:1px solid var(--c-primary); box-shadow: 0 8px 24px rgba(0,0,0,0.85); z-index: 50; width: 220px; padding: 8px 0;">
              <div class="wishlist-sort-option" data-sort="name" style="padding: 10px 20px; color: var(--c-mute); font-size: 0.85rem; cursor: pointer; transition: background 0.2s, color 0.2s; font-weight: 500;">Judul Game</div>
              <div class="wishlist-sort-option" data-sort="price" style="padding: 10px 20px; color: var(--c-mute); font-size: 0.85rem; cursor: pointer; transition: background 0.2s, color 0.2s; font-weight: 500;">Harga Terendah</div>
              <div class="wishlist-sort-option" data-sort="discount" style="padding: 10px 20px; color: var(--c-mute); font-size: 0.85rem; cursor: pointer; transition: background 0.2s, color 0.2s; font-weight: 500;">Diskon Terbesar</div>
              <div class="wishlist-sort-option active" data-sort="date" style="padding: 10px 20px; color: var(--c-ink); background: rgba(255,255,255,0.05); font-size: 0.85rem; cursor: pointer; transition: background 0.2s, color 0.2s; font-weight: 700;">Terbaru Ditambah</div>
          </div>
      </div>
      
      {{-- SHARE BUTTON --}}
      <button class="wishlist-ctrl-btn wishlist-btn-share" title="Bagikan Wishlist">
          <i class="fas fa-share-nodes"></i>
      </button>
  </div>

  {{-- Mobile Backdrop --}}
  <div id="wishlistMobileBackdrop" class="wishlist-mobile-backdrop" onclick="closeAllDropdowns()"></div>

  @if($wishlistItems->isEmpty())
    <div class="card text-center" style="padding: 64px 20px;background:var(--c-surface-soft);margin-bottom:64px;border:1px dashed var(--c-hairline);border-radius:0;">
      <div style="font-size:48px;color:rgba(255,255,255,0.1);margin-bottom:16px"><i class="fa fa-heart-crack"></i></div>
      <h2 class="t-heading-md mb-sm" style="color:var(--c-ink);">Wishlist masih kosong</h2>
      <p class="text-mute mb-lg" style="font-size:0.9rem;">Kamu belum menambahkan game apa pun ke wishlist.</p>
      <a href="{{ route('home') }}" class="btn btn-primary" style="padding:10px 24px;border-radius: 0;text-decoration:none;font-weight:700;">Mulai Belanja</a>
    </div>
  @else
    <div class="wishlist-list">
      @foreach($wishlistItems as $game)
        <div class="wishlist-item-wrapper" 
             data-title="{{ strtolower($game->title) }}" 
             data-price="{{ $game->price }}" 
             data-discount="{{ $game->discount_percent ?? 0 }}"
             data-date="{{ strtotime($game->pivot->created_at ?? now()) }}">
            
            {{-- Custom Checkbox --}}
            <div class="wishlist-checkbox-wrap">
                <label class="wishlist-custom-cb"
                       onmouseenter="this.style.borderColor='var(--c-primary)'"
                       onmouseleave="this.style.borderColor=this.querySelector('input').checked?'var(--c-primary)':'rgba(255,255,255,0.25)'"
                       title="Pilih game ini">
                    <input type="checkbox" class="wishlist-item-checkbox" data-slug="{{ $game->slug }}" onchange="wishlistCheckboxChange(this)" style="position:absolute;opacity:0;width:0;height:0;">
                    <svg class="check-icon" width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#76b900" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:none;"><polyline points="20 6 9 17 4 12"></polyline></svg>
                </label>
            </div>

            <div class="wishlist-row" data-game-slug="{{ $game->slug }}">
                <a href="{{ route('game.show', $game->slug) }}" class="wishlist-row-cover">
                    @if($game->cover_image)
                        <img src="{{ $game->cover_image_url }}" alt="{{ $game->title }}" loading="lazy">
                    @else
                        <div style="width:100%;height:100%;background:#141414;display:flex;align-items:center;justify-content:center;">
                            <i class="fa fa-image" style="font-size:2rem;color:var(--c-mute);"></i>
                        </div>
                    @endif
                </a>
                
                <div class="wishlist-row-body">
                    <div class="wishlist-row-info">
                        <h3>
                            <a href="{{ route('game.show', $game->slug) }}" style="color:var(--c-ink);text-decoration:none;">{{ $game->title }}</a>
                        </h3>

                        <div class="wishlist-meta">
                            <div class="wishlist-meta-row">
                                <div>
                                    <span class="wishlist-meta-label">Rilis:</span>
                                    <span style="color:var(--c-mute);margin-left:4px;">{{ $game->release_date ? $game->release_date->format('d M Y') : '-' }}</span>
                                </div>
                                <div>
                                    <span class="wishlist-meta-label">Review:</span>
                                    <span style="color:{{ $game->review_color ?? '#66C0F4' }};margin-left:4px;font-weight:600;">
                                        {{ $game->review_tier ?? 'NO REVIEWS' }}
                                    </span>
                                </div>
                            </div>

                            <div class="wishlist-meta-row" style="align-items:flex-start; margin-top:2px;">
                                <div style="display:flex;flex-wrap:wrap;">
                                    @foreach(array_slice($game->genre ?? [], 0, 4) as $g)
                                        <span class="wishlist-tag">{{ $g }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="wishlist-row-actions">
                        <div style="font-size:0.75rem;color:var(--c-mute);display:flex;align-items:center;justify-content:space-between;width:100%;">
                            <span class="added-on-text">Ditambah {{ \Carbon\Carbon::parse($game->pivot->created_at ?? now())->format('d/m/Y') }}</span>
                            <div class="remove-wishlist-btn-wrapper">
                                <button class="remove-wishlist-btn" data-game-slug="{{ $game->slug }}" style="background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:var(--c-mute);padding:4px 8px;border-radius:0;cursor:pointer;font-size:0.75rem;display:flex;align-items:center;gap:4px;transition:background 0.2s;" onmouseenter="this.style.background='rgba(255,255,255,0.1)';" onmouseleave="this.style.background='rgba(255,255,255,0.05)';">
                                    <span class="icon-remove" style="color:var(--c-primary);">✕</span> <span class="text-remove">Hapus</span>
                                </button>
                            </div>
                        </div>

                        <div class="wishlist-price-btn">
                            @if($game->price == 0)
                                <div style="color:var(--c-primary);font-size:1.05rem;font-weight:700;background:#000;padding:0 16px;display:flex;align-items:center;justify-content:center;border-radius:0;">GRATIS</div>
                            @else
                                <div class="wishlist-price-block">
                                    @if($game->discount_percent > 0)
                                        <div style="background:var(--c-accent-green);color:#000;font-size:1rem;font-weight:800;padding:0 8px;display:flex;align-items:center;justify-content:center;">
                                            -{{ $game->discount_percent }}%
                                        </div>
                                        <div style="text-align:right;padding:0 10px;display:flex;flex-direction:column;justify-content:center;white-space:nowrap;background:#000;">
                                            <div style="color:var(--c-mute);font-size:0.68rem;text-decoration:line-through;opacity:0.8;line-height:1;margin-bottom:2px;">Rp {{ number_format($game->original_price, 0, ',', '.') }}</div>
                                            <div style="color:var(--c-primary);font-size:0.9rem;font-weight:700;line-height:1;">Rp {{ number_format($game->price, 0, ',', '.') }}</div>
                                        </div>
                                    @else
                                        <div style="color:var(--c-primary);font-size:0.95rem;font-weight:700;background:#000;padding:0 14px;display:flex;align-items:center;justify-content:center;white-space:nowrap;">
                                            Rp {{ number_format($game->price, 0, ',', '.') }}
                                        </div>
                                    @endif
                                </div>
                            @endif

                            @if(in_array($game->id, $cartIds))
                                <a href="{{ route('cart.index') }}" style="background:rgba(255,255,255,0.08);color:var(--c-ink);border:1px solid rgba(255,255,255,0.15);padding:0 16px;border-radius: 0;font-weight:700;font-size:0.9rem;white-space:nowrap;cursor:pointer;text-decoration:none;display:flex;align-items:center;justify-content:center;transition:background 0.2s;">
                                    Di Keranjang
                                </a>
                            @else
                                <button class="cart-btn" data-prevent-global="true" data-game-slug="{{ $game->slug }}" style="background:var(--c-primary);color:#000;border:none;padding:0 16px;border-radius: 0;font-weight:700;font-size:0.9rem;cursor:pointer;white-space:nowrap;transition:transform 0.1s;" onmousedown="this.style.transform='scale(0.97)';" onmouseup="this.style.transform='scale(1)';">
                                    + Keranjang
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
      @endforeach
    </div>
  @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    // ── Hapus dari Wishlist ──────────────────────────────────────────
    document.querySelectorAll('.remove-wishlist-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const slug = this.dataset.gameSlug;
            const card = this.closest('.wishlist-row');

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/wishlist/${slug}`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN':csrfToken,'Accept':'application/json','Content-Type':'application/json'},
                body: JSON.stringify({_token: csrfToken})
            })
            .then(r => r.json())
            .then(data => {
                if (data.success && !data.in_wishlist) {
                    removeCard(card, data.count, 'wishlist-count');
                    if (typeof showCenterPopup==='function') showCenterPopup('Dihapus dari wishlist', true);
                    else if (typeof showToast==='function') showToast('Dihapus dari wishlist', 'success');
                } else if (data.in_wishlist) {
                    this.disabled = false;
                    this.innerHTML = '<span class="icon-remove" style="color:var(--c-primary);">✕</span> <span class="text-remove">Hapus</span>';
                } else {
                    this.disabled = false;
                    this.innerHTML = '<span class="icon-remove" style="color:var(--c-primary);">✕</span> <span class="text-remove">Hapus</span>';
                    if (typeof showCenterPopup==='function') showCenterPopup(data.error || 'Gagal menghapus', false);
                }
            })
            .catch(() => {
                this.disabled = false;
                this.innerHTML = '<span class="icon-remove" style="color:var(--c-primary);">✕</span> <span class="text-remove">Hapus</span>';
                if (typeof showCenterPopup==='function') showCenterPopup('Terjadi kesalahan', false);
            });
        });
    });

    // ── Pindah ke Keranjang (cart-btn) ──────────────────────────────
    document.querySelectorAll('.cart-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const slug = this.dataset.gameSlug;
            const originalHTML = this.innerHTML;

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch(`/cart/${slug}`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN':csrfToken,'Accept':'application/json','Content-Type':'application/json'}
            })
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                    if (typeof showCenterPopup==='function') showCenterPopup(data.error, false);
                    return;
                }

                if (typeof updateBadge === 'function') updateBadge('cart-count', data.count);

                if (data.in_cart) {
                    if (typeof showCenterPopup==='function') showCenterPopup(data.message, true);
                    const actionDiv = this.parentElement;
                    const url = "{{ route('cart.index') }}";
                    actionDiv.innerHTML = `
                        <div class="wishlist-price-block">
                            ${this.previousElementSibling ? this.previousElementSibling.outerHTML : ''}
                        </div>
                        <a href="${url}" style="background:rgba(255,255,255,0.08);color:var(--c-ink);border:1px solid rgba(255,255,255,0.15);padding:0 16px;border-radius: 0;font-weight:700;font-size:0.9rem;white-space:nowrap;cursor:pointer;text-decoration:none;display:flex;align-items:center;justify-content:center;transition:background 0.2s;">
                            Di Keranjang
                        </a>
                    `;
                } else {
                    this.disabled = false;
                    this.innerHTML = originalHTML;
                }
            })
            .catch((error) => {
                console.error(error);
                this.disabled = false;
                this.innerHTML = originalHTML;
                if (typeof showCenterPopup==='function') showCenterPopup('Terjadi kesalahan', false);
            });
        });
    });

    // ── Helper: animasikan penghapusan kartu ────────────────────────
    function removeCard(card, count, badgeId) {
        const wrapper = card.closest('.wishlist-item-wrapper');
        const elementToRemove = wrapper ? wrapper : card;
        elementToRemove.style.transition = 'all 0.35s';
        elementToRemove.style.opacity = '0';
        elementToRemove.style.transform = 'scale(0.95)';

        setTimeout(() => {
            elementToRemove.remove();
            if (count !== null && badgeId && typeof updateBadge === 'function') {
                updateBadge(badgeId, count);
            }
            const headerCount = document.getElementById('wishlistHeaderCount');
            if (headerCount && count !== null) {
                headerCount.textContent = count + ' GAME';
            }

            const remaining = document.querySelectorAll('.wishlist-row');
            if (!remaining.length) {
                const grid = document.querySelector('.wishlist-list');
                if (grid) grid.remove();
                const container = document.querySelector('.container');
                if (container) {
                    container.insertAdjacentHTML('beforeend', `
                        <div class="card text-center" style="padding: 64px 20px;margin-bottom:64px;background:var(--c-surface-soft);border:1px dashed var(--c-hairline);border-radius:0;">
                          <div style="font-size:48px;color:rgba(255,255,255,0.1);margin-bottom:16px"><i class="fa fa-heart-crack"></i></div>
                          <h2 class="t-heading-md mb-sm" style="color:var(--c-ink);">Wishlist masih kosong</h2>
                          <p class="text-mute mb-lg" style="font-size:0.9rem;">Kamu belum menambahkan game apa pun ke wishlist.</p>
                          <a href="{{ route('home') }}" class="btn btn-primary" style="padding:10px 24px;border-radius:0;font-weight:700;">Mulai Belanja</a>
                        </div>
                    `);
                }
            }
        }, 350);
    }

    // ── Wishlist Multi-Select Logic ──────────────────────────────────
    function wishlistUpdateBulkBar() {
        const checked = document.querySelectorAll('.wishlist-item-checkbox:checked');
        const bar = document.getElementById('wishlist-bulk-bar');
        const label = document.getElementById('wishlist-selected-label');
        if (checked.length > 0) {
            bar.style.display = 'flex';
            label.textContent = checked.length + ' item dipilih';
        } else {
            bar.style.display = 'none';
        }
    }

    window.wishlistCheckboxChange = function(checkbox) {
        const label = checkbox ? checkbox.closest('label') : null;
        const wrapper = checkbox ? checkbox.closest('.wishlist-item-wrapper') : null;
        const row = wrapper ? wrapper.querySelector('.wishlist-row') : null;
        const icon = label ? label.querySelector('.check-icon') : null;
        if (icon) icon.style.display = checkbox.checked ? 'block' : 'none';
        if (label) {
            label.style.borderColor = checkbox.checked ? 'var(--c-primary)' : 'rgba(255,255,255,0.25)';
            label.style.background = checkbox.checked ? 'rgba(118,185,0,0.2)' : 'rgba(255,255,255,0.05)';
        }
        if (row) row.classList.toggle('is-checked', checkbox.checked);
        wishlistUpdateBulkBar();
    };

    window.wishlistSelectAll = function() {
        document.querySelectorAll('.wishlist-item-checkbox').forEach(cb => {
            cb.checked = true;
            wishlistCheckboxChange(cb);
        });
        wishlistUpdateBulkBar();
    };

    window.wishlistDeselectAll = function() {
        document.querySelectorAll('.wishlist-item-checkbox').forEach(cb => {
            cb.checked = false;
            wishlistCheckboxChange(cb);
        });
        wishlistUpdateBulkBar();
    };

    window.wishlistBulkRemove = function() {
        const checked = document.querySelectorAll('.wishlist-item-checkbox:checked');
        if (!checked.length) return;
        const total = checked.length;
        let done = 0;
        checked.forEach(cb => {
            const slug = cb.dataset.slug;
            fetch(`/wishlist/${slug}`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
            }).then(r => r.json()).then(data => {
                const row = document.querySelector(`.wishlist-row[data-game-slug="${slug}"]`);
                if (row) removeCard(row, data.count, 'wishlist-count');
                done++;
                if (done === total) {
                    if (typeof showToast === 'function') showToast(total + ' game dihapus dari wishlist', 'success');
                    wishlistUpdateBulkBar();
                }
            });
        });
    };

    window.wishlistBulkAddCart = function() {
        const checked = document.querySelectorAll('.wishlist-item-checkbox:checked');
        if (!checked.length) return;
        const total = checked.length;
        let done = 0;
        checked.forEach(cb => {
            const slug = cb.dataset.slug;
            fetch(`/cart/${slug}`, {
                method: 'POST',
                headers: {'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json'}
            }).then(r => r.json()).then(data => {
                if (typeof updateBadge === 'function') updateBadge('cart-count', data.count);
                done++;
                if (done === total) {
                    if (typeof showCenterPopup === 'function') showCenterPopup(total + ' game berhasil ditambahkan ke keranjang!', true);
                    wishlistDeselectAll();
                }
            });
        });
    };

    // ── Dropdowns & Filtering & Sorting Logic ────────────────────────
    const btnOptions = document.getElementById('wishlist-btn-options');
    const menuOptions = document.getElementById('wishlist-menu-options');
    const btnSort = document.getElementById('wishlist-btn-sort');
    const menuSort = document.getElementById('wishlist-menu-sort');
    const backdrop = document.getElementById('wishlistMobileBackdrop');

    window.closeAllDropdowns = function() {
        if(menuOptions) menuOptions.style.display = 'none';
        if(menuSort) menuSort.style.display = 'none';
        if(backdrop) backdrop.classList.remove('show');
    };

    if(btnOptions && menuOptions) {
        btnOptions.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = menuOptions.style.display === 'none';
            closeAllDropdowns();
            if (isHidden) {
                menuOptions.style.display = 'block';
                if (backdrop && window.innerWidth <= 768) backdrop.classList.add('show');
            }
        });
    }

    if(btnSort && menuSort) {
        btnSort.addEventListener('click', function(e) {
            e.stopPropagation();
            const isHidden = menuSort.style.display === 'none';
            closeAllDropdowns();
            if (isHidden) {
                menuSort.style.display = 'block';
                if (backdrop && window.innerWidth <= 768) backdrop.classList.add('show');
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (menuOptions && !menuOptions.contains(e.target) && !btnOptions.contains(e.target)) {
            menuOptions.style.display = 'none';
        }
        if (menuSort && !menuSort.contains(e.target) && !btnSort.contains(e.target)) {
            menuSort.style.display = 'none';
        }
        if (backdrop && (!menuOptions || menuOptions.style.display === 'none') && (!menuSort || menuSort.style.display === 'none')) {
            backdrop.classList.remove('show');
        }
    });

    // Share Button Handler
    const btnShare = document.querySelector('.wishlist-btn-share');
    if (btnShare) {
        btnShare.addEventListener('click', function() {
            if (navigator.share) {
                navigator.share({
                    title: 'Wishlist GameVault',
                    url: window.location.href
                }).catch(() => {});
            } else {
                navigator.clipboard.writeText(window.location.href).then(() => {
                    if (typeof showToast === 'function') showToast('Link wishlist disalin ke clipboard!', 'success');
                    else alert('Link wishlist disalin ke clipboard!');
                });
            }
        });
    }

    const searchInput = document.getElementById('wishlist-search');
    const filterRadios = document.querySelectorAll('input[name="filter_discount"]');
    const priceRange = document.getElementById('filter_price_range');
    const priceLabel = document.getElementById('filter_price_label');
    const sortOptions = document.querySelectorAll('.wishlist-sort-option');
    const sortLabel = document.getElementById('wishlist-sort-label');
    const listContainer = document.querySelector('.wishlist-list');

    let currentSort = 'date';
    
    function formatRp(num) {
        return 'Rp ' + num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    if (priceRange && priceLabel) {
        priceRange.addEventListener('input', function() {
            const val = parseInt(this.value);
            if (val >= 1000000) {
                priceLabel.textContent = 'Semua Harga';
            } else {
                priceLabel.textContent = 'Di bawah ' + formatRp(val);
            }
            applyFiltersAndSort();
        });
    }

    if (searchInput) searchInput.addEventListener('input', applyFiltersAndSort);
    filterRadios.forEach(r => r.addEventListener('change', applyFiltersAndSort));

    sortOptions.forEach(opt => {
        opt.addEventListener('click', function() {
            sortOptions.forEach(o => {
                o.classList.remove('active');
                o.style.fontWeight = '500';
                o.style.color = 'var(--c-mute)';
                o.style.background = 'transparent';
            });
            this.classList.add('active');
            this.style.fontWeight = '700';
            this.style.color = 'var(--c-ink)';
            this.style.background = 'rgba(255,255,255,0.05)';
            
            currentSort = this.dataset.sort;
            if (sortLabel) sortLabel.textContent = this.textContent;
            closeAllDropdowns();
            applyFiltersAndSort();
        });
    });

    function applyFiltersAndSort() {
        if (!listContainer) return;
        const wrappers = Array.from(listContainer.querySelectorAll('.wishlist-item-wrapper'));
        const query = searchInput ? searchInput.value.toLowerCase() : '';
        const discountRadio = document.querySelector('input[name="filter_discount"]:checked');
        const discountFilter = discountRadio ? discountRadio.value : 'all';
        const maxPrice = priceRange ? parseInt(priceRange.value) : 1000000;

        wrappers.forEach(w => {
            const title = w.dataset.title || '';
            const price = parseInt(w.dataset.price) || 0;
            const discount = parseInt(w.dataset.discount) || 0;
            
            let match = true;
            if (query && !title.includes(query)) match = false;
            
            if (discountFilter === 'any' && discount <= 0) match = false;
            if (discountFilter === '50' && discount < 50) match = false;
            if (discountFilter === '75' && discount < 75) match = false;
            
            if (maxPrice < 1000000 && price > maxPrice) match = false;

            w.style.display = match ? 'flex' : 'none';
        });

        const visibleWrappers = wrappers.filter(w => w.style.display !== 'none');
        
        visibleWrappers.sort((a, b) => {
            if (currentSort === 'name') {
                return (a.dataset.title || '').localeCompare(b.dataset.title || '');
            } else if (currentSort === 'price') {
                return (parseInt(a.dataset.price) || 0) - (parseInt(b.dataset.price) || 0);
            } else if (currentSort === 'discount') {
                return (parseInt(b.dataset.discount) || 0) - (parseInt(a.dataset.discount) || 0);
            } else if (currentSort === 'date') {
                return (parseInt(b.dataset.date) || 0) - (parseInt(a.dataset.date) || 0);
            }
            return 0;
        });

        visibleWrappers.forEach(w => listContainer.appendChild(w));
    }
});
</script>
@endsection
