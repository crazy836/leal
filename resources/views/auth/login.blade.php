@extends('layouts.app')

@section('title', 'Login')

@section('styles')
<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 20px;
    }
    
    .login-content {
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
    
    .login-text {
        flex: 1;
        min-width: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .login-text h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .login-text p {
        font-size: 1.1rem;
        line-height: 1.6;
        opacity: 0.9;
    }
    
    .login-form {
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
    
    .btn-login {
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
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-login:active {
        transform: translateY(0);
    }
    
    .forgot-password {
        text-align: center;
        margin: 15px 0;
    }
    
    .forgot-password a {
        color: #667eea;
        text-decoration: none;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .forgot-password a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    .signup-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #666;
        font-size: 0.95rem;
    }
    
    .signup-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .signup-link a:hover {
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
        .login-content {
            flex-direction: column;
        }
        
        .login-text {
            padding: 2rem;
        }
        
        .login-form {
            padding: 2rem;
        }
        
        .login-text h1 {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 480px) {
        .login-text,
        .login-form {
            padding: 1.5rem;
        }
        
        .login-text h1 {
            font-size: 1.8rem;
        }
        
        .form-title h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <div class="login-content">
        <div class="login-text">
            <h1>Welcome Back</h1>
            <p>Sign in to access your account and continue your shopping experience.</p>
            <p>Don't have an account? <a href="{{ route('register') }}" style="color: white; text-decoration: underline;">Sign up here</a></p>
        </div>
        <div class="login-form">
            <div class="form-title">
                <h2>Sign In</h2>
                <p>Enter your credentials to continue</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Email Address">
                    
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-container">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Password">
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

                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>

                <button type="submit" class="btn-login">
                    {{ __('Login') }}
                </button>
            </form>
            
            <div class="forgot-password">
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                @endif
            </div>
            
            <div class="signup-link">
                Don't have an account? <a href="{{ route('register') }}">Sign up here</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality
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
    });
</script>
@endsection