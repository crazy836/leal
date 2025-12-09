<!DOCTYPE html>
<html>
<head>
    <title>Order Receipt - {{ $order->order_number }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h1>E-Commerce System</h1>
                <h2>Order Receipt</h2>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h4>Order #{{ $order->order_number }}</h4>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div class="col-6 text-end">
                <p><strong>Status:</strong> 
                    @switch($order->status)
                        @case('pending')
                            <span class="badge bg-warning">Pending</span>
                            @break
                        @case('processing')
                            <span class="badge bg-info">Processing</span>
                            @break
                        @case('shipped')
                            <span class="badge bg-primary">Shipped</span>
                            @break
                        @case('delivered')
                            <span class="badge bg-success">Delivered</span>
                            @break
                        @case('cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                            @break
                    @endswitch
                </p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h5>Customer Information</h5>
                <p><strong>Name:</strong> {{ $order->user->name }}</p>
                <p><strong>Email:</strong> {{ $order->user->email }}</p>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-6">
                <h5>Billing Address</h5>
                @php
                    $billingAddress = $order->billing_address;
                @endphp
                <address>
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
                </address>
            </div>
            <div class="col-6">
                <h5>Shipping Address</h5>
                @php
                    $shippingAddress = $order->shipping_address;
                @endphp
                <address>
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
                </address>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <h5>Order Items</h5>
                <table class="table table-bordered">
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
        </div>

        <div class="row">
            <div class="col-6 offset-6">
                <table class="table table-bordered">
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

        <div class="row mt-5 no-print">
            <div class="col-12 text-center">
                <button class="btn btn-primary" onclick="window.print()">Print Receipt</button>
                <button class="btn btn-secondary" onclick="window.close()">Close</button>
            </div>
        </div>
    </div>
</body>
</html>