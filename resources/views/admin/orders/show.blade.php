@extends('layouts.admin')

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Order Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title">Order #{{ $order->order_number }}</h4>
                        <div>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Back to Orders</a>
                            <a href="{{ route('admin.orders.print', $order->id) }}" target="_blank" class="btn btn-info">Print</a>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <p><strong>Name:</strong> {{ $order->user->name }}</p>
                            <p><strong>Email:</strong> {{ $order->user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Order Status</h5>
                            <form id="statusForm" action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <div class="input-group">
                                    <select name="status" class="form-select" id="statusSelect">
                                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                            <option value="{{ $status }}" {{ $order->status == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
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
                        <div class="col-md-6">
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

                    <div class="row mt-4">
                        <div class="col-12">
                            <h5>Order Items</h5>
                            <div class="table-responsive">
                                <table class="table table-centered table-nowrap">
                                    <thead class="thead-light">
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
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6 offset-md-6">
                            <div class="table-responsive">
                                <table class="table table-nowrap">
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
            </div>
        </div>
    </div>
</div>

<!-- Script to automatically redirect after status update -->
<script>
document.getElementById('statusForm').addEventListener('submit', function(e) {
    // Add a slight delay to show the success message before redirecting
    setTimeout(function() {
        window.location.href = "{{ route('admin.orders.index') }}";
    }, 1000);
});
</script>
@endsection