<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<style>
/* Modals for media */
.media-modal {
    display: none; 
    position: fixed; z-index: 9999; left: 0; top: 0;
    width: 100%; height: 100%; overflow: auto;
    background-color: rgba(0,0,0,0.8);
    backdrop-filter: blur(5px);
}
.media-modal-content {
    background-color: var(--c-surface-dark);
    margin: 5% auto; padding: 20px;
    border: 1px solid var(--c-hairline-strong);
    width: 90%; max-width: 800px;
    border-radius: 0;
    color: var(--c-ink);
}
.media-preview-container {
    max-height: 500px;
    overflow: hidden;
    display: flex; justify-content: center; align-items: center;
    background: #000;
    margin-bottom: 20px;
}
.media-preview-container img, .media-preview-container video {
    max-width: 100%; max-height: 500px;
}
</style>

<!-- Cropper Modal -->
<div id="cropperModal" class="media-modal">
    <div class="media-modal-content">
        <h3 style="margin-top:0;">Potong Gambar</h3>
        <div class="media-preview-container">
            <img id="cropperImage" src="">
        </div>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button type="button" class="btn-outline" onclick="closeCropperModal()">Batal</button>
            <button type="button" class="btn-primary" onclick="confirmCrop()">Gunakan Gambar</button>
        </div>
    </div>
</div>

<!-- Video Trimmer Modal -->
<div id="videoModal" class="media-modal">
    <div class="media-modal-content">
        <h3 style="margin-top:0;">Atur Durasi Video</h3>
        <p style="color:var(--c-mute);font-size:0.9rem;">Pilih titik awal dan akhir pemutaran. Maksimal durasi tayang: 3 Menit (180 Detik).</p>
        <div class="media-preview-container">
            <video id="videoPlayer" controls></video>
        </div>
        <div style="margin-bottom: 20px;display:flex;gap:15px;align-items:center;">
            <div style="flex:1;">
                <label>Mulai (Detik): <span id="lbl-start">0</span></label>
                <input type="range" id="videoStart" min="0" value="0" style="width:100%;">
            </div>
            <div style="flex:1;">
                <label>Selesai (Detik): <span id="lbl-end">0</span></label>
                <input type="range" id="videoEnd" min="0" value="0" style="width:100%;">
            </div>
        </div>
        <div id="videoError" style="color:#e53935;margin-bottom:10px;font-weight:bold;display:none;">Durasi pilihan tidak boleh lebih dari 3 Menit (180 detik)!</div>
        <div style="display:flex;gap:10px;justify-content:flex-end;">
            <button type="button" class="btn-outline" onclick="closeVideoModal()">Batal</button>
            <button type="button" class="btn-primary" onclick="confirmVideo()">Gunakan Video</button>
        </div>
    </div>
</div>

<script>
let cropper = null;
let currentFileInput = null; // To know which form (create or edit) triggered it

function openCropper(file, fileInput) {
    currentFileInput = fileInput;
    const reader = new FileReader();
    reader.onload = function(e) {
        const image = document.getElementById('cropperImage');
        image.src = e.target.result;
        document.getElementById('cropperModal').style.display = 'block';
        if (cropper) cropper.destroy();
        cropper = new Cropper(image, {
            viewMode: 2,
            autoCropArea: 1,
        });
    };
    reader.readAsDataURL(file);
}

function closeCropperModal() {
    document.getElementById('cropperModal').style.display = 'none';
    if (cropper) cropper.destroy();
    if (currentFileInput) currentFileInput.value = ''; // Reset if canceled
}

function confirmCrop() {
    if (!cropper) return;
    cropper.getCroppedCanvas({
        maxWidth: 1920,
        maxHeight: 1080,
    }).toBlob((blob) => {
        // Fallback: Send base64 just in case DataTransfer fails in older browsers
        const dataUrl = cropper.getCroppedCanvas({maxWidth: 1920, maxHeight: 1080}).toDataURL('image/jpeg', 0.85);
        setHiddenInput(currentFileInput.form, 'media_base64', dataUrl);

        // Set the blob into the file input using DataTransfer
        try {
            let dt = new DataTransfer();
            dt.items.add(new File([blob], 'cropped_image.jpg', { type: 'image/jpeg' }));
            currentFileInput.files = dt.files;
        } catch(e) {}
        
        // Also set media_type
        setHiddenInput(currentFileInput.form, 'media_type', 'image');
        
        // Show preview
        const previewContainer = currentFileInput.parentElement.querySelector('.media-preview-final');
        if (previewContainer) {
            const objectUrl = URL.createObjectURL(blob);
            previewContainer.innerHTML = `<div style="font-size:0.85rem;color:var(--c-mute);margin-bottom:6px;">Pratinjau Gambar:</div><img src="${objectUrl}" style="max-height:200px;border-radius: 0;border:1px solid var(--c-hairline);object-fit:cover;">`;
            previewContainer.style.display = 'block';
        }
        
        closeCropperModal();
    }, 'image/jpeg', 0.85);
}

let videoDuration = 0;
function openVideoTrimmer(file, fileInput) {
    currentFileInput = fileInput;
    const video = document.getElementById('videoPlayer');
    const fileURL = URL.createObjectURL(file);
    video.src = fileURL;
    
    video.onloadedmetadata = function() {
        videoDuration = Math.floor(video.duration);
        const vStart = document.getElementById('videoStart');
        const vEnd = document.getElementById('videoEnd');
        
        vStart.max = videoDuration;
        vEnd.max = videoDuration;
        vStart.value = 0;
        vEnd.value = videoDuration;
        
        updateVideoLabels();
        document.getElementById('videoModal').style.display = 'block';
    };
}

function updateVideoLabels() {
    const s = parseInt(document.getElementById('videoStart').value);
    const e = parseInt(document.getElementById('videoEnd').value);
    document.getElementById('lbl-start').innerText = s;
    document.getElementById('lbl-end').innerText = e;
    
    const err = document.getElementById('videoError');
    if (e - s > 180) {
        err.style.display = 'block';
    } else if (s >= e) {
        err.innerText = "Titik akhir harus lebih besar dari awal!";
        err.style.display = 'block';
    } else {
        err.style.display = 'none';
    }
}

document.getElementById('videoStart').addEventListener('input', updateVideoLabels);
document.getElementById('videoEnd').addEventListener('input', updateVideoLabels);

function closeVideoModal() {
    document.getElementById('videoModal').style.display = 'none';
    document.getElementById('videoPlayer').pause();
    if (currentFileInput) currentFileInput.value = ''; // Reset if canceled
}

function confirmVideo() {
    const s = parseInt(document.getElementById('videoStart').value);
    const e = parseInt(document.getElementById('videoEnd').value);
    
    if (e - s > 180 || s >= e) {
        return; // Invalid, do nothing, label shows error
    }
    
    // Valid! Let's set hidden inputs
    setHiddenInput(currentFileInput.form, 'media_type', 'video');
    setHiddenInput(currentFileInput.form, 'video_start', s);
    setHiddenInput(currentFileInput.form, 'video_end', e);
    
    // Show preview
    const previewContainer = currentFileInput.parentElement.querySelector('.media-preview-final');
    if (previewContainer) {
        const file = currentFileInput.files[0];
        if (file) {
            const objectUrl = URL.createObjectURL(file);
            previewContainer.innerHTML = `<div style="font-size:0.85rem;color:var(--c-mute);margin-bottom:6px;">Pratinjau Video (${s}s - ${e}s):</div><video controls style="max-width:100%;max-height:300px;border-radius: 0;border:1px solid var(--c-hairline);" preload="metadata"><source src="${objectUrl}#t=${s},${e}">Browser Anda tidak mendukung tag video.</video>`;
            previewContainer.style.display = 'block';
        }
    }
    
    document.getElementById('videoModal').style.display = 'none';
    document.getElementById('videoPlayer').pause();
}

function setHiddenInput(form, name, value) {
    let input = form.querySelector(`input[name="${name}"]`);
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        form.appendChild(input);
    }
    input.value = value;
}

// Bind file inputs
document.addEventListener('DOMContentLoaded', function() {
    const mediaInputs = document.querySelectorAll('.review-media-input');
    mediaInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;
            
            if (file.type.startsWith('image/')) {
                openCropper(file, this);
            } else if (file.type.startsWith('video/')) {
                if (file.size > 50 * 1024 * 1024) {
                    showSweetAlert('Gagal', 'Ukuran video maksimal adalah 50MB!', 'error');
                    this.value = '';
                    return;
                }
                openVideoTrimmer(file, this);
            } else {
                showSweetAlert('Gagal', 'Tipe file tidak didukung!', 'error');
                this.value = '';
            }
        });
    });
});
</script>
