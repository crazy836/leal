@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 text-dark">Dashboard Overview</h4>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-3" data-aos="fade-up">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Revenue</p>
                            <h4 class="mb-2">${{ number_format($stats['total_revenue'], 2) }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success me-1"><i class="mdi mdi-arrow-up"></i> 12.5%</span> from last month
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                <i class="bx bx-dollar"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Products</p>
                            <h4 class="mb-2">{{ $stats['total_products'] }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success me-1"><i class="mdi mdi-arrow-up"></i> 5.3%</span> from last month
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning rounded-circle font-size-16">
                                <i class="bx bx-package"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Orders</p>
                            <h4 class="mb-2">{{ $stats['total_orders'] }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-danger me-1"><i class="mdi mdi-arrow-down"></i> 2.1%</span> from last month
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                <i class="bx bx-cart"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Users</p>
                            <h4 class="mb-2">{{ $stats['total_users'] }}</h4>
                            <p class="text-muted mb-0">
                                <span class="text-success me-1"><i class="mdi mdi-arrow-up"></i> 8.7%</span> from last month
                            </p>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info rounded-circle font-size-16">
                                <i class="bx bx-user"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0">
                <div class="card-header bg-transparent border-0 pb-0">
                    <h5 class="card-title mb-0">All Orders</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($allOrders as $order)
                                <tr>
                                    <td>#{{ $order->order_number }}</td>
                                    <td>{{ $order->user->name ?? 'N/A' }}</td>
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
                                        @endswitch
                                    </td>
                                    <td>${{ number_format($order->total_amount, 2) }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No orders found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection