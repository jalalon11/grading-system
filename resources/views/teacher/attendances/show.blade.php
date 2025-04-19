@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-0">
                    <div class="px-4 py-3 border-bottom">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="fas fa-clipboard-check text-primary"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">Attendance Details</h5>
                            </div>
                            <div class="d-flex flex-wrap gap-2 w-100 w-md-auto justify-content-between justify-content-md-end">
                                <a href="{{ route('teacher.attendances.edit', ['attendance' => $section->id, 'date' => $date]) }}" class="btn btn-primary">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="attendance-header mb-4">
                        <div class="alert alert-info mb-3">
                            <div class="d-flex flex-column flex-md-row align-items-center gap-3">
                                <div class="attendance-date-badge d-flex align-items-center justify-content-center rounded-circle bg-info bg-opacity-10 text-info">
                                    <i class="fas fa-calendar-day fa-2x"></i>
                                </div>
                                <div class="text-center text-md-start">
                                    <h5 class="alert-heading fw-bold mb-1">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h5>
                                    <div class="d-flex flex-wrap justify-content-center justify-content-md-start gap-2 align-items-center">
                                        <span class="badge bg-primary">{{ $section->name }}</span>
                                        <span class="badge bg-secondary">{{ count($students) }} Students</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-md-block d-none">
                            <x-school-day-indicator :schoolDays="$schoolDays" :currentMonth="$currentMonth" />
                        </div>
                    </div>

                    <div class="row mb-4 g-3">
                        <div class="col-lg-7">
                            <div class="card h-100 mobile-card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center text-md-start"><i class="fas fa-chart-pie me-2"></i> Attendance Summary</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="attendance-chart mb-3">
                                                <canvas id="attendanceChart" width="100%" height="220"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="attendance-stats">
                                                <div class="attendance-stat-item mb-3 p-3 rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Present</span>
                                                        <span class="badge bg-success rounded-pill">{{ $presentCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: {{ count($students) > 0 ? ($presentCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>

                                                <div class="attendance-stat-item mb-3 p-3 rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Late</span>
                                                        <span class="badge bg-warning text-dark rounded-pill">{{ $lateCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-warning" style="width: {{ count($students) > 0 ? ($lateCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>

                                                <div class="attendance-stat-item mb-3 p-3 rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Half Day</span>
                                                        <span class="badge bg-info text-dark rounded-pill">{{ $halfDayCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-info" style="width: {{ count($students) > 0 ? ($halfDayCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>

                                                <div class="attendance-stat-item p-3 rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Absent</span>
                                                        <span class="badge bg-danger rounded-pill">{{ $absentCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-danger" style="width: {{ count($students) > 0 ? ($absentCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>

                                                <div class="attendance-stat-item p-3 rounded bg-light mt-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Excused</span>
                                                        <span class="badge bg-secondary rounded-pill">{{ $excusedCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-secondary" style="width: {{ count($students) > 0 ? ($excusedCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <div class="card h-100 mobile-card">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0 text-center text-md-start"><i class="fas fa-info-circle me-2"></i> Attendance Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label text-muted small">SECTION</div>
                                            <div class="detail-value fw-medium">{{ $section->name }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label text-muted small">DATE</div>
                                            <div class="detail-value fw-medium">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label text-muted small">TOTAL STUDENTS</div>
                                            <div class="detail-value fw-medium">{{ count($students) }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label text-muted small">RECORDED BY</div>
                                            <div class="detail-value fw-medium">{{ Auth::user()->name }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="detail-label text-muted small">ATTENDANCE RATE</div>
                                            <div class="d-flex align-items-center">
                                                <div class="attendance-rate-display me-3">
                                                    <h2 class="mb-0 text-primary">
                                                        @if(count($students) > 0)
                                                            {{ round((($presentCount + $lateCount + ($halfDayCount * 0.5)) / count($students)) * 100, 1) }}%
                                                        @else
                                                            N/A
                                                        @endif
                                                    </h2>
                                                </div>
                                                <div class="attendance-rate-progress flex-grow-1">
                                                    <div class="progress" style="height: 10px;">
                                                        @if(count($students) > 0)
                                                            <div class="progress-bar bg-success" role="progressbar"
                                                                 style="width: {{ ($presentCount / count($students) * 100) }}%"></div>
                                                            <div class="progress-bar bg-warning" role="progressbar"
                                                                 style="width: {{ ($lateCount / count($students) * 100) }}%"></div>
                                                            <div class="progress-bar bg-info" role="progressbar"
                                                                 style="width: {{ ($halfDayCount / count($students) * 100) }}%"></div>
                                                        @else
                                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                                        @endif
                                                    </div>
                                                    <div class="mt-1 d-flex justify-content-between small text-muted">
                                                        <span>0%</span>
                                                        <span>50%</span>
                                                        <span>100%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card mobile-card">
                        <div class="card-header bg-white">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2">
                                <h6 class="mb-0 text-center text-md-start w-100">
                                    <i class="fas fa-users me-2"></i> Student Attendance Details
                                </h6>
                                <div class="attendance-legend d-flex flex-wrap justify-content-center gap-2 w-100 w-md-auto">
                                    <div class="legend-item"><span class="badge-dot bg-success me-1"></span> Present: {{ $presentCount }}</div>
                                    <div class="legend-item"><span class="badge-dot bg-warning me-1"></span> Late: {{ $lateCount }}</div>
                                    <div class="legend-item"><span class="badge-dot bg-info me-1"></span> Half: {{ $halfDayCount }}</div>
                                    <div class="legend-item"><span class="badge-dot bg-danger me-1"></span> Absent: {{ $absentCount }}</div>
                                    <div class="legend-item"><span class="badge-dot bg-secondary me-1"></span> Excused: {{ $excusedCount }}</div>
                                </div>
                            </div>
                        </div>

                        @if(count($students) > 0)
                            <div class="attendance-table-wrapper">
                                <div class="table-responsive attendance-table-container">
                                    <table class="table table-hover align-middle mb-0 attendance-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 40px" class="d-none d-md-table-cell">#</th>
                                                <th style="width: 60%">Student Name</th>
                                                <th class="d-none d-md-table-cell">Student ID</th>
                                                <th style="width: 40%" class="text-center">Status</th>
                                            </tr>
                                        </thead>
                                    <tbody>
                                        @foreach($students as $index => $student)
                                            <tr>
                                                <td class="text-muted d-none d-md-table-cell">{{ $index + 1 }}</td>
                                                <td class="student-name-cell">
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-container me-2">
                                                            <span class="avatar rounded-circle d-flex align-items-center justify-content-center"
                                                                  style="width: 35px; height: 35px; background-color: #e0f2ff; color: #0d6efd;">
                                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div class="student-info">
                                                            <div class="fw-medium text-truncate">{{ $student->surname_first }}</div>
                                                            <div class="small text-muted d-md-none">{{ $student->student_id }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="d-none d-md-table-cell">
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        {{ $student->student_id }}
                                                    </span>
                                                </td>
                                                <td class="status-cell text-center">
                                                    @if($attendanceData[$student->id] == 'present')
                                                        <span class="badge bg-success status-badge">
                                                            <i class="fas fa-check-circle me-1"></i> Present
                                                        </span>
                                                    @elseif($attendanceData[$student->id] == 'late')
                                                        <span class="badge bg-warning text-dark status-badge">
                                                            <i class="fas fa-clock me-1"></i> Late
                                                        </span>
                                                    @elseif($attendanceData[$student->id] == 'half_day')
                                                        <span class="badge bg-info text-dark status-badge">
                                                            <i class="fas fa-adjust me-1"></i> Half Day
                                                        </span>
                                                    @elseif($attendanceData[$student->id] == 'excused')
                                                        <div class="excused-status">
                                                            <span class="badge bg-secondary mb-1 status-badge">
                                                                <i class="fas fa-file-alt me-1"></i> Excused
                                                            </span>
                                                            @if(!empty($attendanceRemarks[$student->id]))
                                                                <div class="excused-reason p-2 rounded border border-secondary border-opacity-25 bg-light">
                                                                    <small class="d-block text-secondary"><i class="fas fa-comment-alt me-1"></i> <strong>Reason:</strong></small>
                                                                    <div class="mt-1 small">{{ $attendanceRemarks[$student->id] }}</div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="badge bg-danger status-badge">
                                                            <i class="fas fa-times-circle me-1"></i> Absent
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="card-body py-5">
                                <div class="text-center text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>No students found in this section.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }

    .legend-item {
        font-size: 0.85rem;
        white-space: nowrap;
    }

    .detail-label {
        margin-bottom: 2px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 1rem;
    }

    /* Attendance header styles */
    .attendance-date-badge {
        width: 60px;
        height: 60px;
        flex-shrink: 0;
    }

    /* Card styles */
    .mobile-card {
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    /* Table styles */
    .attendance-table-wrapper {
        position: relative;
        max-height: 500px;
        overflow-y: auto;
    }

    .attendance-table-container {
        overflow-x: auto;
    }

    .attendance-table thead {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #f8f9fa;
    }

    .attendance-table-wrapper::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .attendance-table-wrapper::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .attendance-table-wrapper::-webkit-scrollbar-thumb {
        background: #c1c1c1;
        border-radius: 4px;
    }

    .attendance-table-wrapper::-webkit-scrollbar-thumb:hover {
        background: #a8a8a8;
    }

    /* Excused reason styles */
    .excused-status {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: left;
        width: 100%;
    }

    .excused-reason {
        width: 100%;
        max-width: 250px;
        font-size: 0.85rem;
        color: #495057;
    }

    /* Mobile-friendly styles */
    @media (max-width: 767.98px) {
        .card-header .btn {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }

        .attendance-stats .attendance-stat-item {
            margin-bottom: 0.75rem !important;
            padding: 0.75rem !important;
        }

        .detail-value {
            font-size: 0.9rem;
        }

        .attendance-table-wrapper {
            max-height: 400px;
            border: 1px solid rgba(0,0,0,0.1);
            border-radius: 0.5rem;
            margin: 0 -1rem;
            width: calc(100% + 2rem);
        }

        .table-responsive {
            margin: 0;
            width: 100%;
        }

        .table th, .table td {
            padding: 0.5rem;
            vertical-align: middle;
        }

        .attendance-table td {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .student-name-cell {
            max-width: 60%;
            width: 60%;
        }

        .student-info {
            max-width: calc(100% - 40px);
            width: calc(100% - 40px);
        }

        .status-cell {
            max-width: 40%;
            width: 40%;
        }

        .attendance-table .badge {
            padding: 0.5rem 0.75rem;
            font-size: 0.8rem;
            display: inline-block;
            width: 100%;
            max-width: 120px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .status-badge {
            width: 100%;
            display: inline-block;
        }

        .excused-reason {
            max-width: 100%;
            margin-top: 0.5rem;
            font-size: 0.75rem;
        }

        .excused-status {
            width: 100%;
        }

        .avatar {
            width: 30px !important;
            height: 30px !important;
            font-size: 0.75rem;
        }

        .attendance-legend {
            background-color: #f8f9fa;
            padding: 0.5rem;
            border-radius: 0.25rem;
            margin-top: 0.5rem;
        }

        .legend-item {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
            background-color: white;
            border-radius: 0.25rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create attendance pie chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Late', 'Half Day', 'Absent', 'Excused'],
                datasets: [{
                    data: [{{ $presentCount }}, {{ $lateCount }}, {{ $halfDayCount }}, {{ $absentCount }}, {{ $excusedCount }}],
                    backgroundColor: [
                        '#28a745',  // Present - green
                        '#ffc107',  // Late - yellow
                        '#17a2b8',  // Half Day - info/blue
                        '#dc3545',  // Absent - red
                        '#6c757d'   // Excused - gray
                    ],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection