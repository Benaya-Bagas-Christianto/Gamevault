@extends('layouts.app')

@section('title', 'Ganti Email')

@section('content')
<div class="container section">
  <div class="mb-lg">
    <a href="{{ route('profile.index') }}" class="btn-ghost text-mute"><i class="fa fa-arrow-left"></i> Kembali ke Profil</a>
  </div>

  <div class="card" style="max-width:500px;margin:0 auto">
    <h1 class="t-heading-xl mb-sm">Ganti Email</h1>
    
    @php
      $step = session('otp_step', 'init');
    @endphp

    @if($step === 'init')
      <!-- Step 1: Init / Request Old Email OTP -->
      <p class="text-mute mb-xl">Demi keamanan, kami perlu memverifikasi bahwa ini benar Anda. Kami akan mengirimkan OTP ke email Anda saat ini: <strong>{{ auth()->user()->email }}</strong>.</p>

      <form action="{{ route('profile.email.send-old-otp') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-primary w-full">Kirim OTP ke Email Saat Ini</button>
      </form>

    @elseif($step === 'verify_old')
      <!-- Step 2: Verify Old Email OTP -->
      <p class="text-mute mb-xl" style="text-align: center; line-height: 1.6;">Kami telah mengirim kode OTP ke <strong style="color:var(--c-ink);">{{ auth()->user()->email }}</strong>.<br>Silakan periksa inbox kamu (berlaku 5 menit).</p>

      <form action="{{ route('profile.email.verify-old-otp') }}" method="POST">
        @csrf
        <div class="form-group" style="margin-bottom: 24px;">
          <label for="code" class="form-label" style="text-align: center; display: block; font-size: 0.9rem; margin-bottom: 12px; color:var(--c-mute);">KODE OTP (6 DIGIT)</label>
          <input type="text" name="code" id="code" class="form-control" placeholder="0 0 0 0 0 0" required maxlength="6" style="letter-spacing:12px;font-size:32px;text-align:center;font-weight:800;font-family:monospace; padding: 16px; border-radius: 0; border: 2px solid var(--c-primary); color: var(--c-ink); background: rgba(0,0,0,0.2);">
        </div>
        <button type="submit" class="btn btn-primary w-full" style="padding: 14px; font-size: 1.1rem; font-weight: 700; border-radius: 0; margin-bottom: 16px;">Verifikasi Email Lama</button>
      </form>

      <form action="{{ route('profile.email.send-old-otp') }}" method="POST">
        @csrf
        <button type="submit" style="background: transparent; border: none; width: 100%; color: var(--c-mute); text-decoration: underline; cursor: pointer; padding: 8px; font-size: 0.95rem; transition: color 0.2s;" onmouseenter="this.style.color='var(--c-ink)';" onmouseleave="this.style.color='var(--c-mute)';">Kirim Ulang Kode</button>
      </form>

    @elseif($step === 'input_new')
      <!-- Step 3: Input New Email -->
      <div style="background:rgba(46, 204, 113, 0.1); border:1px solid rgba(46, 204, 113, 0.4); padding:12px; margin-bottom:20px; border-radius: 0; color:#2ecc71; text-align:center;">
        <i class="fas fa-check-circle"></i> Email lama berhasil diverifikasi.
      </div>
      <p class="text-mute mb-xl">Masukkan email baru Anda. Kami akan mengirimkan OTP ke email ini untuk memastikan Anda memiliki akses.</p>

      <form action="{{ route('profile.email.send-new-otp') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="email" class="form-label">Email Baru</label>
          <input type="email" name="email" id="email" class="form-control" placeholder="nama@email.com" required>
        </div>
        <button type="submit" class="btn btn-primary w-full">Kirim OTP ke Email Baru</button>
      </form>

    @elseif($step === 'verify_new')
      <!-- Step 4: Verify New Email OTP -->
      <p class="text-mute mb-xl" style="text-align: center; line-height: 1.6;">Kami telah mengirim kode OTP ke email baru Anda <strong style="color:var(--c-ink);">{{ session('otp_new_email') }}</strong>.<br>Silakan periksa inbox kamu (berlaku 5 menit).</p>

      <form action="{{ route('profile.email.verify-new-otp') }}" method="POST">
        @csrf
        <div class="form-group" style="margin-bottom: 24px;">
          <label for="code" class="form-label" style="text-align: center; display: block; font-size: 0.9rem; margin-bottom: 12px; color:var(--c-mute);">KODE OTP (6 DIGIT)</label>
          <input type="text" name="code" id="code" class="form-control" placeholder="0 0 0 0 0 0" required maxlength="6" style="letter-spacing:12px;font-size:32px;text-align:center;font-weight:800;font-family:monospace; padding: 16px; border-radius: 0; border: 2px solid var(--c-primary); color: var(--c-ink); background: rgba(0,0,0,0.2);">
        </div>
        <button type="submit" class="btn btn-primary w-full" style="padding: 14px; font-size: 1.1rem; font-weight: 700; border-radius: 0; margin-bottom: 16px;">Verifikasi & Simpan Email Baru</button>
      </form>

      <form action="{{ route('profile.email.send-new-otp') }}" method="POST">
        @csrf
        <input type="hidden" name="email" value="{{ session('otp_new_email') }}">
        <button type="submit" style="background: transparent; border: none; width: 100%; color: var(--c-mute); text-decoration: underline; cursor: pointer; padding: 8px; font-size: 0.95rem; transition: color 0.2s;" onmouseenter="this.style.color='var(--c-ink)';" onmouseleave="this.style.color='var(--c-mute)';">Kirim Ulang Kode</button>
      </form>
    @endif
  </div>
</div>
@endsection
