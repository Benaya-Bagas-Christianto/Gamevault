@extends('layouts.app')
@section('title', 'Profil Saya')

@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
.profile-modal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    pointer-events: all;
}
.profile-modal.active {
    display: flex !important;
}
.modal-box {
    background: #111111;
    border: 1px solid rgba(118, 185, 0, 0.5);
    padding: 30px;
    border-radius: 4px;
    max-width: 420px;
    width: 90%;
    position: relative;
    box-shadow: 0 0 40px rgba(118,185,0,0.15);
}
.modal-close {
    position: absolute;
    top: 15px; right: 15px;
    background: none;
    border: none;
    color: #757575;
    font-size: 20px;
    cursor: pointer;
    z-index: 1;
}

/* Mobile Responsive Adjustments */
@media (max-width: 768px) {
    .layout-sidebar {
        display: flex !important;
        flex-direction: column !important;
        width: 100%;
        gap: 24px;
    }
    .layout-sidebar > .card {
        width: 100% !important;
        min-width: 0 !important;
        box-sizing: border-box;
        overflow: hidden;
    }
    .profile-tabs-container {
        flex-wrap: wrap !important;
        overflow-x: visible;
        padding-bottom: 8px;
        border-bottom: 1px solid var(--c-hairline);
        gap: 8px !important;
    }
    .pill-tab {
        flex: 1 1 calc(50% - 8px);
        text-align: center;
        padding: 8px !important;
        font-size: 0.8rem;
    }
    
    .table-wrap table, .table-wrap thead, .table-wrap tbody, .table-wrap th, .table-wrap td, .table-wrap tr {
        display: block;
    }
    .table-wrap thead tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    .table-wrap tr {
        border: 1px solid var(--c-hairline);
        border-radius: 8px;
        margin-bottom: 16px;
        background: var(--c-surface-soft);
        padding: 16px;
    }
    .table-wrap td {
        border: none !important;
        position: relative;
        padding: 8px 0 8px 40% !important;
        text-align: right !important;
        min-height: 32px;
        display: flex;
        align-items: center;
        justify-content: flex-end;
    }
    .table-wrap td::before {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        left: 0;
        width: 35%;
        padding-right: 10px;
        white-space: nowrap;
        text-align: left;
        font-weight: 600;
        color: var(--c-mute);
        font-size: 0.85rem;
    }
    .table-wrap td:nth-of-type(1)::before { content: "Order ID"; }
    .table-wrap td:nth-of-type(2)::before { content: "Tanggal"; }
    .table-wrap td:nth-of-type(3)::before { content: "Item"; }
    .table-wrap td:nth-of-type(4)::before { content: "Total"; }
    .table-wrap td:nth-of-type(5)::before { content: "Status"; }
    .table-wrap td:nth-of-type(6)::before { content: "Aksi"; }
    
    .table-wrap td:last-child {
        margin-top: 12px;
        padding: 12px 0 0 0 !important;
        border-top: 1px dashed var(--c-hairline) !important;
        justify-content: stretch;
    }
    .table-wrap td:last-child::before {
        display: none;
    }
    .table-wrap {
        margin: 0;
        padding: 0;
        overflow-x: visible;
    }
    .table-wrap table {
        min-width: 0;
        width: 100%;
    }
    
    .history-actions {
        flex-direction: column !important;
        align-items: stretch !important;
        width: 100%;
    }
    .history-actions button, .history-actions form {
        width: 100%;
        margin: 0 !important;
    }
    .history-actions form button {
        width: 100%;
    }
    .email-flex-wrap {
        flex-direction: column !important;
        align-items: stretch !important;
    }
    .email-flex-wrap button {
        width: 100% !important;
    }
}
</style>
@endpush

@section('content')
<div class="container section" style="margin-bottom: 100px;">
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
      <div class="flex gap-md mb-lg profile-tabs-container" style="border-bottom:1px solid var(--c-hairline)">
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
          
          <div class="form-group" style="margin-top:16px;">
            <label class="form-label">Email</label>
            <div class="flex gap-sm email-flex-wrap">
              <input type="email" class="form-control" value="{{ $user->email }}" disabled style="background:var(--c-surface-soft)">
              <button
                id="btn-ganti-email"
                type="button"
                class="btn btn-outline"
                style="white-space:nowrap; flex-shrink:0;"
                onclick="openEmailModal()"
              >Ganti Email</button>
            </div>
          </div>

          <button type="submit" class="btn btn-primary" style="margin-top:8px;">Simpan Perubahan</button>
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
          </div>
          <div class="form-group">
            <label class="form-label">PIN Baru</label>
            <div style="position:relative">
              <input id="new_pin" type="password" name="new_pin" class="form-control" placeholder="6 Digit Angka" maxlength="6" pattern="[0-9]{6}" inputmode="numeric" required style="padding-right:40px;">
              <button type="button" onclick="toggleVisibility('new_pin', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); background:none; border:none; color:var(--c-mute); cursor:pointer;">
                <i class="fas fa-eye"></i>
              </button>
            </div>
            <div style="font-size: 13px; color: var(--c-mute); margin-top: 8px;">Catatan: PIN tidak boleh berurutan (123456) atau angka berulang (111111, 121212).</div>
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
                @foreach($transactions as $t)
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
                      <div class="flex gap-sm align-center history-actions">
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
                @endforeach
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

<!-- Email Modal -->
<div id="emailModal" class="profile-modal">
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
<div id="otpModal" class="profile-modal">
    <div class="modal-box text-center">
        <button class="modal-close" onclick="closeModal('otpModal')"><i class="fas fa-times"></i></button>
        <div id="otp-header">
            <div style="font-size:40px; color:var(--c-primary); margin-bottom:15px;"><i class="fas fa-shield-alt"></i></div>
            <h3 class="t-heading-sm mb-sm">Verifikasi Keamanan</h3>
            <p class="text-mute mb-md" style="font-size:0.85rem">Masukkan 6 digit kode OTP yang baru saja kami kirimkan ke email Anda.</p>
        </div>
        <form id="form-otp">
            <input type="hidden" id="otp-action">
            <div class="form-group">
                <input type="text" id="otp_code" class="form-control text-center" style="font-size:24px; letter-spacing:10px; font-weight:bold" maxlength="6" required autocomplete="off" inputmode="numeric" oninput="document.getElementById('otp-error-msg').style.display='none'">
            </div>
            <!-- Error message shown inline, without closing modal -->
            <div id="otp-error-msg" style="display:none; background:rgba(220,38,38,0.12); border:1px solid rgba(220,38,38,0.4); border-left:3px solid #dc2626; color:#fca5a5; padding:10px 14px; font-size:13px; margin-bottom:12px; text-align:left;">
                <i class="fas fa-exclamation-circle" style="margin-right:6px;"></i><span></span>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-otp" style="width:100%">Verifikasi & Simpan</button>
        </form>
        <div id="otp-success-state" style="display:none; text-align:center; padding: 10px 0;">
            <div style="font-size:50px; color:var(--c-primary); margin-bottom:15px;"><i class="fas fa-check-circle"></i></div>
            <h3 class="t-heading-sm mb-sm" style="color:var(--c-primary)">Berhasil!</h3>
            <p class="text-mute mb-md" id="otp-success-msg-text" style="font-size:0.9rem; line-height:1.5;">Tindakan berhasil dilakukan.</p>
            <button type="button" class="btn btn-primary" style="width:100%" onclick="location.reload()">OK</button>
        </div>
    </div>
</div>

<!-- Reset PIN Modal -->
<div id="resetPinModal" class="profile-modal">
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
        <div id="reset-pin-success-state" style="display:none; text-align:center; padding: 10px 0;">
            <div style="font-size:50px; color:var(--c-primary); margin-bottom:15px;"><i class="fas fa-check-circle"></i></div>
            <h3 class="t-heading-sm mb-sm" style="color:var(--c-primary)">Berhasil!</h3>
            <p class="text-mute mb-md" id="reset-pin-success-msg-text" style="font-size:0.9rem; line-height:1.5;">PIN berhasil diubah.</p>
            <button type="button" class="btn btn-primary" style="width:100%" onclick="location.reload()">OK</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let otpPendingData = {};

function openEmailModal() {
    const m = document.getElementById('emailModal');
    if(m) {
        m.style.cssText = 'display:flex !important; pointer-events:all !important;';
        m.classList.add('active');
    }
}
function closeModal(id) {
    const m = document.getElementById(id);
    if(m) {
        m.style.display = 'none';
        m.classList.remove('active');
        
        // Reset state for OTP modal if closed
        if(id === 'otpModal') {
            const form = document.getElementById('form-otp');
            const header = document.getElementById('otp-header');
            const success = document.getElementById('otp-success-state');
            const error = document.getElementById('otp-error-msg');
            const input = document.getElementById('otp_code');
            if(form) form.style.display = 'block';
            if(header) header.style.display = 'block';
            if(success) success.style.display = 'none';
            if(error) error.style.display = 'none';
            if(input) input.value = '';
        }
        
        // Reset state for Reset PIN modal if closed
        if(id === 'resetPinModal') {
            const form = document.getElementById('form-reset-pin');
            const success = document.getElementById('reset-pin-success-state');
            if(form) {
                form.style.display = 'block';
                form.reset();
            }
            if(success) success.style.display = 'none';
        }
    }
}

function requestPinOtp() {
    const btn = event.target;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
    btn.disabled = true;

    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    fetch('/profile/ajax/pin/forgot-otp', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Lupa PIN?';
        btn.disabled = false;
        if(data.status === 'success') {
            document.getElementById('otp-action').value = 'forgot_pin';
            document.getElementById('otp_code').value = '';
            var _om=document.getElementById('otpModal');if(_om){_om.style.cssText='display:flex !important;pointer-events:all !important;';}
        } else {
            showToast('error', 'Gagal', data.message || 'Error');
        }
    }).catch(e => {
        btn.innerHTML = 'Lupa PIN?';
        btn.disabled = false;
        showToast('error', 'Gagal', 'Terjadi kesalahan jaringan.');
    });
}

function payWithSnap(tokenStr) {
    if (!tokenStr) {
        showToast('error', 'Gagal', 'Token pembayaran tidak ditemukan.');
        return;
    }
    window.snap.pay(tokenStr, {
      onSuccess: function(result){
        showToast('success', 'Berhasil', 'Pembayaran berhasil!');
        setTimeout(() => window.location.reload(), 2000);
      },
      onPending: function(result){
        showToast('info', 'Menunggu', 'Menunggu pembayaran.');
      },
      onError: function(result){
        showToast('error', 'Gagal', 'Pembayaran gagal.');
      }
    });
}

function toggleVisibility(inputId, btn) {
    const input = document.getElementById(inputId);
    if(!input) return;
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

function cekStatusMasal() {
    let pendingIds = [];
    document.querySelectorAll('[data-status="pending"]').forEach(el => {
        pendingIds.push(el.dataset.id);
    });

    if(pendingIds.length === 0) {
        showToast('info', 'Info', 'Tidak ada transaksi pending.');
        return;
    }

    let btn = document.getElementById('btn-cek-status');
    if(btn) {
        btn.innerText = 'Mengecek...';
        btn.disabled = true;
    }

    fetch('{{ route("profile.orders.check-statuses") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
            if(btn) {
                btn.innerText = 'Cek Status Pending';
                btn.disabled = false;
            }
            showToast('info', 'Info', 'Status belum berubah.');
        }
    }).catch(e => {
        if(btn) {
            btn.innerText = 'Cek Status Pending';
            btn.disabled = false;
        }
    });
}

function showDetail(id) {
    var _mdm=document.getElementById('modalDetail');if(_mdm){_mdm.style.cssText='display:flex !important;pointer-events:all !important;';}
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
    const md = document.getElementById('modalDetail');
    if(md) md.style.display = 'none';
}

function openInvoiceModal(id) {
    const im = document.getElementById('invoiceModal');
    if(im) {
        im.style.cssText='display:flex !important;pointer-events:all !important;';
        document.getElementById('invoice_order_id').value = id;
    }
}

// ---------------- DOM CONTENT LOADED ----------------
document.addEventListener('DOMContentLoaded', function() {
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    // Ganti Email Button
    const btnGantiEmail = document.getElementById('btn-ganti-email');
    if(btnGantiEmail) {
        btnGantiEmail.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const modal = document.getElementById('emailModal');
            if(modal) {
                modal.style.cssText = 'display:flex !important; pointer-events:all !important;';
                modal.classList.add('active');
            } else {
                showToast('error', 'Gagal', 'emailModal not found in DOM');
            }
        });
    }


    // Password Form
    const fp = document.getElementById('form-password');
    if(fp) {
        fp.addEventListener('submit', function(e) {
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
                    document.getElementById('otp-action').value = 'password';
                    document.getElementById('otp_code').value = '';
                    var _om=document.getElementById('otpModal');if(_om){_om.style.cssText='display:flex !important;pointer-events:all !important;';}
                } else {
                    showToast('error', 'Gagal', data.message || 'Error');
                }
            }).catch(e => {
                btn.innerHTML = 'Lanjut & Kirim OTP';
                btn.disabled = false;
                showToast('error', 'Gagal', 'Gagal terhubung ke server');
            });
        });
    }

    // PIN Form
    const fpin = document.getElementById('form-pin');
    if(fpin) {
        fpin.addEventListener('submit', function(e) {
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
                    document.getElementById('otp-action').value = 'pin';
                    document.getElementById('otp_code').value = '';
                    var _om=document.getElementById('otpModal');if(_om){_om.style.cssText='display:flex !important;pointer-events:all !important;';}
                } else {
                    if (typeof showToast === 'function') showToast('error', 'Gagal', data.message || 'Terjadi kesalahan.');
                }
            }).catch(e => {
                btn.innerHTML = 'Lanjut & Kirim OTP';
                btn.disabled = false;
                if (typeof showToast === 'function') showToast('error', 'Gagal', 'Terjadi kesalahan jaringan.');
            });
        });
    }

    // Email Form
    const fem = document.getElementById('form-email');
    if(fem) {
        fem.addEventListener('submit', function(e) {
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
                    closeModal('emailModal');
                    document.getElementById('otp-action').value = 'email';
                    document.getElementById('otp_code').value = '';
                    var _om=document.getElementById('otpModal');if(_om){_om.style.cssText='display:flex !important;pointer-events:all !important;';}
                } else {
                    if (typeof showToast === 'function') showToast('error', 'Gagal', data.message || 'Terjadi kesalahan.');
                }
            }).catch(e => {
                btn.innerHTML = 'Kirim OTP ke Email Lama';
                btn.disabled = false;
                if (typeof showToast === 'function') showToast('error', 'Gagal', 'Terjadi kesalahan jaringan.');
            });
        });
    }

    // OTP Form
    const fotp = document.getElementById('form-otp');
    if(fotp) {
        fotp.addEventListener('submit', function(e) {
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
                        var _rpm=document.getElementById('resetPinModal');if(_rpm){_rpm.style.cssText='display:flex !important;pointer-events:all !important;';}
                        return;
                    }
                    // Transform to success state
                    const form = document.getElementById('form-otp');
                    const header = document.getElementById('otp-header');
                    const success = document.getElementById('otp-success-state');
                    const msgText = document.getElementById('otp-success-msg-text');
                    
                    if (form) form.style.display = 'none';
                    if (header) header.style.display = 'none';
                    if (success) success.style.display = 'block';
                    if (msgText) msgText.textContent = data.message || 'Berhasil verifikasi OTP.';
                } else {
                    // Show error inside OTP modal area without closing it
                    const otpError = document.getElementById('otp-error-msg');
                    if(otpError) {
                        const span = otpError.querySelector('span');
                        if(span) span.textContent = data.message || 'Kode OTP salah atau kedaluwarsa.';
                        otpError.style.display = 'block';
                    } else {
                        if (typeof showToast === 'function') showToast('error', 'Gagal', data.message || 'Kode OTP salah.');
                    }
                }
            }).catch(e => {
                btn.innerHTML = 'Verifikasi & Simpan';
                btn.disabled = false;
                if (typeof showToast === 'function') showToast('error', 'Gagal', 'Terjadi kesalahan jaringan.');
            });
        });
    }

    // Reset PIN Form
    const freset = document.getElementById('form-reset-pin');
    if(freset) {
        freset.addEventListener('submit', function(e) {
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
                    // Transform to success state
                    const form = document.getElementById('form-reset-pin');
                    const success = document.getElementById('reset-pin-success-state');
                    const msgText = document.getElementById('reset-pin-success-msg-text');
                    
                    if (form) form.style.display = 'none';
                    if (success) success.style.display = 'block';
                    if (msgText) msgText.textContent = data.message || 'PIN berhasil diubah.';
                } else {
                    if (typeof showToast === 'function') showToast('error', 'Gagal', data.message || 'Gagal mengubah PIN');
                }
            }).catch(e => {
                btn.innerHTML = 'Simpan PIN Baru';
                btn.disabled = false;
                if (typeof showToast === 'function') showToast('error', 'Gagal', 'Terjadi kesalahan jaringan.');
            });
        });
    }

    // Invoice Form
    const finvoice = document.getElementById('form-invoice-pin');
    if(finvoice) {
        finvoice.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = document.getElementById('btn-invoice-submit');
            const order_id = document.getElementById('invoice_order_id').value;
            const pin = document.getElementById('invoice_pin').value;

            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
            btn.disabled = true;

            fetch('/profile/ajax/pin/verify-invoice', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': token},
                body: JSON.stringify({ order_id: order_id, pin: pin })
            })
            .then(r => r.json())
            .then(data => {
                btn.innerHTML = 'Download Invoice';
                btn.disabled = false;
                
                if (data.status === 'success') {
                    closeModal('invoiceModal');
                    document.getElementById('invoice_pin').value = '';
                    window.location.href = `/profile/orders/${order_id}/invoice/download?token=${data.download_token}`;
                } else {
                    alert('PIN Salah atau Error.');
                }
            }).catch(e => {
                btn.innerHTML = 'Download Invoice';
                btn.disabled = false;
            });
        });
    }

    // Tabs logic
    const tabs = document.querySelectorAll('.pill-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    function activateTab(tabName) {
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

    window.activateTab = activateTab;

    if (window.location.hash) {
      const hashTab = window.location.hash.substring(1);
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

    // Avatar cropper
    let cropper = null;
    const avatarInput = document.getElementById('avatar-input');
    const overlay = document.getElementById('cropper-overlay');
    const cropperImage = document.getElementById('cropper-image');

    if (avatarInput) {
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
    }

    window.cancelCrop = function() {
        if(overlay) overlay.style.display = 'none';
        if(cropper) cropper.destroy();
        if(avatarInput) avatarInput.value = '';
    }

    window.saveCrop = function() {
        if(!cropper) return;
        const canvas = cropper.getCroppedCanvas({
          width: 300,
          height: 300
        });
        
        fetch('{{ route("profile.avatar") }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token
          },
          body: JSON.stringify({
            avatar_base64: canvas.toDataURL('image/jpeg')
          })
        })
        .then(res => res.json())
        .then(data => {
          if(data.success) {
            document.getElementById('avatar-preview').src = data.avatar_url;
            window.cancelCrop();
            window.location.reload();
          }
        });
    }
});
</script>
@endpush
