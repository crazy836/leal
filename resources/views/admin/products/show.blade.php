@extends('layouts.admin')

@section('title', 'Product Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Product Details</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ $product->name }}</h5>
                            <p><strong>SKU:</strong> {{ $product->sku }}</p>
                            <p><strong>Category:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                            <p><strong>Brand:</strong> {{ $product->brand ?? 'N/A' }}</p>
                            <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                            @if($product->compare_price)
                                <p><strong>Compare Price:</strong> ${{ number_format($product->compare_price, 2) }}</p>
                            @endif
                            <p><strong>Status:</strong> 
                                @if($product->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-danger">Inactive</span>
                                @endif
                            </p>
                            <p><strong>Featured:</strong> 
                                @if($product->is_featured)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Description:</strong></p>
                            <p>{{ $product->description ?? 'No description provided.' }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-primary">Edit</a>
                        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Back to Products</a>
                    </div>
                    
                    <div class="mt-5">
                        <h5>Product Images</h5>
                        <div class="row">
                            @foreach($product->images as $image)
                                <div class="col-md-3 mb-3">
                                    <div class="card">
                                        <img src="{{ Storage::url($image->image_path) }}" class="card-img-top" alt="{{ $product->name }}">
                                        <div class="card-body">
                                            @if($image->is_primary)
                                                <span class="badge bg-primary">Primary</span>
                                            @else
                                                <form action="{{ route('admin.product-images.set-primary', $image->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Set as Primary</button>
                                                </form>
                                            @endif
                                            <form action="{{ route('admin.images.destroy', $image->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection