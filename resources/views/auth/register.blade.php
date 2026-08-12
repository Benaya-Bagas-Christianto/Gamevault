@extends('layouts.auth')

@section('form')
<h1>Buat Akun</h1>
<p class="subtitle">Bergabung dengan jutaan gamer di GameVault</p>

<form method="POST" action="{{ route('register') }}">
  @csrf

  <div class="form-group">
    <label for="name" class="form-label">Nama Lengkap</label>
    <input id="name" type="text" name="name" value="{{ old('name') }}"
           class="form-control {{ $errors->has('name') ? 'input-error' : '' }}" autocomplete="new-password"
           placeholder="John Doe" required>
    @error('name') <div class="error-msg">{{ $message }}</div> @enderror
  </div>

  <div class="form-group">
    <label for="username" class="form-label">Username</label>
    <input id="username" type="text" name="username" value="{{ old('username') }}"
           class="form-control {{ $errors->has('username') ? 'input-error' : '' }}" autocomplete="new-password"
           placeholder="johndoe123" required>
    @error('username') <div class="error-msg">{{ $message }}</div> @enderror
  </div>

  <div class="form-group">
    <label for="email" class="form-label">Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}"
           class="form-control {{ $errors->has('email') ? 'input-error' : '' }}" autocomplete="new-password"
           placeholder="nama@email.com" required>
    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
  </div>

  <div class="form-group">
    <label for="password" class="form-label">Password</label>
    <div style="position:relative">
      <input id="password" type="password" name="password"
             class="form-control {{ $errors->has('password') ? 'input-error' : '' }}" autocomplete="new-password"
             placeholder="Minimal 8 karakter" required style="padding-right:40px;">
      <button type="button" onclick="toggleVisibility('password', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
        <i class="fas fa-eye"></i>
      </button>
    </div>
    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
  </div>

  <div class="form-group">
    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
    <div style="position:relative">
      <input id="password_confirmation" type="password" name="password_confirmation"
             class="form-control" autocomplete="new-password"
             placeholder="Ulangi password" required style="padding-right:40px;">
      <button type="button" onclick="toggleVisibility('password_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
        <i class="fas fa-eye"></i>
      </button>
    </div>
  </div>

  <div style="border-top:1px solid rgba(255,255,255,0.1);margin:20px 0 16px;"></div>
  <p style="font-size:13px;color:var(--c-mute);margin-bottom:16px;"><i class="fas fa-shield-alt" style="color:var(--c-primary);margin-right:6px;"></i>Buat PIN 6 digit untuk mengamankan transaksi kamu.</p>

  <div class="form-group">
    <label for="pin" class="form-label">PIN Transaksi (6 Digit)</label>
    <div style="position:relative">
      <input id="pin" type="password" name="pin"
             class="form-control {{ $errors->has('pin') ? 'input-error' : '' }}" autocomplete="new-password"
             placeholder="6 Digit Angka" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px;">
      <button type="button" onclick="toggleVisibility('pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
        <i class="fas fa-eye"></i>
      </button>
    </div>
    @error('pin') 
      <div class="error-msg">{{ $message }}</div> 
    @else
      <div style="font-size: 13px; color: var(--c-mute); margin-top: 8px;">Catatan: PIN tidak boleh berurutan (123456) atau angka berulang (111111, 121212).</div>
    @enderror
  </div>

  <div class="form-group">
    <label for="pin_confirmation" class="form-label">Konfirmasi PIN</label>
    <div style="position:relative">
      <input id="pin_confirmation" type="password" name="pin_confirmation"
             class="form-control" autocomplete="new-password"
             placeholder="6 Digit Angka" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px;">
      <button type="button" onclick="toggleVisibility('pin_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
        <i class="fas fa-eye"></i>
      </button>
    </div>
  </div>

  <button type="submit" class="btn btn-primary w-full" style="justify-content:center">
    <i class="fa fa-user-plus"></i> Daftar Sekarang
  </button>

  <p style="text-align:center;margin-top:20px;color:var(--c-mute);font-size:14px">
    Sudah punya akun?
    <a href="{{ route('login') }}" style="color:var(--c-primary);font-weight:700">Masuk</a>
  </p>
</form>

<script>
function toggleVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
        icon.style.color = 'var(--c-primary)';
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
        icon.style.color = 'var(--c-mute)';
    }
}
</script>
@endsection
