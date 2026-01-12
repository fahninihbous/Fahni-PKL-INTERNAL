@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="container-fluid">
    {{-- Header Page --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}" class="text-decoration-none text-muted">Daftar Pesanan</a></li>
                    <li class="breadcrumb-item active fw-bold" aria-current="page">#{{ $order->order_number }}</li>
                </ol>
            </nav>
            <h2 class="h4 fw-bold text-dark mb-0">Rincian Transaksi</h2>
        </div>
        <div class="text-end">
            <span class="text-muted d-block small">Tanggal Pesanan:</span>
            <span class="fw-bold"><i class="bi bi-calendar-event me-1"></i> {{ $order->created_at->format('d F Y, H:i') }}</span>
        </div>
    </div>

    <div class="row g-4">
        {{-- KOLOM KIRI: ITEM & PENGIRIMAN --}}
        <div class="col-lg-8">
            {{-- List Item --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                            <i class="bi bi-bag-check-fill text-primary"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Item Dipesan</h5>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="border-0 small text-muted text-uppercase py-3 ps-3">Produk</th>
                                    <th class="border-0 small text-muted text-uppercase py-3 text-center">Harga Satuan</th>
                                    <th class="border-0 small text-muted text-uppercase py-3 text-center">Qty</th>
                                    <th class="border-0 small text-muted text-uppercase py-3 text-end pe-3">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="ps-3">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item->product->image_url }}" 
                                                 class="rounded-3 border me-3" 
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                            <div>
                                                <h6 class="mb-0 fw-bold text-dark">{{ $item->product->name }}</h6>
                                                <small class="text-muted">ID: #{{ $item->product->id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center text-muted">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center fw-bold text-dark">
                                        {{ $item->quantity }}
                                    </td>
                                    <td class="text-end pe-3 fw-bold text-primary">
                                        Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 p-4">
                    <div class="row justify-content-end">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal Produk</span>
                                <span class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Biaya Layanan</span>
                                <span class="text-success fw-bold">Rp 0</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                <span class="h6 fw-bold mb-0">Total Pembayaran</span>
                                <span class="h4 fw-bold mb-0 text-primary">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Info Pengiriman (Optional Addition to Layout) --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-geo-alt-fill me-2 text-danger"></i>Detail Pengiriman</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="p-3 bg-light rounded-3">
                        <p class="mb-1"><strong>Penerima:</strong> {{ $order->shipping_name ?? $order->user->name }}</p>
                        <p class="mb-1"><strong>Telepon:</strong> {{ $order->shipping_phone ?? '-' }}</p>
                        <p class="mb-0 text-muted"><strong>Alamat:</strong> {{ $order->shipping_address ?? 'Alamat tidak tersedia' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN: STATUS & CUSTOMER --}}
        <div class="col-lg-4">
            {{-- Action Card / Update Status --}}
            <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                <div class="card-header bg-dark py-3 border-0">
                    <h6 class="fw-bold mb-0 text-white"><i class="bi bi-lightning-charge-fill me-2 text-warning"></i>Kelola Status</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-4 text-center py-3 rounded-3 bg-light border">
                            <small class="text-muted d-block text-uppercase fw-bold ls-1 mb-1" style="font-size: 0.7rem;">Status Saat Ini</small>
                            <span class="badge px-3 py-2 rounded-pill shadow-sm 
                                @if($order->status == 'pending') bg-warning text-dark 
                                @elseif($order->status == 'processing') bg-info text-white
                                @elseif($order->status == 'completed') bg-success text-white
                                @else bg-danger text-white @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold small">Ubah Status Ke:</label>
                            <select name="status" class="form-select border-0 bg-light py-2 px-3 shadow-none">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>🕒 Pending (Belum Bayar)</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>📦 Processing (Dikemas)</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>✅ Completed (Selesai)</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>❌ Cancelled (Batalkan)</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                            <i class="bi bi-arrow-repeat me-2"></i>Update Status
                        </button>
                    </form>

                    @if($order->status == 'cancelled')
                        <div class="alert alert-soft-danger mt-3 mb-0 small border-0 d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-2 mt-1"></i>
                            <div>Pesanan ini dibatalkan. Stok produk telah dikembalikan otomatis ke gudang.</div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Info Customer --}}
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="mb-0 fw-bold">Pelanggan</h5>
                </div>
                <div class="card-body pt-0">
                    <div class="d-flex align-items-center mb-4">
                        <div class="avatar-placeholder rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white fw-bold me-3 shadow-sm" style="width: 50px; height: 50px; font-size: 1.2rem;">
                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="mb-0 fw-bold text-dark">{{ $order->user->name }}</p>
                            <p class="mb-0 text-muted small">{{ $order->user->email }}</p>
                        </div>
                    </div>
                    
                    <div class="list-group list-group-flush small">
                        <div class="list-group-item px-0 py-2 border-0 d-flex justify-content-between">
                            <span class="text-muted">Total Order User:</span>
                            <span class="fw-bold">{{ $order->user->orders()->count() }} Pesanan</span>
                        </div>
                        <div class="list-group-item px-0 py-2 border-0 d-flex justify-content-between">
                            <span class="text-muted">Member Sejak:</span>
                            <span class="fw-bold">{{ $order->user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .ls-1 { letter-spacing: 1px; }
    .breadcrumb-item + .breadcrumb-item::before { content: "›"; }
    .alert-soft-danger {
        background-color: #fff5f5;
        color: #e53e3e;
    }
    .table thead th {
        font-weight: 700;
        font-size: 0.75rem;
    }
    .avatar-placeholder {
        background: linear-gradient(135deg, #6c757d, #343a40);
    }
    .form-select:focus {
        border: 1px solid var(--bs-primary);
        box-shadow: none;
    }
</style>
@endsection