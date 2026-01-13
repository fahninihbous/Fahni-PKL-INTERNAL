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
     * Accessor: hitung subtotal otomatis menggunakan harga diskon (display_price)
     */
    public function getSubtotalAttribute()
    {
        // Mengambil display_price dari model Product yang sudah menghandle logika diskon
        return $this->quantity * $this->product->display_price;
    }
}