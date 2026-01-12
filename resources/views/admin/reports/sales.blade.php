@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid py-4">
    {{-- HEADER SECTION --}}
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="h3 fw-bold text-dark mb-1">Analisis Penjualan</h2>
            <p class="text-muted mb-0">Pantau performa bisnis dan pertumbuhan transaksi Anda.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reports.export-sales', request()->all()) }}" class="btn btn-success shadow-sm px-4">
                <i class="bi bi-file-earmark-excel me-2"></i>Export Excel
            </a>
        </div>
    </div>

    {{-- FILTER CARD --}}
    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-body p-4">
            <form method="GET" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Mulai Tanggal</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-calendar-range"></i></span>
                        <input type="date" name="date_from" value="{{ $dateFrom }}" class="form-control border-0 bg-light">
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-uppercase text-muted">Sampai Tanggal</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-calendar-check"></i></span>
                        <input type="date" name="date_to" value="{{ $dateTo }}" class="form-control border-0 bg-light">
                    </div>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm">
                        <i class="bi bi-filter-right me-2"></i>Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SUMMARY METRICS --}}
    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 border-start border-5 border-primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Total Pendapatan</p>
                            <h2 class="fw-bold mb-0">Rp {{ number_format($summary->total_revenue ?? 0, 0, ',', '.') }}</h2>
                        </div>
                        <div class="icon-box bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                            <i class="bi bi-wallet2 fs-2"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        <i class="bi bi-info-circle me-1"></i> Berdasarkan periode terpilih
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-body p-4 border-start border-5 border-info">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted small fw-bold text-uppercase mb-1">Total Transaksi</p>
                            <h2 class="fw-bold mb-0">{{ number_format($summary->total_orders ?? 0) }} <span class="h5 text-muted">Order</span></h2>
                        </div>
                        <div class="icon-box bg-info bg-opacity-10 text-info p-3 rounded-3">
                            <i class="bi bi-cart-check fs-2"></i>
                        </div>
                    </div>
                    <div class="mt-3 small text-muted">
                        <i class="bi bi-check-circle-fill text-success me-1"></i> Hanya pesanan yang berhasil dibayar
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        {{-- SALES BY CATEGORY --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark">Proporsi Kategori</h5>
                </div>
                <div class="card-body p-4">
                    @foreach($byCategory as $cat)
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold text-dark small">{{ $cat->name }}</span>
                                <span class="text-muted small">Rp {{ number_format($cat->total, 0, ',', '.') }}</span>
                            </div>
                            <div class="progress rounded-pill" style="height: 10px;">
                                @php 
                                    $percentage = ($cat->total / ($summary->total_revenue ?: 1)) * 100;
                                @endphp
                                <div class="progress-bar bg-primary opacity-75" role="progressbar"
                                     style="width: {{ $percentage }}%" 
                                     aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
                                </div>
                            </div>
                        </div>
                    @endforeach
                    
                    @if($byCategory->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-pie-chart text-muted opacity-25" style="font-size: 3rem;"></i>
                            <p class="text-muted small mt-2">Tidak ada data kategori</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- TRANSACTIONS TABLE --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                <div class="card-header bg-transparent border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark">Log Transaksi Terbaru</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3 border-0 small fw-bold text-muted">ORDER ID</th>
                                    <th class="py-3 border-0 small fw-bold text-muted">CUSTOMER</th>
                                    <th class="py-3 border-0 small fw-bold text-muted">TANGGAL</th>
                                    <th class="pe-4 py-3 border-0 small fw-bold text-muted text-end">NOMINAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="ps-4">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-primary fw-bold text-decoration-none">
                                                #{{ $order->order_number }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                                    {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark mb-0 small">{{ $order->user->name }}</div>
                                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $order->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="small text-muted">
                                            {{ $order->created_at->format('d M Y') }}
                                            <div style="font-size: 0.7rem;">{{ $order->created_at->format('H:i') }} WIB</div>
                                        </td>
                                        <td class="pe-4 text-end fw-bold text-dark">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-inbox fs-1 d-block opacity-25"></i>
                                            Data tidak ditemukan untuk periode ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 p-4">
                    {{ $orders->appends(request()->all())->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { transition: all 0.3s ease; }
    .form-control:focus {
        box-shadow: none;
        background-color: #fff !important;
        border: 1px solid #0d6efd !important;
    }
    .table thead th {
        letter-spacing: 0.5px;
    }
    .progress {
        background-color: #f0f2f5;
    }
    .icon-box {
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endsection