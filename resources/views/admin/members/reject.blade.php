<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-x-circle"></i> Tolak Pendaftaran Anggota
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Member Info -->
                    <div class="row mb-4">
                        <div class="col-md-3 text-center">
                            <img src="{{ asset('storage/' . $biodata->pas_foto_url) }}" 
                                 alt="Foto {{ $biodata->nama_lengkap }}" 
                                 class="img-thumbnail"
                                 style="width: 120px; height: 150px; object-fit: cover;">
                        </div>
                        <div class="col-md-9">
                            <h5>{{ $biodata->nama_lengkap }}</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Email:</strong> {{ $biodata->user->email }}</p>
                                    <p><strong>No. Telepon:</strong> {{ $biodata->no_telepon }}</p>
                                    <p><strong>Tanggal Lahir:</strong> {{ \Carbon\Carbon::parse($biodata->tanggal_lahir)->format('d F Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Jurusan:</strong> {{ $biodata->jurusan->nama_jurusan }}</p>
                                    <p><strong>Angkatan:</strong> {{ $biodata->tahun_angkatan }}</p>
                                    <p><strong>No. KTA:</strong> {{ $biodata->no_kta }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <h6><i class="bi bi-exclamation-triangle"></i> Peringatan!</h6>
                        <p class="mb-0">Tindakan ini akan menolak pendaftaran anggota dan menghapus data biodata. User masih bisa mendaftar ulang dengan data baru.</p>
                    </div>

                    <!-- Reject Form -->
                    <form action="{{ route('admin.members.reject', $biodata) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        
                        <div class="form-group mb-4">
                            <label for="reason" class="form-label">
                                <strong>Alasan Penolakan <span class="text-danger">*</span></strong>
                            </label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" 
                                      name="reason" 
                                      rows="4" 
                                      placeholder="Jelaskan alasan penolakan pendaftaran..."
                                      required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Alasan ini akan dilihat oleh calon anggota untuk memahami mengapa pendaftarannya ditolak.
                            </div>
                        </div>

                        <!-- Common Reasons -->
                        <div class="mb-4">
                            <label class="form-label"><strong>Alasan Umum (Klik untuk menggunakan):</strong></label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Dokumen tidak lengkap atau tidak sesuai persyaratan')">
                                    Dokumen Tidak Lengkap
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Foto tidak memenuhi standar yang ditentukan')">
                                    Foto Tidak Sesuai
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Data yang dimasukkan tidak valid atau tidak benar')">
                                    Data Tidak Valid
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Tidak memenuhi syarat usia atau kriteria lainnya')">
                                    Tidak Memenuhi Syarat
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Kuota anggota untuk jurusan/angkatan sudah penuh')">
                                    Kuota Penuh
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Yakin ingin menolak pendaftaran {{ $biodata->nama_lengkap }}?')">
                                <i class="bi bi-x-circle"></i> Tolak Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function setReason(reason) {
            document.getElementById('reason').value = reason;
        }
    </script>
</x-app-layout>
