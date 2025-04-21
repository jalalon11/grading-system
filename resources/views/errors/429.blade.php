@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8 mt-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center p-5">
                    <div class="mb-4">
                        <i class="fas fa-clock text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Rate Limit Exceeded</h2>
                    <p class="text-muted mb-4">
                        You've made too many payment submission attempts in a short period of time. 
                        This limit helps prevent duplicate payments and ensures system security.
                    </p>
                    
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle me-3 fs-4"></i>
                            <div class="text-start">
                                <p class="mb-1"><strong>Why am I seeing this?</strong></p>
                                <p class="mb-0">For security reasons, we limit payment submissions to 3 per hour. Please wait before trying again.</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('teacher-admin.payments.index') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i> Return to Payments
                        </a>
                        <a href="{{ route('teacher-admin.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i> Go to Dashboard
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted">
                    If you need immediate assistance, please contact the administrator.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
