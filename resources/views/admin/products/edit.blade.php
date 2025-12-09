@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Edit Product</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Product Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="price" class="form-label">Price ($)</label>
                                            <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ old('price', $product->price) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="compare_price" class="form-label">Compare Price ($)</label>
                                            <input type="number" class="form-control" id="compare_price" name="compare_price" step="0.01" value="{{ old('compare_price', $product->compare_price) }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Category</label>
                                            <select class="form-select" id="category_id" name="category_id" required>
                                                <option value="">Select Category</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sku" class="form-label">SKU</label>
                                            <input type="text" class="form-control" id="sku" name="sku" value="{{ old('sku', $product->sku) }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="brand" class="form-label">Brand</label>
                                    <input type="text" class="form-control" id="brand" name="brand" value="{{ old('brand', $product->brand) }}">
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        Active
                                    </label>
                                </div>
                                
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        Featured
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <!-- Product Images Section -->
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Product Images</h5>
                                    </div>
                                    <div class="card-body">
                                        <!-- Image Upload -->
                                        <div class="mb-3">
                                            <label for="images" class="form-label">Upload Images</label>
                                            <input class="form-control" type="file" id="images" name="images[]" multiple accept="image/*">
                                            <div class="form-text">You can select multiple images. Maximum 5 images allowed.</div>
                                        </div>
                                        
                                        <!-- Existing Images -->
                                        @if($product->images->count() > 0)
                                            <div class="row g-2" id="existing-images">
                                                @foreach($product->images as $index => $image)
                                                    <div class="col-6 col-md-4 mb-2" id="image-container-{{ $image->id }}">
                                                        <div class="position-relative">
                                                            @php
                                                                // Check if the image path is a URL or a local path
                                                                if (filter_var($image->image_path, FILTER_VALIDATE_URL)) {
                                                                    // External URL
                                                                    $imageUrl = $image->image_path;
                                                                } elseif (strpos($image->image_path, 'images/') === 0) {
                                                                    // Image in public/images folder
                                                                    $imageUrl = asset($image->image_path);
                                                                } else {
                                                                    // Image in storage folder
                                                                    $imageUrl = asset('storage/' . $image->image_path);
                                                                }
                                                            @endphp
                                                            <img src="{{ $imageUrl }}" class="img-fluid rounded" alt="Product Image">
                                                            <div class="position-absolute top-0 end-0 m-1">
                                                                @if($image->is_primary)
                                                                    <span class="badge bg-primary">Primary</span>
                                                                @else
                                                                    <button type="button" class="btn btn-sm btn-success set-primary-btn" data-id="{{ $image->id }}">
                                                                        <i class="bi bi-star"></i>
                                                                    </button>
                                                                @endif
                                                                <button type="button" class="btn btn-sm btn-danger remove-image-btn" data-id="{{ $image->id }}">
                                                                    <i class="bi bi-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-muted">No images uploaded yet.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle set primary image
    document.querySelectorAll('.set-primary-btn').forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.getAttribute('data-id');
            setPrimaryImage(imageId);
        });
    });
    
    // Handle remove image
    document.querySelectorAll('.remove-image-btn').forEach(button => {
        button.addEventListener('click', function() {
            const imageId = this.getAttribute('data-id');
            removeImage(imageId);
        });
    });
    
    // Set primary image
    function setPrimaryImage(imageId) {
        fetch(`/admin/product-images/${imageId}/set-primary`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reload the page to show updated primary image
                location.reload();
            } else {
                alert('Failed to set primary image.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while setting the primary image.');
        });
    }
    
    // Remove image
    function removeImage(imageId) {
        if (confirm('Are you sure you want to remove this image?')) {
            fetch(`/admin/product-images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image container
                    const container = document.getElementById(`image-container-${imageId}`);
                    if (container) {
                        container.remove();
                    }
                } else {
                    alert('Failed to remove image.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the image.');
            });
        }
    }
});
</script>
@endsection