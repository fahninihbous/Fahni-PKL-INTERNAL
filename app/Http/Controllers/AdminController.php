<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }
    public function dashboard()
    {
        $stats =[
            'users'  => User::count(),
            'products'  => Product::count(),
            'category'  => Category::count(),
            'total_orders'  => Order::count(),
            'total_revenue'  => Order::sum('total_amount'),
            'pending_orders'  => Order::where('status','pending')->count(),
            'low_stock'  => Product::where('stock','<',5)->count(),
        ];

        $recentOrders = Order::latest()->take(5)->get();

       return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
