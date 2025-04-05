@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-check text-primary me-2"></i> Record New Attendance
                        </h5>
                        <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Back to Records
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teacher.attendances.store') }}" id="attendanceForm">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="section_id" class="form-label fw-medium">
                                        <i class="fas fa-users text-secondary me-1"></i> Section <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select form-select-lg @error('section_id') is-invalid @enderror" 
                                        id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="date" class="form-label fw-medium">
                                        <i class="fas fa-calendar-alt text-secondary me-1"></i> Date <span class="text-danger">*</span>
                                    </label>
                                    <input type="date" class="form-control form-control-lg @error('date') is-invalid @enderror" 
                                        id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                    @error('date')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div id="students-container" class="mb-4">
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-chalkboard-teacher fa-3x text-muted"></i>
                                </div>
                                <h6 class="text-muted">Please select a section to load students</h6>
                                <p class="text-muted small">Student list will appear here after selecting a section</p>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg" disabled>
                                <i class="fas fa-save me-1"></i> Save Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .status-badge {
        width: 12px;
        height: 12px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 5px;
    }
    .status-present { background-color: #28a745; }
    .status-late { background-color: #ffc107; }
    .status-absent { background-color: #dc3545; }
    .status-excused { background-color: #6c757d; }
    .status-half_day { background-color: #17a2b8; }
    
    .attendance-table th, 
    .attendance-table td {
        vertical-align: middle;
    }
    
    .form-check-input:checked[value="present"] {
        background-color: #28a745;
        border-color: #28a745;
    }
    
    .form-check-input:checked[value="late"] {
        background-color: #ffc107;
        border-color: #ffc107;
    }
    
    .form-check-input:checked[value="absent"] {
        background-color: #dc3545;
        border-color: #dc3545;
    }
    
    .form-check-input:checked[value="excused"] {
        background-color: #6c757d;
        border-color: #6c757d;
    }
    
    .form-check-input:checked[value="half_day"] {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
    
    .student-table-container {
        max-height: 600px;
        overflow-y: auto;
    }
    
    /* Loading animation */
    .loading-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .loading-spinner {
        width: 40px;
        height: 40px;
        border: 4px solid rgba(0, 0, 0, 0.1);
        border-left-color: #3490dc;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-bottom: 1rem;
    }
    
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionSelect = document.getElementById('section_id');
        const studentsContainer = document.getElementById('students-container');
        const submitBtn = document.getElementById('submitBtn');
        
        sectionSelect.addEventListener('change', function() {
            const sectionId = this.value;
            submitBtn.disabled = true;
            
            if (!sectionId) {
                studentsContainer.innerHTML = `
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-chalkboard-teacher fa-3x text-muted"></i>
                        </div>
                        <h6 class="text-muted">Please select a section to load students</h6>
                        <p class="text-muted small">Student list will appear here after selecting a section</p>
                    </div>
                `;
                return;
            }
            
            // Show loading indicator
            studentsContainer.innerHTML = `
                <div class="loading-container">
                    <div class="loading-spinner"></div>
                    <p>Loading students...</p>
                </div>
            `;
            
            fetch(`/teacher/sections/${sectionId}/students`)
                .then(response => response.json())
                .then(data => {
                    if (data.students.length === 0) {
                        studentsContainer.innerHTML = `                            <div class="alert alert-info">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-info-circle fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">No students found</h6>
                                        <p class="mb-0">There are no students assigned to this section yet.</p>
                                        <a href="/teacher/students/create" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-plus-circle me-1"></i> Add Students
                                        </a>
                                    </div>
                                </div>
                            </div>
                        `;
                        return;
                    }
                    
                    submitBtn.disabled = false;
                    
                    let html = `
                        <div class="card bg-light">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard-list me-1"></i> 
                                        Student Attendance (${data.students.length} students)
                                    </h6>
                                    <div>
                                        <button type="button" class="btn btn-sm btn-success me-1" id="markAllPresent">
                                            <i class="fas fa-check-circle me-1"></i> Mark All Present
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                <div class="student-table-container">
                                    <table class="table table-hover attendance-table mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 40%;">Student Name</th>
                                                <th style="width: 25%;">ID Number</th>
                                                <th style="width: 35%;">Attendance Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    `;
                    
                    data.students.forEach(student => {
                        html += `
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-container me-2">
                                            <span class="avatar bg-primary text-white rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                ${student.first_name.charAt(0)}${student.last_name.charAt(0)}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-medium">${student.first_name} ${student.last_name}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                        ${student.student_id}
                                    </span>
                                </td>
                                <td>
                                    <div class="attendance-options">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                name="attendance[${student.id}]" 
                                                id="present_${student.id}" 
                                                value="present" 
                                                checked>
                                            <label class="form-check-label" for="present_${student.id}">
                                                <span class="status-badge status-present"></span>Present
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                name="attendance[${student.id}]" 
                                                id="late_${student.id}" 
                                                value="late">
                                            <label class="form-check-label" for="late_${student.id}">
                                                <span class="status-badge status-late"></span>Late
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                name="attendance[${student.id}]" 
                                                id="absent_${student.id}" 
                                                value="absent">
                                            <label class="form-check-label" for="absent_${student.id}">
                                                <span class="status-badge status-absent"></span>Absent
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                name="attendance[${student.id}]" 
                                                id="excused_${student.id}" 
                                                value="excused">
                                            <label class="form-check-label" for="excused_${student.id}">
                                                <span class="status-badge status-excused"></span>Excused
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" 
                                                name="attendance[${student.id}]" 
                                                id="half_day_${student.id}" 
                                                value="half_day">
                                            <label class="form-check-label" for="half_day_${student.id}">
                                                <span class="status-badge status-half_day"></span>Half Day
                                            </label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += `
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer bg-light">
                                <div class="d-flex align-items-center text-muted small">
                                    <div class="me-3">
                                        <span class="status-badge status-present"></span> Present
                                    </div>
                                    <div class="me-3">
                                        <span class="status-badge status-late"></span> Late
                                    </div>
                                    <div class="me-3">
                                        <span class="status-badge status-absent"></span> Absent
                                    </div>
                                    <div class="me-3">
                                        <span class="status-badge status-excused"></span> Excused
                                    </div>
                                    <div>
                                        <span class="status-badge status-half_day"></span> Half Day
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    
                    studentsContainer.innerHTML = html;
                    
                    // Add event listener to "Mark All Present" button
                    document.getElementById('markAllPresent').addEventListener('click', function() {
                        document.querySelectorAll('input[value="present"]').forEach(radio => {
                            radio.checked = true;
                        });
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    studentsContainer.innerHTML = `
                        <div class="alert alert-danger">
                            <div class="d-flex">
                                <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                <div>
                                    <h6 class="mb-1">Error Loading Students</h6>
                                    <p class="mb-0">There was a problem loading the student list. Please try again.</p>
                                    <button class="btn btn-sm btn-outline-danger mt-2" onClick="window.location.reload();">
                                        <i class="fas fa-redo me-1"></i> Reload Page
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
        });
        
        // Trigger change if section is already selected (e.g., when coming back from validation error)
        if (sectionSelect.value) {
            sectionSelect.dispatchEvent(new Event('change'));
        }
        
        // Form validation
        document.getElementById('attendanceForm').addEventListener('submit', function(e) {
            const section = document.getElementById('section_id').value;
            const date = document.getElementById('date').value;
            
            if (!section || !date) {
                e.preventDefault();
                alert('Please select both a section and date before submitting.');
            }
        });
    });
</script>
@endpush@endsection 

