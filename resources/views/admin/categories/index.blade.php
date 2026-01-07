@extends('layouts.admin')

@section('title', 'Manajemen Kategori')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            {{-- Alert Messages --}}
            @if(session('success'))
                <div class="alert alert-flat alert-success border-0 shadow-sm alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Main Card --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Daftar Kategori</h5>
                        <small class="text-muted">Kelola kategori produk toko Anda</small>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small uppercase">
                                <tr>
                                    <th class="ps-4 py-3">INFO KATEGORI</th>
                                    <th class="text-center">JUMLAH PRODUK</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-end pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        {{-- Category Info --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol-label me-3">
                                                    @if($category->image)
                                                        <img src="{{ Storage::url($category->image) }}" class="rounded-3 shadow-sm" width="48" height="48" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-soft-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                            <i class="bi bi-tag-fill text-primary"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <a href="#" class="text-dark fw-bold text-decoration-none d-block">{{ $category->name }}</a>
                                                    <span class="text-muted small">URL: /{{ $category->slug }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Product Count --}}
                                        <td class="text-center">
                                            <div class="badge rounded-pill bg-light text-dark border px-3">
                                                {{ $category->products_count }} Produk
                                            </div>
                                        </td>

                                        {{-- Status --}}
                                        <td class="text-center">
                                            @if($category->is_active)
                                                <span class="badge bg-soft-success text-success px-3">
                                                    <i class="bi bi-dot"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-soft-secondary text-secondary px-3">
                                                    <i class="bi bi-dot"></i> Non-Aktif
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-light-warning text-warning me-2" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editModal{{ $category->id }}"
                                                        title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                
                                                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger text-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- MODAL EDIT --}}
                                    <div class="modal fade" id="editModal{{ $category->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow">
                                                <div class="modal-header border-0 pb-0">
                                                    <h5 class="fw-bold">Edit Kategori</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="modal-body px-4">
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Nama Kategori</label>
                                                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ $category->name }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label fw-semibold small">Gambar Baru (Biarkan kosong jika tidak diganti)</label>
                                                            <input type="file" name="image" class="form-control border-0 bg-light">
                                                        </div>
                                                        <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between bg-light p-3 rounded-3">
                                                            <label class="form-check-label fw-semibold small mb-0">Status Kategori Aktif</label>
                                                            <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" {{ $category->is_active ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary px-4 rounded-pill">Update Kategori</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" width="80" class="mb-3 opacity-25">
                                            <p class="text-muted">Belum ada kategori yang tersedia.</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CREATE --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Nama Kategori</label>
                        <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: Elektronik" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Gambar Cover</label>
                        <input type="file" name="image" class="form-control border-0 bg-light">
                    </div>
                    <div class="form-check form-switch p-0 d-flex align-items-center justify-content-between bg-light p-3 rounded-3">
                        <label class="form-check-label fw-semibold small mb-0">Langsung Aktifkan</label>
                        <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" checked>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .bg-soft-success { background-color: #e8fadf; }
    .bg-soft-primary { background-color: #e0eaff; }
    .bg-soft-secondary { background-color: #f1f3f5; }
    .btn-light-warning { background-color: #fff8dd; border: none; }
    .btn-light-danger { background-color: #fff5f8; border: none; }
    .form-control:focus { background-color: #fff !important; border: 1px solid #0d6efd !important; box-shadow: none; }
    .table thead th { font-size: 11px; letter-spacing: 0.05rem; }
</style>
@endsection