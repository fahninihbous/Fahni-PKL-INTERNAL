<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Renderable;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda website.
     * * @return Renderable
     */
    public function index(): Renderable
    {
        // 1. AMBIL DATA KATEGORI
        // Mengambil kategori aktif yang memiliki produk aktif dan tersedia stoknya
        $categories = Category::query()
            ->where('is_active', true) 
            ->withCount(['products' => function($q) {
                $q->where('is_active', true)
                  ->where('stock', '>', 0);
            }])
            ->having('products_count', '>', 0) // Hanya tampilkan kategori yang ada isinya
            ->orderBy('name')
            ->take(6)
            ->get();

        // 2. PRODUK UNGGULAN (FEATURED)
        // Menggunakan Scope yang sudah Anda buat di Model Product
        $featuredProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->active()   // Filter is_active = true
            ->inStock()  // Filter stock > 0
            ->featured() // Filter is_featured = true
            ->latest()
            ->take(8)
            ->get();

        // 3. PRODUK TERBARU
        $latestProducts = Product::query()
            ->with(['category', 'primaryImage'])
            ->active()   // Filter is_active = true
            ->inStock()  // Filter stock > 0
            ->latest()
            ->take(8)
            ->get();

        // 4. KIRIM DATA KE VIEW
        return view('home', compact(
            'categories',
            'featuredProducts',
            'latestProducts'
        ));
    }
}