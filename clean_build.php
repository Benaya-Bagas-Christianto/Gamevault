<?php
$content = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');
// Extract the top part: @extends to @section('title')
preg_match('/@extends.*?@section\(\'title\'.*?\n/s', $content, $top);

// Modals CSS
$styles = <<<'HTML'
@push('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<style>
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; display: none; align-items: center; justify-content: center; }
.modal-box { background: var(--c-surface); border: 1px solid var(--c-hairline); padding: 30px; border-radius: 8px; max-width: 400px; width: 100%; position: relative; }
.modal-close { position: absolute; top: 15px; right: 15px; background: none; border: none; color: var(--c-mute); font-size: 20px; cursor: pointer; }
</style>
@endpush
HTML;

$modals = <<<'HTML'
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
HTML;

// Extract main content WITHOUT the modals (if they are there)
// But we can just use the content between @section('content') and @endsection
preg_match('/@section\(\'content\'\)(.*?)@endsection/s', $content, $mainContent);

$scripts = <<<'JS'
@push('scripts')
<script src="{{ config('services.midtrans.is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script>
let otpPendingData = {};

function openEmailModal() {
    document.getElementById('emailModal').style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

function showToast(msg, isError = false) {
    alert(msg); // fallback
}

const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
            document.getElementById('otp-action').value = 'password';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

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
            document.getElementById('otp-action').value = 'pin';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

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
            closeModal('emailModal');
            document.getElementById('otp-action').value = 'email';
            document.getElementById('otp_code').value = '';
            document.getElementById('otpModal').style.display = 'flex';
        } else {
            showToast(data.message || 'Error', true);
        }
    });
});

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
            location.reload(); 
        } else {
            alert('GAGAL: ' + (data.message || 'Error verifikasi'));
        }
    });
});

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
            closeModal('resetPinModal');
            alert('BERHASIL: ' + data.message);
            location.reload();
        } else {
            alert('GAGAL: ' + (data.message || 'Gagal mengubah PIN'));
        }
    });
});

function payWithSnap(tokenStr) {
    if (!tokenStr) {
        alert('Error: Token pembayaran tidak ditemukan.');
        return;
    }
    window.snap.pay(tokenStr, {
      onSuccess: function(result){
        alert('Pembayaran berhasil!');
        setTimeout(() => window.location.reload(), 2000);
      },
      onPending: function(result){
        alert('Menunggu pembayaran.');
      },
      onError: function(result){
        alert('Pembayaran gagal.');
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

document.addEventListener('DOMContentLoaded', function() {
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
});

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

function cancelCrop() {
    overlay.style.display = 'none';
    if(cropper) cropper.destroy();
    if(avatarInput) avatarInput.value = '';
}

function saveCrop() {
    if(!cropper) return;
    const canvas = cropper.getCroppedCanvas({
      width: 300,
      height: 300
    });
    
    fetch('{{ route("profile.avatar") }}', {
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

function cekStatusMasal() {
      let pendingIds = [];
      document.querySelectorAll('[data-status="pending"]').forEach(el => {
          pendingIds.push(el.dataset.id);
      });

      if(pendingIds.length === 0) {
          alert('Tidak ada transaksi pending.');
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
              alert('Status belum berubah.');
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

function openInvoiceModal(id) {
    document.getElementById('invoiceModal').style.display = 'flex';
    document.getElementById('invoice_order_id').value = id;
}

function submitInvoicePin(e) {
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
            
            // Trigger download link
            window.location.href = `/profile/orders/${order_id}/invoice/download?token=${data.download_token}`;
        } else {
            alert('PIN Salah atau Error.');
        }
    });
}
</script>
@endpush
JS;

$newFile = trim($top[0]) . "\n\n" . $styles . "\n\n@section('content')\n" . trim($mainContent[1]) . "\n\n" . $modals . "\n@endsection\n\n" . $scripts . "\n";
file_put_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php', $newFile);
