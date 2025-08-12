<x-app-layout>
    <div class="page-heading">
        <h3>{{ $acara->exists ? 'Edit Acara' : 'Buat Acara' }}</h3>
    </div>

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
            <form action="{{ $acara->exists ? route('acara.update', $acara) : route('acara.store') }}" method="POST">
                @csrf
                @if($acara->exists)
                    @method('PUT')
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Nama Acara</label>
                        <input type="text" name="nama" class="form-control" value="{{ old('nama', $acara->nama) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal_date" class="form-control" value="{{ old('tanggal_date', ($acara->waktu_mulai ?? $acara->tanggal) ? ($acara->waktu_mulai ?? $acara->tanggal)->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Waktu Mulai</label>
                        <input type="time" name="waktu_mulai" class="form-control" value="{{ old('waktu_mulai', $acara->waktu_mulai ? $acara->waktu_mulai->format('H:i') : '') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date" name="tanggal_selesai_date" class="form-control" value="{{ old('tanggal_selesai_date', $acara->waktu_selesai ? $acara->waktu_selesai->format('Y-m-d') : (($acara->waktu_mulai ?? $acara->tanggal)? ($acara->waktu_mulai ?? $acara->tanggal)->format('Y-m-d') : '')) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Waktu Selesai</label>
                        <input type="time" name="waktu_selesai" class="form-control" value="{{ old('waktu_selesai', $acara->waktu_selesai ? $acara->waktu_selesai->format('H:i') : '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Lokasi</label>
                        <input type="text" name="lokasi" class="form-control" value="{{ old('lokasi', $acara->lokasi) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Seragam</label>
                        <input type="text" name="seragam" class="form-control" value="{{ old('seragam', $acara->seragam) }}" placeholder="Misal: PDH, PDL, Olahraga">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" rows="4" class="form-control">{{ old('deskripsi', $acara->deskripsi) }}</textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Perlengkapan (opsional)</label>
                        @php($items = old('perlengkapan', $acara->perlengkapan ?? []))
                        <div id="perlengkapan-list">
                            @forelse ($items as $idx => $item)
                                <div class="input-group mb-2">
                                    <input name="perlengkapan[]" class="form-control" value="{{ $item }}">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">Hapus</button>
                                </div>
                            @empty
                                <div class="input-group mb-2">
                                    <input name="perlengkapan[]" class="form-control" placeholder="Misal: Peluit, Tali, Botol Minum">
                                    <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">Hapus</button>
                                </div>
                            @endforelse
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addItem()">Tambah Item</button>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Wajib Hadir</label>
                        <div class="table-responsive" style="max-height: 400px; overflow-y:auto;">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>No. KTA</th>
                                        <th>Nama</th>
                                        <th>Role</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php($selected = collect(old('wajib_hadir', $acara->wajibHadir->pluck('id')->all())))
                                @foreach ($eligibleUsers as $user)
                                    <tr>
                                        <td style="width: 40px;">
                                            <input type="checkbox" name="wajib_hadir[]" value="{{ $user->id }}" @checked($selected->contains($user->id))>
                                        </td>
                                        <td><span class="badge bg-secondary">{{ $user->biodata?->no_kta ?? '-' }}</span></td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->role?->nama_role }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary">{{ $acara->exists ? 'Simpan Perubahan' : 'Buat Acara' }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function addItem() {
            const container = document.getElementById('perlengkapan-list');
            const div = document.createElement('div');
            div.className = 'input-group mb-2';
            div.innerHTML = `<input name="perlengkapan[]" class="form-control" placeholder="Item">
                             <button type="button" class="btn btn-outline-danger" onclick="this.parentElement.remove()">Hapus</button>`;
            container.appendChild(div);
        }
    </script>
</x-app-layout>


