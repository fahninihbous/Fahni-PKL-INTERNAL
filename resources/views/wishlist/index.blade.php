{{-- resources/views/wishlist/index.blade.php --}}

@extends('layouts.app')

@section('title', 'Wishlist Saya - Garden Edition')

@section('content')
<div class="garden-theme-wrapper py-5">
    <div class="container">
        {{-- Header Section --}}
        <div class="d-flex align-items-center justify-content-between mb-5 flex-wrap gap-3">
            <div>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-1 text-uppercase ls-1 small">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-garden-muted">Home</a></li>
                        <li class="breadcrumb-item active fw-bold text-garden-primary">My Garden Wishlist</li>
                    </ol>
                </nav>
                <h1 class="display-6 fw-bold text-garden-dark m-0">Koleksi Favorit</h1>
                <p class="text-garden-muted m-0">Produk pilihan yang ingin kamu bawa pulang.</p>
            </div>
            @if($products->count())
                <a href="{{ route('catalog.index') }}" class="btn btn-garden px-4 py-2 rounded-pill shadow-sm fw-bold">
                    <i class="bi bi-plus-lg me-2"></i>Tambah Koleksi
                </a>
            @endif
        </div>

        @if($products->count())
            {{-- Grid Produk --}}
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
                @foreach($products as $product)
                    <div class="col" id="product-wishlist-{{ $product->id }}">
                        <div class="garden-card-container h-100">
                            {{-- Kita bungkus card agar bisa dikasih style garden --}}
                            <x-product-card :product="$product" />
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="d-flex justify-content-center mt-5">
                {{ $products->links() }}
            </div>
        @else
            {{-- Empty State (Garden Theme) --}}
            <div class="row justify-content-center py-5">
                <div class="col-md-6 text-center">
                    <div class="garden-empty-icon mb-4">
                        <div class="leaf-bg mx-auto">
                            <i class="bi bi-tree-fill"></i>
                        </div>
                    </div>
                    <h3 class="fw-bold text-garden-dark">Kebun favoritmu masih kosong</h3>
                    <p class="text-garden-muted mb-4">Mulai jelajahi katalog kami dan tanamkan benih keinginanmu di sini!</p>
                    <a href="{{ route('catalog.index') }}" class="btn btn-garden btn-lg rounded-pill px-5 shadow fw-bold">
                        Cari Produk Sekarang
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
    /* Variabel Warna Garden */
    :root {
        --garden-primary: #2d5a27;      /* Hijau Tua Daun */
        --garden-secondary: #8da684;    /* Hijau Sage */
        --garden-light: #f1f4f0;        /* Putih Kehijauan Sedikit */
        --garden-dark: #1e351b;         /* Hijau Gelap */
        --garden-muted: #6b7c65;        /* Hijau Abu-abu */
        --garden-accent: #d4a373;       /* Cokelat Kayu/Tanah */
    }

    .garden-theme-wrapper {
        background-color: var(--garden-light);
        min-height: 100vh;
    }

    /* Typography */
    .text-garden-primary { color: var(--garden-primary) !important; }
    .text-garden-dark { color: var(--garden-dark) !important; }
    .text-garden-muted { color: var(--garden-muted) !important; }
    .ls-1 { letter-spacing: 1px; }

    /* Button Garden */
    .btn-garden {
        background-color: var(--garden-primary);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }

    .btn-garden:hover {
        background-color: var(--garden-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(45, 90, 39, 0.3);
    }

    /* Card Styling Overrides */
    .garden-card-container .card {
        border: none !important;
        border-radius: 20px !important;
        overflow: hidden;
        transition: all 0.3s ease;
        background: white;
    }

    .garden-card-container:hover .card {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(30, 53, 27, 0.1);
    }

    /* Empty State */
    .garden-empty-icon .leaf-bg {
        width: 130px;
        height: 130px;
        background: #e4ece2;
        color: var(--garden-primary);
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; /* Bentuk Organik Daun */
        display: flex;
        align-items: center;
        justify-content: center;
        animation: morph 6s ease-in-out infinite;
    }

    .garden-empty-icon i {
        font-size: 4rem;
    }

    @keyframes morph {
        0% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
        50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
        100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
    }

    /* Pagination Garden Style */
    .pagination .page-link {
        color: var(--garden-primary);
        background: white;
        border: none;
        margin: 0 4px;
        border-radius: 10px;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--garden-primary);
        color: white;
    }
</style>
@endsection 