@extends('layouts.app')

@section('content')
<div class="container-fluid py-2">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-2 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-headset text-primary me-2"></i>
                        Create Support Ticket
                    </h5>
                    <a href="{{ route('teacher-admin.support.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        <span>Back to Tickets</span>
                    </a>
                </div>

                <div class="card-body p-3">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Quick Help Section -->
                    <div class="info-banner mb-3 p-2 rounded d-flex align-items-center">
                        <i class="fas fa-info-circle text-primary me-2 fs-5"></i>
                        <div class="small">
                            Our support team typically responds within 24 hours during business days. For urgent issues, please select "High" priority.
                        </div>
                    </div>

                    <form action="{{ route('teacher-admin.support.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-12">
                                <!-- Subject Field -->
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Brief description of your issue" required>
                                    <div class="form-text small">Concise title that describes your issue</div>
                                    @error('subject')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Priority Selection -->
                                <div class="mb-3">
                                    <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                                    <div class="priority-container">
                                        <div class="priority-options-wrapper">
                                            <div class="form-check priority-option p-0 border-0">
                                                <input class="form-check-input visually-hidden" type="radio" name="priority" id="priority-low" value="low" {{ old('priority') == 'low' ? 'checked' : '' }}>
                                                <label class="form-check-label priority-card" for="priority-low">
                                                    <div class="priority-icon">
                                                        <i class="fas fa-info-circle"></i>
                                                    </div>
                                                    <div class="priority-badge bg-info">Low</div>
                                                    <div class="priority-title">General</div>
                                                    <div class="priority-desc">General inquiries, feature requests</div>
                                                </label>
                                            </div>

                                            <div class="form-check priority-option p-0 border-0">
                                                <input class="form-check-input visually-hidden" type="radio" name="priority" id="priority-medium" value="medium" {{ old('priority') == 'medium' || old('priority') == null ? 'checked' : '' }}>
                                                <label class="form-check-label priority-card" for="priority-medium">
                                                    <div class="priority-icon">
                                                        <i class="fas fa-exclamation-circle"></i>
                                                    </div>
                                                    <div class="priority-badge bg-warning text-dark">Medium</div>
                                                    <div class="priority-title">Important</div>
                                                    <div class="priority-desc">Issues affecting your work</div>
                                                </label>
                                            </div>

                                            <div class="form-check priority-option p-0 border-0">
                                                <input class="form-check-input visually-hidden" type="radio" name="priority" id="priority-high" value="high" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                                <label class="form-check-label priority-card" for="priority-high">
                                                    <div class="priority-icon">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </div>
                                                    <div class="priority-badge bg-danger">High</div>
                                                    <div class="priority-title">Critical</div>
                                                    <div class="priority-desc">Urgent issues blocking operations</div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @error('priority')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Message Field -->
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message <span class="text-danger">*</span></label>
                                    <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="4" placeholder="Please describe your issue in detail..." required>{{ old('message') }}</textarea>
                                    @error('message')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="message-tips mt-2 rounded">
                                        <div class="tips-header">
                                            <i class="fas fa-lightbulb"></i> Tips for faster resolution
                                        </div>
                                        <div class="tips-content">
                                            <div class="tip-item">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <span>Include specific error messages you encountered</span>
                                            </div>
                                            <div class="tip-item">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <span>List exact steps to reproduce the issue</span>
                                            </div>
                                            <div class="tip-item">
                                                <i class="fas fa-check-circle text-success"></i>
                                                <span>Mention which browser/device you're using</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="form-text"><span class="text-danger">*</span> Required fields</div>
                                    <button type="submit" class="btn btn-primary px-4">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Submit Ticket
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make the entire priority option clickable
        const priorityOptions = document.querySelectorAll('.priority-option');
        priorityOptions.forEach(option => {
            option.addEventListener('click', function(e) {
                // Don't trigger if clicking on the radio button itself
                if (e.target.type !== 'radio') {
                    const radio = this.querySelector('input[type="radio"]');
                    radio.checked = true;

                    // Trigger a change event to update any listeners
                    const event = new Event('change', { bubbles: true });
                    radio.dispatchEvent(event);
                }
            });
        });

        // Focus the subject field on page load
        const subjectField = document.getElementById('subject');
        if (subjectField) {
            setTimeout(() => {
                subjectField.focus();
            }, 300);
        }
    });
</script>
@endpush

@push('styles')
<style>
    /* Professional styles for the support ticket form */
    .card {
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05) !important;
    }

    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }

    .form-control, .btn {
        border-radius: 6px;
    }

    .form-control:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 0.3rem;
        color: #344767;
        font-size: 0.9rem;
    }

    /* Info banner styling */
    .info-banner {
        background-color: rgba(13, 110, 253, 0.05);
        border-left: 3px solid #0d6efd;
        color: #495057;
    }

    /* Modern Priority selector styling */
    .priority-container {
        margin-bottom: 0.5rem;
    }

    .priority-options-wrapper {
        display: flex;
        gap: 10px;
        width: 100%;
    }

    .priority-option {
        flex: 1;
    }

    .priority-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background-color: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 15px 10px;
        cursor: pointer;
        transition: all 0.25s ease;
        height: 100%;
        position: relative;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.03);
    }

    .priority-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
    }

    .priority-icon {
        font-size: 1.5rem;
        margin-bottom: 8px;
        color: #6c757d;
        transition: all 0.25s ease;
    }

    .priority-badge {
        font-size: 0.7rem;
        font-weight: 600;
        padding: 0.25em 0.75em;
        border-radius: 50px;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .priority-title {
        font-weight: 600;
        font-size: 0.95rem;
        margin-bottom: 5px;
        color: #344767;
    }

    .priority-desc {
        font-size: 0.75rem;
        color: #6c757d;
        text-align: center;
        line-height: 1.3;
    }

    /* Style for low priority */
    #priority-low:checked ~ .priority-card {
        background-color: rgba(13, 202, 240, 0.05);
        border-color: #0dcaf0;
        box-shadow: 0 0 0 1px rgba(13, 202, 240, 0.4);
    }

    #priority-low:checked ~ .priority-card .priority-icon {
        color: #0dcaf0;
    }

    /* Style for medium priority */
    #priority-medium:checked ~ .priority-card {
        background-color: rgba(255, 193, 7, 0.05);
        border-color: #ffc107;
        box-shadow: 0 0 0 1px rgba(255, 193, 7, 0.4);
    }

    #priority-medium:checked ~ .priority-card .priority-icon {
        color: #ffc107;
    }

    /* Style for high priority */
    #priority-high:checked ~ .priority-card {
        background-color: rgba(220, 53, 69, 0.05);
        border-color: #dc3545;
        box-shadow: 0 0 0 1px rgba(220, 53, 69, 0.4);
    }

    #priority-high:checked ~ .priority-card .priority-icon {
        color: #dc3545;
    }

    /* Modern Message tips styling */
    .message-tips {
        background-color: #f8f9fa;
        border-radius: 10px;
        font-size: 0.85rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        border: 1px solid #e9ecef;
    }

    .tips-header {
        background-color: #f0f2f5;
        padding: 8px 12px;
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
        border-bottom: 1px solid #e9ecef;
    }

    .tips-header i {
        color: #ffc107;
        margin-right: 6px;
    }

    .tips-content {
        padding: 10px 12px;
    }

    .tip-item {
        display: flex;
        align-items: flex-start;
        margin-bottom: 6px;
        line-height: 1.4;
    }

    .tip-item:last-child {
        margin-bottom: 0;
    }

    .tip-item i {
        margin-right: 8px;
        font-size: 0.85rem;
        margin-top: 2px;
    }

    /* Button styling */
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        box-shadow: 0 4px 6px rgba(13, 110, 253, 0.11);
        font-weight: 500;
        padding: 0.5rem 1.5rem;
        transition: all 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
        transform: translateY(-1px);
        box-shadow: 0 5px 10px rgba(13, 110, 253, 0.2);
    }

    .form-text {
        color: #6c757d;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .card-header h5 {
            font-size: 1.1rem;
        }

        .card-header .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .form-label {
            margin-bottom: 0.25rem;
        }

        .form-text {
            font-size: 0.75rem;
        }

        /* Mobile priority options */
        .priority-options-wrapper {
            flex-direction: column;
            gap: 8px;
        }

        .priority-card {
            padding: 10px;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
            text-align: left;
        }

        .priority-icon {
            margin-bottom: 0;
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .priority-badge {
            margin-bottom: 0;
            margin-right: 10px;
        }

        .priority-title {
            margin-bottom: 0;
            margin-right: 5px;
        }

        .priority-desc {
            display: inline;
            font-size: 0.7rem;
            color: #6c757d;
        }

        /* Mobile tips */
        .tips-header {
            padding: 6px 10px;
            font-size: 0.85rem;
        }

        .tips-content {
            padding: 8px 10px;
        }

        .tip-item {
            margin-bottom: 5px;
            font-size: 0.8rem;
        }

        .tip-item i {
            font-size: 0.8rem;
        }
    }
</style>
@endpush
