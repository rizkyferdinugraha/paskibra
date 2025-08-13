<x-app-layout>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @if (auth()->user()->isSuperAdmin() && !auth()->user()->biodata)
        <div class="alert alert-info">
            <h4 class="alert-heading">Super Admin</h4>
            <p>Anda adalah Super Admin. Silahkan isi biodata terlebih dahulu untuk mengakses panel admin.</p>
        </div>
    @endif

    @if ($status == 'deactivated')
        <div class="alert alert-warning">
            <h4 class="alert-heading">Keanggotaan Dinonaktifkan</h4>
            <p>
                Akun Anda telah dinonaktifkan dan tidak dapat mengajukan keanggotaan kembali.
                @if(isset($deactivation_reason) && $deactivation_reason)
                    Alasan: <strong>{{ $deactivation_reason }}</strong>
                @endif
            </p>
            @if(isset($deactivation_admin) || isset($deactivation_when))
                <small class="text-muted">
                    @if(isset($deactivation_admin) && $deactivation_admin)
                        Oleh: {{ $deactivation_admin }}
                    @endif
                    @if(isset($deactivation_when) && $deactivation_when)
                        pada {{ \Carbon\Carbon::parse($deactivation_when)->format('d F Y H:i') }}
                    @endif
                </small>
            @endif
        </div>
    @elseif ($status == 'not_found')
        <!-- // Basic multiple Column Form section start -->
        <div class="alert alert-warning">

            <h4 class="alert-heading">Perhatian!</h4>
            <p>Silahkan isi form di bawah ini untuk melanjutkan pendaftaran anggota baru Paskibra</p>
        </div>
        <section id="multiple-column-form" class="">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header mt-3 mb-3">
                            <h4 class="card-title text-center">FORM PENGAJUAN ANGGOTA BARU</h4>
                        </div>
                        <div class="card-content">
                            <div class="card-body">
                                <form class="form" action="{{ route('biodata.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="nama_lengkap">Nama Lengkap</label>
                                                <input type="text" id="nama_lengkap" class="form-control"
                                                    placeholder="Nama Lengkap" name="nama_lengkap" value="{{ Auth::user()->name }}"
                                                    readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                                <input type="date" id="tanggal_lahir"
                                                    class="form-control flatpickr-no-config flatpickr-input"
                                                    placeholder="Tanggal Lahir" name="tanggal_lahir" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="jenis_kelamin">Jenis Kelamin</label>
                                                <select class="form-select" id="jenis_kelamin" name="jenis_kelamin"
                                                    required>
                                                    <option value="">Pilih Jenis Kelamin</option>
                                                    <option value="Laki-laki">Laki-laki</option>
                                                    <option value="Perempuan">Perempuan</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="no_telepon">No Telepon</label>
                                                <input type="text" id="no_telepon" class="form-control"
                                                    name="no_telepon" placeholder="No Telepon" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="jurusan_id">Jurusan Kelas</label>
                                                <select class="form-select" id="jurusan_id" name="jurusan_id" required>
                                                    <option value="">Pilih Jurusan Kelas</option>
                                                    @foreach ($jurusans as $jurusan)
                                                        <option value="{{ $jurusan->id }}">{{ $jurusan->nama_jurusan }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="tahun_angkatan">Tahun Angkatan</label>
                                                <input type="number" id="tahun_angkatan" class="form-control"
                                                    name="tahun_angkatan" placeholder="Tahun Angkatan" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="alamat">Alamat Rumah</label>
                                                <textarea id="alamat" class="form-control" name="alamat" placeholder="Alamat Rumah" rows="7" required></textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-12">
                                            <div class="form-group">
                                                <label for="riwayat_penyakit">Riwayat Penyakit</label>
                                                <textarea id="riwayat_penyakit" class="form-control" name="riwayat_penyakit"
                                                    placeholder="Jelaskan tentang riwayat penyakit kamu termasuk obat yang dikonsumsi. beri tanda '-' jika tidak mempunyai riwayat penyakit"
                                                    rows="7" required></textarea>
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="col border">
                                            <div class="card">
                                                <div class="card-header">
                                                    <h5 class="card-title">Pas Foto</h5>
                                                </div>
                                                <div class="card-content">
                                                    <div class="card-body">
                                                        <p class="card-text">
                                                            Silakan unggah foto diri kamu dengan format .jpg, .jpeg,
                                                            atau
                                                            .png. Ukuran maksimal 2MB.
                                                            dengan latar belakang berwarna biru, mengenakan seragam
                                                            sekolah abu-abu, dan wajah terlihat jelas.
                                                        </p>
                                                        <!-- File uploader with image preview -->
                                                        <input type="file" class="image-preview-filepond"
                                                            name="pas_foto" accept="image/jpeg, image/png" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-2 border">
                                            <div class="card">
                                                <div class="card-content">
                                                    <div class="card-body">
                                                        <image src="{{ asset('image/contoh_foto.png') }}"
                                                            class="img-fluid" alt="Placeholder Image"
                                                            style="width: 100%;">
                                                            <p class="text-center">Contoh Pas Foto</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 d-flex justify-content-end mt-5">
                                            <button type="submit" class="btn btn-primary me-1 mb-1">Kirim
                                                Data</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- // Basic multiple Column Form section end -->
    @elseif ($status == 'pending')
        <div class="alert alert-success text-center" role="alert">
            <strong>Pengajuan Anda Masih Dalam Proses Verifikasi!</strong> Silakan tunggu konfirmasi dari admin.
        </div>
        <section id="multiple-column-form" class="">
            <div class="row match-height">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                Informasi Formulir Pendaftaran
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <div class="card">
                                                            <div class="card-content">
                                                                <div class="card-body">
                                                                    <image
                                                                        src="{{ asset('storage/' . $biodata->pas_foto_url) }}"
                                                                        class="img-fluid" alt="Pas Foto"
                                                                        style="width: 100%;">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="nama_lengkap">Nama Lengkap</label>
                                                            <input type="text" id="nama_lengkap"
                                                                class="form-control" placeholder="Nama Lengkap"
                                                                name="nama_lengkap"
                                                                value="{{ Auth::user()->name }}" readonly disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tanggal_lahir">Tanggal Lahir</label>
                                                            <input type="date" id="tanggal_lahir"
                                                                class="form-control flatpickr-no-config flatpickr-input"
                                                                placeholder="Tanggal Lahir" name="tanggal_lahir"
                                                                value="{{ $biodata->tanggal_lahir }}" readonly
                                                                disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jenis_kelamin">Jenis Kelamin</label>
                                                            <input type="text" id="jenis_kelamin"
                                                                class="form-control" name="jenis_kelamin"
                                                                placeholder="Jenis Kelamin"
                                                                value="{{ $biodata->jenis_kelamin }}" readonly
                                                                disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="no_telepon">No Telepon</label>
                                                            <input type="text" id="no_telepon"
                                                                class="form-control" name="no_telepon"
                                                                placeholder="No Telepon"
                                                                value="{{ $biodata->no_telepon }}" readonly disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="jurusan_id">Jurusan Kelas</label>
                                                            <input type="text" id="jurusan_id"
                                                                class="form-control" name="jurusan_id"
                                                                placeholder="Jurusan Kelas"
                                                                value="{{ $jurusan }}" readonly disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="tahun_angkatan">Tahun Angkatan</label>
                                                            <input type="number" id="tahun_angkatan"
                                                                class="form-control" name="tahun_angkatan"
                                                                placeholder="Tahun Angkatan"
                                                                value="{{ $biodata->tahun_angkatan }}" readonly
                                                                disabled>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="alamat">Alamat Rumah</label>
                                                            <textarea id="alamat" class="form-control" name="alamat" placeholder="Alamat Rumah" rows="7" readonly
                                                                disabled>{{ $biodata->alamat }}</textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="riwayat_penyakit">Riwayat Penyakit</label>
                                                            <textarea id="riwayat_penyakit" class="form-control" name="riwayat_penyakit"
                                                                placeholder="Jelaskan tentang riwayat penyakit kamu termasuk obat yang dikonsumsi. beri tanda '-' jika tidak mempunyai riwayat penyakit"
                                                                rows="7" readonly disabled>{{ $biodata->riwayat_penyakit }}</textarea>
                                                        </div>
                                                    </div>
                                                    <p class="text-center mt-3 fs-4 alert alert-info">Untuk ubah data, silahkan klik <a href="{{ route('profile.edit') }}">disini</a></p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                Tentang Paskibra
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <iframe src="{{ asset('pdf_file/paskibra_info.pdf') }}"
                                                    frameborder="0" allowfullscreen
                                                    style="width: 100%; height: 800px;"></iframe>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingThree">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                aria-expanded="false" aria-controls="collapseThree">
                                                Kartu tanda anggota
                                            </button>
                                        </h2>
                                        <div id="collapseThree" class="accordion-collapse collapse"
                                            aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                            <iframe src="{{ route('template.kta') }}" frameborder="0" allowfullscreen
                                                style="width: 100%; height: 400px;"></iframe>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @else
        @php
            $today = now()->startOfDay();
            $tomorrow = now()->addDay()->startOfDay();
            
            // Tandai otomatis selesai jika melewati waktu_selesai
            \App\Models\Acara::whereNotNull('waktu_selesai')
                ->where('selesai', false)
                ->where('waktu_selesai', '<=', now())
                ->update(['selesai' => true]);

            // Acara hari ini (belum selesai)
            $ongoing = \App\Models\Acara::where('selesai', false)
                ->whereDate('tanggal', $today)
                ->orderBy('tanggal')
                ->get();
            
            // Acara yang akan datang (belum selesai)
            $upcoming = \App\Models\Acara::where('selesai', false)
                ->whereDate('tanggal', '>=', $tomorrow)
                ->orderBy('tanggal')
                ->take(6)
                ->get();
            
            // Acara yang sudah selesai (diarsipkan) - ambil yang terbaru
            $archived = \App\Models\Acara::where('selesai', true)
                ->orderBy('tanggal', 'desc')
                ->take(6)
                ->get();
                
            // Debug: Log jumlah acara untuk memastikan query bekerja
            \Log::info('Dashboard Acara Count', [
                'ongoing' => $ongoing->count(),
                'upcoming' => $upcoming->count(),
                'archived' => $archived->count()
            ]);
        @endphp
        <!-- Tabs: Acara & Statistik -->
        <div class="mb-3">
            <h2 class="fw-bold mb-1" style="letter-spacing:0.2px;">Dashboard</h2>
            <div class="text-muted">Ringkasan kegiatan dan statistik</div>
        </div>
        <div class="dashboard-tabs-container mb-4">
            @php($isSenior = auth()->user()->role && strcasecmp(auth()->user()->role->nama_role, 'Senior') === 0)
            <ul class="nav nav-pills nav-fill dashboard-tabs" id="dashboardTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active dashboard-tab-item" id="tab-acara-tab" data-bs-toggle="tab" data-bs-target="#tab-acara" type="button" role="tab" aria-controls="tab-acara" aria-selected="true">
                        <div class="tab-content-wrapper">
                            <span class="tab-text">Acara & Kegiatan</span>
                            <div class="tab-indicator"></div>
                        </div>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link dashboard-tab-item" id="tab-statistik-tab" data-bs-toggle="tab" data-bs-target="#tab-statistik" type="button" role="tab" aria-controls="tab-statistik" aria-selected="false">
                        <div class="tab-content-wrapper">
                            <span class="tab-text">Statistik & Progress</span>
                            <div class="tab-indicator"></div>
                        </div>
                    </button>
                </li>
                @if($isSenior)
                <li class="nav-item" role="presentation">
                    <button class="nav-link dashboard-tab-item" id="tab-komplain-tab" data-bs-toggle="tab" data-bs-target="#tab-komplain" type="button" role="tab" aria-controls="tab-komplain" aria-selected="false">
                        <div class="tab-content-wrapper">
                            <span class="tab-text">Riwayat Komplain</span>
                            <div class="tab-indicator"></div>
                        </div>
                    </button>
                </li>
                @endif
            </ul>
        </div>

        

        
        
        

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.dashboard-tab-item');
            const tabContainer = document.querySelector('.dashboard-tabs-container');
            
            // Calendar size consistency fix
            function maintainCalendarSize() {
                const calendarRoot = document.getElementById('calendar-root');
                if (calendarRoot) {
                    calendarRoot.style.minHeight = '600px';
                }
            }
            
            // Add click sound effect (optional)
            function playTabClickSound() {
                // Create a subtle click sound
                const audioContext = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioContext.createOscillator();
                const gainNode = audioContext.createGain();
                
                oscillator.connect(gainNode);
                gainNode.connect(audioContext.destination);
                
                oscillator.frequency.setValueAtTime(800, audioContext.currentTime);
                oscillator.frequency.exponentialRampToValueAtTime(600, audioContext.currentTime + 0.1);
                
                gainNode.gain.setValueAtTime(0.1, audioContext.currentTime);
                gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.1);
                
                oscillator.start(audioContext.currentTime);
                oscillator.stop(audioContext.currentTime + 0.1);
            }
            
            // Enhanced tab switching with animations
            tabButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    // Add loading state
                    tabContainer.classList.add('loading');
                    
                    // Play sound effect
                    try {
                        playTabClickSound();
                    } catch (error) {
                        // Ignore if audio context is not supported
                    }
                    
                    // Remove loading state after animation
                    setTimeout(() => {
                        tabContainer.classList.remove('loading');
                    }, 300);
                    
                    // Add ripple effect
                    const ripple = document.createElement('div');
                    ripple.style.position = 'absolute';
                    ripple.style.borderRadius = '50%';
                    ripple.style.background = 'rgba(255, 255, 255, 0.3)';
                    ripple.style.transform = 'scale(0)';
                    ripple.style.animation = 'ripple 0.6s linear';
                    ripple.style.left = e.offsetX + 'px';
                    ripple.style.top = e.offsetY + 'px';
                    ripple.style.width = ripple.style.height = '20px';
                    
                    this.appendChild(ripple);
                    
                    setTimeout(() => {
                        ripple.remove();
                    }, 600);
                });
            });
            
            // Add ripple animation
            const style = document.createElement('style');
            style.textContent = `
                @keyframes ripple {
                    to {
                        transform: scale(4);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
            
            // Auto-hide loading state if something goes wrong
            setTimeout(() => {
                tabContainer.classList.remove('loading');
            }, 2000);
            
            // Maintain calendar size on window resize
            window.addEventListener('resize', maintainCalendarSize);
            
            // Initial calendar size maintenance
            maintainCalendarSize();
            
            // Monitor calendar content changes
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'childList') {
                        setTimeout(maintainCalendarSize, 100);
                    }
                });
            });
            
            const calendarRoot = document.getElementById('calendar-root');
            if (calendarRoot) {
                observer.observe(calendarRoot, {
                    childList: true,
                    subtree: true
                });
            }
        });
        </script>

        <div class="tab-content mt-3" id="dashboardTabsContent">
            <div class="tab-pane fade show active" id="tab-acara" role="tabpanel" aria-labelledby="tab-acara-tab">
                <div class="tab-content-wrapper">

        @if($ongoing->isNotEmpty())
            <div class="page-heading mt-4">
                <h4><i class="bi bi-clock-history text-warning"></i> Sedang Berlangsung Hari Ini</h4>
            </div>
            <div class="row">
                @foreach($ongoing as $acara)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card border-warning h-100 event-card ongoing">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $acara->nama }}</h5>
                                    @if($acara->wajibHadir->contains('id', auth()->id()))
                                        <span class="badge bg-danger pulse-badge">Wajib Hadir!</span>
                                    @else
                                        <span class="badge bg-warning">Sedang Berlangsung</span>
                                    @endif
                                </div>
                                 <p class="card-text mb-1">
                                     @php($mulai = $acara->waktu_mulai ?? \Carbon\Carbon::parse($acara->tanggal))
                                     @php($selesai = $acara->waktu_selesai)
                                     <i class="bi bi-clock"></i> {{ $mulai->format('H:i') }}@if($selesai) - {{ $selesai->format('H:i') }}@endif
                                 </p>
                                <p class="card-text mb-2"><i class="bi bi-geo-alt"></i> {{ $acara->lokasi }}</p>
                                <p class="card-text small text-muted">
                                    <i class="bi bi-person-badge"></i> {{ $acara->seragam }}
                                </p>
                                <a href="{{ route('acara.show', $acara) }}" class="btn btn-warning btn-sm event-btn">
                                    <i class="bi bi-eye-fill me-1"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($upcoming->isNotEmpty())
            <div class="page-heading mt-4">
                <h4><i class="bi bi-calendar-event text-primary"></i> Acara Yang Akan Datang</h4>
            </div>
            <div class="row">
                @foreach($upcoming as $acara)
                    <div class="col-md-4 col-sm-6 mb-3">
                        <div class="card h-100 event-card upcoming">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $acara->nama }}</h5>
                                    @if($acara->wajibHadir->contains('id', auth()->id()))
                                        <span class="badge bg-danger pulse-badge">Wajib Hadir!</span>
                                    @else
                                        <span class="badge bg-primary">Akan Datang</span>
                                    @endif
                                </div>
                                 <p class="card-text mb-1">
                                     @php($mulai = $acara->waktu_mulai ?? \Carbon\Carbon::parse($acara->tanggal))
                                     @php($selesai = $acara->waktu_selesai)
                                     <i class="bi bi-calendar"></i> {{ $mulai->format('d/m/Y H:i') }}@if($selesai) - {{ $selesai->format('H:i') }}@endif
                                 </p>
                                <p class="card-text mb-2"><i class="bi bi-geo-alt"></i> {{ $acara->lokasi }}</p>
                                <p class="card-text small text-muted">
                                    <i class="bi bi-person-badge"></i> {{ $acara->seragam }}
                                </p>
                                <a href="{{ route('acara.show', $acara) }}" class="btn btn-outline-primary btn-sm event-btn">
                                    <i class="bi bi-info-circle-fill me-1"></i>Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($archived->isNotEmpty())
            <div class="page-heading mt-4">
                <h4>
                    <i class="bi bi-archive text-muted"></i> Arsip Acara (Sudah Selesai)
                    <button class="btn btn-sm btn-outline-secondary archive-toggle" type="button" data-bs-toggle="collapse" data-bs-target="#arsipAcara">
                        <i class="bi bi-chevron-down me-1"></i>Tampilkan/Sembunyikan
                    </button>
                </h4>
            </div>
            <div class="collapse" id="arsipAcara">
                <div class="row">
                    @foreach($archived as $acara)
                        <div class="col-md-4 col-sm-6 mb-3">
                            <div class="card h-100 event-card archived">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0 text-muted">{{ $acara->nama }}</h5>
                                        <span class="badge bg-secondary">Selesai</span>
                                    </div>
                                     <p class="card-text mb-1 text-muted">
                                         @php($mulai = $acara->waktu_mulai ?? \Carbon\Carbon::parse($acara->tanggal))
                                         @php($selesai = $acara->waktu_selesai)
                                         <i class="bi bi-calendar-check"></i> {{ $mulai->format('d/m/Y H:i') }}@if($selesai) - {{ $selesai->format('H:i') }}@endif
                                     </p>
                                    <p class="card-text mb-2 text-muted"><i class="bi bi-geo-alt"></i> {{ $acara->lokasi }}</p>
                                    <p class="card-text small text-muted">
                                        <i class="bi bi-person-badge"></i> {{ $acara->seragam }}
                                    </p>
                                    <div class="mt-3">
                                        <a href="{{ route('acara.show', $acara) }}" class="btn btn-outline-secondary btn-sm event-btn">
                                            <i class="bi bi-images me-1"></i>Lihat Dokumentasi
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($ongoing->isEmpty() && $upcoming->isEmpty() && $archived->isEmpty())
            <div class="empty-state text-center text-muted mt-4">
                <div class="empty-icon mb-3">
                    <i class="bi bi-calendar-x display-1 text-muted"></i>
                </div>
                <h5 class="empty-title">Belum ada acara yang dijadwalkan</h5>
                <p class="empty-text">Acara akan muncul di sini setelah dijadwalkan oleh admin.</p>
            </div>
        @endif

                    <!-- Kalender Acara (dipindah ke bawah daftar acara) -->
                    <div class="card mt-4 mb-2">
                        <div class="card-body">
                            <div id="calendar-root">
                                @include('components.calendar', [
                                    'events' => $calendarEvents ?? collect(),
                                    'currentMonth' => $currentMonth ?? now()->month,
                                    'currentYear' => $currentYear ?? now()->year,
                                    'withAssets' => true,
                                ])
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="tab-pane fade" id="tab-statistik" role="tabpanel" aria-labelledby="tab-statistik-tab">
                <div class="tab-content-wrapper">
                    @if(isset($showMembersSummary) && $showMembersSummary)
                        <div class="welcome-section mb-4">
                            <div class="row">
                                <div class="col-12">
                                    <div class="welcome-card stats-welcome">
                                        <div class="welcome-content">
                                            <h3 class="welcome-title" style="line-height:1.2;">
                                                <i class="bi bi-people-fill text-primary me-2"></i>
                                                Ringkasan Statistik Anggota (CJ & Junior)
                                            </h3>
                                            <p class="welcome-text" style="line-height:1.5;">
                                                Anda melihat ringkasan performa dan kehadiran untuk anggota dengan role Calon Junior dan Junior.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3">
                            @forelse(($membersSummary ?? collect()) as $m)
                                <div class="col-lg-4 col-md-6 col-12">
                                    <div class="card stat-card h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <div>
                                                    <h6 class="mb-0">{{ $m['name'] }}</h6>
                                                    <small class="text-muted">{{ $m['role'] }}</small>
                                                </div>
                                                <div class="stat-icon-wrapper">
                                                    <i class="bi bi-person-check-fill text-primary"></i>
                                                </div>
                                            </div>
                                            <div class="row g-2 mb-2">
                                                <div class="col-12">
                                                    <small class="text-muted">Nilai Keseluruhan</small>
                                                    <div class="stat-number">{{ $m['avg_overall'] ? number_format($m['avg_overall'], 2) : '-' }}/5</div>
                                                </div>
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-12">
                                                    <small class="text-muted">Rincian Nilai (1–5)</small>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <span class="badge bg-light text-dark">Fisik: {{ $m['avg']['avg_fisik'] ? number_format($m['avg']['avg_fisik'], 2) : '-' }}</span>
                                                        <span class="badge bg-light text-dark">Kepedulian: {{ $m['avg']['avg_kepedulian'] ? number_format($m['avg']['avg_kepedulian'], 2) : '-' }}</span>
                                                        <span class="badge bg-light text-dark">Tanggung Jawab: {{ $m['avg']['avg_tanggung_jawab'] ? number_format($m['avg']['avg_tanggung_jawab'], 2) : '-' }}</span>
                                                        <span class="badge bg-light text-dark">Disiplin: {{ $m['avg']['avg_disiplin'] ? number_format($m['avg']['avg_disiplin'], 2) : '-' }}</span>
                                                        <span class="badge bg-light text-dark">Kerjasama: {{ $m['avg']['avg_kerjasama'] ? number_format($m['avg']['avg_kerjasama'], 2) : '-' }}</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>

                                            <div class="row g-2">
                                                <div class="col-md-6 col-12">
                                                    <small class="text-muted">Kehadiran Total</small>
                                                    <div class="stat-number">
                                                        @if($m['attendance_overall']['percent'] !== null)
                                                            {{ $m['attendance_overall']['present'] }}/{{ $m['attendance_overall']['total'] }} ({{ $m['attendance_overall']['percent'] }}%)
                                                        @else - @endif
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-12">
                                                    <small class="text-muted">Kehadiran Bulan Ini</small>
                                                    <div class="stat-number">
                                                        @if($m['attendance_month']['percent'] !== null)
                                                            {{ $m['attendance_month']['present'] }}/{{ $m['attendance_month']['total'] }} ({{ $m['attendance_month']['percent'] }}%)
                                                        @else - @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-muted text-center">Belum ada data anggota.</div>
                                </div>
                            @endforelse
                        </div>
                    @endif
                    @if(!(isset($showMembersSummary) && $showMembersSummary))
                    <!-- Welcome Message for Stats (hanya untuk CJ/Junior) -->
                    <div class="welcome-section mb-4">
                        <div class="row">
                            <div class="col-12">
                                <div class="welcome-card stats-welcome">
                                    <div class="welcome-content">
                                        <h3 class="welcome-title" style="line-height:1.2;">
                                            <i class="bi bi-graph-up-arrow text-success me-2"></i>
                                            Statistik & Progress Keanggotaan
                                        </h3>
                                         <p class="welcome-text" style="line-height:1.5;">
                                             Pantau performa dan progress kehadiran Anda dalam kegiatan Paskibra.
                                         </p>
                                    </div>
                                    <div class="welcome-decoration">
                                        <div class="floating-icon stats-icon">
                                            <i class="bi bi-trophy-fill"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Radar Chart dipindah ke bawah statistik angka -->

                <div class="row g-3">
                    <div class="col-md-3 col-6">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="stat-icon-wrapper mb-2">
                                    <i class="bi bi-activity text-warning"></i>
                                </div>
                                <small class="text-muted">Rata-rata Keseluruhan</small>
                                <h3 class="mt-2 mb-0 stat-number">
                                    {{ isset($userStats['avg_overall']) ? number_format($userStats['avg_overall'], 2) : '-' }}/5
                                </h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="stat-icon-wrapper mb-2">
                                    <i class="bi bi-heart-pulse-fill text-danger"></i>
                                </div>
                                <small class="text-muted">Fisik</small>
                                <h3 class="mt-2 mb-0 stat-number">{{ isset($userStats['avg_fisik']) ? number_format($userStats['avg_fisik'], 2) : '-' }}/5</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="stat-icon-wrapper mb-2">
                                    <i class="bi bi-hand-thumbs-up-fill text-info"></i>
                                </div>
                                <small class="text-muted">Kepedulian</small>
                                <h3 class="mt-2 mb-0 stat-number">{{ isset($userStats['avg_kepedulian']) ? number_format($userStats['avg_kepedulian'], 2) : '-' }}/5</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="stat-icon-wrapper mb-2">
                                    <i class="bi bi-shield-check text-success"></i>
                                </div>
                                <small class="text-muted">Tanggung Jawab</small>
                                <h3 class="mt-2 mb-0 stat-number">{{ isset($userStats['avg_tanggung_jawab']) ? number_format($userStats['avg_tanggung_jawab'], 2) : '-' }}/5</h3>
                            </div>
                        </div>
                    </div>
                </div>

                    <div class="row g-3 mt-1">
                    <div class="col-md-3 col-6">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="stat-icon-wrapper mb-2">
                                    <i class="bi bi-exclamation-diamond-fill text-primary"></i>
                                </div>
                                <small class="text-muted">Disiplin</small>
                                <h3 class="mt-2 mb-0 stat-number">{{ isset($userStats['avg_disiplin']) ? number_format($userStats['avg_disiplin'], 2) : '-' }}/5</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="stat-icon-wrapper mb-2">
                                    <i class="bi bi-diagram-3-fill text-secondary"></i>
                                </div>
                                <small class="text-muted">Kerjasama</small>
                                <h3 class="mt-2 mb-0 stat-number">{{ isset($userStats['avg_kerjasama']) ? number_format($userStats['avg_kerjasama'], 2) : '-' }}/5</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="stat-icon-wrapper mb-2">
                                    <i class="bi bi-flag-fill text-danger"></i>
                                </div>
                                <small class="text-muted">Komplain Selesai</small>
                                <h3 class="mt-2 mb-0 stat-number">{{ (int)($complaintsCount ?? 0) }}</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 col-6">
                        <div class="card h-100 stat-card">
                            <div class="card-body">
                                <div class="stat-icon-wrapper mb-2">
                                    <i class="bi bi-journal-check text-success"></i>
                                </div>
                                <small class="text-muted">Kehadiran Wajib (total)</small>
                                <h3 class="mt-2 mb-0 stat-number">
                                    @if(isset($attendanceStats['overall_percent']))
                                        {{ $attendanceStats['overall_present'] }}/{{ $attendanceStats['overall_total'] }} ({{ $attendanceStats['overall_percent'] }}%)
                                    @else
                                        -
                                    @endif
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Radar Chart: Ringkasan Penilaian (dipindah ke bawah angka) -->
                <div class="card mb-3 stat-card mt-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0" style="font-size:1.05rem;">
                                <i class="bi bi-bezier2 text-primary me-2"></i>
                                Radar Penilaian (1–5)
                            </h6>
                            <small class="text-muted">Data dari rata-rata penilaian</small>
                        </div>
                        <div id="radarStatsChart" style="height: 280px;"></div>
                    </div>
                </div>

                <div class="card mt-3 progress-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">
                                <i class="bi bi-graph-up text-success me-2"></i>
                                Progress Kehadiran Bulanan
                            </h6>
                            <small class="text-muted">
                                @if(isset($attendanceStats['percent']) && $attendanceStats['percent'] !== null)
                                    {{ $attendanceStats['percent'] }}%
                                @else
                                    -
                                @endif
                            </small>
                        </div>
                        @php($p = $attendanceStats['percent'] ?? 0)
                        @php($barClass = $p >= 95 ? 'bg-success' : ($p >= 90 ? 'bg-warning' : 'bg-danger'))
                        <div class="progress custom-progress" style="height: 12px;">
                            <div class="progress-bar {{ $barClass }}" role="progressbar" style="width: {{ $p }}%;" aria-valuenow="{{ $p }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
                @endif

                @if(!(isset($showMembersSummary) && $showMembersSummary))
                <script>
                    (function() {
                        var radarChartInstance = null;
                        function getSeries() {
                            return [{
                                name: 'Rata-rata',
                                data: [
                                    {{ isset($userStats['avg_fisik']) ? number_format($userStats['avg_fisik'], 2, '.', '') : '0' }},
                                    {{ isset($userStats['avg_kepedulian']) ? number_format($userStats['avg_kepedulian'], 2, '.', '') : '0' }},
                                    {{ isset($userStats['avg_tanggung_jawab']) ? number_format($userStats['avg_tanggung_jawab'], 2, '.', '') : '0' }},
                                    {{ isset($userStats['avg_disiplin']) ? number_format($userStats['avg_disiplin'], 2, '.', '') : '0' }},
                                    {{ isset($userStats['avg_kerjasama']) ? number_format($userStats['avg_kerjasama'], 2, '.', '') : '0' }}
                                ]
                            }];
                        }

                        function getOptions() {
                            return {
                                chart: { type: 'radar', toolbar: { show: false } },
                                series: getSeries(),
                                labels: ['Fisik', 'Kepedulian', 'Tanggung Jawab', 'Disiplin', 'Kerjasama'],
                                dataLabels: { enabled: false },
                                xaxis: {
                                    labels: {
                                        show: true,
                                        style: { fontSize: '14px', fontWeight: 600 }
                                    }
                                },
                                yaxis: {
                                    min: 0,
                                    max: 5,
                                    tickAmount: 5,
                                    labels: { show: true, style: { fontSize: '12px' } }
                                },
                                stroke: { width: 2 },
                                fill: { opacity: 0.2 },
                                colors: ['#3b82f6'],
                                markers: { size: 3 },
                                legend: { show: false }
                            };
                        }

                        function mountRadarChart() {
                            var el = document.getElementById('radarStatsChart');
                            if (!el || typeof ApexCharts === 'undefined') return;
                            if (radarChartInstance) { radarChartInstance.destroy(); }
                            var options = getOptions();
                            radarChartInstance = new ApexCharts(el, options);
                            radarChartInstance.render();
                        }

                        function whenTabShownMountChart() {
                            var tabTrigger = document.getElementById('tab-statistik-tab');
                            if (!tabTrigger) { mountRadarChart(); return; }
                            tabTrigger.addEventListener('shown.bs.tab', function() {
                                setTimeout(mountRadarChart, 50);
                            });
                            var pane = document.getElementById('tab-statistik');
                            if (pane && pane.classList.contains('active')) {
                                setTimeout(mountRadarChart, 50);
                            }
                        }

                        document.addEventListener('DOMContentLoaded', whenTabShownMountChart);
                    })();
                </script>

                <div class="card mt-3 history-card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-clock-history text-primary me-2"></i>
                            Riwayat Terakhir
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 custom-table">
                                <thead>
                                    <tr>
                                        <th><i class="bi bi-calendar-event me-1"></i>Acara Wajib</th>
                                        <th><i class="bi bi-calendar-date me-1"></i>Tanggal</th>
                                        <th><i class="bi bi-check-circle me-1"></i>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($attendanceHistory ?? []) as $r)
                                        <tr>
                                            <td>{{ $r['nama'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($r['tanggal'])->format('d/m/Y H:i') }}</td>
                                            <td>
                                                @if($r['hadir'])
                                                    <span class="badge bg-success status-badge">Hadir</span>
                                                @else
                                                    <span class="badge bg-danger status-badge">Absen</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">Belum ada riwayat</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                @php($isSenior = auth()->user()->role && strcasecmp(auth()->user()->role->nama_role, 'Senior') === 0)
                @if(!$isSenior)
                    <div class="card mt-3 history-card">
                        <div class="card-header">
                            <h6 class="mb-0">
                                <i class="bi bi-flag-fill text-danger me-2"></i>
                                Riwayat Komplain (Selesai)
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0 custom-table">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse(($complaintsHistory ?? collect()) as $rc)
                                            <tr>
                                                <td>{{ $rc->judul }}</td>
                                                <td>{{ \Carbon\Carbon::parse($rc->created_at)->format('d/m/Y H:i') }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="text-center text-muted">Belum ada komplain selesai</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
                @endif
                </div>
            </div>
        </div>
        @if($isSenior)
        <div class="tab-pane fade" id="tab-komplain" role="tabpanel" aria-labelledby="tab-komplain-tab">
            <div class="tab-content-wrapper">
                <div class="card mt-1 history-card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="bi bi-flag-fill text-danger me-2"></i>
                            Riwayat Komplain (Selesai)
                        </h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table mb-0 custom-table">
                                <thead>
                                    <tr>
                                        <th>Judul</th>
                                        <th>Ringkasan</th>
                                        <th>Tanggal</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse(($complaintsHistory ?? collect()) as $rc)
                                        <tr>
                                            <td>{{ $rc->judul }}</td>
                                            <td>
                                                <details>
                                                    <summary>Lihat ringkasan</summary>
                                                    <div class="small text-muted mt-1">{{ \Illuminate\Support\Str::limit($rc->deskripsi, 180) }}</div>
                                                    @if($rc->follow_up)
                                                        <div class="small text-success mt-1">Tindak Lanjut: {{ \Illuminate\Support\Str::limit($rc->follow_up, 180) }}</div>
                                                    @endif
                                                </details>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($rc->created_at)->format('d/m/Y H:i') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('complaints.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada komplain selesai</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif

</x-app-layout>