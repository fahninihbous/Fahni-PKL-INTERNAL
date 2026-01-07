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

<style>
    /* Custom Styling for Modern Look */
    .bg-soft-primary { background-color: #e0eaff !important; }
    .bg-soft-success { background-color: #e8fadf !important; }
    .bg-soft-warning { background-color: #fff8dd !important; }
    .bg-soft-danger { background-color: #fff5f8 !important; }
    .bg-soft-info { background-color: #e1f5fe !important; }
    .bg-soft-secondary { background-color: #f1f3f5 !important; }

    .table thead th {
        font-size: 11px;
        letter-spacing: 0.05rem;
        font-weight: 700;
        border: none;
    }

    .table tbody tr {
        transition: all 0.2s ease;
    }

    .table tbody tr:hover {
        background-color: #fbfcfe;
        transform: scale(1.002);
    }

    .btn-light {
        background-color: #f8fafc;
        transition: all 0.3s;
    }

    .btn-light:hover {
        background-color: #0d6efd !important;
        color: white !important;
    }

    .card {
        border-radius: 15px;
    }
</style>
@endsection