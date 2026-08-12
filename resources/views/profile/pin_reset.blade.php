@extends('layouts.app')

@section('title', 'Reset PIN')

@section('content')
<div class="container section">
  <div class="mb-lg">
    <a href="{{ route('profile.index') }}" class="btn-ghost text-mute"><i class="fa fa-arrow-left"></i> Kembali ke Profil</a>
  </div>

  <div class="card" style="max-width:500px;margin:0 auto">
    <h1 class="t-heading-xl mb-sm">Reset PIN</h1>
    <p class="text-mute mb-xl">Silakan masukkan PIN transaksi 6 digit baru Anda.</p>

    <form action="{{ route('profile.pin.reset') }}" method="POST">
      @csrf
      <div class="form-group">
        <label class="form-label">PIN Baru</label>
        <div style="position:relative">
          <input id="new_pin" type="password" name="new_pin" class="form-control" placeholder="6 Digit Angka" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px;">
          <button type="button" onclick="toggleVisibility('new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
            <i class="fas fa-eye"></i>
          </button>
        </div>
        @error('new_pin') 
          <div class="error-msg">{{ $message }}</div> 
        @else
          <div style="font-size: 13px; color: var(--c-mute); margin-top: 8px;">Catatan: PIN tidak boleh berurutan (123456) atau angka berulang (111111, 121212).</div>
        @enderror
      </div>
      
      <div class="form-group">
        <label class="form-label">Konfirmasi PIN Baru</label>
        <div style="position:relative">
          <input id="new_pin_confirmation" type="password" name="new_pin_confirmation" class="form-control" placeholder="6 Digit Angka" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px;">
          <button type="button" onclick="toggleVisibility('new_pin_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
            <i class="fas fa-eye"></i>
          </button>
        </div>
      </div>
      
      <button type="submit" class="btn btn-primary w-full" style="padding: 14px; font-size: 1.1rem; font-weight: 700; border-radius: 0;">Simpan PIN Baru</button>
    </form>
  </div>
</div>
@endsection

@push('scripts')
<script>
function toggleVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}
</script>
@endpush
