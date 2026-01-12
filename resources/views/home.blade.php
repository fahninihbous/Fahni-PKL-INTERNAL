{{-- ================================================
     FILE: resources/views/home.blade.php
     FUNGSI: Halaman utama website
     ================================================ --}}

@extends('layouts.app')

@section('title', 'Beranda')

@section('content')
    {{-- Hero Section --}}
    <section class="bg-secondary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
               <div class="col-lg-6 hero-text-wrapper">
                    <span class="badge-promo mb-2">🌿 Penawaran Terbatas Musim Ini</span>
                    <h1 class="display-4 fw-bold mb-3">
                        Hadirkan Kenyamanan <span class="text-accent">Alami</span> di Setiap Langkah Anda
                    </h1>
                    <p class="lead mb-4 text-light-green">
                        Koleksi pilihan produk berkualitas tinggi yang ramah lingkungan. 
                        Dapatkan <strong>Gratis Ongkir</strong> & <strong>Voucher Belanja</strong> khusus untuk transaksi pertama Anda hari ini!
                    </p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('catalog.index') }}" class="btn btn-nature-primary btn-lg px-4 shadow">
                            <i class="bi bi-bag-heart me-2"></i>Mulai Belanja
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 d-none d-lg-block text-center position-relative">
                    <div class="image-nature-wrapper">
                        {{-- Gambar Utama Bertema Alam --}}
                        <img src="https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?q=80&w=1000&auto=format&fit=crop" 
                            alt="Nature Product" 
                            class="img-fluid nature-hero-img shadow-lg">
                        
                        {{-- Dekorasi Elemen Melayang (Opsional) --}}
                        <div class="floating-badge-cute">
                            <span>🍃 100% Organic</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Kategori --}}
    <section class="py-5 bg-light">
    <div class="container">
        <h2 class="fw-bold mb-4 text-center">Kategori Populer</h2>
        
        <div class="row g-4 justify-content-center">
            @foreach($categories as $category)
                <div class="col-6 col-md-4 col-lg-2">
                    <a href="{{ route('catalog.index', ['category' => $category->slug]) }}"
                       class="text-decoration-none group-category">
                        <div class="card border-0 shadow-sm h-100 category-card-portrait">
                            <div class="category-img-wrapper">
                                <img src="{{ $category->image_url }}"
                                     alt="{{ $category->name }}"
                                     class="card-img-top img-square"
                                     onerror="this.onerror=null;this.src='https://placehold.co/400x500?text={{ $category->name }}';">
                            </div>
                            
                            <div class="card-body text-center d-flex flex-column justify-content-center py-3">
                                <h6 class="card-title mb-1 text-dark fw-bold text-truncate">{{ $category->name }}</h6>
                                <small class="text-muted d-block mb-2">{{ $category->products_count }} Produk</small>
                                <span class="btn-explore-mini">Lihat Produk <i class="bi bi-chevron-right"></i></span>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</section>

    {{-- Produk Unggulan --}}
    <section class="py-5 bg-light">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-center mb-4">Produk Unggulan</h2>
                <a href="{{ route('catalog.index') }}" class="btn btn-outline-success">
                    Lihat Semua <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            <div class="row g-4 justify-content-center"">
                @foreach($featuredProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Promo Banner --}}
   <section class="py-5">
    <div class="container">
        <div class="row g-4">
            {{-- CARD FLASH SALE --}}
            <div class="col-md-6">
                <a href="{{ route('catalog.index', ['promo' => 1]) }}" class="text-decoration-none">
                    <div class="card bg-warning border-0 position-relative overflow-hidden promo-card" style="min-height: 220px;">
                        <div class="position-absolute top-0 end-0 p-3 opacity-25">
                            <i class="bi bi-lightning-charge-fill" style="font-size: 6rem;"></i>
                        </div>
                        <div class="card-body d-flex flex-column justify-content-center px-4 position-relative">
                            <span class="badge bg-danger mb-2" style="width: fit-content;">PROMO TERBATAS</span>
                            <h3 class="fw-bold text-dark h2 mb-1">Flash Sale!</h3>
                            <p class="text-dark opacity-75 mb-4">Diskon hingga 50% untuk produk pilihan kebun.</p>
                            <span class="btn btn-dark fw-bold rounded-pill px-4 shadow-sm" style="width: fit-content;">
                                Lihat Semua Promo <i class="bi bi-arrow-right ms-2"></i>
                            </span>
                        </div>
                    </div>
                </a>
            </div>

            {{-- CARD MEMBER BARU (Hanya muncul jika belum login) --}}
            <div class="col-md-6">
                @guest
                    <a href="{{ route('register') }}" class="text-decoration-none">
                        <div class="card bg-info border-0 position-relative overflow-hidden promo-card" style="min-height: 220px;">
                            <div class="position-absolute top-0 end-0 p-3 opacity-25 text-white">
                                <i class="bi bi-stars" style="font-size: 6rem;"></i>
                            </div>
                            <div class="card-body d-flex flex-column justify-content-center px-4 position-relative">
                                <span class="badge bg-white text-info mb-2" style="width: fit-content;">MEMBER BARU</span>
                                <h3 class="fw-bold text-white h2 mb-1">Makin Hemat?</h3>
                                <p class="text-white opacity-75 mb-4">Dapatkan voucher Rp 50.000 untuk pembelian pertama.</p>
                                <span class="btn btn-light text-info fw-bold rounded-pill px-4 shadow-sm" style="width: fit-content;">
                                    Daftar Sekarang <i class="bi bi-person-plus-fill ms-2"></i>
                                </span>
                            </div>
                        </div>
                    </a>
                @else
                    {{-- Opsional: Tampilan card lain untuk user yang sudah login --}}
                    <div class="card bg-success border-0 position-relative overflow-hidden promo-card" style="min-height: 220px;">
                        <div class="card-body d-flex flex-column justify-content-center px-4 text-white">
                            <h3 class="fw-bold">Selamat Datang, {{ Auth::user()->name }}!</h3>
                            <p class="opacity-75">Cek pesanan terbaru atau lanjutkan belanja koleksi tanaman kami.</p>
                            <a href="{{ route('orders.index') }}" class="btn btn-light text-success fw-bold rounded-pill px-4" style="width: fit-content;">Riwayat Pesanan</a>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </div>
</section>
    {{-- Produk Terbaru --}}
    <section class="py-5">
        <div class="container">
            <h2 class=" mb-4">Produk Terbaru</h2>
            <div class="row g-4 ">
                @foreach($latestProducts as $product)
                    <div class="col-6 col-md-4 col-lg-3">
                        @include('partials.product-card', ['product' => $product])
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
<style>
    .promo-card { transition: all 0.4s ease; cursor: pointer; }
    .promo-card:hover { transform: translateY(-8px); box-shadow: 0 15px 30px rgba(0,0,0,0.15); }
   
    /* 1. GLOBAL & TYPOGRAPHY 
       Mengatur dasar tampilan halaman agar terlihat bersih dan profesional. */
    body {
        background-color: #fdfcf8; /* Warna krem lembut agar mata tidak cepat lelah */
        color: #2d3436;
        font-family: 'Inter', sans-serif;
    }

    h2 {
        color: #2d422d; /* Hijau tua khas alam */
        font-weight: 800;
        position: relative;
    }

    /* Menambahkan icon daun setelah judul secara otomatis */
    h2::after {
        content: '🍃';
        font-size: 1rem;
        margin-left: 10px;
        vertical-align: middle;
    }

    /* 2. HERO SECTION 
       Bagian utama (Header) dengan background gambar dan gradasi gelap agar teks terbaca jelas. */
    .bg-secondary {
        background: linear-gradient(rgba(45, 66, 45, 0.8), rgba(20, 30, 20, 0.9)), 
                    url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&q=80') !important;
        background-size: cover !important;
        background-position: center !important;
        border-bottom: 5px solid #6ab04c;
    }

    .hero-text-wrapper {
        padding-right: 20px;
        animation: fadeInUp 1s ease-out; /* Animasi teks muncul dari bawah */
    }

    .bg-secondary h1 {
        color: #eccc68; /* Warna kuning emas untuk highlight */
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .text-accent { color: #eccc68; }
    .text-light-green { color: #d1d8d1; font-weight: 300; }

    /* 3. HERO IMAGES & ANIMATIONS
       Efek visual pada gambar utama di bagian Hero. */
    .image-nature-wrapper {
        position: relative;
        display: inline-block;
        padding: 20px;
    }

    /* Animasi Morphing: Mengubah border-radius secara dinamis agar gambar terlihat 'hidup' */
    .nature-hero-img {
        max-height: 400px;
        width: 100%;
        object-fit: cover;
        border: 8px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
        animation: morphing 10s ease-in-out infinite;
    }

    /* Badge melayang di atas gambar hero */
    .floating-badge-cute {
        position: absolute;
        top: 10%;
        right: 0;
        background: #ffffff;
        padding: 10px 20px;
        border-radius: 50px;
        font-weight: bold;
        color: #2d422d;
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        animation: floaty 3s ease-in-out infinite;
        z-index: 2;
    }

    @keyframes morphing {
        0%, 100% { border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%; }
        50% { border-radius: 50% 50% 33% 67% / 55% 27% 73% 45%; }
    }

    @keyframes floaty {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-15px); }
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* 4. CARDS (UMUM) 
       Berlaku untuk kartu produk dan kategori. */
    .card.border-0.shadow-sm, .row.g-4 .card {
        background-color: #ffffff;
        border-radius: 20px !important;
        transition: all 0.4s ease;
        border: 1px solid #e0eadd !important;
        overflow: hidden;
    }

    .card.border-0.shadow-sm:hover {
        background-color: #f1f8e9;
        transform: translateY(-8px); /* Efek kartu naik saat kursor diarahkan */
        box-shadow: 0 10px 20px rgba(106, 176, 76, 0.15) !important;
    }

    /* 5. KATEGORI POPULER (PORTRAIT)
       Khusus untuk bagian kategori dengan bentuk memanjang ke bawah. */
    .category-card-portrait {
        display: flex;
        flex-direction: column;
        border-radius: 15px !important;
    }

    .category-img-wrapper {
        width: 100%;
        aspect-ratio: 4 / 5; /* Memaksa gambar tetap portrait 4:5 */
        overflow: hidden;
        background: #f8f9fa;
    }

    .img-square {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    /* Efek Zoom-in pada gambar saat hover */
    .group-category:hover .img-square {
        transform: scale(1.1);
    }

    .group-category:hover .category-card-portrait {
        border-color: #6ab04c !important;
        box-shadow: 0 10px 25px rgba(45, 66, 45, 0.15) !important;
        transform: translateY(-5px);
    }

    .btn-explore-mini {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        color: #6ab04c;
        letter-spacing: 0.5px;
        opacity: 0.7;
        transition: all 0.3s ease;
    }

    .group-category:hover .btn-explore-mini {
        opacity: 1;
        letter-spacing: 1px;
    }

    /* 6. BANNER & BACKGROUNDS
       Gaya untuk badge promo dan banner Flash Sale. */
    .card.bg-warning { background: linear-gradient(135deg, #e67e22, #d35400) !important; color: white !important; }
    .card.bg-info { background: linear-gradient(135deg, #27ae60, #16a085) !important; color: white !important; }
    .bg-light { background-color: #f1f2f6 !important; }

    .badge-promo {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 50px;
        backdrop-filter: blur(5px); /* Efek kaca transparan (Glassmorphism) */
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    /* 7. BUTTONS
       Kustomisasi tombol agar sesuai dengan tema alam. */
    .btn-nature-primary {
        background-color: #6ab04c !important;
        color: #ffffff !important;
        border-radius: 12px;
        font-weight: 600;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
    }

    .btn-nature-primary:hover {
        background-color: #58943f !important;
        border: 2px solid #eccc68 !important;
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(106, 176, 76, 0.3) !important;
    }

    .btn-nature-primary i { color: #eccc68; transition: transform 0.3s ease; }
    .btn-nature-primary:hover i { transform: scale(1.2); }

    .btn-light {
        background-color: #ffffff;
        color: #2d422d !important;
        border: 2px solid #6ab04c;
    }

    .btn-light:hover { background-color: #6ab04c; color: white !important; }

    /* 8. UTILITIES & RESPONSIVE
       Pengaturan jarak dan tampilan di perangkat mobile. */
    .gap-3 { gap: 1rem !important; }
    .shadow { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; }

    @media (max-width: 991px) {
        .nature-hero-img { max-height: 300px; margin-top: 30px; }
    }
</style>