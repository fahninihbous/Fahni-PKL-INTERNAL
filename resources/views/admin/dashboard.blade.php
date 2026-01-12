@extends('layouts.admin')

@section('title', 'Garden Admin Dashboard')

@section('content')
<div class="container-fluid py-4 px-4" style="background-color: #f3f7f2; min-height: 100vh; font-family: 'Quicksand', sans-serif;">
    
    {{-- Header dengan nuansa Alam --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h3 class="fw-bold" style="color: #2d4a22; letter-spacing: -0.5px;">
                <i class="bi bi-leaf-fill me-2" style="color: #588157;"></i>Ringkasan Kebun
            </h3>
            <p class="text-muted mb-0 small">Pantau pertumbuhan bisnis Anda hari ini.</p>
        </div>
    </div>

    <div class="row g-4">
        {{-- SISI KIRI: Fokus pada Grafik & Produk --}}
        <div class="col-lg-8">
            
            {{-- Stats Row --}}
            <div class="row g-3 mb-4">
                {{-- Revenue Card --}}
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm overflow-hidden h-100" style="background: linear-gradient(135deg, #344e41 0%, #588157 100%); border-radius: 20px;">
                        <div class="card-body p-4 text-white position-relative">
                            <h6 class="text-white-50 text-uppercase fw-bold small">Total Pendapatan</h6>
                            <h2 class="fw-bold mb-0">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</h2>
                            <i class="bi bi-flower1 position-absolute end-0 bottom-0 mb-n3 me-n2 opacity-25" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                </div>
                {{-- Sub Stats --}}
                <div class="col-md-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card border-0 shadow-sm rounded-4 text-center p-3 h-100 bg-white">
                                <div class="text-warning mb-1"><i class="bi bi-droplets-fill fs-4"></i></div>
                                <h4 class="fw-bold mb-0 text-dark">{{ $stats['pending_orders'] }}</h4>
                                <small class="text-muted fw-semibold" style="font-size: 0.65rem;">PERLU DIPROSES</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 shadow-sm rounded-4 text-center p-3 h-100 bg-white">
                                <div class="text-danger mb-1"><i class="bi bi-tree-fill fs-4"></i></div>
                                <h4 class="fw-bold mb-0 text-dark">{{ $stats['low_stock'] }}</h4>
                                <small class="text-muted fw-semibold" style="font-size: 0.65rem;">STOK MENIPIS</small>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white d-flex flex-row align-items-center justify-content-between">
                                <div class="ps-2">
                                    <h5 class="fw-bold mb-0 text-dark">{{ $stats['total_products'] }}</h5>
                                    <small class="text-muted fw-semibold" style="font-size: 0.65rem;">TOTAL KOLEKSI PRODUK</small>
                                </div>
                                <div class="bg-success bg-opacity-10 p-2 rounded-3">
                                    <i class="bi bi-tags-fill text-success fs-4"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Chart Card --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold" style="color: #2d4a22;">Statistik Penjualan</h5>
                    <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2">7 Hari Terakhir</div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div style="height: 300px;">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
            </div>

            {{-- Produk Terlaris (Grid Layout di Bawah) --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold" style="color: #2d4a22;">Produk Terlaris</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3">
                        @foreach($topProducts as $product)
                        <div class="col text-center">
                            <div class="p-2 border border-light rounded-4 hover-garden transition-all h-100">
                                <img src="{{ $product->image_url }}" class="rounded-3 mb-2 shadow-sm w-100" style="height: 80px; object-fit: cover;">
                                <h6 class="text-truncate small fw-bold mb-1">{{ $product->name }}</h6>
                                <span class="badge bg-sage text-white rounded-pill" style="font-size: 0.6rem; background-color: #a3b18a;">{{ $product->sold }} Terjual</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- SISI KANAN: Aktivitas Pesanan --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
                <div class="card-header border-0 pt-4 px-4 bg-white">
                    <h5 class="fw-bold mb-0" style="color: #2d4a22;">Pesanan Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @foreach($recentOrders as $order)
                        <div class="list-group-item border-light px-4 py-3 hover-garden-item transition-all">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="fw-bold text-dark" style="font-size: 0.9rem;">#{{ $order->order_number }}</span>
                                <span class="fw-bold text-success" style="font-size: 0.85rem;">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <small class="text-muted"><i class="bi bi-person me-1"></i>{{ Str::limit($order->user->name, 15) }}</small>
                                <span class="badge {{ $order->status == 'completed' ? 'bg-success' : 'bg-light text-dark' }} rounded-pill" style="font-size: 0.6rem;">
                                    {{ strtoupper($order->status) }}
                                </span>
                            </div>
                            <a href="{{ route('admin.orders.show', $order) }}" class="stretched-link"></a>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-4 text-center">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
                        LIHAT SEMUA PESANAN
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600;700&display=swap');

    .transition-all { transition: all 0.3s ease; }
    
    /* Hover Effects */
    .hover-garden:hover {
        background-color: #f8faf7;
        border-color: #a3b18a !important;
        transform: translateY(-5px);
    }
    
    .hover-garden-item:hover {
        background-color: #f1f5ef;
        padding-left: 1.75rem !important;
    }

    .card { border-radius: 20px; }
    
    /* Custom Progress Bar Style if needed */
    .bg-sage { background-color: #a3b18a; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('revenueChart').getContext('2d');
        
        // Gradient Hijau Alam
        const gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(88, 129, 87, 0.3)');
        gradient.addColorStop(1, 'rgba(88, 129, 87, 0)');

        const labels = {!! json_encode($revenueChart->pluck('date')) !!};
        const data = {!! json_encode($revenueChart->pluck('total')) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: data,
                    borderColor: '#344e41',
                    borderWidth: 3,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#588157',
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true,
                    backgroundColor: gradient
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#e9ede8' },
                        ticks: {
                            color: '#588157',
                            callback: v => 'Rp' + new Intl.NumberFormat('id-ID', {notation: 'compact'}).format(v)
                        }
                    },
                    x: { grid: { display: false }, ticks: { color: '#588157' } }
                }
            }
        });
    });
</script>
@endsection