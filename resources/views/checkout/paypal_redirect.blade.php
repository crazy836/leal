@extends('layouts.app')

@section('title', 'PayPal Payment')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5>Complete Your PayPal Payment</h5>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <i class="bi bi-paypal" style="font-size: 3rem; color: #0070ba;"></i>
                    </div>
                    
                    <h3>Order #{{ $order->order_number }}</h3>
                    <p class="lead">Amount: ${{ number_format($order->total_amount, 2) }}</p>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        <strong>PayPal Payment Instructions</strong>
                        <p>To complete your payment with PayPal, please follow these steps:</p>
                        <ol class="text-start">
                            <li>Click the "Pay with PayPal" button below</li>
                            <li>Log in to your PayPal account</li>
                            <li>Confirm your payment details</li>
                            <li>Complete the payment process</li>
                            <li>You will be redirected back to our site automatically</li>
                        </ol>
                    </div>
                    
                    <div class="mb-4">
                        <p>If you don't have a PayPal account, you can still pay using your credit or debit card.</p>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <button id="paypal-button" class="btn btn-primary btn-lg" style="background-color: #0070ba; border-color: #0070ba;">
                            <i class="bi bi-paypal me-2"></i>Pay with PayPal
                        </button>
                        
                        <a href="{{ route('checkout.index') }}" class="btn btn-secondary btn-lg">
                            <i class="bi bi-arrow-left"></i> Return to Checkout
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline-secondary">
                            <i class="bi bi-receipt"></i> View Order Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('paypal-button').addEventListener('click', function() {
        // Show loading state
        this.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Redirecting to PayPal...';
        this.disabled = true;
        
        // Redirect to PayPal after a short delay
        setTimeout(function() {
            // Create a form to submit to PayPal
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ env('PAYPAL_SANDBOX', true) ? 'https://www.sandbox.paypal.com/cgi-bin/webscr' : 'https://www.paypal.com/cgi-bin/webscr' }}';
            
            // Add required PayPal parameters
            const businessInput = document.createElement('input');
            businessInput.type = 'hidden';
            businessInput.name = 'business';
            businessInput.value = '{{ env('PAYPAL_BUSINESS_EMAIL', 'seller@example.com') }}';
            form.appendChild(businessInput);
            
            const item_nameInput = document.createElement('input');
            item_nameInput.type = 'hidden';
            item_nameInput.name = 'item_name';
            item_nameInput.value = 'Order #{{ $order->order_number }}';
            form.appendChild(item_nameInput);
            
            const amountInput = document.createElement('input');
            amountInput.type = 'hidden';
            amountInput.name = 'amount';
            amountInput.value = '{{ $order->total_amount }}';
            form.appendChild(amountInput);
            
            const currencyInput = document.createElement('input');
            currencyInput.type = 'hidden';
            currencyInput.name = 'currency_code';
            currencyInput.value = 'USD';
            form.appendChild(currencyInput);
            
            const returnUrlInput = document.createElement('input');
            returnUrlInput.type = 'hidden';
            returnUrlInput.name = 'return';
            returnUrlInput.value = '{{ route('checkout.paypal.callback') }}';
            form.appendChild(returnUrlInput);
            
            const cancelReturnInput = document.createElement('input');
            cancelReturnInput.type = 'hidden';
            cancelReturnInput.name = 'cancel_return';
            cancelReturnInput.value = '{{ route('checkout.paypal.redirect', ['order' => $order->id]) }}';
            form.appendChild(cancelReturnInput);
            
            const notifyUrlInput = document.createElement('input');
            notifyUrlInput.type = 'hidden';
            notifyUrlInput.name = 'notify_url';
            notifyUrlInput.value = '{{ url('/paypal/ipn') }}';
            form.appendChild(notifyUrlInput);
            
            const cmdInput = document.createElement('input');
            cmdInput.type = 'hidden';
            cmdInput.name = 'cmd';
            cmdInput.value = '_xclick';
            form.appendChild(cmdInput);
            
            const customInput = document.createElement('input');
            customInput.type = 'hidden';
            customInput.name = 'custom';
            customInput.value = '{{ $order->id }}';
            form.appendChild(customInput);
            
            // Submit the form
            try {
                document.body.appendChild(form);
                form.submit();
            } catch (error) {
                console.error('Error submitting PayPal form:', error);
                alert('There was an error redirecting to PayPal. Please try again.');
                // Re-enable the button
                const button = document.getElementById('paypal-button');
                button.innerHTML = '<i class="bi bi-paypal me-2"></i>Pay with PayPal';
                button.disabled = false;
            }
        }, 1500);
    });
});
</script>
@endsection