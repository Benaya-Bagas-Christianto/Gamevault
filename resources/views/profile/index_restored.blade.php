@extends('layouts.app', array_diff_key(get_defined_vars()
@section('title', 'Profil Saya')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container section">
  <div class="layout-sidebar">
    <!-- Sidebar / Avatar -->
    <div class="card text-center">
      <div style="position:relative;width:150px;height:150px;margin:0 auto 16px">
        <img id="avatar-preview" src="{{ $user->avatar_url }}" style="width:150px;height:150px;border-radius: 50%;object-fit:cover;border:3px solid var(--c-primary)">
        <label for="avatar-input" style="position:absolute;bottom:5px;right:5px;width:40px;height:40px;background:var(--c-primary);color:#000;border-radius: 50%;display:flex;align-items:center;justify-content:center;cursor:pointer;border:3px solid #fff">
          <i class="fa fa-camera"></i>
        </label>
        <input type="file" id="avatar-input" accept="image/*" style="display:none">
      </div>
      <h2 class="t-heading-sm">{{ $user->name }}</h2>
      <p class="text-mute t-caption-sm mt-xs">{{ $user->email }}</p>
      
      <div class="mt-md" style="font-size:12px;color:var(--c-mute)">
        Bergabung sejak {{ $user->created_at->format('M Y') }}

      </div>
      
      @if($user->isAdmin())
      <div class="mt-md">
        <a href="{{ route('admin.dashboard') }}" 
           class="btn" 
           style="width:100%; border-radius: 0; border: 1px solid var(--c-primary); color: var(--c-primary); background: transparent; transition: all 0.2s;"
           onmouseover="this.style.background='var(--c-primary)'; this.style.color='#000';"
           onmouseout="this.style.background='transparent'; this.style.color='var(--c-primary)';">
          <i class="fa-solid fa-shield-halved"></i> Admin Panel
        </a>
      </div>
      @endif
    </div>

    <!-- Main Content -->
    <div class="card">
      <div class="flex gap-md mb-lg" style="border-bottom:1px solid var(--c-hairline)">
        <button class="pill-tab active" data-tab="info">Info Profil</button>
        <button class="pill-tab" data-tab="password">Ubah Password</button>
        <button class="pill-tab" data-tab="keamanan">Keamanan PIN</button>
        <button class="pill-tab" data-tab="history">Riwayat Pembelian</button>
      </div>

      <!-- Tab Info -->
      <div id="tab-info" class="tab-content">
        <form action="{{ route('profile.update') }}" method="POST" style="max-width:400px">
          @csrf
          <div class="form-group">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
          </div>
          <div class="form-group">
            <label class="form-label">Email</label>
            <div class="flex gap-sm">
              <input type="email" class="form-control" value="{{ $user->email }}" disabled style="background:var(--c-surface-soft)">
              <button type="button" class="btn btn-outline" style="white-space:nowrap" onclick="openEmailModal()">Ganti Email</button>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
      </div>

      <!-- Tab Password -->
      <div id="tab-password" class="tab-content" style="display:none">
        <h3 class="t-heading-sm mb-sm">Ubah Password</h3>
        <p class="text-mute" style="font-size:0.85rem;margin-bottom:20px;">Masukkan password saat ini kamu, lalu buat password baru yang kuat. Jika lupa password, gunakan fitur <a href="{{ route('password.request') }}" style="color:var(--c-primary);font-weight:600;">Lupa Password</a>.</p>
        <form id="form-password" style="max-width:400px">
          <div class="form-group">
            <label class="form-label">Password Saat Ini</label>
            <input type="password" id="pw_current" name="current_password" class="form-control" required>
          </div>
          <div class="form-group">
            <label class="form-label">Password Baru</label>
            <input type="password" id="pw_new" name="password" class="form-control" required placeholder="Minimal 8 karakter">
          </div>
          <div class="form-group">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" id="pw_confirm" name="password_confirmation" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary" id="btn-password">Lanjut & Kirim OTP</button>
        </form>
      </div>

      <!-- Tab Keamanan PIN -->
      <div id="tab-keamanan" class="tab-content" style="display:none">
        <h3 class="t-heading-sm mb-sm">Ubah PIN Transaksi</h3>
        <p class="text-mute" style="font-size:0.85rem;margin-bottom:20px;">Ubah PIN 6 digit kamu. PIN digunakan saat mengunduh invoice dan verifikasi transaksi.</p>
        <form id="form-pin" style="max-width:400px">
          
          <div class="form-group">
            <div class="flex-between">
              <label class="form-label">PIN Saat Ini</label>
              <button type="button" onclick="requestPinOtp()" style="background:none; border:none; color:var(--c-primary); font-size:0.85rem; cursor:pointer; text-decoration:underline; padding:0;">Lupa PIN?</button>
            </div>
            <div style="position:relative">
              <input id="old_pin" type="password" name="old_pin" class="form-control" placeholder="6 Digit Angka" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px;">
              <button type="button" onclick="toggleVisibility('old_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <?php $__errorArgs = ['old_pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="error-msg">{{ $message }}</div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="form-group">
            <label class="form-label">PIN Baru</label>
            <div style="position:relative">
              <input id="new_pin" type="password" name="new_pin" class="form-control" placeholder="6 Digit Angka" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px;">
              <button type="button" onclick="toggleVisibility('new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <?php $__errorArgs = ['new_pin'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> 
              <div class="error-msg">{{ $message }}</div> 
            @else
              <div style="font-size: 13px; color: var(--c-mute); margin-top: 8px;">Catatan: PIN tidak boleh berurutan (123456) atau angka berulang (111111, 121212).</div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
          <button type="submit" class="btn btn-primary" id="btn-pin">Lanjut & Kirim OTP</button>
        </form>
      </div>

      <!-- Tab History -->
      <div id="tab-history" class="tab-content" style="display:none">
        @if($transactions->isEmpty())
          <p class="text-mute">Belum ada riwayat transaksi.</p>
        @else
          <div class="flex gap-md mb-md" style="justify-content: flex-end;">
             <button id="btn-cek-status" class="btn btn-outline" onclick="cekStatusMasal()">Cek Status Pending</button>
          </div>
          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th>Order ID</th>
                  <th>Tanggal</th>
                  <th>Item</th>
                  <th>Total</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                
                  <tr>
                    <td><strong>{{ $t->order_id }}</strong></td>
                    <td>{{ $t->created_at->format('d M Y') }}</td>
                    <td>{{ $t->details->count() }} Game</td>
                    <td>Rp {{ number_format($t->total_amount, 0, ',', '.') }}</td>
                    <td>
                      <span class="status status-{{ strtolower($t->status) }}" id="status-{{ $t->id }}" data-id="{{ $t->id }}" data-status="{{ strtolower($t->status) }}">
                        {{ strtoupper($t->status) }}

                      </span>
                    </td>
                    <td>
                      <div class="flex gap-sm align-center">
                        <button class="btn btn-outline btn-sm" onclick="showDetail({{ $t->id }})">Detail</button>
                        @if($t->status === 'pending')
                          <button type="button" class="btn btn-primary btn-sm" onclick="payWithSnap('{{ $t->payment_token }}')">Bayar</button>
                          <form action="{{ route('profile.orders.cancel', $t->id) }}" method="POST" onsubmit="return confirmAction(event, this, 'Konfirmasi Pembatalan', 'Apakah Anda yakin ingin membatalkan pesanan ini?');" style="margin:0; display:flex;">
                            
                            <button class="btn btn-outline btn-sm" style="border-color:var(--c-error); color:var(--c-error);" onmouseover="this.style.background='var(--c-error)'; this.style.color='#fff';" onmouseout="this.style.background='transparent'; this.style.color='var(--c-error)';">Batal</button>
                          </form>
                        @elseif($t->status === 'success' || $t->status === 'paid')
                          <button type="button" class="btn btn-outline btn-sm" onclick="openInvoiceModal({{ $t->id }})">Invoice</button>
                        @endif
                      </div>
                    </td>
                  </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Cropper Overlay -->
<div class="cropper-overlay" id="cropper-overlay" style="display:none">
  <h3 class="t-heading-md" style="color:#fff">Sesuaikan Avatar</h3>
  <div class="cropper-container">
    <img id="cropper-image" src="" style="max-width:100%">
  </div>
  <div class="flex gap-md">
    <button class="btn btn-outline-dark" onclick="cancelCrop()">Batal</button>
    <button class="btn btn-primary" onclick="saveCrop()">Simpan Avatar</button>
  </div>
</div>
@endsection

@push('scripts')
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>

<!-- Reset PIN Modal -->
<div id="resetPinModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('resetPinModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Buat PIN Baru</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Silakan masukkan PIN transaksi Anda yang baru.</p>
        <form id="form-reset-pin">
            <div class="form-group">
                <label class="form-label">PIN Baru (6 Digit Angka)</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi PIN Baru</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin_confirmation" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-reset-pin" style="width:100%">Simpan PIN Baru</button>
        </form>
    </div>
</div>

<script>
function requestPinOtp() {
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

    fetch('/profile/ajax/pin/forgot-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lupa PIN?';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'forgot_pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            alert(data.message || 'Error');
        }
    });
}

document.getElementById('form-reset-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-reset-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    const new_pin = document.getElementById('reset_new_pin').value;
    const new_pin_confirmation = document.getElementById('reset_new_pin_confirmation').value;

    fetch('/profile/ajax/pin/reset', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify({ new_pin, new_pin_confirmation })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Simpan PIN Baru';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('resetPinModal');
            alert('BERHASIL: ' + data.message);
            location.reload();
        } else {
            alert('GAGAL: ' + (data.message || 'Gagal mengubah PIN'));
        }
    });
});

// Update global OTP Verification JS to handle forgot_pin action
const originalOtpVerify = document.getElementById('form-otp').onsubmit; 
// We will replace the entire otp verification script in the backend script to inject forgot_pin
</script>

<!-- SPA MODALS FOR OTP & EMAIL -->
<style>
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.8); z-index: 9999;
    display: none; align-items: center; justify-content: center;
}
.modal-box {
    background: var(--c-surface); border: 1px solid var(--c-hairline);
    padding: 30px; border-radius: 8px; max-width: 400px; width: 100%;
    position: relative;
}
.modal-close {
    position: absolute; top: 15px; right: 15px; background: none; border: none;
    color: var(--c-mute); font-size: 20px; cursor: pointer;
}
</style>

<!-- Email Modal -->
<div id="emailModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('emailModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Ganti Email</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Masukkan alamat email baru Anda. Kami akan mengirimkan OTP ke email LAMA Anda untuk verifikasi.</p>
        <form id="form-email">
            <div class="form-group">
                <label class="form-label">Email Baru</label>
                <input type="email" id="new_email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-email" style="width:100%">Kirim OTP ke Email Lama</button>
        </form>
    </div>
</div>

<!-- Universal OTP Modal -->
<div id="otpModal" class="modal-overlay">
    <div class="modal-box text-center">
        <button class="modal-close" onclick="closeModal('otpModal')"><i class="fas fa-times"></i></button>
        <div style="font-size:40px; color:var(--c-primary); margin-bottom:15px;"><i class="fas fa-shield-alt"></i></div>
        <h3 class="t-heading-sm mb-sm">Verifikasi Keamanan</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Masukkan 6 digit kode OTP yang baru saja kami kirimkan ke email Anda.</p>
        <form id="form-otp">
            <input type="hidden" id="otp-action">
            <div class="form-group">
                <input type="text" id="otp_code" class="form-control text-center" style="font-size:24px; letter-spacing:10px; font-weight:bold" maxlength="6" required autocomplete="off" inputmode="numeric">
            </div>
            <button type="submit" class="btn btn-primary" id="btn-otp" style="width:100%">Verifikasi & Simpan</button>
        </form>
    </div>
</div>

<script>
let otpPendingData = {};

function openEmailModal() {
    document.getElementById('emailModal').style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function showToast(msg, isError = false) {
    alert(msg); // fallback, since Toastify might not be perfectly integrated here if not loaded.
}

// Global AJAX setup
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// 1. Password Flow
document.getElementById('form-password').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-password');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    otpPendingData = {
        current_password: document.getElementById('pw_current').value,
        password: document.getElementById('pw_new').value,
        password_confirmation: document.getElementById('pw_confirm').value,
    };

    fetch('/profile/ajax/password/send-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lanjut & Kirim OTP';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'password';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 2. PIN Flow
document.getElementById('form-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    otpPendingData = {
        old_pin: document.getElementById('old_pin').value,
        new_pin: document.getElementById('new_pin').value,
        new_pin_confirmation: document.getElementById('new_pin_confirmation').value,
    };

    fetch('/profile/ajax/pin/send-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lanjut & Kirim OTP';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 3. Email Flow
document.getElementById('form-email').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-email');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim OTP...';
    btn.disabled = true;

    otpPendingData = {
        new_email: document.getElementById('new_email').value
    };

    fetch('/profile/ajax/email/send-old-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Kirim OTP ke Email Lama';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('emailModal');
            document.getElementById('otp-action').value = 'email';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 4. OTP Verification
document.getElementById('form-otp').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-otp');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
    btn.disabled = true;

    const action = document.getElementById('otp-action').value;
    const code = document.getElementById('otp_code').value;
    otpPendingData.code = code;

    let url = '';
    if(action === 'password') url = '/profile/ajax/password/verify-and-update';
    if(action === 'pin') url = '/profile/ajax/pin/verify-and-update';
    if(action === 'email') url = '/profile/ajax/email/verify-and-update';
    if(action === 'forgot_pin') url = '/profile/ajax/pin/forgot-verify';

    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Verifikasi & Simpan';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('otpModal');
            alert('BERHASIL: ' + data.message);
            location.reload(); // Reload to show new data
        } else {
            alert('GAGAL: ' + (data.message || 'Error verifikasi'));
        }
    });
});
</script>

<!-- Reset PIN Modal -->
<div id="resetPinModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('resetPinModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Buat PIN Baru</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Silakan masukkan PIN transaksi Anda yang baru.</p>
        <form id="form-reset-pin">
            <div class="form-group">
                <label class="form-label">PIN Baru (6 Digit Angka)</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi PIN Baru</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin_confirmation" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-reset-pin" style="width:100%">Simpan PIN Baru</button>
        </form>
    </div>
</div>

<script>
function requestPinOtp() {
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

    fetch('/profile/ajax/pin/forgot-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lupa PIN?';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'forgot_pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            alert(data.message || 'Error');
        }
    });
}

document.getElementById('form-reset-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-reset-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    const new_pin = document.getElementById('reset_new_pin').value;
    const new_pin_confirmation = document.getElementById('reset_new_pin_confirmation').value;

    fetch('/profile/ajax/pin/reset', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify({ new_pin, new_pin_confirmation })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Simpan PIN Baru';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('resetPinModal');
            alert('BERHASIL: ' + data.message);
            location.reload();
        } else {
            alert('GAGAL: ' + (data.message || 'Gagal mengubah PIN'));
        }
    });
});

// Update global OTP Verification JS to handle forgot_pin action
const originalOtpVerify = document.getElementById('form-otp').onsubmit; 
// We will replace the entire otp verification script in the backend script to inject forgot_pin
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Reset PIN Modal -->
<div id="resetPinModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('resetPinModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Buat PIN Baru</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Silakan masukkan PIN transaksi Anda yang baru.</p>
        <form id="form-reset-pin">
            <div class="form-group">
                <label class="form-label">PIN Baru (6 Digit Angka)</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi PIN Baru</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin_confirmation" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-reset-pin" style="width:100%">Simpan PIN Baru</button>
        </form>
    </div>
</div>

<script>
function requestPinOtp() {
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

    fetch('/profile/ajax/pin/forgot-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lupa PIN?';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'forgot_pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            alert(data.message || 'Error');
        }
    });
}

document.getElementById('form-reset-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-reset-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    const new_pin = document.getElementById('reset_new_pin').value;
    const new_pin_confirmation = document.getElementById('reset_new_pin_confirmation').value;

    fetch('/profile/ajax/pin/reset', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify({ new_pin, new_pin_confirmation })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Simpan PIN Baru';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('resetPinModal');
            alert('BERHASIL: ' + data.message);
            location.reload();
        } else {
            alert('GAGAL: ' + (data.message || 'Gagal mengubah PIN'));
        }
    });
});

// Update global OTP Verification JS to handle forgot_pin action
const originalOtpVerify = document.getElementById('form-otp').onsubmit; 
// We will replace the entire otp verification script in the backend script to inject forgot_pin
</script>

<!-- SPA MODALS FOR OTP & EMAIL -->
<style>
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.8); z-index: 9999;
    display: none; align-items: center; justify-content: center;
}
.modal-box {
    background: var(--c-surface); border: 1px solid var(--c-hairline);
    padding: 30px; border-radius: 8px; max-width: 400px; width: 100%;
    position: relative;
}
.modal-close {
    position: absolute; top: 15px; right: 15px; background: none; border: none;
    color: var(--c-mute); font-size: 20px; cursor: pointer;
}
</style>

<!-- Email Modal -->
<div id="emailModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('emailModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Ganti Email</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Masukkan alamat email baru Anda. Kami akan mengirimkan OTP ke email LAMA Anda untuk verifikasi.</p>
        <form id="form-email">
            <div class="form-group">
                <label class="form-label">Email Baru</label>
                <input type="email" id="new_email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-email" style="width:100%">Kirim OTP ke Email Lama</button>
        </form>
    </div>
</div>

<!-- Universal OTP Modal -->
<div id="otpModal" class="modal-overlay">
    <div class="modal-box text-center">
        <button class="modal-close" onclick="closeModal('otpModal')"><i class="fas fa-times"></i></button>
        <div style="font-size:40px; color:var(--c-primary); margin-bottom:15px;"><i class="fas fa-shield-alt"></i></div>
        <h3 class="t-heading-sm mb-sm">Verifikasi Keamanan</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Masukkan 6 digit kode OTP yang baru saja kami kirimkan ke email Anda.</p>
        <form id="form-otp">
            <input type="hidden" id="otp-action">
            <div class="form-group">
                <input type="text" id="otp_code" class="form-control text-center" style="font-size:24px; letter-spacing:10px; font-weight:bold" maxlength="6" required autocomplete="off" inputmode="numeric">
            </div>
            <button type="submit" class="btn btn-primary" id="btn-otp" style="width:100%">Verifikasi & Simpan</button>
        </form>
    </div>
</div>

<script>
let otpPendingData = {};

function openEmailModal() {
    document.getElementById('emailModal').style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function showToast(msg, isError = false) {
    alert(msg); // fallback, since Toastify might not be perfectly integrated here if not loaded.
}

// Global AJAX setup
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// 1. Password Flow
document.getElementById('form-password').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-password');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    otpPendingData = {
        current_password: document.getElementById('pw_current').value,
        password: document.getElementById('pw_new').value,
        password_confirmation: document.getElementById('pw_confirm').value,
    };

    fetch('/profile/ajax/password/send-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lanjut & Kirim OTP';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'password';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 2. PIN Flow
document.getElementById('form-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    otpPendingData = {
        old_pin: document.getElementById('old_pin').value,
        new_pin: document.getElementById('new_pin').value,
        new_pin_confirmation: document.getElementById('new_pin_confirmation').value,
    };

    fetch('/profile/ajax/pin/send-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lanjut & Kirim OTP';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 3. Email Flow
document.getElementById('form-email').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-email');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim OTP...';
    btn.disabled = true;

    otpPendingData = {
        new_email: document.getElementById('new_email').value
    };

    fetch('/profile/ajax/email/send-old-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Kirim OTP ke Email Lama';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('emailModal');
            document.getElementById('otp-action').value = 'email';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 4. OTP Verification
document.getElementById('form-otp').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-otp');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
    btn.disabled = true;

    const action = document.getElementById('otp-action').value;
    const code = document.getElementById('otp_code').value;
    otpPendingData.code = code;

    let url = '';
    if(action === 'password') url = '/profile/ajax/password/verify-and-update';
    if(action === 'pin') url = '/profile/ajax/pin/verify-and-update';
    if(action === 'email') url = '/profile/ajax/email/verify-and-update';
    if(action === 'forgot_pin') url = '/profile/ajax/pin/forgot-verify';

    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Verifikasi & Simpan';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('otpModal');
            alert('BERHASIL: ' + data.message);
            location.reload(); // Reload to show new data
        } else {
            alert('GAGAL: ' + (data.message || 'Error verifikasi'));
        }
    });
});
</script>

<!-- Reset PIN Modal -->
<div id="resetPinModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('resetPinModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Buat PIN Baru</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Silakan masukkan PIN transaksi Anda yang baru.</p>
        <form id="form-reset-pin">
            <div class="form-group">
                <label class="form-label">PIN Baru (6 Digit Angka)</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi PIN Baru</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin_confirmation" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-reset-pin" style="width:100%">Simpan PIN Baru</button>
        </form>
    </div>
</div>

<script>
function requestPinOtp() {
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

    fetch('/profile/ajax/pin/forgot-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lupa PIN?';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'forgot_pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            alert(data.message || 'Error');
        }
    });
}

document.getElementById('form-reset-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-reset-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    const new_pin = document.getElementById('reset_new_pin').value;
    const new_pin_confirmation = document.getElementById('reset_new_pin_confirmation').value;

    fetch('/profile/ajax/pin/reset', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify({ new_pin, new_pin_confirmation })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Simpan PIN Baru';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('resetPinModal');
            alert('BERHASIL: ' + data.message);
            location.reload();
        } else {
            alert('GAGAL: ' + (data.message || 'Gagal mengubah PIN'));
        }
    });
});

// Update global OTP Verification JS to handle forgot_pin action
const originalOtpVerify = document.getElementById('form-otp').onsubmit; 
// We will replace the entire otp verification script in the backend script to inject forgot_pin
</script>
<script>
  function payWithSnap(token) {
    if (!token) {
        showCustomAlert('Error', 'Token pembayaran tidak ditemukan atau tidak valid.', 'error');
        return;
    }
    
    window.snap.pay(token, {
      onSuccess: function(result){
        showCustomAlert('Berhasil', 'Pembayaran berhasil! Silakan cek invoice Anda.', 'success');
        setTimeout(() => window.location.reload(), 2000);
      },
      onPending: function(result){
        showCustomAlert('Menunggu', 'Menunggu pembayaran diselesaikan.', 'warning');
      },
      onError: function(result){
        showCustomAlert('Gagal', 'Pembayaran gagal atau terjadi kesalahan.', 'error');
      },
      onClose: function(){
        // user closed the popup
      }
    });
  }

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

  // Tabs logic - wrapped in DOMContentLoaded
  document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.pill-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    function activateTab(tabName) {
      console.log('Activating tab:', tabName);
      tabs.forEach(t => t.classList.remove('active'));
      tabContents.forEach(c => c.style.display = 'none');
      const targetTab = document.querySelector(`.pill-tab[data-tab="${tabName}"]`);
      if(targetTab) {
        targetTab.classList.add('active');
        const tabContent = document.getElementById('tab-' + tabName);
        if(tabContent) {
          tabContent.style.display = 'block';
        }
      }
    }

    // Make activateTab globally accessible
    window.activateTab = activateTab;

    // Check initial hash
    if (window.location.hash) {
      const hashTab = window.location.hash.substring(1); // remove #
      if (document.querySelector(`.pill-tab[data-tab="${hashTab}"]`)) {
        activateTab(hashTab);
      }
    }

    tabs.forEach(tab => {
      tab.addEventListener('click', function(e) {
        e.preventDefault();
        const tabName = this.dataset.tab;
        activateTab(tabName);
        window.location.hash = tabName;
      });
    });
  });

  let cropper = null;
  const avatarInput = document.getElementById('avatar-input');
  const overlay = document.getElementById('cropper-overlay');
  const cropperImage = document.getElementById('cropper-image');

  avatarInput.addEventListener('change', function(e) {
    const file = e.target.files[0];
    if(file) {
      const reader = new FileReader();
      reader.onload = function(event) {
        cropperImage.src = event.target.result;
        overlay.style.display = 'flex';
        if(cropper) cropper.destroy();
        cropper = new Cropper(cropperImage, {
          aspectRatio: 1,
          viewMode: 1
        });
      };
      reader.readAsDataURL(file);
    }
  });

  function cancelCrop() {
    overlay.style.display = 'none';
    if(cropper) cropper.destroy();
    avatarInput.value = '';
  }

  function saveCrop() {
    if(!cropper) return;
    const canvas = cropper.getCroppedCanvas({
      width: 300,
      height: 300
    });
    
    fetch('{{ route('profile.avatar') }}', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        avatar_base64: canvas.toDataURL('image/jpeg')
      })
    })
    .then(res => res.json())
    .then(data => {
      if(data.success) {
        document.getElementById('avatar-preview').src = data.avatar_url;
        cancelCrop();
        window.location.reload();
      }
    });
  }

  // --- GameVault Features Logic ---
  function cekStatusMasal() {
      let pendingIds = [];
      document.querySelectorAll('[data-status="pending"]').forEach(el => {
          pendingIds.push(el.dataset.id);
      });

      if(pendingIds.length === 0) {
          showCustomAlert('Pemberitahuan', 'Tidak ada transaksi pending yang perlu dicek saat ini.', 'warning');
          return;
      }

      let btn = document.getElementById('btn-cek-status');
      btn.innerText = 'Mengecek...';
      btn.disabled = true;

      fetch('{{ route("profile.orders.check-statuses") }}', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': '{{ csrf_token() }}'
          },
          body: JSON.stringify({ ids: pendingIds })
      })
      .then(res => res.json())
      .then(data => {
          let reloaded = false;
          for(let id in data) {
              if (data[id] !== 'pending') {
                  reloaded = true;
              }
          }
          if(reloaded) {
              window.location.reload();
          } else {
              btn.innerText = 'Cek Status Pending';
              btn.disabled = false;
              showCustomAlert('Status Belum Berubah', 'Sistem belum mendeteksi adanya pembayaran masuk dari pihak Midtrans. Silakan tunggu beberapa saat atau pastikan Anda sudah melakukan pembayaran.', 'warning');
          }
      });
  }

  function showDetail(id) {
      document.getElementById('modalDetail').style.display = 'flex';
      document.getElementById('detailContent').innerHTML = '<p class="text-mute">Loading...</p>';

      fetch(`/profile/orders/${id}/detail`)
      .then(res => res.json())
      .then(data => {
          if (data.status === 'success') {
              let html = `
                  <div class="mb-md">
                      <p><strong>Order ID:</strong> ${data.transaksi.id}</p>
                      <p><strong>Tanggal:</strong> ${data.transaksi.created_at}</p>
                      <p><strong>Status:</strong> <span class="status status-${data.transaksi.status.toLowerCase()}">${data.transaksi.status.toUpperCase()}</span></p>
                  </div>
                  <div class="table-wrap">
                      <table>
                          <thead>
                              <tr>
                                  <th>Game</th>
                                  <th>Harga</th>
                              </tr>
                          </thead>
                          <tbody>
              `;
              data.items.forEach(item => {
                  html += `
                      <tr>
                          <td>
                              <div class="flex gap-sm align-center">
                                  <img src="${item.image.startsWith('http') ? item.image : '/storage/' + item.image}" style="width:40px;height:20px;object-fit:cover;border-radius: 0;">
                                  <span>${item.name} ${item.is_refunded ? '<span class="status status-failed" style="padding:2px 4px;font-size:10px;">Refunded</span>' : ''}</span>
                              </div>
                          </td>
                          <td>Rp ${new Intl.NumberFormat('id-ID').format(item.harga_saat_beli)}</td>
                      </tr>
                  `;
              });
              html += `
                          </tbody>
                      </table>
                  </div>
              `;
              document.getElementById('detailContent').innerHTML = html;
          }
      });
  }

  function closeModalDetail() {
      document.getElementById('modalDetail').style.display = 'none';
  }

  // Invoice PIN Modal
  let currentInvoiceId = null;
  
  function openInvoiceModal(transactionId) {
      currentInvoiceId = transactionId;
      document.getElementById('pinInput').value = '';
      const errEl = document.getElementById('pinError');
      errEl.textContent = '';
      errEl.style.display = 'none';
      document.getElementById('modalInvoice').style.display = 'flex';
      setTimeout(() => document.getElementById('pinInput').focus(), 100);
  }

  function closeInvoiceModal() {
      document.getElementById('modalInvoice').style.display = 'none';
      currentInvoiceId = null;
  }

  async function submitInvoicePin() {
      const pin = document.getElementById('pinInput').value.trim();
      const errEl = document.getElementById('pinError');
      errEl.textContent = '';
      errEl.style.display = 'none';

      if (!pin || pin.length !== 6 || !/^\d{6}$/.test(pin)) {
          errEl.textContent = 'PIN harus 6 digit angka.';
          errEl.style.display = 'block';
          return;
      }

      const btn = document.getElementById('btnDownloadInvoice');
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';

      try {
          const formData = new FormData();
          formData.append('pin', pin);
          formData.append('_token', '{{ csrf_token() }}');
          
          const response = await fetch(`/invoice/${currentInvoiceId}/download`, {
              method: 'POST',
              body: formData,
              headers: {
                  'X-Requested-With': 'XMLHttpRequest'
              }
          });
          
          if (response.ok && response.headers.get('content-type').includes('application/pdf')) {
              // Success - Download PDF
              const blob = await response.blob();
              const url = window.URL.createObjectURL(blob);
              const a = document.createElement('a');
              a.href = url;
              a.download = `invoice-${currentInvoiceId}.pdf`;
              document.body.appendChild(a);
              a.click();
              document.body.removeChild(a);
              window.URL.revokeObjectURL(url);
              
              // Close modal and show success
              closeInvoiceModal();
              showCustomAlert('Berhasil!', 'Invoice berhasil diunduh.', 'success');
          } else {
              // Error - Show message
              const data = await response.json();
              errEl.textContent = data.message || 'Terjadi kesalahan. Silakan coba lagi.';
              errEl.style.display = 'block';
              document.getElementById('pinInput').value = '';
              document.getElementById('pinInput').focus();
          }
      } catch (error) {
          console.error('Error:', error);
          errEl.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
          errEl.style.display = 'block';
      } finally {
          btn.disabled = false;
          btn.innerHTML = 'Unduh Invoice';
      }
  }

  // Allow Enter key in PIN input
  document.addEventListener('DOMContentLoaded', function() {
      const pinInput = document.getElementById('pinInput');
      if (pinInput) {
          pinInput.addEventListener('keydown', function(e) {
              if (e.key === 'Enter') {
                  e.preventDefault();
                  submitInvoicePin();
              }
          });
          // Only allow digits
          pinInput.addEventListener('input', function() {
              this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
          });
      }
  });

  function requestPinOtp() {
      const btn = event.currentTarget;
      
      showCustomConfirmCallback('Konfirmasi OTP', 'Kirim kode OTP ke email Anda untuk reset PIN?', () => {
          const originalText = btn.innerHTML;
          btn.innerHTML = 'Mengirim...';
          btn.disabled = true;

          fetch('/profile/ajax/pin/forgot-otp', {
              method: 'POST',
              headers: {
                  'X-CSRF-TOKEN': '{{ csrf_token() }}',
                  'Accept': 'application/json',
                  'Content-Type': 'application/json'
              }
          })
          .then(async r => {
              const contentType = r.headers.get("content-type");
              if (contentType && contentType.indexOf("application/json") !== -1) {
                  return r.json();
              } else {
                  if (r.status === 429) throw new Error("Terlalu banyak permintaan. Silakan tunggu 15 menit.");
                  throw new Error("Terjadi kesalahan sistem (Non-JSON).");
              }
          })
          .then(data => {
              btn.innerHTML = originalText;
              btn.disabled = false;
              if (data.error) {
                  if (typeof showCenterPopup === 'function') showCenterPopup(data.error, false);
                  else showSweetAlert('Gagal', data.error, 'error');
              } else {
                  openForgotPinOtpModal();
              }
          })
          .catch(e => {
              btn.innerHTML = originalText;
              btn.disabled = false;
              if (typeof showCenterPopup === 'function') showCenterPopup(e.message || 'Terjadi kesalahan jaringan.', false);
          });
      });
  }

  function openForgotPinOtpModal() {
      document.getElementById('forgotPinOtpInput').value = '';
      const errEl = document.getElementById('forgotPinOtpError');
      errEl.textContent = '';
      errEl.style.display = 'none';
      document.getElementById('modalForgotPinOtp').style.display = 'flex';
      setTimeout(() => document.getElementById('forgotPinOtpInput').focus(), 100);
  }

  function closeForgotPinOtpModal() {
      document.getElementById('modalForgotPinOtp').style.display = 'none';
  }

  function submitForgotPinOtp() {
      const code = document.getElementById('forgotPinOtpInput').value.trim();
      const errEl = document.getElementById('forgotPinOtpError');
      if (code.length !== 6) {
          errEl.textContent = 'OTP harus 6 digit angka.';
          errEl.style.display = 'block';
          return;
      }
      errEl.style.display = 'none';
      
      const btn = document.getElementById('btnSubmitForgotPinOtp');
      const originalText = btn.innerHTML;
      btn.innerHTML = 'Memverifikasi...';
      btn.disabled = true;

      verifyPinOtp(code, btn, originalText, errEl);
  }

  function verifyPinOtp(code, btn, originalText, errEl) {
      fetch('/profile/ajax/pin/forgot-verify', {
          method: 'POST',
          headers: {
              'X-CSRF-TOKEN': '{{ csrf_token() }}',
              'Accept': 'application/json',
              'Content-Type': 'application/json'
          },
          body: JSON.stringify({ code: code })
      })
      .then(async r => {
          const contentType = r.headers.get("content-type");
          if (contentType && contentType.indexOf("application/json") !== -1) {
              return r.json();
          } else {
              throw new Error("Terjadi kesalahan sistem (Non-JSON).");
          }
      })
      .then(data => {
          if (btn) {
              btn.innerHTML = originalText;
              btn.disabled = false;
          }
          if (data.error) {
              if (errEl) {
                  errEl.textContent = data.error;
                  errEl.style.display = 'block';
              } else if (typeof showCenterPopup === 'function') {
                  showCenterPopup(data.error, false);
              }
          } else if (data.redirect) {
              window.location.href = data.redirect;
          }
      })
      .catch(e => {
          if (btn) {
              btn.innerHTML = originalText;
              btn.disabled = false;
          }
          if (errEl) {
              errEl.textContent = e.message || 'Terjadi kesalahan jaringan.';
              errEl.style.display = 'block';
          }
      });
  }
</script>

<!-- Reset PIN Modal -->
<div id="resetPinModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('resetPinModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Buat PIN Baru</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Silakan masukkan PIN transaksi Anda yang baru.</p>
        <form id="form-reset-pin">
            <div class="form-group">
                <label class="form-label">PIN Baru (6 Digit Angka)</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi PIN Baru</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin_confirmation" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-reset-pin" style="width:100%">Simpan PIN Baru</button>
        </form>
    </div>
</div>

<script>
function requestPinOtp() {
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

    fetch('/profile/ajax/pin/forgot-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lupa PIN?';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'forgot_pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            alert(data.message || 'Error');
        }
    });
}

document.getElementById('form-reset-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-reset-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    const new_pin = document.getElementById('reset_new_pin').value;
    const new_pin_confirmation = document.getElementById('reset_new_pin_confirmation').value;

    fetch('/profile/ajax/pin/reset', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify({ new_pin, new_pin_confirmation })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Simpan PIN Baru';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('resetPinModal');
            alert('BERHASIL: ' + data.message);
            location.reload();
        } else {
            alert('GAGAL: ' + (data.message || 'Gagal mengubah PIN'));
        }
    });
});

// Update global OTP Verification JS to handle forgot_pin action
const originalOtpVerify = document.getElementById('form-otp').onsubmit; 
// We will replace the entire otp verification script in the backend script to inject forgot_pin
</script>

<!-- SPA MODALS FOR OTP & EMAIL -->
<style>
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.8); z-index: 9999;
    display: none; align-items: center; justify-content: center;
}
.modal-box {
    background: var(--c-surface); border: 1px solid var(--c-hairline);
    padding: 30px; border-radius: 8px; max-width: 400px; width: 100%;
    position: relative;
}
.modal-close {
    position: absolute; top: 15px; right: 15px; background: none; border: none;
    color: var(--c-mute); font-size: 20px; cursor: pointer;
}
</style>

<!-- Email Modal -->
<div id="emailModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('emailModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Ganti Email</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Masukkan alamat email baru Anda. Kami akan mengirimkan OTP ke email LAMA Anda untuk verifikasi.</p>
        <form id="form-email">
            <div class="form-group">
                <label class="form-label">Email Baru</label>
                <input type="email" id="new_email" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-email" style="width:100%">Kirim OTP ke Email Lama</button>
        </form>
    </div>
</div>

<!-- Universal OTP Modal -->
<div id="otpModal" class="modal-overlay">
    <div class="modal-box text-center">
        <button class="modal-close" onclick="closeModal('otpModal')"><i class="fas fa-times"></i></button>
        <div style="font-size:40px; color:var(--c-primary); margin-bottom:15px;"><i class="fas fa-shield-alt"></i></div>
        <h3 class="t-heading-sm mb-sm">Verifikasi Keamanan</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Masukkan 6 digit kode OTP yang baru saja kami kirimkan ke email Anda.</p>
        <form id="form-otp">
            <input type="hidden" id="otp-action">
            <div class="form-group">
                <input type="text" id="otp_code" class="form-control text-center" style="font-size:24px; letter-spacing:10px; font-weight:bold" maxlength="6" required autocomplete="off" inputmode="numeric">
            </div>
            <button type="submit" class="btn btn-primary" id="btn-otp" style="width:100%">Verifikasi & Simpan</button>
        </form>
    </div>
</div>

<script>
let otpPendingData = {};

function openEmailModal() {
    document.getElementById('emailModal').style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function showToast(msg, isError = false) {
    alert(msg); // fallback, since Toastify might not be perfectly integrated here if not loaded.
}

// Global AJAX setup
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// 1. Password Flow
document.getElementById('form-password').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-password');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    otpPendingData = {
        current_password: document.getElementById('pw_current').value,
        password: document.getElementById('pw_new').value,
        password_confirmation: document.getElementById('pw_confirm').value,
    };

    fetch('/profile/ajax/password/send-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lanjut & Kirim OTP';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'password';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 2. PIN Flow
document.getElementById('form-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
    btn.disabled = true;

    otpPendingData = {
        old_pin: document.getElementById('old_pin').value,
        new_pin: document.getElementById('new_pin').value,
        new_pin_confirmation: document.getElementById('new_pin_confirmation').value,
    };

    fetch('/profile/ajax/pin/send-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lanjut & Kirim OTP';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 3. Email Flow
document.getElementById('form-email').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-email');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim OTP...';
    btn.disabled = true;

    otpPendingData = {
        new_email: document.getElementById('new_email').value
    };

    fetch('/profile/ajax/email/send-old-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Kirim OTP ke Email Lama';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('emailModal');
            document.getElementById('otp-action').value = 'email';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

// 4. OTP Verification
document.getElementById('form-otp').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-otp');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memverifikasi...';
    btn.disabled = true;

    const action = document.getElementById('otp-action').value;
    const code = document.getElementById('otp_code').value;
    otpPendingData.code = code;

    let url = '';
    if(action === 'password') url = '/profile/ajax/password/verify-and-update';
    if(action === 'pin') url = '/profile/ajax/pin/verify-and-update';
    if(action === 'email') url = '/profile/ajax/email/verify-and-update';
    if(action === 'forgot_pin') url = '/profile/ajax/pin/forgot-verify';

    fetch(url, {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify(otpPendingData)
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Verifikasi & Simpan';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('otpModal');
            alert('BERHASIL: ' + data.message);
            location.reload(); // Reload to show new data
        } else {
            alert('GAGAL: ' + (data.message || 'Error verifikasi'));
        }
    });
});
</script>

<!-- Reset PIN Modal -->
<div id="resetPinModal" class="modal-overlay">
    <div class="modal-box">
        <button class="modal-close" onclick="closeModal('resetPinModal')"><i class="fas fa-times"></i></button>
        <h3 class="t-heading-sm mb-sm">Buat PIN Baru</h3>
        <p class="text-mute mb-md" style="font-size:0.85rem">Silakan masukkan PIN transaksi Anda yang baru.</p>
        <form id="form-reset-pin">
            <div class="form-group">
                <label class="form-label">PIN Baru (6 Digit Angka)</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Konfirmasi PIN Baru</label>
                <div style="position:relative">
                    <input type="password" id="reset_new_pin_confirmation" class="form-control" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px">
                    <button type="button" onclick="toggleVisibility('reset_new_pin_confirmation', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;"><i class="fas fa-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-reset-pin" style="width:100%">Simpan PIN Baru</button>
        </form>
    </div>
</div>

<script>
function requestPinOtp() {
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

    fetch('/profile/ajax/pin/forgot-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lupa PIN?';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            document.getElementById('otp-action').value = 'forgot_pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            alert(data.message || 'Error');
        }
    });
}

document.getElementById('form-reset-pin').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-reset-pin');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
    btn.disabled = true;

    const new_pin = document.getElementById('reset_new_pin').value;
    const new_pin_confirmation = document.getElementById('reset_new_pin_confirmation').value;

    fetch('/profile/ajax/pin/reset', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
        body: JSON.stringify({ new_pin, new_pin_confirmation })
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Simpan PIN Baru';
        btn.disabled = false;
        if(data.status === 'success') {
            if (action === 'forgot_pin') {
                closeModal('otpModal');
                document.getElementById('resetPinModal').style.display = 'flex';
                return;
            }
            closeModal('resetPinModal');
            alert('BERHASIL: ' + data.message);
            location.reload();
        } else {
            alert('GAGAL: ' + (data.message || 'Gagal mengubah PIN'));
        }
    });
});

// Update global OTP Verification JS to handle forgot_pin action
const originalOtpVerify = document.getElementById('form-otp').onsubmit; 
// We will replace the entire otp verification script in the backend script to inject forgot_pin
</script>

<!-- Modal Detail -->
<div class="cropper-overlay" id="modalDetail" style="display:none;z-index:9999;align-items:center;justify-content:center;">
    <div class="card" style="width:100%;max-width:500px;max-height:80vh;overflow-y:auto;background:var(--c-surface)">
        <div class="flex justify-between align-center mb-md">
            <h3 class="t-heading-sm">Detail Pesanan</h3>
            <button class="btn btn-outline" style="border:none;padding:5px;" onclick="closeModalDetail()">
                X
            </button>
        </div>
        <div id="detailContent"></div>
        <div class="mt-md text-right">
            <button class="btn btn-primary" onclick="closeModalDetail()">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Invoice PIN -->
<div class="cropper-overlay" id="modalInvoice" style="display:none;z-index:10000;align-items:center;justify-content:center;">
    <div style="width:100%;max-width:380px;background:var(--c-surface-elevated);border:1px solid var(--c-hairline-strong);border-radius: 0;padding:var(--sp-xxl);">
        <div class="flex justify-between align-center" style="margin-bottom:var(--sp-lg);">
            <h3 class="t-heading-sm" style="margin:0;"><i class="fas fa-lock" style="color:var(--c-primary);margin-right:var(--sp-sm);"></i>Verifikasi PIN</h3>
            <button onclick="closeInvoiceModal()" style="background:none;border:none;color:var(--c-mute);font-size:1.2rem;cursor:pointer;padding:var(--sp-xs) var(--sp-sm);">&times;</button>
        </div>
        <p class="text-mute" style="font-size:0.85rem;margin-bottom:var(--sp-lg);">Masukkan PIN 6 digit kamu untuk mengunduh invoice.</p>
        <div class="form-group" style="margin-bottom:var(--sp-sm);">
            <label class="form-label" style="font-size:0.8rem;letter-spacing:1px;">PIN TRANSAKSI</label>
            <input
                id="pinInput"
                type="password"
                class="form-control"
                placeholder="••••••"
                maxlength="6"
                inputmode="numeric"
                autocomplete="off"
                style="font-size:1.5rem;letter-spacing:8px;text-align:center;font-family:monospace;padding:14px;"
            >
            <p id="pinError" style="color:var(--c-error);font-size:0.8rem;margin-top:var(--sp-xs);min-height:18px;display:none;"></p>
        </div>
        <div style="display:flex;gap:var(--sp-sm);margin-top:var(--sp-sm);">
            <button onclick="closeInvoiceModal()" style="flex:1;background:var(--c-surface-soft);border:1px solid var(--c-hairline);border-radius: 0;color:var(--c-mute);padding:10px;cursor:pointer;font-weight:600;">Batal</button>
            <button id="btnDownloadInvoice" onclick="submitInvoicePin()" class="btn btn-primary" style="flex:2;justify-content:center;">
                <i class="fas fa-download"></i> Unduh Invoice
            </button>
        </div>
        @if(!auth()->user()->pin)
        <p style="margin-top:14px;font-size:0.78rem;color:#ffb347;text-align:center;">
            <i class="fas fa-exclamation-triangle"></i>
            Belum punya PIN? <a href="#" onclick="closeInvoiceModal(); document.querySelector('[data-tab=keamanan]').click();" style="color:var(--c-primary);font-weight:700;">Buat PIN di sini</a>
        </p>
        @endif
    </div>
</div>
</div>

<!-- Modal Forgot PIN OTP -->
<div class="cropper-overlay" id="modalForgotPinOtp" style="display:none;z-index:10000;align-items:center;justify-content:center;">
    <div style="width:100%;max-width:380px;background:var(--c-surface-elevated);border:1px solid var(--c-hairline-strong);border-radius: 0;padding:var(--sp-xxl);">
        <div class="flex justify-between align-center" style="margin-bottom:var(--sp-lg);">
            <h3 class="t-heading-sm" style="margin:0;"><i class="fas fa-envelope-open-text" style="color:var(--c-primary);margin-right:var(--sp-sm);"></i>Verifikasi OTP</h3>
            <button onclick="closeForgotPinOtpModal()" style="background:none;border:none;color:var(--c-mute);font-size:1.2rem;cursor:pointer;padding:var(--sp-xs) var(--sp-sm);">&times;</button>
        </div>
        <p class="text-mute" style="font-size:0.85rem;margin-bottom:var(--sp-lg); text-align:center;">Kode OTP telah dikirim ke email Anda.<br>Masukkan 6 digit kode OTP untuk reset PIN.</p>
        <div class="form-group" style="margin-bottom:var(--sp-sm);">
            <input
                id="forgotPinOtpInput"
                type="text"
                class="form-control"
                placeholder="000000"
                maxlength="6"
                inputmode="numeric"
                autocomplete="off"
                style="font-size:2rem;letter-spacing:12px;text-align:center;font-weight:800;font-family:monospace;padding:16px;"
            >
            <p id="forgotPinOtpError" style="color:var(--c-error);font-size:0.8rem;margin-top:var(--sp-xs);min-height:18px;display:none; text-align:center;"></p>
        </div>
        <div style="display:flex;gap:var(--sp-sm);margin-top:var(--sp-sm);">
            <button onclick="closeForgotPinOtpModal()" style="flex:1;background:var(--c-surface-soft);border:1px solid var(--c-hairline);border-radius: 0;color:var(--c-mute);padding:10px;cursor:pointer;font-weight:600;">Batal</button>
            <button id="btnSubmitForgotPinOtp" onclick="submitForgotPinOtp()" class="btn btn-primary" style="flex:2;justify-content:center;">
                Verifikasi OTP
            </button>
        </div>
    </div>
</div>
@endpush

<?php /**PATH D:\Laragon\laragon\www\GameVault\resources\views/profile/index.blade.php ENDPATH**/ ?>