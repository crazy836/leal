@extends('layouts.admin')

@section('title', 'Products')

@section('content')
<div class="container-fluid">
    <!-- Header with title and add button -->
    <div class="row">
        <div class="col-12">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="mb-0 text-dark">Product Management</h4>
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bx bx-plus"></i> Add New Product
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3" data-aos="fade-up">
            <div class="card stat-card border-0">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-truncate font-size-14 mb-2">Total Products</p>
                            <h4 class="mb-2">{{ $products->total() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-primary rounded-circle font-size-16">
                                <i class="bx bx-package"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Active Products</p>
                            <h4 class="mb-2">{{ $products->where('is_active', 1)->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-success rounded-circle font-size-16">
                                <i class="bx bx-check-circle"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Featured Products</p>
                            <h4 class="mb-2">{{ $products->where('is_featured', 1)->count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-warning rounded-circle font-size-16">
                                <i class="bx bx-star"></i>
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
                            <p class="text-truncate font-size-14 mb-2">Categories</p>
                            <h4 class="mb-2">{{ App\Models\Category::count() }}</h4>
                        </div>
                        <div class="avatar-sm">
                            <span class="avatar-title bg-info rounded-circle font-size-16">
                                <i class="bx bx-category"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="row">
        <div class="col-12">
            <div class="admin-card p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Product List</h5>
                    <div class="d-flex">
                        <form action="{{ route('admin.products.index') }}" method="GET" class="d-flex">
                            <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search products..." value="{{ request('search') }}">
                            <button class="btn btn-outline-primary btn-sm" type="submit">
                                <i class="bx bx-search"></i>
                            </button>
                        </form>
                    </div>
                </div>

                @if($products->count() > 0)
                    <div class="row g-4">
                        @foreach($products as $product)
                            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                                <div class="card h-100 border-0 shadow-sm">
                                    <div class="position-relative">
                                        @if($product->images->count() > 0)
                                            @php
                                                $imagePath = $product->images->first()->image_path;
                                                // Check if the image path is a URL or a local path
                                                if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
                                                    // External URL
                                                    $imageUrl = $imagePath;
                                                } elseif (strpos($imagePath, 'images/') === 0) {
                                                    // Image in public/images folder
                                                    $imageUrl = asset($imagePath);
                                                } else {
                                                    // Image in storage folder
                                                    $imageUrl = asset('storage/' . $imagePath);
                                                }
                                            @endphp
                                            <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                                        @endif
                                        
                                        @if($product->is_featured)
                                            <span class="position-absolute top-0 start-0 m-2 badge bg-warning">
                                                <i class="bx bx-star"></i> Featured
                                            </span>
                                        @endif
                                        
                                        @if(!$product->is_active)
                                            <span class="position-absolute top-0 end-0 m-2 badge bg-danger">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ Str::limit($product->name, 40) }}</h5>
                                        <p class="card-text flex-grow-1">{{ Str::limit($product->description, 60) }}</p>
                                        
                                        <div class="d-flex justify-content-between align-items-center mt-auto">
                                            <div>
                                                <span class="h5 mb-0 text-primary">${{ number_format($product->price, 2) }}</span>
                                                @if($product->compare_price && $product->compare_price > $product->price)
                                                    <small class="text-muted text-decoration-line-through ms-1">${{ number_format($product->compare_price, 2) }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="d-flex justify-content-between mt-3">
                                            <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-sm btn-info">
                                                <i class="bx bx-show"></i> View
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-sm btn-primary">
                                                <i class="bx bx-edit"></i> Edit
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">
                                                    <i class="bx bx-trash"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Centered pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        <nav aria-label="Products pagination">
                            <ul class="pagination">
                                {{-- Previous Page Link --}}
                                @if ($products->onFirstPage())
                                    <li class="page-item disabled">
                                        <span class="page-link">« Previous</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">« Previous</a>
                                    </li>
                                @endif

                                {{-- Pagination Elements --}}
                                @foreach ($products->links()->elements as $element)
                                    {{-- "Three Dots" Separator --}}
                                    @if (is_string($element))
                                        <li class="page-item disabled">
                                            <span class="page-link">{{ $element }}</span>
                                        </li>
                                    @endif

                                    {{-- Array Of Links --}}
                                    @if (is_array($element))
                                        @foreach ($element as $page => $url)
                                            @if ($page == $products->currentPage())
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
                                @if ($products->hasMorePages())
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">Next »</a>
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
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} results
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bx bx-package" style="font-size: 4rem; color: #ccc;"></i>
                        <h3 class="mt-3">No products found</h3>
                        <p class="mb-4">Looks like you haven't added any products yet.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="bx bx-plus"></i> Add Your First Product
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection