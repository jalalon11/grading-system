@extends('layouts.app')

@push('styles')
<style>
    html, body {
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        width: 100%;
        height: 100%;
    }

    .register-key-container {
        min-height: 100vh;
        background: linear-gradient(135deg, #F4F6F6 0%, #D5DBDB 100%);
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
        width: 100%;
    }

    /* Override any layout padding */
    .wrapper {
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Override default main padding */
    main {
        padding: 0 !important;
        margin: 0 !important;
    }

    #content {
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    /* Force full width */
    body, html, .wrapper, #content, main, .container, .row, .col-12, .register-key-container {
        max-width: 100%;
        width: 100%;
    }

    .register-key-card {
        max-width: 420px;
        width: 100%;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
        background: rgba(244, 246, 246, 0.95);
        border: 1px solid rgba(170, 183, 184, 0.2);
    }

    .register-key-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .key-logo {
        width: 90px;
        height: 90px;
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        border-radius: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }

    .key-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }

    .key-title {
        font-size: 2rem;
        font-weight: 700;
        color: #1C2833;
        margin-bottom: 0.75rem;
        background: linear-gradient(135deg, #1C2833 0%, #2E4053 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .key-subtitle {
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

    .btn-verify {
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

    .btn-verify:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

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
</style>
@endpush

@section('content')
<div class="register-key-container">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="register-key-card card border-0 shadow-lg mx-auto">
                    <div class="card-body p-5">
                        <div class="key-logo">
                            <i class="fas fa-key text-white" style="font-size: 2.25rem;"></i>
                        </div>

                        <div class="key-header">
                            <h1 class="key-title">Access Required</h1>
                            <p class="key-subtitle">Please enter a valid registration key to continue</p>
                        </div>

                        @if(session('error'))
                            <div class="alert alert-danger mb-4">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <span>{{ session('error') }}</span>
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('register.verify-key') }}" class="key-form" id="keyForm">
                            @csrf

                            <div class="mb-4">
                                <label for="key" class="form-label fw-medium mb-2">Registration Key</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="key" type="text"
                                           class="form-control @error('key') is-invalid @enderror"
                                           name="key" value="{{ old('key') }}"
                                           required autofocus
                                           placeholder="Enter your registration key">
                                </div>
                                @error('key')
                                    <div class="invalid-feedback">
                                        <i class="fas fa-exclamation-circle"></i>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-verify btn-lg">
                                    <i class="fas fa-check-circle me-2"></i>Verify Key
                                </button>
                            </div>
                        </form>
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
    const keyForm = document.getElementById('keyForm');
    const verifyButton = keyForm.querySelector('button[type="submit"]');
    
    // Form submission animation
    keyForm.addEventListener('submit', function() {
        verifyButton.classList.add('loading');
        verifyButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Verifying...';
    });
});
</script>
@endpush
@endsection 