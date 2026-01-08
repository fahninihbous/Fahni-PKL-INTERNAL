{{-- ================================================
     FILE: resources/views/partials/navbar.blade.php
     FUNGSI: Navigation bar untuk customer
     ================================================ --}}

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
        {{-- Logo & Brand --}}
        <a class="navbar-brand text-dark" href="{{ route('home') }}">
            <i class=" bi-leaf-fill"></i>
            Aden Garden
        </a>

        {{-- Mobile Toggle --}}
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Navbar Content --}}
        <div class="collapse navbar-collapse" id="navbarMain">
            {{-- Search Form --}}
            <form class="d-flex mx-auto" style="max-width: 400px; width: 100%;"
                  action="{{ route('catalog.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q"
                           class="form-control"
                           placeholder="Cari produk..."
                           value="{{ request('q') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>

            {{-- Right Menu --}}
            <ul class="navbar-nav ms-auto align-items-center">
                {{-- Katalog --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('catalog.index') }}">
                        <i class="bi bi-grid me-1"></i> Katalog
                    </a>
                </li>

                @auth
                    {{-- Wishlist --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('wishlist.index') }}">
                            <i class="bi bi-heart"></i>
                            @if(auth()->user()->wishlists()->count() > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    {{ auth()->user()->wishlists()->count() }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- Cart --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart"></i>
                            @php
                                $cartCount = auth()->user()->cart?->items()->count() ?? 0;
                            @endphp
                            @if($cartCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary" style="font-size: 0.6rem;">
                                    {{ $cartCount }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- User Dropdown --}}
                    <li class="nav-item dropdown ms-2">
                        <a class="nav-link dropdown-toggle d-flex align-items-center"
                           href="#" id="userDropdown"
                           data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}"
                                 class="rounded-circle me-2"
                                 width="32" height="32"
                                 alt="{{ auth()->user()->name }}">
                            <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person me-2"></i> Profil Saya
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('orders.index') }}">
                                    <i class="bi bi-bag me-2"></i> Pesanan Saya
                                </a>
                            </li>
                            @if(auth()->user()->isAdmin())
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i> Admin Panel
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    {{-- Guest Links --}}
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Masuk</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm ms-2" href="{{ route('register') }}">
                            Daftar
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>\
<style>
    /* 1. Global Navbar Styling */
    .navbar {
        background-color: #ffffff !important;
        padding: 0.8rem 0;
        transition: all 0.3s ease;
    }

    .navbar-brand {
        font-weight: 800;
        color: #2d422d !important; /* Hijau Hutan Tua */
        letter-spacing: -0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .navbar-brand i {
        color: #6ab04c; /* Hijau Daun */
    }

    /* 2. Search Bar Styling */
    .input-group .form-control {
        border-radius: 50px 0 0 50px !important;
        border-color: #eef3ed;
        padding-left: 20px;
        background-color: #f8fcf7;
    }

    .input-group .form-control:focus {
        box-shadow: none;
        border-color: #6ab04c;
    }

    .input-group .btn-outline-primary {
        border-radius: 0 50px 50px 0 !important;
        border-color: #eef3ed;
        color: #6ab04c;
        background-color: #f8fcf7;
    }

    .input-group .btn-outline-primary:hover {
        background-color: #6ab04c;
        border-color: #6ab04c;
        color: white;
    }

    /* 3. Nav Links Styling */
    .nav-link {
        color: #4a5d4a !important;
        font-weight: 500;
        padding: 0.5rem 1rem !important;
        transition: 0.2s;
    }

    .nav-link:hover {
        color: #6ab04c !important;
    }

    /* 4. Badge & Icon Styling */
    .bi-heart, .bi-cart, .bi-grid {
        font-size: 1.2rem;
    }

    .badge {
        font-weight: 700;
        padding: 0.35em 0.5em !important;
    }

    .bg-danger { background-color: #ff6b6b !important; } /* Soft red for wishlist */
    .bg-primary { background-color: #6ab04c !important; } /* Green for cart */

    /* 5. Dropdown Styling */
    .dropdown-menu {
        border: none;
        box-shadow: 0 10px 30px rgba(45, 66, 45, 0.1);
        border-radius: 15px;
        padding: 10px;
        margin-top: 15px !important;
    }

    .dropdown-item {
        border-radius: 8px;
        padding: 8px 15px;
        font-weight: 500;
        color: #4a5d4a;
    }

    .dropdown-item:hover {
        background-color: #f1f8e9;
        color: #2d422d;
    }

    /* 6. Button Styling */
    .btn-primary {
        background-color: #6ab04c !important;
        border-color: #6ab04c !important;
        border-radius: 50px;
        padding: 6px 20px;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(106, 176, 76, 0.2);
    }

    .btn-primary:hover {
        background-color: #2d422d !important;
        border-color: #2d422d !important;
        transform: translateY(-2px);
    }
</style>