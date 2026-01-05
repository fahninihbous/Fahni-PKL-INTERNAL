@extends('layouts.admin')

@section('title', 'Manajemen Produk')

@push('styles')
<style>
    .product-thumb {
        transition: transform .2s ease;
    }
    .product-thumb:hover {
        transform: scale(1.08);
    }
    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary fw-bold">Daftar Produk</h5>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createModal">
                    <i class="bi bi-plus-lg"></i> Tambah Baru
                </button>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Produk</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Berat</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Harga</th>
                                <th class="text-center">Stok</th>
                                <th class="text-end pe-4">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        @if($product->primaryImage)
                                            <img src="{{ $product->primaryImage->image_url }}"
                                                 class="rounded product-thumb me-3 shadow-sm"
                                                 width="48" height="48"
                                                 style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3 shadow-sm"
                                                 style="width:48px;height:48px;">
                                                <i class="bi bi-image text-muted fs-4"></i>
                                            </div>
                                        @endif

                                        <div>
                                            <div class="fw-bold">{{ $product->name }}</div>
                                            <small class="text-muted">{{ $product->slug }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-primary">{{ $product->category->name }}</span>
                                </td>

                                <td class="text-center">
                                    <span class="badge bg-warning text-dark">{{ $product->weight }} g</span>
                                </td>

                                <td class="text-center">
                                    @if($product->is_active)
                                        <span class="badge rounded-pill bg-success px-3">Aktif</span>
                                    @else
                                        <span class="badge rounded-pill bg-secondary px-3">Nonaktif</span>
                                    @endif
                                </td>

                                <td class="text-center fw-bold text-danger">
                                    Rp {{ number_format($product->price, 0, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    @if($product->stock <= 5)
                                        <span class="badge bg-danger">⚠ {{ $product->stock }}</span>
                                    @else
                                        <span class="badge bg-info">{{ $product->stock }}</span>
                                    @endif
                                </td>

                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editProduk{{ $product->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>

                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus produk ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="bi bi-box-seam fs-1 text-muted"></i>
                                    <p class="mt-3 text-muted fw-semibold">
                                        Belum ada produk yang ditambahkan
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="card-footer bg-white">
                {{ $products->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>

{{-- ===================== MODAL EDIT ===================== --}}
@foreach($products as $product)
<div class="modal fade" id="editProduk{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content"
              action="{{ route('admin.products.update', $product) }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="category_id" class="form-select">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Harga</label>
                        <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Stok</label>
                        <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Berat (gram)</label>
                        <input type="number" name="weight" class="form-control" value="{{ $product->weight }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Gambar</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1"
                        {{ $product->is_active ? 'checked' : '' }}>
                    <label class="form-check-label">Aktif</label>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" type="submit">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

{{-- ===================== MODAL CREATE ===================== --}}
<div class="modal fade" id="createModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form class="modal-content"
              action="{{ route('admin.products.store') }}"
              method="POST"
              enctype="multipart/form-data">
            @csrf

            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Nama Produk</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Kategori</label>
                    <select name="category_id" class="form-select" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Harga</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Stok</label>
                        <input type="number" name="stock" class="form-control" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Berat (gram)</label>
                        <input type="number" name="weight" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Gambar</label>
                        <input type="file" name="image" class="form-control">
                    </div>
                </div>

                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1">
                    <label class="form-check-label">Aktif</label>
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>
@endsection
