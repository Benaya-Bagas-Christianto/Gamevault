<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'GameVault') — Platform Game Digital</title>
  <meta name="description" content="@yield('meta_description', 'GameVault — Beli game digital favoritmu dengan harga terbaik. Ribuan judul tersedia dengan pembayaran mudah dan aman.')">

  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">

  <!-- Design System CSS -->
  <link rel="stylesheet" href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

  @stack('styles')
</head>
<body style="display:flex; flex-direction:column; min-height:100vh; overflow-x:hidden;">



<!-- ═══ PRIMARY NAV ════════════════════════════════════════ -->
<nav class="primary-nav" style="padding:0 48px;">
  <a href="{{ route('home') }}" class="nav-brand" style="display:flex; align-items:center; gap:12px; text-decoration:none;">
    <img src="{{ asset('img/logo.png') }}" alt="GameVault Logo" style="height:32px; width:auto; object-fit:contain;">
    <span style="color:#fff; font-size:1.2rem; font-weight:800; letter-spacing:0.5px; font-family:var(--font-heading);">Game<span style="color:var(--c-primary);">Vault</span></span>
  </a>

  <div style="flex:1; display:flex; justify-content:center;">
    <ul class="nav-links">
      <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
      <li><a href="{{ route('store') }}" class="{{ request()->routeIs('store') ? 'active' : '' }}">Toko</a></li>
      <li><a href="{{ route('categories') }}" class="{{ request()->routeIs('categories') ? 'active' : '' }}">Kategori</a></li>
      <li><a href="{{ route('bantuan') }}" class="{{ request()->routeIs('bantuan') ? 'active' : '' }}">Bantuan</a></li>
    </ul>
  </div>

  <div style="display:flex; align-items:center; gap:24px;">
    @auth
      <a href="{{ route('wishlist.index') }}" title="Wishlist" style="color:var(--c-on-dark); font-size:16px; position:relative;">
        <i class="fa fa-heart"></i>
        @if(($wCount = auth()->user()->wishlists()->count()) > 0)
          <span id="wishlist-count" class="nav-badge" style="position:absolute; top:-8px; right:-12px;">{{ $wCount }}</span>
        @endif
      </a>
      <a href="{{ route('cart.index') }}" title="Keranjang" style="color:var(--c-on-dark); font-size:16px; position:relative;">
        <i class="fa fa-shopping-cart"></i>
        @if(($cCount = auth()->user()->cartBadgeCount()) > 0)
          <span id="cart-count" class="nav-badge" style="position:absolute; top:-8px; right:-12px;">{{ $cCount }}</span>
        @endif
      </a>
      <div style="position:relative;" class="profile-dropdown-wrapper" onmouseleave="if(this.dataset.pinned !== 'true') this.querySelector('.profile-dropdown').style.display='none'">
        <div style="cursor:pointer; display:flex; align-items:center; gap:8px; padding:8px 12px; margin:-8px -12px; border-radius: 0;" 
             onmouseenter="this.nextElementSibling.style.display='block'"
             onclick="const wrapper = this.parentElement; wrapper.dataset.pinned = wrapper.dataset.pinned === 'true' ? 'false' : 'true'; this.nextElementSibling.style.display = (wrapper.dataset.pinned === 'true' || this.matches(':hover')) ? 'block' : 'none'; event.stopPropagation();">
          <div style="position:relative;">
            <img src="{{ auth()->user()->avatar_url }}" alt="Profile" style="width:32px;height:32px;border-radius: 50%;object-fit:cover;border:1px solid var(--c-primary);">
            @if(auth()->user()->role === 'admin' && ($refundCount = \App\Models\TransactionDetail::where('refund_status', 'requested')->count()) > 0)
              <span class="nav-badge" style="position:absolute; top:-6px; right:-6px;">{{ $refundCount }}</span>
            @endif
          </div>
          <span style="font-weight:700; font-size:14px; color:var(--c-on-dark); user-select: none;">{{ auth()->user()->username ?? auth()->user()->name }}</span>
          <i class="fas fa-chevron-down" style="font-size:10px; color:var(--c-mute);"></i>
        </div>
        
        <div class="profile-dropdown" style="display:none; position:absolute; top:100%; right:0; margin-top:4px; width:220px; background:var(--c-surface-elevated); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); border:1px solid var(--c-hairline-strong); border-radius: 0; box-shadow:0 12px 40px rgba(0,0,0,0.7); z-index:9999; padding:8px 0; overflow:hidden;">
          <div style="padding:12px 16px; border-bottom:1px solid var(--c-hairline); margin-bottom:8px;">
            <div style="font-weight:700; color:var(--c-on-dark); font-size:14px;">Pengaturan Akun</div>
            <div style="color:var(--c-mute); font-size:12px; margin-top:2px;">{{ auth()->user()->email }}</div>
          </div>
          
          <a href="{{ route('profile.index') }}" style="display:flex; align-items:center; gap:12px; padding:10px 16px; color:var(--c-body); text-decoration:none; font-size:14px; transition:all 0.2s;" onmouseenter="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseleave="this.style.background='transparent'; this.style.color='var(--c-body)';">
            <i class="fas fa-user" style="width:16px; text-align:center;"></i> Profil Saya
          </a>
          <a href="{{ route('library.index') }}" style="display:flex; align-items:center; gap:12px; padding:10px 16px; color:var(--c-body); text-decoration:none; font-size:14px; transition:all 0.2s;" onmouseenter="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseleave="this.style.background='transparent'; this.style.color='var(--c-body)';">
            <i class="fas fa-gamepad" style="width:16px; text-align:center;"></i> Library Game
          </a>
          <a href="{{ route('wishlist.index') }}" style="display:flex; align-items:center; gap:12px; padding:10px 16px; color:var(--c-body); text-decoration:none; font-size:14px; transition:all 0.2s;" onmouseenter="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseleave="this.style.background='transparent'; this.style.color='var(--c-body)';">
            <i class="fas fa-heart" style="width:16px; text-align:center;"></i> Wishlist
          </a>
          
          @if(auth()->user()->role === 'admin')
          <a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; gap:12px; padding:10px 16px; color:var(--c-body); text-decoration:none; font-size:14px; transition:all 0.2s;" onmouseenter="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseleave="this.style.background='transparent'; this.style.color='var(--c-body)';">
            <i class="fas fa-shield-alt" style="width:16px; text-align:center;"></i> Buka Admin Panel
          </a>
          @endif
          
          <div style="border-top:1px solid var(--c-hairline); margin:8px 0;"></div>
          
          <form method="POST" action="{{ route('logout') }}" style="margin:0;">
            @csrf
            <button type="submit" style="width:100%; text-align:left; background:none; border:none; display:flex; align-items:center; gap:12px; padding:10px 16px; color:var(--c-error); text-decoration:none; font-size:14px; font-family:inherit; cursor:pointer; transition:all 0.2s;" onmouseenter="this.style.background='rgba(255,59,48,0.1)';" onmouseleave="this.style.background='transparent';">
              <i class="fas fa-sign-out-alt" style="width:16px; text-align:center;"></i> Keluar Akun
            </button>
          </form>
        </div>
      </div>
    @else
      <a href="{{ route('login') }}" class="desktop-only" style="color:var(--c-on-dark); font-size:14px; font-weight:600; text-decoration:none;">Login</a>
      <a href="{{ route('register') }}" class="btn btn-primary btn-sm desktop-only" style="border-radius: 0;">Get Started</a>
    @endauth
  </div>
  
  <!-- Mobile Menu Toggle Button -->
  <button class="mobile-menu-btn" onclick="toggleMobileMenu()" style="display:none; background:none; border:none; color:var(--c-on-dark); font-size:24px; cursor:pointer; margin-left:16px;">
    <i class="fas fa-bars"></i>
  </button>
</nav>

<!-- Mobile Menu Drawer -->
<div class="mobile-menu-overlay" id="mobile-menu-overlay" onclick="toggleMobileMenu()" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.75); backdrop-filter:blur(4px); -webkit-backdrop-filter:blur(4px); z-index:9998; opacity:0; transition:opacity 0.3s ease;"></div>
<div class="mobile-menu-drawer" id="mobile-menu-drawer" style="position:fixed; top:0; right:0; bottom:0; width:290px; max-width:85vw; height:100%; height:100dvh; max-height:100dvh; background:var(--c-surface-elevated, #141414); border-left:1px solid var(--c-hairline); z-index:9999; transform:translateX(100%); transition:transform 0.3s cubic-bezier(0.16, 1, 0.3, 1); padding:16px 18px calc(16px + env(safe-area-inset-bottom, 0px)) 18px; box-sizing:border-box; display:flex; flex-direction:column; box-shadow:-12px 0 36px rgba(0,0,0,0.8);">
  
  <!-- Header: Brand & Close Button -->
  <div style="display:flex; align-items:center; justify-content:space-between; padding-bottom:12px; margin-bottom:14px; border-bottom:1px solid var(--c-hairline); flex-shrink:0;">
    <div style="display:flex; align-items:center; gap:10px;">
      <img src="{{ asset('img/logo.png') }}" alt="GameVault Logo" style="height:24px; width:auto; object-fit:contain;">
      <span style="color:#fff; font-size:1.1rem; font-weight:800; letter-spacing:0.5px; font-family:var(--font-heading);">Game<span style="color:var(--c-primary);">Vault</span></span>
    </div>
    <button onclick="toggleMobileMenu()" aria-label="Tutup Menu" style="background:none; border:none; color:var(--c-on-dark); font-size:22px; cursor:pointer; padding:4px 8px; line-height:1; display:flex; align-items:center; justify-content:center;">
      <i class="fas fa-times"></i>
    </button>
  </div>
  
  <!-- Middle: Nav Links (Flexible, compact, no forced scrolling) -->
  <div style="flex:1; min-height:0; overflow-y:auto; display:flex; flex-direction:column; gap:6px; padding-right:2px;">
    <a href="{{ route('home') }}" class="mobile-nav-link" style="display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:4px; color:var(--c-on-dark); text-decoration:none; font-size:15px; font-weight:700; transition:all 0.2s;" onmouseenter="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseleave="this.style.background='transparent'; this.style.color='var(--c-on-dark)';">
      <i class="fas fa-home" style="width:18px; text-align:center; color:var(--c-primary);"></i> Beranda
    </a>
    <a href="{{ route('store') }}" class="mobile-nav-link" style="display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:4px; color:var(--c-on-dark); text-decoration:none; font-size:15px; font-weight:700; transition:all 0.2s;" onmouseenter="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseleave="this.style.background='transparent'; this.style.color='var(--c-on-dark)';">
      <i class="fas fa-store" style="width:18px; text-align:center; color:var(--c-primary);"></i> Toko
    </a>
    <a href="{{ route('categories') }}" class="mobile-nav-link" style="display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:4px; color:var(--c-on-dark); text-decoration:none; font-size:15px; font-weight:700; transition:all 0.2s;" onmouseenter="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseleave="this.style.background='transparent'; this.style.color='var(--c-on-dark)';">
      <i class="fas fa-th-large" style="width:18px; text-align:center; color:var(--c-primary);"></i> Kategori
    </a>
    <a href="{{ route('bantuan') }}" class="mobile-nav-link" style="display:flex; align-items:center; gap:12px; padding:10px 14px; border-radius:4px; color:var(--c-on-dark); text-decoration:none; font-size:15px; font-weight:700; transition:all 0.2s;" onmouseenter="this.style.background='var(--c-surface-soft)'; this.style.color='var(--c-primary)';" onmouseleave="this.style.background='transparent'; this.style.color='var(--c-on-dark)';">
      <i class="fas fa-question-circle" style="width:18px; text-align:center; color:var(--c-primary);"></i> Bantuan
    </a>
  </div>
  
  <!-- Bottom Actions: Pinned at the very bottom edge, always visible without scrolling -->
  <div style="margin-top:auto; flex-shrink:0; padding-top:14px; border-top:1px solid var(--c-hairline); display:flex; flex-direction:column; gap:8px;">
    @auth
      <div style="font-size:11px; font-weight:700; color:var(--c-mute); text-transform:uppercase; letter-spacing:1px; margin-bottom:2px;">Akun Saya</div>
      <a href="{{ route('profile.index') }}" style="display:flex; align-items:center; gap:12px; color:var(--c-on-dark); text-decoration:none; font-size:14px; padding:6px 0;">
        <i class="fas fa-user" style="width:18px; text-align:center; color:var(--c-primary);"></i> Profil Saya
      </a>
      <a href="{{ route('library.index') }}" style="display:flex; align-items:center; gap:12px; color:var(--c-on-dark); text-decoration:none; font-size:14px; padding:6px 0;">
        <i class="fas fa-gamepad" style="width:18px; text-align:center; color:var(--c-primary);"></i> Library Game
      </a>
      <a href="{{ route('wishlist.index') }}" style="display:flex; align-items:center; gap:12px; color:var(--c-on-dark); text-decoration:none; font-size:14px; padding:6px 0;">
        <i class="fas fa-heart" style="width:18px; text-align:center; color:var(--c-primary);"></i> Wishlist
      </a>
      @if(auth()->user()->role === 'admin')
      <a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; gap:12px; color:var(--c-on-dark); text-decoration:none; font-size:14px; padding:6px 0;">
        <i class="fas fa-shield-alt" style="width:18px; text-align:center; color:var(--c-primary);"></i> Buka Admin Panel
      </a>
      @endif
      <form method="POST" action="{{ route('logout') }}" style="margin:0; margin-top:4px;">
        @csrf
        <button type="submit" style="background:none; border:none; display:flex; align-items:center; gap:12px; color:var(--c-error); text-decoration:none; font-size:14px; padding:6px 0; cursor:pointer; width:100%;">
          <i class="fas fa-sign-out-alt" style="width:18px; text-align:center;"></i> Keluar Akun
        </button>
      </form>
    @else
      <div style="display:flex; flex-direction:column; gap:8px;">
        <a href="{{ route('login') }}" class="btn btn-outline" style="width:100%; border-radius:4px; text-align:center; padding:10px 14px; font-weight:700; font-size:14px; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:8px; border:1px solid rgba(255,255,255,0.25); color:#fff; background:transparent;">
          <i class="fas fa-sign-in-alt"></i> Login
        </a>
        <a href="{{ route('register') }}" class="btn btn-primary" style="width:100%; border-radius:4px; text-align:center; padding:10px 14px; font-weight:700; font-size:14px; text-decoration:none; display:flex; align-items:center; justify-content:center; gap:8px; background:var(--c-primary); color:#000; border:none; box-shadow:0 4px 14px rgba(0,255,102,0.25);">
          <i class="fas fa-user-plus"></i> Get Started
        </a>
      </div>
    @endauth
  </div>
</div><!-- ═══ BREADCRUMB BAR (Optional) ════════════════════════════════════════ -->
@if(!request()->routeIs('home'))
<div style="background:rgba(255,255,255,0.02); backdrop-filter:blur(16px); -webkit-backdrop-filter:blur(16px); color:var(--c-body); height:48px; display:flex; align-items:center; padding:0 48px; font-size:14px; font-weight:700; text-transform:uppercase; border-bottom:1px solid rgba(255,255,255,0.05);">
  <a href="{{ route('home') }}" style="color:var(--c-mute);">HOME</a>
  <i class="fas fa-chevron-right" style="font-size:10px; color:var(--c-mute); margin:0 12px;"></i>
  <span style="color:var(--c-body);">@yield('title')</span>
</div>
@endif

<!-- ═══ Main Content ═══════════════════════════════════════ -->
<main id="main-content" style="flex:1;">
  @yield('content')
</main>

<!-- ═══ Footer ═════════════════════════════════════════════ -->
<footer class="app-footer" style="margin-top:auto; background:#050505; border-top:1px solid var(--c-hairline);">
  <div class="container footer-container" style="max-width:1280px;">
    <style>
      .app-footer {
        padding: 56px 48px 32px;
      }
      .footer-brand-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 24px;
        margin-bottom: 32px;
        border-bottom: 1px solid var(--c-hairline);
        flex-wrap: wrap;
        gap: 16px;
      }
      .footer-brand-info {
        display: flex;
        flex-direction: column;
        gap: 6px;
      }
      .footer-brand-logo {
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
      }
      .footer-brand-tagline {
        color: var(--c-mute);
        font-size: 0.82rem;
        margin: 0;
      }
      .footer-socials {
        display: flex;
        align-items: center;
        gap: 8px;
      }
      .footer-social-link {
        width: 36px;
        height: 36px;
        background: var(--c-surface-dark);
        border: 1px solid var(--c-hairline);
        color: var(--c-mute);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        text-decoration: none;
        border-radius: 0;
        transition: all 0.2s ease;
      }
      .footer-social-link:hover {
        background: var(--c-surface-soft);
        color: var(--c-primary);
        border-color: var(--c-primary);
        transform: translateY(-2px);
      }

      /* Desktop Grid */
      .footer-nav-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 24px;
        margin-bottom: 48px;
      }
      .footer-col-header {
        font-size: 15px;
        font-weight: 700;
        color: var(--c-on-dark);
        margin: 0 0 16px 0;
        text-transform: none;
        letter-spacing: 0.3px;
        background: none;
        border: none;
        padding: 0;
        text-align: left;
      }
      .footer-col-header i {
        display: none;
      }
      .footer-link-list {
        list-style: none;
        padding: 0;
        margin: 0;
        display: flex;
        flex-direction: column;
        gap: 12px;
      }
      .footer-link {
        color: var(--c-on-dark-mute);
        font-size: 14px;
        text-decoration: none;
        transition: color 0.2s ease;
        display: inline-block;
      }
      .footer-link:hover {
        color: var(--c-primary) !important;
      }

      /* Bottom Row */
      .footer-bottom-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 24px;
        border-top: 1px solid var(--c-hairline);
        color: var(--c-mute);
        font-size: 13.5px;
        flex-wrap: wrap;
        gap: 16px;
      }

      /* ════ MOBILE FOOTER SYSTEM (<= 768px) ════ */
      @media (max-width: 768px) {
        .app-footer {
          padding: 28px 16px 36px !important;
        }
        .footer-brand-row {
          flex-direction: column;
          align-items: flex-start;
          gap: 14px;
          margin-bottom: 20px;
          padding-bottom: 18px;
        }

        /* Mobile Accordion Nav */
        .footer-nav-grid {
          display: flex !important;
          flex-direction: column !important;
          gap: 0 !important;
          margin-bottom: 24px !important;
          border-top: 1px solid var(--c-hairline);
        }
        .footer-col {
          border-bottom: 1px solid var(--c-hairline);
        }
        .footer-col-header {
          width: 100%;
          display: flex !important;
          justify-content: space-between;
          align-items: center;
          padding: 14px 4px;
          margin: 0;
          cursor: pointer;
          font-size: 14.5px;
          color: #fff;
          user-select: none;
        }
        .footer-col-header i {
          display: block !important;
          font-size: 12px;
          color: var(--c-mute);
          transition: transform 0.25s ease;
        }
        .footer-col.active .footer-col-header i {
          transform: rotate(180deg);
          color: var(--c-primary);
        }
        .footer-col.active .footer-col-header {
          color: var(--c-primary);
        }
        .footer-link-list {
          display: none;
          padding: 0 4px 16px 8px !important;
          gap: 10px !important;
        }
        .footer-col.active .footer-link-list {
          display: flex !important;
        }
        .footer-link {
          font-size: 13.5px;
          padding: 3px 0;
        }

        .footer-bottom-row {
          flex-direction: column;
          align-items: flex-start;
          gap: 16px;
          padding-top: 18px;
        }
      }
    </style>

    {{-- Brand & Community Row --}}
    <div class="footer-brand-row">
      <div class="footer-brand-info">
        <a href="{{ route('home') }}" class="footer-brand-logo">
          <img src="{{ asset('img/logo.png') }}" alt="GameVault Logo" style="height:28px; width:auto; object-fit:contain;">
          <span style="color:#fff; font-size:1.15rem; font-weight:800; letter-spacing:0.5px; font-family:var(--font-heading);">Game<span style="color:var(--c-primary);">Vault</span></span>
        </a>
        <p class="footer-brand-tagline">Platform Game Digital Resmi &amp; Terpercaya</p>
      </div>
      <div class="footer-socials">
        <a href="https://discord.com" target="_blank" rel="noopener" class="footer-social-link" title="Discord"><i class="fab fa-discord"></i></a>
        <a href="https://store.steampowered.com" target="_blank" rel="noopener" class="footer-social-link" title="Steam"><i class="fab fa-steam"></i></a>
        <a href="https://instagram.com" target="_blank" rel="noopener" class="footer-social-link" title="Instagram"><i class="fab fa-instagram"></i></a>
        <a href="https://twitter.com" target="_blank" rel="noopener" class="footer-social-link" title="Twitter / X"><i class="fab fa-x-twitter"></i></a>
        <a href="https://youtube.com" target="_blank" rel="noopener" class="footer-social-link" title="YouTube"><i class="fab fa-youtube"></i></a>
      </div>
    </div>

    {{-- Navigation Grid (Desktop: 4 Col, Mobile: Modern Accordion) --}}
    <div class="footer-nav-grid">
      
      {{-- Column 1: GameVault --}}
      <div class="footer-col">
        <button type="button" class="footer-col-header" onclick="toggleFooterCol(this)">
          <span>GameVault</span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <ul class="footer-link-list">
          <li><a href="#" class="footer-link">Tentang Kami</a></li>
          <li><a href="#" class="footer-link">Karir</a></li>
          <li><a href="#" class="footer-link">Berita &amp; Update</a></li>
        </ul>
      </div>

      {{-- Column 2: Jelajahi --}}
      <div class="footer-col">
        <button type="button" class="footer-col-header" onclick="toggleFooterCol(this)">
          <span>Jelajahi</span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <ul class="footer-link-list">
          <li><a href="{{ route('home') }}" class="footer-link">Semua Game</a></li>
          <li><a href="{{ route('home') }}?genres[]=RPG" class="footer-link">Game RPG</a></li>
          <li><a href="{{ route('home') }}?price[]=free" class="footer-link">Game Gratis</a></li>
        </ul>
      </div>

      {{-- Column 3: Akun Anda --}}
      <div class="footer-col">
        <button type="button" class="footer-col-header" onclick="toggleFooterCol(this)">
          <span>Akun Anda</span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <ul class="footer-link-list">
          @auth
            <li><a href="{{ route('profile.index') }}" class="footer-link">Profil Saya</a></li>
            <li><a href="{{ route('library.index') }}" class="footer-link">Library Game</a></li>
          @else
            <li><a href="{{ route('login') }}" class="footer-link">Masuk / Login</a></li>
            <li><a href="{{ route('register') }}" class="footer-link">Daftar Akun Baru</a></li>
          @endauth
        </ul>
      </div>

      {{-- Column 4: Bantuan --}}
      <div class="footer-col">
        <button type="button" class="footer-col-header" onclick="toggleFooterCol(this)">
          <span>Bantuan</span>
          <i class="fas fa-chevron-down"></i>
        </button>
        <ul class="footer-link-list">
          <li><a href="{{ route('bantuan') }}#cara-membeli" class="footer-link">Cara Membeli</a></li>
          <li><a href="{{ route('bantuan') }}#metode-pembayaran" class="footer-link">Metode Pembayaran</a></li>
          <li><a href="{{ route('bantuan') }}#kebijakan-refund" class="footer-link">Kebijakan Refund</a></li>
          <li><a href="{{ route('bantuan') }}#syarat-ketentuan" class="footer-link">Syarat &amp; Ketentuan</a></li>
          <li><a href="{{ route('bantuan') }}#privasi" class="footer-link">Privasi</a></li>
        </ul>
      </div>

    </div>

    {{-- Bottom Bar: Copyright & Payment Badges --}}
    <div class="footer-bottom-row">
      <span>&copy; {{ date('Y') }} <span style="font-weight:800; letter-spacing:0.5px; color:var(--c-on-dark); font-family:var(--font-heading);">Game<span style="color:var(--c-primary);">Vault</span></span>. All rights reserved.</span>
      <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
        <img src="{{ asset('images/mastercard.png') }}" height="20" alt="Mastercard" style="object-fit:contain;background:#fff;border-radius:0;padding:2px;">
        <img src="{{ asset('images/visa.png') }}" height="16" alt="Visa" style="object-fit:contain;background:#fff;border-radius:0;padding:2px;">
        <img src="{{ asset('images/dana.png') }}" height="20" alt="Dana" style="object-fit:contain;background:#fff;border-radius:0;padding:2px;">
        <img src="{{ asset('images/gopay.png') }}" height="16" alt="GoPay" style="object-fit:contain;background:#fff;border-radius:0;padding:2px;">
        <img src="{{ asset('images/qris.png') }}" height="16" alt="QRIS" style="object-fit:contain;background:#fff;border-radius:0;padding:2px;">
        <img src="{{ asset('images/alfamart.png') }}" height="16" alt="Alfamart" style="object-fit:contain;background:#fff;border-radius:0;padding:2px;">
        <img src="{{ asset('images/indomaret.png') }}" height="16" alt="Indomaret" style="object-fit:contain;background:#fff;border-radius:0;padding:2px;">
      </div>
    </div>

  </div>
</footer>
<script>
  function toggleFooterCol(btn) {
    if (window.innerWidth <= 768) {
      const col = btn.closest('.footer-col');
      if (col) col.classList.toggle('active');
    }
  }
</script>
</div> <!-- End Main Area Flex Container -->
</div> <!-- End App Wrapper -->

<!-- ═══ Toast Container ════════════════════════════════════ -->
<div class="toast-container" id="toast-container"></div>

<!-- ═══ Global JS ══════════════════════════════════════════ -->
<script>
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;
  const IS_AUTHENTICATED = {{ auth()->check() ? 'true' : 'false' }};

  function dismissToast(el) {
      if (!el) return;
      el.style.animation = 'slideOut .3s ease forwards';
      setTimeout(() => el.remove(), 300);
  }

  function showToast(type, title, message, duration = 4000) {
      const icons = { success: 'fa-circle-check', error: 'fa-circle-xmark', warning: 'fa-triangle-exclamation' };
      const container = document.getElementById('toast-container');
      const toast = document.createElement('div');
      toast.className = `toast toast-${type}`;
      toast.innerHTML = `
          <i class="fa-solid ${icons[type] || icons.success} toast-icon"></i>
          <div class="toast-body">
              <div class="toast-title">${title}</div>
              ${message ? `<div class="toast-msg">${message}</div>` : ''}
          </div>
          <button class="toast-close" onclick="dismissToast(this.parentElement)">
              <i class="fa-solid fa-xmark"></i>
          </button>`;
      container.appendChild(toast);
      if (duration > 0) setTimeout(() => dismissToast(toast), duration);
  }

  function closeSweetAlert() {
      const overlay = document.getElementById('sweet-alert-overlay');
      if (overlay) {
          overlay.classList.remove('open');
          const modal = overlay.querySelector('.modal');
          if(modal) modal.style.transform = 'scale(0.9)';
          setTimeout(() => overlay.remove(), 300);
      }
  }

  function showSweetAlert(title, message, type = 'success') {
      let overlay = document.getElementById('sweet-alert-overlay');
      if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'sweet-alert-overlay';
          overlay.className = 'modal-overlay';
          document.body.appendChild(overlay);
      }
      
      let iconHtml = '';
      if (type === 'success') {
          iconHtml = `<svg class="svg-success-circle" viewBox="-2 -2 56 56" style="width:72px; height:72px; display:inline-block; border-radius: 0; fill:none;">
            <circle cx="26" cy="26" r="24" fill="none"/>
            <path class="svg-success-check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
          </svg>`;
      } else {
          let iconClass = 'fa-circle-check';
          let iconColor = 'var(--c-primary)';
          if(type === 'warning') {
              iconClass = 'fa-triangle-exclamation';
              iconColor = 'var(--c-warning)';
          } else if(type === 'error') {
              iconClass = 'fa-circle-xmark';
              iconColor = 'var(--c-error)';
          }
          iconHtml = `<i class="fa-solid ${iconClass}" style="color: ${iconColor};"></i>`;
      }
      
      overlay.innerHTML = `
        <div class="modal" style="text-align:center; padding: 40px 24px; max-width: 360px;">
          <div style="font-size: 64px; margin-bottom: 24px;">
              ${iconHtml}
          </div>
          <h3 style="font-size: 24px; font-weight: 800; margin: 0 0 12px 0;">${title}</h3>
          <p style="color: var(--c-body); margin: 0 0 32px 0; line-height: 1.5;">${message}</p>
          <button class="btn btn-primary w-full" style="justify-content:center" onclick="closeSweetAlert()">OK</button>
        </div>
      `;
      
      void overlay.offsetWidth; // trigger reflow
      overlay.classList.add('open');
  }

  function showCenterPopup(message, isSuccess = true) {
      showSweetAlert(isSuccess ? 'Berhasil!' : 'Oops!', message, isSuccess ? 'success' : 'error');
  }

  function showLoginRequiredPopup(feature = 'keranjang atau wishlist') {
      let overlay = document.getElementById('sweet-alert-overlay');
      if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'sweet-alert-overlay';
          overlay.className = 'modal-overlay';
          document.body.appendChild(overlay);
      }
      
      const featureName = feature === 'wishlist' ? 'wishlist' : (feature === 'keranjang' ? 'keranjang belanja' : feature);

      overlay.innerHTML = `
        <div class="modal" style="text-align:center; padding: 40px 24px; max-width: 360px; border: 1px solid var(--c-hairline-strong); box-shadow: 0 20px 50px rgba(0,0,0,0.8); background: var(--c-surface-elevated, #161616);">
          <div style="font-size: 64px; margin-bottom: 24px;">
              <i class="fa-solid fa-triangle-exclamation" style="color: var(--c-warning, #f59e0b);"></i>
          </div>
          <h3 style="font-size: 24px; font-weight: 800; margin: 0 0 12px 0; color: #fff;">Perhatian!</h3>
          <p style="color: var(--c-body, #9ca3af); margin: 0 0 28px 0; line-height: 1.5; font-size: 15px;">
            Silakan login terlebih dahulu untuk menambahkan game ke <strong style="color: #fff;">${featureName}</strong>.
          </p>
          <div style="display:flex; flex-direction:column; gap:10px;">
            <a href="{{ route('login') }}" class="btn btn-primary w-full" style="justify-content:center; text-decoration:none; font-weight:700; border-radius:0; padding:12px 16px; font-size:14px; background:var(--c-primary); color:#000; box-shadow:0 4px 14px rgba(0,255,102,0.25);">
              <i class="fas fa-sign-in-alt me-2"></i> Login Sekarang
            </a>
            <button type="button" class="btn btn-outline w-full" style="justify-content:center; border-radius:0; border:1px solid rgba(255,255,255,0.2); color:var(--c-on-dark); padding:10px 16px; font-size:14px; background:transparent;" onclick="closeSweetAlert()">
              Nanti Saja
            </button>
          </div>
        </div>
      `;
      
      overlay.onclick = function(e) {
          if (e.target === overlay) closeSweetAlert();
      };
      
      void overlay.offsetWidth; // trigger reflow
      overlay.classList.add('open');
  }

  function showLoadingPopup(title, message) {
      let overlay = document.getElementById('sweet-alert-overlay');
      if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'sweet-alert-overlay';
          overlay.className = 'modal-overlay';
          document.body.appendChild(overlay);
      }
      
      let dotsHtml = '<style>@keyframes dotsRingAnim { 0%, 100% { transform: scale(1); opacity: 1; } 50% { transform: scale(0.5); opacity: 0.3; } }</style>';
      dotsHtml += '<div style="position:relative; width:48px; height:48px; margin: 0 auto 32px auto;">';
      for (let i = 0; i < 8; i++) {
          dotsHtml += `<div style="position:absolute; top:0; left:50%; width:8px; height:8px; margin-left:-4px; transform-origin:4px 24px; transform:rotate(${i * 45}deg);">
                          <div style="width:100%; height:100%; background:var(--c-primary); border-radius:50%; animation: dotsRingAnim 1.5s ease-in-out infinite; animation-delay:${i * 0.15}s;"></div>
                       </div>`;
      }
      dotsHtml += '</div>';
      
      overlay.innerHTML = `
        <div class="modal" style="text-align:center; padding: 40px 24px; max-width: 360px;">
          ${dotsHtml}
          <h3 style="font-size: 24px; font-weight: 800; margin: 0 0 12px 0;">${title}</h3>
          <p style="color: var(--c-body); margin: 0; line-height: 1.5;">${message}</p>
        </div>
      `;
      
      void overlay.offsetWidth;
      overlay.classList.add('open');
  }

  function closeCustomAlert() {
      const overlay = document.getElementById('custom-alert-overlay');
      if (overlay) {
          overlay.classList.remove('open');
          const modal = overlay.querySelector('.modal');
          if(modal) modal.style.transform = 'scale(0.9)';
          setTimeout(() => overlay.remove(), 300);
      }
  }

  function showCustomAlert(title, message, type = 'info') {
      let overlay = document.getElementById('custom-alert-overlay');
      if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'custom-alert-overlay';
          overlay.className = 'modal-overlay';
          document.body.appendChild(overlay);
      }
      
      let iconClass = 'fa-info-circle';
      let iconColor = 'var(--c-primary)';
      if(type === 'warning') {
          iconClass = 'fa-triangle-exclamation';
          iconColor = 'var(--c-warning)';
      } else if(type === 'error') {
          iconClass = 'fa-circle-xmark';
          iconColor = 'var(--c-error)';
      } else if(type === 'success') {
          iconClass = 'fa-circle-check';
      }
      
      overlay.innerHTML = `
        <div class="modal">
          <div class="modal-header">
            <h3 style="margin:0; display:flex; align-items:center; gap:8px;">
              <i class="fa-solid ${iconClass}" style="color:${iconColor};"></i> ${title}
            </h3>
            <button class="btn btn-ghost" onclick="closeCustomAlert()"><i class="fa fa-times"></i></button>
          </div>
          <div class="modal-body">
            <p style="color:var(--c-body); line-height:1.6; margin:0;">${message}</p>
          </div>
          <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeCustomAlert()">OK</button>
          </div>
        </div>
      `;
      
      void overlay.offsetWidth; // trigger reflow
      overlay.classList.add('open');
  }

  function closeCustomConfirm() {
      const overlay = document.getElementById('custom-confirm-overlay');
      if (overlay) {
          overlay.classList.remove('open');
          setTimeout(() => overlay.remove(), 300);
      }
  }

  function confirmAction(event, formElement, title, message) {
      event.preventDefault();
      
      let overlay = document.getElementById('custom-confirm-overlay');
      if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'custom-confirm-overlay';
          overlay.className = 'modal-overlay';
          document.body.appendChild(overlay);
      }
      
      overlay.innerHTML = `
        <div class="modal">
          <div class="modal-header">
            <h3 style="margin:0; display:flex; align-items:center; gap:8px;">
              <i class="fa-solid fa-circle-question" style="color:var(--c-warning);"></i> ${title}
            </h3>
            <button type="button" class="btn btn-ghost" onclick="closeCustomConfirm()"><i class="fa fa-times"></i></button>
          </div>
          <div class="modal-body">
            <p style="color:var(--c-body); line-height:1.6; margin:0;">${message}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeCustomConfirm()">Batal</button>
            <button type="button" class="btn btn-primary" id="confirm-action-btn">Ya, Lanjutkan</button>
          </div>
        </div>
      `;
      
      document.getElementById('confirm-action-btn').onclick = function() {
          formElement.submit();
      };
      
      void overlay.offsetWidth;
      overlay.classList.add('open');
      return false;
  }

  function showCustomConfirmCallback(title, message, callback) {
      let overlay = document.getElementById('custom-confirm-overlay');
      if (!overlay) {
          overlay = document.createElement('div');
          overlay.id = 'custom-confirm-overlay';
          overlay.className = 'modal-overlay';
          document.body.appendChild(overlay);
      }
      
      overlay.innerHTML = `
        <div class="modal">
          <div class="modal-header">
            <h3 style="margin:0; display:flex; align-items:center; gap:8px;">
              <i class="fa-solid fa-circle-question" style="color:var(--c-warning);"></i> ${title}
            </h3>
            <button type="button" class="btn btn-ghost" onclick="closeCustomConfirm()"><i class="fa fa-times"></i></button>
          </div>
          <div class="modal-body">
            <p style="color:var(--c-body); line-height:1.6; margin:0;">${message}</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="closeCustomConfirm()">Batal</button>
            <button type="button" class="btn btn-primary" id="confirm-action-btn-cb">Ya, Lanjutkan</button>
          </div>
        </div>
      `;
      
      document.getElementById('confirm-action-btn-cb').onclick = function() {
          closeCustomConfirm();
          if(typeof callback === 'function') callback();
      };
      
      void overlay.offsetWidth;
      overlay.classList.add('open');
  }

  // Flash Session Alerts
  document.addEventListener('DOMContentLoaded', () => {
      @if(session('success'))
        showSweetAlert('Berhasil!', '{{ addslashes(session("success")) }}', 'success');
      @endif
      @if(session('error'))
        showSweetAlert('Gagal!', '{{ addslashes(session("error")) }}', 'error');
      @endif
      @if(session('warning'))
        showSweetAlert('Perhatian!', '{{ addslashes(session("warning")) }}', 'warning');
      @endif
      @if($errors->any())
        showSweetAlert('Validasi Gagal!', '{!! addslashes($errors->first()) !!}', 'error');
      @endif
  });

  function updateBadge(id, count) {
    var el = document.getElementById(id);
    if (count > 0) {
      if (!el) {
        var selector = id === 'wishlist-count' ? 'a[href*="wishlist"]' : 'a[href*="cart"]';
        var link = document.querySelector(selector);
        if (link) {
          var badge = document.createElement('span');
          badge.className = 'nav-badge';
          badge.id = id;
          badge.textContent = count;
          badge.style.position = 'absolute';
          badge.style.top = '-8px';
          badge.style.right = '-12px';
          link.appendChild(badge);
        }
      } else {
        el.textContent = count;
      }
    } else if (el) {
      el.remove();
    }
  }

  // Global Event Delegation for Wishlist and Cart
  document.addEventListener('click', async function(e) {
      // 1. Wishlist Button
      const wishlistBtn = e.target.closest('.wishlist-btn');
      if (wishlistBtn && !wishlistBtn.dataset.preventGlobal) {
          e.preventDefault();
          if (!IS_AUTHENTICATED) {
              showLoginRequiredPopup('wishlist');
              return;
          }
          if (wishlistBtn.disabled) return;
          wishlistBtn.disabled = true;
          
          const gameSlug = wishlistBtn.dataset.gameSlug;
          try {
              const res = await fetch('/wishlist/' + gameSlug, {
                  method: 'POST',
                  headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
              });
              if (res.status === 401) {
                  wishlistBtn.disabled = false;
                  showLoginRequiredPopup('wishlist');
                  return;
              }
              const data = await res.json();
              wishlistBtn.disabled = false;
              
              if (data.error) {
                  if (typeof showCenterPopup === 'function') showCenterPopup(data.error, false);
                  return;
              }
              
              wishlistBtn.classList.toggle('active', data.in_wishlist);
              if (wishlistBtn.classList.contains('btn')) {
                  wishlistBtn.classList.toggle('btn-primary', data.in_wishlist);
                  wishlistBtn.classList.toggle('btn-outline', !data.in_wishlist);
              }
              
              const icon = wishlistBtn.querySelector('i.fa-heart');
              if (icon) {
                  icon.className = data.in_wishlist ? 'fas fa-heart' : 'far fa-heart';
              }
              
              if (typeof updateBadge === 'function') updateBadge('wishlist-count', data.count);
              if (typeof showCenterPopup === 'function') showCenterPopup(data.message, true);
          } catch (err) {
              wishlistBtn.disabled = false;
              if (typeof showCenterPopup === 'function') showCenterPopup('Silakan login terlebih dahulu.', false);
          }
      }

      // 2. Cart Button
      const cartBtn = e.target.closest('.cart-btn');
      if (cartBtn && !cartBtn.dataset.preventGlobal) {
          e.preventDefault();
          if (!IS_AUTHENTICATED) {
              showLoginRequiredPopup('keranjang');
              return;
          }
          if (cartBtn.disabled) return;
          cartBtn.disabled = true;
          
          const gameSlug = cartBtn.dataset.gameSlug;
          try {
              const res = await fetch('/cart/' + gameSlug, {
                  method: 'POST',
                  headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
              });
              if (res.status === 401) {
                  cartBtn.disabled = false;
                  showLoginRequiredPopup('keranjang');
                  return;
              }
              const data = await res.json();
              cartBtn.disabled = false;
              
              if (data.error) {
                  if (typeof showCenterPopup === 'function') showCenterPopup(data.error, false);
                  return;
              }
              
              cartBtn.classList.toggle('active', data.in_cart);
              if (cartBtn.classList.contains('btn')) {
                  cartBtn.classList.toggle('btn-primary', data.in_cart);
                  cartBtn.classList.toggle('btn-outline', !data.in_cart);
                  cartBtn.innerHTML = `<i class="fas fa-shopping-cart me-1"></i>${data.in_cart ? 'Di Keranjang' : 'Keranjang'}`;
              }
              
              if (typeof updateBadge === 'function') updateBadge('cart-count', data.count);
              if (data.in_cart) {
                  if (typeof showCenterPopup === 'function') showCenterPopup(data.message, true);
              } else {
                  if (typeof showCenterPopup === 'function') showCenterPopup(data.message || 'Dihapus dari keranjang', true);
              }
          } catch (err) {
              cartBtn.disabled = false;
              if (typeof showCenterPopup === 'function') showCenterPopup('Silakan login terlebih dahulu.', false);
          }
      }
  });

  // Global click listener to close pinned profile dropdown
  document.addEventListener('click', function(e) {
      const wrapper = document.querySelector('.profile-dropdown-wrapper');
      if (wrapper && !wrapper.contains(e.target)) {
          wrapper.dataset.pinned = 'false';
          const dropdown = wrapper.querySelector('.profile-dropdown');
          if (dropdown) dropdown.style.display = 'none';
      }
  });

  // Scroll Restoration (Fix Position on Back)
  window.addEventListener("beforeunload", function() {
    sessionStorage.setItem('scrollPos_' + window.location.pathname + window.location.search, window.scrollY);
  });
  document.addEventListener("DOMContentLoaded", function() {
    // Persistent Background Animation
    if (!sessionStorage.getItem('bg_start_time')) {
        sessionStorage.setItem('bg_start_time', Date.now());
    }
    const startTime = parseInt(sessionStorage.getItem('bg_start_time'));
    const elapsedMs = Date.now() - startTime;
    const elapsedSecs = (elapsedMs / 1000) % 8; // 4s alternate = 8s full loop
    
    const style = document.createElement('style');
    style.innerHTML = `body::before { animation-delay: -${elapsedSecs}s !important; }`;
    document.head.appendChild(style);

    // Check if the page is loaded from cache/back-forward history
    if ((window.performance && window.performance.navigation && window.performance.navigation.type === 2) || 
        (window.performance && window.performance.getEntriesByType && window.performance.getEntriesByType("navigation").length > 0 && window.performance.getEntriesByType("navigation")[0].type === "back_forward")) {
      
      var scrollPos = sessionStorage.getItem('scrollPos_' + window.location.pathname + window.location.search);
      if (scrollPos) {
        // Coba segera setelah DOM ready
        window.scrollTo(0, parseInt(scrollPos));
        // Coba lagi setelah sedikit jeda (menunggu gambar termuat/CSS grid ter-render)
        setTimeout(() => window.scrollTo(0, parseInt(scrollPos)), 50);
        setTimeout(() => window.scrollTo(0, parseInt(scrollPos)), 300);
      }
    }
  });
</script>

@stack('scripts')
    <script>
        function toggleMobileMenu() {
            const drawer = document.getElementById('mobile-menu-drawer');
            const overlay = document.getElementById('mobile-menu-overlay');
            if (!drawer || !overlay) return;

            const isOpen = drawer.classList.contains('is-open');
            if (isOpen) {
                drawer.classList.remove('is-open');
                drawer.style.transform = 'translateX(100%)';
                overlay.style.opacity = '0';
                document.body.style.overflow = '';
                setTimeout(() => {
                    overlay.style.display = 'none';
                }, 300);
            } else {
                overlay.style.display = 'block';
                document.body.style.overflow = 'hidden';
                setTimeout(() => {
                    drawer.classList.add('is-open');
                    drawer.style.transform = 'translateX(0)';
                    overlay.style.opacity = '1';
                }, 10);
            }
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const drawer = document.getElementById('mobile-menu-drawer');
                if (drawer && drawer.classList.contains('is-open')) {
                    toggleMobileMenu();
                }
            }
        });
    </script>
</body>
</html>
