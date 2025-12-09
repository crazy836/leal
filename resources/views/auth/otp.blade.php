@extends('layouts.app')

@section('title', 'OTP Verification')

@section('styles')
<style>
    .otp-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        background: #f8f9fa;
        padding: 20px;
    }
    
    .otp-content {
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
    
    .otp-text {
        flex: 1;
        min-width: 300px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 3rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .otp-text h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }
    
    .otp-text p {
        font-size: 1.1rem;
        line-height: 1.6;
        opacity: 0.9;
    }
    
    .otp-form {
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
        text-align: center;
        letter-spacing: 5px;
        font-size: 1.5rem;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        outline: none;
    }
    
    .btn-verify {
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
    
    .btn-verify:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-verify:active {
        transform: translateY(0);
    }
    
    .resend-link {
        text-align: center;
        margin-top: 1.5rem;
        color: #666;
        font-size: 0.95rem;
    }
    
    .resend-link a {
        color: #667eea;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .resend-link a:hover {
        color: #764ba2;
        text-decoration: underline;
    }
    
    /* Alert styles */
    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border: 1px solid transparent;
        border-radius: 4px;
    }
    
    .alert-warning {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeaa7;
    }
    
    .alert strong {
        font-weight: bold;
    }
    
    /* Responsive design */
    @media (max-width: 768px) {
        .otp-content {
            flex-direction: column;
        }
        
        .otp-text {
            padding: 2rem;
        }
        
        .otp-form {
            padding: 2rem;
        }
        
        .otp-text h1 {
            font-size: 2rem;
        }
    }
    
    @media (max-width: 480px) {
        .otp-text,
        .otp-form {
            padding: 1.5rem;
        }
        
        .otp-text h1 {
            font-size: 1.8rem;
        }
        
        .form-title h2 {
            font-size: 1.5rem;
        }
    }
</style>
@endsection

@section('content')
<div class="otp-container">
    <div class="otp-content">
        <div class="otp-text">
            <h1>OTP Verification</h1>
            <p>We've sent a 6-digit verification code to your email address. Please enter the code below to complete your registration.</p>
            <p>Didn't receive the code? Check your spam folder or click resend.</p>
        </div>
        <div class="otp-form">
            <div class="form-title">
                <h2>Enter Verification Code</h2>
                <p>Please check your email for the OTP code</p>
            </div>
            
            @if(isset($emailSent) && !$emailSent)
                <div class="alert alert-warning">
                    <strong>Note:</strong> The code is: <strong>{{ session('otp_code') }}</strong>
                </div>
            @endif
            
            <form method="POST" action="{{ route('register.verify-otp') }}">
                @csrf

                <div class="form-group">
                    <label for="otp" class="form-label">Verification Code</label>
                    <input id="otp" type="text" class="form-control @error('otp') is-invalid @enderror" name="otp" value="{{ old('otp') }}" required autocomplete="off" placeholder="000000" maxlength="6">
                    
                    @error('otp')
                        <div class="invalid-feedback d-block mt-1">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn-verify">
                    {{ __('Verify OTP') }}
                </button>
            </form>
            
            <div class="resend-link">
                Didn't receive the code? <a href="{{ route('register.resend-otp') }}">Resend OTP</a>
            </div>
        </div>
    </div>
</div>
@endsection
</section