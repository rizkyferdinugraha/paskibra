<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-person-dash"></i> Nonaktifkan Anggota
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
                            <h5>{{ $biodata->nama_lengkap }}
                                @if($biodata->user->super_admin)
                                    <span class="badge bg-danger">Super Admin</span>
                                @elseif($biodata->user->is_admin)
                                    <span class="badge bg-warning">Admin</span>
                                @endif
                            </h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Email:</strong> {{ $biodata->user->email }}</p>
                                    <p><strong>No. Telepon:</strong> {{ $biodata->no_telepon }}</p>
                                    <p><strong>Status:</strong> 
                                        <span class="badge bg-success">Anggota Aktif</span>
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Jurusan:</strong> {{ $biodata->jurusan->nama_jurusan }}</p>
                                    <p><strong>Angkatan:</strong> {{ $biodata->tahun_angkatan }}</p>
                                    <p><strong>Bergabung:</strong> {{ $biodata->created_at->format('d F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <h6><i class="bi bi-info-circle"></i> Informasi</h6>
                        <p class="mb-0">Tindakan ini akan menonaktifkan keanggotaan. Anggota masih bisa login tetapi tidak dapat mengakses fitur khusus anggota aktif.</p>
                    </div>

                    <!-- Deactivate Form -->
                    <form action="{{ route('admin.members.deactivate', $biodata) }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-4">
                            <label for="reason" class="form-label">
                                <strong>Alasan Penonaktifan <span class="text-danger">*</span></strong>
                            </label>
                            <textarea class="form-control @error('reason') is-invalid @enderror" 
                                      id="reason" 
                                      name="reason" 
                                      rows="4" 
                                      placeholder="Jelaskan alasan penonaktifan keanggotaan..."
                                      required>{{ old('reason') }}</textarea>
                            @error('reason')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Alasan ini akan dilihat oleh anggota untuk memahami mengapa keanggotaannya dinonaktifkan.
                            </div>
                        </div>

                        <!-- Common Reasons -->
                        <div class="mb-4">
                            <label class="form-label"><strong>Alasan Umum (Klik untuk menggunakan):</strong></label>
                            <div class="d-flex flex-wrap gap-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Tidak aktif dalam kegiatan organisasi dalam jangka waktu lama')">
                                    Tidak Aktif dalam Kegiatan
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Melanggar peraturan dan tata tertib organisasi')">
                                    Melanggar Peraturan
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Permintaan sendiri untuk berhenti dari keanggotaan')">
                                    Permintaan Sendiri
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Pindah sekolah atau lulus dari institusi')">
                                    Pindah/Lulus
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="setReason('Tidak memenuhi kewajiban sebagai anggota')">
                                    Tidak Memenuhi Kewajiban
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.members.active') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Batal
                            </a>
                            <button type="submit" class="btn btn-warning" onclick="return confirm('Yakin ingin menonaktifkan {{ $biodata->nama_lengkap }}?')">
                                <i class="bi bi-person-dash"></i> Nonaktifkan Anggota
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
