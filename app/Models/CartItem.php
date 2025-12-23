<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    // ⬇️ Supaya atribut subtotal bisa dipanggil di Blade
    protected $appends = ['subtotal'];

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Accessor: hitung subtotal otomatis
     * Dipakai di Blade: $item->subtotal
     */
    public function getSubtotalAttribute()
    {
        // Pastikan field harga produk sesuai (price)
        return $this->quantity * $this->product->price;
    }
}
