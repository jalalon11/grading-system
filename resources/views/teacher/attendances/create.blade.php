@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-0">
                    <div class="px-4 py-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-user-check text-primary me-2"></i> Record New Attendance
                            </h5>
                            <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Records
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div>
                                    {!! session('error') !!}
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <x-school-day-indicator :schoolDays="$schoolDays" :currentMonth="$currentMonth" />
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-info mb-0 h-100">
                                <div class="d-flex">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x text-primary"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading mb-1">School Day Attendance</h5>
                                        <p class="mb-0">Recording attendance for a date automatically marks it as a <strong>school day</strong>. Attendance statistics and reports will include this date in calculations for school days attended.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('teacher.attendances.store') }}" id="attendanceForm">
                        @csrf

                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-white py-3">
                                <h6 class="mb-0">
                                    <i class="fas fa-filter text-primary me-1"></i> Select Section and Date
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="section_id" class="form-label fw-medium">
                                                <i class="fas fa-users text-secondary me-1"></i> Section <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-select @error('section_id') is-invalid @enderror"
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
                                            <input type="date" class="form-control @error('date') is-invalid @enderror"
                                                id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                            @error('date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="students-container" class="mb-4">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body py-5">
                                    <div class="text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-chalkboard-teacher fa-3x text-primary opacity-50"></i>
                                        </div>
                                        <h5 class="mb-2">Please select a section to load students</h5>
                                        <p class="text-muted">The student list will appear here after selecting a section</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Back to Attendance Records
                            </a>
                            <button type="submit" id="submitBtn" class="btn btn-primary" disabled>
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
    /* Status badges */
    .status-badge {
        width: 12px;
        height: 12px;
        display: inline-block;
        border-radius: 50%;
        margin-right: 5px;
        flex-shrink: 0;
    }
    .status-present { background-color: #28a745; }
    .status-late { background-color: #ffc107; }
    .status-absent { background-color: #dc3545; }
    .status-excused { background-color: #6c757d; }
    .status-half_day { background-color: #17a2b8; }

    /* Table styling */
    .attendance-table th,
    .attendance-table td {
        vertical-align: middle;
    }

    /* Responsive table */
    @media (max-width: 767.98px) {
        .attendance-table thead {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .attendance-options {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }

        .form-check-inline {
            margin-right: 0;
            margin-bottom: 0.25rem;
        }

        .card-footer .d-flex {
            flex-wrap: wrap;
            gap: 0.75rem;
        }
    }

    /* Radio button styling */
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

    /* Attendance options container */
    .attendance-options {
        display: flex;
        flex-wrap: wrap;
    }

    .form-check-inline {
        margin-right: 0.75rem;
    }

    /* Student table container */
    .student-table-container {
        max-height: 600px;
        overflow-y: auto;
        border-radius: 0.25rem;
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

    /* Action buttons */
    .action-buttons {
        display: flex;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    /* Student avatar */
    .student-avatar {
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionSelect = document.getElementById('section_id');
        const dateInput = document.getElementById('date');
        const studentsContainer = document.getElementById('students-container');
        const submitBtn = document.getElementById('submitBtn');
        let existingAttendanceAlert = null;

        // Function to check if attendance exists for the selected section and date
        function checkAttendanceExists() {
            const sectionId = sectionSelect.value;
            const date = dateInput.value;

            if (!sectionId || !date) return;

            // Remove any existing alert
            if (existingAttendanceAlert) {
                existingAttendanceAlert.remove();
                existingAttendanceAlert = null;
            }

            fetch('/teacher/attendance/check-exists', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    section_id: sectionId,
                    date: date
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    // Create alert for existing attendance
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-warning alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-exclamation-triangle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading mb-1">Attendance Already Exists</h5>
                                <p class="mb-2">Attendance for this section and date has already been recorded.</p>
                                <a href="${data.edit_url}" class="btn btn-warning">
                                    <i class="fas fa-edit me-1"></i> Edit Existing Attendance
                                </a>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;

                    // Insert alert before the students container
                    studentsContainer.parentNode.insertBefore(alertDiv, studentsContainer);
                    existingAttendanceAlert = alertDiv;

                    // Disable the submit button
                    submitBtn.disabled = true;
                }
            })
            .catch(error => {
                console.error('Error checking attendance:', error);
            });
        }

        // Add event listeners to both section and date inputs
        sectionSelect.addEventListener('change', function() {
            const sectionId = this.value;
            submitBtn.disabled = true;

            // Check if attendance exists when both section and date are selected
            if (sectionId && dateInput.value) {
                checkAttendanceExists();
            }

            if (!sectionId) {
                studentsContainer.innerHTML = `
                    <div class="card border-0 shadow-sm">
                        <div class="card-body py-5">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-chalkboard-teacher fa-3x text-primary opacity-50"></i>
                                </div>
                                <h5 class="mb-2">Please select a section to load students</h5>
                                <p class="text-muted">The student list will appear here after selecting a section</p>
                            </div>
                        </div>
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
                        studentsContainer.innerHTML = `
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="alert alert-info mb-0">
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
                                </div>
                            </div>
                        `;
                        return;
                    }

                    submitBtn.disabled = false;

                    let html = `
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-white py-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <h6 class="mb-0">
                                        <i class="fas fa-clipboard-list text-primary me-1"></i>
                                        Student Attendance (${data.students.length} students)
                                    </h6>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-success" id="markAllPresent">
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
                                                <th class="d-none d-md-table-cell" style="width: 5%;">No.</th>
                                                <th style="width: 35%;">Student Name</th>
                                                <th class="d-none d-md-table-cell" style="width: 20%;">ID Number</th>
                                                <th style="width: 40%;">Attendance Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                    `;

                    data.students.forEach((student, index) => {
                        html += `
                            <tr>
                                <td class="d-none d-md-table-cell">${index + 1}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="me-2">
                                            <span class="student-avatar bg-primary text-white rounded-circle">
                                                ${student.first_name.charAt(0)}${student.last_name.charAt(0)}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="fw-medium">
                                                ${student.last_name}, ${student.first_name}${student.middle_name ? ' ' + student.middle_name.charAt(0) + '.' : ''}
                                            </div>
                                            <div class="small text-muted d-md-none">${student.student_id}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-md-table-cell">
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
                                            <input class="form-check-input excused-radio" type="radio"
                                                name="attendance[${student.id}]"
                                                id="excused_${student.id}"
                                                value="excused"
                                                data-student-id="${student.id}">
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
                                        <div class="excused-reason-container mt-2 d-none" id="excused_reason_container_${student.id}">
                                            <input type="text" class="form-control form-control-sm"
                                                name="remarks[${student.id}]"
                                                id="remarks_${student.id}"
                                                placeholder="Enter reason for excuse">
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
                            <div class="card-footer bg-white py-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="d-flex align-items-center text-muted small flex-wrap gap-2">
                                            <div class="me-2">
                                                <span class="status-badge status-present"></span> Present
                                            </div>
                                            <div class="me-2">
                                                <span class="status-badge status-late"></span> Late
                                            </div>
                                            <div class="me-2">
                                                <span class="status-badge status-absent"></span> Absent
                                            </div>
                                            <div class="me-2">
                                                <span class="status-badge status-excused"></span> Excused
                                            </div>
                                            <div>
                                                <span class="status-badge status-half_day"></span> Half Day
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mt-3 mt-md-0 text-md-end">
                                        <div class="text-muted small">Click on a status to mark attendance</div>
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
                        // Hide all excused reason fields
                        document.querySelectorAll('.excused-reason-container').forEach(container => {
                            container.classList.add('d-none');
                        });
                    });

                    // Add event listeners to excused radio buttons
                    document.querySelectorAll('.excused-radio').forEach(radio => {
                        radio.addEventListener('change', function() {
                            const studentId = this.getAttribute('data-student-id');
                            const reasonContainer = document.getElementById(`excused_reason_container_${studentId}`);

                            if (this.checked) {
                                reasonContainer.classList.remove('d-none');
                            } else {
                                reasonContainer.classList.add('d-none');
                            }
                        });
                    });

                    // Add event listeners to all other radio buttons to hide reason field
                    document.querySelectorAll('input[type="radio"]:not(.excused-radio)').forEach(radio => {
                        radio.addEventListener('change', function() {
                            const studentId = this.name.match(/\[(\d+)\]/)[1];
                            const reasonContainer = document.getElementById(`excused_reason_container_${studentId}`);

                            if (reasonContainer) {
                                reasonContainer.classList.add('d-none');
                            }
                        });
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    studentsContainer.innerHTML = `
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="alert alert-danger mb-0">
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
                            </div>
                        </div>
                    `;
                });
        });

        // Add event listener to date input
        dateInput.addEventListener('change', function() {
            const date = this.value;
            const sectionId = sectionSelect.value;

            // Check if attendance exists when both section and date are selected
            if (date && sectionId) {
                checkAttendanceExists();
            }
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
@endpush
@endsection
