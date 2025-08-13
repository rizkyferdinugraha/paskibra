@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="card-title text-success mb-3">Terima Kasih!</h2>
                    <p class="card-text mb-4">
                        Komplain Anda telah berhasil dikirim. Tim kami akan segera memproses dan menindaklanjuti laporan tersebut.
                    </p>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Laporan Anda bersifat rahasia dan hanya dapat diakses oleh Pembina, Pelatih, dan Senior.
                    </div>
                    <div class="d-grid gap-2 d-md-block">
                        <a href="{{ route('complaints.create') }}" class="btn btn-primary me-md-2">
                            <i class="fas fa-plus me-2"></i>Ajukan Komplain Lain
                        </a>
                        <a href="{{ route('/') }}" class="btn btn-secondary">
                            <i class="fas fa-home me-2"></i>Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loader Component -->
<x-loader />
@endsection


