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
        <h2 class="fw-bold mb-4">Kategori Populer</h2>
        <div class="row g-4">
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
            <div class="row g-4">
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
                <div class="col-md-6">
                    <div class="card bg-warning text-dark border-0" style="min-height: 200px;">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h3>Flash Sale!</h3>
                            <p>Diskon hingga 50% untuk produk pilihan</p>
                            <a href="#" class="btn btn-dark" style="width: fit-content;">
                                Lihat Promo
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-info text-white border-0" style="min-height: 200px;">
                        <div class="card-body d-flex flex-column justify-content-center">
                            <h3>Member Baru?</h3>
                            <p>Dapatkan voucher Rp 50.000 untuk pembelian pertama</p>
                            <a href="{{ route('register') }}" class="btn btn-light" style="width: fit-content;">
                                Daftar Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk Terbaru --}}
    <section class="py-5">
        <div class="container">
            <h2 class=" mb-4">Produk Terbaru</h2>
            <div class="row g-4">
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
    /* 8. Portrait Category Card Style */
    
    .category-card-portrait {
        border-radius: 15px !important;
        overflow: hidden;
        background: #ffffff;
        display: flex;
        flex-direction: column;
    }

    .category-img-wrapper {
        width: 100%;
        /* Mengatur proporsi kotak agak tinggi (Portrait) */
        aspect-ratio: 4 / 5; 
        overflow: hidden;
        background: #f8f9fa;
    }

    .img-square {
        width: 100%;
        height: 100%;
        object-fit: cover; /* Foto tetap proporsional meski kotak */
        transition: transform 0.5s ease;
    }

    /* Animasi Hover Baru */
    .group-category:hover .img-square {
        transform: scale(1.1);
    }

    .group-category:hover .category-card-portrait {
        border-color: #6ab04c !important;
        box-shadow: 0 10px 25px rgba(45, 66, 45, 0.15) !important;
    }

    /* Tombol Mini di bawah teks */
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
    
    /* 1. Global & Typography */
    body {
        background-color: #fdfcf8;
        color: #2d3436;
        font-family: 'Inter', sans-serif;
    }

    h2 {
        color: #2d422d;
        font-weight: 800;
        position: relative;
    }

    h2::after {
        content: '🍃';
        font-size: 1rem;
        margin-left: 10px;
        vertical-align: middle;
    }

    /* 2. Hero Section & Text Wrapper */
    .bg-secondary {
        background: linear-gradient(rgba(45, 66, 45, 0.8), rgba(20, 30, 20, 0.9)), 
                    url('https://images.unsplash.com/photo-1441974231531-c6227db76b6e?auto=format&fit=crop&q=80') !important;
        background-size: cover !important;
        background-position: center !important;
        border-bottom: 5px solid #6ab04c;
    }

    .hero-text-wrapper {
        padding-right: 20px;
        animation: fadeInUp 1s ease-out;
    }

    .bg-secondary h1 {
        color: #eccc68;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .text-accent { color: #eccc68; }
    .text-light-green { color: #d1d8d1; font-weight: 300; }

    /* 3. Hero Image & Animations */
    .image-nature-wrapper {
        position: relative;
        display: inline-block;
        padding: 20px;
    }

    .nature-hero-img {
        max-height: 400px;
        width: 100%;
        object-fit: cover;
        border: 8px solid rgba(255, 255, 255, 0.3);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2) !important;
        animation: morphing 10s ease-in-out infinite;
    }

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

    /* 4. Cards & Banners */
    .card.border-0.shadow-sm, .row.g-4 .card {
        background-color: #ffffff;
        border-radius: 20px !important;
        transition: all 0.4s ease;
        border: 1px solid #e0eadd !important;
        overflow: hidden;
    }

    .card.border-0.shadow-sm:hover {
        background-color: #f1f8e9;
        transform: translateY(-8px);
        box-shadow: 0 10px 20px rgba(106, 176, 76, 0.15) !important;
    }

    .badge-promo {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 5px 15px;
        border-radius: 50px;
        backdrop-filter: blur(5px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .card.bg-warning { background: linear-gradient(135deg, #e67e22, #d35400) !important; color: white !important; }
    .card.bg-info { background: linear-gradient(135deg, #27ae60, #16a085) !important; color: white !important; }
    .bg-light { background-color: #f1f2f6 !important; }

    /* 5. Buttons */
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
    .btn-nature-primary:hover i { transform: scale(1.2); color: #ffffff; }

    .btn-outline-nature {
        background: transparent;
        color: white !important;
        border: 2px solid rgba(255, 255, 255, 0.5);
        border-radius: 12px;
    }

    .btn-outline-nature:hover { background: rgba(255, 255, 255, 0.1); border-color: white; }

    .btn-light {
        background-color: #ffffff;
        color: #2d422d !important;
        border: 2px solid #6ab04c;
    }

    .btn-light:hover { background-color: #6ab04c; color: white !important; }

    .btn-outline-primary { color: #27ae60; border-color: #27ae60; }
    .btn-outline-primary:hover { background-color: #27ae60; color: white; }

    /* 6. Utilities */
    .gap-3 { gap: 1rem !important; }
    .shadow { box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important; }

    @media (max-width: 991px) {
        .nature-hero-img { max-height: 300px; margin-top: 30px; }
    }
</style>