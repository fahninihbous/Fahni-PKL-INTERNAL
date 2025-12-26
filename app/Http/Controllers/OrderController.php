<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;

class OrderController extends Controller
{
    public function index()
    {
        $orders = auth()->user()->orders()
            ->with(['items.product'])
            ->latest()
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function show(Order $order, MidtransService $midtrans)
    {
        // 🔐 Security
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Load relasi
        $order->load(['items.product', 'items.product.primaryImage']);

        // DEFAULT
        $snapToken = null;

        // HANYA BUAT TOKEN JIKA PENDING
        if ($order->status === 'pending') {

            // Pakai token lama jika sudah ada
            if ($order->snap_token) {
                $snapToken = $order->snap_token;
            } else {
                // Buat token baru
                $snapToken = $midtrans->createSnapToken($order);

                // Simpan ke DB
                $order->update([
                    'snap_token' => $snapToken
                ]);
            }
        }

        return view('orders.show', compact('order', 'snapToken'));
    }
}
