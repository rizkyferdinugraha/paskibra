<x-app-layout>
    <section>
        <div class="col">
            <div class="card">
                {{-- Notifikasi Sukses --}}
                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success">
                        Profil Anda berhasil diperbarui
                    </div>
                @endif

                {{-- Notifikasi Error Umum --}}
                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Notifikasi Validasi Error --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session()->has('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @elseif (session()->has('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif
                <div class="card-header">
                    <h5 class="card-title">Informasi Profil</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#info_akun"
                                role="tab" aria-controls="home" aria-selected="true">Info Akun</a>
                        </li>
                        <li class="nav-item" role="presentation">
                            <a class="nav-link" id="profile-tab" data-bs-toggle="tab" href="#info_biodata"
                                role="tab" aria-controls="profile" aria-selected="false" tabindex="-1">Info
                                Biodata</a>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="info_akun" role="tabpanel"
                            aria-labelledby="home-tab">
                            @include('profile.partial.info_akun')
                        </div>
                        <div class="tab-pane fade" id="info_biodata" role="tabpanel" aria-labelledby="profile-tab">
                            @include('profile.partial.info_biodata')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
