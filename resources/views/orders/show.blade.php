@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="garden-detail-wrapper py-5">
    <div class="container">
        {{-- Header & Navigasi --}}
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
            <div>
                <a href="{{ route('orders.index') }}" class="btn btn-link text-garden-primary text-decoration-none fw-bold p-0 mb-2 d-inline-block">
                    <i class="bi bi-arrow-left-circle-fill me-2"></i>Kembali ke Pesanan Saya
                </a>
                <h1 class="h3 fw-bold text-garden-dark mb-0">Order #{{ $order->order_number }}</h1>
                <p class="text-muted small mb-0"><i class="bi bi-calendar3 me-1"></i> Dipesan pada {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
            
            @php
                $statusClasses = [
                    'pending' => ['bg' => '#fff3cd', 'text' => '#856404', 'icon' => 'bi-clock-history'],
                    'processing' => ['bg' => '#d1ecf1', 'text' => '#0c5460', 'icon' => 'bi-gear-fill'],
                    'shipped' => ['bg' => '#cfe2ff', 'text' => '#084298', 'icon' => 'bi-truck'],
                    'delivered' => ['bg' => '#d1e7dd', 'text' => '#0f5132', 'icon' => 'bi-check-circle-fill'],
                    'cancelled' => ['bg' => '#f8d7da', 'text' => '#842029', 'icon' => 'bi-x-circle-fill'],
                ];
                $currentStatus = $statusClasses[$order->status] ?? ['bg' => '#e2e3e5', 'text' => '#383d41', 'icon' => 'bi-info-circle'];
            @endphp
            
            <div class="status-badge-wrapper px-4 py-3 rounded-4 shadow-sm d-flex align-items-center" 
                 style="background-color: {{ $currentStatus['bg'] }}; color: {{ $currentStatus['text'] }}; border: 1px solid rgba(0,0,0,0.05)">
                <i class="bi {{ $currentStatus['icon'] }} fs-4 me-3"></i>
                <div>
                    <small class="d-block text-uppercase fw-bold ls-1" style="font-size: 0.7rem; opacity: 0.8">Status Pesanan</small>
                    <span class="fw-bold fs-5">{{ ucfirst($order->status) }}</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- KOLOM KIRI: DAFTAR PRODUK --}}
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold text-garden-dark mb-0">Rincian Produk</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4 py-3 text-muted small text-uppercase">Produk</th>
                                        <th class="py-3 text-center text-muted small text-uppercase">Qty</th>
                                        <th class="pe-4 py-3 text-end text-muted small text-uppercase">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="product-icon-box bg-garden-light rounded-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                    <i class="bi bi-box-seam text-garden-primary fs-4"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-garden-dark">{{ $item->product_name }}</div>
                                                    <div class="text-muted small">Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold">{{ $item->quantity }}</td>
                                        <td class="pe-4 text-end fw-bold text-garden-primary">
                                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- INFO PEMBAYARAN (Hanya Tampil Jika Pending) --}}
                @if(isset($snapToken) && $order->status === 'pending')
                <div class="card border-0 shadow-sm rounded-4 bg-garden-dark text-white overflow-hidden">
                    <div class="card-body p-4 text-center">
                        <div class="mb-3">
                            <i class="bi bi-shield-lock fs-1 text-warning"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Menunggu Pembayaran</h5>
                        <p class="opacity-75 mb-4 px-md-5 small">Selesaikan pembayaran Anda sekarang agar pesanan dapat segera kami proses dan kirim ke alamat Anda.</p>
                        <button id="pay-button" class="btn btn-warning btn-lg px-5 py-3 rounded-pill fw-bold shadow-sm">
                            <i class="bi bi-credit-card-fill me-2"></i>Bayar Sekarang
                        </button>
                    </div>
                </div>
                @endif
            </div>

            {{-- KOLOM KANAN: RINGKASAN & ALAMAT --}}
            <div class="col-lg-4">
                {{-- Ringkasan Biaya --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-garden-dark mb-4">Ringkasan Biaya</h5>
                        
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Total Harga Barang</span>
                            <span>Rp {{ number_format($order->items->sum('subtotal'), 0, ',', '.') }}</span>
                        </div>
                        
                        @if($order->shipping_cost > 0)
                        <div class="d-flex justify-content-between mb-3 text-muted">
                            <span>Ongkos Kirim</span>
                            <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                        </div>
                        @endif

                        <hr class="my-4 opacity-10">

                        <div class="d-flex justify-content-between align-items-center mb-0">
                            <span class="h6 fw-bold mb-0">Total Bayar</span>
                            <span class="h4 fw-bold text-garden-primary mb-0">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Alamat Pengiriman --}}
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h5 class="fw-bold text-garden-dark mb-4">Informasi Pengiriman</h5>
                        
                        <div class="d-flex mb-4">
                            <div class="flex-shrink-0">
                                <i class="bi bi-person-badge text-garden-primary fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Nama Penerima</small>
                                <span class="fw-bold text-garden-dark d-block">{{ $order->shipping_name }}</span>
                                <span class="text-muted small"><i class="bi bi-telephone me-1"></i> {{ $order->shipping_phone }}</span>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="bi bi-geo-alt-fill text-garden-primary fs-4"></i>
                            </div>
                            <div class="ms-3">
                                <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.65rem;">Alamat Lengkap</small>
                                <span class="text-garden-dark small lh-sm">{{ $order->shipping_address }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* THEME COLORS */
    :root {
        --garden-primary: #2d5a27;
        --garden-light: #f3f6f2;
        --garden-dark: #1e351b;
        --garden-muted: #6b7c65;
    }

    .garden-detail-wrapper {
        background-color: var(--garden-light);
        min-height: 100vh;
    }

    .text-garden-primary { color: var(--garden-primary) !important; }
    .text-garden-dark { color: var(--garden-dark) !important; }
    .bg-garden-light { background-color: #e9f0e8 !important; }
    .bg-garden-dark { background-color: var(--garden-dark) !important; }
    
    .ls-1 { letter-spacing: 1px; }

    /* CARD STYLING */
    .card { transition: transform 0.2s; }
    
    /* TABLE STYLING */
    .table thead th { border-bottom: none; }
    .table td { border-color: rgba(0,0,0,0.03); }

    /* BUTTON STYLING */
    .btn-warning {
        background-color: #f8c244;
        border: none;
        color: #5d4400;
    }
    .btn-warning:hover {
        background-color: #eab12b;
        color: #5d4400;
        transform: translateY(-2px);
    }
</style>

@if(isset($snapToken))
    @push('scripts')
        <script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                const payButton = document.getElementById('pay-button');

                if (payButton) {
                    payButton.addEventListener('click', function() {
                        payButton.disabled = true;
                        payButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses...';

                        window.snap.pay('{{ $snapToken }}', {
                            onSuccess: function(result) {
                                window.location.href = '{{ route("orders.success", $order) }}';
                            },
                            onPending: function(result) {
                                window.location.href = '{{ route("orders.pending", $order) }}';
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal! Silakan coba lagi.');
                                payButton.disabled = false;
                                payButton.innerHTML = '💳 Bayar Sekarang';
                            },
                            onClose: function() {
                                payButton.disabled = false;
                                payButton.innerHTML = '💳 Bayar Sekarang';
                            }
                        });
                    });
                }
            });
        </script>
    @endpush
@endif
@endsection