<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Order::with('user');
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Search by order number if provided
        if ($request->has('search') && $request->search != '') {
            $query->where('order_number', 'like', '%' . $request->search . '%');
        }
        
        $orders = $query->latest()->paginate(15);
        $statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        
        return view('admin.orders.index', compact('orders', 'statuses'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }
    
    /**
     * Update the status of the specified order.
     */
    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);
        
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        
        // Set shipped_at or delivered_at timestamps if applicable
        if ($request->status == 'shipped' && !$order->shipped_at) {
            $order->shipped_at = now();
        } elseif ($request->status == 'delivered' && !$order->delivered_at) {
            $order->delivered_at = now();
        }
        
        $order->save();
        
        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully!');
    }
    
    /**
     * Print the order receipt.
     */
    public function print(string $id)
    {
        $order = Order::with(['user', 'items.product'])->findOrFail($id);
        return view('admin.orders.print', compact('order'));
    }
}