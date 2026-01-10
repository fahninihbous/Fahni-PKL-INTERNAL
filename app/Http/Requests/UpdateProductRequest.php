<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // atau auth()->check() jika perlu
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Ambil ID produk yang sedang diedit
        $productId = $this->route('product')->id;

        return [
            // Field utama produk - WAJIB ada di sini!
            'name'            => ['required', 'string', 'max:255', Rule::unique('products')->ignore($productId)],
            'description'     => ['nullable', 'string'],
            'price'           => ['required', 'numeric', 'min:0'],
            'stock'           => ['required', 'integer', 'min:0'],
            'weight'          => ['required', 'integer', 'min:1'], // jika wajib
            'category_id'     => ['required', 'exists:categories,id'],

            // Gambar baru (opsional)
            'images'          => ['nullable', 'array'],
            'images.*'        => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'], // max 5MB

            // Gambar yang akan dihapus
            'delete_images'   => ['nullable', 'array'],
            'delete_images.*' => ['exists:product_images,id'],

            // Set gambar utama
            'primary_image'   => ['nullable', 'exists:product_images,id'],

            // Status
            'is_active'       => ['boolean'],
            'is_featured'     => ['boolean'],
        ];
    }

    /**
     * Custom message (opsional)
     */
    public function messages(): array
    {
        return [
            'name.required'        => 'Nama produk wajib diisi.',
            'name.unique'          => 'Nama produk sudah digunakan.',
            'price.required'       => 'Harga wajib diisi.',
            'category_id.required' => 'Pilih kategori produk.',
            'weight.required'      => 'Berat produk wajib diisi untuk ongkir.',
        ];
    }
}