<x-app-layout>
    <div class="page-heading d-print-none">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Detail Komplain</h3>
            <div>
                <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                    <i class="bi bi-printer"></i> Cetak
                </button>
                @if($complaint->status !== 'selesai')
                <form action="{{ route('complaints.done', $complaint) }}" method="POST" class="d-inline" onsubmit="return confirm('Tandai komplain ini sebagai selesai?')">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-success me-1">
                        <i class="bi bi-check2"></i> Tandai Selesai
                    </button>
                </form>
                @endif
                <form action="{{ route('complaints.destroy', $complaint) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus laporan komplain ini? Tindakan tidak dapat dibatalkan.')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger">
                        <i class="bi bi-trash"></i> Hapus Laporan
                    </button>
                </form>
            </div>
        </div>
        @if (session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div id="print-area" class="report-sheet mx-auto">
                    <div class="report-header d-flex align-items-center mb-3">
                        <div class="me-3">
                            <img src="{{ asset('image/logo_no_text.png') }}" alt="Logo" style="height: 60px;">
                        </div>
                        <div class="flex-grow-1 text-center">
                            <div class="h5 mb-1 fw-bold">PASKIBRA</div>
                            <div class="h6 mb-0">Formulir Laporan Komplain</div>
                        </div>
                        <div style="width:60px"></div>
                    </div>

                    <div class="report-meta mb-3">
                        <div class="row g-2">
                            <div class="col-sm-6"><strong>Nomor Laporan</strong>: KPL-{{ str_pad($complaint->id, 5, '0', STR_PAD_LEFT) }}</div>
                            <div class="col-sm-6 text-sm-end"><strong>Tanggal Lapor</strong>: {{ $complaint->created_at->format('d/m/Y H:i') }}</div>
                        </div>
                    </div>

                    <div class="report-section mb-3">
                        <div class="section-title">Data Pelapor</div>
                        <div class="row">
                            <div class="col-12">
                                <div class="field-line"><span>Nama Pelapor</span><span class="dots"></span><span class="value">{{ isset($maskPelapor) && $maskPelapor ? substr($complaint->nama_pelapor,0,1) . str_repeat('*', max(strlen($complaint->nama_pelapor)-1, 0)) : $complaint->nama_pelapor }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="report-section mb-3">
                        <div class="section-title">Data Terlapor</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-line"><span>Nama</span><span class="dots"></span><span class="value">{{ $complaint->terlapor?->name }}</span></div>
                            </div>
                            <div class="col-md-3">
                                <div class="field-line"><span>Role</span><span class="dots"></span><span class="value">{{ $complaint->terlapor?->role?->nama_role }}</span></div>
                            </div>
                            <div class="col-md-3">
                                <div class="field-line"><span>No. KTA</span><span class="dots"></span><span class="value">{{ $complaint->terlapor?->biodata?->no_kta ?: '-' }}</span></div>
                            </div>
                        </div>
                    </div>

                    <div class="report-section mb-3">
                        <div class="section-title">Perihal</div>
                        <div class="field-line"><span>Judul Laporan</span><span class="dots"></span><span class="value">{{ $complaint->judul }}</span></div>
                    </div>

                    <div class="report-section mb-3">
                        <div class="section-title">Uraian Kejadian</div>
                        <div class="desc-box">{!! nl2br(e($complaint->deskripsi)) !!}</div>
                    </div>

                    <div class="report-section mb-4">
                        <div class="section-title">Lampiran</div>
                        <div>
                            @if($complaint->bukti_path)
                                Ada (terlampir) — <a href="{{ asset('storage/'.$complaint->bukti_path) }}" target="_blank">Lihat Bukti</a>
                            @else
                                Tidak ada
                            @endif
                        </div>
                    </div>

                    <div class="report-section mb-4">
                        <div class="section-title">Status</div>
                        <div>
                            <span class="badge {{ $complaint->status === 'selesai' ? 'bg-success' : 'bg-primary' }}">{{ ucfirst($complaint->status) }}</span>
                        </div>
                    </div>

                    @if(auth()->user()->role && in_array(strtolower(auth()->user()->role->nama_role), ['pembina','pelatih','senior']))
                    <div class="report-section mb-4 d-print-none">
                        <div class="section-title">Tindak Lanjut</div>
                        <form action="{{ route('complaints.followup', $complaint) }}" method="POST" class="mb-3">
                            @csrf
                            <textarea name="follow_up" rows="4" class="form-control" placeholder="Tuliskan arahan, sanksi, atau rencana pembinaan..." required>{{ old('follow_up', $complaint->follow_up) }}</textarea>
                            <div class="mt-2 text-end">
                                <button class="btn btn-primary">Simpan Tindak Lanjut</button>
                            </div>
                        </form>
                        @if($complaint->follow_up)
                            <div class="small text-muted">Terakhir diperbarui: {{ $complaint->follow_up_at ? \Carbon\Carbon::parse($complaint->follow_up_at)->format('d/m/Y H:i') : '-' }}</div>
                        @endif
                    </div>
                    @endif

                    <div class="report-signatures row mt-5">
                        <div class="col-md-6 text-center">
                            <div class="signature-line"></div>
                            <div class="signature-label">Pelapor</div>
                            <div class="signature-name">{{ $complaint->nama_pelapor }}</div>
                        </div>
                        <div class="col-md-6 text-center">
                            <div class="signature-line"></div>
                            <div class="signature-label">Penerima/Validator</div>
                            <div class="signature-name">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .report-sheet {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 24px;
            max-width: 900px;
        }
        .report-header .h5, .report-header .h6 { margin: 0; }
        .section-title {
            font-weight: 600;
            margin-bottom: 8px;
            padding-bottom: 4px;
            border-bottom: 2px solid #e5e7eb;
        }
        .field-line { display: flex; align-items: baseline; gap: 8px; margin-bottom: 6px; }
        .field-line .dots { flex: 1 1 auto; border-bottom: 1px dotted #cbd5e1; height: 0; }
        .field-line .value { min-width: 200px; text-align: right; }
        .desc-box {
            min-height: 120px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            line-height: 1.6;
            white-space: pre-wrap;
        }
        .signature-line {
            margin: 40px auto 6px;
            border-bottom: 1px solid #94a3b8;
            width: 70%;
            height: 0;
        }
        .signature-label { font-size: 0.9rem; color: #6b7280; }
        .signature-name { font-weight: 600; margin-top: 4px; }

        @media print {
            @page { size: A4; margin: 12mm; }
            body * { visibility: hidden; }
            #print-area, #print-area * { visibility: visible; }
            #print-area { position: absolute; left: 0; top: 0; width: 100%; }
            .d-print-none { display: none !important; }
            .report-sheet { border: none; box-shadow: none; padding: 0; max-width: none; }
            a[href]:after { content: "" !important; }
        }
    </style>
</x-app-layout>


