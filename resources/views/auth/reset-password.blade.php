@extends('layouts.auth')

@section('form')
<h1>Reset Password</h1>
<p class="subtitle">Buat password baru yang kuat untuk akunmu.</p>

<form method="POST" action="{{ route('password.store') }}">
  @csrf

  {{-- Password Reset Token --}}
  <input type="hidden" name="token" value="{{ $request->route('token') }}">

  <div class="form-group">
    <label for="email" class="form-label">Alamat Email</label>
    <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
           class="form-control {{ $errors->has('email') ? 'input-error' : '' }}"
           placeholder="contoh@email.com" required autofocus autocomplete="username">
    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
  </div>

  <div class="form-group">
    <label for="password" class="form-label">Password Baru</label>
    <div style="position:relative;">
      <input id="password" type="password" name="password"
             class="form-control {{ $errors->has('password') ? 'input-error' : '' }}"
             placeholder="Minimal 8 karakter" required autocomplete="new-password" style="padding-right:44px;">
      <button type="button" onclick="toggleVisibility('password', this)"
              style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--c-mute);cursor:pointer;padding:0;font-size:16px;line-height:1;transition:color 0.2s;">
        <i class="fas fa-eye"></i>
      </button>
    </div>
    @error('password') <div class="error-msg">{{ $message }}</div> @enderror
  </div>

  <div class="form-group">
    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
    <div style="position:relative;">
      <input id="password_confirmation" type="password" name="password_confirmation"
             class="form-control {{ $errors->has('password_confirmation') ? 'input-error' : '' }}"
             placeholder="Ulangi password baru" required autocomplete="new-password" style="padding-right:44px;">
      <button type="button" onclick="toggleVisibility('password_confirmation', this)"
              style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--c-mute);cursor:pointer;padding:0;font-size:16px;line-height:1;transition:color 0.2s;">
        <i class="fas fa-eye"></i>
      </button>
    </div>
    @error('password_confirmation') <div class="error-msg">{{ $message }}</div> @enderror
  </div>

  <button type="submit" class="btn btn-primary w-full" style="justify-content:center; margin-top:8px;">
    <i class="fa fa-lock"></i> Simpan Password Baru
  </button>

  <p style="text-align:center;margin-top:24px;color:var(--c-mute);font-size:14px;">
    Ingat password kamu?
    <a href="{{ route('login') }}" style="color:var(--c-primary);font-weight:700;">Masuk sekarang</a>
  </p>
</form>

<script>
function toggleVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
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
