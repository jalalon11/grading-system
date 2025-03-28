@extends('layouts.app')

@push('styles')
<style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Override default main padding */
    main {
        padding: 0 !important;
    }
    
    #content {
        padding: 0 !important;
    }
    
    .login-card {
        max-width: 400px;
        width: 100%;
        transition: all 0.3s ease;
    }
    
    .login-card:hover {
        transform: translateY(-5px);
    }
    
    .school-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        animation: float 6s ease-in-out infinite;
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .login-title {
        font-size: 1.75rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.5rem;
    }
    
    .login-subtitle {
        color: #718096;
        font-size: 0.975rem;
    }
    
    .form-control {
        border: 2px solid #e2e8f0;
        padding: 0.75rem 1rem;
        font-size: 0.975rem;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }
    
    .input-group-text {
        border: 2px solid #e2e8f0;
        background-color: #f7fafc;
        color: #718096;
    }
    
    .input-group .form-control {
        border-left: 0;
    }
    
    .input-group .input-group-text {
        border-right: 0;
    }
    
    .btn-login {
        padding: 0.75rem 1.5rem;
        font-weight: 600;
        border-radius: 0.5rem;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
    }
    
    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    
    .forgot-password {
        color: #718096;
        text-decoration: none;
        font-size: 0.875rem;
        transition: color 0.2s ease;
    }
    
    .forgot-password:hover {
        color: #667eea;
    }
    
    .remember-me {
        user-select: none;
    }
    
    .remember-me input[type="checkbox"] {
        border-radius: 0.25rem;
        border: 2px solid #e2e8f0;
    }
    
    .card-footer {
        background: transparent;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Override any default body padding/margin */
    body {
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
    }

    /* Remove default container padding */
    .container {
        padding: 0;
    }

    .row {
        margin: 0;
    }

    .col-12 {
        padding: 0;
    }
</style>
@endpush

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="login-card card border-0 shadow-lg mx-auto">
                    <div class="card-body p-5">
                        <div class="school-logo">
                            <i class="fas fa-graduation-cap text-white" style="font-size: 2rem;"></i>
                        </div>
                        
                        <div class="login-header">
                            <h1 class="login-title">Welcome Back!</h1>
                            <p class="login-subtitle">Sign in to continue to your account</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="login-form">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label fw-medium mb-2">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input id="email" type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" 
                                           required autocomplete="email" autofocus
                                           placeholder="Enter your email">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="password" class="form-label fw-medium mb-0">Password</label>
                                    @if (Route::has('password.request'))
                                        <a class="forgot-password" href="{{ route('password.request') }}">
                                            Forgot Password?
                                        </a>
                                    @endif
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required autocomplete="current-password"
                                           placeholder="Enter your password">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block mt-1">
                                        <i class="fas fa-exclamation-circle me-1"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check remember-me">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Remember me
                                    </label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-login btn-lg text-white">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <span class="text-muted">&copy; {{ date('Y') }} Grading System</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
