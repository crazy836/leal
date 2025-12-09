@extends('layouts.app')

@section('title', 'Edit Profile')

@section('styles')
<style>
    .profile-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 20px;
    }
    
    .profile-content {
        display: flex;
        flex-wrap: wrap;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        background: white;
        border-radius: 15px;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }
    
    .profile-header {
        flex: 1;
        min-width: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .profile-header h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .profile-header p {
        font-size: 1.1rem;
        line-height: 1.6;
        opacity: 0.9;
    }
    
    .profile-form {
        flex: 1;
        min-width: 300px;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .form-title {
        margin-bottom: 2rem;
        text-align: center;
    }
    
    .form-title h2 {
        font-size: 1.8rem;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
    }
    
    .form-title p {
        color: #666;
        font-size: 1rem;
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
    
    .btn-primary:active {
        transform: translateY(0);
    }
    
    .password-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e1e5e9;
    }
    
    .addresses-section {
        margin-top: 3rem;
        padding-top: 2rem;
        border-top: 1px solid #e1e5e9;
    }
    
    .address-item {
        border: 1px solid #e1e5e9;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
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
        margin-top: 0.5rem;
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
    
    /* Avatar styling */
    .avatar-preview-container {
        position: relative;
        display: inline-block;
    }
    
    .avatar-preview {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #e1e5e9;
        margin-bottom: 1rem;
    }
    
    .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: #e1e5e9;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        border: 3px solid #e1e5e9;
    }
    
    .avatar-placeholder i {
        font-size: 2rem;
        color: #666;
    }
    
    .avatar-upload-text {
        font-size: 0.8rem;
        color: #666;
        margin-top: -0.5rem;
        margin-bottom: 1rem;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .profile-content {
            flex-direction: column;
        }
        
        .profile-header {
            padding: 2rem;
        }
        
        .profile-form {
            padding: 2rem;
        }
        
        .profile-header h1 {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 480px) {
        .profile-header,
        .profile-form {
            padding: 1.5rem;
        }
        
        .profile-header h1 {
            font-size: 1.8rem;
        }
        
        .form-title h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-content">
        <div class="profile-header">
            <h1>Profile Settings</h1>
            <p>Manage your account information and security settings.</p>
            <p>Keep your profile up to date to ensure you receive important notifications.</p>
        </div>
        <div class="profile-form">
            <div class="form-title">
                <h2>Edit Profile</h2>
                <p>Update your personal information</p>
            </div>
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" id="profileForm">
                @csrf
                @method('PUT')

                <div class="text-center mb-4">
                    <div class="avatar-preview-container">
                        @if($user->profile_photo_path)
                            <img id="avatarPreview" src="{{ asset('storage/' . $user->profile_photo_path) }}{{ $profileUpdated ? '?v=' . time() : '' }}" alt="Profile Photo" class="avatar-preview">
                        @else
                            <div id="avatarPreviewPlaceholder" class="avatar-placeholder">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <img id="avatarPreview" src="" alt="Profile Photo" class="avatar-preview d-none">
                        @endif
                    </div>
                    <div class="form-group mb-0">
                        <label for="avatar" class="form-label">Profile Picture</label>
                        <input id="avatar" type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar" accept="image/*">
                        <div class="avatar-upload-text">Select an image to upload (JPG, PNG, max 2MB)</div>
                        @error('avatar')
                            <div class="invalid-feedback d-block mt-1">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}" required autocomplete="name" autofocus placeholder="Full Name">
                    
                    @error('name')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" placeholder="Email Address">
                    
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">Phone Number</label>
                    <input id="phone" type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="Phone Number">
                    
                    @error('phone')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <input id="birthdate" type="date" class="form-control @error('birthdate') is-invalid @enderror" name="birthdate" value="{{ old('birthdate', $user->birthdate ? $user->birthdate->format('Y-m-d') : '') }}" placeholder="Birthdate">
                    
                    @error('birthdate')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-primary">
                    Update Profile
                </button>
            </form>
            
            <div class="addresses-section">
                <div class="form-title">
                    <h2>Addresses</h2>
                    <p>Manage your shipping and billing addresses</p>
                </div>
                
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Address Book</h5>
                    <a href="{{ route('profile.addresses.create') }}" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Add Address
                    </a>
                </div>
                
                @if(isset($addresses) && $addresses->count() > 0)
                    @foreach($addresses as $address)
                        <div class="address-item">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h6>
                                        {{ $address->first_name }} {{ $address->last_name }}
                                        @if($address->is_default)
                                            <span class="default-badge">Default</span>
                                        @endif
                                    </h6>
                                    <p class="mb-1 small">{{ $address->address_line_1 }}</p>
                                    @if($address->address_line_2)
                                        <p class="mb-1 small">{{ $address->address_line_2 }}</p>
                                    @endif
                                    <p class="mb-1 small">{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</p>
                                    <p class="mb-1 small">{{ $address->country }}</p>
                                    <p class="mb-0 small">Phone: {{ $address->phone }}</p>
                                    <span class="badge bg-secondary">{{ ucfirst($address->type) }}</span>
                                </div>
                                <div class="address-actions">
                                    @if(!$address->is_default)
                                        <form action="{{ route('profile.addresses.default', $address) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="btn btn-sm btn-outline-primary">Set Default</button>
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
                @else
                    <p class="text-muted">No addresses added yet. <a href="{{ route('profile.addresses.create') }}">Add your first address</a>.</p>
                @endif
            </div>
            
            <div class="password-section">
                <div class="form-title">
                    <h2>Change Password</h2>
                    <p>Update your account password</p>
                </div>
                
                <form method="POST" action="{{ route('profile.password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input id="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" required placeholder="Current Password">
                        
                        @error('current_password')
                            <div class="invalid-feedback d-block mt-1">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">New Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="New Password">
                        
                        @error('password')
                            <div class="invalid-feedback d-block mt-1">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                        <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required placeholder="Confirm New Password">
                    </div>

                    <button type="submit" class="btn-primary">
                        Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('avatar').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Hide placeholder and show preview image
                const placeholder = document.getElementById('avatarPreviewPlaceholder');
                const preview = document.getElementById('avatarPreview');
                
                if (placeholder) {
                    placeholder.classList.add('d-none');
                }
                
                preview.src = e.target.result;
                preview.classList.remove('d-none');
            }
            reader.readAsDataURL(file);
        }
    });
</script>
@endsection