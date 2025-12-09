<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\RecentlyViewedProduct;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Apply search filter
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        
        // Apply category filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }
        
        // Apply price range filters
        if ($request->has('min_price') && $request->min_price != '') {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price') && $request->max_price != '') {
            $query->where('price', '<=', $request->max_price);
        }
        
        // Apply sorting
        if ($request->has('sort') && $request->sort != '') {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('name', 'asc');
            }
        } else {
            $query->orderBy('name', 'asc');
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        
        // If this is an AJAX request, return only the product grid
        if ($request->ajax()) {
            return view('products.partials.product-grid', compact('products'))->render();
        }
        
        return view('products.index', compact('products', 'categories'));
    }
    
    /**
     * Display the specified product.
     */
    public function show($id)
    {
        $product = Product::with(['images', 'category'])->findOrFail($id);
        
        // Track recently viewed product for authenticated users
        if (Auth::check()) {
            // Check if the product is already in the recently viewed list
            $existingItem = RecentlyViewedProduct::where('user_id', Auth::id())
                ->where('product_id', $product->id)
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
                    'product_id' => $product->id,
                ]);
            }
        }
        
        return view('products.show', compact('product'));
    }
}