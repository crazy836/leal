@extends('layouts.admin')

@section('title', 'Manage Orders')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Orders</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Order Management</h4>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <form action="{{ route('admin.orders.index') }}" method="GET" class="d-inline">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Search by order number..." value="{{ request('search') }}">
                                    <select name="status" class="form-select">
                                        <option value="">All Statuses</option>
                                        @foreach(['pending', 'processing', 'shipped', 'delivered', 'cancelled'] as $status)
                                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                                {{ ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <button class="btn btn-primary" type="submit">Filter</button>
                                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap">
                            <thead class="thead-light">
                                <tr>
                                    <th>Order #</th>
                                    <th>Date</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                <tr>
                                    <td>{{ $order->order_number }}</td>
                                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                                    <td>{{ $order->user->name }}</td>
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
                                        @endswitch
                                    </td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">View</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Centered pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Orders pagination">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($orders->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">« Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev">« Previous</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($orders->links()->elements as $element)
                                    {{-- "Three Dots" Separator --}}
                                    @if (is_string($element))
                                        <li class="page-item disabled">
                                            <span class="page-link">{{ $element }}</span>
                                        </li>
                                    @endif

                                    {{-- Array Of Links --}}
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $orders->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach

                                {{-- Next Page Link --}}
                                @if ($orders->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next">Next »</a>
                                    </li>
                                @else
                                    <li class="page-item disabled">
                                        <span class="page-link">Next »</span>
                                    </li>
                                @endif
                            </ul>
                        </nav>
                    </div>
                    
                    <!-- Showing results text centered below pagination -->
                    <div class="text-center mt-2 text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} results
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection