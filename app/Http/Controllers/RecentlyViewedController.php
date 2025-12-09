<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RecentlyViewedProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class RecentlyViewedController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recentlyViewedItems = RecentlyViewedProduct::with('product.images', 'product.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->limit(10)
            ->get();
        
        return view('recently-viewed.index', compact('recentlyViewedItems'));
    }
    
    /**
     * Add a product to the recently viewed list.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        // Check if the product is already in the recently viewed list
        $existingItem = RecentlyViewedProduct::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();
        
        if ($existingItem) {
            // Update the timestamp to move it to the top
            $existingItem->touch();
        } else {
            // Add product to recently viewed list
            // First, check if we have 10 items already, if so remove the oldest
            $recentlyViewedCount = RecentlyViewedProduct::where('user_id', Auth::id())->count();
            if ($recentlyViewedCount >= 10) {
                $oldestItem = RecentlyViewedProduct::where('user_id', Auth::id())
                    ->oldest()
                    ->first();
                if ($oldestItem) {
                    $oldestItem->delete();
                }
            }
            
            RecentlyViewedProduct::create([
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to recently viewed list.'
        ]);
    }
    
    /**
     * Clear all items from the recently viewed list.
     */
    public function clear()
    {
        RecentlyViewedProduct::where('user_id', Auth::id())->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Recently viewed list cleared successfully!'
        ]);
    }
}