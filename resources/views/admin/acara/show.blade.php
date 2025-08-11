<x-app-layout>
    <div class="page-heading d-flex align-items-center justify-content-between">
        <h3>Detail Acara</h3>
        @if (auth()->user()->role && strcasecmp(auth()->user()->role->nama_role, 'Senior')===0)
            @if($acara->hasNotStarted())
                <button class="btn btn-secondary" disabled title="Acara belum dimulai">
                    <i class="bi bi-clock"></i> Tandai Selesai
                </button>
            @else
                <form action="{{ route('acara.toggle', $acara) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-{{ $acara->selesai ? 'warning' : 'success' }}">
                        {{ $acara->selesai ? 'Tandai Belum Selesai' : 'Tandai Selesai' }}
                    </button>
                </form>
            @endif
        @endif
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h4 class="card-title">{{ $acara->nama }}</h4>
                        @if($acara->wajibHadir->contains('id', auth()->id()))
                            <span class="badge bg-danger fs-6">Kamu Wajib Hadir!</span>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width: 40px">
                                        <i class="bi bi-calendar-event text-primary fs-4"></i>
                                    </div>
                                    <h6 class="mb-0">Waktu</h6>
                                </div>
                                <div class="ps-5">
                                    <p class="mb-0">{{ $acara->tanggal->translatedFormat('l, d F Y') }}</p>
                                    <p class="mb-0">Pukul {{ $acara->tanggal->format('H:i') }} WIB</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width: 40px">
                                        <i class="bi bi-geo-alt text-danger fs-4"></i>
                                    </div>
                                    <h6 class="mb-0">Lokasi</h6>
                                </div>
                                <p class="ps-5 mb-0">{{ $acara->lokasi }}</p>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width: 40px">
                                        <i class="bi bi-person-badge text-success fs-4"></i>
                                    </div>
                                    <h6 class="mb-0">Seragam</h6>
                                </div>
                                <p class="ps-5 mb-0">{{ $acara->seragam }}</p>
                            </div>

                            @if($acara->perlengkapan && count($acara->perlengkapan) > 0)
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width: 40px">
                                            <i class="bi bi-tools text-warning fs-4"></i>
                                        </div>
                                        <h6 class="mb-0">Perlengkapan</h6>
                                    </div>
                                    <ul class="ps-5 mb-0">
                                        @foreach($acara->perlengkapan as $item)
                                            <li>{{ $item }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($acara->deskripsi)
                                <div class="mb-4">
                                    <div class="d-flex align-items-center mb-3">
                                        <div style="width: 40px">
                                            <i class="bi bi-info-circle text-info fs-4"></i>
                                        </div>
                                        <h6 class="mb-0">Deskripsi</h6>
                                    </div>
                                    <p class="ps-5 mb-0">{{ $acara->deskripsi }}</p>
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <div class="mb-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div style="width: 40px">
                                        <i class="bi bi-people text-primary fs-4"></i>
                                    </div>
                                    <h6 class="mb-0">Wajib Hadir ({{ $acara->wajibHadir->count() }} orang)</h6>
                                </div>
                                <div class="table-responsive ps-5">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>No. KTA</th>
                                                <th>Nama</th>
                                                <th>Role</th>
                                                @if($acara->selesai)
                                                    <th class="text-center">Kehadiran</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($acara->wajibHadir as $user)
                                                <tr @if($user->id === auth()->id()) class="table-warning" @endif>
                                                    <td><span class="badge bg-secondary">{{ $user->biodata?->no_kta ?? '-' }}</span></td>
                                                    <td>
                                                        {{ $user->name }}
                                                        @if($user->id === auth()->id())
                                                            <span class="badge bg-warning text-dark">Kamu</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->role?->nama_role }}</td>
                                                    @if($acara->selesai)
                                                        <td class="text-center">
                                                            @php($hadir = $acara->absens->firstWhere('user_id', $user->id)?->hadir)
                                                            @if($hadir)
                                                                <i class="bi bi-check-circle-fill text-success"></i>
                                                            @else
                                                                <i class="bi bi-x-circle-fill text-danger"></i>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (auth()->user()->role && strcasecmp(auth()->user()->role->nama_role, 'Senior')===0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Absen Acara</h5>
                    </div>
                    <div class="card-body">
                        @if($acara->hasNotStarted())
                            <div class="alert alert-warning">
                                <i class="bi bi-clock"></i>
                                <strong>Acara belum dimulai.</strong> 
                                Absen hanya dapat dilakukan setelah acara dimulai pada {{ $acara->tanggal->translatedFormat('l, d F Y') }} pukul {{ $acara->tanggal->format('H:i') }} WIB.
                            </div>
                        @else
                            <form action="{{ route('acara.absen', $acara) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>No. KTA</th>
                                                <th>Nama</th>
                                                <th>Role</th>
                                                <th class="text-center">Hadir</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($acara->wajibHadir as $user)
                                                @php($hadir = optional($acara->absens->firstWhere('user_id', $user->id))->hadir)
                                                <tr>
                                                    <td><span class="badge bg-secondary">{{ $user->biodata?->no_kta ?? '-' }}</span></td>
                                                    <td>{{ $user->name }}</td>
                                                    <td>{{ $user->role?->nama_role }}</td>
                                                    <td class="text-center">
                                                        <input type="checkbox" name="absen[{{ $user->id }}]" @checked($hadir) />
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <button class="btn btn-primary">Simpan Absen</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            @if (auth()->user()->role && strcasecmp(auth()->user()->role->nama_role, 'Senior')===0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Feedback Hasil Kegiatan</h5>
                    </div>
                    <div class="card-body">
                        @if($acara->hasNotStarted())
                            <div class="alert alert-warning">
                                <i class="bi bi-clock"></i>
                                <strong>Acara belum dimulai.</strong> 
                                Feedback hanya dapat diberikan setelah acara dimulai pada {{ $acara->tanggal->translatedFormat('l, d F Y') }} pukul {{ $acara->tanggal->format('H:i') }} WIB.
                            </div>
                        @else
                            <form action="{{ route('acara.feedback', $acara) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label for="feedback" class="form-label">Tuliskan feedback, evaluasi, atau hasil dari kegiatan ini:</label>
                                    <textarea name="feedback" id="feedback" rows="6" class="form-control" placeholder="Contoh: Kegiatan berjalan lancar, peserta antusias, perlu perbaikan di sesi tanya jawab...">{{ old('feedback', $acara->feedback) }}</textarea>
                                </div>
                                <button class="btn btn-primary">Simpan Feedback</button>
                            </form>
                        @endif
                    </div>
                </div>
            @elseif($acara->feedback)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5>Feedback Hasil Kegiatan</h5>
                    </div>
                    <div class="card-body">
                        <p>{{ $acara->feedback }}</p>
                        <small class="text-muted">Feedback dari pengurus</small>
                    </div>
                </div>
            @endif

            <div class="card mt-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Galeri Foto Kegiatan ({{ $acara->photos->count() }} foto)</h5>
                    @if (auth()->user()->role && strcasecmp(auth()->user()->role->nama_role, 'Senior')===0 && $acara->hasStarted())
                        <form action="{{ route('acara.photo', $acara) }}" method="POST" enctype="multipart/form-data" id="photoUploadForm">
                            @csrf
                            <div class="d-flex flex-column" style="width: 500px;">
                                <div class="input-group mb-2">
                                    <input type="file" name="photos[]" accept="image/jpeg,image/png,image/jpg,image/gif" class="form-control" multiple required id="photoInput">
                                    <button type="submit" class="btn btn-primary" id="uploadBtn">Upload</button>
                                </div>
                                <small class="text-muted">Pilih 1 atau lebih foto (maks 20 foto)</small>
                                <small class="text-success d-block">✓ Foto besar akan dikompres otomatis di browser sebelum upload</small>
                                <div id="fileCount" class="text-info mt-1" style="display: none;"></div>
                                <div id="compressionProgress" class="mt-2" style="display: none;">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted">Mengompres foto...</small>
                                </div>
                            </div>
                        </form>
                    @elseif (auth()->user()->role && strcasecmp(auth()->user()->role->nama_role, 'Senior')===0 && $acara->hasNotStarted())
                        <div class="alert alert-warning mb-0" style="max-width: 500px;">
                            <i class="bi bi-clock"></i>
                            <small><strong>Upload foto belum tersedia.</strong> Foto dapat diupload setelah acara dimulai.</small>
                        </div>
                    @endif
                </div>
                <div class="card-body">
                    @if($acara->photos->count() > 0)
                        <div class="gallery-grid">
                            @foreach ($acara->photos as $index => $p)
                                <div class="gallery-item">
                                    <img src="{{ asset('storage/'.$p->path) }}" 
                                         alt="foto" 
                                         class="gallery-image"
                                         data-bs-toggle="modal" 
                                         data-bs-target="#photoModal"
                                         data-index="{{ $index }}"
                                         data-src="{{ asset('storage/'.$p->path) }}"
                                         data-uploader="{{ $p->uploader?->name }}"
                                         style="cursor: pointer;">
                                    <div class="gallery-overlay">
                                        <div class="gallery-actions">
                                            <button class="btn btn-light btn-sm" 
                                                    onclick="viewPhoto({{ $index }})"
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#photoModal">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            <a href="{{ asset('storage/'.$p->path) }}" 
                                               download="foto-{{ $acara->nama }}-{{ $index + 1 }}.jpg"
                                               class="btn btn-light btn-sm">
                                                <i class="bi bi-download"></i>
                                            </a>
                                            @if (auth()->user()->role && strcasecmp(auth()->user()->role->nama_role, 'Senior')===0)
                                                <form action="{{ route('photo.delete', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus foto ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-5">
                            <i class="bi bi-images fs-1"></i>
                            <p class="mt-2"><em>Belum ada foto</em></p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Photo Modal -->
            <div class="modal fade" id="photoModal" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Foto Kegiatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img id="modalImage" src="" alt="foto" class="img-fluid">
                            <div class="mt-3">
                                <small class="text-muted">Diupload oleh: <span id="modalUploader"></span></small>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-between">
                            <div>
                                <button type="button" class="btn btn-secondary" id="prevPhoto">
                                    <i class="bi bi-arrow-left"></i> Sebelumnya
                                </button>
                                <button type="button" class="btn btn-secondary" id="nextPhoto">
                                    Selanjutnya <i class="bi bi-arrow-right"></i>
                                </button>
                            </div>
                            <a id="downloadBtn" href="" download="" class="btn btn-primary">
                                <i class="bi bi-download"></i> Download
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .gallery-item {
            position: relative;
            aspect-ratio: 1;
            overflow: hidden;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .gallery-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .gallery-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .gallery-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-item:hover .gallery-image {
            transform: scale(1.1);
        }
        
        .gallery-actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .gallery-actions .btn {
            border-radius: 50%;
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
        }
    </style>

    <script>
        let photos = [];
        let currentIndex = 0;
        
        document.addEventListener('DOMContentLoaded', function() {
            // Collect all photos data
            document.querySelectorAll('.gallery-image').forEach((img, index) => {
                photos.push({
                    src: img.dataset.src,
                    uploader: img.dataset.uploader,
                    index: index
                });
            });

            // File input change handler
            const fileInput = document.getElementById('photoInput');
            const fileCount = document.getElementById('fileCount');
            const uploadForm = document.getElementById('photoUploadForm');
            
            if (fileInput && fileCount) {
                fileInput.addEventListener('change', function() {
                    const files = this.files;
                    if (files.length > 0) {
                        fileCount.style.display = 'block';
                        
                        if (files.length > 20) {
                            fileCount.textContent = `Terlalu banyak file! Maksimal 20 foto.`;
                            fileCount.className = 'text-danger mt-1';
                        } else {
                            fileCount.className = 'text-info mt-1';
                            
                            // Hitung total ukuran file
                            let totalSize = 0;
                            let needsCompression = false;
                            for (let i = 0; i < files.length; i++) {
                                totalSize += files[i].size;
                                if (files[i].size > 2 * 1024 * 1024) { // > 2MB
                                    needsCompression = true;
                                }
                            }
                            
                            const totalMB = (totalSize / 1024 / 1024).toFixed(1);
                            if (needsCompression) {
                                fileCount.textContent = `${files.length} file dipilih (${totalMB}MB total) - ada foto yang akan dikompres`;
                                fileCount.className = 'text-warning mt-1';
                            } else {
                                fileCount.textContent = `${files.length} file dipilih (${totalMB}MB total) - siap upload`;
                                fileCount.className = 'text-success mt-1';
                            }
                        }
                    } else {
                        fileCount.style.display = 'none';
                    }
                });
            }
            
            // Handle form submission with compression
            if (uploadForm) {
                uploadForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const files = fileInput.files;
                    if (files.length === 0) return;
                    
                    // Check if any file needs compression
                    let needsCompression = false;
                    for (let i = 0; i < files.length; i++) {
                        if (files[i].size > 2 * 1024 * 1024) { // > 2MB
                            needsCompression = true;
                            break;
                        }
                    }
                    
                    if (needsCompression) {
                        await compressAndUpload(files);
                    } else {
                        // Upload normally
                        this.submit();
                    }
                });
            }
        });
        
        async function compressAndUpload(files) {
            const uploadBtn = document.getElementById('uploadBtn');
            const progressDiv = document.getElementById('compressionProgress');
            const progressBar = progressDiv.querySelector('.progress-bar');
            
            uploadBtn.disabled = true;
            uploadBtn.textContent = 'Mengompres...';
            progressDiv.style.display = 'block';
            
            const formData = new FormData();
            formData.append('_token', document.querySelector('input[name="_token"]').value);
            
            let completed = 0;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                if (file.size > 2 * 1024 * 1024) { // > 2MB, compress
                    try {
                        const compressedFile = await compressImage(file);
                        formData.append('photos[]', compressedFile, file.name);
                    } catch (error) {
                        console.error('Compression failed for', file.name, error);
                        formData.append('photos[]', file, file.name); // fallback
                    }
                } else {
                    formData.append('photos[]', file, file.name);
                }
                
                completed++;
                const progress = (completed / files.length) * 100;
                progressBar.style.width = progress + '%';
            }
            
            // Submit compressed files
            try {
                const response = await fetch('{{ route("acara.photo", $acara) }}', {
                    method: 'POST',
                    body: formData
                });
                
                if (response.ok) {
                    window.location.reload();
                } else {
                    throw new Error('Upload failed');
                }
            } catch (error) {
                alert('Upload gagal: ' + error.message);
            } finally {
                uploadBtn.disabled = false;
                uploadBtn.textContent = 'Upload';
                progressDiv.style.display = 'none';
            }
        }
        
        function compressImage(file) {
            return new Promise((resolve) => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');
                const img = new Image();
                
                img.onload = function() {
                    // Calculate new dimensions (max 1920px)
                    let { width, height } = img;
                    const maxSize = 1920;
                    
                    if (width > maxSize || height > maxSize) {
                        if (width > height) {
                            height = (height * maxSize) / width;
                            width = maxSize;
                        } else {
                            width = (width * maxSize) / height;
                            height = maxSize;
                        }
                    }
                    
                    canvas.width = width;
                    canvas.height = height;
                    
                    // Draw and compress
                    ctx.drawImage(img, 0, 0, width, height);
                    
                    canvas.toBlob((blob) => {
                        resolve(new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        }));
                    }, 'image/jpeg', 0.85); // 85% quality
                };
                
                img.src = URL.createObjectURL(file);
            });
        }
        
        function viewPhoto(index) {
            currentIndex = index;
            showPhoto();
        }
        
        function showPhoto() {
            if (photos.length === 0) return;
            
            const photo = photos[currentIndex];
            document.getElementById('modalImage').src = photo.src;
            document.getElementById('modalUploader').textContent = photo.uploader;
            document.getElementById('downloadBtn').href = photo.src;
            document.getElementById('downloadBtn').download = `foto-{{ $acara->nama }}-${currentIndex + 1}.jpg`;
            
            // Update navigation buttons
            document.getElementById('prevPhoto').disabled = currentIndex === 0;
            document.getElementById('nextPhoto').disabled = currentIndex === photos.length - 1;
        }
        
        document.getElementById('prevPhoto').addEventListener('click', function() {
            if (currentIndex > 0) {
                currentIndex--;
                showPhoto();
            }
        });
        
        document.getElementById('nextPhoto').addEventListener('click', function() {
            if (currentIndex < photos.length - 1) {
                currentIndex++;
                showPhoto();
            }
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (document.getElementById('photoModal').classList.contains('show')) {
                if (e.key === 'ArrowLeft' && currentIndex > 0) {
                    currentIndex--;
                    showPhoto();
                } else if (e.key === 'ArrowRight' && currentIndex < photos.length - 1) {
                    currentIndex++;
                    showPhoto();
                } else if (e.key === 'Escape') {
                    bootstrap.Modal.getInstance(document.getElementById('photoModal')).hide();
                }
            }
        });
        
        // Click on image to view
        document.querySelectorAll('.gallery-image').forEach((img, index) => {
            img.addEventListener('click', function() {
                viewPhoto(index);
            });
        });
    </script>
</x-app-layout>