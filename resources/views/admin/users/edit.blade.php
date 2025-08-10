<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit User: {{ $user->name }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="role_id" class="form-label">Role</label>
                                    <select class="form-control @error('role_id') is-invalid @enderror" 
                                            id="role_id" 
                                            name="role_id" 
                                            required>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" 
                                                    {{ old('role_id', $user->role_id) == $role->id ? 'selected' : '' }}>
                                                {{ $role->nama_role }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">Status Admin</label>
                                    <div>
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   id="is_admin" 
                                                   name="is_admin"
                                                   value="1" 
                                                   {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_admin">
                                                Admin
                                            </label>
                                        </div>
                                        
                                        {{-- Super Admin tidak dapat diubah dari halaman ini --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($user->biodata)
                            <div class="alert alert-info">
                                <h6>Informasi Biodata:</h6>
                                <p><strong>No KTA:</strong> {{ $user->biodata->no_kta }}</p>
                                <p><strong>Jurusan:</strong> {{ $user->biodata->jurusan->nama_jurusan ?? 'N/A' }}</p>
                                <p><strong>Status:</strong> 
                                    @if($user->biodata->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-warning">Pending</span>
                                    @endif
                                </p>
                            </div>
                        @else
                            <div class="alert alert-warning">
                                User ini belum mengisi biodata.
                            </div>
                        @endif
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update
                            </button>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
