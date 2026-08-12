@extends('layouts.auth')

@section('form')
<h1>Lupa Password?</h1>
<p class="subtitle">Tenang! Masukkan email kamu dan kami akan kirimkan link untuk reset password.</p>

{{-- Session Status --}}
@if (session('status'))
  <div class="alert alert-success mb-lg" style="background:rgba(118,185,0,0.1); border:1px solid var(--c-primary); color:var(--c-primary); padding:12px 16px; border-radius: 0; font-size:0.9rem; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
    <i class="fa fa-check-circle"></i> {{ session('status') }}
  </div>
@endif

<form method="POST" action="{{ route('password.email') }}">
  @csrf

  <div class="form-group">
    <label for="email" class="form-label">Alamat Email</label>
    <input id="email" type="email" name="email" value="{{ old('email') }}"
           class="form-control {{ $errors->has('email') ? 'input-error' : '' }}"
           placeholder="contoh@email.com" required autofocus>
    @error('email') <div class="error-msg">{{ $message }}</div> @enderror
  </div>

  <button type="submit" class="btn btn-primary w-full" style="justify-content:center; margin-top:8px;">
    <i class="fa fa-paper-plane"></i> Kirim Link Reset Password
  </button>

  <p style="text-align:center;margin-top:24px;color:var(--c-mute);font-size:14px;">
    Ingat password kamu?
    <a href="{{ route('login') }}" style="color:var(--c-primary);font-weight:700;">Masuk sekarang</a>
  </p>
</form>
@endsection
