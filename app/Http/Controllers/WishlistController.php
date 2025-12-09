<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $wishlistItems = Wishlist::with('product.images')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();
        
        return view('wishlist.index', compact('wishlistItems'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        
        // Check if the product is already in the wishlist
        $existingItem = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->first();
        
        if ($existingItem) {
            return response()->json([
                'success' => false,
                'message' => 'Product is already in your wishlist.'
            ]);
        }
        
        // Add product to wishlist
        $wishlistItem = Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Product added to wishlist successfully!',
            'wishlist_count' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();
        
        $wishlistItem->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Product removed from wishlist successfully!',
            'wishlist_count' => Wishlist::where('user_id', Auth::id())->count()
        ]);
    }
}