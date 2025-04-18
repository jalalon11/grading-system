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
                                <i class="fas fa-edit text-primary me-2"></i> Edit Attendance
                            </h5>
                            <div>
                                <a href="{{ route('teacher.attendances.show', ['attendance' => $section->id, 'date' => $date]) }}" class="btn btn-outline-info me-2">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </a>
                                <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back to Records
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <x-school-day-indicator :schoolDays="$schoolDays" :currentMonth="$currentMonth" />
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-info mb-0 h-100">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <i class="fas fa-info-circle fa-2x"></i>
                                    </div>
                                    <div>
                                        <h5 class="alert-heading mb-1">Editing Attendance</h5>
                                        <p class="mb-0">Section: <strong>{{ $section->name }}</strong> | Date: <strong>{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</strong></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('teacher.attendances.update', $section->id) }}" id="editAttendanceForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="date" value="{{ $date }}">
                        <input type="hidden" name="section_id" value="{{ $section->id }}">

                        @if(count($students) > 0)
                            <div class="card">
                                <div class="card-header bg-white">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="fas fa-clipboard-list me-2"></i>
                                            Student Attendance ({{ count($students) }} students)
                                        </h6>
                                        <div>
                                            <button type="button" class="btn btn-sm btn-success me-1" id="markAllPresent">
                                                <i class="fas fa-check-circle me-1"></i> Mark All Present
                                            </button>
                                            <button type="button" class="btn btn-sm btn-warning me-1 text-dark" id="markAllLate">
                                                <i class="fas fa-clock me-1"></i> Mark All Late
                                            </button>
                                            <button type="button" class="btn btn-sm btn-info me-1 text-dark" id="markAllHalfDay">
                                                <i class="fas fa-adjust me-1"></i> Mark All Half Day
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger me-1" id="markAllAbsent">
                                                <i class="fas fa-times-circle me-1"></i> Mark All Absent
                                            </button>
                                            <button type="button" class="btn btn-sm btn-secondary" id="markAllExcused">
                                                <i class="fas fa-file-alt me-1"></i> Mark All Excused
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle mb-0 attendance-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 50px">#</th>
                                                    <th>Student Name</th>
                                                    <th>Student ID</th>
                                                    <th>Attendance Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($students as $index => $student)
                                                    <tr>
                                                        <td class="text-muted">{{ $index + 1 }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="avatar-container me-2">
                                                                    <span class="avatar rounded-circle d-flex align-items-center justify-content-center"
                                                                          style="width: 35px; height: 35px; background-color: #e0f2ff; color: #0d6efd;">
                                                                        {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                                                    </span>
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                                {{ $student->student_id }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="attendance-options d-flex">
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input status-radio"
                                                                           type="radio"
                                                                           name="attendance[{{ $student->id }}]"
                                                                           id="present_{{ $student->id }}"
                                                                           value="present"
                                                                           {{ $attendanceData[$student->id] == 'present' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="present_{{ $student->id }}">
                                                                        <span class="status-badge status-present"></span> Present
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input status-radio"
                                                                           type="radio"
                                                                           name="attendance[{{ $student->id }}]"
                                                                           id="late_{{ $student->id }}"
                                                                           value="late"
                                                                           {{ $attendanceData[$student->id] == 'late' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="late_{{ $student->id }}">
                                                                        <span class="status-badge status-late"></span> Late
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input status-radio"
                                                                           type="radio"
                                                                           name="attendance[{{ $student->id }}]"
                                                                           id="absent_{{ $student->id }}"
                                                                           value="absent"
                                                                           {{ $attendanceData[$student->id] == 'absent' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="absent_{{ $student->id }}">
                                                                        <span class="status-badge status-absent"></span> Absent
                                                                    </label>
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input status-radio excused-radio"
                                                                           type="radio"
                                                                           name="attendance[{{ $student->id }}]"
                                                                           id="excused_{{ $student->id }}"
                                                                           value="excused"
                                                                           data-student-id="{{ $student->id }}"
                                                                           {{ $attendanceData[$student->id] == 'excused' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="excused_{{ $student->id }}">
                                                                        <span class="status-badge status-excused"></span> Excused
                                                                    </label>
                                                                </div>
                                                                <div class="excused-reason-container mt-2 {{ $attendanceData[$student->id] == 'excused' ? '' : 'd-none' }}" id="excused_reason_container_{{ $student->id }}">
                                                                    <input type="text" class="form-control form-control-sm"
                                                                           name="remarks[{{ $student->id }}]"
                                                                           id="remarks_{{ $student->id }}"
                                                                           placeholder="Enter reason for excuse"
                                                                           value="{{ $attendanceRemarks[$student->id] ?? '' }}">
                                                                </div>
                                                                <div class="form-check form-check-inline">
                                                                    <input class="form-check-input status-radio"
                                                                           type="radio"
                                                                           name="attendance[{{ $student->id }}]"
                                                                           id="half_day_{{ $student->id }}"
                                                                           value="half_day"
                                                                           {{ $attendanceData[$student->id] == 'half_day' ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="half_day_{{ $student->id }}">
                                                                        <span class="status-badge status-half_day"></span> Half Day
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
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
                        @else
                            <div class="alert alert-warning">
                                <div class="d-flex">
                                    <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                                    <div>
                                        <h6 class="mb-1">No Students Found</h6>
                                        <p class="mb-0">There are no students assigned to this section.</p>
                                        <a href="{{ route('teacher.students.create') }}" class="btn btn-sm btn-primary mt-2">
                                            <i class="fas fa-plus-circle me-1"></i> Add Students
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-save me-1"></i> Update Attendance
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

    .form-check-input[type="radio"]:checked[value="present"] {
        background-color: #28a745;
        border-color: #28a745;
    }

    .form-check-input[type="radio"]:checked[value="late"] {
        background-color: #ffc107;
        border-color: #ffc107;
    }

    .form-check-input[type="radio"]:checked[value="absent"] {
        background-color: #dc3545;
        border-color: #dc3545;
    }

    .form-check-input[type="radio"]:checked[value="excused"] {
        background-color: #6c757d;
        border-color: #6c757d;
    }

    .form-check-input[type="radio"]:checked[value="half_day"] {
        background-color: #17a2b8;
        border-color: #17a2b8;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Mark all present
        document.getElementById('markAllPresent').addEventListener('click', function() {
            document.querySelectorAll('input[value="present"]').forEach(radio => {
                radio.checked = true;
            });
            // Hide all excused reason fields
            document.querySelectorAll('.excused-reason-container').forEach(container => {
                container.classList.add('d-none');
            });
            updateAttendanceSummary();
        });

        // Mark all late
        document.getElementById('markAllLate').addEventListener('click', function() {
            document.querySelectorAll('input[value="late"]').forEach(radio => {
                radio.checked = true;
            });
            // Hide all excused reason fields
            document.querySelectorAll('.excused-reason-container').forEach(container => {
                container.classList.add('d-none');
            });
            updateAttendanceSummary();
        });

        // Mark all half day
        document.getElementById('markAllHalfDay').addEventListener('click', function() {
            document.querySelectorAll('input[value="half_day"]').forEach(radio => {
                radio.checked = true;
            });
            // Hide all excused reason fields
            document.querySelectorAll('.excused-reason-container').forEach(container => {
                container.classList.add('d-none');
            });
            updateAttendanceSummary();
        });

        // Mark all absent
        document.getElementById('markAllAbsent').addEventListener('click', function() {
            document.querySelectorAll('input[value="absent"]').forEach(radio => {
                radio.checked = true;
            });
            // Hide all excused reason fields
            document.querySelectorAll('.excused-reason-container').forEach(container => {
                container.classList.add('d-none');
            });
            updateAttendanceSummary();
        });

        // Mark all excused
        document.getElementById('markAllExcused').addEventListener('click', function() {
            document.querySelectorAll('input[value="excused"]').forEach(radio => {
                radio.checked = true;
            });
            // Show all excused reason fields
            document.querySelectorAll('.excused-reason-container').forEach(container => {
                container.classList.remove('d-none');
            });
            updateAttendanceSummary();
        });

        // Track changes to attendance status
        document.querySelectorAll('.status-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                updateAttendanceSummary();

                // Handle excused reason field visibility
                if (radio.classList.contains('excused-radio') && radio.checked) {
                    const studentId = radio.getAttribute('data-student-id');
                    const reasonContainer = document.getElementById(`excused_reason_container_${studentId}`);
                    reasonContainer.classList.remove('d-none');
                } else if (!radio.classList.contains('excused-radio') && radio.checked) {
                    const studentId = radio.name.match(/\[(\d+)\]/)[1];
                    const reasonContainer = document.getElementById(`excused_reason_container_${studentId}`);
                    if (reasonContainer) {
                        reasonContainer.classList.add('d-none');
                    }
                }
            });
        });

        // Update attendance summary when page loads
        updateAttendanceSummary();

        // Function to update attendance summary
        function updateAttendanceSummary() {
            let present = document.querySelectorAll('input[value="present"]:checked').length;
            let late = document.querySelectorAll('input[value="late"]:checked').length;
            let absent = document.querySelectorAll('input[value="absent"]:checked').length;
            let excused = document.querySelectorAll('input[value="excused"]:checked').length;
            let halfDay = document.querySelectorAll('input[value="half_day"]:checked').length;
            let total = document.querySelectorAll('.status-radio[name^="attendance"]').length / 5; // Updated for 5 options

            // You can add code here to display a summary if you want
            console.log(`Present: ${present}, Late: ${late}, Absent: ${absent}, Excused: ${excused}, Half Day: ${halfDay}, Total: ${total}`);
        }
    });
</script>
@endpush
@endsection