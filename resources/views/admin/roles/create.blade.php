<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Role Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.roles.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="nama_role" class="form-label">Nama Role</label>
                            <input type="text" 
                                   class="form-control @error('nama_role') is-invalid @enderror" 
                                   id="nama_role" 
                                   name="nama_role" 
                                   value="{{ old('nama_role') }}" 
                                   placeholder="Contoh: Koordinator, Bendahara, dll"
                                   required>
                            @error('nama_role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Nama role harus unik dan akan digunakan untuk mengatur hak akses user.
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Informasi:</h6>
                            <ul class="mb-0">
                                <li>Role yang dibuat akan menjadi pilihan dalam pengaturan user</li>
                                <li>Role default (ID 1-6) tidak dapat dihapus</li>
                                <li>Pastikan nama role mudah dipahami</li>
                            </ul>
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
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
