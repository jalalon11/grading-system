@extends('layouts.app')

@push('styles')
<style>
    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #f6f8fc 0%, #e9ecef 100%);
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }
    
    .login-container::before {
        content: '';
        position: absolute;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(102,126,234,0.1) 0%, rgba(118,75,162,0.1) 100%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Override default main padding */
    main {
        padding: 0 !important;
    }
    
    #content {
        padding: 0 !important;
    }
    
    .login-card {
        max-width: 420px;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.95);
    }
    
    .login-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }
    
    .school-logo {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        animation: float 6s ease-in-out infinite;
        position: relative;
        overflow: hidden;
    }
    
    .school-logo::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background: linear-gradient(45deg, transparent, rgba(255,255,255,0.2), transparent);
        transform: translateX(-100%);
        animation: shine 3s infinite;
    }
    
    @keyframes shine {
        100% { transform: translateX(100%); }
    }
    
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    
    .login-title {
        font-size: 2rem;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .login-subtitle {
        color: #718096;
        font-size: 1rem;
        line-height: 1.5;
    }
    
    .form-control {
        border: 2px solid #e2e8f0;
        padding: 0.875rem 1rem;
        font-size: 1rem;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        background: rgba(255, 255, 255, 0.9);
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        background: #ffffff;
    }
    
    .input-group-text {
        border: 2px solid #e2e8f0;
        background-color: rgba(247, 250, 252, 0.9);
        color: #718096;
        border-radius: 0.75rem 0 0 0.75rem;
    }
    
    .input-group .form-control {
        border-left: 0;
        border-radius: 0 0.75rem 0.75rem 0;
    }
    
    .input-group .input-group-text {
        border-right: 0;
    }
    
    .btn-login {
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        font-size: 1rem;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }
    
    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }
    
    .btn-login:hover::before {
        left: 100%;
    }
    
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }
    
    .forgot-password {
        color: #718096;
        text-decoration: none;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        position: relative;
    }
    
    .forgot-password::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 2px;
        bottom: -2px;
        left: 0;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transform-origin: right;
        transition: transform 0.3s ease;
    }
    
    .forgot-password:hover {
        color: #667eea;
    }
    
    .forgot-password:hover::after {
        transform: scaleX(1);
        transform-origin: left;
    }
    
    .remember-me {
        user-select: none;
    }
    
    .remember-me input[type="checkbox"] {
        border-radius: 0.375rem;
        border: 2px solid #e2e8f0;
        width: 1.25rem;
        height: 1.25rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .remember-me input[type="checkbox"]:checked {
        background-color: #667eea;
        border-color: #667eea;
    }
    
    .card-footer {
        background: transparent;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem;
    }

    /* Override any default body padding/margin */
    body {
        margin: 0;
        padding: 0;
        background: linear-gradient(135deg, #f6f8fc 0%, #e9ecef 100%);
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

    /* Error message styling */
    .invalid-feedback {
        font-size: 0.875rem;
        margin-top: 0.5rem;
        color: #e53e3e;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .invalid-feedback i {
        color: #e53e3e;
    }

    /* Loading animation for button */
    .btn-login.loading {
        position: relative;
        pointer-events: none;
    }

    .btn-login.loading::after {
        content: '';
        position: absolute;
        width: 1.5rem;
        height: 1.5rem;
        top: 50%;
        left: 50%;
        margin: -0.75rem 0 0 -0.75rem;
        border: 2px solid rgba(255,255,255,0.3);
        border-top-color: #ffffff;
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
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
                            <i class="fas fa-graduation-cap text-white" style="font-size: 2.25rem;"></i>
                        </div>
                        
                        <div class="login-header">
                            <h1 class="login-title">Welcome Back!</h1>
                            <p class="login-subtitle">Sign in to continue to your account</p>
                        </div>

                        <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
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
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
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
                                    <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <div class="form-check remember-me">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label ms-2" for="remember">
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
                    <div class="card-footer text-center">
                        <span class="text-muted">&copy; {{ date('Y') }} Grading System</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginButton = loginForm.querySelector('button[type="submit"]');
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');

    // Toggle password visibility
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Form submission
    loginForm.addEventListener('submit', function() {
        loginButton.classList.add('loading');
        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';
    });
});
</script>
@endpush
@endsection
