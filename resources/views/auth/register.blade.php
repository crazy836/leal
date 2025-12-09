@extends('layouts.app')

@section('title', 'Register')

@section('styles')
<style>
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 20px;
    }
    
    .register-content {
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
    
    .register-text {
        flex: 1;
        min-width: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .register-text h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .register-text p {
        font-size: 1.1rem;
        line-height: 1.6;
        opacity: 0.9;
    }
    
    .register-form {
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
    
    .btn-register {
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
    
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-register:active {
        transform: translateY(0);
    }
    
    .signin-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #666;
        font-size: 0.95rem;
    }
    
    .signin-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .signin-link a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .register-content {
            flex-direction: column;
        }
        
        .register-text {
            padding: 2rem;
        }
        
        .register-form {
            padding: 2rem;
        }
        
        .register-text h1 {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 480px) {
        .register-text,
        .register-form {
            padding: 1.5rem;
        }
        
        .register-text h1 {
            font-size: 1.8rem;
        }
        
        .form-title h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="register-container">
    <div class="register-content">
        <div class="register-text">
            <h1>Join Our Community</h1>
            <p>Create an account to access exclusive features, track your orders, and enjoy a personalized shopping experience.</p>
            <p>Already have an account? <a href="{{ route('login') }}" style="color: white; text-decoration: underline;">Sign in here</a></p>
        </div>
        <div class="register-form">
            <div class="form-title">
                <h2>Create Account</h2>
                <p>Fill in your details to get started</p>
            </div>
            
            <form method="POST" action="{{ route('register') }}" id="registerForm">
                @csrf

                <div class="form-group">
                    <label for="name" class="form-label">Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Full Name">
                    
                    @error('name')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Email Address">
                    
                    @error('email')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <div class="password-container">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
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
                    <label for="password-confirm" class="form-label">Confirm Password</label>
                    <div class="password-container">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                        <button type="button" class="password-toggle" id="togglePasswordConfirm">
                            <i class="bi bi-eye-slash"></i>
                        </button>
                    </div>
                </div>

                <!-- reCAPTCHA with visible widget but hidden testing text -->
                @if(env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SITE_KEY') != 'your_site_key_here')
                    <div class="form-group">
                        <div id="recaptcha-container" style="position: relative; min-height: 78px;">
                            <div class="g-recaptcha" 
                                 data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}" 
                                 data-size="normal" 
                                 data-theme="light">
                            </div>
                            <!-- Loading indicator -->
                            <div id="recaptcha-loading" style="display: none; font-size: 14px; color: #666; margin-top: 5px;">
                                Loading security verification...
                            </div>
                        </div>
                        @error('g-recaptcha-response')
                            <div class="invalid-feedback d-block mt-1">
                                <strong>{{ $message }}</strong>
                            </div>
                        @enderror
                    </div>
                    
                    <!-- CSS to hide only the testing text while keeping reCAPTCHA visible -->
                    <style>
                        #recaptcha-container {
                            position: relative;
                        }
                        
                        /* Hide Google's testing text specifically */
                        #recaptcha-container div > div:last-child:not(:empty) {
                            display: none !important;
                        }
                        
                        /* Hide elements with low opacity that contain testing text */
                        #recaptcha-container div[style*="opacity"]:not([class]) {
                            display: none !important;
                        }
                        
                        /* Style adjustments for better integration */
                        #recaptcha-container .g-recaptcha {
                            display: block;
                        }
                    </style>
                @endif

                <button type="submit" class="btn-register">
                    {{ __('Register') }}
                </button>
            </form>
            
            <div class="signin-link">
                Already have an account? <a href="{{ route('login') }}">Sign in here</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@if(env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SITE_KEY') != 'your_site_key_here')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endif
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle functionality for both password fields
        function setupPasswordToggle(toggleId, passwordId) {
            const togglePassword = document.querySelector(toggleId);
            const password = document.querySelector(passwordId);
            
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
        }
        
        // Setup toggles for both password fields
        setupPasswordToggle('#togglePassword', '#password');
        setupPasswordToggle('#togglePasswordConfirm', '#password-confirm');
        
        // Form submission handler
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                // Only validate reCAPTCHA if it's enabled
                @if(env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SITE_KEY') != 'your_site_key_here')
                    const recaptchaResponse = typeof grecaptcha !== 'undefined' ? grecaptcha.getResponse() : null;
                    if (!recaptchaResponse) {
                        e.preventDefault();
                        alert('Please complete the reCAPTCHA verification.');
                    }
                @endif
            });
        }
        
        // Show loading indicator while reCAPTCHA loads
        @if(env('RECAPTCHA_SITE_KEY') && env('RECAPTCHA_SITE_KEY') != 'your_site_key_here')
        const loadingIndicator = document.getElementById('recaptcha-loading');
        if (loadingIndicator) {
            loadingIndicator.style.display = 'block';
        }
        
        // Function to hide only the testing text
        function hideRecaptchaTestingText() {
            try {
                // Hide loading indicator
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'none';
                }
                
                // Find and hide elements containing testing text
                const allDivs = document.querySelectorAll('#recaptcha-container div');
                allDivs.forEach(div => {
                    if (div.textContent && 
                        (div.textContent.includes('testing') || 
                         div.textContent.includes('TESTING') ||
                         div.textContent.includes('This reCAPTCHA is for testing purposes only') ||
                         div.textContent.includes('For testing purposes only'))) {
                        // Hide the specific text element but preserve the container
                        div.style.display = 'none';
                    }
                });
                
                // Additional targeting for Google's specific implementation
                const suspiciousElements = document.querySelectorAll('#recaptcha-container div[style*="opacity"]');
                suspiciousElements.forEach(el => {
                    if (el.style && el.style.opacity && parseFloat(el.style.opacity) < 1) {
                        // Only hide low-opacity elements that likely contain testing text
                        if (el.textContent && el.textContent.trim() !== '') {
                            el.style.display = 'none';
                        }
                    }
                });
            } catch (e) {
                // Silently fail to avoid breaking the page
                if (loadingIndicator) {
                    loadingIndicator.style.display = 'none';
                }
            }
        }
        
        // Try to hide testing text immediately
        hideRecaptchaTestingText();
        
        // Try again after reCAPTCHA loads
        if (typeof grecaptcha !== 'undefined') {
            grecaptcha.ready(function() {
                setTimeout(hideRecaptchaTestingText, 500);
            });
        } else {
            // If grecaptcha is not available, try after a delay
            setTimeout(hideRecaptchaTestingText, 2000);
        }
        @endif
    });
</script>
@endsection