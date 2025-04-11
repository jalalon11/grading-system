@extends('layouts.app')

@push('styles')
<style>
    /* Form styling */
    .form-section {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }

    .required:after {
        content: " *";
        color: #dc3545;
    }

    /* Dark mode support */
    .dark .form-section {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .section-title {
        color: var(--text-color);
        border-bottom-color: var(--border-color);
    }

    .dark .form-control {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .form-control:focus {
        background-color: var(--bg-card);
        border-color: #4361ee;
        color: var(--text-color);
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .dark .form-control::placeholder {
        color: var(--text-muted);
    }

    .dark .form-label {
        color: var(--text-color);
    }

    .dark .form-text {
        color: var(--text-muted);
    }

    .dark .form-select {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }

    .dark .input-group-text {
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .btn-back {
        color: var(--text-color);
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
    }

    .dark .btn-back:hover {
        background-color: var(--border-color);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .text-muted {
        color: var(--text-muted) !important;
    }

    .dark .card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .card-header {
        background-color: var(--bg-card-header) !important;
        border-bottom-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .card-header h5 {
        color: var(--text-color);
    }

    .dark .card-header .text-primary {
        color: #4361ee !important;
    }

    .dark .card-header .btn-outline-secondary {
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .card-header .btn-outline-secondary:hover {
        background-color: var(--border-color);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .card-body {
        color: var(--text-color);
    }

    .dark .breadcrumb {
        background-color: var(--bg-card-header);
    }

    .dark .breadcrumb-item {
        color: var(--text-muted);
    }

    .dark .breadcrumb-item.active {
        color: var(--text-color);
    }

    .dark .breadcrumb-item + .breadcrumb-item::before {
        color: var(--text-muted);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-plus text-primary me-2"></i> Add New Student
                        </h5>
                        <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i> Back to Students
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('teacher.students.store') }}" method="POST" id="studentForm">
                        @csrf

                        <!-- Personal Information -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Personal Information</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('first_name') is-invalid @enderror"
                                            id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                                        @error('first_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="middle_name" class="form-label">Middle Name</label>
                                        <input type="text" class="form-control @error('middle_name') is-invalid @enderror"
                                            id="middle_name" name="middle_name" value="{{ old('middle_name') }}">
                                        @error('middle_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="last_name" class="form-label">Last Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('last_name') is-invalid @enderror"
                                            id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                                        @error('last_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="student_id" class="form-label">Student ID <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('student_id') is-invalid @enderror"
                                            id="student_id" name="student_id" value="{{ old('student_id') }}" required>
                                        @error('student_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="lrn" class="form-label">Learner Reference Number (LRN) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('lrn') is-invalid @enderror"
                                            id="lrn" name="lrn" value="{{ old('lrn') }}" required
                                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        @error('lrn')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Numbers only</small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                        @error('gender')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row g-3 mt-2">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="birth_date" class="form-label">Birth Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror"
                                            id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
                                            max="{{ date('Y-m-d') }}">
                                        @error('birth_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" rows="2">{{ old('address') }}</textarea>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guardian Information -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Guardian Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guardian_name" class="form-label">Guardian Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('guardian_name') is-invalid @enderror"
                                            id="guardian_name" name="guardian_name" value="{{ old('guardian_name') }}" required>
                                        @error('guardian_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="guardian_contact" class="form-label">Guardian Contact <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('guardian_contact') is-invalid @enderror"
                                            id="guardian_contact" name="guardian_contact" value="{{ old('guardian_contact') }}" required>
                                        @error('guardian_contact')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Class Information -->
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Class Information</h6>
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                        <select class="form-select @error('section_id') is-invalid @enderror"
                                            id="section_id" name="section_id" required>
                                            <option value="">Select Section</option>
                                            @foreach($sections as $section)
                                                <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                    {{ $section->name }} (Grade {{ $section->grade_level }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('section_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i> Save Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Set max date to today
    document.getElementById('birth_date').max = new Date().toISOString().split('T')[0];

    // Prevent multiple form submissions
    document.addEventListener('DOMContentLoaded', function() {
        const studentForm = document.getElementById('studentForm');
        const submitBtn = document.querySelector('#studentForm button[type="submit"]');

        if (studentForm && submitBtn) {
            console.log('Found student form and submit button');

            studentForm.addEventListener('submit', function(e) {
                // Check if the form is already being submitted
                if (studentForm.classList.contains('submitting')) {
                    console.log('Form already being submitted');
                    e.preventDefault();
                    return false;
                }

                console.log('Form submitted, adding loading state');
                // Add submitting class to form
                studentForm.classList.add('submitting');

                // Disable button and show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';

                // Allow form submission
                return true;
            });
        } else {
            console.error('Could not find student form or submit button');
        }
    });
</script>
@endpush
@endsection