@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Products</h1>
        </div>
    </div>

    <div class="row">
        <!-- Filters Sidebar -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header">
                    <h5>Filters</h5>
                </div>
                <div class="card-body">
                    <form id="filter-form">
                        <!-- Search -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Search</label>
                            <input type="text" class="form-control" id="search" name="search" placeholder="Search products...">
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div class="mb-3">
                            <label class="form-label">Price Range</label>
                            <div class="row">
                                <div class="col">
                                    <input type="number" class="form-control" id="min_price" name="min_price" placeholder="Min">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" id="max_price" name="max_price" placeholder="Max">
                                </div>
                            </div>
                        </div>

                        <!-- Sort By -->
                        <div class="mb-3">
                            <label for="sort" class="form-label">Sort By</label>
                            <select class="form-select" id="sort" name="sort">
                                <option value="">Default</option>
                                <option value="price_low">Price: Low to High</option>
                                <option value="price_high">Price: High to Low</option>
                                <option value="newest">Newest</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="col-lg-9">
            <div id="products-container">
                @include('products.partials.product-grid')
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterForm = document.getElementById('filter-form');
    const productsContainer = document.getElementById('products-container');
    
    // Handle form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        applyFilters();
    });
    
    // Handle filter changes
    const inputs = filterForm.querySelectorAll('input, select');
    inputs.forEach(input => {
        input.addEventListener('change', function() {
            applyFilters();
        });
    });
    
    function applyFilters() {
        const formData = new FormData(filterForm);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value !== '') {
                params.append(key, value);
            }
        }
        
        // Add a timestamp to prevent caching
        params.append('_', Date.now());
        
        fetch(`{{ route('products.index') }}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            productsContainer.innerHTML = html;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
</script>
@endsection