@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i> Create New Section
                    </h4>
                    <a href="{{ route('teacher-admin.sections.index') }}" class="btn btn-light">
                        <i class="fas fa-arrow-left me-1"></i> Back to Sections
                    </a>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('teacher-admin.sections.store') }}" method="POST" id="createSectionForm">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Basic Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Section Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="grade_level" class="form-label">Grade Level <span class="text-danger">*</span></label>
                                            <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level" required>
                                                <option value="" selected disabled>Select Grade Level</option>
                                                @foreach($gradeLevels as $grade)
                                                    <option value="Grade {{ $grade }}" {{ old('grade_level') == "Grade {$grade}" ? 'selected' : '' }}>
                                                        Grade {{ $grade }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('grade_level')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label for="school_year" class="form-label">School Year <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('school_year') is-invalid @enderror" id="school_year" name="school_year" value="{{ old('school_year', date('Y').'-'.(date('Y')+1)) }}" placeholder="e.g. 2023-2024" required>
                                            @error('school_year')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0">Adviser Assignment</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="adviser_id" class="form-label">Adviser <span class="text-danger">*</span></label>
                                            <select class="form-select @error('adviser_id') is-invalid @enderror" id="adviser_id" name="adviser_id" required>
                                                <option value="" selected disabled>Select Adviser</option>
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" {{ old('adviser_id') == $teacher->id ? 'selected' : '' }}>
                                                        {{ $teacher->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('adviser_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        
                                        <div class="alert alert-info">
                                            <i class="fas fa-info-circle me-1"></i>
                                            <strong>Note:</strong> After creating the section, you can assign subjects and teachers on the section detail page.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="{{ route('teacher-admin.sections.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Create Section
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
        $("#createSectionForm").on("submit", function(e) {
            let valid = true;
            const requiredFields = ['name', 'grade_level', 'school_year', 'adviser_id'];
            
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