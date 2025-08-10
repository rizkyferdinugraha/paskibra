<x-app-layout>
    <div class="row">
        <div class="col-12">
            <!-- Current Status Card (berdasarkan riwayat terakhir) -->
            @php
                $color = 'secondary';
                $icon = 'person-x';
                $label = 'Belum Mendaftar';
                $desc = 'Anda belum mengisi biodata untuk mendaftar sebagai anggota.';
                $showReason = false;
                $reason = null;
                $who = null;
                $when = null;

                if ($latestLog) {
                    $when = $latestLog->created_at?->format('d F Y H:i');
                    $who = $latestLog->admin_name;
                    switch ($latestLog->action) {
                        case 'deactivated':
                            $color = 'warning';
                            $icon = 'person-dash';
                            $label = 'Keanggotaan Dinonaktifkan';
                            $desc = 'Keanggotaan Anda telah dinonaktifkan.';
                            $showReason = true;
                            $reason = $latestLog->reason;
                            break;
                        case 'rejected':
                            $color = 'danger';
                            $icon = 'person-x';
                            $label = 'Pendaftaran Ditolak';
                            $desc = 'Pendaftaran Anda ditolak.';
                            $showReason = true;
                            $reason = $latestLog->reason;
                            break;
                        case 'approved':
                            $color = 'success';
                            $icon = 'person-check';
                            $label = 'Pendaftaran Disetujui';
                            $desc = 'Pendaftaran Anda telah disetujui. Jika belum aktif, menunggu aktivasi.';
                            break;
                        case 'activated':
                            $color = 'success';
                            $icon = 'person-check';
                            $label = 'Anggota Aktif';
                            $desc = 'Anda adalah anggota aktif Paskibra.';
                            break;
                        case 'pending':
                        default:
                            $color = 'info';
                            $icon = 'clock';
                            $label = 'Mengajukan Pendaftaran';
                            $desc = 'Pendaftaran Anda telah diajukan dan sedang menunggu persetujuan admin.';
                            break;
                    }
                }
            @endphp

            <div class="card mb-4">
                <div class="card-header bg-{{ $color }} text-white">
                    <h4 class="card-title mb-0">
                        <i class="bi bi-{{ $icon }}"></i> Status Keanggotaan
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-2 text-center">
                            <i class="bi bi-{{ $icon }} text-{{ $color }}" style="font-size: 4rem;"></i>
                        </div>
                        <div class="col-md-10">
                            <h3 class="text-{{ $color }}">{{ $label }}</h3>
                            <p class="lead">{{ $desc }}</p>

                            @if($showReason && $reason)
                                <div class="alert alert-{{ $color }} mt-3">
                                    <h6><i class="bi bi-info-circle"></i> Alasan:</h6>
                                    <p class="mb-0">{{ $reason }}</p>
                                    @if($who)
                                        <small class="text-muted">
                                            <i class="bi bi-person"></i> Oleh: {{ $who }}
                                            @if($when) pada {{ $when }} @endif
                                        </small>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Member Info Card (if has biodata) -->
            @if($biodata)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="bi bi-person-badge"></i> Informasi Anggota</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <img src="{{ asset('storage/' . $biodata->pas_foto_url) }}" 
                                     alt="Foto {{ $biodata->nama_lengkap }}" 
                                     class="img-thumbnail mb-3"
                                     style="width: 150px; height: 180px; object-fit: cover;">
                                     
                                @if($biodata->is_active)
                                    <div class="d-grid">
                                        <a href="{{ route('template.kta') }}" target="_blank" class="btn btn-success">
                                            <i class="bi bi-card-text"></i> Lihat KTA
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Nama Lengkap:</strong></td>
                                                <td>{{ $biodata->nama_lengkap }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. KTA:</strong></td>
                                                <td><code>{{ $biodata->no_kta }}</code></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Email:</strong></td>
                                                <td>{{ $user->email }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>No. Telepon:</strong></td>
                                                <td>{{ $biodata->no_telepon }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <td><strong>Jurusan:</strong></td>
                                                <td>{{ $biodata->jurusan->nama_jurusan }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Angkatan:</strong></td>
                                                <td>{{ $biodata->tahun_angkatan }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Daftar:</strong></td>
                                                <td>{{ $biodata->created_at->format('d F Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status:</strong></td>
                                                <td>
                                                    @if($biodata->is_active)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-warning">Tidak Aktif</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Status History -->
            @if($statusLogs->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5><i class="bi bi-clock-history"></i> Riwayat Status Keanggotaan</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            @foreach($statusLogs as $log)
                                <div class="timeline-item mb-4">
                                    <div class="row">
                                        <div class="col-md-2 text-center">
                                            <div class="timeline-badge bg-{{ $log->action == 'approved' ? 'success' : ($log->action == 'rejected' ? 'danger' : ($log->action == 'deactivated' ? 'warning' : 'info')) }}">
                                                <i class="bi bi-{{ $log->action == 'approved' ? 'check' : ($log->action == 'rejected' ? 'x' : ($log->action == 'deactivated' ? 'pause' : 'clock')) }}"></i>
                                            </div>
                                            <small class="text-muted">
                                                {{ $log->created_at->format('d M Y') }}<br>
                                                {{ $log->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                        <div class="col-md-10">
                                            <div class="card">
                                                <div class="card-body">
                                                    <h6 class="card-title">
                                                        <span class="badge bg-{{ $log->action == 'approved' ? 'success' : ($log->action == 'rejected' ? 'danger' : ($log->action == 'deactivated' ? 'warning' : 'info')) }}">
                                                            {{ $log->action_label }}
                                                        </span>
                                                    </h6>
                                                    
                                                    @if($log->reason)
                                                        <p class="card-text">{{ $log->reason }}</p>
                                                    @endif
                                                    
                                                    @if($log->admin_name)
                                                        <small class="text-muted">
                                                            <i class="bi bi-person"></i> Oleh: {{ $log->admin_name }}
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Action Buttons -->
            @if($currentStatus['status'] == 'no_biodata')
                <div class="text-center mt-4">
                    <p class="lead">Belum mendaftar sebagai anggota?</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-person-plus"></i> Daftar Sekarang
                    </a>
                </div>
            @elseif($currentStatus['status'] == 'rejected')
                <div class="text-center mt-4">
                    <p class="lead">Ingin mendaftar ulang?</p>
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-clockwise"></i> Daftar Ulang
                    </a>
                </div>
            @endif
        </div>
    </div>

    <style>
        .timeline-badge {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            margin: 0 auto;
        }
    </style>
</x-app-layout>
