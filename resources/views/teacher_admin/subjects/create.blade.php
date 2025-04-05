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
                    
                    <!-- Entry Type Toggle -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">Select Entry Method</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <div class="card h-100 entry-method-card" id="single_card">
                                        <div class="card-body p-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="entry_type" id="single_entry" value="single" checked>
                                                <label class="form-check-label w-100" for="single_entry">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="bg-primary text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                            <i class="fas fa-file-alt fa-lg"></i>
                                                        </div>
                                                        <h5 class="mb-0">Single Entry</h5>
                                                    </div>
                                                    <p class="text-muted mb-0">Create one subject with detailed information including MAPEH options.</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card h-100 entry-method-card" id="batch_card">
                                        <div class="card-body p-4">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="entry_type" id="batch_entry" value="batch">
                                                <label class="form-check-label w-100" for="batch_entry">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <div class="bg-success text-white rounded-circle p-3 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                                            <i class="fas fa-layer-group fa-lg"></i>
                                                        </div>
                                                        <h5 class="mb-0">Batch Entry</h5>
                                                    </div>
                                                    <p class="text-muted mb-0">Create multiple subjects at once using CSV-like format.</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Single Entry Form -->
                    <form id="createSubjectForm" action="{{ route('teacher-admin.subjects.store') }}" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        <input type="hidden" name="is_batch" value="0" id="is_batch_input">
                        
                        <div id="single_entry_form">
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
                                                        <option value="{{ $grade }}" {{ old('grade_level') == $grade ? 'selected' : '' }}>Grade {{ $grade }}</option>
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
                        </div>
                        
                        <!-- Batch Entry Form -->
                        <div id="batch_entry_form" style="display: none;">
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Batch Subject Entry</h5>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="show_batch_example">
                                        <i class="fas fa-question-circle me-1"></i> Show Example
                                    </button>
                                </div>
                                <div class="card-body">
                                    <div class="alert alert-info">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-info-circle me-2 fa-lg"></i>
                                            <strong>Instructions:</strong>
                                        </div>
                                        <ol class="mb-2">
                                            <li>Enter one subject per line using the format below</li>
                                            <li>Required fields: Subject Name, Subject Code, Grade Level</li>
                                            <li>Optional: Add Description as 4th value</li>
                                        </ol>
                                        <div class="bg-light p-2 rounded border">
                                            <code>Subject Name, Subject Code, Grade Level (number only), Description(optional)</code>
                                        </div>
                                    </div>
                                    
                                    <div id="batch_example" class="alert alert-secondary" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>Example Data:</strong>
                                            <button type="button" class="btn btn-sm btn-primary" id="use_example_data">
                                                <i class="fas fa-copy me-1"></i> Use This Example
                                            </button>
                                        </div>
                                        <pre class="mb-0 bg-light p-2 rounded code-sample">Mathematics 7, MATH-007, Grade 7, Basic mathematics for 7th grade
Science 7, SCI-007, Grade 7, General science including basic physics and chemistry
English 7, ENG-007, Grade 7, English language and literature
Filipino 7, FIL-007, Grade 7
History 7, HIST-007, Grade 7, Philippine history and culture</pre>
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label for="batch_subjects" class="form-label fw-bold">Subjects (one per line) <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('batch_subjects') is-invalid @enderror" id="batch_subjects" name="batch_subjects" rows="10" placeholder="Mathematics 7, MATH-007, 7, Basic mathematics course&#10;Science 7, SCI-007, 7, General science course&#10;English 7, ENG-007, 7, English language and literature&#10;Filipino 7, FIL-007, 7, Filipino language and literature&#10;History 7, HIST-007, 7, Philippine history and culture">{{ old('batch_subjects') }}</textarea>
                                        @error('batch_subjects')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="alert alert-success">
                                                <h6><i class="fas fa-check-circle me-1"></i> Benefits of Batch Entry:</h6>
                                                <ul class="mb-0">
                                                    <li>Create multiple subjects at once</li>
                                                    <li>Faster than creating subjects individually</li>
                                                    <li>Perfect for initial setup or new grade levels</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="card bg-light">
                                                <div class="card-body">
                                                    <h6 class="card-title"><i class="fas fa-list-ol me-1"></i> Subject Count</h6>
                                                    <p class="card-text mb-0">Lines detected: <span id="line_count" class="fw-bold">0</span></p>
                                                    <small class="text-muted">Empty lines are ignored</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="{{ route('teacher-admin.subjects.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitSubjectsBtn">
                                <i class="fas fa-save me-1"></i> Create Subject(s)
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
        // Add CSS for the entry method cards
        $("<style>")
            .prop("type", "text/css")
            .html(`
                .entry-method-card {
                    cursor: pointer;
                    transition: all 0.2s ease;
                    border: 2px solid transparent;
                }
                .entry-method-card.selected {
                    border-color: #4e73df;
                    box-shadow: 0 0 15px rgba(78, 115, 223, 0.25);
                }
                .entry-method-card:hover:not(.selected) {
                    border-color: #e9ecef;
                }
            `)
            .appendTo("head");
            
        // Make entire card clickable for entry method selection
        $('.entry-method-card').click(function() {
            const radioBtn = $(this).find('input[type="radio"]');
            radioBtn.prop('checked', true).trigger('change');
        });
        
        // Toggle entry type with visual feedback
        $('input[name="entry_type"]').change(function() {
            // Update active card styling
            $('.entry-method-card').removeClass('selected');
            if($(this).val() === 'single') {
                $('#single_card').addClass('selected');
                $('#single_entry_form').show();
                $('#batch_entry_form').hide();
                $('#is_batch_input').val('0');
            } else {
                $('#batch_card').addClass('selected');
                $('#single_entry_form').hide();
                $('#batch_entry_form').show();
                $('#is_batch_input').val('1');
            }
        });
        
        // Set initial selected state
        if($('#batch_entry').is(':checked')) {
            $('#batch_card').addClass('selected');
            $('#is_batch_input').val('1');
        } else {
            $('#single_card').addClass('selected');
            $('#is_batch_input').val('0');
        }
        
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
        
        // Toggle batch example
        $("#show_batch_example").click(function() {
            $("#batch_example").slideToggle();
        });
        
        // Use example data
        $("#use_example_data").click(function() {
            const exampleData = $(".code-sample").text();
            $("#batch_subjects").val(exampleData);
            countLines();
        });
        
        // Count lines for batch entry
        function countLines() {
            const text = $("#batch_subjects").val();
            const lines = text ? text.split("\n").filter(line => line.trim() !== "").length : 0;
            $("#line_count").text(lines);
        }
        
        // Update line count when textarea changes
        $("#batch_subjects").on("input", function() {
            countLines();
        });
        
        // Initialize line count
        countLines();
        
        // Add direct click handler for the submit button
        $("#submitSubjectsBtn").click(function(e) {
            // Prevent the default button behavior to handle it manually
            e.preventDefault();
            
            // Ensure the batch mode is correctly set
            if($('#batch_entry').is(':checked')) {
                $('#is_batch_input').val('1');
            } else {
                $('#is_batch_input').val('0');
            }
            
            // Additional check to ensure form is valid
            let isFormValid = true;
            
            // Validate the current visible form
            if($('#is_batch_input').val() === '1') {
                // Batch form validation
                if(!$('#batch_subjects').val().trim()) {
                    isFormValid = false;
                    $('#batch_subjects').addClass('is-invalid');
                }
            } else {
                // Single form validation
                if(!$('#name').val().trim()) {
                    isFormValid = false;
                    $('#name').addClass('is-invalid');
                }
            }
            
            console.log('Submit button clicked, form valid:', isFormValid);
            
            if(isFormValid) {
                // If valid, manually submit the form
                console.log('Manually submitting form');
                $('#createSubjectForm').submit();
            } else {
                console.log('Form validation failed on submit button click');
                // Show error notification
                if (!$('.alert-danger').length) {
                    $('.card-body:first').prepend(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> Please fill in all required fields.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    `);
                }
                
                // Scroll to first error
                $('html, body').animate({
                    scrollTop: $('.is-invalid:first').offset().top - 100
                }, 500);
            }
        });
    });
</script>
@endpush
@endsection 