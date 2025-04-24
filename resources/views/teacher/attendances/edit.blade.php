@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-0">
                    <div class="px-4 py-3 border-bottom">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <h5 class="mb-0">
                                <i class="fas fa-edit text-primary me-2"></i> Edit Attendance
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('teacher.attendances.show', ['attendance' => $section->id, 'date' => $date]) }}" class="btn btn-outline-info">
                                    <i class="fas fa-eye me-1"></i> View Details
                                </a>
                                <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back
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

                    <div class="row mb-4 g-3">
                        <div class="col-md-4">
                            <x-school-day-indicator :schoolDays="$schoolDays" :currentMonth="$currentMonth" />
                        </div>
                        <div class="col-md-8">
                            <div class="alert alert-info mb-0 h-100">
                                <div class="d-flex flex-column flex-sm-row align-items-center gap-3">
                                    <div class="icon-box bg-info bg-opacity-10 rounded-circle p-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                        <i class="fas fa-info-circle text-info fa-lg"></i>
                                    </div>
                                    <div class="text-center text-sm-start w-100">
                                        <h5 class="alert-heading mb-2 fw-bold">Editing Attendance</h5>
                                        <div class="attendance-info-container">
                                            <div class="attendance-info-item mb-2 mb-sm-0">
                                                <span class="attendance-info-badge bg-primary text-white">
                                                <i class="fas fa-users"></i>
                                                </span>
                                                <span class="ms-2 fw-medium">{{ $section->name }}</span>
                                            </div>
                                            <div class="attendance-info-item">
                                                <span class="attendance-info-badge bg-success text-white">
                                                    <i class="fas fa-calendar-day"></i>
                                                </span>
                                                <span class="ms-2 fw-medium">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</span>
                                            </div>
                                        </div>
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
                                    <div class="d-flex flex-column gap-3">
                                        <h6 class="mb-0">
                                            <i class="fas fa-clipboard-list me-2"></i>
                                            Student Attendance ({{ count($students) }} students)
                                        </h6>
                                        <div class="mark-all-buttons-container">
                                            <!-- Desktop View Buttons -->
                                            <div class="d-none d-md-block">
                                                <div class="btn-group btn-group-sm">
                                                    <button type="button" class="btn btn-success" id="markAllPresent">
                                                        <i class="fas fa-check-circle me-1"></i> Present
                                                    </button>
                                                    <button type="button" class="btn btn-warning text-dark" id="markAllLate">
                                                        <i class="fas fa-clock me-1"></i> Late
                                                    </button>
                                                    <button type="button" class="btn btn-info text-dark" id="markAllHalfDay">
                                                        <i class="fas fa-adjust me-1"></i> Half Day
                                                    </button>
                                                    <button type="button" class="btn btn-danger" id="markAllAbsent">
                                                        <i class="fas fa-times-circle me-1"></i> Absent
                                                    </button>
                                                    <button type="button" class="btn btn-secondary" id="markAllExcused">
                                                        <i class="fas fa-file-alt me-1"></i> Excused
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Mobile View Buttons -->
                                            <div class="d-md-none">
                                                <div class="mark-all-mobile-header mb-2 text-center">
                                                    <span class="small text-muted">Mark all students as:</span>
                                                </div>
                                                <div class="mark-all-mobile-buttons">
                                                    <div class="row g-2">
                                                        <div class="col-4">
                                                            <button type="button" class="btn btn-sm btn-outline-light text-success w-100 mark-all-mobile-btn" id="markAllPresentMobile">
                                                                <div class="mark-all-btn-dot bg-success mb-1"></div>
                                                                Present
                                                            </button>
                                                        </div>
                                                        <div class="col-4">
                                                            <button type="button" class="btn btn-sm btn-outline-light text-warning w-100 mark-all-mobile-btn" id="markAllLateMobile">
                                                                <div class="mark-all-btn-dot bg-warning mb-1"></div>
                                                                Late
                                                            </button>
                                                        </div>
                                                        <div class="col-4">
                                                            <button type="button" class="btn btn-sm btn-outline-light text-info w-100 mark-all-mobile-btn" id="markAllHalfDayMobile">
                                                                <div class="mark-all-btn-dot bg-info mb-1"></div>
                                                                Half Day
                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button" class="btn btn-sm btn-outline-light text-danger w-100 mark-all-mobile-btn" id="markAllAbsentMobile">
                                                                <div class="mark-all-btn-dot bg-danger mb-1"></div>
                                                                Absent
                                                            </button>
                                                        </div>
                                                        <div class="col-6">
                                                            <button type="button" class="btn btn-sm btn-outline-light text-secondary w-100 mark-all-mobile-btn" id="markAllExcusedMobile">
                                                                <div class="mark-all-btn-dot bg-secondary mb-1"></div>
                                                                Excused
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive attendance-table-container">
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
                                                                    <div class="fw-medium">
                                                                        {{ $student->surname_first }}
                                                                    </div>
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
                                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start align-items-center text-muted small gap-3">
                                        <div>
                                            <span class="status-badge status-present"></span> Present
                                        </div>
                                        <div>
                                            <span class="status-badge status-late"></span> Late
                                        </div>
                                        <div>
                                            <span class="status-badge status-absent"></span> Absent
                                        </div>
                                        <div>
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

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3 mt-4">
                            <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary order-2 order-sm-1">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg order-1 order-sm-2">
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

    /* Attendance info styles */
    .attendance-info-container {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .attendance-info-item {
        display: flex;
        align-items: center;
    }

    .attendance-info-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-size: 0.8rem;
    }

    @media (min-width: 576px) {
        .attendance-info-container {
            flex-direction: row;
            gap: 1.5rem;
        }
    }

    /* Table scrolling improvements */
    .attendance-table-container {
        position: relative;
        max-height: 70vh;
        overflow-y: auto;
        scrollbar-width: thin;
    }

    .attendance-table-container::-webkit-scrollbar {
        width: 6px;
    }

    .attendance-table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .attendance-table-container::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 3px;
    }

    .attendance-table-container::-webkit-scrollbar-thumb:hover {
        background: #999;
    }

    .attendance-table thead th {
        position: sticky;
        top: 0;
        z-index: 10;
        background-color: #f8f9fa;
        box-shadow: 0 1px 0 rgba(0,0,0,0.1);
    }



    /* No visual highlighting for current row */

    /* Mobile-friendly styles */
    @media (max-width: 767.98px) {
        .mark-all-mobile-btn {
            border: 1px solid rgba(0,0,0,0.1);
            background-color: #f8f9fa;
            padding: 0.5rem 0.25rem;
            font-size: 0.8rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            min-height: 60px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .mark-all-mobile-btn:hover, .mark-all-mobile-btn:active {
            transform: translateY(-2px);
            box-shadow: 0 3px 5px rgba(0,0,0,0.1);
        }

        .mark-all-btn-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            display: inline-block;
        }

        .card-header h6 {
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .attendance-options {
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem;
        }

        .form-check-inline {
            margin-right: 0;
        }

        .excused-reason-container {
            width: 100%;
            margin-top: 0.5rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to mark all with a specific status
        function markAllWithStatus(status) {
            document.querySelectorAll(`input[value="${status}"]`).forEach(radio => {
                radio.checked = true;
            });

            // Handle excused reason fields visibility
            if (status === 'excused') {
                document.querySelectorAll('.excused-reason-container, .mobile-excused-reason-container').forEach(container => {
                    container.classList.remove('d-none');
                });
            } else {
                document.querySelectorAll('.excused-reason-container, .mobile-excused-reason-container').forEach(container => {
                    container.classList.add('d-none');
                });
            }

            updateAttendanceSummary();
        }

        // Mark all present - Desktop
        document.getElementById('markAllPresent').addEventListener('click', function() {
            markAllWithStatus('present');
        });

        // Mark all present - Mobile
        document.getElementById('markAllPresentMobile').addEventListener('click', function() {
            markAllWithStatus('present');
        });

        // Mark all late - Desktop
        document.getElementById('markAllLate').addEventListener('click', function() {
            markAllWithStatus('late');
        });

        // Mark all late - Mobile
        document.getElementById('markAllLateMobile').addEventListener('click', function() {
            markAllWithStatus('late');
        });

        // Mark all half day - Desktop
        document.getElementById('markAllHalfDay').addEventListener('click', function() {
            markAllWithStatus('half_day');
        });

        // Mark all half day - Mobile
        document.getElementById('markAllHalfDayMobile').addEventListener('click', function() {
            markAllWithStatus('half_day');
        });

        // Mark all absent - Desktop
        document.getElementById('markAllAbsent').addEventListener('click', function() {
            markAllWithStatus('absent');
        });

        // Mark all absent - Mobile
        document.getElementById('markAllAbsentMobile').addEventListener('click', function() {
            markAllWithStatus('absent');
        });

        // Mark all excused - Desktop
        document.getElementById('markAllExcused').addEventListener('click', function() {
            markAllWithStatus('excused');
        });

        // Mark all excused - Mobile
        document.getElementById('markAllExcusedMobile').addEventListener('click', function() {
            markAllWithStatus('excused');
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

        // Table is now pure scrollable with no additional navigation

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