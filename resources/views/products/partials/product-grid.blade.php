<div class="row">
    @forelse($products as $product)
        <div class="col-md-4 col-sm-6 mb-4">
            <div class="card h-100">
                @if($product->images->count() > 0)
                    @php
                        $imagePath = $product->images->first()->image_path;
                        // Check if the image path is a URL or a local path
                        $imageUrl = filter_var($imagePath, FILTER_VALIDATE_URL) ? $imagePath : asset($imagePath);
                    @endphp
                    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                @endif
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">${{ number_format($product->price, 2) }}</span>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">View</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <h4>No products found</h4>
                <p>Try adjusting your search or filter criteria</p>
            </div>
        </div>
    @endforelse
</div>

@if($products->hasPages())
    <div class="row">
        <div class="col-12">
            <nav aria-label="Product pagination">
                {{ $products->links() }}
            </nav>
        </div>
    </div>
@endif