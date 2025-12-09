<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CompareProduct;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CompareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $compareItems = CompareProduct::with('product.images', 'product.category')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        
        return view('compare.index', compact('compareItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        // Check if the product is already in the compare list
        $existingItem = CompareProduct::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();
        
        if ($existingItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already in your compare list.'
            ]);
        }
        
        // Check if the user has reached the maximum number of items in compare list (5)
        $compareCount = CompareProduct::where('user_id', Auth::id())->count();
        if ($compareCount >= 5) {
            return response()->json([
                'success' => false,
                'message' => 'You can only compare up to 5 products.'
            ]);
        }
        
        // Add product to compare list
        $compareItem = CompareProduct::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to compare list successfully!',
            'compare_count' => CompareProduct::where('user_id', Auth::id())->count()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $compareItem = CompareProduct::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $compareItem->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Product removed from compare list successfully!',
            'compare_count' => CompareProduct::where('user_id', Auth::id())->count()
        ]);
    }
    
    /**
     * Clear all items from the compare list.
     */
    public function clear()
    {
        CompareProduct::where('user_id', Auth::id())->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Compare list cleared successfully!',
            'compare_count' => 0
        ]);
    }
}