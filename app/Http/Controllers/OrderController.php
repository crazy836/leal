<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = Order::where('user_id', Auth::id())->latest()->paginate(10);
        return view('orders.index', compact('orders'));
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
        // This is handled in the CheckoutController
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);
        
        // Get the next order (newer order)
        $nextOrder = Order::where('user_id', Auth::id())
            ->where('id', '>', $order->id)
            ->orderBy('id', 'asc')
            ->first();
            
        // Get the previous order (older order)
        $previousOrder = Order::where('user_id', Auth::id())
            ->where('id', '<', $order->id)
            ->orderBy('id', 'desc')
            ->first();
        
        return view('orders.show', compact('order', 'nextOrder', 'previousOrder'));
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