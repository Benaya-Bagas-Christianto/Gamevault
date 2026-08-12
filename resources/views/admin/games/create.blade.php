@extends('layouts.admin')

@section('page_title', 'Tambah Game')

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" />
<style>
.cover-upload-area {
    border: 2px dashed var(--border-color);
    border-radius: 0;
    padding: 24px;
    text-align: center;
    cursor: pointer;
    transition: var(--transition);
    background: #1c1c1c;
}
.cover-upload-area:hover { border-color: var(--nvidia-green); background: rgba(118,185,0,.08); }
.cover-upload-area.has-preview { border-style: solid; border-color: var(--nvidia-green); }
#coverPreviewImg { display: none; width: 100%; height: auto; aspect-ratio: 460/215; object-fit: cover; border-radius: 0; margin: 0 auto; }
</style>
@endpush

@section('content')

<div class="page-header" style="justify-content: flex-start; gap: 16px; align-items: flex-start;">
    <a href="{{ route('admin.games.index') }}" class="topbar-icon-btn" title="Kembali" style="margin-top: 4px;">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div class="page-header-left">
        <h1>Tambah Game Baru</h1>
        <p>Isi detail informasi game yang ingin ditambahkan</p>
    </div>
</div>

<form action="{{ route('admin.games.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-layout-split" style="display:grid;grid-template-columns:1fr 320px;gap:22px;align-items:start;">

        {{-- ---- LEFT COLUMN ---- --}}
        <div>

            {{-- Basic Info --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fa-solid fa-circle-info"></i> Informasi Dasar</div>
                </div>
                <div class="admin-card-body">

                    <div class="form-group">
                        <label class="form-label" for="title">Judul Game <span class="req">*</span></label>
                        <input type="text" id="title" name="title" class="form-control"
                               placeholder="e.g. Cyberpunk 2077"
                               value="{{ old('title') }}" required>
                        @error('title')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="description">Deskripsi</label>
                        <textarea id="description" name="description" class="form-control" rows="5"
                                  placeholder="Tulis deskripsi lengkap tentang game ini...">{{ old('description') }}</textarea>
                        @error('description')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <div class="form-row cols-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="price">Harga (Rp) <span class="req">*</span></label>
                            <input type="number" id="price" name="price" class="form-control"
                                   placeholder="e.g. 299000" min="0" step="1000"
                                   value="{{ old('price') }}" required>
                            @error('price')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="release_date">Tanggal Rilis</label>
                            <input type="date" id="release_date" name="release_date" class="form-control"
                                   value="{{ old('release_date') }}">
                            @error('release_date')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>

                </div>
            </div>

            {{-- Developer & Publisher --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fa-solid fa-building"></i> Developer & Publisher</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-row cols-2">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="developer">Developer</label>
                            <input type="text" id="developer" name="developer" class="form-control"
                                   placeholder="e.g. CD Projekt Red"
                                   value="{{ old('developer') }}">
                            @error('developer')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" for="publisher">Publisher</label>
                            <input type="text" id="publisher" name="publisher" class="form-control"
                                   placeholder="e.g. CD Projekt"
                                   value="{{ old('publisher') }}">
                            @error('publisher')<span class="form-error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- Genre --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fa-solid fa-tags"></i> Genre</div>
                </div>
                <div class="admin-card-body">
                    <div class="genre-grid">
                        @php
                            $genres = ['Action','Adventure','RPG','FPS','Sports','Horror','Indie','Simulation','Strategy','Racing','Puzzle','Fighting','Sandbox','Souls-like'];
                            $oldGenres = old('genre', []);
                        @endphp
                        @foreach($genres as $genre)
                        <label class="genre-item">
                            <input type="checkbox" name="genre[]" value="{{ $genre }}"
                                {{ in_array($genre, $oldGenres) ? 'checked' : '' }}>
                            <span>{{ $genre }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('genre')<span class="form-error" style="display:block;margin-top:8px;">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Game Previews (Video & Youtube) --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fa-solid fa-video"></i> Game Previews & Media</div>
                </div>
                <div class="admin-card-body">
                    <div class="form-group">
                        <label class="form-label" for="trailer_mp4">Upload Trailer (MP4)</label>
                        <input type="file" id="trailer_mp4" name="trailer_mp4" class="form-control" accept="video/mp4">
                        <span style="font-size:11px;color:#9ca3af;">Upload file video berekstensi .mp4</span>
                        @error('trailer_mp4')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label" for="youtube_link">YouTube Link</label>
                        <input type="url" id="youtube_link" name="youtube_link" class="form-control"
                               placeholder="e.g. https://www.youtube.com/watch?v=..."
                               value="{{ old('youtube_link') }}">
                        <span style="font-size:11px;color:#9ca3af;">Masukkan tautan YouTube untuk video gameplay/trailer</span>
                        @error('youtube_link')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>
            </div>

        </div>{{-- /.left-column --}}

        {{-- ---- RIGHT COLUMN ---- --}}
        <div>

            {{-- Cover Image --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fa-solid fa-image"></i> Cover Image</div>
                </div>
                <div class="admin-card-body">
                    <div class="cover-upload-area" id="coverUploadArea" onclick="document.getElementById('coverInput').click()">
                        <img id="coverPreviewImg" src="" alt="Preview">
                        <div id="coverPlaceholder">
                            <i class="fa-solid fa-cloud-arrow-up" style="font-size:32px;color:#ccc;margin-bottom:10px;"></i>
                            <p style="font-size:13px;color:#9ca3af;margin:0;">Klik untuk upload cover</p>
                            <p style="font-size:11px;color:#ccc;margin-top:4px;">JPG, PNG, WEBP max 2MB</p>
                        </div>
                    </div>
                    <input type="file" id="coverInput" name="cover_image"
                           accept="image/*" style="display:none;">
                           
                    <div style="margin-top:10px;display:flex;gap:8px;">
                        <input type="url" id="coverUrlInput" class="form-control" placeholder="Atau paste URL gambar dari Steam..." style="font-size:12px;">
                        <button type="button" class="btn-outline" onclick="fetchCoverUrl()" style="padding:0 12px;font-size:12px;" id="btnFetchUrl">Ambil</button>
                    </div>

                    @error('cover_image')<span class="form-error">{{ $message }}</span>@enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="admin-card">
                <div class="admin-card-header">
                    <div class="admin-card-title"><i class="fa-solid fa-toggle-on"></i> Status</div>
                </div>
                <div class="admin-card-body">
                    <label class="genre-item" style="border:none;padding:0;">
                        <input type="checkbox" name="is_active" value="1"
                               id="is_active"
                               {{ old('is_active', '1') ? 'checked' : '' }}>
                        <div>
                            <div style="font-weight:600;font-size:13.5px;">Game Aktif</div>
                            <div style="font-size:12px;color:#9ca3af;margin-top:2px;">Game akan terlihat di katalog publik</div>
                        </div>
                    </label>
                </div>
            </div>

            {{-- Submit --}}
            <div style="display:flex;flex-direction:column;gap:10px;">
                <button type="submit" class="btn-primary" style="justify-content:center;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Game
                </button>
                <a href="{{ route('admin.games.index') }}" class="btn-outline" style="justify-content:center;">
                    <i class="fa-solid fa-xmark"></i> Batal
                </a>
            </div>

        </div>{{-- /.right-column --}}

    </div>

</form>

@endsection

<!-- Cropper Modal -->
<div id="cropperModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.8); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:#1c1c1c; padding:20px; border-radius: 0; width:90%; max-width:600px;">
        <h3 style="color:#fff; margin-bottom:16px;">Sesuaikan Posisi Cover</h3>
        <div style="width:100%; height:400px; background:#000;">
            <img id="cropperImage" src="" style="max-width:100%; display:block;">
        </div>
        <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
            <button type="button" class="btn-outline" onclick="closeCropper()">Batal</button>
            <button type="button" class="btn-primary" onclick="applyCrop()">Terapkan & Simpan</button>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
let cropper = null;
let currentFilename = 'cover.jpg';

document.getElementById('coverInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    currentFilename = file.name;
    
    const reader = new FileReader();
    reader.onload = function(evt) {
        document.getElementById('cropperImage').src = evt.target.result;
        document.getElementById('cropperModal').style.display = 'flex';
        
        if (cropper) { cropper.destroy(); }
        cropper = new Cropper(document.getElementById('cropperImage'), {
            aspectRatio: 460 / 215,
            viewMode: 1,
            autoCropArea: 1,
            background: false
        });
    };
    reader.readAsDataURL(file);
});

function fetchCoverUrl() {
    const url = document.getElementById('coverUrlInput').value;
    if(!url) return;
    const btn = document.getElementById('btnFetchUrl');
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    
    fetch('{{ route("admin.games.fetch-image") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({url: url})
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = 'Ambil';
        btn.disabled = false;
        if(data.success) {
            currentFilename = url.split('/').pop().split('?')[0] || 'fetched.jpg';
            document.getElementById('cropperImage').src = data.image;
            document.getElementById('cropperModal').style.display = 'flex';
            
            if (cropper) { cropper.destroy(); }
            cropper = new Cropper(document.getElementById('cropperImage'), {
                aspectRatio: 460 / 215,
                viewMode: 1,
                autoCropArea: 1,
                background: false
            });
            document.getElementById('coverUrlInput').value = '';
        } else {
            showSweetAlert('Gagal!', data.message || 'Gagal mengambil gambar', 'error');
        }
    })
    .catch(err => {
        btn.innerHTML = 'Ambil';
        btn.disabled = false;
        showSweetAlert('Gagal!', 'Terjadi kesalahan jaringan', 'error');
    });
}

function closeCropper() {
    document.getElementById('cropperModal').style.display = 'none';
    if(cropper) { cropper.destroy(); cropper = null; }
    document.getElementById('coverInput').value = '';
}

function applyCrop() {
    if(!cropper) return;
    cropper.getCroppedCanvas({
        width: 920,
        height: 430
    }).toBlob(function(blob) {
        const croppedFile = new File([blob], currentFilename, { type: 'image/jpeg', lastModified: new Date().getTime() });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(croppedFile);
        document.getElementById('coverInput').files = dataTransfer.files;
        
        const previewUrl = URL.createObjectURL(blob);
        const img = document.getElementById('coverPreviewImg');
        const placeholder = document.getElementById('coverPlaceholder');
        const area = document.getElementById('coverUploadArea');
        
        img.src = previewUrl;
        img.style.display = 'block';
        if(placeholder) placeholder.style.display = 'none';
        if(area) area.classList.add('has-preview');
        
        document.getElementById('cropperModal').style.display = 'none';
        if(cropper) { cropper.destroy(); cropper = null; }
    }, 'image/jpeg', 0.85);
}

// Initialize CKEditor
ClassicEditor
    .create(document.querySelector('#description'), {
        toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', '|', 'undo', 'redo' ]
    })
    .then(editor => {
        // Force dark background on CKEditor
        const editorElement = editor.ui.view.editable.element;
        editorElement.style.backgroundColor = '#252525';
        editorElement.style.color = '#e5e7eb';
        editorElement.style.borderColor = 'var(--border-color)';
        
        const toolbarElement = editor.ui.view.toolbar.element;
        toolbarElement.style.backgroundColor = '#1c1c1c';
        toolbarElement.style.borderColor = 'var(--border-color)';
    })
    .catch(error => {
        console.error(error);
    });
</script>
<style>
/* CSS Override for CKEditor Dark Theme */
.ck-editor__editable_inline { min-height: 200px; }
.ck-toolbar { background: #1c1c1c !important; border-color: var(--border-color) !important; }
.ck-editor__main .ck-editor__editable { background: #252525 !important; border-color: var(--border-color) !important; color: #e5e7eb !important; }
.ck-button { color: #e5e7eb !important; }
.ck-button:hover { background: #333 !important; }
.ck.ck-button.ck-on { background: rgba(118,185,0,.15) !important; color: var(--nvidia-green) !important; }
</style>
@endpush
