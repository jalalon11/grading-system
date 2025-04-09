@extends('layouts.app')

@push('styles')
<style>


    .login-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #F4F6F6 0%, #D5DBDB 100%);
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
        background: radial-gradient(circle, rgba(28,40,51,0.1) 0%, rgba(46,64,83,0.1) 100%);
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

    .auth-card {
        max-width: 450px;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
        background: rgba(244, 246, 246, 0.95);
        border: 1px solid rgba(170, 183, 184, 0.2);
    }

    .auth-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .school-logo {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
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

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1C2833;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .auth-subtitle {
        color: #2E4053;
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

    .btn-auth {
        padding: 0.875rem 1.5rem;
        font-weight: 600;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        border: none;
        font-size: 1rem;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        color: white;
    }

    .btn-auth::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: 0.5s;
    }

    .btn-auth:hover::before {
        left: 100%;
    }

    .btn-auth:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
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

    /* Tab styling */
    .nav-tabs {
        border: none;
        margin-bottom: 2rem;
        justify-content: center;
    }

    .nav-tabs .nav-item {
        margin: 0 0.5rem;
    }

    .nav-tabs .nav-link {
        border: none;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        color: #718096;
        transition: all 0.2s ease;
        background-color: rgba(255, 255, 255, 0.5);
    }

    .nav-tabs .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.8);
    }

    .nav-tabs .nav-link.active {
        color: #1C2833;
        background-color: white;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }

    /* Registration key form */
    .key-form-container {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background-color: rgba(255, 255, 255, 0.5);
        border-radius: 0.75rem;
        border: 1px dashed rgba(28, 40, 51, 0.2);
    }

    .tab-content > .tab-pane {
        display: none;
    }

    .tab-content > .active {
        display: block;
    }

    /* Clearfix for nav tabs */
    .nav-tabs::after {
        display: block;
        clear: both;
        content: "";
    }

</style>
@endpush

@section('content')
<div class="login-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="auth-card card border-0 shadow-lg mx-auto">
                    <div class="card-body p-5">
                        <div class="school-logo">
                            <i class="fas fa-graduation-cap text-white" style="font-size: 2.25rem;"></i>
                        </div>

                        <ul class="nav nav-tabs" id="authTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-tab-pane" type="button" role="tab" aria-controls="login-tab-pane" aria-selected="true">
                                    <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-tab-pane" type="button" role="tab" aria-controls="register-tab-pane" aria-selected="false">
                                    <i class="fas fa-user-plus me-2"></i>Register
                                </button>
                            </li>
                        </ul>

                        @if(session('error'))
                            <div class="alert alert-danger mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <span>{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif
                        
                        @if(session('success'))
                            <div class="alert alert-success mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle me-2"></i>
                                    <span>{{ session('success') }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="tab-content" id="authTabsContent">
                            <!-- Login Tab -->
                            <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                                <div class="auth-header">
                                    <h1 class="auth-title">Welcome Back!</h1>
                                    <p class="auth-subtitle">Sign in to continue to your account</p>
                                </div>
                            
                                <form method="POST" action="{{ route('login') }}" class="login-form" id="loginForm">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="login-email" class="form-label fw-medium mb-2">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input id="login-email" type="email"
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
                                            <label for="login-password" class="form-label fw-medium mb-0">Password</label>
                                        </div>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input id="login-password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="current-password"
                                                placeholder="Enter your password">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleLoginPassword">
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
                                        <button type="submit" class="btn btn-auth btn-lg">
                                            <i class="fas fa-sign-in-alt me-2"></i>Sign In
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Register Tab -->
                            <div class="tab-pane fade" id="register-tab-pane" role="tabpanel" aria-labelledby="register-tab" tabindex="0">
                                <div class="auth-header">
                                    <h1 class="auth-title">Create Account</h1>
                                    <p class="auth-subtitle">Join our platform today</p>
                                </div>
                                
                                <!-- Registration Key Form -->
                                <div class="key-form-container" id="keyFormContainer">
                                    <form id="registrationKeyForm" class="mb-0">
                                        <div class="mb-3">
                                            <label for="registration-key" class="form-label fw-medium mb-2">Registration Key</label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-key"></i>
                                                </span>
                                                <input id="registration-key" type="text" class="form-control" 
                                                       placeholder="Enter your registration key" required>
                                                <button class="btn btn-outline-primary" type="submit" id="verifyKeyBtn">
                                                    <i class="fas fa-check me-1"></i>Verify
                                                </button>
                                            </div>
                                            <div class="form-text">A valid registration key is required to create an account</div>
                                        </div>
                                    </form>
                                </div>
                                
                                <!-- Registration Form (initially hidden) -->
                                <form method="POST" action="{{ route('register') }}" class="register-form d-none" id="registerForm">
                                    @csrf
                                    <input type="hidden" name="registration_key" id="verified-key">

                                    <div class="mb-4">
                                        <label for="register-name" class="form-label fw-medium mb-2">Full Name</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input id="register-name" type="text" 
                                                class="form-control @error('name') is-invalid @enderror"
                                                name="name" value="{{ old('name') }}" 
                                                required autocomplete="name"
                                                placeholder="Enter your full name">
                                        </div>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="register-email" class="form-label fw-medium mb-2">Email Address</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input id="register-email" type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                name="email" value="{{ old('email') }}"
                                                required autocomplete="email" 
                                                placeholder="Enter your email address">
                                        </div>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle"></i>
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="register-password" class="form-label fw-medium mb-2">Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input id="register-password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="password" required autocomplete="new-password"
                                                placeholder="Create a strong password">
                                            <button class="btn btn-outline-secondary" type="button" id="toggleRegisterPassword">
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
                                        <label for="password-confirm" class="form-label fw-medium mb-2">Confirm Password</label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input id="password-confirm" type="password" class="form-control"
                                                name="password_confirmation" required autocomplete="new-password"
                                                placeholder="Confirm your password">
                                        </div>
                                    </div>

                                    <div class="d-grid mt-4">
                                        <button type="submit" class="btn btn-auth btn-lg">
                                            <i class="fas fa-user-plus me-2"></i>Register
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <span class="text-muted">&copy; {{ date('Y') }} Grading System by Vincent Jalalon</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Check if register tab should be active
    @if(session('register_tab'))
    document.getElementById('register-tab').click();
    @endif
    
    // Login form functionality
    const loginForm = document.getElementById('loginForm');
    const loginButton = loginForm.querySelector('button[type="submit"]');
    const toggleLoginPassword = document.getElementById('toggleLoginPassword');
    const loginPasswordInput = document.getElementById('login-password');

    // Toggle login password visibility
    toggleLoginPassword.addEventListener('click', function() {
        const type = loginPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        loginPasswordInput.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
    });

    // Login form submission
    loginForm.addEventListener('submit', function() {
        loginButton.classList.add('loading');
        loginButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Signing in...';
    });

    // Register form functionality
    const registerForm = document.getElementById('registerForm');
    const registerButton = registerForm?.querySelector('button[type="submit"]');
    const toggleRegisterPassword = document.getElementById('toggleRegisterPassword');
    const registerPasswordInput = document.getElementById('register-password');

    // Toggle register password visibility
    if (toggleRegisterPassword) {
        toggleRegisterPassword.addEventListener('click', function() {
            const type = registerPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            registerPasswordInput.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });
    }

    // Register form submission
    if (registerForm) {
        registerForm.addEventListener('submit', function() {
            registerButton.classList.add('loading');
            registerButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creating account...';
        });
    }

    // Registration key verification
    const registrationKeyForm = document.getElementById('registrationKeyForm');
    const verifyKeyBtn = document.getElementById('verifyKeyBtn');
    const keyInput = document.getElementById('registration-key');
    const verifiedKeyInput = document.getElementById('verified-key');
    const keyFormContainer = document.getElementById('keyFormContainer');

    // Check if URL has key parameter
    const urlParams = new URLSearchParams(window.location.search);
    const keyParam = urlParams.get('key');
    
    if (keyParam) {
        keyInput.value = keyParam;
        // Auto verify after short delay
        setTimeout(() => {
            verifyKey(keyParam);
        }, 500);
    }

    registrationKeyForm.addEventListener('submit', function(e) {
        e.preventDefault();
        verifyKey(keyInput.value);
    });

    function verifyKey(key) {
        verifyKeyBtn.disabled = true;
        verifyKeyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

        // Verify key using AJAX
        fetch('/register/verify-key-ajax', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ key: key })
        })
        .then(response => response.json())
        .then(data => {
            if (data.valid) {
                // Key is valid, show registration form
                keyFormContainer.innerHTML = `
                    <div class="alert alert-success mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-check-circle me-2"></i>
                            <span>Registration key verified successfully!</span>
                        </div>
                    </div>`;
                registerForm.classList.remove('d-none');
                verifiedKeyInput.value = key;
            } else {
                // Key is invalid, show error
                keyInput.classList.add('is-invalid');
                
                // Create error message if it doesn't exist
                let errorMessage = document.getElementById('key-error-message');
                if (!errorMessage) {
                    errorMessage = document.createElement('div');
                    errorMessage.id = 'key-error-message';
                    errorMessage.className = 'invalid-feedback d-block mt-2';
                    errorMessage.innerHTML = '<i class="fas fa-exclamation-circle"></i> Invalid registration key. Please try again.';
                    keyInput.parentNode.after(errorMessage);
                }
                
                verifyKeyBtn.disabled = false;
                verifyKeyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Verify';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            verifyKeyBtn.disabled = false;
            verifyKeyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Verify';
        });
    }
    
    // For testing purposes, allow immediate verification with admin123
    // Remove this in production!
    keyInput.addEventListener('input', function() {
        if (keyInput.value === 'admin123') {
            keyInput.classList.remove('is-invalid');
            
            // Remove error message if it exists
            const errorMessage = document.getElementById('key-error-message');
            if (errorMessage) {
                errorMessage.remove();
            }
        }
    });
});
</script>
@endpush
@endsection
