@extends('layouts.app')

@section('title', 'Add New Address')

@section('styles')
<style>
    .address-form-container {
        min-height: 100vh;
        padding: 20px;
        background: #f8f9fa;
    }
    
    .address-form-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .address-form-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: #333;
    }
    
    .form-control {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
        box-sizing: border-box;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
        color: white;
        width: 100%;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
        margin-top: 10px;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-secondary {
        background: #6c757d;
        border: none;
        border-radius: 8px;
        padding: 12px;
        font-weight: 600;
        color: white;
        width: 100%;
        font-size: 1rem;
        transition: all 0.3s ease;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    
    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(108, 117, 125, 0.4);
    }
</style>
@endsection

@section('content')
<div class="address-form-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="address-form-card">
                    <div class="address-form-header">
                        <h1 class="mb-0">Add New Address</h1>
                        <p class="mb-0">Fill in the details below to add a new address</p>
                    </div>
                    
                    <div class="p-4">
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <form method="POST" action="{{ route('profile.addresses.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type" class="form-label">Address Type</label>
                                        <select id="type" name="type" class="form-control @error('type') is-invalid @enderror" required>
                                            <option value="">Select Type</option>
                                            <option value="billing" {{ old('type') == 'billing' ? 'selected' : '' }}>Billing</option>
                                            <option value="shipping" {{ old('type') == 'shipping' ? 'selected' : '' }}>Shipping</option>
                                        </select>
                                        @error('type')
                                            <div class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name</label>
                                        <input id="first_name" type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name" value="{{ old('first_name') }}" required placeholder="First Name">
                                        @error('first_name')
                                            <div class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name</label>
                                        <input id="last_name" type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name" value="{{ old('last_name') }}" required placeholder="Last Name">
                                        @error('last_name')
                                            <div class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="company_name" class="form-label">Company Name (Optional)</label>
                                <input id="company_name" type="text" class="form-control @error('company_name') is-invalid @enderror" name="company_name" value="{{ old('company_name') }}" placeholder="Company Name">
                                @error('company_name')
                                    <div class="invalid-feedback d-block mt-1">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="address_line_1" class="form-label">Address Line 1</label>
                                <input id="address_line_1" type="text" class="form-control @error('address_line_1') is-invalid @enderror" name="address_line_1" value="{{ old('address_line_1') }}" required placeholder="Street address">
                                @error('address_line_1')
                                    <div class="invalid-feedback d-block mt-1">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="form-group">
                                <label for="address_line_2" class="form-label">Address Line 2 (Optional)</label>
                                <input id="address_line_2" type="text" class="form-control @error('address_line_2') is-invalid @enderror" name="address_line_2" value="{{ old('address_line_2') }}" placeholder="Apartment, suite, unit, etc.">
                                @error('address_line_2')
                                    <div class="invalid-feedback d-block mt-1">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city" class="form-label">City</label>
                                        <input id="city" type="text" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" required placeholder="City">
                                        @error('city')
                                            <div class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state" class="form-label">State/Province</label>
                                        <input id="state" type="text" class="form-control @error('state') is-invalid @enderror" name="state" value="{{ old('state') }}" required placeholder="State/Province">
                                        @error('state')
                                            <div class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="postal_code" class="form-label">Postal Code</label>
                                        <input id="postal_code" type="text" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code') }}" required placeholder="Postal Code">
                                        @error('postal_code')
                                            <div class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country" class="form-label">Country</label>
                                        <input id="country" type="text" class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country', 'United States') }}" required placeholder="Country">
                                        @error('country')
                                            <div class="invalid-feedback d-block mt-1">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone') }}" required placeholder="Phone Number">
                                @error('phone')
                                    <div class="invalid-feedback d-block mt-1">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="{{ route('profile.edit') }}" class="btn-secondary">Cancel</a>
                                <button type="submit" class="btn-primary">Add Address</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection