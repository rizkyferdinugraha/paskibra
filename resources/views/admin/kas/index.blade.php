<x-app-layout>
    <div class="page-heading">
        <h3>Kas</h3>
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

    <div class="row">
        <div class="col-xl-4 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h6>Total Pemasukan</h6>
                    <h3 class="mt-2">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h6>Total Pengeluaran</h6>
                    <h3 class="mt-2">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 col-sm-12">
            <div class="card">
                <div class="card-body">
                    <h6>Saldo Saat Ini</h6>
                    <h3 class="mt-2">Rp {{ number_format($saldo, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Catat Transaksi</h4>
        </div>
        <div class="card-body">
            <form action="{{ route('kas.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', now()->toDateString()) }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jenis</label>
                        <select name="jenis" class="form-select" required>
                            <option value="pemasukan" @selected(old('jenis')==='pemasukan')>Pemasukan</option>
                            <option value="pengeluaran" @selected(old('jenis')==='pengeluaran')>Pengeluaran</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Jumlah (Rp)</label>
                        <input type="number" name="jumlah" class="form-control" min="1" step="1" value="{{ old('jumlah') }}" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Keterangan</label>
                        <input type="text" name="keterangan" class="form-control" maxlength="255" value="{{ old('keterangan') }}">
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Riwayat Transaksi</h4>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Jenis</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                        <th>Dicatat Oleh</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $t)
                        <tr>
                            <td>{{ $t->tanggal->format('d/m/Y') }}</td>
                            <td>
                                <span class="badge {{ $t->jenis==='pemasukan' ? 'bg-success' : 'bg-danger' }}">{{ ucfirst($t->jenis) }}</span>
                            </td>
                            <td>Rp {{ number_format($t->jumlah, 0, ',', '.') }}</td>
                            <td>{{ $t->keterangan }}</td>
                            <td>{{ $t->creator?->name }}</td>
                            <td class="text-end">
                                <form action="{{ route('kas.destroy', $t) }}" method="POST" onsubmit="return confirm('Hapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada transaksi</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                {{ $transaksis->links() }}
            </div>
        </div>
    </div>
</x-app-layout>


