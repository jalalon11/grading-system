@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between">
                        <h5 class="mb-3 mb-sm-0">
                            <i class="fas fa-headset text-primary me-2"></i>
                            Create Support Ticket
                        </h5>
                        <a href="{{ route('teacher-admin.support.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i>
                            <span>Back to Tickets</span>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('teacher-admin.support.store') }}" method="POST">
                        @csrf
                        <div class="row justify-content-center">
                            <div class="col-lg-8 col-md-10 col-sm-12">
                                <div class="card border-0 shadow-sm rounded-3 mb-4">
                                    <div class="card-header bg-white py-3 border-bottom">
                                        <h6 class="mb-0 fw-bold">
                                            <i class="fas fa-info-circle me-2 text-primary"></i>
                                            Ticket Information
                                        </h6>
                                    </div>
                                    <div class="card-body p-3 p-md-4">
                                        <div class="mb-4">
                                            <label for="subject" class="form-label fw-medium">Subject</label>
                                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Brief description of your issue" required>
                                            <div class="form-text">Provide a short title for your support request</div>
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="priority" class="form-label fw-medium">Priority</label>
                                            <!-- Mobile-friendly priority selector that stacks on small screens -->
                                            <div class="priority-selector d-flex flex-column flex-md-row gap-2 mb-2">
                                                <div class="form-check priority-option p-2 border rounded mb-2 mb-md-0">
                                                    <input class="form-check-input" type="radio" name="priority" id="priority-low" value="low" {{ old('priority') == 'low' ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100 d-flex align-items-center" for="priority-low">
                                                        <span class="badge bg-info me-2">Low</span>
                                                        <span>General question</span>
                                                    </label>
                                                </div>
                                                <div class="form-check priority-option p-2 border rounded mb-2 mb-md-0">
                                                    <input class="form-check-input" type="radio" name="priority" id="priority-medium" value="medium" {{ old('priority') == 'medium' || old('priority') == null ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100 d-flex align-items-center" for="priority-medium">
                                                        <span class="badge bg-warning text-dark me-2">Medium</span>
                                                        <span>Issue affecting work</span>
                                                    </label>
                                                </div>
                                                <div class="form-check priority-option p-2 border rounded">
                                                    <input class="form-check-input" type="radio" name="priority" id="priority-high" value="high" {{ old('priority') == 'high' ? 'checked' : '' }}>
                                                    <label class="form-check-label w-100 d-flex align-items-center" for="priority-high">
                                                        <span class="badge bg-danger me-2">High</span>
                                                        <span>Critical issue</span>
                                                    </label>
                                                </div>
                                            </div>
                                            @error('priority')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-4">
                                            <label for="message" class="form-label fw-medium">Message</label>
                                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="6" placeholder="Please describe your issue in detail..." required>{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text mt-2">
                                                <i class="fas fa-lightbulb text-warning me-1"></i>
                                                Please provide details to help us assist you better. Include error messages and steps to reproduce.
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-grid gap-2 mb-3">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>
                                        Submit Support Ticket
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
            }, 500);
        }
    });
</script>
@endpush

@push('styles')
<style>
    /* Mobile-friendly styles for the support ticket form */
    .priority-option {
        transition: all 0.2s ease;
        cursor: pointer;
    }

    .priority-option:hover {
        background-color: #f8f9fa;
    }

    .priority-option .form-check-input:checked ~ .form-check-label {
        font-weight: 500;
    }

    /* Style for low priority */
    .priority-option:has(#priority-low:checked) {
        background-color: rgba(13, 202, 240, 0.1);
        border-color: rgba(13, 202, 240, 0.5) !important;
    }

    /* Style for medium priority */
    .priority-option:has(#priority-medium:checked) {
        background-color: rgba(255, 193, 7, 0.1);
        border-color: rgba(255, 193, 7, 0.5) !important;
    }

    /* Style for high priority */
    .priority-option:has(#priority-high:checked) {
        background-color: rgba(220, 53, 69, 0.1);
        border-color: rgba(220, 53, 69, 0.5) !important;
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

        .priority-option {
            padding: 0.5rem !important;
        }

        .priority-option .badge {
            font-size: 0.7rem;
        }

        .priority-option span:not(.badge) {
            font-size: 0.85rem;
        }
    }
</style>
@endpush
