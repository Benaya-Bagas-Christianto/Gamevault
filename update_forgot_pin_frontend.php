<?php
$file = 'd:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php';
$content = file_get_contents($file);

$modalAndScript = <<<EOT

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

// Update global OTP Verification JS to handle forgot_pin action
const originalOtpVerify = document.getElementById('form-otp').onsubmit; 
// We will replace the entire otp verification script in the backend script to inject forgot_pin
</script>
EOT;

$content = str_replace('</script>', "</script>\n" . $modalAndScript, $content);

// We need to modify the JS in the form-otp submit event to add `if(action === 'forgot_pin') url = '/profile/ajax/pin/forgot-verify';`
$content = str_replace(
    "if(action === 'email') url = '/profile/ajax/email/verify-and-update';",
    "if(action === 'email') url = '/profile/ajax/email/verify-and-update';\n    if(action === 'forgot_pin') url = '/profile/ajax/pin/forgot-verify';",
    $content
);

// We also need to modify the success callback for OTP verification so that if action is 'forgot_pin', it opens resetPinModal instead of alert success
$content = str_replace(
    "if(data.status === 'success') {",
    "if(data.status === 'success') {\n            if (action === 'forgot_pin') {\n                closeModal('otpModal');\n                document.getElementById('resetPinModal').style.display = 'flex';\n                return;\n            }",
    $content
);

file_put_contents($file, $content);
echo "Frontend JS and Modals updated for Forgot PIN.";
