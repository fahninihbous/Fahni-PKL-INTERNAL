<?php

namespace App\Services;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    /**
     * Membuat Order baru dengan harga yang sudah disesuaikan (Diskon).
     */
    public function createOrder(User $user, array $shippingData): Order
    {
        $cart = $user->cart;

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception("Keranjang belanja kosong.");
        }

        return DB::transaction(function () use ($user, $cart, $shippingData) {

            // 1. Validasi Stok & Hitung Total menggunakan Harga Diskon (display_price)
            $totalAmount = 0;
            foreach ($cart->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception("Stok produk {$item->product->name} tidak mencukupi.");
                }
                // Gunakan display_price untuk kalkulasi total header
                $totalAmount += $item->product->display_price * $item->quantity;
            }

            // 2. Buat Header Order
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'shipping_name' => $shippingData['name'],
                'shipping_address' => $shippingData['address'],
                'shipping_phone' => $shippingData['phone'],
                'total_amount' => $totalAmount,
            ]);

            // 3. Pindahkan Items & Snapshot Harga Diskon
            foreach ($cart->items as $item) {
                $product = $item->product;
                $effectivePrice = $product->display_price; // Ambil harga diskon

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $product->name,
                    'price' => $effectivePrice, // Simpan harga diskon ke order_items
                    'quantity' => $item->quantity,
                    'subtotal' => $effectivePrice * $item->quantity,
                ]);

                // 4. Kurangi Stok secara Atomic
                $product->decrement('stock', $item->quantity);
            }

            // 5. Bersihkan Keranjang
            $cart->items()->delete();

            return $order;
        });
    }
}