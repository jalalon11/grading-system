@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-table me-2"></i>Generate Class Record
                    </h4>
                    <a href="{{ route('teacher.reports.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Reports
                    </a>
                </div>
                <div class="card-body pt-0">
                    <p class="text-muted mb-4">Complete the form below to generate a comprehensive class record report.</p>
                    
                    <div class="bg-light p-4 rounded-3 mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="report-icon">
                                <i class="fas fa-info-circle text-primary"></i>
                            </div>
                            <div class="ms-3">
                                <h5 class="mb-1 fw-bold">Report Information</h5>
                                <p class="text-muted mb-0 small">The report will show student grades organized by component categories.</p>
                            </div>
                        </div>
                    </div>
                    
                    <form action="{{ route('teacher.reports.generate-class-record') }}" method="POST" target="_blank" class="needs-validation" novalidate>
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-12 mb-3">
                                <label for="section_id" class="form-label fw-semibold">Section</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-users"></i></span>
                                    <select name="section_id" id="section_id" class="form-select @error('section_id') is-invalid @enderror" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                        <option value="{{ $section->id }}">{{ $section->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Select the class section for which you want to generate the report</div>
                            </div>
                            
                            <div class="col-md-12 mb-3">
                                <label for="subject_id" class="form-label fw-semibold">Subject</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-book"></i></span>
                                    <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required disabled>
                                        <option value="">Select a section first</option>
                                    </select>
                                    @error('subject_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Choose the subject for the selected class section</div>
                            </div>
                            
                            <!-- MAPEH Component Selection (initially hidden) -->
                            <div class="col-md-12 mb-3" id="mapeh_component_container" style="display: none;">
                                <label for="mapeh_component_id" class="form-label fw-semibold">MAPEH Component</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-info text-white"><i class="fas fa-puzzle-piece"></i></span>
                                    <select name="subject_id" id="mapeh_component_id" class="form-select" disabled>
                                        <option value="">Select a MAPEH component</option>
                                    </select>
                                </div>
                                <div class="form-text">For MAPEH subjects, select which component to generate a class record for</div>
                            </div>
                            
                            <div class="col-md-12 mb-4">
                                <label for="quarter" class="form-label fw-semibold">Quarter</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light"><i class="fas fa-calendar-alt"></i></span>
                                    <select name="quarter" id="quarter" class="form-select @error('quarter') is-invalid @enderror" required>
                                        <option value="">Select Quarter</option>
                                        <option value="Q1">First Quarter</option>
                                        <option value="Q2">Second Quarter</option>
                                        <option value="Q3">Third Quarter</option>
                                        <option value="Q4">Fourth Quarter</option>
                                    </select>
                                    @error('quarter')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-text">Select the grading period for the report</div>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary py-2">
                                <i class="fas fa-file-alt me-2"></i> Generate Class Record Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .report-icon {
        width: 50px;
        height: 50px;
        background-color: rgba(13, 110, 253, 0.1);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .report-icon i {
        font-size: 1.5rem;
    }
    
    .form-label {
        color: #495057;
    }
    
    .input-group-text {
        color: #495057;
        border-color: #ced4da;
    }
    
    .form-select, .form-control {
        border-color: #ced4da;
    }
    
    .form-text {
        font-size: 0.85rem;
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionSelect = document.getElementById('section_id');
        const subjectSelect = document.getElementById('subject_id');
        const mapehComponentContainer = document.getElementById('mapeh_component_container');
        const mapehComponentSelect = document.getElementById('mapeh_component_id');
        
        // Keep track of MAPEH components for each subject
        let mapehComponents = {};
        
        // Function to load subjects for a section
        function loadSubjects(sectionId) {
            if (!sectionId) {
                subjectSelect.innerHTML = '<option value="">Select a section first</option>';
                subjectSelect.disabled = true;
                return;
            }
            
            subjectSelect.disabled = true;
            subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
            
            fetch(`{{ route('teacher.reports.section-subjects') }}?section_id=${sectionId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                
                data.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name;
                    
                    // Add a data attribute to indicate if this is a MAPEH subject
                    if (subject.components && subject.components.length === 4) {
                        // Check if it has the MAPEH components
                        const componentNames = subject.components.map(c => c.name.toLowerCase());
                        const isMAPEH = 
                            (componentNames.some(name => name.includes('music')) &&
                             componentNames.some(name => name.includes('art')) &&
                             (componentNames.some(name => name.includes('physical')) || componentNames.some(name => name.includes('pe'))) &&
                             componentNames.some(name => name.includes('health')));
                             
                        if (isMAPEH) {
                            option.dataset.isMapeh = 'true';
                            // Store the components for this subject ID
                            mapehComponents[subject.id] = subject.components;
                        }
                    }
                    
                    subjectSelect.appendChild(option);
                });
                
                subjectSelect.disabled = false;
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
            });
        }
        
        // Function to handle subject change
        function handleSubjectChange() {
            const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
            const isMapeh = selectedOption.dataset.isMapeh === 'true';
            
            // If it's a MAPEH subject, show the component selection
            if (isMapeh) {
                mapehComponentContainer.style.display = 'block';
                mapehComponentSelect.disabled = false;
                mapehComponentSelect.innerHTML = '<option value="">Select MAPEH Component</option>';
                
                // Add the components as options
                const subjectId = selectedOption.value;
                if (mapehComponents[subjectId]) {
                    mapehComponents[subjectId].forEach(component => {
                        const option = document.createElement('option');
                        option.value = component.id;
                        option.textContent = component.name;
                        mapehComponentSelect.appendChild(option);
                    });
                }
            } else {
                // Hide the component selection for non-MAPEH subjects
                mapehComponentContainer.style.display = 'none';
                mapehComponentSelect.disabled = true;
            }
        }
        
        // Event listener for section change
        sectionSelect.addEventListener('change', function() {
            loadSubjects(this.value);
            // Reset MAPEH component display
            mapehComponentContainer.style.display = 'none';
        });
        
        // Event listener for subject change
        subjectSelect.addEventListener('change', handleSubjectChange);
        
        // Form submission handler to use the correct subject_id
        document.querySelector('form').addEventListener('submit', function(e) {
            const selectedOption = subjectSelect.options[subjectSelect.selectedIndex];
            const isMapeh = selectedOption.dataset.isMapeh === 'true';
            
            if (isMapeh && mapehComponentSelect.value) {
                // If a MAPEH component is selected, use that ID instead
                subjectSelect.disabled = true; // Disable to avoid sending the parent subject ID
                mapehComponentSelect.name = 'subject_id'; // Ensure this has the correct name
            }
        });
        
        // Form validation
        (function() {
            'use strict';
            
            // Fetch all forms we want to apply validation styles to
            const forms = document.querySelectorAll('.needs-validation');
            
            // Loop over them and prevent submission
            Array.from(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    });
</script>
@endpush 