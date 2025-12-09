<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_categories' => Category::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
        ];
        
        // Fetch all orders with user information
        $allOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('admin.dashboard', compact('stats', 'allOrders'));
    }
}