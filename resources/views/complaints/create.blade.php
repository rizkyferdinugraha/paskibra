@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Ajukan Komplain</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('complaints.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="nama_pelapor" class="form-label">Nama Pelapor *</label>
                            <input type="text" class="form-control @error('nama_pelapor') is-invalid @enderror" 
                                   id="nama_pelapor" name="nama_pelapor" value="{{ old('nama_pelapor') }}" required>
                            @error('nama_pelapor')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="judul" class="form-label">Judul Komplain *</label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" name="judul" value="{{ old('judul') }}" required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="terlapor_user_id" class="form-label">Terlapor *</label>
                            <select class="form-select @error('terlapor_user_id') is-invalid @enderror" 
                                    id="terlapor_user_id" name="terlapor_user_id" required>
                                <option value="">Pilih anggota yang dilaporkan</option>
                                @foreach($terlapors as $terlapor)
                                    <option value="{{ $terlapor->id }}" 
                                            {{ old('terlapor_user_id') == $terlapor->id ? 'selected' : '' }}>
                                        {{ $terlapor->name }} - {{ $terlapor->role->nama_role ?? 'Tidak ada role' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('terlapor_user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Komplain *</label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                      id="deskripsi" name="deskripsi" rows="4" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bukti" class="form-label">Bukti (Opsional)</label>
                            <input type="file" class="form-control @error('bukti') is-invalid @enderror" 
                                   id="bukti" name="bukti" accept="image/*,.pdf">
                            <div class="form-text">Format: JPG, JPEG, PNG, PDF. Maksimal 4MB.</div>
                            @error('bukti')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary" onclick="showLoader('Mengirim komplain...')">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Komplain
                            </button>
                            <a href="{{ route('/') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Beranda
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loader Component -->
<x-loader />
@endsection


