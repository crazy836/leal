@extends('layouts.app')

@section('title', 'Reset Password')

@section('styles')
<style>
    .reset-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 20px;
    }
    
    .reset-content {
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
    
    .reset-text {
        flex: 1;
        min-width: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .reset-text h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .reset-text p {
        font-size: 1.1rem;
        line-height: 1.6;
        opacity: 0.9;
    }
    
    .reset-form {
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
    
    .password-container {
        position: relative;
        width: 100%;
    }
    
    .password-toggle {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #6c757d;
        z-index: 2;
        padding: 0;
    }
    
    .password-toggle:hover {
        color: #667eea;
    }
    
    .btn-reset {
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
    
    .btn-reset:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-reset:active {
        transform: translateY(0);
    }
    
    .login-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #666;
        font-size: 0.95rem;
    }
    
    .login-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .login-link a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    .form-check {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
    }
    
    .form-check-input {
        margin-right: 8px;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .reset-content {
            flex-direction: column;
        }
        
        .reset-text {
            padding: 2rem;
        }
        
        .reset-form {
            padding: 2rem;
        }
        
        .reset-text h1 {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 480px) {
        .reset-text,
        .reset-form {
            padding: 1.5rem;
        }
        
        .reset-text h1 {
            font-size: 1.8rem;
        }
        
        .form-title h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="reset-container">
    <div class="reset-content">
        <div class="reset-text">
            <h1>Reset Your Password</h1>
            <p>Enter your new password below to regain access to your account.</p>
            <p>Remember to use a strong password with a mix of letters, numbers, and symbols.</p>
        </div>
        <div class="reset-form">
            <div class="form-title">
                <h2>Reset Password</h2>
                <p>Create a new secure password</p>
            </div>
            
            <form method="POST" action="{{ route('password.update') }}">
                @csrf

                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                    
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">{{ __('Password') }}</label>
                    <div class="password-container">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="New Password">
                        <button type="button" class="password-toggle" id="togglePassword">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                    
                    @error('password')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                    <div class="password-container">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm New Password">
                        <button type="button" class="password-toggle" id="toggleConfirmPassword">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-reset">
                    {{ __('Reset Password') }}
                </button>
            </form>
            
            <div class="login-link">
                Remember your password? <a href="{{ route('login') }}">Back to Login</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality for password field
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        
        if (togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                // Toggle the type attribute
                const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
                password.setAttribute('type', type);
                
                // Toggle the eye icon
                const eyeIcon = this.querySelector('i');
                if (type === 'password') {
                    eyeIcon.classList.remove('bi-eye');
                    eyeIcon.classList.add('bi-eye-slash');
                } else {
                    eyeIcon.classList.remove('bi-eye-slash');
                    eyeIcon.classList.add('bi-eye');
                }
            });
        }
        
        // Password toggle functionality for confirm password field
        const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
        const confirmPassword = document.querySelector('#password-confirm');
        
        if (toggleConfirmPassword && confirmPassword) {
            toggleConfirmPassword.addEventListener('click', function () {
                // Toggle the type attribute
                const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
                confirmPassword.setAttribute('type', type);
                
                // Toggle the eye icon
                const eyeIcon = this.querySelector('i');
                if (type === 'password') {
                    eyeIcon.classList.remove('bi-eye');
                    eyeIcon.classList.add('bi-eye-slash');
                } else {
                    eyeIcon.classList.remove('bi-eye-slash');
                    eyeIcon.classList.add('bi-eye');
                }
            });
        }
    });
</script>
@endsection