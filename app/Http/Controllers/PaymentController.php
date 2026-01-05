<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Ambil Snap Token Midtrans (dipanggil via AJAX / frontend)
     */
    public function getSnapToken(Order $order, MidtransService $midtransService)
    {
        // Pastikan user pemilik order
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        // Jika sudah dibayar, stop
        if ($order->payment_status === 'paid') {
            return response()->json([
                'error' => 'Pesanan sudah dibayar.'
            ], 400);
        }

        try {
            // Generate Snap Token
            $snapToken = $midtransService->createSnapToken($order);

            // Simpan token ke DB
            $order->update([
                'snap_token' => $snapToken
            ]);

            return response()->json([
                'token' => $snapToken
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Halaman sukses setelah pembayaran
     */
    public function success(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payments.success', compact('order'));
    }

    /**
     * Halaman pending (menunggu pembayaran)
     */
    public function pending(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        return view('payments.pending', compact('order'));
    }
}
