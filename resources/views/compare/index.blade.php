@extends('layouts.app')

@section('title', 'Compare Products')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Compare Products</h1>
                @if($compareItems->count() > 0)
                    <button class="btn btn-outline-danger" id="clear-compare">
                        <i class="bi bi-trash"></i> Clear All
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($compareItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Product</th>
                                @foreach($compareItems as $item)
                                    <th class="text-center compare-item" data-id="{{ $item->id }}">
                                        <button class="btn btn-danger btn-sm remove-from-compare" data-id="{{ $item->id }}">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Image</td>
                                @foreach($compareItems as $item)
                                    <td class="text-center">
                                        @if($item->product->images->count() > 0)
                                            @php
                                                $imagePath = $item->product->images->first()->image_path;
                                                // Check if the image path is a URL or a local path
                                                $imageUrl = filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset($imagePath);
                                            @endphp
                                            <img src="{{ $imageUrl }}" alt="{{ $item->product->name }}" class="img-fluid" style="height: 100px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/100x100?text=No+Image" alt="{{ $item->product->name }}" class="img-fluid" style="height: 100px; object-fit: cover;">
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Name</td>
                                @foreach($compareItems as $item)
                                    <td class="text-center">
                                        <a href="{{ route('products.show', $item->product->id) }}" class="text-decoration-none">
                                            {{ $item->product->name }}
                                        </a>
                                    </td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Category</td>
                                @foreach($compareItems as $item)
                                    <td class="text-center">{{ $item->product->category->name ?? 'N/A' }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Price</td>
                                @foreach($compareItems as $item)
                                    <td class="text-center">${{ number_format($item->product->price, 2) }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Description</td>
                                @foreach($compareItems as $item)
                                    <td>{{ Str::limit($item->product->description, 100) }}</td>
                                @endforeach
                            </tr>
                            <tr>
                                <td>Actions</td>
                                @foreach($compareItems as $item)
                                    <td class="text-center">
                                        <a href="{{ route('products.show', $item->product->id) }}" class="btn btn-primary btn-sm">View</a>
                                        <form action="{{ route('cart.store') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $item->product->id }}">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="bi bi-cart"></i> Add to Cart
                                            </button>
                                        </form>
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <div class="alert alert-info mt-3">
                    <i class="bi bi-info-circle"></i> You can compare up to 5 products. 
                    {{ 5 - $compareItems->count() }} slots remaining.
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-layout-wtf" style="font-size: 4rem; color: #ccc;"></i>
                    <h3 class="mt-3">No products to compare</h3>
                    <p class="mb-4">Add products to your compare list to see detailed comparisons.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function initComparePage() {
    // Handle remove from compare
    document.querySelectorAll('.remove-from-compare').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            removeFromCompare(id);
        });
    });
    
    // Handle clear compare
    const clearCompareBtn = document.getElementById('clear-compare');
    if (clearCompareBtn) {
        clearCompareBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your compare list?')) {
                clearCompare();
            }
        });
    }
    
    // Remove from compare
    function removeFromCompare(id) {
        fetch(`/compare/${id}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the compare item from the table
                const item = document.querySelector(`.compare-item[data-id="${id}"]`).closest('tr');
                if (item) {
                    item.remove();
                }
                
                // Update compare count in navbar
                const compareCount = document.querySelector('.compare-count');
                if (compareCount) {
                    compareCount.textContent = data.compare_count;
                }
                
                // Show success message
                alert('Item removed from compare list successfully!');
                
                // Show empty compare message if no items left
                if (data.compare_count === 0) {
                    location.reload();
                }
            } else {
                alert('Failed to remove item from compare list.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while removing the item from compare list.');
        });
    }
    
    // Clear compare
    function clearCompare() {
        fetch('/compare/clear', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page
                location.reload();
            } else {
                alert('Failed to clear compare list.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while clearing the compare list.');
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initComparePage);
} else {
    initComparePage();
}
</script>
@endsection