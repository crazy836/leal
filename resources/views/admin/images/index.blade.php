@extends('layouts.admin')

@section('title', 'Image Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Image Management</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h4 class="card-title">Manage Product Images</h4>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadImagesModal">
                                <i class="bi bi-upload"></i> Upload Images
                            </button>
                        </div>
                    </div>

                    <!-- Filters -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <select class="form-select" id="product-filter">
                                <option value="">All Products</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="category-filter">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" class="form-control" id="search-images" placeholder="Search images...">
                                <button class="btn btn-outline-secondary" type="button" id="clear-filters">Clear</button>
                            </div>
                        </div>
                    </div>

                    <!-- Image Grid -->
                    <div class="row g-3" id="images-container">
                        @forelse($images as $image)
                            <div class="col-md-3 col-sm-4 col-6 image-item" 
                                 data-product="{{ $image->product_id }}" 
                                 data-category="{{ $image->product->category_id ?? '' }}"
                                 data-name="{{ strtolower($image->product->name ?? '') }}">
                                <div class="card h-100">
                                    <img src="{{ 
                                        filter_var($image->image_path, FILTER_VALIDATE_URL) 
                                        ? $image->image_path 
                                        : (strpos($image->image_path, 'images/') !== false 
                                            ? asset($image->image_path) 
                                            : asset('storage/' . $image->image_path)) 
                                    }}" class="card-img-top" alt="{{ $image->product->name ?? 'Product Image' }}" style="height: 200px; object-fit: cover;">
                                    <div class="card-body">
                                        <h6 class="card-title">{{ $image->product->name ?? 'Unnamed Product' }}</h6>
                                        <p class="card-text">
                                            <small class="text-muted">{{ $image->product->category->name ?? 'Uncategorized' }}</small>
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            @if($image->is_primary)
                                                <span class="badge bg-primary">Primary</span>
                                            @else
                                                <button class="btn btn-sm btn-success set-primary-btn" data-id="{{ $image->id }}">
                                                    <i class="bi bi-star"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-danger remove-image-btn" data-id="{{ $image->id }}">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="text-center py-5">
                                    <i class="bi bi-image" style="font-size: 3rem; color: #ccc;"></i>
                                    <h4 class="mt-3">No images found</h4>
                                    <p class="mb-4">Upload some product images to get started.</p>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadImagesModal">
                                        <i class="bi bi-upload"></i> Upload Images
                                    </button>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($images->hasPages())
                        <div class="row mt-4">
                            <div class="col-12 d-flex justify-content-center">
                                {{ $images->links('admin.images.partials.custom-pagination') }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Images Modal -->
<div class="modal fade" id="uploadImagesModal" tabindex="-1" aria-labelledby="uploadImagesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="uploadImagesModalLabel">Upload Product Images</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="upload-images-form" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="product_id" class="form-label">Select Product</label>
                        <select class="form-select" id="product_id" name="product_id" required>
                            <option value="">Choose a product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label for="image_files" class="form-label">Select Images</label>
                        <input class="form-control" type="file" id="image_files" name="images[]" multiple accept="image/*" required>
                        <div class="form-text">You can select multiple images. Maximum 5 images allowed per upload.</div>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="set_primary" name="set_primary" value="1">
                        <label class="form-check-label" for="set_primary">
                            Set first image as primary
                        </label>
                    </div>
                    
                    <div class="progress mb-3 d-none" id="upload-progress">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" id="upload-btn">
                            <i class="bi bi-upload"></i> Upload Images
                        </button>
                    </div>
                </form>
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
    
    // Handle image upload form submission
    document.getElementById('upload-images-form').addEventListener('submit', function(e) {
        e.preventDefault();
        uploadImages();
    });
    
    // Handle filters
    document.getElementById('product-filter').addEventListener('change', filterImages);
    document.getElementById('category-filter').addEventListener('change', filterImages);
    document.getElementById('search-images').addEventListener('input', filterImages);
    document.getElementById('clear-filters').addEventListener('click', clearFilters);
    
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
                // Show success message
                alert('Primary image updated successfully!');
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
            fetch(`/admin/images/${imageId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the image item from the grid
                    const imageItem = document.querySelector(`.image-item .remove-image-btn[data-id="${imageId}"]`).closest('.image-item');
                    if (imageItem) {
                        imageItem.remove();
                    }
                    // Show success message
                    alert('Image removed successfully!');
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
    
    // Upload images
    function uploadImages() {
        const formData = new FormData(document.getElementById('upload-images-form'));
        const uploadBtn = document.getElementById('upload-btn');
        const progressContainer = document.getElementById('upload-progress');
        const progressBar = progressContainer.querySelector('.progress-bar');
        
        // Show progress bar
        progressContainer.classList.remove('d-none');
        uploadBtn.disabled = true;
        uploadBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...';
        
        fetch('/admin/images', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Hide progress bar
                progressContainer.classList.add('d-none');
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="bi bi-upload"></i> Upload Images';
                
                // Close modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('uploadImagesModal'));
                modal.hide();
                
                // Show success message
                alert('Images uploaded successfully!');
                
                // Reload the page to show new images
                location.reload();
            } else {
                // Hide progress bar
                progressContainer.classList.add('d-none');
                uploadBtn.disabled = false;
                uploadBtn.innerHTML = '<i class="bi bi-upload"></i> Upload Images';
                
                alert('Failed to upload images: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Hide progress bar
            progressContainer.classList.add('d-none');
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="bi bi-upload"></i> Upload Images';
            
            alert('An error occurred while uploading the images.');
        });
    }
    
    // Filter images
    function filterImages() {
        const productFilter = document.getElementById('product-filter').value;
        const categoryFilter = document.getElementById('category-filter').value;
        const searchFilter = document.getElementById('search-images').value.toLowerCase();
        
        document.querySelectorAll('.image-item').forEach(item => {
            const productMatch = !productFilter || item.getAttribute('data-product') === productFilter;
            const categoryMatch = !categoryFilter || item.getAttribute('data-category') === categoryFilter;
            const nameMatch = !searchFilter || item.getAttribute('data-name').includes(searchFilter);
            
            if (productMatch && categoryMatch && nameMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }
    
    // Clear filters
    function clearFilters() {
        document.getElementById('product-filter').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('search-images').value = '';
        filterImages();
    }
});
</script>
@endsection