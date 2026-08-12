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
<div class="mobile-menu-overlay" id="mobile-menu-overlay" onclick="toggleMobileMenu()" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.7); z-index:9998; opacity:0; transition:opacity 0.3s;"></div>
<div class="mobile-menu-drawer" id="mobile-menu-drawer" style="position:fixed; top:0; right:-300px; width:280px; height:100vh; background:var(--c-surface-elevated); border-left:1px solid var(--c-hairline); z-index:9999; transition:right 0.3s ease; padding:24px; display:flex; flex-direction:column;">
  <div style="display:flex; justify-content:flex-end; margin-bottom:32px;">
    <button onclick="toggleMobileMenu()" style="background:none; border:none; color:var(--c-on-dark); font-size:24px; cursor:pointer;">
      <i class="fas fa-times"></i>
    </button>
  </div>
  
  <ul style="list-style:none; padding:0; margin:0; display:flex; flex-direction:column; gap:16px;">
    <li><a href="{{ route('home') }}" class="mobile-nav-link" style="color:var(--c-on-dark); text-decoration:none; font-size:18px; font-weight:700;">Beranda</a></li>
    <li><a href="{{ route('store') }}" class="mobile-nav-link" style="color:var(--c-on-dark); text-decoration:none; font-size:18px; font-weight:700;">Toko</a></li>
    <li><a href="{{ route('categories') }}" class="mobile-nav-link" style="color:var(--c-on-dark); text-decoration:none; font-size:18px; font-weight:700;">Kategori</a></li>
    <li><a href="{{ route('bantuan') }}" class="mobile-nav-link" style="color:var(--c-on-dark); text-decoration:none; font-size:18px; font-weight:700;">Bantuan</a></li>
  </ul>
  
  <div style="margin-top:auto; padding-top:24px; border-top:1px solid var(--c-hairline); display:flex; flex-direction:column; gap:12px;">
    @auth
      <a href="{{ route('profile.index') }}" style="display:flex; align-items:center; gap:12px; color:var(--c-on-dark); text-decoration:none; font-size:16px;">
        <i class="fas fa-user" style="width:20px; text-align:center; color:var(--c-primary);"></i> Profil Saya
      </a>
      <a href="{{ route('library.index') }}" style="display:flex; align-items:center; gap:12px; color:var(--c-on-dark); text-decoration:none; font-size:16px;">
        <i class="fas fa-gamepad" style="width:20px; text-align:center; color:var(--c-primary);"></i> Library Game
      </a>
      <a href="{{ route('wishlist.index') }}" style="display:flex; align-items:center; gap:12px; color:var(--c-on-dark); text-decoration:none; font-size:16px;">
        <i class="fas fa-heart" style="width:20px; text-align:center; color:var(--c-primary);"></i> Wishlist
      </a>
      @if(auth()->user()->role === 'admin')
      <a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; gap:12px; color:var(--c-on-dark); text-decoration:none; font-size:16px;">
        <i class="fas fa-shield-alt" style="width:20px; text-align:center; color:var(--c-primary);"></i> Buka Admin Panel
      </a>
      @endif
      <form method="POST" action="{{ route('logout') }}" style="margin:0; margin-top:12px;">
        @csrf
        <button type="submit" style="background:none; border:none; display:flex; align-items:center; gap:12px; color:var(--c-error); text-decoration:none; font-size:16px; padding:0; cursor:pointer;">
          <i class="fas fa-sign-out-alt" style="width:20px; text-align:center;"></i> Keluar Akun
        </button>
      </form>
    @else
      <a href="{{ route('login') }}" class="btn btn-outline" style="width:100%; border-radius:0;">Login</a>
      <a href="{{ route('register') }}" class="btn btn-primary" style="width:100%; border-radius:0; margin-top:8px;">Get Started</a>
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
<footer class="footer" style="margin-top:auto; background:#050505; padding:64px 48px; border-top:1px solid var(--c-hairline);">
  <div class="container" style="max-width:1280px; padding:0;">
    <style>
      .footer-link {
        color: var(--c-on-dark-mute);
        font-size: 15px;
        text-decoration: none;
        transition: color 0.2s ease;
      }
      .footer-link:hover {
        color: var(--c-primary) !important;
      }
    </style>
    <div style="display:grid; grid-template-columns:repeat(4, 1fr); gap:24px; margin-bottom:64px;">
      <div>
        <h4 style="font-size:16px;font-weight:700;color:var(--c-on-dark);margin-bottom:16px;text-transform:none;">GameVault</h4>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:12px;">
          <li><a href="#" class="footer-link">Tentang Kami</a></li>
          <li><a href="#" class="footer-link">Karir</a></li>
          <li><a href="#" class="footer-link">Berita &amp; Update</a></li>
        </ul>
      </div>
      <div>
        <h4 style="font-size:16px;font-weight:700;color:var(--c-on-dark);margin-bottom:16px;text-transform:none;">Jelajahi</h4>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:12px;">
          <li><a href="{{ route('home') }}" class="footer-link">Semua Game</a></li>
          <li><a href="{{ route('home') }}?genres[]=RPG" class="footer-link">Game RPG</a></li>
          <li><a href="{{ route('home') }}?price[]=free" class="footer-link">Game Gratis</a></li>
        </ul>
      </div>
      <div>
        <h4 style="font-size:16px;font-weight:700;color:var(--c-on-dark);margin-bottom:16px;text-transform:none;">Akun Anda</h4>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:12px;">
          @auth
            <li><a href="{{ route('profile.index') }}" class="footer-link">Profil Saya</a></li>
            <li><a href="{{ route('library.index') }}" class="footer-link">Library Game</a></li>
          @else
            <li><a href="{{ route('login') }}" class="footer-link">Masuk / Login</a></li>
            <li><a href="{{ route('register') }}" class="footer-link">Daftar Akun Baru</a></li>
          @endauth
        </ul>
      </div>
      <div>
        <h4 style="font-size:16px;font-weight:700;color:var(--c-on-dark);margin-bottom:16px;text-transform:none;">Bantuan</h4>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:12px;">
          <li><a href="{{ route('bantuan') }}#cara-membeli" class="footer-link">Cara Membeli</a></li>
          <li><a href="{{ route('bantuan') }}#metode-pembayaran" class="footer-link">Metode Pembayaran</a></li>
          <li><a href="{{ route('bantuan') }}#kebijakan-refund" class="footer-link">Kebijakan Refund</a></li>
          <li><a href="{{ route('bantuan') }}#syarat-ketentuan" class="footer-link">Syarat &amp; Ketentuan</a></li>
          <li><a href="{{ route('bantuan') }}#privasi" class="footer-link">Privasi</a></li>
        </ul>
      </div>
    </div>
    <div class="footer-bottom" style="display:flex; justify-content:space-between; align-items:center; padding:24px 0; border-top:1px solid var(--c-hairline); color:var(--c-mute); font-size:14px;">
      <span>&copy; {{ date('Y') }} <span style="font-weight:800; letter-spacing:0.5px; color:var(--c-on-dark); font-family:var(--font-heading);">Game<span style="color:var(--c-primary);">Vault</span></span>. All rights reserved.</span>
      <div style="display:flex; gap:6px; flex-wrap:wrap; align-items:center;">
        <img src="{{ asset('images/mastercard.png') }}" height="20" alt="Mastercard" style="object-fit:contain;background:#fff;border-radius:2px;padding:2px;">
        <img src="{{ asset('images/visa.png') }}" height="16" alt="Visa" style="object-fit:contain;background:#fff;border-radius:2px;padding:2px;">
        <img src="{{ asset('images/dana.png') }}" height="20" alt="Dana" style="object-fit:contain;background:#fff;border-radius:2px;padding:2px;">
        <img src="{{ asset('images/gopay.png') }}" height="16" alt="GoPay" style="object-fit:contain;background:#fff;border-radius:2px;padding:2px;">
        <img src="{{ asset('images/qris.png') }}" height="16" alt="QRIS" style="object-fit:contain;background:#fff;border-radius:2px;padding:2px;">
        <img src="{{ asset('images/alfamart.png') }}" height="16" alt="Alfamart" style="object-fit:contain;background:#fff;border-radius:2px;padding:2px;">
        <img src="{{ asset('images/indomaret.png') }}" height="16" alt="Indomaret" style="object-fit:contain;background:#fff;border-radius:2px;padding:2px;">
      </div>
    </div>
  </div>
</footer>
</div> <!-- End Main Area Flex Container -->
</div> <!-- End App Wrapper -->

<!-- ═══ Toast Container ════════════════════════════════════ -->
<div class="toast-container" id="toast-container"></div>

<!-- ═══ Global JS ══════════════════════════════════════════ -->
<script>
  const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

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
          if (wishlistBtn.disabled) return;
          wishlistBtn.disabled = true;
          
          const gameSlug = wishlistBtn.dataset.gameSlug;
          try {
              const res = await fetch('/wishlist/' + gameSlug, {
                  method: 'POST',
                  headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
              });
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
              if (typeof showCenterPopup === 'function') showCenterPopup('Terjadi kesalahan. Silakan login terlebih dahulu.', false);
          }
      }

      // 2. Cart Button
      const cartBtn = e.target.closest('.cart-btn');
      if (cartBtn && !cartBtn.dataset.preventGlobal) {
          e.preventDefault();
          if (cartBtn.disabled) return;
          cartBtn.disabled = true;
          
          const gameSlug = cartBtn.dataset.gameSlug;
          try {
              const res = await fetch('/cart/' + gameSlug, {
                  method: 'POST',
                  headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' }
              });
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
              if (typeof showCenterPopup === 'function') showCenterPopup(data.message, true);
          } catch (err) {
              cartBtn.disabled = false;
              if (typeof showCenterPopup === 'function') showCenterPopup('Terjadi kesalahan. Silakan login terlebih dahulu.', false);
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
            if (drawer.style.right === '0px') {
                drawer.style.right = '-300px';
                overlay.style.opacity = '0';
                setTimeout(() => overlay.style.display = 'none', 300);
            } else {
                overlay.style.display = 'block';
                setTimeout(() => {
                    drawer.style.right = '0px';
                    overlay.style.opacity = '1';
                }, 10);
            }
        }
    </script>
</body>
</html>
