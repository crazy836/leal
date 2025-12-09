@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-12">
            <h1>Checkout</h1>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5>Shipping Address</h5>
                        </div>
                        <div class="card-body">
                            <form id="checkout-form">
                                @csrf
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_first_name" class="form-label">First Name</label>
                                            <input type="text" class="form-control" id="shipping_first_name" name="shipping_first_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_last_name" class="form-label">Last Name</label>
                                            <input type="text" class="form-control" id="shipping_last_name" name="shipping_last_name" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="shipping_address_line_1" class="form-label">Address Line 1</label>
                                    <input type="text" class="form-control" id="shipping_address_line_1" name="shipping_address_line_1" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="shipping_address_line_2" class="form-label">Address Line 2 (Optional)</label>
                                    <input type="text" class="form-control" id="shipping_address_line_2" name="shipping_address_line_2">
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_city" class="form-label">City</label>
                                            <input type="text" class="form-control" id="shipping_city" name="shipping_city" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_state" class="form-label">State/Province</label>
                                            <input type="text" class="form-control" id="shipping_state" name="shipping_state" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_postal_code" class="form-label">Postal Code</label>
                                            <input type="text" class="form-control" id="shipping_postal_code" name="shipping_postal_code" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="shipping_country" class="form-label">Country</label>
                                            <input type="text" class="form-control" id="shipping_country" name="shipping_country" value="United States" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="shipping_phone" class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" id="shipping_phone" name="shipping_phone" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method</label>
                                    <select class="form-control" id="payment_method" name="payment_method" required>
                                        <option value="">Select payment method</option>
                                        <option value="credit_card">Credit Card</option>
                                        <option value="paypal">PayPal</option>
                                        <option value="cash_on_delivery">Cash on Delivery</option>
                                    </select>
                                </div>
                                
                                <!-- Credit Card Fields (Hidden by default) -->
                                <div id="credit-card-fields" style="display: none;">
                                    <div class="card mb-3">
                                        <div class="card-header">
                                            <h6>Credit Card Information</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="card_number" class="form-label">Card Number</label>
                                                <input type="text" class="form-control" id="card_number" name="card_number" placeholder="1234 5678 9012 3456">
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="expiry_date" class="form-label">Expiry Date</label>
                                                        <input type="text" class="form-control" id="expiry_date" name="expiry_date" placeholder="MM/YY">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="cvv" class="form-label">CVV</label>
                                                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="cardholder_name" class="form-label">Cardholder Name</label>
                                                <input type="text" class="form-control" id="cardholder_name" name="cardholder_name" placeholder="John Doe">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">Place Order</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Order Summary</h5>
                        </div>
                        <div class="card-body">
                            <ul class="list-group list-group-flush">
                                @foreach($cartItems as $item)
                                <li class="list-group-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="mb-0">{{ $item->product->name }}</h6>
                                            <small class="text-muted">Qty: {{ $item->quantity }}</small>
                                        </div>
                                        <div>
                                            ${{ number_format($item->product->price * $item->quantity, 2) }}
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            <div class="mt-3">
                                <div class="d-flex justify-content-between">
                                    <strong>Subtotal:</strong>
                                    <strong>${{ number_format($total, 2) }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mt-2">
                                    <strong>Shipping:</strong>
                                    <strong>Free</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <h5><strong>Total:</strong></h5>
                                    <h5><strong>${{ number_format($total, 2) }}</strong></h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show/hide credit card fields based on payment method selection
    const paymentMethodSelect = document.getElementById('payment_method');
    const creditCardFields = document.getElementById('credit-card-fields');
    
    paymentMethodSelect.addEventListener('change', function() {
        if (this.value === 'credit_card') {
            creditCardFields.style.display = 'block';
        } else {
            creditCardFields.style.display = 'none';
        }
    });
    
    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // If credit card is selected, validate card fields
        if (document.getElementById('payment_method').value === 'credit_card') {
            const cardNumber = document.getElementById('card_number').value;
            const expiryDate = document.getElementById('expiry_date').value;
            const cvv = document.getElementById('cvv').value;
            const cardholderName = document.getElementById('cardholder_name').value;
            
            // Basic validation
            if (!cardNumber || !expiryDate || !cvv || !cardholderName) {
                alert('Please fill in all credit card fields.');
                return;
            }
            
            // Simple card number validation (16 digits)
            if (cardNumber.replace(/\s/g, '').length !== 16) {
                alert('Please enter a valid 16-digit card number.');
                return;
            }
        }
        
        const formData = new FormData(this);
        
        // Show loading indicator
        const submitButton = document.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...';
        submitButton.disabled = true;
        
        fetch('{{ route('checkout.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Check if we need to redirect (for PayPal)
                if (data.redirect_url) {
                    // Redirect to PayPal or show message
                    if (data.message && data.message.includes('PayPal')) {
                        // Show a message before redirecting
                        alert(data.message);
                    }
                    window.location.href = data.redirect_url;
                } else {
                    // For other payment methods, go to success page
                    window.location.href = '{{ route('checkout.success') }}';
                }
            } else {
                alert(data.message || 'There was an error processing your order. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error processing your order. Please try again.');
        })
        .finally(() => {
            // Restore submit button
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
        });
    });
});
</script>
@endsection