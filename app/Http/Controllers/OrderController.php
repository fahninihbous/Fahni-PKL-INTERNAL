<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Order;
use Midtrans\Snap;
use Midtrans\Config;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan milik user yang sedang login.
     */
    public function index()
    {
        // PENTING: Jangan gunakan Order::all() !
        // Kita hanya mengambil order milik user yg sedang login menggunakan relasi hasMany.
        // auth()->user()->orders() akan otomatis memfilter: WHERE user_id = current_user_id
        $orders = auth()->user()->orders()
            ->with(['items.product']) // Eager Load nested: Order -> OrderItems -> Product
            ->latest() // Urutkan dari pesanan terbaru
            ->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail satu pesanan.
     */
    
public function show(Order $order)
{
    // 1. Authorize (Security Check)
    if ($order->user_id !== auth()->id()) {
        abort(403, 'Anda tidak memiliki akses ke pesanan ini.');
    }

    // 2. Load relasi detail
    $order->load(['items.product', 'items.product.primaryImage']);

    $snapToken = null;

    // 3. Generate Snap Token jika masih pending & belum dibayar
    if ($order->status === 'pending' && $order->payment_status === 'unpaid') {

        if ($order->snap_token) {

            $snapToken = $order->snap_token;

        } else {

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $params = [
                'transaction_details' => [
                    'order_id' => $order->order_number,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => $order->shipping_name,
                    'phone' => $order->shipping_phone,
                ],
            ];

            $snapToken = Snap::getSnapToken($params);

            $order->snap_token = $snapToken;
            $order->save();
        }
    }

    return view('orders.show', compact('order', 'snapToken'));
}
public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,processing,completed'
    ]);

    $order = Order::findOrFail($id);

    $order->status = $request->status;

    // OPTIONAL: kalau completed, pastikan payment_status paid
    if ($request->status === 'completed' && $order->payment_status !== 'paid') {
        $order->payment_status = 'paid';
    }

    $order->save();

    return redirect()->back()->with('success', 'Status pesanan berhasil diupdate');
}

}