@extends('layouts.app')

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
    <div class="d-flex align-items-center mb-4">
        <div class="bg-primary text-white rounded-3 p-3 me-3 shadow-sm">
            <i class="bi bi-bag-check-fill fs-4"></i>
        </div>
        <div>
            <h1 class="h3 mb-1 fw-bold text-dark">Daftar Pesanan Saya</h1>
            <p class="text-muted mb-0">Pantau status pengiriman dan riwayat belanja Anda</p>
        </div>
    </div>

    {{-- Main Card --}}
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small uppercase">
                        <tr>
                            <th class="ps-4 py-3">NO. ORDER</th>
                            <th>TANGGAL</th>
                            <th>STATUS</th>
                            <th>TOTAL</th>
                            <th class="text-end pe-4">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            {{-- No. Order dengan Badge Kecil --}}
                            <td class="ps-4">
                                <span class="fw-bold text-dark">#{{ $order->order_number }}</span>
                            </td>
                            
                            {{-- Tanggal dengan Format Lebih Bersih --}}
                            <td>
                                <div class="text-dark">{{ $order->created_at->format('d M Y') }}</div>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ $order->created_at->format('H:i') }} WIB</small>
                            </td>

                            {{-- Status dengan Soft Badges --}}
                            <td>
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-soft-warning text-warning',
                                        'processing' => 'bg-soft-info text-info',
                                        'shipped' => 'bg-soft-primary text-primary',
                                        'delivered' => 'bg-soft-success text-success',
                                        'cancelled' => 'bg-soft-danger text-danger',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Menunggu',
                                        'processing' => 'Diproses',
                                        'shipped' => 'Dikirim',
                                        'delivered' => 'Sampai',
                                        'cancelled' => 'Batal',
                                    ];
                                    $class = $statusClasses[$order->status] ?? 'bg-soft-secondary text-secondary';
                                    $label = $statusLabels[$order->status] ?? ucfirst($order->status);
                                @endphp
                                <span class="badge {{ $class }} px-3 py-2 rounded-pill fw-bold" style="font-size: 0.75rem;">
                                    {{ $label }}
                                </span>
                            </td>

                            {{-- Total Harga --}}
                            <td>
                                <span class="fw-bold text-dark">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </td>

                            {{-- Tombol Aksi --}}
                            <td class="text-end pe-4">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-light border-0 px-3 fw-bold text-primary rounded-pill shadow-sm">
                                    <i class="bi bi-eye-fill me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="py-4">
                                    <img src="https://cdn-icons-png.flaticon.com/512/7486/7486744.png" alt="Empty" style="width: 80px; opacity: 0.3;">
                                    <p class="text-muted mt-3 mb-0">Belum ada pesanan ditemukan.</p>
                                    <a href="/" class="btn btn-primary btn-sm mt-3 rounded-pill px-4">Mulai Belanja</a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination --}}
        @if($orders->hasPages())
        <div class="card-footer bg-white border-0 py-4">
            <div class="d-flex justify-content-center">
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
<style>
    /* 1. Global & Header Styling */
    body {
        background-color: #f8f9fa;
    }

    h1.h3 {
        color: #2d422d !important; /* Hijau Hutan Aden */
    }

    .bg-primary {
        background-color: #6ab04c !important; /* Hijau Daun Utama */
        box-shadow: 0 4px 15px rgba(106, 176, 76, 0.3) !important;
    }

    /* 2. Card & Table Container */
    .card.rounded-4 {
        border-radius: 20px !important;
        border: 1px solid #eef3ed !important;
        background-color: #ffffff;
    }

    .table thead {
        background-color: #fdfcf8 !important;
        border-bottom: 2px solid #eef3ed;
    }

    .table thead th {
        font-size: 11px;
        letter-spacing: 1.2px;
        font-weight: 700;
        color: #5d715d;
        padding: 15px 10px;
    }

    /* 3. Soft Status Badges */
    .bg-soft-warning { background-color: #fff8dd !important; color: #856404 !important; }
    .bg-soft-info { background-color: #e0f7fa !important; color: #006064 !important; }
    .bg-soft-primary { background-color: #e7f3ff !important; color: #0061f2 !important; }
    .bg-soft-success { background-color: #e8fadf !important; color: #2d422d !important; }
    .bg-soft-danger { background-color: #fff5f8 !important; color: #b91d1d !important; }
    .bg-soft-secondary { background-color: #f1f3f5 !important; color: #495057 !important; }

    .badge {
        letter-spacing: 0.3px;
    }

    /* 4. Action Button Styling */
    .btn-light.text-primary {
        background-color: #f1f8e9 !important;
        color: #6ab04c !important;
        border: 1px solid #dcedc8 !important;
        transition: all 0.3s ease;
    }

    .btn-light.text-primary:hover {
        background-color: #6ab04c !important;
        color: #ffffff !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(106, 176, 76, 0.2) !important;
    }

    /* 5. Typography & Details */
    .text-dark.fw-bold {
        color: #2d3436 !important;
    }

    .table-hover tbody tr {
        transition: background-color 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: #fafdfa;
    }

    /* 6. Empty State Button */
    .btn-primary.rounded-pill {
        background-color: #6ab04c !important;
        border: none;
        font-weight: 600;
        padding: 10px 25px;
    }

    .btn-primary.rounded-pill:hover {
        background-color: #58943f !important;
        transform: scale(1.05);
    }

    /* 7. Pagination Styling */
    .pagination .page-link {
        color: #6ab04c;
        border-radius: 8px;
        margin: 0 3px;
        border: none;
        background: #f1f8e9;
    }

    .pagination .page-item.active .page-link {
        background-color: #6ab04c;
        color: white;
    }
</style>