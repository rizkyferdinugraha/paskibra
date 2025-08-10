<x-app-layout>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Super Admin Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 col-sm-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $stats['total_users'] }}</h3>
                                            <p class="mb-0">Total Users</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-people fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $stats['active_members'] }}</h3>
                                            <p class="mb-0">Anggota Aktif</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-person-check fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $stats['pending_members'] }}</h3>
                                            <p class="mb-0">Menunggu Verifikasi</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-person-clock fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3 col-sm-6">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h3>{{ $stats['total_jurusans'] }}</h3>
                                            <p class="mb-0">Total Jurusan</p>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bi bi-mortarboard fs-1"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Quick Actions</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.members.index') }}" class="btn btn-warning btn-lg w-100 mb-3">
                                                <i class="bi bi-person-plus"></i> Persetujuan Anggota
                                                @if($stats['pending_members'] > 0)
                                                    <span class="badge bg-danger">{{ $stats['pending_members'] }}</span>
                                                @endif
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-lg w-100 mb-3">
                                                <i class="bi bi-people"></i> Kelola Users
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.jurusan.index') }}" class="btn btn-success btn-lg w-100 mb-3">
                                                <i class="bi bi-mortarboard"></i> Kelola Jurusan
                                            </a>
                                        </div>
                                        <div class="col-md-3">
                                            <a href="{{ route('admin.roles.index') }}" class="btn btn-info btn-lg w-100 mb-3">
                                                <i class="bi bi-shield-check"></i> Kelola Roles
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
