@extends('layouts.app')

@section('title', 'My Wishlist')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>My Wishlist</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($wishlistItems->count() > 0)
                <div class="row g-4">
                    @foreach($wishlistItems as $item)
                        <div class="col-md-4 col-sm-6 wishlist-item" data-id="{{ $item->id }}">
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
                                                <button class="btn btn-danger btn-sm remove-from-wishlist" data-id="{{ $item->id }}">
                                                    <i class="bi bi-trash"></i>
                                                </button>
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
                    <i class="bi bi-heart" style="font-size: 4rem; color: #ccc;"></i>
                    <h3 class="mt-3">Your wishlist is empty</h3>
                    <p class="mb-4">Looks like you haven't added any items to your wishlist yet.</p>
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
// Handle remove from wishlist
function initWishlistPage() {
    // Handle remove from wishlist
    document.querySelectorAll('.remove-from-wishlist').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            removeFromWishlist(id);
        });
    });
    
    // Remove from wishlist
    function removeFromWishlist(id) {
        if (confirm('Are you sure you want to remove this item from your wishlist?')) {
            fetch(`/wishlist/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the wishlist item from the grid
                    const item = document.querySelector(`.wishlist-item[data-id="${id}"]`);
                    if (item) {
                        item.remove();
                    }
                    
                    // Update wishlist count in navbar
                    const wishlistCount = document.querySelector('.wishlist-count');
                    if (wishlistCount) {
                        wishlistCount.textContent = data.wishlist_count;
                    }
                    
                    // Show success message
                    alert('Item removed from wishlist successfully!');
                    
                    // Show empty wishlist message if no items left
                    if (data.wishlist_count === 0) {
                        location.reload();
                    }
                } else {
                    alert('Failed to remove item from wishlist.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the item from wishlist.');
            });
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initWishlistPage);
} else {
    initWishlistPage();
}
</script>
@endsection