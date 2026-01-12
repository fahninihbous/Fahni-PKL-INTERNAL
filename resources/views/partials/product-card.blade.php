{{-- ================================================
     FILE: resources/views/partials/product-card.blade.php
     FUNGSI: Komponen kartu produk yang reusable
     ================================================ --}}

<div class="card product-card h-100 border-0 shadow-sm">
    {{-- Product Image --}}
    <div class="position-relative">
        <a href="{{ route('catalog.show', $product->slug) }}">
            <img src="{{ $product->image_url }}"
                 class="card-img-top"
                 alt="{{ $product->name }}"
                 style="height: 200px; object-fit: cover;">
        </a>

        {{-- Badge Diskon --}}
        @if($product->has_discount)
            <span class="badge-discount">
                -{{ $product->discount_percentage }}%
            </span>
        @endif

        {{-- Wishlist Button --}}
        @auth
            <button type="button"
                    onclick="toggleWishlist({{ $product->id }})"
                    class="btn btn-light btn-sm position-absolute top-0 end-0 m-2 rounded-circle wishlist-btn-{{ $product->id }}">
                <i class="bi {{ auth()->user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart' }}"></i>
            </button>
        @endauth
    </div>

    {{-- Card Body --}}
    <div class="card-body d-flex flex-column">
        {{-- Category --}}
        <small class="text-muted mb-1">{{ $product->category->name }}</small>

        {{-- Product Name --}}
        <h6 class="card-title mb-2">
            <a href="{{ route('catalog.show', $product->slug) }}"
               class="text-decoration-none text-dark stretched-link">
                {{ Str::limit($product->name, 40) }}
            </a>
        </h6>

        {{-- Price --}}
        <div class="mt-auto">
            @if($product->has_discount)
                <small class="text-muted text-decoration-line-through">
                    {{ $product->formatted_original_price }}
                </small>
            @endif
            <div class="fw-bold text-dark">
                {{ $product->formatted_price }}
            </div>
        </div>

        {{-- Stock Info --}}
        @if($product->stock <= 5 && $product->stock > 0)
            <small class="text-warning mt-2">
                <i class="bi bi-exclamation-triangle"></i>
                Stok tinggal {{ $product->stock }}
            </small>
        @elseif($product->stock == 0)
            <small class="text-danger mt-2">
                <i class="bi bi-x-circle"></i> Stok Habis
            </small>
        @endif
    </div>

    {{-- Card Footer --}}
    <div class="card-footer bg-white border-0 pt-0">
        <form action="{{ route('cart.add') }}" method="POST">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="quantity" value="1">
            <button type="submit"
                    class="btn btn-primary btn-sm w-100"
                    @if($product->stock == 0) disabled @endif>
                <i class="bi bi-cart-plus me-1"></i>
                @if($product->stock == 0)
                    Stok Habis
                @else
                    Tambah Keranjang
                @endif
            </button>
            <button onclick="toggleWishlist({{ $product->id }})"
        class="wishlist-btn-{{ $product->id }} btn btn-light btn-sm rounded-circle p-2 transition">
             <i class="bi {{ Auth::check() && Auth::user()->hasInWishlist($product) ? 'bi-heart-fill text-danger' : 'bi-heart text-secondary' }} fs-5"></i>
        </button>
        </form>
    </div>
</div>

<style>
    /* 1. PRODUCT CARD BASE
       Mengatur struktur kartu agar memiliki sudut yang halus dan bayangan lembut. */
    .product-card {
        border-radius: 20px !important;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: 1px solid #f0f0f0 !important;
        overflow: hidden;
        background: #fff;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(45, 66, 45, 0.1) !important;
        border-color: #6ab04c !important;
    }

    /* 2. PRODUCT IMAGE
       Memastikan gambar produk proporsional dan memiliki efek zoom saat hover. */
    .product-card .card-img-top {
        border-top-left-radius: 20px;
        border-top-right-radius: 20px;
        transition: transform 0.6s ease;
    }

    .product-card:hover .card-img-top {
        transform: scale(1.05);
    }

    /* 3. BADGE DISKON (Glassmorphism)
       Tampilan badge diskon yang melayang dengan efek blur transparan. */
    .badge-discount {
        position: absolute;
        top: 15px;
        left: 15px;
        background: rgba(231, 76, 60, 0.9);
        backdrop-filter: blur(4px);
        color: white;
        padding: 5px 12px;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 2;
        box-shadow: 0 4px 10px rgba(231, 76, 60, 0.3);
    }

    /* 4. WISHLIST BUTTON
       Tombol hati yang muncul lebih elegan di pojok gambar. */
    .product-card .btn-light.rounded-circle {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        z-index: 3;
    }

    .product-card .btn-light.rounded-circle:hover {
        background: #fff;
        transform: scale(1.1);
        color: #e74c3c;
    }

    /* 5. PRODUCT INFO (Body) */
    .product-card .card-title a {
        font-weight: 700;
        color: #2d422d !important;
        font-size: 0.95rem;
        line-height: 1.4;
        transition: color 0.3s ease;
    }

    .product-card:hover .card-title a {
        color: #6ab04c !important;
    }

    .product-card .text-muted {
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
    }

    /* 6. PRICE & STOCK TAGS */
    .product-card .fw-bold.text-dark {
        font-size: 1.1rem;
        color: #2d3436 !important;
    }

    .text-decoration-line-through {
        font-size: 0.8rem;
        opacity: 0.6;
    }

    /* Animasi denyut halus untuk stok yang hampir habis */
    .text-warning {
        font-weight: 600;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* 7. CART BUTTON (Modern Green)
       Tombol tambah keranjang yang lebih lebar dan bersih. */
    .product-card .btn-primary {
        background: #6ab04c !important;
        border: none !important;
        border-radius: 12px;
        padding: 8px 15px;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .product-card .btn-primary:hover {
        background: #58943f !important;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(106, 176, 76, 0.3);
    }

    .product-card .btn-primary:disabled {
        background: #bdc3c7 !important;
        transform: none;
    }

    /* Footer adjustment */
    .product-card .card-footer {
        padding-bottom: 20px;
    }
</style>