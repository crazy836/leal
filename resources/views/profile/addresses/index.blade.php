@extends('layouts.app')

@section('title', 'My Addresses')

@section('styles')
<style>
    .addresses-container {
        min-height: 100vh;
        padding: 20px;
        background: #f8f9fa;
    }
    
    .addresses-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .addresses-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 2rem;
    }
    
    .address-item {
        border-bottom: 1px solid #eee;
        padding: 1.5rem;
    }
    
    .address-item:last-child {
        border-bottom: none;
    }
    
    .default-badge {
        background: #667eea;
        color: white;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.8rem;
        margin-left: 0.5rem;
    }
    
    .address-actions {
        margin-top: 1rem;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        border-radius: 0.2rem;
    }
    
    .btn-outline-primary {
        color: #667eea;
        border-color: #667eea;
    }
    
    .btn-outline-primary:hover {
        background: #667eea;
        color: white;
    }
</style>
@endsection

@section('content')
<div class="addresses-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="addresses-card">
                    <div class="addresses-header">
                        <h1 class="mb-0">My Addresses</h1>
                        <p class="mb-0">Manage your shipping and billing addresses</p>
                    </div>
                    
                    <div class="p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h3>Address Book</h3>
                            <a href="{{ route('profile.addresses.create') }}" class="btn btn-primary">
                                <i class="bi bi-plus-lg"></i> Add New Address
                            </a>
                        </div>
                        
                        @if($addresses->isEmpty())
                            <div class="text-center py-5">
                                <i class="bi bi-geo-alt" style="font-size: 3rem; color: #ccc;"></i>
                                <h4 class="mt-3">No addresses yet</h4>
                                <p class="text-muted">Add your first address to get started</p>
                                <a href="{{ route('profile.addresses.create') }}" class="btn btn-primary">Add Address</a>
                            </div>
                        @else
                            @foreach($addresses as $address)
                                <div class="address-item">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5>
                                                {{ $address->first_name }} {{ $address->last_name }}
                                                @if($address->is_default)
                                                    <span class="default-badge">Default</span>
                                                @endif
                                            </h5>
                                            <p class="mb-1">{{ $address->address_line_1 }}</p>
                                            @if($address->address_line_2)
                                                <p class="mb-1">{{ $address->address_line_2 }}</p>
                                            @endif
                                            <p class="mb-1">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                            <p class="mb-1">{{ $address->country }}</p>
                                            <p class="mb-0">Phone: {{ $address->phone }}</p>
                                            <span class="badge bg-secondary">{{ ucfirst($address->type) }}</span>
                                        </div>
                                        <div class="address-actions">
                                            @if(!$address->is_default)
                                                <form action="{{ route('profile.addresses.default', $address) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">Set as Default</button>
                                                </form>
                                            @endif
                                            <a href="{{ route('profile.addresses.edit', $address) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                                            <form action="{{ route('profile.addresses.destroy', $address) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this address?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection