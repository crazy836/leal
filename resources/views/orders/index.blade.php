@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>My Orders</h1>
            
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order Number</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $order->order_number }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td>
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
                                        @default
                                            <span class="badge bg-secondary">{{ ucfirst($order->status) }}</span>
                                    @endswitch
                                </td>
                                <td>${{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">View</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div>
                        @if($orders->previousPageUrl())
                            <a href="{{ $orders->previousPageUrl() }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Previous
                            </a>
                        @endif
                    </div>
                    
                    <div>
                        <span class="text-muted">
                            Page {{ $orders->currentPage() }} of {{ $orders->lastPage() }}
                        </span>
                    </div>
                    
                    <div>
                        @if($orders->nextPageUrl())
                            <a href="{{ $orders->nextPageUrl() }}" class="btn btn-secondary">
                                Next <i class="bi bi-arrow-right"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center">
                    <h3>You haven't placed any orders yet</h3>
                    <p>Looks like you haven't ordered anything yet.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Start Shopping</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection