@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    {{-- Breadcrumb Modern --}}
    <nav aria-label="breadcrumb" class="mb-5">
        <ol class="breadcrumb bg-light p-3 rounded-pill shadow-sm px-4">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-success text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog.index') }}" class="text-success text-decoration-none">Katalog</a></li>
            <li class="breadcrumb-item active fw-bold text-dark">{{ Str::limit($product->name, 20) }}</li>
        </ol>
    </nav>

    <div class="row g-5">
        {{-- SISI KIRI: Image Gallery --}}
        <div class="col-lg-6">
            <div class="sticky-top" style="top: 20px;">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden mb-3">
                    <div class="position-relative bg-white p-4">
                        <img src="{{ $product->image_url }}" id="main-image" class="img-fluid w-100" alt="{{ $product->name }}" style="height: 500px; object-fit: contain; transition: transform 0.3s ease;">
                        
                        @if($product->has_discount)
                            <div class="position-absolute top-0 start-0 m-4">
                                <span class="badge bg-danger fs-5 px-3 py-2 rounded-pill shadow">
                                    -{{ $product->discount_percentage }}%
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                @if($product->images->count() > 1)
                    <div class="d-flex gap-3 overflow-auto pb-2 px-1">
                        @foreach($product->images as $image)
                            <div class="thumb-container border rounded-3 p-1 bg-white shadow-sm cursor-pointer" onclick="changeMainImage('{{ asset('storage/' . $image->image_path) }}')">
                                <img src="{{ asset('storage/' . $image->image_path) }}" class="rounded-2" style="width: 70px; height: 70px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- SISI KANAN: Detail & Actions --}}
        <div class="col-lg-6">
            <div class="ps-lg-4">
                <div class="mb-3">
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-3 py-2 rounded-pill fw-bold">
                        <i class="bi bi-tag-fill me-1"></i> {{ $product->category->name }}
                    </span>
                </div>
                
                <h1 class="display-5 fw-bold text-dark mb-3">{{ $product->name }}</h1>

                <div class="d-flex align-items-baseline gap-3 mb-4">
                    <h2 class="fw-bold text-primary mb-0 fs-1">{{ $product->formatted_price }}</h2>
                    @if($product->has_discount)
                        <span class="text-muted text-decoration-line-through fs-4">{{ $product->formatted_original_price }}</span>
                    @endif
                </div>

                <div class="card border-0 bg-light rounded-4 p-4 mb-4">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small text-uppercase ls-1">Ketersediaan Stok</label>
                        <div>
                            @if($product->stock > 10)
                                <span class="badge bg-success px-3 py-2 rounded-pill"><i class="bi bi-check-circle-fill me-2"></i>Stok Ready</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="bi bi-clock-history me-2"></i>Tersisa {{ $product->stock }} Item</span>
                            @else
                                <span class="badge bg-danger px-3 py-2 rounded-pill"><i class="bi bi-x-circle-fill me-2"></i>Habis</span>
                            @endif
                        </div>
                    </div>

                    {{-- Form Add To Cart --}}
                    <form action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-muted small text-uppercase">Jumlah</label>
                                <div class="input-group input-group-lg border rounded-pill overflow-hidden bg-white shadow-sm">
                                    <button type="button" class="btn btn-white border-0 px-3" onclick="decrementQty()"><i class="bi bi-dash"></i></button>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control text-center border-0 fw-bold" readonly>
                                    <button type="button" class="btn btn-white border-0 px-3" onclick="incrementQty()"><i class="bi bi-plus"></i></button>
                                </div>
                            </div>
                            <div class="col-md-8 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm py-3 transition-up" @if($product->stock == 0) disabled @endif>
                                    <i class="bi bi-bag-plus-fill me-2"></i> Masukkan Keranjang
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Tombol Wishlist --}}
                @auth
                    <button type="button" 
                            onclick="toggleWishlist({{ $product->id }})" 
                            class="btn {{ auth()->user()->hasInWishlist($product) ? 'btn-danger' : 'btn-outline-danger' }} wishlist-btn-{{ $product->id }} w-100 py-3 rounded-pill fw-bold mb-4 shadow-sm transition-up">
                        <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                        <span>{{ auth()->user()->hasInWishlist($product) ? 'Hapus dari Wishlist' : 'Tambah ke Wishlist' }}</span>
                    </button>
                @endauth

                {{-- Deskripsi Section --}}
                <div class="description-section mt-5">
                    <h5 class="fw-bold border-bottom pb-3 mb-3"><i class="bi bi-card-text me-2 text-primary"></i>Detail Produk</h5>
                    <p class="text-muted lh-lg">{!! nl2br(e($product->description)) !!}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .transition-up { transition: all 0.3s ease; }
    .transition-up:hover { transform: translateY(-3px); }
    .thumb-container { transition: all 0.2s; border: 2px solid transparent !important; }
    .thumb-container:hover { border-color: var(--bs-primary) !important; transform: scale(1.05); }
    #main-image:hover { transform: scale(1.02); }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; font-size: 1.2rem; vertical-align: middle; }
</style>
@endsection

@push('scripts')
<script>
    function changeMainImage(src) {
        const mainImg = document.getElementById('main-image');
        mainImg.style.opacity = '0';
        setTimeout(() => {
            mainImg.src = src;
            mainImg.style.opacity = '1';
        }, 200);
    }

    function incrementQty() {
        const input = document.getElementById('quantity');
        const max = parseInt(input.max);
        if (parseInt(input.value) < max) input.value = parseInt(input.value) + 1;
    }

    function decrementQty() {
        const input = document.getElementById('quantity');
        if (parseInt(input.value) > 1) input.value = parseInt(input.value) - 1;
    }
</script>
@endpush