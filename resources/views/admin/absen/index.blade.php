<x-app-layout>
    <div class="page-heading">
        <h3>Absen</h3>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form method="GET" class="row g-3 mb-3">
                <div class="col-auto">
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" class="form-control" value="{{ $tanggal }}">
                </div>
                <div class="col-auto align-self-end">
                    <button class="btn btn-outline-primary">Tampilkan</button>
                </div>
            </form>

            <form action="{{ route('absen.store') }}" method="POST">
                @csrf
                <input type="hidden" name="tanggal" value="{{ $tanggal }}" />
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No. KTA</th>
                                <th>Nama</th>
                                <th>Role</th>
                                <th class="text-center">Hadir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($eligibleUsers as $user)
                                <tr>
                                    <td><span class="badge bg-secondary">{{ $user->biodata?->no_kta ?? '-' }}</span></td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->role?->nama_role }}</td>
                                    <td class="text-center">
                                        <input type="checkbox" name="absen[{{ $user->id }}]" @checked(optional($existing->get($user->id))->hadir) />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div>
                    <button class="btn btn-primary">Simpan Absen</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>


