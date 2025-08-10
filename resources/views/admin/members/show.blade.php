<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Detail Pendaftaran Anggota</h4>
                    <div>
                        <a href="{{ route('admin.members.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Foto dan Info Dasar -->
                        <div class="col-md-4">
                            <div class="text-center">
                                <img src="{{ asset('storage/' . $biodata->pas_foto_url) }}" 
                                     alt="Foto {{ $biodata->nama_lengkap }}" 
                                     class="img-thumbnail mb-3"
                                     style="width: 200px; height: 250px; object-fit: cover;">
                                
                                <h5>{{ $biodata->nama_lengkap }}</h5>
                                <p class="text-muted">No. KTA: {{ $biodata->no_kta }}</p>
                                
                                @if($biodata->is_active)
                                    <span class="badge bg-success fs-6">Anggota Aktif</span>
                                @else
                                    <span class="badge bg-warning fs-6">Menunggu Persetujuan</span>
                                @endif
                            </div>
                            
                            <!-- Action Buttons -->
                            @if(!$biodata->is_active)
                                <div class="d-grid gap-2 mt-4">
                                    <form action="{{ route('admin.members.approve', $biodata) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('Setujui anggota {{ $biodata->nama_lengkap }}?')">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-lg w-100">
                                            <i class="bi bi-check-circle"></i> Setujui Anggota
                                        </button>
                                    </form>
                                    
                                    <a href="{{ route('admin.members.reject.form', $biodata) }}"
                                       class="btn btn-danger btn-lg w-100"
                                       title="Tolak Pendaftaran">
                                        <i class="bi bi-x-circle"></i> Tolak Pendaftaran
                                    </a>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Detail Informasi -->
                        <div class="col-md-8">
                            <div class="row">
                                <!-- Data Pribadi -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><i class="bi bi-person"></i> Data Pribadi</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Nama Lengkap:</strong></td>
                                                    <td>{{ $biodata->nama_lengkap }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tanggal Lahir:</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($biodata->tanggal_lahir)->format('d F Y') }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Umur:</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($biodata->tanggal_lahir)->age }} tahun</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Jenis Kelamin:</strong></td>
                                                    <td>
                                                        <i class="bi bi-gender-{{ $biodata->jenis_kelamin == 'Laki-laki' ? 'male' : 'female' }}"></i>
                                                        {{ $biodata->jenis_kelamin }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><strong>No. Telepon:</strong></td>
                                                    <td>
                                                        <a href="tel:{{ $biodata->no_telepon }}">{{ $biodata->no_telepon }}</a>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Data Akademik -->
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6><i class="bi bi-mortarboard"></i> Data Akademik</h6>
                                        </div>
                                        <div class="card-body">
                                            <table class="table table-sm">
                                                <tr>
                                                    <td><strong>Jurusan:</strong></td>
                                                    <td>{{ $biodata->jurusan->nama_jurusan }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>Tahun Angkatan:</strong></td>
                                                    <td>{{ $biodata->tahun_angkatan }}</td>
                                                </tr>
                                                <tr>
                                                    <td><strong>No. KTA:</strong></td>
                                                    <td><code>{{ $biodata->no_kta }}</code></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Kontak & Alamat -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6><i class="bi bi-geo-alt"></i> Informasi Kontak</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Email:</strong><br>
                                            <a href="mailto:{{ $biodata->user->email }}">{{ $biodata->user->email }}</a>
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Alamat:</strong><br>
                                            {{ $biodata->alamat }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Riwayat Kesehatan -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6><i class="bi bi-heart-pulse"></i> Riwayat Kesehatan</h6>
                                </div>
                                <div class="card-body">
                                    <p>{{ $biodata->riwayat_penyakit }}</p>
                                </div>
                            </div>
                            
                            <!-- Info Pendaftaran -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6><i class="bi bi-info-circle"></i> Informasi Pendaftaran</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Tanggal Daftar:</strong><br>
                                            {{ $biodata->created_at->format('d F Y H:i') }}
                                        </div>
                                        <div class="col-md-6">
                                            <strong>Status:</strong><br>
                                            @if($biodata->is_active)
                                                <span class="badge bg-success">Aktif</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
