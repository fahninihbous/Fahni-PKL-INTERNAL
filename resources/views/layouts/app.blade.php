<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Toko Online') - {{ config('app.name') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- SweetAlert2 untuk Notifikasi Cantik --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')

    <script>
        /**
         * Fungsi AJAX untuk Toggle Wishlist (Global)
         */
        async function toggleWishlist(productId) {
            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch(`/wishlist/toggle/${productId}`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": token,
                    },
                });

                if (response.status === 401) {
                    window.location.href = "/login";
                    return;
                }

                const data = await response.json();

                if (data.status === "success") {
                    updateWishlistUI(productId, data.added); 
                    updateWishlistCounter(data.count); 
                    
                    // Notifikasi Toast
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });

                    Toast.fire({
                        icon: data.added ? 'success' : 'info',
                        title: data.message
                    });
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }

        function updateWishlistUI(productId, isAdded) {
            const buttons = document.querySelectorAll(`.wishlist-btn-${productId}`);
            buttons.forEach((btn) => {
                const icon = btn.querySelector("i");
                const textSpan = btn.querySelector("span"); // Untuk halaman detail yang ada teksnya

                if (isAdded) {
                    btn.classList.replace('btn-outline-danger', 'btn-danger');
                    if(icon) icon.classList.replace("bi-heart", "bi-heart-fill");
                    if(textSpan) textSpan.innerText = "Hapus dari Wishlist";
                } else {
                    btn.classList.replace('btn-danger', 'btn-outline-danger');
                    if(icon) icon.classList.replace("bi-heart-fill", "bi-heart");
                    if(textSpan) textSpan.innerText = "Tambah ke Wishlist";
                }
            });
        }

        function updateWishlistCounter(count) {
            const badge = document.getElementById("wishlist-count");
            if (badge) {
                badge.innerText = count;
                badge.classList.toggle('d-none', count === 0); // Sembunyikan jika 0
                
                // Animasi pop sedikit
                badge.style.transform = 'scale(1.3)';
                setTimeout(() => badge.style.transform = 'scale(1)', 200);
            }
        }
    </script>
    
    <style>
        #wishlist-count { transition: transform 0.2s ease; }
    </style>
</head>
<body>
    @include('partials.navbar')

    <div class="container mt-3">
        @include('partials.flash-messages')
    </div>

    <main class="min-vh-100">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>