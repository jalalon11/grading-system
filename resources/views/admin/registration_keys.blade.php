@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0"><i class="fas fa-key me-2"></i>Registration Key Management</h4>
                </div>
                <div class="card-body">
                    
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    @if(session('generated_key'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <div class="d-flex flex-column">
                            <div><i class="fas fa-info-circle me-2"></i>New registration key generated:</div>
                            <div class="mt-2 p-2 bg-light rounded d-flex align-items-center">
                                <code class="me-2" id="keyDisplay">{{ session('generated_key') }}</code>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard()" title="Copy to clipboard">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <small class="mt-2 text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Save this key, it won't be displayed again!</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    
                    <div class="row">
                        <!-- Reset Master Key -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-key me-2"></i>Reset Master Key</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        The master key allows unlimited registrations. It never expires and can be used multiple times.
                                    </p>
                                    
                                    <form action="{{ route('admin.reset-master-key') }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label for="current_password" class="form-label">Your Account Password</label>
                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                            <div class="form-text text-info">This is your admin account password, not the master key.</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="new_key" class="form-label">New Master Key</label>
                                            <input type="text" class="form-control" id="new_key" name="new_key" required minlength="6">
                                            <div class="form-text">Must be at least 6 characters long.</div>
                                        </div>
                                        
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-sync-alt me-2"></i>Reset Master Key
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Generate One-Time Key -->
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0"><i class="fas fa-ticket-alt me-2"></i>Generate One-Time Key</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">
                                        One-time keys can only be used once for registration. You can set an expiration date.
                                    </p>
                                    
                                    <form action="{{ route('admin.generate-one-time-key') }}" method="POST">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label for="expires_at" class="form-label">Expiration Date (Optional)</label>
                                            <input type="date" class="form-control" id="expires_at" name="expires_at">
                                            <div class="form-text">Leave empty for a key that never expires.</div>
                                        </div>
                                        
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-plus-circle me-2"></i>Generate Key
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard() {
        const keyDisplay = document.getElementById('keyDisplay');
        const textArea = document.createElement('textarea');
        textArea.value = keyDisplay.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        // Show tooltip or some indication
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 1500);
    }
</script>
@endpush 