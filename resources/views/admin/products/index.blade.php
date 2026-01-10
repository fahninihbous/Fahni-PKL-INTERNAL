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

            <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h5 class="mb-0 fw-bold text-dark">Daftar Produk</h5>
                    <small class="text-muted">Kelola inventaris dan stok produk Anda</small>
                </div>
                
                <div class="d-flex flex-column flex-md-row gap-2 align-items-center">
                    {{-- TOMBOL TRIGGER FILTER (Hanya muncul jika tidak sedang mode filter) --}}
                    @if(!request('mode'))
                        <a href="{{ request()->fullUrlWithQuery(['mode' => 'filter']) }}" class="btn btn-outline-success rounded-pill px-3 d-flex align-items-center" style="height: 38px;">
                            <i class="bi bi-funnel me-1"></i> Filter Pencarian
                        </a>
                    @endif

                    {{-- TOMBOL TAMBAH PRODUK --}}
                    <button class="btn btn-primary rounded-pill px-4" style="height: 38px;" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="bi bi-plus-lg me-1"></i> Tambah Produk
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm overflow-hidden mt-3">
                <div class="card-body p-0">
                    
                    {{-- ========================================================== --}}
                    {{-- MODE FILTER (TAMPILAN INI MENIMPA TABEL JIKA ?mode=filter) --}}
                    {{-- ========================================================== --}}
                    @if(request('mode') == 'filter')
                        <div class="p-4 bg-white">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="fw-bold mb-0 text-primary"><i class="bi bi-search me-2"></i>Filter Produk</h5>
                                <a href="{{ route('admin.products.index') }}" class="btn-close"></a>
                            </div>

                            <form action="{{ route('admin.products.index') }}" method="GET">
                                <div class="row g-3">
                                    {{-- Nama Produk --}}
                                    <div class="col-md-12">
                                        <label class="form-label small fw-bold">Cari Nama Produk</label>
                                        <input type="text" name="search" class="form-control bg-light border-0" value="{{ request('search') }}" placeholder="Ketik nama produk...">
                                    </div>
                                    
                                    {{-- Kategori --}}
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Kategori</label>
                                        <select name="category" class="form-select bg-light border-0">
                                            <option value="">Semua Kategori</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    {{-- Status --}}
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Status Produk</label>
                                        <select name="status" class="form-select bg-light border-0">
                                            <option value="">Semua Status</option>
                                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                                        </select>
                                    </div>

                                    {{-- Rentang Harga --}}
                                    <div class="col-md-4">
                                        <label class="form-label small fw-bold">Rentang Harga (Rp)</label>
                                        <div class="input-group">
                                            <input type="number" name="min_price" class="form-control bg-light border-0" placeholder="Min" value="{{ request('min_price') }}">
                                            <span class="input-group-text bg-light border-0">-</span>
                                            <input type="number" name="max_price" class="form-control bg-light border-0" placeholder="Max" value="{{ request('max_price') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex gap-2 justify-content-end border-top mt-4 pt-4">
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-light rounded-pill px-4 text-muted">Batal</a>
                                    <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Terapkan Filter</button>
                                </div>
                            </form>
                        </div>

                    {{-- ========================================================== --}}
                    {{-- MODE DAFTAR TABEL (TAMPILAN DEFAULT) --}}
                    {{-- ========================================================== --}}
                    @else
                        {{-- Header Table (Div Based) --}}
                        <div class="d-none d-md-flex bg-light text-muted small fw-bold py-3 px-4 border-bottom">
                            <div style="flex: 2;">INFO PRODUK</div>
                            <div class="text-center" style="flex: 1;">KATEGORI</div>
                            <div class="text-center" style="flex: 1;">STATUS</div>
                            <div class="text-center" style="flex: 1;">HARGA & STOK</div>
                            <div class="text-end" style="flex: 1;">AKSI</div>
                        </div>

                        {{-- Info filter aktif --}}
                        @if(request()->anyFilled(['search', 'category', 'status', 'min_price', 'max_price']))
                            <div class="bg-soft-primary px-4 py-2 border-bottom d-flex justify-content-between align-items-center">
                                <span class="small text-primary fw-medium">
                                    <i class="bi bi-info-circle me-1"></i> Menampilkan hasil filter pencarian
                                </span>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary rounded-pill py-0 px-2" style="font-size: 11px;">Reset Filter</a>
                            </div>
                        @endif

                        {{-- Data Rows --}}
                        @forelse($products as $product)
                            <div class="d-flex flex-column flex-md-row align-items-center py-3 px-4 border-bottom hover-row">
                                {{-- Info Produk --}}
                                <div class="d-flex align-items-center mb-3 mb-md-0" style="flex: 2; width: 100%;">
                                    <div class="me-3">
                                        @if($product->primaryImage)
                                            <img src="{{ $product->primaryImage->image_url }}" class="rounded-3 shadow-sm product-thumb" width="48" height="48" style="object-fit: cover;" onerror="this.onerror=null;this.src='https://placehold.co/48x48?text=No+Img';">
                                        @else
                                            <div class="bg-soft-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                <i class="bi bi-box-seam text-primary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="text-dark fw-bold mb-0">
                                            {{ $product->name }}
                                            @if($product->is_featured)
                                                <i class="bi bi-star-fill text-warning small ms-1" title="Produk Unggulan"></i>
                                            @endif
                                        </div>
                                        <span class="text-muted small">Berat: {{ $product->weight }}g</span>
                                    </div>
                                </div>

                                {{-- Kategori --}}
                                <div class="text-center mb-2 mb-md-0" style="flex: 1; width: 100%;">
                                    <span class="badge rounded-pill bg-light text-primary border px-3">
                                        {{ $product->category->name }}
                                    </span>
                                </div>

                                {{-- Status --}}
                                <div class="text-center mb-2 mb-md-0" style="flex: 1; width: 100%;">
                                    @if($product->is_active)
                                        <span class="badge bg-soft-success text-success px-3"><i class="bi bi-dot"></i> Aktif</span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary px-3"><i class="bi bi-dot"></i> Non-Aktif</span>
                                    @endif
                                </div>

                                {{-- Harga & Stok --}}
                                <div class="text-center mb-3 mb-md-0" style="flex: 1; width: 100%;">
                                    <div class="fw-bold text-dark">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                                    <small class="{{ $product->stock <= 5 ? 'text-danger fw-bold' : 'text-muted' }}">
                                        @if($product->stock <= 5)<i class="bi bi-exclamation-circle me-1"></i>@endif Stok: {{ $product->stock }}
                                    </small>
                                </div>

                                {{-- Aksi --}}
                                <div class="text-end" style="flex: 1; width: 100%;">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-light-warning text-warning me-2" data-bs-toggle="modal" data-bs-target="#editModal{{ $product->id }}" title="Edit">
                                            <i class="bi bi-pencil-square"></i>
                                        </button>

                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf 
                                            @method('DELETE')
                                            {{-- MEMBAWA SEMUA FILTER AKTIF --}}
                                            <input type="hidden" name="search" value="{{ request('search') }}">
                                            <input type="hidden" name="category" value="{{ request('category') }}">
                                            <input type="hidden" name="status" value="{{ request('status') }}">
                                            <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                            <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                            
                                            <button type="submit" class="btn btn-sm btn-light-danger text-danger" title="Hapus">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <i class="bi bi-box-seam display-4 text-light"></i>
                                <p class="text-muted mt-3">Produk tidak ditemukan.</p>
                                @if(request()->anyFilled(['search', 'category', 'status']))
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary rounded-pill">Reset Filter</a>
                                @endif
                            </div>
                        @endforelse

                        <div class="card-footer bg-white border-0 py-3 text-center">
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
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
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body px-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Nama Produk</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" placeholder="Masukkan nama produk" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Kategori</label>
                            <select name="category_id" class="form-select form-select-lg bg-light border-0" required>
                                <option value="">Pilih...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control bg-light border-0" placeholder="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Stok</label>
                            <input type="number" name="stock" class="form-control bg-light border-0" placeholder="0" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Berat (Gram)</label>
                            <input type="number" name="weight" class="form-control bg-light border-0" placeholder="0" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Gambar Utama</label>
                        <input type="file" name="images[]" class="form-control border-0 bg-light">
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

{{-- MODAL EDIT --}}
@foreach($products as $product)
<div class="modal fade" id="editModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf @method('PUT')
                <div class="modal-body px-4">
                    <div class="row g-3 mb-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold small">Nama Produk</label>
                            <input type="text" name="name" class="form-control form-control-lg bg-light border-0" value="{{ $product->name }}" required>
                        </div>
                        <div class="col-md-4">
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
                    <div class="row g-3 mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Harga (Rp)</label>
                            <input type="number" name="price" class="form-control bg-light border-0" value="{{ $product->price }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Stok</label>
                            <input type="number" name="stock" class="form-control bg-light border-0" value="{{ $product->stock }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold small">Berat (Gram)</label>
                            <input type="number" name="weight" class="form-control bg-light border-0" value="{{ $product->weight }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">Ganti Gambar (Opsional)</label>
                        <input type="file" name="images[]" class="form-control border-0 bg-light">
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center justify-content-between">
                                <label class="form-check-label fw-semibold small mb-0">Produk Aktif</label>
                                <input class="form-check-input ms-0" type="checkbox" name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch p-3 bg-light rounded-3 d-flex align-items-center justify-content-between border border-warning">
                                <label class="form-check-label fw-semibold small mb-0">
                                    <i class="bi bi-star-fill text-warning me-1"></i> Produk Unggulan
                                </label>
                                <input class="form-check-input ms-0" type="checkbox" name="is_featured" value="1" {{ $product->is_featured ? 'checked' : '' }}>
                            </div>
                        </div>
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
    .bg-soft-success { background-color: #e8fadf; }
    .bg-soft-primary { background-color: #e0eaff; }
    .bg-soft-secondary { background-color: #f1f3f5; }
    .btn-light-warning { background-color: #fff8dd; border: none; }
    .btn-light-danger { background-color: #fff5f8; border: none; }
    .hover-row:hover { background-color: #f8f9fa; cursor: default; }
    .product-thumb { transition: transform .2s ease; }
    .product-thumb:hover { transform: scale(1.1); }
    .form-control:focus, .form-select:focus { 
        background-color: #fff !important; 
        border: 1px solid #0d6efd !important; 
        box-shadow: none; 
    }
    .alert-flat { border-radius: 10px; }
    
</style>
@endsection