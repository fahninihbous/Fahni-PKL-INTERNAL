{{-- ================================================
     FILE: resources/views/cart/index.blade.php
     FUNGSI: Halaman keranjang belanja (Premium Look)
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h2 class="fw-bold text-dark mb-2">Keranjang Belanja</h2>
            <p class="text-muted">Pastikan barang impianmu sudah siap untuk dipesan.</p>
            <div class="mx-auto" style="width: 50px; height: 3px; background-color: var(--bs-primary); border-radius: 10px;"></div>
        </div>
    </div>

    @if($cart && $cart->items->count())
        <div class="row g-4">
            {{-- Cart Items List --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-3 mb-4 rounded-4">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead class="text-muted small text-uppercase">
                                    <tr>
                                        <th class="border-0 px-4 py-3">Produk</th>
                                        <th class="border-0 text-center py-3">Jumlah</th>
                                        <th class="border-0 text-end px-4 py-3">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cart->items as $item)
                                        <tr class="cart-item-row">
                                            <td class="px-4 py-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="position-relative">
                                                        <img src="{{ $item->product->image_url }}" 
                                                             class="rounded-3 shadow-sm" 
                                                             width="90" height="90" 
                                                             style="object-fit: cover;">
                                                        <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="position-absolute top-0 start-0 translate-middle">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow p-1" 
                                                                    onclick="return confirm('Hapus item ini?')"
                                                                    style="width: 24px; height: 24px; font-size: 10px;">
                                                                <i class="bi bi-x-lg"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                    <div class="ms-4">
                                                        <a href="{{ route('catalog.show', $item->product->slug) }}" 
                                                           class="text-decoration-none text-dark fw-bold mb-1 d-block hover-primary">
                                                            {{ Str::limit($item->product->name, 40) }}
                                                        </a>
                                                        <span class="badge bg-light text-muted fw-normal rounded-pill border">
                                                            {{ $item->product->category->name }}
                                                        </span>
                                                        <div class="text-primary small mt-1 fw-medium">
                                                            {{ $item->product->formatted_price }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center py-4">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="input-group input-group-sm qty-selector border rounded-pill overflow-hidden shadow-sm" style="width: 110px;">
                                                        <input type="number" name="quantity" 
                                                               value="{{ $item->quantity }}" 
                                                               min="1" max="{{ $item->product->stock }}" 
                                                               class="form-control border-0 text-center fw-bold bg-white" 
                                                               onchange="this.form.submit()">
                                                    </div>
                                                    <small class="text-muted mt-1 d-block" style="font-size: 10px;">Stok: {{ $item->product->stock }}</small>
                                                </form>
                                            </td>
                                            <td class="text-end px-4 py-4 fw-bold text-dark fs-6">
                                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                {{-- Action Left --}}
                <div class="d-flex justify-content-between align-items-center px-2">
                    <a href="{{ route('catalog.index') }}" class="text-decoration-none text-muted small fw-medium">
                        <i class="bi bi-arrow-left-short fs-5 align-middle"></i> Kembali Belanja
                    </a>
                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="col-lg-4">
                <div class="card border-0 shadow-lg rounded-4 overflow-hidden sticky-top" style="top: 2rem; z-index: 10;">
                    <div class="card-header bg-dark text-white p-4 border-0">
                        <h5 class="mb-0 fw-bold">Ringkasan Pesanan</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Barang</span>
                            <span class="fw-medium text-dark">{{ $cart->items->sum('quantity') }} Unit</span>
                        </div>
                        <div class="d-flex justify-content-between mb-4 text-muted">
                            <span>Subtotal</span>
                            <span class="fw-medium text-dark">Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="bg-light p-3 rounded-3 mb-4 border border-dashed border-secondary border-opacity-25">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-dark">Total Tagihan</span>
                                <span class="fw-bold text-primary fs-4">
                                    Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm mb-2 transition-up">
                            Lanjut ke Pembayaran <i class="bi bi-arrow-right-short ms-2"></i>
                        </a>
                        
                        <div class="text-center mt-3">
                            <p class="small text-muted"><i class="bi bi-shield-check me-1 text-success"></i> Pembayaran Aman & Terenkripsi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        {{-- Empty Cart - Enhanced --}}
        <div class="row justify-content-center">
            <div class="col-md-6 text-center py-5">
                <div class="empty-cart-animation mb-4 position-relative">
                    <i class="bi bi-cart3 display-1 text-light"></i>
                    <i class="bi bi-search position-absolute top-50 start-50 translate-middle fs-2 text-primary opacity-50"></i>
                </div>
                <h3 class="fw-bold">Keranjangmu Kosong</h3>
                <p class="text-muted px-lg-5">Sepertinya kamu belum memilih produk favoritmu. Yuk, jelajahi katalog kami sekarang!</p>
                <a href="{{ route('catalog.index') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow mt-3">
                    Mulai Belanja Sekarang
                </a>
            </div>
        </div>
    @endif
</div>

<style>
    /* Custom Stylings */
    .cart-item-row {
        transition: all 0.2s ease;
    }
    .cart-item-row:hover {
        background-color: #fbfbfb;
    }
    .hover-primary:hover {
        color: var(--bs-primary) !important;
    }
    .qty-selector input::-webkit-outer-spin-button,
    .qty-selector input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .qty-selector input[type=number] {
        -moz-appearance: textfield;
    }
    .transition-up:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .border-dashed {
        border-style: dashed !important;
    }
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endsection