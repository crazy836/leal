<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Display the shopping cart.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $cartItems = $this->getCartItems();
        $total = $this->calculateTotal($cartItems);
        
        return view('cart.index', compact('cartItems', 'total'));
    }
    
    /**
     * Add a product to the shopping cart.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        $product = Product::findOrFail($request->product_id);
        
        if (Auth::check()) {
            // For logged-in users
            $cartItem = CartItem::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'product_id' => $product->id,
                ],
                [
                    'quantity' => $request->quantity,
                    'options' => [],
                ]
            );
        } else {
            // For guest users
            $cart = Session::get('cart', []);
            $productId = $product->id;
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $request->quantity;
            } else {
                $cart[$productId] = [
                    'product_id' => $product->id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                    'name' => $product->name,
                ];
            }
            
            Session::put('cart', $cart);
        }
        
        // Redirect to cart page with success message
        return redirect()->route('cart.index')->with('success', 'Product added to cart successfully.');
    }
    
    /**
     * Update the quantity of a cart item.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        
        if (Auth::check()) {
            // For logged-in users
            $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
            $cartItem->update(['quantity' => $request->quantity]);
        } else {
            // For guest users
            $cart = Session::get('cart', []);
            
            if (isset($cart[$id])) {
                $cart[$id]['quantity'] = $request->quantity;
                Session::put('cart', $cart);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully.',
            'cart_count' => $this->getCartCount(),
            'total' => $this->calculateTotal($this->getCartItems()),
        ]);
    }
    
    /**
     * Remove a cart item.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::check()) {
            // For logged-in users
            $cartItem = CartItem::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
            $cartItem->delete();
        } else {
            // For guest users
            $cart = Session::get('cart', []);
            
            if (isset($cart[$id])) {
                unset($cart[$id]);
                Session::put('cart', $cart);
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart.',
            'cart_count' => $this->getCartCount(),
            'total' => $this->calculateTotal($this->getCartItems()),
        ]);
    }
    
    /**
     * Get cart items for the current user.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getCartItems()
    {
        if (Auth::check()) {
            // For logged-in users
            return CartItem::with('product')->where('user_id', Auth::id())->get();
        } else {
            // For guest users
            $cart = Session::get('cart', []);
            $cartItems = collect();
            
            foreach ($cart as $item) {
                $product = Product::find($item['product_id']);
                if ($product) {
                    $cartItems->push((object)[
                        'id' => $item['product_id'],
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }
            }
            
            return $cartItems;
        }
    }
    
    /**
     * Get the total number of items in the cart.
     *
     * @return int
     */
    private function getCartCount()
    {
        if (Auth::check()) {
            // For logged-in users
            return CartItem::where('user_id', Auth::id())->sum('quantity');
        } else {
            // For guest users
            $cart = Session::get('cart', []);
            $count = 0;
            
            foreach ($cart as $item) {
                $count += $item['quantity'];
            }
            
            return $count;
        }
    }
    
    /**
     * Calculate the total price of items in the cart.
     *
     * @param  \Illuminate\Support\Collection  $cartItems
     * @return float
     */
    private function calculateTotal($cartItems)
    {
        return $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
    }
}