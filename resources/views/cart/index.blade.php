@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Shopping Cart</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            @if($cartItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items-container">
                            @foreach($cartItems as $item)
                                <tr id="cart-item-{{ $item->id }}" data-item-id="{{ $item->id }}">
                                    <td>
                                        <div class="d-flex">
                                            @if($item->product->images->count() > 0)
                                                <img src="{{ asset('storage/' . $item->product->images->first()->image_path) }}" alt="{{ $item->product->name }}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                            @else
                                                <img src="https://via.placeholder.com/80x80?text=No+Image" alt="{{ $item->product->name }}" class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                            @endif
                                            <div class="ms-3">
                                                <h6 class="mb-0">{{ $item->product->name }}</h6>
                                                <small class="text-muted">{{ $item->product->category->name ?? 'Uncategorized' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item->product->price, 2) }}</td>
                                    <td>
                                        <div class="input-group" style="width: 120px;">
                                            <button class="btn btn-outline-secondary decrement-qty" type="button" data-id="{{ $item->id }}">-</button>
                                            <input type="number" class="form-control text-center quantity-input" value="{{ $item->quantity }}" min="1" max="10" data-id="{{ $item->id }}">
                                            <button class="btn btn-outline-secondary increment-qty" type="button" data-id="{{ $item->id }}">+</button>
                                        </div>
                                    </td>
                                    <td class="item-total">${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm remove-item" data-id="{{ $item->id }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="row">
                    <div class="col-md-8">
                        <a href="{{ url('/') }}" class="btn btn-outline-primary">
                            <i class="bi bi-arrow-left"></i> Continue Shopping
                        </a>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Cart Summary</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td>Subtotal:</td>
                                        <td class="text-end" id="cart-subtotal">${{ number_format($total, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td>Tax:</td>
                                        <td class="text-end">$0.00</td>
                                    </tr>
                                    <tr>
                                        <td>Shipping:</td>
                                        <td class="text-end">Free</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Total:</strong></td>
                                        <td class="text-end"><strong id="cart-total">${{ number_format($total, 2) }}</strong></td>
                                    </tr>
                                </table>
                                <a href="{{ route('checkout.index') }}" class="btn btn-primary w-100">Proceed to Checkout</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-cart-x" style="font-size: 4rem; color: #ccc;"></i>
                    <h3 class="mt-3">Your cart is empty</h3>
                    <p class="mb-4">Looks like you haven't added any items to your cart yet.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    /* Animation for cart items */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .cart-item-animation {
        animation: fadeInUp 0.5s ease-out;
    }
    
    /* Hover effect for cart items */
    #cart-items-container tr:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
        transition: all 0.2s ease;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    /* Quantity button hover effects */
    .increment-qty:hover, .decrement-qty:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    /* Remove button hover effect */
    .remove-item:hover {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }
</style>
@endsection

@section('scripts')
<script>
function initCartPage() {
    // Handle quantity increment
    document.querySelectorAll('.increment-qty').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            let value = parseInt(input.value);
            if (value < 10) {
                input.value = value + 1;
                updateCartItem(id, input.value);
            }
        });
    });
    
    // Handle quantity decrement
    document.querySelectorAll('.decrement-qty').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            let value = parseInt(input.value);
            if (value > 1) {
                input.value = value - 1;
                updateCartItem(id, input.value);
            }
        });
    });
    
    // Handle quantity input change
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            let value = parseInt(this.value);
            if (isNaN(value) || value < 1) value = 1;
            if (value > 10) value = 10;
            this.value = value;
            updateCartItem(id, value);
        });
    });
    
    // Handle remove item
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            removeCartItem(id);
        });
    });
    
    // Update cart item
    function updateCartItem(id, quantity) {
        fetch(`/cart/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                quantity: quantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update item total
                const itemTotalElement = document.querySelector(`#cart-item-${id} .item-total`);
                itemTotalElement.textContent = `$${parseFloat(data.item_total).toFixed(2)}`;
                
                // Update cart totals
                document.getElementById('cart-subtotal').textContent = `$${parseFloat(data.cart_total).toFixed(2)}`;
                document.getElementById('cart-total').textContent = `$${parseFloat(data.cart_total).toFixed(2)}`;
                
                // Add animation class to updated item
                const row = document.getElementById(`cart-item-${id}`);
                row.classList.remove('cart-item-animation');
                void row.offsetWidth; // Trigger reflow
                row.classList.add('cart-item-animation');
            } else {
                alert('Failed to update cart item.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while updating the cart item.');
        });
    }
    
    // Remove cart item
    function removeCartItem(id) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            fetch(`/cart/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove item row with animation
                    const row = document.getElementById(`cart-item-${id}`);
                    row.style.transition = 'all 0.3s ease';
                    row.style.transform = 'translateX(100px)';
                    row.style.opacity = '0';
                    
                    setTimeout(() => {
                        row.remove();
                        
                        // Update cart totals
                        document.getElementById('cart-subtotal').textContent = `$${parseFloat(data.cart_total).toFixed(2)}`;
                        document.getElementById('cart-total').textContent = `$${parseFloat(data.cart_total).toFixed(2)}`;
                        
                        // Show empty cart message if no items left
                        if (data.cart_count === 0) {
                            location.reload();
                        }
                    }, 300);
                } else {
                    alert('Failed to remove cart item.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while removing the cart item.');
            });
        }
    }
}

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initCartPage);
} else {
    initCartPage();
}
</script>
@endsection