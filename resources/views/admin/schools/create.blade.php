@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-school text-success me-2"></i> Add New School</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Schools
                    </a>
                </div>
            </div>
            <p class="text-muted">Register a new school in the system and add teachers to it.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <ul class="nav nav-tabs card-header-tabs" id="schoolTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="school-info-tab" data-bs-toggle="tab" data-bs-target="#school-info" type="button" role="tab" aria-controls="school-info" aria-selected="true">
                                <i class="fas fa-info-circle me-1"></i> School Information
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="add-teachers-tab" data-bs-toggle="tab" data-bs-target="#add-teachers" type="button" role="tab" aria-controls="add-teachers" aria-selected="false">
                                <i class="fas fa-chalkboard-teacher me-1"></i> Add Teachers
                            </button>
                        </li>
                    </ul>
                </div>
                
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.schools.store') }}" id="schoolForm">
                        @csrf
                        <div class="tab-content" id="schoolTabsContent">
                            <!-- School Information Tab -->
                            <div class="tab-pane fade show active" id="school-info" role="tabpanel" aria-labelledby="school-info-tab">
                                <div class="mb-4">
                                    <h5 class="mb-3">Basic Information</h5>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="name" class="form-label fw-bold">School Name <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                            @error('name')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="code" class="form-label fw-bold">School Code <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                                            @error('code')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                            <div class="form-text">A unique code for this school (e.g., SCH-001)</div>
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
                                            <label for="principal" class="form-label fw-bold">Principal</label>
                                            <input type="text" class="form-control @error('principal') is-invalid @enderror" id="principal" name="principal" value="{{ old('principal') }}" placeholder="Enter principal's full name">
                                            @error('principal')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label for="school_division_id" class="form-label fw-bold">School Division <span class="text-danger">*</span></label>
                                            <select class="form-select @error('school_division_id') is-invalid @enderror" id="school_division_id" name="school_division_id" required>
                                                <option value="">Select School Division</option>
                                                @foreach(\App\Models\SchoolDivision::all() as $division)
                                                    <option value="{{ $division->id }}" {{ old('school_division_id') == $division->id ? 'selected' : '' }}>
                                                        {{ $division->name }} ({{ $division->code }})
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('school_division_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <!-- Grade Levels Section -->
                                        <div class="col-md-12 mt-3">
                                            <label class="form-label fw-bold">Grade Levels <span class="text-danger">*</span></label>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_k" name="grade_levels[]" value="K" {{ is_array(old('grade_levels')) && in_array('K', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_k">Kindergarten</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_1" name="grade_levels[]" value="1" {{ is_array(old('grade_levels')) && in_array('1', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_1">Grade 1</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_2" name="grade_levels[]" value="2" {{ is_array(old('grade_levels')) && in_array('2', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_2">Grade 2</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_3" name="grade_levels[]" value="3" {{ is_array(old('grade_levels')) && in_array('3', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_3">Grade 3</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_4" name="grade_levels[]" value="4" {{ is_array(old('grade_levels')) && in_array('4', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_4">Grade 4</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_5" name="grade_levels[]" value="5" {{ is_array(old('grade_levels')) && in_array('5', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_5">Grade 5</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_6" name="grade_levels[]" value="6" {{ is_array(old('grade_levels')) && in_array('6', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_6">Grade 6</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_7" name="grade_levels[]" value="7" {{ is_array(old('grade_levels')) && in_array('7', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_7">Grade 7</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_8" name="grade_levels[]" value="8" {{ is_array(old('grade_levels')) && in_array('8', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_8">Grade 8</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_9" name="grade_levels[]" value="9" {{ is_array(old('grade_levels')) && in_array('9', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_9">Grade 9</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_10" name="grade_levels[]" value="10" {{ is_array(old('grade_levels')) && in_array('10', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_10">Grade 10</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_11" name="grade_levels[]" value="11" {{ is_array(old('grade_levels')) && in_array('11', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_11">Grade 11</label>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 mb-2">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" id="grade_12" name="grade_levels[]" value="12" {{ is_array(old('grade_levels')) && in_array('12', old('grade_levels')) ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="grade_12">Grade 12</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAllGrades">Select All</button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllGrades">Clear All</button>
                                                    </div>
                                                    @error('grade_levels')
                                                        <div class="text-danger mt-2">
                                                            <small><strong>{{ $message }}</strong></small>
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between mt-4">
                                    <div></div>
                                    <button type="button" class="btn btn-primary px-4" id="nextToTeachers">
                                        Next: Add Teachers <i class="fas fa-arrow-right ms-1"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Add Teachers Tab -->
                            <div class="tab-pane fade" id="add-teachers" role="tabpanel" aria-labelledby="add-teachers-tab">
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="mb-0">Add Teachers to School</h5>
                                        <button type="button" class="btn btn-success btn-sm" id="addTeacherRow">
                                            <i class="fas fa-plus me-1"></i> Add Another Teacher
                                        </button>
                                    </div>
                                    
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i> You can add multiple teachers to this school.
                                    </div>
                                    
                                    <div id="teachersContainer">
                                        <!-- Teacher Template -->
                                        <div class="teacher-entry card mb-3">
                                            <div class="card-body">
                                                <div class="d-flex justify-content-between mb-3">
                                                    <h6 class="card-title"><i class="fas fa-user-tie me-2"></i> Teacher #1</h6>
                                                    <button type="button" class="btn btn-outline-danger btn-sm remove-teacher" disabled>
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </div>
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="teachers[0][name]" placeholder="Enter teacher's full name" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" name="teachers[0][email]" placeholder="Enter email address" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" name="teachers[0][password]" placeholder="Create a password" required>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" name="teachers[0][password_confirmation]" placeholder="Confirm password" required>
                                                    </div>
                                                    <div class="col-md-12">
                                                        <label class="form-label fw-bold">Subjects/Expertise</label>
                                                        <input type="text" class="form-control" name="teachers[0][subjects]" placeholder="e.g., Mathematics, Science, English">
                                                        <div class="form-text">Enter subjects separated by commas</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="d-flex justify-content-between mt-4">
                                    <button type="button" class="btn btn-outline-secondary px-4" id="backToSchool">
                                        <i class="fas fa-arrow-left me-1"></i> Back to School Info
                                    </button>
                                    <button type="submit" class="btn btn-success px-4">
                                        <i class="fas fa-save me-1"></i> Save School
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
        // Direct tab navigation with Bootstrap
        var schoolInfoTab = new bootstrap.Tab(document.getElementById('school-info-tab'));
        var addTeachersTab = new bootstrap.Tab(document.getElementById('add-teachers-tab'));
        
        // Navigate to teachers tab
        document.getElementById('nextToTeachers').addEventListener('click', function() {
            addTeachersTab.show();
        });
        
        // Navigate back to school info tab
        document.getElementById('backToSchool').addEventListener('click', function() {
            schoolInfoTab.show();
        });
        
        // Add Teacher Row
        let teacherCounter = 1;
        document.getElementById('addTeacherRow').addEventListener('click', function() {
            teacherCounter++;
            const container = document.getElementById('teachersContainer');
            const teacherTemplate = document.querySelector('.teacher-entry').cloneNode(true);
            
            // Update teacher number
            teacherTemplate.querySelector('.card-title').innerHTML = `<i class="fas fa-user-tie me-2"></i> Teacher #${teacherCounter}`;
            
            // Update input names with correct index
            const inputs = teacherTemplate.querySelectorAll('input');
            inputs.forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/teachers\[0\]/, `teachers[${teacherCounter - 1}]`);
                    input.value = '';
                }
            });
            
            // Enable remove button
            const removeBtn = teacherTemplate.querySelector('.remove-teacher');
            removeBtn.disabled = false;
            
            // Use onclick instead of addEventListener to ensure it works
            removeBtn.onclick = function() {
                this.closest('.teacher-entry').remove();
            };
            
            container.appendChild(teacherTemplate);
        });

        // Grade Levels functionality
        const selectAllGradesBtn = document.getElementById('selectAllGrades');
        const clearAllGradesBtn = document.getElementById('clearAllGrades');
        const gradeCheckboxes = document.querySelectorAll('input[name="grade_levels[]"]');

        if (selectAllGradesBtn) {
            selectAllGradesBtn.addEventListener('click', function() {
                gradeCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                const gradeLevelsCard = document.querySelector('.col-md-12.mt-3 .card');
                if (gradeLevelsCard && gradeLevelsCard.classList.contains('border-danger')) {
                    gradeLevelsCard.classList.remove('border-danger');
                }
            });
        }

        if (clearAllGradesBtn) {
            clearAllGradesBtn.addEventListener('click', function() {
                gradeCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
        }

        // Form validation
        document.getElementById('schoolForm').addEventListener('submit', function(e) {
            // Check if at least one grade level is selected
            const checkedGrades = document.querySelectorAll('input[name="grade_levels[]"]:checked');
            if (checkedGrades.length === 0) {
                e.preventDefault();
                const gradeLevelsCard = document.querySelector('.col-md-12.mt-3 .card');
                if (gradeLevelsCard) {
                    gradeLevelsCard.classList.add('border-danger');
                }
                alert('Please select at least one grade level');
                schoolInfoTab.show(); // Ensure the school info tab is shown
            }
        });

        // Add event listeners to remove red border when a grade level is selected
        gradeCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    const gradeLevelsCard = document.querySelector('.col-md-12.mt-3 .card');
                    if (gradeLevelsCard && gradeLevelsCard.classList.contains('border-danger')) {
                        gradeLevelsCard.classList.remove('border-danger');
                    }
                }
            });
        });
    });
</script>
@endpush
@endsection 