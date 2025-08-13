<x-app-layout>
    {{-- Storage facade dipanggil via helper di controller atau gunakan helper url dari disk --}}
    <div class="page-heading">
        <h3>Daftar Laporan Komplain</h3>
    </div>
    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Laporan Terbaru</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Judul</th>
                                        <th>Nama Pelapor</th>
                                        <th>Terlapor</th>
                                        <th>Status</th>
                                        <th>Tindak Lanjut</th>
                                        <th>Bukti</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($complaints as $c)
                                        <tr>
                                            <td>{{ $c->created_at->format('d/m/Y H:i') }}</td>
                                            <td>
                                                <details>
                                                    <summary>{{ $c->judul }}</summary>
                                                    <div class="mt-2 small text-muted">
                                                        <div class="mt-2">{{ $c->deskripsi }}</div>
                                                    </div>
                                                </details>
                                            </td>
                                            <td>{{ $c->nama_pelapor }}</td>
                                            <td>{{ $c->terlapor?->name }} ({{ $c->terlapor?->role?->nama_role }})</td>
                                            <td><span class="badge bg-primary">{{ ucfirst($c->status) }}</span></td>
                                            <td>
                                                @if($c->follow_up)
                                                    <details>
                                                        <summary class="text-success">Ada</summary>
                                                        <div class="small text-muted mt-1">{{ \Illuminate\Support\Str::limit($c->follow_up, 140) }}</div>
                                                    </details>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>
                                                @if($c->bukti_path)
                                                    <a href="{{ asset('storage/'.$c->bukti_path) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Lihat</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-nowrap">
                                                <a href="{{ route('complaints.show', $c) }}" class="btn btn-sm btn-primary">Detail/Cetak</a>
                                                <form action="{{ route('complaints.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan komplain ini? Tindakan tidak dapat dibatalkan.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Belum ada laporan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $complaints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</x-app-layout>


