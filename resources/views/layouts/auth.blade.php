<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Auth') — GameVault</title>
  <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}?v={{ time() }}">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: var(--c-canvas); }
    .auth-grid { display: grid; grid-template-columns: 1fr 1fr; min-height: 100vh; width: 100%; }
    .auth-panel { background: linear-gradient(135deg, #0f1710 0%, #050a06 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 48px; position: relative; overflow: hidden; border-right: 1px solid var(--c-hairline); }
    .auth-accent { position: absolute; width: 800px; height: 800px; background: radial-gradient(circle, rgba(118,185,0,0.15) 0%, transparent 60%); top: -300px; right: -200px; pointer-events: none; }
    .auth-accent-2 { position: absolute; width: 600px; height: 600px; background: radial-gradient(circle, rgba(118,185,0,0.1) 0%, transparent 60%); bottom: -200px; left: -150px; pointer-events: none; }
    .auth-panel-brand { font-size: 42px; font-weight: 800; color: #fff; text-align: center; letter-spacing: -1px; }
    .auth-panel-brand span { color: var(--c-primary); }
    .auth-form-panel { background: var(--c-canvas); padding: 48px; display: flex; flex-direction: column; align-items: center; justify-content: center; border-left: 1px solid var(--c-hairline-strong); }
    .auth-form-wrap { width: 100%; max-width: 400px; }
    .auth-form-wrap h1 { font-size: 28px; font-weight: 700; margin-bottom: 8px; color: var(--c-on-dark); }
    .auth-form-wrap p.subtitle { color: var(--c-mute); margin-bottom: 32px; font-size: 15px; }
    @media (max-width: 768px) {
      .auth-grid { grid-template-columns: 1fr; }
      .auth-panel { display: none; }
      .auth-form-panel { border-left: none; }
    }
  </style>
</head>
<body>
<div class="auth-grid">
  <!-- Left: Branding -->
  <div class="auth-panel">
    <div class="auth-accent"></div>
    <div class="auth-accent-2"></div>
    <div style="position:relative;z-index:1;text-align:center">
      <div class="auth-panel-brand" style="display:flex; align-items:center; justify-content:center; gap:12px;">
        <img src="{{ asset('images/logo.png') }}" alt="GameVault Logo" style="height:36px; width:auto; drop-shadow(0 0 15px rgba(118,185,0,0.6));">
        <span>Game <span style="color:var(--c-primary)">Vault</span></span>
      </div>
      <p style="color:rgba(255,255,255,0.6);margin-top:16px;font-size:16px;max-width:300px;line-height:1.7">
        Platform game digital terpercaya. Ribuan judul game dengan harga terbaik.
      </p>
      <div style="display:flex;gap:20px;justify-content:center;margin-top:40px">
        <div style="text-align:center">
          <div style="font-size:28px;font-weight:700;color:#76b900">20+</div>
          <div style="font-size:12px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.5px">Game Tersedia</div>
        </div>
        <div style="width:1px;background:rgba(255,255,255,0.1)"></div>
        <div style="text-align:center">
          <div style="font-size:28px;font-weight:700;color:#76b900">100%</div>
          <div style="font-size:12px;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.5px">Aman & Terpercaya</div>
        </div>
      </div>
    </div>
  </div>
  <!-- Right: Form -->
  <div class="auth-form-panel">
    <div class="auth-form-wrap">
      @yield('form')
    </div>
  </div>
</div>
</body>
</html>
