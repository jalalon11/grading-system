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
                                        
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="is_mapeh" name="is_mapeh" value="1" {{ old('is_mapeh') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_mapeh">This is a MAPEH subject</label>
                                            </div>
                                            <div class="form-text">MAPEH includes Music, Arts, Physical Education, and Health components</div>
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
                        
                        <!-- MAPEH Component Weights (initially hidden) -->
                        <div id="mapeh_components" class="row mb-4" style="display: none;">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-header bg-info text-white">
                                        <h5 class="mb-0">MAPEH Component Weights</h5>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-muted mb-3">Set the weight of each MAPEH component. The total should equal 100%.</p>
                                        
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="music_weight" class="form-label">Music Weight (%)</label>
                                                    <input type="number" class="form-control component-weight" id="music_weight" name="music_weight" value="{{ old('music_weight', 25) }}" min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="arts_weight" class="form-label">Arts Weight (%)</label>
                                                    <input type="number" class="form-control component-weight" id="arts_weight" name="arts_weight" value="{{ old('arts_weight', 25) }}" min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="pe_weight" class="form-label">Physical Education Weight (%)</label>
                                                    <input type="number" class="form-control component-weight" id="pe_weight" name="pe_weight" value="{{ old('pe_weight', 25) }}" min="0" max="100">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="mb-3">
                                                    <label for="health_weight" class="form-label">Health Weight (%)</label>
                                                    <input type="number" class="form-control component-weight" id="health_weight" name="health_weight" value="{{ old('health_weight', 25) }}" min="0" max="100">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="alert alert-warning" id="weight_warning" style="display: none;">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            The total weight must equal 100%. Current total: <span id="total_weight">100</span>%
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
        // Toggle MAPEH component weights display
        $("#is_mapeh").change(function() {
            if($(this).is(":checked")) {
                $("#mapeh_components").slideDown();
            } else {
                $("#mapeh_components").slideUp();
            }
        });
        
        // Initialize MAPEH section visibility based on initial checkbox state
        if($("#is_mapeh").is(":checked")) {
            $("#mapeh_components").show();
        }
        
        // Calculate total weight and show warning if not 100%
        $(".component-weight").on("input", function() {
            let totalWeight = 0;
            $(".component-weight").each(function() {
                const weight = parseFloat($(this).val()) || 0;
                totalWeight += weight;
            });
            
            $("#total_weight").text(totalWeight);
            
            if(Math.abs(totalWeight - 100) > 0.01) {
                $("#weight_warning").show();
                // Add a red border to indicate the error
                $(".component-weight").addClass("border-danger");
            } else {
                $("#weight_warning").hide();
                // Remove the red border
                $(".component-weight").removeClass("border-danger");
            }
        });
        
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
            
            // Check MAPEH weights if it's a MAPEH subject
            if($("#is_mapeh").is(":checked")) {
                let totalWeight = 0;
                $(".component-weight").each(function() {
                    const weight = parseFloat($(this).val()) || 0;
                    totalWeight += weight;
                });
                
                if(Math.abs(totalWeight - 100) > 0.01) {
                    valid = false;
                    $("#weight_warning").show();
                    $(".component-weight").addClass("border-danger");
                }
            }
            
            if (!valid) {
                e.preventDefault();
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
                
                // Show error alert
                if (!$('.alert-danger').length) {
                    $('.card-body:first').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> Please correct the errors in the form.
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