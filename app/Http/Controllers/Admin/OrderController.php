<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        // Menggunakan paginate agar tidak error "Method links does not exist"
        $orders = Order::latest()->paginate(5);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        // with(['items.product', 'user']) disebut Eager Loading.
        // Ini wajib agar data produk dan pembeli terbaca di file Blade Anda.
        $order = Order::with(['items.product', 'user'])->findOrFail($id);

        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Validasi input status
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function salesReport()
    {
        return view('admin.reporst.sales');
    }
}