{{-- ================================================
     FILE: resources/views/layouts/admin.blade.php
     FUNGSI: Master layout untuk halaman admin
================================================ --}}

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Admin Panel</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
    --admin-primary: #2d422d; /* Hijau Hutan Tua */
    --admin-dark: #1b2e1b;
    --accent-green: #6ab04c;
    --accent-gold: #eccc68;
    --sidebar-width: 270px;
    --bg-light: #f8fafc;
}

body {
    font-family: 'Inter', sans-serif;
    color: #334155;
    background-color: var(--bg-light);
    margin: 0;
}

/* Sidebar Styling */
.sidebar {
    min-height: 100vh;
    background: linear-gradient(180deg, var(--admin-primary) 0%, var(--admin-dark) 100%);
    box-shadow: 4px 0 15px rgba(0,0,0,0.1);
    z-index: 1000;
    position: sticky;
    top: 0;
    transition: all 0.3s ease;
}

.sidebar-brand {
    padding: 25px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    background: rgba(0, 0, 0, 0.05);
}

.nav-item-header {
    padding: 1.5rem 1.5rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: rgba(255,255,255,0.4);
}

.sidebar .nav-link {
    color: rgba(255,255,255,0.65);
    padding: 12px 20px;
    border-radius: 10px;
    margin: 4px 18px;
    font-weight: 500;
    font-size: 0.925rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    text-decoration: none;
}

.sidebar .nav-link i {
    font-size: 1.1rem;
    margin-right: 12px;
    transition: transform 0.3s;
}

.sidebar .nav-link:hover {
    background: rgba(255,255,255,0.08);
    color: #fff;
    transform: translateX(5px);
}

.sidebar .nav-link.active {
    background: var(--accent-green);
    color: #fff !important;
    box-shadow: 0 4px 12px rgba(106, 176, 76, 0.3);
}

.sidebar .nav-link.active i {
    color: var(--accent-gold);
}

/* User Section in Sidebar */
.user-section {
    background: rgba(0, 0, 0, 0.2);
    margin: 15px;
    border-radius: 15px;
    padding: 12px;
    border: 1px solid rgba(255,255,255,0.05);
}

.user-section img {
    border: 2px solid var(--accent-green);
}

/* Top Bar Styling */
header.bg-white {
    background: #ffffff;
    border-bottom: 1px solid #e2e8f0;
    padding: 1rem 2rem !important;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.page-title {
    font-weight: 700;
    color: var(--admin-primary);
    letter-spacing: -0.02em;
    margin: 0;
}

/* Buttons */
.btn-action {
    border-radius: 8px;
    font-weight: 600;
    padding: 0.5rem 1rem;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-outline-success {
    border-color: var(--accent-green);
    color: var(--accent-green);
}

.btn-outline-success:hover {
    background-color: var(--accent-green);
    color: white;
}

/* Notifications/Badges */
.badge-pending {
    background: var(--accent-gold);
    color: var(--admin-primary);
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 6px;
    font-size: 0.75rem;
}

/* Sidebar Scrollbar */
nav::-webkit-scrollbar {
    width: 4px;
}
nav::-webkit-scrollbar-thumb {
    background: rgba(255,255,255,0.1);
    border-radius: 10px;
}

/* Main Content Padding */
main {
    padding: 2rem;
}
    </style>
    @stack('styles')
</head>
<body class="bg-light">
    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="sidebar d-flex flex-column" style="width: 260px;">
            {{-- Brand --}}
            <div class="p-3 border-bottom border-secondary">
                <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none d-flex align-items-center">
                    <i class="bi bi-shop fs-4 me-2"></i>
                    <span class="fs-5 fw-bold">Admin Panel</span>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-grow-1 py-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.products.index') }}"
                           class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                            <i class="bi bi-box-seam me-2"></i> Produk
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.categories.index') }}"
                           class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                            <i class="bi bi-folder me-2"></i> Kategori
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.orders.index') }}"
                           class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                            <i class="bi bi-receipt me-2"></i> Pesanan
                            {{-- Badge pending orders --}}
                            @if(isset($pendingCount) && $pendingCount > 0)
                                <span class="badge bg-warning text-dark ms-auto">{{ $pendingCount }}</span>
                            @endif
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                           class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="bi bi-people me-2"></i> Pengguna
                        </a>
                    </li>

                    <li class="nav-item mt-3">
                        <span class="nav-link text-muted small text-uppercase">Laporan</span>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('admin.reports.sales') ?? '#' }}"
                           class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                            <i class="bi bi-graph-up me-2"></i> Laporan Penjualan
                        </a>
                    </li>
                </ul>
            </nav>

            {{-- User Info --}}
            <div class="p-3 border-top border-secondary">
                <div class="d-flex align-items-center text-white">
                    <img src="{{ auth()->user()->avatar_url }}"
                         class="rounded-circle me-2" width="36" height="36" alt="Avatar">
                    <div class="flex-grow-1">
                        <div class="small fw-medium">{{ auth()->user()->name }}</div>
                        <div class="small text-muted">Administrator</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="flex-grow-1">
            {{-- Top Bar --}}
            <header class="bg-white shadow-sm py-3 px-4 d-flex justify-content-between align-items-center">
                <h4 class="mb-0">@yield('page-title', 'Dashboard')</h4>
                <div class="d-flex align-items-center">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-sm me-2" target="_blank">
                        <i class="bi bi-box-arrow-up-right me-1"></i> Lihat Toko
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-box-arrow-right me-1"></i> Logout
                        </button>
                    </form>
                </div>
            </header>

            {{-- Flash Messages --}}
            <div class="px-4 pt-3">
                @include('partials.flash-messages')
            </div>

            {{-- Page Content --}}
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>