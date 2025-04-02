@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-building text-primary me-2"></i> Add New School Division</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.school-divisions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Divisions
                    </a>
                </div>
            </div>
            <p class="text-muted">Create a new school division and add schools to it.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="divisionTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="division-info-tab" data-bs-toggle="tab" data-bs-target="#division-info" type="button" role="tab" aria-controls="division-info" aria-selected="true">
                                <i class="fas fa-info-circle me-1"></i> Division Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="add-schools-tab" data-bs-toggle="tab" data-bs-target="#add-schools" type="button" role="tab" aria-controls="add-schools" aria-selected="false">
                                <i class="fas fa-school me-1"></i> Add Schools
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.school-divisions.store') }}" id="divisionForm">
                        @csrf
                        <div class="tab-content" id="divisionTabsContent">
                            <!-- Division Information Tab -->
                            <div class="tab-pane fade show active" id="division-info" role="tabpanel" aria-labelledby="division-info-tab">
                                <div class="mb-4">
                                    <h5 class="mb-3">Basic Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label fw-bold">Division Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="code" class="form-label fw-bold">Division Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}">
                                            @error('code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="form-text">A unique code for this division (e.g., DIV-001)</div>
                                        </div>
                                        <div class="col-md-12">
                                            <label for="address" class="form-label fw-bold">Address</label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                                            @error('address')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label for="region" class="form-label fw-bold">Region</label>
                                            <select class="form-select @error('region') is-invalid @enderror" id="region" name="region">
                                                <option value="">-- Select Region --</option>
                                                <option value="Region I - Ilocos Region" {{ old('region') == 'Region I - Ilocos Region' ? 'selected' : '' }}>Region I - Ilocos Region</option>
                                                <option value="Region II - Cagayan Valley" {{ old('region') == 'Region II - Cagayan Valley' ? 'selected' : '' }}>Region II - Cagayan Valley</option>
                                                <option value="Region III - Central Luzon" {{ old('region') == 'Region III - Central Luzon' ? 'selected' : '' }}>Region III - Central Luzon</option>
                                                <option value="Region IV-A - CALABARZON" {{ old('region') == 'Region IV-A - CALABARZON' ? 'selected' : '' }}>Region IV-A - CALABARZON</option>
                                                <option value="MIMAROPA Region" {{ old('region') == 'MIMAROPA Region' ? 'selected' : '' }}>MIMAROPA Region</option>
                                                <option value="Region V - Bicol Region" {{ old('region') == 'Region V - Bicol Region' ? 'selected' : '' }}>Region V - Bicol Region</option>
                                                <option value="Region VI - Western Visayas" {{ old('region') == 'Region VI - Western Visayas' ? 'selected' : '' }}>Region VI - Western Visayas</option>
                                                <option value="Region VII - Central Visayas" {{ old('region') == 'Region VII - Central Visayas' ? 'selected' : '' }}>Region VII - Central Visayas</option>
                                                <option value="Region VIII - Eastern Visayas" {{ old('region') == 'Region VIII - Eastern Visayas' ? 'selected' : '' }}>Region VIII - Eastern Visayas</option>
                                                <option value="Region IX - Zamboanga Peninsula" {{ old('region') == 'Region IX - Zamboanga Peninsula' ? 'selected' : '' }}>Region IX - Zamboanga Peninsula</option>
                                                <option value="Region X - Northern Mindanao" {{ old('region') == 'Region X - Northern Mindanao' ? 'selected' : '' }}>Region X - Northern Mindanao</option>
                                                <option value="Region XI - Davao Region" {{ old('region') == 'Region XI - Davao Region' ? 'selected' : '' }}>Region XI - Davao Region</option>
                                                <option value="Region XII - SOCCSKSARGEN" {{ old('region') == 'Region XII - SOCCSKSARGEN' ? 'selected' : '' }}>Region XII - SOCCSKSARGEN</option>
                                                <option value="Region XIII - Caraga" {{ old('region') == 'Region XIII - Caraga' ? 'selected' : '' }}>Region XIII - Caraga</option>
                                                <option value="NCR - National Capital Region" {{ old('region') == 'NCR - National Capital Region' ? 'selected' : '' }}>NCR - National Capital Region</option>
                                                <option value="CAR - Cordillera Administrative Region" {{ old('region') == 'CAR - Cordillera Administrative Region' ? 'selected' : '' }}>CAR - Cordillera Administrative Region</option>
                                                <option value="BARMM - Bangsamoro Autonomous Region in Muslim Mindanao" {{ old('region') == 'BARMM - Bangsamoro Autonomous Region in Muslim Mindanao' ? 'selected' : '' }}>BARMM - Bangsamoro Autonomous Region in Muslim Mindanao</option>
                                                <option value="NIR - Negros Island Region" {{ old('region') == 'NIR - Negros Island Region' ? 'selected' : '' }}>NIR - Negros Island Region</option>
                                            </select>
                                            @error('region')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <div></div>
                                    <button type="button" class="btn btn-primary px-4" id="nextToSchools">
                                        <i class="fas fa-arrow-right ms-1"></i> Next: Add Schools
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Add Schools Tab -->
                            <div class="tab-pane fade" id="add-schools" role="tabpanel" aria-labelledby="add-schools-tab">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Add Schools to Division</h5>
                                        <button type="button" class="btn btn-success btn-sm" id="addSchoolRow">
                                            <i class="fas fa-plus me-1"></i> Add Another School
                                        </button>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> You can add multiple schools to this division. At least one school is required.
                                    </div>
                                    
                                    <div id="schoolsContainer">
                                        <!-- School Template -->
                                        <div class="school-entry card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <h6 class="card-title"><i class="fas fa-school me-2"></i> School #1</h6>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-school" disabled>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">School Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="schools[0][name]">
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">School Code <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="schools[0][code]">
                                                        <div class="form-text">A unique code for this school (e.g., SCH-001)</div>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold">School Address</label>
                                                        <textarea class="form-control" name="schools[0][address]" rows="2"></textarea>
                                                    </div>

                                                    <!-- Grade Levels Section -->
                                                    <div class="col-md-12 mt-3">
                                                        <label class="form-label fw-bold">Grade Levels <span class="text-danger">*</span></label>
                                                        <div class="card">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_k_0" name="schools[0][grade_levels][]" value="K">
                                                                            <label class="form-check-label" for="grade_k_0">Kindergarten</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_1_0" name="schools[0][grade_levels][]" value="1">
                                                                            <label class="form-check-label" for="grade_1_0">Grade 1</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_2_0" name="schools[0][grade_levels][]" value="2">
                                                                            <label class="form-check-label" for="grade_2_0">Grade 2</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_3_0" name="schools[0][grade_levels][]" value="3">
                                                                            <label class="form-check-label" for="grade_3_0">Grade 3</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_4_0" name="schools[0][grade_levels][]" value="4">
                                                                            <label class="form-check-label" for="grade_4_0">Grade 4</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_5_0" name="schools[0][grade_levels][]" value="5">
                                                                            <label class="form-check-label" for="grade_5_0">Grade 5</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_6_0" name="schools[0][grade_levels][]" value="6">
                                                                            <label class="form-check-label" for="grade_6_0">Grade 6</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_7_0" name="schools[0][grade_levels][]" value="7">
                                                                            <label class="form-check-label" for="grade_7_0">Grade 7</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_8_0" name="schools[0][grade_levels][]" value="8">
                                                                            <label class="form-check-label" for="grade_8_0">Grade 8</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_9_0" name="schools[0][grade_levels][]" value="9">
                                                                            <label class="form-check-label" for="grade_9_0">Grade 9</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_10_0" name="schools[0][grade_levels][]" value="10">
                                                                            <label class="form-check-label" for="grade_10_0">Grade 10</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_11_0" name="schools[0][grade_levels][]" value="11">
                                                                            <label class="form-check-label" for="grade_11_0">Grade 11</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 mb-2">
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox" id="grade_12_0" name="schools[0][grade_levels][]" value="12">
                                                                            <label class="form-check-label" for="grade_12_0">Grade 12</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="mt-2">
                                                                    <button type="button" class="btn btn-sm btn-outline-secondary select-all-grades" data-school-index="0">Select All</button>
                                                                    <button type="button" class="btn btn-sm btn-outline-secondary clear-all-grades" data-school-index="0">Clear All</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Teacher Section -->
                                                <div class="teachers-section mt-4">
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="mb-0"><i class="fas fa-chalkboard-teacher me-2"></i> Add Teachers to this School</h6>
                                                        <button type="button" class="btn btn-info btn-sm add-teacher" data-school-index="0">
                                                            <i class="fas fa-plus me-1"></i> Add Teacher
                                                        </button>
                                                    </div>
                                                    
                                                    <div class="teachers-container">
                                                        <!-- Teacher entries will be added here -->
                                                        <div class="teacher-entry card bg-light mb-2">
                                                            <div class="card-body py-2">
                                                                <div class="d-flex justify-content-between align-items-center mb-2">
                                                                    <h6 class="card-title mb-0"><i class="fas fa-user-tie me-2"></i> Teacher #1</h6>
                                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-teacher" disabled>
                                                                        <i class="fas fa-times"></i>
                                                                    </button>
                                                                </div>
                                                                <div class="row g-2">
                                                                    <div class="col-md-4">
                                                                        <input type="text" class="form-control form-control-sm" name="schools[0][teachers][0][name]" placeholder="Full Name">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="email" class="form-control form-control-sm" name="schools[0][teachers][0][email]" placeholder="Email">
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <input type="password" class="form-control form-control-sm" name="schools[0][teachers][0][password]" placeholder="Password">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary px-4" id="backToDivision">
                                        <i class="fas fa-arrow-left me-1"></i> Back to Division Info
                                    </button>
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="fas fa-save me-1"></i> Save School Division
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Make sure Bootstrap is loaded
        if (typeof bootstrap === 'undefined') {
            console.error('Bootstrap JavaScript is not loaded!');
            alert('There was an error loading the page components. Please refresh the page.');
            return;
        }

        console.log('Initializing tabs and buttons...');
        
        // Direct tab navigation with Bootstrap
        var divisionInfoTab = document.getElementById('division-info-tab');
        var addSchoolsTab = document.getElementById('add-schools-tab');
        
        if (!divisionInfoTab || !addSchoolsTab) {
            console.error('Tab elements not found!', {divisionInfoTab, addSchoolsTab});
            return;
        }
        
        var divisionInfoTabBS = new bootstrap.Tab(divisionInfoTab);
        var addSchoolsTabBS = new bootstrap.Tab(addSchoolsTab);
        
        // Navigate to schools tab
        var nextButton = document.getElementById('nextToSchools');
        if (nextButton) {
            nextButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Next button clicked, showing schools tab');
                addSchoolsTabBS.show();
            });
        } else {
            console.error('Next to Schools button not found!');
        }
        
        // Navigate back to division info tab
        var backButton = document.getElementById('backToDivision');
        if (backButton) {
            backButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Back button clicked, showing division tab');
                divisionInfoTabBS.show();
            });
        } else {
            console.error('Back to Division button not found!');
        }
        
        // Add School Row
        let schoolCounter = 1;
        var addSchoolButton = document.getElementById('addSchoolRow');
        if (addSchoolButton) {
            addSchoolButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Adding new school row');
                schoolCounter++;
                const container = document.getElementById('schoolsContainer');
                if (!container) {
                    console.error('Schools container not found!');
                    return;
                }
                
                const schoolTemplate = document.querySelector('.school-entry');
                if (!schoolTemplate) {
                    console.error('School template not found!');
                    return;
                }
                
                const newSchool = schoolTemplate.cloneNode(true);
                
                // Update school number
                const titleEl = newSchool.querySelector('.card-title');
                if (titleEl) {
                    titleEl.innerHTML = `<i class="fas fa-school me-2"></i> School #${schoolCounter}`;
                }
                
                // Update input names with correct index
                const inputs = newSchool.querySelectorAll('input, textarea');
                inputs.forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/schools\[\d+\]/, `schools[${schoolCounter - 1}]`);
                        input.value = '';
                        
                        // Update ID for checkbox inputs
                        if (input.type === 'checkbox') {
                            const oldId = input.id;
                            const newId = oldId.replace(/_\d+$/, `_${schoolCounter - 1}`);
                            input.id = newId;
                            
                            // Also update the corresponding label's 'for' attribute
                            const label = input.nextElementSibling;
                            if (label && label.htmlFor) {
                                label.htmlFor = newId;
                            }
                        }
                        
                        // Uncheck all checkboxes
                        if (input.type === 'checkbox') {
                            input.checked = false;
                        }
                    }
                });
                
                // Update select/clear buttons school index
                const selectAllBtn = newSchool.querySelector('.select-all-grades');
                const clearAllBtn = newSchool.querySelector('.clear-all-grades');
                
                if (selectAllBtn) {
                    selectAllBtn.dataset.schoolIndex = schoolCounter - 1;
                }
                
                if (clearAllBtn) {
                    clearAllBtn.dataset.schoolIndex = schoolCounter - 1;
                }
                
                // Enable remove button
                const removeBtn = newSchool.querySelector('.remove-school');
                if (removeBtn) {
                    removeBtn.disabled = false;
                    
                    // Explicitly add event listener for remove button
                    removeBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Removing school row');
                        this.closest('.school-entry').remove();
                    });
                }
                
                // Reset teachers container
                const teachersContainer = newSchool.querySelector('.teachers-container');
                if (teachersContainer) {
                    teachersContainer.innerHTML = '';
                }
                
                // Update add-teacher button's data-school-index
                const addTeacherBtn = newSchool.querySelector('.add-teacher');
                if (addTeacherBtn) {
                    addTeacherBtn.dataset.schoolIndex = schoolCounter - 1;
                    
                    // Add teacher event listener
                    addTeacherBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Adding teacher to school', this.dataset.schoolIndex);
                        const schoolIndex = this.dataset.schoolIndex;
                        const container = this.closest('.teachers-section').querySelector('.teachers-container');
                        if (container) {
                            addTeacher(schoolIndex, container);
                        } else {
                            console.error('Teachers container not found!');
                        }
                    });
                }
                
                container.appendChild(newSchool);
                
                // Initialize select/clear all grade buttons for the new school
                initGradeSelectionButtons(newSchool);
            });
        } else {
            console.error('Add School Row button not found!');
        }
        
        // Function to initialize grade selection buttons
        function initGradeSelectionButtons(container) {
            const selectAllBtn = container.querySelector('.select-all-grades');
            const clearAllBtn = container.querySelector('.clear-all-grades');
            
            if (selectAllBtn) {
                selectAllBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const schoolIndex = this.dataset.schoolIndex;
                    const checkboxes = container.querySelectorAll(`input[name="schools[${schoolIndex}][grade_levels][]"]`);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = true;
                    });
                });
            }
            
            if (clearAllBtn) {
                clearAllBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const schoolIndex = this.dataset.schoolIndex;
                    const checkboxes = container.querySelectorAll(`input[name="schools[${schoolIndex}][grade_levels][]"]`);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                });
            }
        }

        // Initialize grade selection buttons for existing schools
        document.querySelectorAll('.school-entry').forEach(function(school) {
            initGradeSelectionButtons(school);
            
            // Add event listeners to remove red border when a grade level is selected
            const checkboxes = school.querySelectorAll('input[type="checkbox"][name$="[grade_levels][]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', function() {
                    const schoolCard = this.closest('.school-entry');
                    const gradeLevelsCard = schoolCard.querySelector('.col-md-12.mt-3 .card');
                    if (gradeLevelsCard) {
                        // If any checkbox is checked, remove the border-danger class
                        const anyChecked = schoolCard.querySelector('input[type="checkbox"][name$="[grade_levels][]"]:checked');
                        if (anyChecked) {
                            gradeLevelsCard.classList.remove('border-danger');
                        }
                    }
                });
            });
        });

        // Add Teacher Function
        function addTeacher(schoolIndex, container) {
            console.log('Creating new teacher element');
            const teacherCount = container.querySelectorAll('.teacher-entry').length + 1;
            const teacherTemplate = document.createElement('div');
            teacherTemplate.className = 'teacher-entry card bg-light mb-2';
            teacherTemplate.innerHTML = `
                <div class="card-body py-2">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="card-title mb-0"><i class="fas fa-user-tie me-2"></i> Teacher #${teacherCount}</h6>
                        <button type="button" class="btn btn-outline-danger btn-sm remove-teacher">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-4">
                            <input type="text" class="form-control form-control-sm" name="schools[${schoolIndex}][teachers][${teacherCount - 1}][name]" placeholder="Full Name">
                        </div>
                        <div class="col-md-4">
                            <input type="email" class="form-control form-control-sm" name="schools[${schoolIndex}][teachers][${teacherCount - 1}][email]" placeholder="Email">
                        </div>
                        <div class="col-md-4">
                            <input type="password" class="form-control form-control-sm" name="schools[${schoolIndex}][teachers][${teacherCount - 1}][password]" placeholder="Password">
                        </div>
                    </div>
                </div>
            `;
            
            // Add remove teacher functionality
            const removeBtn = teacherTemplate.querySelector('.remove-teacher');
            if (removeBtn) {
                removeBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Removing teacher');
                    this.closest('.teacher-entry').remove();
                });
            }
            
            container.appendChild(teacherTemplate);
        }
        
        // Initialize add teacher functionality for all existing add-teacher buttons
        document.querySelectorAll('.add-teacher').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Add teacher button clicked', this.dataset.schoolIndex);
                const schoolIndex = this.dataset.schoolIndex;
                const container = this.closest('.teachers-section').querySelector('.teachers-container');
                if (container) {
                    addTeacher(schoolIndex, container);
                } else {
                    console.error('Teachers container not found!');
                }
            });
        });

        // Initialize remove buttons for existing teacher entries
        document.querySelectorAll('.remove-teacher').forEach(function(button) {
            if (button && !button.disabled) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Remove teacher button clicked');
                    this.closest('.teacher-entry').remove();
                });
            }
        });

        // Handle form submission
        const divisionForm = document.getElementById('divisionForm');
        if (divisionForm) {
            divisionForm.addEventListener('submit', function(e) {
                e.preventDefault();
                console.log('Form submission intercepted');
                
                // Custom validation
                let isValid = true;
                
                // Validate current tab
                const activeTab = document.querySelector('.tab-pane.active');
                if (activeTab.id === 'division-info') {
                    // Division info validation
                    const name = document.getElementById('name').value.trim();
                    const code = document.getElementById('code').value.trim();
                    
                    if (!name) {
                        isValid = false;
                        document.getElementById('name').classList.add('is-invalid');
                        alert('Division Name is required');
                    }
                    
                    if (!code) {
                        isValid = false;
                        document.getElementById('code').classList.add('is-invalid');
                        alert('Division Code is required');
                    }
                } else if (activeTab.id === 'add-schools') {
                    // Schools validation
                    const schoolEntries = document.querySelectorAll('.school-entry');
                    if (schoolEntries.length === 0) {
                        isValid = false;
                        alert('At least one school is required');
                    }
                    
                    schoolEntries.forEach((school, index) => {
                        const nameInput = school.querySelector(`input[name="schools[${index}][name]"]`);
                        const codeInput = school.querySelector(`input[name="schools[${index}][code]"]`);
                        
                        if (!nameInput.value.trim()) {
                            isValid = false;
                            nameInput.classList.add('is-invalid');
                            alert(`School #${index + 1} Name is required`);
                        }
                        
                        if (!codeInput.value.trim()) {
                            isValid = false;
                            codeInput.classList.add('is-invalid');
                            alert(`School #${index + 1} Code is required`);
                        }
                        
                        // Check grade levels
                        const gradeLevels = school.querySelectorAll(`input[name="schools[${index}][grade_levels][]"]:checked`);
                        if (gradeLevels.length === 0) {
                            isValid = false;
                            // Add a red border to the grade levels card
                            const gradeLevelsCard = school.querySelector('.col-md-12.mt-3 .card');
                            if (gradeLevelsCard) {
                                gradeLevelsCard.classList.add('border-danger');
                            }
                            alert(`School #${index + 1} must have at least one grade level selected`);
                        }
                    });
                }
                
                if (!isValid) {
                    console.log('Validation failed');
                    return;
                }
                
                // Collect form data
                const formData = new FormData(divisionForm);
                
                // Send form data via fetch
                fetch(divisionForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                        return;
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.redirect) {
                        window.location.href = data.redirect;
                    } else if (data && data.errors) {
                        // Handle validation errors
                        console.error('Validation errors:', data.errors);
                        
                        // Show first tab if division info errors
                        if (data.errors.name || data.errors.code || data.errors.address) {
                            divisionInfoTabBS.show();
                            
                            if (data.errors.name) {
                                document.getElementById('name').classList.add('is-invalid');
                                alert(data.errors.name[0]);
                            }
                            
                            if (data.errors.code) {
                                document.getElementById('code').classList.add('is-invalid');
                                alert(data.errors.code[0]);
                            }
                        }
                        
                        // Alert the user
                        alert('Please check the form for errors and try again.');
                    }
                })
                .catch(error => {
                    console.error('Error submitting form:', error);
                    alert('An error occurred while saving. Please try again.');
                });
            });
            
            // Clear validation errors when input changes
            divisionForm.querySelectorAll('input, textarea').forEach(input => {
                input.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                });
            });
        }

        console.log('Initialization complete');
    });
</script>
@endpush

@push('head')
<style>
    /* Optional: Add some hover styles to make buttons more obviously clickable */
    .btn:hover {
        opacity: 0.9;
        cursor: pointer !important;
    }
</style>
@endpush

@endsection 