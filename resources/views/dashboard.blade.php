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
        <div class="alert alert-success text-center" role="alert"><strong>Selamat Datang,
                {{ Auth::user()->name }}!</strong> Anda telah terdaftar sebagai anggota Paskibra.</div>

        @php
            $today = now()->startOfDay();
            $tomorrow = now()->addDay()->startOfDay();
            
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
            
            // Acara yang sudah selesai (diarsipkan)
            $archived = \App\Models\Acara::where('selesai', true)
                ->orderBy('tanggal', 'desc')
                ->take(6)
                ->get();
        @endphp

        @if($ongoing->isNotEmpty())
            <div class="page-heading mt-4">
                <h4><i class="bi bi-clock-history text-warning"></i> Sedang Berlangsung</h4>
            </div>
            <div class="row">
                @foreach($ongoing as $acara)
                    <div class="col-md-4 col-sm-6">
                        <div class="card border-warning h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $acara->nama }}</h5>
                                    @if($acara->wajibHadir->contains('id', auth()->id()))
                                        <span class="badge bg-danger">Wajib Hadir!</span>
                                    @endif
                                </div>
                                <p class="card-text mb-1">
                                    <i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($acara->tanggal)->format('H:i') }}
                                </p>
                                <p class="card-text mb-2"><i class="bi bi-geo-alt"></i> {{ $acara->lokasi }}</p>
                                <p class="card-text small text-muted">
                                    <i class="bi bi-person-badge"></i> {{ $acara->seragam }}
                                </p>
                                <a href="{{ route('acara.show', $acara) }}" class="btn btn-warning btn-sm">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($upcoming->isNotEmpty())
            <div class="page-heading mt-4">
                <h4><i class="bi bi-calendar-event text-primary"></i> Akan Datang</h4>
            </div>
            <div class="row">
                @foreach($upcoming as $acara)
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0">{{ $acara->nama }}</h5>
                                    @if($acara->wajibHadir->contains('id', auth()->id()))
                                        <span class="badge bg-danger">Wajib Hadir!</span>
                                    @endif
                                </div>
                                <p class="card-text mb-1">
                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($acara->tanggal)->format('d/m/Y H:i') }}
                                </p>
                                <p class="card-text mb-2"><i class="bi bi-geo-alt"></i> {{ $acara->lokasi }}</p>
                                <p class="card-text small text-muted">
                                    <i class="bi bi-person-badge"></i> {{ $acara->seragam }}
                                </p>
                                <a href="{{ route('acara.show', $acara) }}" class="btn btn-outline-primary btn-sm">Detail</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($archived->isNotEmpty())
            <div class="page-heading mt-4">
                <h4>
                    <i class="bi bi-archive text-success"></i> Arsip Acara
                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#arsipAcara">
                        Tampilkan/Sembunyikan
                    </button>
                </h4>
            </div>
            <div class="collapse" id="arsipAcara">
                <div class="row">
                    @foreach($archived as $acara)
                        <div class="col-md-4 col-sm-6">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h5 class="card-title mb-0">{{ $acara->nama }}</h5>
                                        @if($acara->wajibHadir->contains('id', auth()->id()))
                                            <span class="badge bg-success">Kamu Wajib Hadir</span>
                                        @endif
                                    </div>
                                    <p class="card-text mb-1">
                                        <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($acara->tanggal)->format('d/m/Y H:i') }}
                                    </p>
                                    <p class="card-text mb-2"><i class="bi bi-geo-alt"></i> {{ $acara->lokasi }}</p>
                                    <span class="badge bg-success mb-2">Selesai</span>
                                    <div>
                                        <a href="{{ route('acara.show', $acara) }}" class="btn btn-outline-success btn-sm">Lihat Dokumentasi</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($ongoing->isEmpty() && $upcoming->isEmpty() && $archived->isEmpty())
            <div class="text-center text-muted mt-4">
                <em>Belum ada acara yang dijadwalkan</em>
            </div>
        @endif
    @endif

</x-app-layout>