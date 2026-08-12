<?php
$content = file_get_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php');

// Replace class modal-overlay with profile-modal-overlay
$content = str_replace('class="modal-overlay"', 'class="profile-modal"', $content);

// Fix the CSS at the top
$oldCss = '.modal-overlay.active {
    display: flex !important;
}
.modal-box {';
$newCss = '.profile-modal.active {
    display: flex !important;
}
.modal-box {';
$content = str_replace($oldCss, $newCss, $content);

// Fix the main modal-overlay CSS
$oldMainCss = '.modal-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
}';
$newMainCss = '.profile-modal {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.85);
    z-index: 99999;
    display: none;
    align-items: center;
    justify-content: center;
    pointer-events: all;
}';
$content = str_replace($oldMainCss, $newMainCss, $content);

// Fix JS openEmailModal and closeModal functions
$oldOpen = "function openEmailModal() {
    const m = document.getElementById('emailModal');
    if(m) {
        m.classList.add('active');
        m.style.display = 'flex';
    }
}
function closeModal(id) {
    const m = document.getElementById(id);
    if(m) {
        m.classList.remove('active');
        m.style.display = 'none';
    }
}";
$newOpen = "function openEmailModal() {
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
    }
}";
$content = str_replace($oldOpen, $newOpen, $content);

// Fix btn-ganti-email listener in DOMContentLoaded
$oldBtnListener = "            if(modal) {
                modal.style.display = 'flex';
                modal.classList.add('active');
            }";
$newBtnListener = "            if(modal) {
                modal.style.cssText = 'display:flex !important; pointer-events:all !important;';
                modal.classList.add('active');
            }";
$content = str_replace($oldBtnListener, $newBtnListener, $content);

// Fix inline onclick on the button
$oldOnclick = "onclick=\"var m=document.getElementById('emailModal');if(m){m.style.display='flex';m.classList.add('active');}else{alert('Modal tidak ditemukan');}\"";
$newOnclick = "onclick=\"var m=document.getElementById('emailModal');if(m){m.style.cssText='display:flex !important; pointer-events:all !important;';m.classList.add('active');}else{alert('Modal tidak ditemukan');}\"";
$content = str_replace($oldOnclick, $newOnclick, $content);

// Also fix all places where modal display is set to flex (for OTP, resetPin etc)
$content = str_replace(
    "document.getElementById('otpModal').style.display = 'flex';",
    "var _om=document.getElementById('otpModal');if(_om){_om.style.cssText='display:flex !important;pointer-events:all !important;';}",
    $content
);
$content = str_replace(
    "document.getElementById('resetPinModal').style.display = 'flex';",
    "var _rpm=document.getElementById('resetPinModal');if(_rpm){_rpm.style.cssText='display:flex !important;pointer-events:all !important;';}",
    $content
);
$content = str_replace(
    "document.getElementById('modalDetail').style.display = 'flex';",
    "var _mdm=document.getElementById('modalDetail');if(_mdm){_mdm.style.cssText='display:flex !important;pointer-events:all !important;';}",
    $content
);
$content = str_replace(
    "im.style.display = 'flex';",
    "im.style.cssText='display:flex !important;pointer-events:all !important;';",
    $content
);

file_put_contents('d:/Laragon/laragon/www/tokoGame/resources/views/profile/index.blade.php', $content);
echo "DONE. Fixed modal-overlay conflicts.\n";
