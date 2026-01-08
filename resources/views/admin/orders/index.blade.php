@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="h3 mb-0 text-gray-800">Daftar Pesanan</h2>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        {{-- Filter Status --}}
        <ul class="nav nav-pills card-header-pills">
            <li class="nav-item">
                <a class="nav-link {{ !request('status') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'pending' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'pending']) }}">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'processing' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'processing']) }}">Diproses</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request('status') == 'completed' ? 'active' : '' }}" href="{{ route('admin.orders.index', ['status' => 'completed']) }}">Selesai</a>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">No. Order</th>
                        <th>Customer</th>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td class="ps-4 fw-bold text-primary">#{{ $order->order_number }}</td>
                            <td>
                                <div class="fw-bold">{{ $order->user->name }}</div>
                                <small class="text-muted">{{ $order->user->email }}</small>
                            </td>
                            <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @elseif($order->status == 'processing')
                                    <span class="badge bg-info text-dark">Diproses</span>
                                @elseif($order->status == 'completed')
                                    <span class="badge bg-success">Selesai</span>
                                @elseif($order->status == 'cancelled')
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada pesanan ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        {{ $orders->links() }}
    </div>
</div>
@endsection
<style>
    /* 1. Global & Card Styling */
    .h3 {
        color: #2d422d;
        font-weight: 800;
    }

    .card {
        border-radius: 20px !important;
        overflow: hidden;
        border: 1px solid #e0eadd !important;
    }

    /* 2. Nav Pills (Filter Status) */
    .nav-pills .nav-link {
        color: #6c757d;
        font-weight: 600;
        border-radius: 50px;
        padding: 8px 20px;
        transition: all 0.3s ease;
    }

    .nav-pills .nav-link.active {
        background-color: #2d422d !important; /* Hijau Hutan Aden */
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(45, 66, 45, 0.2);
    }

    .nav-pills .nav-link:hover:not(.active) {
        background-color: #f1f8e9;
        color: #6ab04c;
    }

    /* 3. Table Styling */
    .table thead {
        background-color: #fdfcf8;
    }

    .table thead th {
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #2d422d;
        border-bottom: 2px solid #eef3ed;
    }

    .text-primary {
        color: #6ab04c !important; /* Ganti biru default ke Hijau Daun */
    }

    /* 4. Custom Badge Status */
    .badge {
        padding: 6px 12px;
        border-radius: 8px;
        font-weight: 600;
    }

    /* Soft Colors untuk Badge */
    .bg-warning { background-color: #fff8dd !important; color: #856404 !important; }
    .bg-info { background-color: #e0f7fa !important; color: #006064 !important; }
    .bg-success { background-color: #e8fadf !important; color: #2d422d !important; }
    .bg-danger { background-color: #fff5f8 !important; color: #b91d1d !important; }

    /* 5. Button Action */
    .btn-outline-primary {
        border-color: #6ab04c;
        color: #6ab04c;
        border-radius: 50px;
        padding: 5px 15px;
        font-weight: 600;
    }

    .btn-outline-primary:hover {
        background-color: #6ab04c;
        border-color: #6ab04c;
        color: white;
        transform: translateY(-2px);
    }

    /* 6. Hover Effect pada Baris Tabel */
    .table-hover tbody tr:hover {
        background-color: #fdfcf8;
    }
</style>