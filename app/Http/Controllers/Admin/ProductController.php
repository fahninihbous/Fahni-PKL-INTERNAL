<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $products = Product::query()
            ->with(['category', 'primaryImage'])
            ->when($request->search, function ($query, $search) {
                $query->search($search); 
            })
            ->when($request->category, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('is_active', $request->status == 'active');
            })
            ->latest()           
            ->paginate(15)       
            ->withQueryString(); 

        $categories = Category::active()->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function store(StoreProductRequest $request): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();
            
            // Tambahkan data tambahan yang sudah diproses di Request atau dipaksa di sini
            $data['is_active'] = $request->has('is_active');
            $data['is_featured'] = $request->has('is_featured');
            $data['discount_price'] = $request->filled('discount_price') ? $request->discount_price : null;

            $product = Product::create($data);

            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Produk berhasil ditambahkan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Gagal menyimpan produk: ' . $e->getMessage());
        }
    }

    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        try {
            DB::beginTransaction();

            $data = $request->validated();

            // Pastikan discount_price terupdate dengan benar
            $data['is_active'] = $request->has('is_active');
            $data['is_featured'] = $request->has('is_featured');
            $data['discount_price'] = $request->filled('discount_price') ? $request->discount_price : null;

            $product->update($data);

            if ($request->hasFile('images')) {
                $this->uploadImages($request->file('images'), $product);
            }

            if ($request->has('delete_images')) {
                $this->deleteImages($request->delete_images);
            }

            DB::commit();

            return redirect()
                ->route('admin.products.index', $request->query())
                ->with('success', 'Produk berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, Product $product): RedirectResponse
    {
        try {
            return DB::transaction(function () use ($product, $request) {
                $imagePaths = $product->images->pluck('image_path')->toArray();

                $product->delete();

                foreach ($imagePaths as $path) {
                    Storage::disk('public')->delete($path);
                }

                return redirect()->route('admin.products.index', $request->query())
                    ->with('success', 'Produk berhasil dihapus!');
            });

        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == "23000") {
                return back()->with('error', 'Gagal: Produk ini sudah memiliki riwayat transaksi.');
            }
            return back()->with('error', 'Gagal menghapus produk karena kendala database.');
        }
    }

    // --- Helper Methods ---

    protected function uploadImages(array $files, Product $product): void
    {
        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($files as $index => $file) {
            $path = $file->store('products', 'public');

            $product->images()->create([
                'image_path' => $path,
                'is_primary' => !$hasPrimary && $index === 0,
                'sort_order' => $product->images()->count(),
            ]);
            
            if (!$hasPrimary) $hasPrimary = true;
        }
    }

    protected function deleteImages(array $imageIds): void
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();
        foreach ($images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
    }
}