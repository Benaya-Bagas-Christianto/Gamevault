<?php
$file = 'd:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php';
$content = file_get_contents($file);

// 1. Ganti Email Link
$content = preg_replace(
    '/<a href="\{\{ route\(\'profile\.email\'\) \}\}" class="btn btn-outline"(.*?)>Ganti Email<\/a>/',
    '<button type="button" class="btn btn-outline"$1 onclick="openEmailModal()">Ganti Email</button>',
    $content
);

// 2. Tab Password
$oldPasswordForm = <<<EOT
        <form action="{{ route('profile.password') }}" method="POST" style="max-width:400px">
          @csrf
          <div class="form-group">
            <label class="form-label">Password Saat Ini</label>
            <input type="password" name="current_password" class="form-control" required>
            @error('current_password') <div class="error-msg">{{ \$message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control" required placeholder="Minimal 8 karakter">
            @error('password') <div class="error-msg">{{ \$message }}</div> @enderror
          </div>
          <div class="form-group">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control" required>
          </div>
          <button type="submit" class="btn btn-primary">Update Password</button>
        </form>
EOT;

$newPasswordForm = <<<EOT
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
EOT;
$content = str_replace($oldPasswordForm, $newPasswordForm, $content);

// 3. Tab PIN 
// Find the old pin form
if (preg_match('/<form action="\{\{ route\(\'profile\.pin\'\) \}\}" method="POST" style="max-width:400px">(.*?)<button type="submit" class="btn btn-primary">Update PIN<\/button>\s*<\/form>/s', $content, $m)) {
    $inner = $m[1];
    $inner = str_replace('@csrf', '', $inner);
    $newPinForm = '<form id="form-pin" style="max-width:400px">' . $inner . '<button type="submit" class="btn btn-primary" id="btn-pin">Lanjut & Kirim OTP</button>' . "\n        </form>";
    $content = str_replace($m[0], $newPinForm, $content);
}

// 4. Remove old Forgot PIN modal and JS
$content = preg_replace('/<!-- Modal Forgot PIN OTP -->.*?<\/div>\s*<\/div>/s', '', $content);
$content = preg_replace('/function requestPinOtp\(\) \{.*?\n\}/s', '', $content);
$content = preg_replace('/function submitForgotPinOtp\(\) \{.*?\n\}/s', '', $content);
$content = preg_replace('/function closeForgotPinOtpModal\(\) \{.*?\n\}/s', '', $content);
// Ensure we don't accidentally leave route('profile.pin.forgot.otp') anywhere
$content = preg_replace('/\{\{\s*route\(\'profile\.pin\.forgot\.(otp|verify)\'\)\s*\}\}/', '/profile/ajax/pin/forgot-$1', $content);

// 5. Append Modals and JS inside @push('scripts')
$modals = <<<EOT
<style>
.modal-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 10000; display: none; align-items: center; justify-content: center; }
.modal-box { background: var(--c-surface); border: 1px solid var(--c-hairline); padding: 30px; border-radius: 8px; max-width: 400px; width: 100%; position: relative; }
.modal-close { position: absolute; top: 15px; right: 15px; background: none; border: none; color: var(--c-mute); font-size: 20px; cursor: pointer; }
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
let otpPendingData = {};
const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function openEmailModal() {
    document.getElementById('emailModal').style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}

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
            alert(data.message || 'Error');
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
            alert(data.message || 'Error');
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
            alert(data.message || 'Error');
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
</script>
EOT;

$content = str_replace('@endpush', $modals . "\n@endpush", $content);

file_put_contents($file, $content);
echo "Cleanly applied UI updates to restored index.blade.php.";
