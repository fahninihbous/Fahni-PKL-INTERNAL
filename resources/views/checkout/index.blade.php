@extends('layouts.app')

@section('title', 'Proses Checkout')

@section('content')
<div class="garden-checkout-wrapper py-5">
    <div class="container">
        {{-- Progress Header --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8 text-center">
                <h1 class="display-6 fw-bold text-garden-dark mb-2">Penyelesaian Pesanan</h1>
                <p class="text-garden-muted">Satu langkah lagi sebelum produk favoritmu sampai di rumah.</p>
                <div class="checkout-progress d-flex justify-content-center align-items-center mt-4">
                    <div class="step active"><i class="bi bi-cart-check"></i></div>
                    <div class="line"></div>
                    <div class="step active"><i class="bi bi-truck"></i></div>
                    <div class="line"></div>
                    <div class="step"><i class="bi bi-credit-card"></i></div>
                </div>
            </div>
        </div>

        <form action="{{ route('checkout.store') }}" method="POST">
            @csrf
            <div class="row g-4">
                {{-- SISI KIRI: DATA PENGIRIMAN --}}
                <div class="col-lg-7">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                        <div class="card-header bg-garden-dark text-white py-3 px-4 border-0">
                            <h5 class="m-0 fw-bold"><i class="bi bi-geo-alt me-2"></i>Alamat Pengiriman</h5>
                        </div>
                        <div class="card-body p-4 bg-white">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-garden-primary small text-uppercase">Nama Penerima</label>
                                    <input type="text" name="name" value="{{ auth()->user()->name }}" 
                                           class="form-control garden-input shadow-none" placeholder="Masukkan nama lengkap" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-garden-primary small text-uppercase">Nomor Telepon</label>
                                    <input type="text" name="phone" class="form-control garden-input shadow-none" 
                                           placeholder="Contoh: 08123456789" required>
                                </div>
                                <div class="col-12 mb-3">
                                    <label class="form-label fw-bold text-garden-primary small text-uppercase">Alamat Lengkap</label>
                                    <textarea name="address" rows="4" class="form-control garden-input shadow-none" 
                                              placeholder="Tuliskan nama jalan, nomor rumah, RT/RW, dan patokan..." required></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="p-3 rounded-3 bg-garden-light border-start border-4 border-garden-primary">
                                        <small class="text-garden-muted d-block">
                                            <i class="bi bi-info-circle-fill me-1"></i> Pastikan alamat sudah benar untuk menghindari kendala saat pengiriman oleh kurir kami.
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- SISI KANAN: RINGKASAN PESANAN --}}
                <div class="col-lg-5">
                    <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 100px;">
                        <div class="card-body p-4">
                            <h5 class="fw-bold text-garden-dark mb-4 pb-2 border-bottom">Ringkasan Pesanan</h5>
                            
                            <div class="order-items-list mb-4" style="max-height: 300px; overflow-y:auto;">
                                @foreach($cart->items as $item)
                                <div class="d-flex align-items-center mb-3">
                                    <div class="flex-shrink-0 position-relative">
                                        <img src="{{ $item->product->image_url ?? asset('storage/' . $item->product->image) }}" 
                                                class="rounded-3 border" 
                                                style="width: 60px; height: 60px; object-fit: cover;"
                                                alt="{{ $item->product->name }}">
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-garden-primary">
                                            {{ $item->quantity }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="small fw-bold text-garden-dark mb-0">{{ $item->product->name }}</h6>
                                        <span class="text-muted extra-small">Rp {{ number_format($item->product->price, 0, ',', '.') }} / unit</span>
                                    </div>
                                    <div class="text-end">
                                        <span class="fw-bold text-garden-primary small">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="calculation-box bg-garden-light p-3 rounded-4 mb-4">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-garden-muted">Subtotal</span>
                                    <span class="text-garden-dark fw-semibold">Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-garden-muted">Biaya Pengiriman</span>
                                    <span class="text-success fw-bold">Gratis</span>
                                </div>
                                <hr class="my-2 opacity-10">
                                <div class="d-flex justify-content-between align-items-center mt-2">
                                    <span class="h6 fw-bold text-garden-dark mb-0">Total Tagihan</span>
                                    <span class="h5 fw-bold text-garden-primary mb-0">
                                        Rp {{ number_format($cart->items->sum('subtotal'), 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-garden w-100 py-3 rounded-pill fw-bold shadow">
                                <i class="bi bi-lock-fill me-2"></i>Selesaikan Pesanan Sekarang
                            </button>
                            
                            <p class="text-center text-garden-muted mt-3 extra-small">
                                <i class="bi bi-shield-check me-1"></i> Pembayaran Aman & Terenkripsi
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    /* THEME GARDEN BASE */
    :root {
        --garden-primary: #2d5a27;
        --garden-light: #f3f6f2;
        --garden-dark: #1e351b;
        --garden-muted: #6b7c65;
    }

    .garden-checkout-wrapper {
        background-color: var(--garden-light);
        min-height: 100vh;
    }

    .bg-garden-dark { background-color: var(--garden-dark) !important; }
    .bg-garden-light { background-color: #e9f0e8 !important; }
    .text-garden-primary { color: var(--garden-primary) !important; }
    .text-garden-dark { color: var(--garden-dark) !important; }
    .text-garden-muted { color: var(--garden-muted) !important; }

    /* FORM ELEMENTS */
    .garden-input {
        background-color: #fcfdfc;
        border: 1px solid #dae2da;
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.2s;
    }
    .garden-input:focus {
        border-color: var(--garden-primary);
        background-color: white;
        box-shadow: 0 0 0 4px rgba(45, 90, 39, 0.05);
    }

    /* PROGRESS BAR */
    .checkout-progress { gap: 10px; }
    .checkout-progress .step {
        width: 40px; height: 40px;
        background: white;
        border: 2px solid #dae2da;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        color: var(--garden-muted);
        font-size: 1.2rem;
    }
    .checkout-progress .step.active {
        background: var(--garden-primary);
        border-color: var(--garden-primary);
        color: white;
    }
    .checkout-progress .line {
        height: 2px; width: 40px;
        background: #dae2da;
    }

    /* BUTTON */
    .btn-garden {
        background-color: var(--garden-primary);
        color: white; border: none;
        transition: all 0.3s;
    }
    .btn-garden:hover {
        background-color: var(--garden-dark);
        color: white; transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(45, 90, 39, 0.2);
    }

    .extra-small { font-size: 0.75rem; }
    .ls-1 { letter-spacing: 1px; }

    /* Custom Scrollbar Ringkasan */
    .order-items-list::-webkit-scrollbar { width: 4px; }
    .order-items-list::-webkit-scrollbar-thumb { background: #dae2da; border-radius: 10px; }
</style>
@endsection