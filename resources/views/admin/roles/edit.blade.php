<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Edit Role: {{ $role->nama_role }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.update', $role) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group mb-3">
                            <label for="nama_role" class="form-label">Nama Role</label>
                            <input type="text" 
                                   class="form-control @error('nama_role') is-invalid @enderror" 
                                   id="nama_role" 
                                   name="nama_role" 
                                   value="{{ old('nama_role', $role->nama_role) }}" 
                                   {{ $role->id <= 6 ? 'readonly' : '' }}
                                   required>
                            @error('nama_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if($role->id <= 6)
                                <div class="form-text text-warning">
                                    <i class="bi bi-exclamation-triangle"></i> Role default tidak dapat diubah namanya.
                                </div>
                            @endif
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-info-circle"></i> Informasi Role:</h6>
                                    <ul class="mb-0">
                                        <li><strong>ID:</strong> {{ $role->id }}</li>
                                        <li><strong>Dibuat:</strong> {{ $role->created_at->format('d M Y H:i') }}</li>
                                        <li><strong>Diupdate:</strong> {{ $role->updated_at->format('d M Y H:i') }}</li>
                                        <li><strong>Jumlah Users:</strong> {{ $role->users()->count() }} users</li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                @if($role->users()->count() > 0)
                                    <div class="alert alert-warning">
                                        <h6><i class="bi bi-people"></i> Users dengan Role ini:</h6>
                                        <ul class="mb-0">
                                            @foreach($role->users()->limit(5)->get() as $user)
                                                <li>{{ $user->name }} ({{ $user->email }})</li>
                                            @endforeach
                                            @if($role->users()->count() > 5)
                                                <li><em>... dan {{ $role->users()->count() - 5 }} lainnya</em></li>
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group">
                            @if($role->id > 6)
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update
                                </button>
                            @endif
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
