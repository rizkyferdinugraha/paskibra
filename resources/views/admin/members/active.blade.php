<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        <i class="bi bi-people"></i> Anggota Aktif
                        <span class="badge bg-success">{{ $activeMembers->total() }} Aktif</span>
                    </h4>
                    <div>
                        <a href="{{ route('admin.members.index') }}" class="btn btn-warning">
                            <i class="bi bi-person-plus"></i> Pending Approval
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($activeMembers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Foto</th>
                                        <th>Data Anggota</th>
                                        <th>Kontak</th>
                                        <th>Akademik</th>
                                        <th>Bergabung</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($activeMembers as $member)
                                        <tr>
                                            <td>
                                                <img src="{{ asset('storage/' . $member->pas_foto_url) }}" 
                                                     alt="Foto {{ $member->nama_lengkap }}" 
                                                     class="rounded"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            </td>
                                            <td>
                                                <strong>{{ $member->nama_lengkap }}</strong>
                                                @if($member->user->super_admin)
                                                    <span class="badge bg-danger">Super Admin</span>
                                                @elseif($member->user->is_admin)
                                                    <span class="badge bg-warning">Admin</span>
                                                @endif
                                                <br>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($member->tanggal_lahir)->format('d M Y') }} ({{ \Carbon\Carbon::parse($member->tanggal_lahir)->age }} th)<br>
                                                    <i class="bi bi-gender-{{ $member->jenis_kelamin == 'Laki-laki' ? 'male' : 'female' }}"></i> {{ $member->jenis_kelamin }}<br>
                                                    <i class="bi bi-card-text"></i> KTA: {{ $member->no_kta }}
                                                </small>
                                            </td>
                                            <td>
                                                <i class="bi bi-envelope"></i> {{ $member->user->email }}<br>
                                                <i class="bi bi-telephone"></i> {{ $member->no_telepon }}<br>
                                                <small class="text-muted">{{ Str::limit($member->alamat, 30) }}</small>
                                            </td>
                                            <td>
                                                <strong>{{ $member->jurusan->nama_jurusan }}</strong><br>
                                                <small class="text-muted">
                                                    <i class="bi bi-calendar3"></i> Angkatan {{ $member->tahun_angkatan }}
                                                </small>
                                            </td>
                                            <td>
                                                <small>{{ $member->created_at->format('d M Y') }}</small><br>
                                                <small class="text-muted">{{ $member->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <div class="btn-group-vertical" role="group">
                                                    <a href="{{ route('admin.members.show', $member) }}" 
                                                       class="btn btn-sm btn-info" title="Lihat Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    
                                                    
                                                    
                                                    @if(!$member->user->super_admin)
                                                        <a href="{{ route('admin.members.deactivate.form', $member) }}" 
                                                           class="btn btn-sm btn-secondary" title="Nonaktifkan">
                                                            <i class="bi bi-pause"></i>
                                                        </a>
                                                    @else
                                                        <button class="btn btn-sm btn-outline-secondary" disabled title="Super Admin tidak dapat dinonaktifkan">
                                                            <i class="bi bi-shield-check"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="d-flex justify-content-center">
                            {{ $activeMembers->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                            <h5 class="mt-3">Belum Ada Anggota Aktif</h5>
                            <p class="text-muted">Belum ada anggota yang disetujui dan diaktifkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
