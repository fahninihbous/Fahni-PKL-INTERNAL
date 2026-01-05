@extends('layouts.app')

@section('title', 'Menunggu Pembayaran')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card border-0 shadow-lg rounded-4 text-center">
                <div class="card-body p-5">

                    {{-- Icon --}}
                    <div class="mb-4">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center"
                             style="width: 90px; height: 90px;">
                            <i class="bi bi-hourglass-split text-warning" style="font-size: 3.5rem;"></i>
                        </div>
                    </div>

                    <h2 class="fw-bold text-warning mb-2">
                        Menunggu Pembayaran ⏳
                    </h2>

                    <p class="text-muted mb-4">
                        Pesanan
                        <span class="fw-semibold">#{{ $order->order_number }}</span>
                        masih menunggu penyelesaian pembayaran.
                    </p>

                    <div class="border rounded-3 p-3 mb-4 bg-light">
                        <p class="mb-1 text-muted">Total Pembayaran</p>
                        <h4 class="fw-bold text-primary mb-0">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </h4>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('orders.show', $order) }}"
                           class="btn btn-warning btn-lg rounded-3 text-white">
                            Lanjutkan Pembayaran
                        </a>

                        <a href="{{ route('orders.index') }}"
                           class="btn btn-outline-secondary rounded-3">
                            Kembali ke Pesanan Saya
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
