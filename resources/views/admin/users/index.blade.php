@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-flat alert-success border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Main Card --}}
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Manajemen User</h5>
                        <small class="text-muted">Pantau dan kelola akses pengguna sistem</small>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small uppercase">
                                <tr>
                                    <th class="ps-4 py-3">PENGGUNA</th>
                                    <th>EMAIL</th>
                                    <th class="text-center">ROLE</th>
                                    <th class="text-center">TANGGAL BERGABUNG</th>
                                    <th class="text-end pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        {{-- User Info with Avatar --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm bg-soft-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 42px; height: 42px;">
                                                    <span class="text-primary fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <div class="text-dark fw-bold lh-1 mb-1">{{ $user->name }}</div>
                                                    <span class="text-muted small">ID: #{{ $user->id }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Email --}}
                                        <td>
                                            <span class="text-dark">{{ $user->email }}</span>
                                        </td>

                                        {{-- Role with Soft Badge --}}
                                        <td class="text-center">
                                            @if($user->role == 'admin')
                                                <span class="badge bg-soft-danger text-danger px-3 rounded-pill">
                                                    <i class="bi bi-shield-lock me-1"></i> Admin
                                                </span>
                                            @else
                                                <span class="badge bg-soft-info text-info px-3 rounded-pill">
                                                    <i class="bi bi-person me-1"></i> User
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Joined Date --}}
                                        <td class="text-center">
                                            <span class="text-muted small">{{ $user->created_at->format('d M Y') }}</span>
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end pe-4">
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-light-danger text-danger border-0" title="Hapus User">
                                                    <i class="bi bi-trash me-1"></i> Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <i class="bi bi-people display-4 text-light"></i>
                                            <p class="text-muted mt-3">Tidak ada data pengguna.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $users->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Theme Consistency */
    .bg-soft-danger { background-color: #fff5f8; }
    .bg-soft-info { background-color: #e0f7fa; }
    .bg-soft-primary { background-color: #e0eaff; }
    .btn-light-danger { background-color: #fff5f8; transition: all 0.2s; }
    .btn-light-danger:hover { background-color: #dc3545; color: white !important; }
    
    .table thead th { 
        font-size: 11px; 
        letter-spacing: 0.05rem; 
        font-weight: 700;
    }
    
    .avatar-sm {
        flex-shrink: 0;
    }

    .alert-flat { border-radius: 10px; }
</style>
@endsection