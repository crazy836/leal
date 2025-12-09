@extends('layouts.app')

@section('title', 'Recently Viewed Products')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Recently Viewed Products</h1>
                @if($recentlyViewedItems->count() > 0)
                    <button class="btn btn-outline-danger" id="clear-recently-viewed">
                        <i class="bi bi-trash"></i> Clear All
                    </button>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($recentlyViewedItems->count() > 0)
                <div class="row g-4">
                    @foreach($recentlyViewedItems as $item)
                        <div class="col-md-3 col-sm-6 recently-viewed-item" data-id="{{ $item->id }}">
                            <div class="card h-100">
                                @if($item->product->images->count() > 0)
                                    @php
                                        $imagePath = $item->product->images->first()->image_path;
                                        // Check if the image path is a URL or a local path
                                        $imageUrl = filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset($imagePath);
                                    @endphp
                                    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $item->product->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $item->product->name }}" style="height: 200px; object-fit: cover;">
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $item->product->name }}</h5>
                                    <p class="card-text">{{ Str::limit($item->product->description, 100) }}</p>
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="h5 mb-0">${{ number_format($item->product->price, 2) }}</span>
                                            <div>
                                                <a href="{{ route('products.show', $item->product->id) }}" class="btn btn-primary btn-sm">View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-clock-history" style="font-size: 4rem; color: #ccc;"></i>
                    <h3 class="mt-3">No recently viewed products</h3>
                    <p class="mb-4">Products you view will appear here.</p>
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
function initRecentlyViewedPage() {
    // Handle clear recently viewed
    const clearRecentlyViewedBtn = document.getElementById('clear-recently-viewed');
    if (clearRecentlyViewedBtn) {
        clearRecentlyViewedBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to clear your recently viewed products?')) {
                clearRecentlyViewed();
            }
        });
    }
    
    // Clear recently viewed
    function clearRecentlyViewed() {
        fetch('/recently-viewed/clear', {
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
                alert('Failed to clear recently viewed products.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while clearing the recently viewed products.');
        });
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initRecentlyViewedPage);
} else {
    initRecentlyViewedPage();
}
</script>
@endsection