@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <!-- Product Images Gallery -->
        <div class="col-md-6">
            <!-- Main Image Display -->
            <div class="product-main-image mb-3">
                @if($product->images->count() > 0)
                    @php
                        $imagePath = $product->images->first()->image_path;
                        // Check if it's a local image path (not a URL)
                        if (strpos($imagePath, 'http') === false) {
                            $imageUrl = asset($imagePath);
                        } else {
                            $imageUrl = $imagePath;
                        }
                    @endphp
                    <img id="mainImage" src="{{ $imageUrl }}" class="img-fluid rounded" alt="{{ $product->name }}" style="width: 100%; height: 500px; object-fit: cover;">
                @else
                    <img id="mainImage" src="https://via.placeholder.com/600x500?text=No+Image" class="img-fluid rounded" alt="{{ $product->name }}" style="width: 100%; height: 500px; object-fit: cover;">
                @endif
            </div>
            
            <!-- Thumbnail Gallery -->
            @if($product->images->count() > 1)
                <div class="product-thumbnails d-flex overflow-auto gap-2 pb-2">
                    @foreach($product->images as $index => $image)
                        <div class="thumbnail-container" style="width: 100px; height: 100px; flex-shrink: 0;">
                            @php
                                // Check if it's a local image path (not a URL)
                                if (strpos($image->image_path, 'http') === false) {
                                    $imageUrl = asset($image->image_path);
                                } else {
                                    $imageUrl = $image->image_path;
                                }
                            @endphp
                            <img src="{{ $imageUrl }}" 
                                 class="img-thumbnail {{ $index === 0 ? 'active' : '' }}" 
                                 alt="{{ $product->name }}" 
                                 style="width: 100%; height: 100%; object-fit: cover; cursor: pointer;"
                                 onclick="changeImage(this.src)">
                        </div>
                    @endforeach
                </div>
            @endif
            
            <!-- 360° Viewer Button -->
            <div class="mt-3">
                <button class="btn btn-outline-primary w-100" data-bs-toggle="modal" data-bs-target="#viewer360Modal">
                    <i class="bi bi-arrow-repeat"></i> 360° View
                </button>
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <p class="lead">{{ $product->category->name ?? 'Uncategorized' }}</p>
            <h3 class="text-primary">${{ number_format($product->price, 2) }}</h3>
            
            @if($product->compare_price && $product->compare_price > $product->price)
                <p class="text-muted text-decoration-line-through">${{ number_format($product->compare_price, 2) }}</p>
                <p class="text-success">
                    {{ round((($product->compare_price - $product->price) / $product->compare_price) * 100) }}% OFF
                </p>
            @endif
            
            <div class="mt-4">
                <h5>Description</h5>
                <p>{{ $product->description }}</p>
            </div>
            
            <!-- Product Metadata -->
            @if($product->metadata)
                <div class="mt-4">
                    <h5>Product Details</h5>
                    <ul class="list-unstyled">
                        @foreach($product->metadata as $key => $value)
                            <li><strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <div class="mt-4">
                <form action="{{ route('cart.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity</label>
                        <div class="input-group" style="width: 150px;">
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(-1)">-</button>
                            <input type="number" id="quantity" name="quantity" class="form-control text-center" value="1" min="1" max="10">
                            <button class="btn btn-outline-secondary" type="button" onclick="changeQuantity(1)">+</button>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="bi bi-cart"></i> Add to Cart
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="addToWishlist({{ $product->id }})">
                            <i class="bi bi-heart"></i> Add to Wishlist
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Product Reviews Section -->
    <div class="row mt-5">
        <div class="col-12">
            <h3>Customer Reviews</h3>
            <div class="reviews-container">
                @if($product->reviews->count() > 0)
                    @foreach($product->reviews->take(3) as $review)
                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="card-title">{{ $review->user->name }}</h5>
                                    <div>
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                        @endfor
                                    </div>
                                </div>
                                <p class="card-text">{{ $review->comment }}</p>
                                <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p>No reviews yet. Be the first to review this product!</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- 360° Viewer Modal -->
<div class="modal fade" id="viewer360Modal" tabindex="-1" aria-labelledby="viewer360ModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewer360ModalLabel">{{ $product->name }} - 360° View</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="viewer360" style="height: 400px; background-color: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                    <p>360° viewer would be implemented here with multiple images or a specialized library</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Change main image when thumbnail is clicked
    function changeImage(src) {
        document.getElementById('mainImage').src = src;
        
        // Update active class on thumbnails
        document.querySelectorAll('.thumbnail-container img').forEach(img => {
            img.classList.remove('active');
        });
        event.target.classList.add('active');
    }
    
    // Change quantity
    function changeQuantity(amount) {
        const quantityInput = document.getElementById('quantity');
        let newValue = parseInt(quantityInput.value) + amount;
        if (newValue < 1) newValue = 1;
        if (newValue > 10) newValue = 10;
        quantityInput.value = newValue;
    }
    
    // Add to wishlist
    function addToWishlist(productId) {
        fetch('/wishlist', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: productId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update wishlist count in navbar
                const wishlistCount = document.querySelector('.wishlist-count');
                if (wishlistCount) {
                    wishlistCount.textContent = data.wishlist_count;
                } else {
                    // Create wishlist count element if it doesn't exist
                    const wishlistLink = document.querySelector('a[href="/wishlist"]');
                    if (wishlistLink) {
                        const badge = document.createElement('span');
                        badge.className = 'badge bg-primary wishlist-count';
                        badge.textContent = data.wishlist_count;
                        wishlistLink.appendChild(badge);
                    }
                }
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while adding the product to wishlist.');
        });
    }
</script>
@endsection