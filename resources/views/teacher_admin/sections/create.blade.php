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

                    @if(session('batch_errors'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h5><i class="fas fa-exclamation-triangle me-1"></i> Batch Entry Errors:</h5>
                            <ul class="mb-0">
                                @foreach(session('batch_errors') as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
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
                                                    <p class="text-muted mb-0">Create one section with detailed information including adviser assignment.</p>
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
                                                    <p class="text-muted mb-0">Create multiple sections at once using CSV-like format.</p>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('teacher-admin.sections.store') }}" method="POST" id="createSectionForm">
                        @csrf
                        <input type="hidden" name="is_batch" value="0" id="is_batch_input">
                        
                        <!-- Single Entry Form -->
                        <div id="single_entry_form">
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
                        </div>
                        
                        <!-- Batch Entry Form -->
                        <div id="batch_entry_form" style="display: none;">
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Batch Section Entry</h5>
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
                                            <li>Enter one section per line using the <strong>exact</strong> format below</li>
                                            <li>Required fields: Section Name, Grade Level, Adviser ID, School Year</li>
                                            <li>The Adviser ID must be a number that matches a teacher ID in your system</li>
                                            <li>Make sure each line has <strong>exactly 4 parts</strong> separated by commas</li>
                                            <li>There should be no extra commas in any field</li>
                                        </ol>
                                        <div class="bg-light p-2 rounded border">
                                            <code>Section Name, Grade Level, Adviser ID, School Year</code>
                                        </div>
                                        <div class="mt-2">
                                            <strong>Troubleshooting:</strong>
                                            <ul class="small mb-0">
                                                <li>Use the example data provided for correct formatting</li>
                                                <li>Check for extra commas or missing commas</li>
                                                <li>Ensure Adviser IDs match the available teacher IDs below</li>
                                            </ul>
                                        </div>
                                    </div>
                                    
                                    <div id="batch_example" class="alert alert-secondary" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <strong>Example Data:</strong>
                                            <button type="button" class="btn btn-sm btn-primary" id="use_example_data">
                                                <i class="fas fa-copy me-1"></i> Use This Example
                                            </button>
                                        </div>
                                        <pre class="mb-0 bg-light p-2 rounded code-sample">St. Matthew, Grade 7, 4, 2025-2026
St. Mark, Grade 7, 2, 2025-2026
St. Luke, Grade 8, 4, 2025-2026
St. John, Grade 8, 2, 2025-2026</pre>
                                        <div class="mt-2 small">
                                            <strong>Note:</strong> Make sure to use the exact format above with commas separating each field.
                                            <span class="text-danger">Do not use special characters in section names.</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="batch_sections" class="form-label fw-bold">Sections (one per line) <span class="text-danger">*</span></label>
                                        <textarea class="form-control @error('batch_sections') is-invalid @enderror" id="batch_sections" name="batch_sections" rows="10" placeholder="St. Matthew, Grade 7, 4, 2025-2026&#10;St. Mark, Grade 7, 2, 2025-2026&#10;St. Luke, Grade 8, 4, 2025-2026">{{ old('batch_sections') }}</textarea>
                                        @error('batch_sections')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="alert alert-success">
                                                <h6><i class="fas fa-check-circle me-1"></i> Benefits of Batch Entry:</h6>
                                                <ul class="mb-0">
                                                    <li>Create multiple sections at once</li>
                                                    <li>Faster than creating sections individually</li>
                                                    <li>Perfect for initial setup or new grade levels</li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-secondary mb-3">
                                                <h6 class="mb-3 fw-bold text-primary"><i class="fas fa-users me-2"></i>Available Teachers (Use these IDs):</h6>
                                                <div class="row">
                                                    @foreach($teachers->chunk(ceil($teachers->count() / 3)) as $chunk)
                                                        <div class="col-md-4">
                                                            <ul class="list-unstyled small">
                                                                @foreach($chunk as $teacher)
                                                                    <li class="mb-1"><span class="badge bg-info">ID {{ $teacher->id }}</span> {{ $teacher->name }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4 text-end">
                            <a href="{{ route('teacher-admin.sections.index') }}" class="btn btn-secondary me-2">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitSectionsBtn">
                                <i class="fas fa-save me-1"></i> Create Section(s)
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
        // Style the entry method cards
        $('.entry-method-card').hover(function() {
            $(this).addClass('shadow-sm border-primary');
        }, function() {
            $(this).removeClass('shadow-sm border-primary');
        });
        
        // Handle card clicks to check the associated radio button
        $('.entry-method-card').click(function() {
            const radioId = $(this).find('input[type="radio"]').attr('id');
            $('#' + radioId).prop('checked', true).trigger('change');
        });
        
        // Toggle entry type
        $('input[name="entry_type"]').change(function() {
            if($(this).val() === 'single') {
                $('#single_entry_form').show();
                $('#batch_entry_form').hide();
                $('#is_batch_input').val('0');
                $('#single_card').addClass('border-primary');
                $('#batch_card').removeClass('border-primary');
            } else {
                $('#single_entry_form').hide();
                $('#batch_entry_form').show();
                $('#is_batch_input').val('1');
                $('#batch_card').addClass('border-primary');
                $('#single_card').removeClass('border-primary');
            }
            console.log('Entry type changed:', $(this).val(), 'is_batch value:', $('#is_batch_input').val());
        });
        
        // Initialize the card highlighting based on selected option
        if($('#batch_entry').is(':checked')) {
            $('#batch_card').addClass('border-primary');
        } else {
            $('#single_card').addClass('border-primary');
        }
        
        // Show/hide example
        $('#show_batch_example').click(function() {
            $('#batch_example').toggle();
        });
        
        // Use example data
        $('#use_example_data').click(function() {
            const exampleData = $('.code-sample').text();
            $('#batch_sections').val(exampleData);
            console.log('Example data inserted:', exampleData);
            validateBatchFormat();
        });
        
        // Batch format validation function
        function validateBatchFormat() {
            if ($('#batch_entry').is(':checked')) {
                const batchSections = $('#batch_sections').val().trim();
                if (batchSections) {
                    let isValid = true;
                    let errorMessages = [];
                    
                    const lines = batchSections.split('\n');
                    lines.forEach((line, index) => {
                        line = line.trim();
                        if (!line) return;
                        
                        // More flexible splitting - handle excessive spaces around commas
                        const parts = line.split(',').map(part => part.trim()).filter(part => part.length > 0);
                        
                        if (parts.length < 4) {
                            errorMessages.push(`Line ${index + 1}: Missing required fields. Found ${parts.length} of 4 required fields.`);
                            isValid = false;
                        } else {
                            // Check adviser ID is numeric
                            const adviserId = parts[2];
                            if (!$.isNumeric(adviserId)) {
                                errorMessages.push(`Line ${index + 1}: Adviser ID must be a number, got '${adviserId}'`);
                                isValid = false;
                            }
                        }
                    });
                    
                    // Show or clear validation message
                    if (!isValid) {
                        if (!$('#batch_format_error').length) {
                            $('#batch_sections').after(`
                                <div id="batch_format_error" class="alert alert-danger mt-2">
                                    <h6><i class="fas fa-exclamation-triangle me-1"></i> Format Errors:</h6>
                                    <ul class="mb-0 ps-3">
                                        ${errorMessages.map(msg => `<li>${msg}</li>`).join('')}
                                    </ul>
                                    <div class="mt-2">
                                        <small class="text-muted">Make sure each line follows the exact format: <code>Section Name, Grade Level, Adviser ID, School Year</code></small>
                                    </div>
                                </div>
                            `);
                        } else {
                            $('#batch_format_error ul').html(errorMessages.map(msg => `<li>${msg}</li>`).join(''));
                        }
                        $('#batch_sections').addClass('is-invalid');
                    } else {
                        $('#batch_format_error').remove();
                        $('#batch_sections').removeClass('is-invalid');
                    }
                    
                    return isValid;
                }
            }
            return true;
        }
        
        // Add live validation to batch sections textarea
        $('#batch_sections').on('input', validateBatchFormat);
        
        // Explicitly set the is_batch value on page load based on which radio is checked
        if($('#batch_entry').is(':checked')) {
            $('#is_batch_input').val('1');
        } else {
            $('#is_batch_input').val('0');
        }
        console.log('Initial is_batch value:', $('#is_batch_input').val());
    
        // Form validation
        $("#createSectionForm").on("submit", function(e) {
            // Prevent default submission to handle it manually
            e.preventDefault();
            
            // Double-check the is_batch value is correctly set
            if($('#batch_entry').is(':checked')) {
                $('#is_batch_input').val('1');
                
                // For batch mode, make sure the batch_sections data is submitted
                if ($('#batch_sections').val().trim() === '') {
                    alert('Please enter section data for batch creation');
                    return false;
                }
                
                // Validate batch format
                if (!validateBatchFormat()) {
                    console.log('Batch format validation failed');
                    return false;
                }
                
                // Create a proper form submission with batch data
                const form = $(this);
                
                // Remove any existing batch sections fields first
                form.find('input[name="batch_sections"]').remove();
                form.find('input[name="batch_sections_hidden"]').remove();
                
                // Create a new hidden input with the batch data, using proper JSON encoding
                // This prevents issues with commas and newlines being lost
                const batchData = JSON.stringify($('#batch_sections').val());
                form.append(`<input type="hidden" name="batch_sections_json" value='${batchData}'>`);
                
                console.log('Submitting with JSON encoded batch data', batchData);
            } else {
                $('#is_batch_input').val('0');
            }
            
            // Submit the form
            console.log('Submitting form...');
            this.submit();
        });
        
        // Add direct click handler for the submit button
        $("#submitSectionsBtn").click(function(e) {
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
                if(!$('#batch_sections').val().trim()) {
                    isFormValid = false;
                    $('#batch_sections').addClass('is-invalid');
                    console.log('Batch sections is empty');
                } else {
                    console.log('Batch sections is valid:', $('#batch_sections').val());
                    // Check batch format
                    isFormValid = validateBatchFormat();
                }
            } else {
                // Single form validation
                const requiredFields = ['name', 'grade_level', 'school_year', 'adviser_id'];
                
                requiredFields.forEach(field => {
                    const value = $(`#${field}`).val();
                    if (!value || value.trim() === '') {
                        $(`#${field}`).addClass('is-invalid');
                        isFormValid = false;
                        console.log(`Field ${field} validation failed - empty value`);
                    } else {
                        $(`#${field}`).removeClass('is-invalid');
                    }
                });
            }
            
            console.log('Submit button clicked, form valid:', isFormValid);
            
            if(isFormValid) {
                // If valid, manually submit the form
                console.log('Manually submitting form');
                
                // For batch mode, ensure the batch_sections data is included
                if($('#is_batch_input').val() === '1') {
                    const form = $('#createSectionForm');
                    
                    // Remove any existing batch sections fields first
                    form.find('input[name="batch_sections"]').remove();
                    form.find('input[name="batch_sections_hidden"]').remove();
                    form.find('input[name="batch_sections_json"]').remove();
                    
                    // Create a new hidden input with the batch data, using proper JSON encoding
                    const batchData = JSON.stringify($('#batch_sections').val());
                    form.append(`<input type="hidden" name="batch_sections_json" value='${batchData}'>`);
                    
                    console.log('Adding batch data with JSON encoding for submit button:', batchData);
                }
                
                $('#createSectionForm').submit();
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