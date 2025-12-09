@extends('layouts.app')

@section('title', 'Order Success')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="card-title">Order Placed Successfully!</h1>
                    
                    @if(isset($order))
                        <p class="card-text">Thank you for your order. We've sent a confirmation email to your registered email address.</p>
                        <p class="card-text">Your order number is: <strong>#{{ $order->order_number }}</strong></p>
                        <p class="card-text">Order total: <strong>${{ number_format($order->total_amount, 2) }}</strong></p>
                    @else
                        <p class="card-text">Thank you for your order.</p>
                    @endif
                    
                    <div class="mt-4">
                        <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">View Orders</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection