<x-app-layout>
    <div class="page-heading d-flex align-items-center justify-content-between">
        <h3>Acara</h3>
        <a class="btn btn-primary" href="{{ route('acara.create') }}">Buat Acara</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Waktu</th>
                        <th>Lokasi</th>
                        <th>Seragam</th>
                        <th>Wajib Hadir</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($acaras as $acara)
                        <tr>
                            <td>{{ $acara->nama }}</td>
                            <td>
                                @php($mulai = $acara->waktu_mulai ?? $acara->tanggal)
                                @php($selesai = $acara->waktu_selesai)
                                {{ $mulai?->format('d/m/Y H:i') }}@if($selesai) - {{ $selesai->format('d/m/Y H:i') }}@endif
                            </td>
                            <td>{{ $acara->lokasi }}</td>
                            <td>{{ $acara->seragam }}</td>
                            <td>{{ $acara->wajibHadir->count() }} orang</td>
                            <td class="text-end">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('acara.edit', $acara) }}">Edit</a>
                                <form action="{{ route('acara.destroy', $acara) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus acara ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada acara</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div>{{ $acaras->links() }}</div>
        </div>
    </div>
</x-app-layout>


