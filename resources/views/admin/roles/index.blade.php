<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Kelola Roles</h4>
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus"></i> Tambah Role
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
                                    <th>Nama Role</th>
                                    <th>Jumlah Users</th>
                                    <th>Status</th>
                                    <th>Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>{{ $role->id }}</td>
                                        <td>
                                            {{ $role->nama_role }}
                                            @if($role->id <= 6)
                                                <span class="badge bg-secondary">Default</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $role->users_count }} users</span>
                                        </td>
                                        <td>
                                            @if($role->id <= 6)
                                                <span class="badge bg-success">System</span>
                                            @else
                                                <span class="badge bg-primary">Custom</span>
                                            @endif
                                        </td>
                                        <td>{{ $role->created_at->format('d M Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                                   class="btn btn-sm btn-warning">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                
                                                @if($role->id > 6 && $role->users_count == 0)
                                                    <form action="{{ route('admin.roles.destroy', $role) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin menghapus role ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <button class="btn btn-sm btn-secondary" disabled 
                                                            title="Role default atau masih digunakan tidak dapat dihapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">Belum ada data role.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
