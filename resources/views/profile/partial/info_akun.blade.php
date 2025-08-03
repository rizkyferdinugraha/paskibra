<section class="section">
    <div class="card">
        <div class="card-body">

            {{-- Notifikasi Sukses --}}
            @if(session('status') === 'profile-updated')
                <div class="alert alert-success">
                    Profil Anda berhasil diperbarui
                </div>
            @endif

            {{-- Notifikasi Error Umum --}}
            @if(session('error'))
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

            <form id="form-update-profile" method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" placeholder="Nama" name="name" value="{{ old('name', Auth::user()->name) }}">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Email" name="email" value="{{ old('email', Auth::user()->email) }}">
                </div>

                <div class="form-group">
                    <label for="password_sekarang">Password Saat ini </label>
                    <input type="password" class="form-control" id="password_sekarang" placeholder="Password Saat ini" name="password_sekarang">
                </div>

                <div class="form-group">
                    <label for="password_baru">Password Baru </label>
                    <input type="password" class="form-control" id="password_baru" placeholder="Password Baru" name="password_baru">
                </div>

                <div class="form-group">
                    <label for="password_baru_confirmation">Konfirmasi Password</label>
                    <input type="password" class="form-control" id="password_baru_confirmation" placeholder="Konfirmasi Password" name="password_baru_confirmation">
                </div>

                <div class="form-group mt-5">
                    <button class="btn btn-primary" type="submit" id="btn-update-profile">Perbarui Profil</button>
                </div>
            </form>

        </div>
    </div>
</section>
