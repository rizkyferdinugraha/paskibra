<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tambah Jurusan Baru</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.jurusan.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="nama_jurusan" class="form-label">Nama Jurusan</label>
                            <input type="text" 
                                   class="form-control @error('nama_jurusan') is-invalid @enderror" 
                                   id="nama_jurusan" 
                                   name="nama_jurusan" 
                                   value="{{ old('nama_jurusan') }}" 
                                   required>
                            @error('nama_jurusan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Simpan
                            </button>
                            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
