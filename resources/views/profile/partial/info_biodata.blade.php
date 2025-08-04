<section id="multiple-column-form" class="">
    <div class="row match-height">
        <div class="col-12">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <form class="form" action="{{ route('biodata.update') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="nama_lengkap">Nama Lengkap</label>
                                        <input type="text" id="nama_lengkap" class="form-control bg-secondary text-white"
                                            placeholder="Nama Lengkap" name="nama_lengkap"
                                            value="{{ Auth::user()->name }}" readonly required>
                                        <small class="text-muted">Ubah nama di menu Info Akun</small>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="tanggal_lahir">Tanggal Lahir</label>
                                        <input type="date" id="tanggal_lahir"
                                            class="form-control flatpickr-no-config flatpickr-input"
                                            placeholder="Tanggal Lahir" name="tanggal_lahir"
                                            value="{{ $biodata->tanggal_lahir }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="jenis_kelamin">Jenis Kelamin</label>
                                        <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                            <option value="{{ $biodata->jenis_kelamin }}">{{ $biodata->jenis_kelamin }}
                                            </option>
                                            <option value="Laki-laki">Laki-laki</option>
                                            <option value="Perempuan">Perempuan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="no_telepon">No Telepon</label>
                                        <input type="text" id="no_telepon" class="form-control" name="no_telepon"
                                            placeholder="No Telepon" value="{{ $biodata->no_telepon }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="jurusan_id">Jurusan Kelas</label>
                                        <select class="form-select" id="jurusan_id" name="jurusan_id" required>
                                            <option value="{{ $biodata->jurusan_id }}">
                                                {{ $biodata->jurusan->nama_jurusan }}</option>
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
                                            name="tahun_angkatan" placeholder="Tahun Angkatan"
                                            value="{{ $biodata->tahun_angkatan }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="alamat">Alamat Rumah</label>
                                        <textarea id="alamat" class="form-control" name="alamat" placeholder="Alamat Rumah" rows="7" required>{{ $biodata->alamat }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12">
                                    <div class="form-group">
                                        <label for="riwayat_penyakit">Riwayat Penyakit</label>
                                        <textarea id="riwayat_penyakit" class="form-control" name="riwayat_penyakit"
                                            placeholder="Jelaskan tentang riwayat penyakit kamu termasuk obat yang dikonsumsi. beri tanda '-' jika tidak mempunyai riwayat penyakit"
                                            rows="7" required>{{ $biodata->riwayat_penyakit }}</textarea>
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
                                                    atau .png. Ukuran maksimal 2MB.
                                                    dengan latar belakang berwarna biru, mengenakan seragam
                                                    sekolah abu-abu, dan wajah terlihat jelas.
                                                </p>

                                                <!-- Preview foto lama -->
                                                @if ($biodata->pas_foto_url)
                                                    <div class="mb-3">
                                                        <img src="{{ asset('storage/' . $biodata->pas_foto_url) }}" alt="Pas Foto"
                                                            style="max-width: 200px; max-height: 200px; object-fit: contain;">
                                                    </div>
                                                @endif

                                                <hr class="mt-5">
                                                <h5>Unggah Pas Foto Baru Anda Di Sini</h5>

                                                <!-- File uploader with image preview -->
                                                <input type="file" class="image-preview-filepond" name="pas_foto"
                                                    accept="image/jpeg, image/png">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-2 border">
                                    <div class="card">
                                        <div class="card-content">
                                            <div class="card-body">
                                                <image src="{{ asset('image/contoh_foto.png') }}" class="img-fluid"
                                                    alt="Placeholder Image" style="width: 100%;">
                                                    <p class="text-center">Contoh Pas Foto</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end mt-5">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Update
                                        Biodata</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
