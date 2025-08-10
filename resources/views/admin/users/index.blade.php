<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Kelola Users</h4>
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
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Biodata</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ $user->id }}</td>
                                        <td>
                                            {{ $user->name }}
                                            @if($user->super_admin)
                                                <span class="badge bg-danger">Super Admin</span>
                                            @elseif($user->is_admin)
                                                <span class="badge bg-warning">Admin</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $user->role->nama_role ?? 'No Role' }}</span>
                                        </td>
                                        <td>
                                            @if($user->biodata)
                                                @if($user->biodata->is_active)
                                                    <span class="badge bg-success">Aktif</span>
                                                @else
                                                    <span class="badge bg-warning">Pending</span>
                                                @endif
                                            @else
                                                <span class="badge bg-secondary">Belum Daftar</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->biodata)
                                                <i class="bi bi-check-circle text-success"></i>
                                            @else
                                                <i class="bi bi-x-circle text-danger"></i>
                                            @endif
                                        </td>
                                        <td>
                                            @if(!$user->super_admin)
                                                <div class="btn-group" role="group">
                                                    @if($user->biodata)
                                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                                           class="btn btn-sm btn-warning" title="Edit User">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                    @endif
                                                    
                                                    <form action="{{ route('admin.users.reset-password', $user) }}" 
                                                          method="POST" class="d-inline"
                                                          onsubmit="return confirm('Reset password ke password123?')">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-info">
                                                            <i class="bi bi-key"></i>
                                                        </button>
                                                    </form>
                                                    
                                                    @if($user->id !== auth()->id())
                                                        <form action="{{ route('admin.users.destroy', $user) }}" 
                                                              method="POST" class="d-inline"
                                                              onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">Belum ada data user.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
