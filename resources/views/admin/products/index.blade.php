@extends('layouts.admin')

@section('title', 'Manajemen Produk')

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

            @if(session('error'))
                <div class="alert alert-flat alert-danger border-0 shadow-sm alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Main Card --}}
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0 fw-bold text-dark">Daftar Produk</h5>
                        <small class="text-muted">Kelola inventaris dan stok produk Anda</small>
                    </div>
                    <button class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                    </button>
                </div>

                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small uppercase">
                                <tr>
                                    <th class="ps-4 py-3">INFO PRODUK</th>
                                    <th class="text-center">KATEGORI</th>
                                    <th class="text-center">STATUS</th>
                                    <th class="text-center">HARGA & STOK</th>
                                    <th class="text-end pe-4">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($products as $product)
                                    <tr>
                                        {{-- Product Info --}}
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="symbol-label me-3">
                                                    @if($product->primaryImage)
                                                        <img src="{{ $product->primaryImage->image_url }}" class="rounded-3 shadow-sm product-thumb" width="48" height="48" style="object-fit: cover;">
                                                    @else
                                                        <div class="bg-soft-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                            <i class="bi bi-box-seam text-primary"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="text-dark fw-bold lh-1 mb-1">{{ $product->name }}</div>
                                                    <span class="text-muted small">Berat: {{ $product->weight }}g</span>
                                                </div>
                                            </div>
                                        </td>

                                        {{-- Category --}}
                                        <td class="text-center">
                                            <span class="badge rounded-pill bg-light text-primary border px-3">
                                                {{ $product->category->name }}
                                            </span>
                                        </td>

                                        {{-- Status --}}
                                        <td class="text-center">
                                            @if($product->is_active)
                                                <span class="badge bg-soft-success text-success px-3">
                                                    <i class="bi bi-dot"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-soft-secondary text-secondary px-3">
                                                    <i class="bi bi-dot"></i> Non-Aktif
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Price & Stock --}}
                                        <td class="text-center">
                                            <div class="fw-bold text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                            @if($product->stock <= 5)
                                                <small class="text-danger fw-bold"><i class="bi bi-exclamation-circle me-1"></i>Stok: {{ $product->stock }}</small>
                                            @else
                                                <small class="text-muted">Stok: {{ $product->stock }}</small>
                                            @endif
                                        </td>

                                        {{-- Actions --}}
                                        <td class="text-end pe-4">
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-light-warning text-warning me-2" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#editModal{{ $product->id }}"
                                                        title="Edit">
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                
                                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light-danger text-danger" title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5">
                                            <div class="py-3">
                                                <i class="bi bi-box-seam display-4 text-light"></i>
                                                <p class="text-muted mt-3">Belum ada produk yang tersedia.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="card-footer bg-white border-0 py-3">
                    <div class="d-flex justify-content-center">
                        {{ $products->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL CREATE --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold small">Nama Produk</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="Masukkan nama produk" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold small">Kategori</label>
                            <select name="category_id" class="form-select form-select-lg bg-light border-0" required>
                                <option value="">Pilih...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold small">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control bg-light border-0" placeholder="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold small">Stok</label>
                            <input type="number" name="stock" class="form-control bg-light border-0" placeholder="0" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold small">Berat (Gram)</label>
                            <input type="number" name="weight" class="form-control bg-light border-0" placeholder="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Gambar Utama</label>
                        <input type="file" name="image" class="form-control border-0 bg-light">
                    </div>
                    <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                        <label class="form-check-label fw-semibold small mb-0">Aktifkan Produk</label>
                        <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" checked>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL EDIT (LOOP) --}}
@foreach($products as $product)
<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body px-4">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label fw-semibold small">Nama Produk</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ $product->name }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold small">Kategori</label>
                            <select name="category_id" class="form-select form-select-lg bg-light border-0" required>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold small">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control bg-light border-0" value="{{ $product->price }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold small">Stok</label>
                            <input type="number" name="stock" class="form-control bg-light border-0" value="{{ $product->stock }}" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label fw-semibold small">Berat (Gram)</label>
                            <input type="number" name="weight" class="form-control bg-light border-0" value="{{ $product->weight }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Ganti Gambar (Opsional)</label>
                        <input type="file" name="image" class="form-control border-0 bg-light">
                    </div>
                    <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                        <label class="form-check-label fw-semibold small mb-0">Produk Aktif</label>
                        <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-muted text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4 rounded-pill">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<style>
    /* Custom Styling yang selaras dengan Kategori */
    .bg-soft-success { background-color: #e8fadf; }
    .bg-soft-primary { background-color: #e0eaff; }
    .bg-soft-secondary { background-color: #f1f3f5; }
    .btn-light-warning { background-color: #fff8dd; border: none; }
    .btn-light-danger { background-color: #fff5f8; border: none; }
    .product-thumb { transition: transform .2s ease; }
    .product-thumb:hover { transform: scale(1.1); }
    .form-control:focus, .form-select:focus { 
        background-color: #fff !important; 
        border: 1px solid #0d6efd !important; 
        box-shadow: none; 
    }
    .table thead th { font-size: 11px; letter-spacing: 0.05rem; }
    .alert-flat { border-radius: 10px; }
</style>
@endsection