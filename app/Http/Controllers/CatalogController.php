<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index(Request $request)
    {
        // 1. BASE QUERY
        $query = Product::query()
            ->with(['category', 'primaryImage'])
            ->available(); 

        // 2. FILTERING PIPELINE
        
        // Filter: Search keyword
        if ($request->filled('q')) {
            $query->search($request->q);
        }

        // Filter: Category by Slug
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // --- FILTER BARU: Flash Sale / Promo (?promo=1) ---
        if ($request->filled('promo')) {
            $query->whereNotNull('discount_price')
                  ->where('discount_price', '>', 0);
        }

        // Filter: Price Range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // 3. SORTING LOGIC
        $sort = $request->get('sort', 'newest');
        $query->when($sort === 'price_asc', fn($q) => $q->orderBy('price', 'asc'))
              ->when($sort === 'price_desc', fn($q) => $q->orderBy('price', 'desc'))
              ->when($sort === 'name_asc', fn($q) => $q->orderBy('name', 'asc'))
              ->when($sort === 'name_desc', fn($q) => $q->orderBy('name', 'desc'))
              ->when($sort === 'newest', fn($q) => $q->latest());

        // 4. EXECUTE & PAGINATE
        $products = $query->paginate(12)->withQueryString();

        // 5. DATA PENDUKUNG VIEW
        $categories = Category::active()
            ->withCount(['products' => fn($q) => $q->available()])
            ->having('products_count', '>', 0)
            ->orderBy('name')
            ->get();

        $priceRange = Product::available()
            ->selectRaw('MIN(price) as min, MAX(price) as max')
            ->first();

        return view('catalog.index', compact('products', 'categories', 'priceRange'));
    }

    public function show($slug)
    {
        $product = Product::available()
            ->with(['category', 'images'])
            ->where('slug', $slug)
            ->firstOrFail();

        return view('catalog.show', compact('product'));
    }
}