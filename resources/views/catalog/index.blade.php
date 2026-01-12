@extends('layouts.app')

@section('content')
<div class="garden-catalog-wrapper py-5">
    <div class="container">
        <div class="row g-4">
            {{-- SIDEBAR FILTER --}}
            <div class="col-lg-3">
                <div class="sticky-top" style="top: 20px;">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-garden-dark text-white fw-bold py-3">
                            <i class="bi bi-filter-left me-2"></i>Filter Produk
                        </div>
                        <div class="card-body bg-white p-4">
                            <form action="{{ route('catalog.index') }}" method="GET">
                                @if(request('q')) <input type="hidden" name="q" value="{{ request('q') }}"> @endif
                                
                                {{-- Hidden input untuk menjaga state promo saat filter lain diterapkan --}}
                                @if(request('promo')) <input type="hidden" name="promo" value="1"> @endif

                                {{-- Filter Kategori --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold text-garden-primary mb-3 text-uppercase small ls-1">Kategori</h6>
                                    @foreach($categories as $cat)
                                        <div class="form-check custom-garden-check mb-2">
                                            <input class="form-check-input" type="radio" name="category" value="{{ $cat->slug }}"
                                                id="cat-{{ $cat->slug }}"
                                                {{ request('category') == $cat->slug ? 'checked' : '' }}
                                                onchange="this.form.submit()">
                                            <label class="form-check-label d-flex justify-content-between align-items-center w-100" for="cat-{{ $cat->slug }}">
                                                <span>{{ $cat->name }}</span>
                                                <span class="badge rounded-pill bg-light text-garden-muted border small">{{ $cat->products_count }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                {{-- Filter Harga --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold text-garden-primary mb-3 text-uppercase small ls-1">Rentang Harga</h6>
                                    <div class="input-group input-group-sm mb-2 shadow-sm rounded-pill overflow-hidden border">
                                        <span class="input-group-text border-0 bg-white text-muted small">Rp</span>
                                        <input type="number" name="min_price" class="form-control border-0" placeholder="Min" value="{{ request('min_price') }}">
                                    </div>
                                    <div class="input-group input-group-sm mb-3 shadow-sm rounded-pill overflow-hidden border">
                                        <span class="input-group-text border-0 bg-white text-muted small">Rp</span>
                                        <input type="number" name="max_price" class="form-control border-0" placeholder="Max" value="{{ request('max_price') }}">
                                    </div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-garden rounded-pill shadow-sm fw-bold">Terapkan Filter</button>
                                    <a href="{{ route('catalog.index') }}" class="btn btn-outline-garden-muted rounded-pill btn-sm fw-bold border-0">Reset Filter</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PRODUCT GRID --}}
            <div class="col-lg-9">
                {{-- Header Katalog --}}
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-md-center p-4 gap-3">
                        <div>
                            <h4 class="fw-bold text-garden-dark mb-0">Jelajahi Katalog</h4>
                            <p class="text-garden-muted small mb-0">Menampilkan {{ $products->total() }} produk berkualitas untuk Anda.</p>
                        </div>
                        
                        <div class="d-flex align-items-center gap-2">
                            <span class="text-muted small fw-bold text-uppercase ls-1 d-none d-sm-inline">Urutkan:</span>
                            <form method="GET" class="d-inline-block">
                                @foreach(request()->except('sort') as $key => $value)
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endforeach
                                <select name="sort" class="form-select form-select-sm border-0 bg-light rounded-pill px-3 fw-bold text-garden-primary shadow-sm" onchange="this.form.submit()">
                                    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- --- INDIKATOR FILTER PROMO FLASH SALE --- --}}
                @if(request()->filled('promo'))
                    <div class="alert alert-warning border-0 shadow-sm rounded-4 d-flex justify-content-between align-items-center mb-4 p-3 bg-white border-start border-warning border-4">
                        <div class="d-flex align-items-center">
                            <div class="bg-warning p-2 rounded-circle me-3 text-dark shadow-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div>
                                <span class="d-block fw-bold text-dark">Mode Flash Sale Aktif</span>
                                <small class="text-muted">Hanya menampilkan produk dengan harga diskon.</small>
                            </div>
                        </div>
                        <a href="{{ route('catalog.index', request()->except('promo')) }}" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                            <i class="bi bi-x-lg me-1"></i> Tutup
                        </a>
                    </div>
                @endif

                {{-- Row Produk --}}
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    @forelse($products as $product)
                        <div class="col">
                            <div class="garden-product-item">
                                <x-product-card :product="$product" />
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center py-5 bg-white rounded-4 shadow-sm my-4">
                            <div class="leaf-empty-icon mb-4 mx-auto">
                                <i class="bi bi-search text-garden-secondary"></i>
                            </div>
                            <h5 class="fw-bold text-garden-dark">Produk tidak ditemukan</h5>
                            <p class="text-garden-muted">Maaf, kami tidak menemukan produk yang sesuai dengan kriteria filter Anda.</p>
                            <a href="{{ route('catalog.index') }}" class="btn btn-garden rounded-pill px-4 shadow-sm">Lihat Semua Produk</a>
                        </div>
                    @endforelse
                </div>

                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* GARDEN THEME VARIABLES */
    :root {
        --garden-primary: #2d5a27;      /* Hijau Tua */
        --garden-secondary: #8da684;    /* Hijau Sage */
        --garden-light: #f3f6f2;        /* Background */
        --garden-dark: #1e351b;         /* Text Dark */
        --garden-muted: #6b7c65;        /* Muted Green */
    }

    .garden-catalog-wrapper {
        background-color: var(--garden-light);
        min-height: 100vh;
    }

    .bg-garden-dark { background-color: var(--garden-dark) !important; }
    .text-garden-primary { color: var(--garden-primary) !important; }
    .text-garden-dark { color: var(--garden-dark) !important; }
    .text-garden-muted { color: var(--garden-muted) !important; }
    .ls-1 { letter-spacing: 1px; }

    /* BUTTONS */
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
    }
    .btn-outline-garden-muted {
        color: var(--garden-muted);
    }

    /* CUSTOM CHECKBOX/RADIO */
    .custom-garden-check .form-check-input:checked {
        background-color: var(--garden-primary);
        border-color: var(--garden-primary);
    }
    .custom-garden-check .form-check-label {
        cursor: pointer;
        transition: color 0.2s ease;
    }
    .custom-garden-check:hover .form-check-label {
        color: var(--garden-primary);
    }

    /* PRODUCT CARD WRAPPER */
    .garden-product-item {
        transition: transform 0.3s ease;
    }
    .garden-product-item:hover {
        transform: translateY(-8px);
    }

    /* EMPTY STATE ICON */
    .leaf-empty-icon {
        width: 100px;
        height: 100px;
        background: #e9f0e8;
        border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
    }

    /* PAGINATION OVERRIDE */
    .pagination .page-link {
        color: var(--garden-primary);
        border: none;
        background: white;
        margin: 0 3px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .pagination .page-item.active .page-link {
        background-color: var(--garden-primary);
        color: white;
    }
</style>
@endsection