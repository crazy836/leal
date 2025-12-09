@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Order Details</h1>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="bi bi-printer"></i> Print Order
                </button>
            </div>
            
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Order #{{ $order->order_number }}</h5>
                        <span class="badge bg-{{ $order->status == 'pending' ? 'warning' : ($order->status == 'processing' ? 'info' : ($order->status == 'shipped' ? 'primary' : ($order->status == 'delivered' ? 'success' : 'danger'))) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Billing Address</h6>
                            @php
                                // Check if billing_address is already an array or needs to be decoded
                                if (is_array($order->billing_address)) {
                                    $billingAddress = $order->billing_address;
                                } elseif (is_string($order->billing_address)) {
                                    $billingAddress = json_decode($order->billing_address, true);
                                } else {
                                    $billingAddress = [];
                                }
                            @endphp
                            <address>
                                @if(!empty($billingAddress))
                                    <strong>{{ $billingAddress['first_name'] }} {{ $billingAddress['last_name'] }}</strong><br>
                                    {{ $billingAddress['address_line_1'] }}<br>
                                    @if(!empty($billingAddress['address_line_2']))
                                        {{ $billingAddress['address_line_2'] }}<br>
                                    @endif
                                    {{ $billingAddress['city'] }}, {{ $billingAddress['state'] }} {{ $billingAddress['postal_code'] }}<br>
                                    {{ $billingAddress['country'] }}<br>
                                    @if(!empty($billingAddress['phone']))
                                        Phone: {{ $billingAddress['phone'] }}
                                    @endif
                                @else
                                    Not available
                                @endif
                            </address>
                        </div>
                        <div class="col-md-6">
                            <h6>Shipping Address</h6>
                            @php
                                // Check if shipping_address is already an array or needs to be decoded
                                if (is_array($order->shipping_address)) {
                                    $shippingAddress = $order->shipping_address;
                                } elseif (is_string($order->shipping_address)) {
                                    $shippingAddress = json_decode($order->shipping_address, true);
                                } else {
                                    $shippingAddress = [];
                                }
                            @endphp
                            <address>
                                @if(!empty($shippingAddress))
                                    <strong>{{ $shippingAddress['first_name'] }} {{ $shippingAddress['last_name'] }}</strong><br>
                                    {{ $shippingAddress['address_line_1'] }}<br>
                                    @if(!empty($shippingAddress['address_line_2']))
                                        {{ $shippingAddress['address_line_2'] }}<br>
                                    @endif
                                    {{ $shippingAddress['city'] }}, {{ $shippingAddress['state'] }} {{ $shippingAddress['postal_code'] }}<br>
                                    {{ $shippingAddress['country'] }}<br>
                                    @if(!empty($shippingAddress['phone']))
                                        Phone: {{ $shippingAddress['phone'] }}
                                    @endif
                                @else
                                    Not available
                                @endif
                            </address>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h6>Order Date</h6>
                            <p>{{ $order->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Payment Method</h6>
                            <p>{{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Order Items</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->total, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="row justify-content-end">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Subtotal:</strong></td>
                                    <td>${{ number_format($order->subtotal, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tax:</strong></td>
                                    <td>${{ number_format($order->tax_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Shipping:</strong></td>
                                    <td>${{ number_format($order->shipping_amount, 2) }}</td>
                                </tr>
                                @if($order->discount_amount > 0)
                                <tr>
                                    <td><strong>Discount:</strong></td>
                                    <td>-${{ number_format($order->discount_amount, 2) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td><strong>Total:</strong></td>
                                    <td><strong>${{ number_format($order->total_amount, 2) }}</strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-4 d-flex justify-content-between">
                @if($previousOrder)
                    <a href="{{ route('orders.show', $previousOrder->id) }}" class="btn btn-secondary">
                        <i class="bi bi-arrow-left"></i> Previous Order
                    </a>
                @else
                    <div></div> <!-- Empty div to maintain spacing -->
                @endif
                
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Back to Orders</a>
                
                @if($nextOrder)
                    <a href="{{ route('orders.show', $nextOrder->id) }}" class="btn btn-secondary">
                        Next Order <i class="bi bi-arrow-right"></i>
                    </a>
                @else
                    <div></div> <!-- Empty div to maintain spacing -->
                @endif
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide navigation and other non-essential elements */
    nav, footer, .btn-secondary, .btn-outline-primary {
        display: none !important;
    }
    
    /* Ensure the print button itself is hidden */
    .btn-outline-primary[onclick*="print"] {
        display: none !important;
    }
    
    /* Make sure the container takes full width */
    .container {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Improve print readability */
    body {
        font-size: 12pt;
        color: black;
        background: white;
    }
    
    .card {
        border: 1px solid #000;
        box-shadow: none;
    }
    
    .table {
        border-collapse: collapse;
    }
    
    .table th, .table td {
        border: 1px solid #000;
        padding: 8px;
    }
    
    .badge {
        border: 1px solid #000;
        color: #000 !important;
        background-color: #fff !important;
    }
}
</style>
@endsection