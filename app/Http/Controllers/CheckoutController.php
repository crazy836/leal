<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\Charge;
use Stripe\PaymentIntent;
// PayPal SDK
use PaypalServerSdkLib\Environment;
use PaypalServerSdkLib\PaypalServerSdkClientBuilder;
use PaypalServerSdkLib\Authentication\ClientCredentialsAuthCredentialsBuilder;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to checkout.');
        }
        
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $addresses = Address::where('user_id', Auth::id())->get();
        
        return view('checkout.index', compact('cartItems', 'total', 'addresses'));
    }
    
    /**
     * Process the checkout.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Please login to checkout.']);
        }
        
        $cartItems = CartItem::with('product')->where('user_id', Auth::id())->get();
        
        if ($cartItems->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Your cart is empty.']);
        }
        
        // Validate shipping address fields
        $request->validate([
            'shipping_first_name' => 'required|string|max:255',
            'shipping_last_name' => 'required|string|max:255',
            'shipping_address_line_1' => 'required|string|max:255',
            'shipping_address_line_2' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_postal_code' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'payment_method' => 'required|in:credit_card,paypal,cash_on_delivery',
        ]);
        
        // Create shipping address array (use for both billing and shipping)
        $shippingAddress = [
            'first_name' => $request->shipping_first_name,
            'last_name' => $request->shipping_last_name,
            'address_line_1' => $request->shipping_address_line_1,
            'address_line_2' => $request->shipping_address_line_2,
            'city' => $request->shipping_city,
            'state' => $request->shipping_state,
            'postal_code' => $request->shipping_postal_code,
            'country' => $request->shipping_country,
            'phone' => $request->shipping_phone,
        ];
        
        // Use the same address for billing
        $billingAddress = $shippingAddress;
        
        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });
        
        $taxAmount = 0; // For simplicity, we're not calculating tax
        $shippingAmount = 0; // Assuming free shipping
        $discountAmount = 0; // No discount for now
        $totalAmount = $subtotal + $taxAmount + $shippingAmount - $discountAmount;
        
        // Handle payment based on selected method
        switch ($request->payment_method) {
            case 'credit_card':
                return $this->processStripePayment($request, $cartItems, $billingAddress, $shippingAddress, $subtotal, $taxAmount, $shippingAmount, $discountAmount, $totalAmount);
            case 'paypal':
                return $this->processPayPalPayment($request, $cartItems, $billingAddress, $shippingAddress, $subtotal, $taxAmount, $shippingAmount, $discountAmount, $totalAmount);
            case 'cash_on_delivery':
                return $this->processCashOnDelivery($request, $cartItems, $billingAddress, $shippingAddress, $subtotal, $taxAmount, $shippingAmount, $discountAmount, $totalAmount);
            default:
                return response()->json(['success' => false, 'message' => 'Invalid payment method selected.']);
        }
    }
    
    /**
     * Process Stripe payment
     */
    private function processStripePayment($request, $cartItems, $billingAddress, $shippingAddress, $subtotal, $taxAmount, $shippingAmount, $discountAmount, $totalAmount)
    {
        // Set Stripe secret key
        Stripe::setApiKey(env('STRIPE_SECRET'));
        
        try {
            // Create a PaymentIntent
            $paymentIntent = PaymentIntent::create([
                'amount' => $totalAmount * 100, // Amount in cents
                'currency' => 'usd',
                'metadata' => [
                    'user_id' => Auth::id(),
                ],
            ]);
            
            // Create order
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_status' => 'paid',
                'payment_method' => 'credit_card',
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,
            ]);
            
            // Create payment log
            PaymentLog::create([
                'order_id' => $order->id,
                'payment_gateway' => 'stripe',
                'transaction_id' => $paymentIntent->id,
                'status' => 'completed',
                'amount' => $totalAmount,
                'currency' => 'USD',
                'response_data' => json_encode($paymentIntent),
            ]);
            
            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->product->price * $cartItem->quantity,
                ]);
            }
            
            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();
            
            // Return success response
            return response()->json(['success' => true, 'message' => 'Order placed successfully!', 'order_id' => $order->id]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Stripe Payment Error: ' . $e->getMessage());
            
            return response()->json(['success' => false, 'message' => 'Payment failed: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Process PayPal payment
     */
    private function processPayPalPayment($request, $cartItems, $billingAddress, $shippingAddress, $subtotal, $taxAmount, $shippingAmount, $discountAmount, $totalAmount)
    {
        try {
            // Create order first
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'paypal',
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,
            ]);
            
            // Create payment log
            PaymentLog::create([
                'order_id' => $order->id,
                'payment_gateway' => 'paypal',
                'status' => 'pending',
                'amount' => $totalAmount,
                'currency' => 'USD',
            ]);
            
            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->product->price * $cartItem->quantity,
                ]);
            }
            
            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();
            
            // Generate PayPal redirect URL using SDK
            $paypalRedirectUrl = $this->createPayPalPayment($order, $totalAmount);
            
            // For immediate redirect to PayPal, we return the redirect URL directly
            if ($paypalRedirectUrl && $paypalRedirectUrl !== route('checkout.paypal.redirect', ['order' => $order->id])) {
                // Redirect to PayPal immediately
                return response()->json([
                    'success' => true, 
                    'message' => 'Redirecting to PayPal for payment.',
                    'redirect_url' => $paypalRedirectUrl,
                    'order_id' => $order->id
                ]);
            } else {
                // Show the PayPal redirect page if there was an issue
                $paypalRedirectUrl = route('checkout.paypal.redirect', ['order' => $order->id]);
                return response()->json([
                    'success' => true, 
                    'message' => 'Order placed successfully! Redirecting to PayPal payment page.',
                    'redirect_url' => $paypalRedirectUrl,
                    'order_id' => $order->id
                ]);
            }
        } catch (\Exception $e) {
            // Log the error
            \Log::error('PayPal Payment Error: ' . $e->getMessage());
            
            return response()->json(['success' => false, 'message' => 'There was an error processing your PayPal payment. Please try again or contact support.']);
        }
    }
    
    /**
     * Create PayPal payment using SDK
     */
    private function createPayPalPayment($order, $amount)
    {
        // Check if PayPal credentials are configured
        if (empty(env('PAYPAL_CLIENT_ID')) || empty(env('PAYPAL_SECRET'))) {
            \Log::warning('PayPal credentials not configured');
            // Fallback to manual redirect if credentials are missing
            return route('checkout.paypal.redirect', ['order' => $order->id]);
        }
        
        // For now, return the redirect page URL since we're having issues with the SDK
        // In a proper implementation, we would use the PayPal SDK here
        \Log::warning('PayPal SDK not working, falling back to manual redirect');
        return route('checkout.paypal.redirect', ['order' => $order->id]);
    }
    
    /**
     * Process Cash on Delivery
     */
    private function processCashOnDelivery($request, $cartItems, $billingAddress, $shippingAddress, $subtotal, $taxAmount, $shippingAmount, $discountAmount, $totalAmount)
    {
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'order_number' => 'ORD-' . strtoupper(Str::random(8)),
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'discount_amount' => $discountAmount,
                'total_amount' => $totalAmount,
                'currency' => 'USD',
                'status' => 'pending',
                'payment_status' => 'pending',
                'payment_method' => 'cash_on_delivery',
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,
            ]);
            
            // Create payment log
            PaymentLog::create([
                'order_id' => $order->id,
                'payment_gateway' => 'cash_on_delivery',
                'status' => 'pending',
                'amount' => $totalAmount,
                'currency' => 'USD',
            ]);
            
            // Create order items
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'price' => $cartItem->product->price,
                    'quantity' => $cartItem->quantity,
                    'total' => $cartItem->product->price * $cartItem->quantity,
                ]);
            }
            
            // Clear cart
            CartItem::where('user_id', Auth::id())->delete();
            
            // Return success response
            return response()->json(['success' => true, 'message' => 'Order placed successfully!', 'order_id' => $order->id]);
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Cash on Delivery Error: ' . $e->getMessage());
            
            return response()->json(['success' => false, 'message' => 'Order failed: ' . $e->getMessage()]);
        }
    }
    
    /**
     * Display the PayPal redirect page.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function paypalRedirect(Request $request)
    {
        $orderId = $request->query('order');
        $order = null;
        
        if ($orderId) {
            $order = Order::where('user_id', Auth::id())->find($orderId);
        }
        
        if (!$order) {
            return redirect()->route('checkout.index')->with('error', 'Invalid order.');
        }
        
        // For SDK integration, we pass the order to the view
        // The actual PayPal redirect happens in the processPayPalPayment method
        return view('checkout.paypal_redirect', compact('order'));
    }
    
    /**
     * Handle PayPal payment completion callback.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function paypalCallback(Request $request)
    {
        // Get the order ID from the request
        $orderId = $request->query('order') ?? $request->query('cm') ?? $request->query('custom');
        
        if ($orderId) {
            // Update the order status to paid
            $order = Order::find($orderId);
            if ($order && $order->payment_status === 'pending') {
                $order->payment_status = 'paid';
                $order->status = 'processing';
                $order->save();
                
                // Update payment log
                $paymentLog = PaymentLog::where('order_id', $order->id)->first();
                if ($paymentLog) {
                    $paymentLog->status = 'completed';
                    $paymentLog->save();
                }
            }
        } else {
            // Log error if no order ID is found
            \Log::warning('PayPal callback received without order ID', $request->all());
        }
        
        return redirect()->route('checkout.success', ['order' => $orderId]);
    }
    
    /**
     * Handle PayPal IPN (Instant Payment Notification).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function paypalIPN(Request $request)
    {
        // In a real implementation, you would verify the IPN notification from PayPal
        // This is a simplified version for demonstration purposes
        
        // Log the IPN data
        \Log::info('PayPal IPN received', $request->all());
        
        // For now, just return a success response
        return response('OK', 200);
    }
    
    /**
     * Display the checkout success page.
     *
     * @return \Illuminate\View\View
     */
    public function success(Request $request)
    {
        $orderId = $request->query('order');
        $order = null;
        
        if ($orderId) {
            $order = Order::where('user_id', Auth::id())->find($orderId);
        }
        
        return view('checkout.success', compact('order'));
    }
}