@extends('layouts.auth')

@section('form')
<h1>Selamat Datang</h1>
<p class="subtitle">Masuk ke akun GameVault kamu</p>

<!-- Session Status -->
@if (session('status'))
  <div class="alert alert-success mb-lg">{{ session('status') }}</div>
@endif

{{-- Error Box - tampil kalau ada error login --}}
@if ($errors->any())
<div style="
  background: rgba(220, 38, 38, 0.08);
  border: 1px solid rgba(220, 38, 38, 0.4);
  border-left: 4px solid #dc2626;
  border-radius: 0;
  padding: 14px 16px;
  margin-bottom: 20px;
  display: flex;
  align-items: flex-start;
  gap: 12px;
">
  <i class="fas fa-circle-xmark" style="color:#dc2626; font-size:18px; flex-shrink:0; margin-top:2px;"></i>
  <div>
    @foreach ($errors->all() as $error)
      <p style="color:#fca5a5; font-size:0.88rem; font-weight:600; margin:0; line-height:1.5;">{{ $error }}</p>
    @endforeach
  </div>
</div>
@endif

<form method="POST" action="{{ route('login') }}" autocomplete="off">
  @csrf

  <div class="form-group">
    <label for="login" class="form-label">Email / Username</label>
    <input id="login" type="text" name="login" value="{{ old('login') }}"
           class="form-control {{ $errors->has('login') ? 'input-error' : '' }}" autocomplete="new-password"
           placeholder="Email atau Username" required>
  </div>

  <div class="form-group">
    <label for="password" class="form-label">Password</label>
    <div style="position:relative;">
      <input id="password" type="password" name="password"
             class="form-control {{ $errors->has('password') ? 'input-error' : '' }}" autocomplete="new-password"
             placeholder="••••••••" required style="padding-right:44px;">
      <button type="button" onclick="toggleLoginPassword()" id="toggle-pw-btn"
              style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--c-mute);cursor:pointer;padding:0;font-size:16px;line-height:1;transition:color 0.2s;">
        <i id="toggle-pw-icon" class="fas fa-eye"></i>
      </button>
    </div>
  </div>

  <div class="flex-between mb-lg">
    <label class="checkbox-item">
      <input type="checkbox" name="remember"> Ingat saya
    </label>
    @if (Route::has('password.request'))
      <a href="{{ route('password.request') }}" style="color:var(--c-mute);font-size:14px;text-decoration:none;transition:color 0.2s;"
         onmouseover="this.style.color='var(--c-primary)'" onmouseout="this.style.color='var(--c-mute)'">Lupa password?</a>
    @endif
  </div>

  <button type="submit" class="btn btn-primary w-full" style="justify-content:center">
    <i class="fa fa-sign-in-alt"></i> Masuk
  </button>

  <p style="text-align:center;margin-top:20px;color:var(--c-mute);font-size:14px">
    Belum punya akun?
    <a href="{{ route('register') }}" style="color:var(--c-primary);font-weight:700">Daftar sekarang</a>
  </p>
</form>

<script>
function toggleLoginPassword() {
  const input = document.getElementById('password');
  const icon = document.getElementById('toggle-pw-icon');
  const btn = document.getElementById('toggle-pw-btn');
  if (input.type === 'password') {
    input.type = 'text';
    icon.classList.replace('fa-eye', 'fa-eye-slash');
    btn.style.color = 'var(--c-primary)';
  } else {
    input.type = 'password';
    icon.classList.replace('fa-eye-slash', 'fa-eye');
    btn.style.color = 'var(--c-mute)';
  }
}
</script>
@endsection
