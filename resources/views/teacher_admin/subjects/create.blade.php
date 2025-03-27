@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i> Create New Subject
                    </h4>
                    <a href="{{ route('teacher-admin.subjects.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Back to Subjects
                    </a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form id="createSubjectForm" action="{{ route('teacher-admin.subjects.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Basic Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Subject Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="code" class="form-label">Subject Code</label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}">
                                            @error('code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">A short code or identifier for this subject (e.g., MATH7, SCIB)</div>
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="grade_level" class="form-label">Grade Level</label>
                                            <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level">
                                                <option value="">Select Grade Level</option>
                                                @foreach($gradeLevels as $grade)
                                                    <option value="Grade {{ $grade }}" {{ old('grade_level') == "Grade {$grade}" ? 'selected' : '' }}>Grade {{ $grade }}</option>
                                                @endforeach
                                            </select>
                                            @error('grade_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <div class="form-text">Specify a grade level on this subject</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Additional Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Description</label>
                                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Note:</strong> After creating the subject, you can assign it to sections and teachers.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="{{ route('teacher-admin.subjects.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Subject
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
    $(document).ready(function() {
        // Form validation
        $("#createSubjectForm").on("submit", function(e) {
            let valid = true;
            const requiredFields = ['name'];
            
            requiredFields.forEach(field => {
                const value = $(`#${field}`).val();
                if (!value || value.trim() === '') {
                    $(`#${field}`).addClass('is-invalid');
                    valid = false;
                } else {
                    $(`#${field}`).removeClass('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
                
                // Show error alert
                if (!$('.alert-danger').length) {
                    $('.card-body').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> Please fill in all required fields.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
            }
        });
    });
</script>
@endpush
@endsection 