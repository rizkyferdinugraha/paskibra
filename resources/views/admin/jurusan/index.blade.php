<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Kelola Jurusan</h4>
                    <a href="{{ route('admin.jurusan.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Jurusan
                    </a>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Jurusan</th>
                                    <th>Jumlah Anggota</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($jurusans as $jurusan)
                                    <tr>
                                        <td>{{ $jurusan->id }}</td>
                                        <td>{{ $jurusan->nama_jurusan }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $jurusan->biodatas_count }} anggota</span>
                                        </td>
                                        <td>{{ $jurusan->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.jurusan.edit', $jurusan) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                @if($jurusan->biodatas_count == 0)
                                                    <form action="{{ route('admin.jurusan.destroy', $jurusan) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus jurusan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled title="Tidak dapat dihapus karena masih ada anggota">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Belum ada data jurusan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $jurusans->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
