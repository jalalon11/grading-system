@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white p-0">
                    <div class="px-4 py-3 border-bottom">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                            <h5 class="mb-0">
                                <i class="fas fa-calendar-alt text-primary me-2"></i> Monthly Attendance
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('teacher.attendances.weekly-summary') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-calendar-week me-1"></i> Weekly View
                                </a>
                                <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <!-- Professional Filter Section (Always Visible) -->
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center mb-3">
                                <span class="badge bg-primary p-2 me-2">
                                    <i class="fas fa-filter"></i>
                                </span>
                                <h6 class="mb-0">Attendance Filters</h6>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="d-flex align-items-center bg-light p-2 rounded">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $summary['month_name'] ?? 'Current Month' }}</h6>
                                            <div class="small text-muted">{{ $schoolDays }} school days</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('teacher.attendances.monthly-summary') }}" method="GET" class="row g-3">
                                <!-- Section Selection -->
                                <div class="col-md-5">
                                    <label for="section_id" class="form-label">Section</label>
                                    <select class="form-select" id="section_id" name="section_id">
                                        <option value="">All Sections</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }} (Grade {{ $section->grade_level }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Month Selection -->
                                <div class="col-md-5">
                                    <label for="month" class="form-label">Month</label>
                                    <select class="form-select" id="month" name="month">
                                        @foreach($availableMonths as $month)
                                            <option value="{{ $month->month_value }}" {{ $yearMonth == $month->month_value ? 'selected' : '' }}>
                                                {{ $month->month_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Action Button -->
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-filter me-1"></i> Apply
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>



                    @if(count($summary['dates']) > 0)
                        <!-- Monthly Overview Card - Simplified -->
                        <div class="row g-3 mb-4">
                            <!-- Attendance Rate -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-primary p-2 me-2">
                                                <i class="fas fa-percentage"></i>
                                            </span>
                                            <h6 class="mb-0">Attendance Rate</h6>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <h2 class="mb-0 me-2 text-primary">{{ $summary['total_stats']['attendance_rate'] }}%</h2>
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ $summary['total_stats']['attendance_rate'] }}%"
                                                    aria-valuenow="{{ $summary['total_stats']['attendance_rate'] }}"
                                                    aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-muted mt-2 mb-0 small">
                                            {{ $summary['total_stats']['total_students'] }} students over {{ $summary['total_stats']['total_days'] }} days
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Key Attendance Stats -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center mb-3">
                                            <span class="badge bg-success p-2 me-2">
                                                <i class="fas fa-chart-pie"></i>
                                            </span>
                                            <h6 class="mb-0">Attendance Summary</h6>
                                        </div>
                                        <div class="row g-2">
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <span class="badge bg-success rounded-circle p-2">
                                                            <i class="fas fa-check"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="small text-muted">Present</div>
                                                        <div class="fw-bold">{{ $summary['total_stats']['present'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <span class="badge bg-danger rounded-circle p-2">
                                                            <i class="fas fa-times"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="small text-muted">Absent</div>
                                                        <div class="fw-bold">{{ $summary['total_stats']['absent'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <span class="badge bg-warning rounded-circle p-2">
                                                            <i class="fas fa-clock"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="small text-muted">Late</div>
                                                        <div class="fw-bold">{{ $summary['total_stats']['late'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <span class="badge bg-info rounded-circle p-2">
                                                            <i class="fas fa-adjust"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="small text-muted">Half Day</div>
                                                        <div class="fw-bold">{{ $summary['total_stats']['half_day'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <span class="badge bg-secondary rounded-circle p-2">
                                                            <i class="fas fa-file-medical"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="small text-muted">Excused</div>
                                                        <div class="fw-bold">{{ $summary['total_stats']['excused'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <span class="badge bg-primary rounded-circle p-2">
                                                            <i class="fas fa-calendar-check"></i>
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="small text-muted">School Days</div>
                                                        <div class="fw-bold">{{ $summary['total_stats']['total_days'] }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weekly Breakdown - Simplified -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-warning p-2 me-2">
                                        <i class="fas fa-calendar-week"></i>
                                    </span>
                                    <h6 class="mb-0">Weekly Breakdown</h6>
                                </div>

                                <div>
                                    <div class="list-group">
                                        @foreach($summary['weekly_stats'] as $weekNumber => $weekStats)
                                            @if($weekStats['attendance_days'] > 0)
                                                <div class="list-group-item p-3">
                                                    <div class="row align-items-center">
                                                        <div class="col-md-4">
                                                            <h6 class="mb-1">Week {{ $weekNumber }}</h6>
                                                            <small class="text-muted">{{ $weekStats['start_date'] }} - {{ $weekStats['end_date'] }}</small>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                                <span class="badge bg-success">P: {{ $weekStats['present'] }}</span>
                                                                <span class="badge bg-danger">A: {{ $weekStats['absent'] }}</span>
                                                                <span class="badge bg-warning">L: {{ $weekStats['late'] }}</span>
                                                                <span class="badge bg-info">H: {{ $weekStats['half_day'] }}</span>
                                                                <span class="badge bg-secondary">E: {{ $weekStats['excused'] }}</span>
                                                                <span class="badge bg-primary">Days: {{ $weekStats['attendance_days'] }}</span>
                                                            </div>
                                                            <div class="progress" style="height: 6px;">
                                                                <div class="progress-bar bg-primary" role="progressbar"
                                                                    style="width: {{ $weekStats['attendance_rate'] }}%"
                                                                    aria-valuenow="{{ $weekStats['attendance_rate'] }}"
                                                                    aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-3 text-md-end mt-2 mt-md-0">
                                                            <span class="fw-bold">{{ $weekStats['attendance_rate'] }}%</span>
                                                            <div class="small text-muted">attendance rate</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Attendance Chart - Simplified -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-3">
                                <div class="mb-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info p-2 me-2">
                                            <i class="fas fa-chart-line"></i>
                                        </span>
                                        <h6 class="mb-0">Daily Attendance</h6>
                                    </div>
                                </div>
                                <div>
                                    <div style="height: 250px;">
                                        <canvas id="monthlyAttendanceChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Attendance Summary - Simplified -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-success p-2 me-2">
                                        <i class="fas fa-user-check"></i>
                                    </span>
                                    <h6 class="mb-0">Student Summary</h6>
                                </div>

                                <div>
                                    <div class="list-group">
                                        @foreach($summary['students'] as $student)
                                            <div class="list-group-item p-3">
                                                <div class="row align-items-center">
                                                    <div class="col-md-4">
                                                        <h6 class="mb-1">{{ $student['last_name'] }}, {{ $student['first_name'] }}{{ $student['middle_name'] ? ' ' . substr($student['middle_name'], 0, 1) . '.' : '' }}</h6>
                                                        <small class="text-muted">{{ $student['section'] }}</small>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex flex-wrap gap-2 mb-2">
                                                            <span class="badge bg-success">Present: {{ $student['stats']['present'] }}</span>
                                                            <span class="badge bg-danger">Absent: {{ $student['stats']['absent'] }}</span>
                                                            <span class="badge bg-warning">Late: {{ $student['stats']['late'] }}</span>
                                                            <span class="badge bg-info">Half: {{ $student['stats']['half_day'] }}</span>
                                                            <span class="badge bg-secondary">Excused: {{ $student['stats']['excused'] }}</span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: {{ $student['stats']['attendance_rate'] }}%"
                                                                aria-valuenow="{{ $student['stats']['attendance_rate'] }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3 text-md-end mt-2 mt-md-0">
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <span class="badge bg-primary rounded-pill">{{ $student['stats']['attendance_ratio'] }}</span>
                                                            <span class="ms-2 fw-bold">{{ $student['stats']['attendance_rate'] }}%</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No attendance records found for this month. Please create attendance records first.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if(count($summary['dates']) > 0)
        // Simple attendance chart
        const ctx = document.getElementById('monthlyAttendanceChart').getContext('2d');

        // Prepare data for chart
        const dates = @json(array_values($summary['dates']));
        const dailyData = @json($summary['daily_stats']);
        const dateKeys = @json(array_keys($summary['dates']));

        const presentData = [];
        const absentData = [];
        const halfDayData = [];
        const lateData = [];
        const excusedData = [];

        dateKeys.forEach(date => {
            presentData.push(dailyData[date]['present']);
            absentData.push(dailyData[date]['absent']);
            halfDayData.push(dailyData[date]['half_day']);
            lateData.push(dailyData[date]['late']);
            excusedData.push(dailyData[date]['excused']);
        });

        // Create a simple chart
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Present',
                        data: presentData,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: 'Absent',
                        data: absentData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: 'Half Day',
                        data: halfDayData,
                        borderColor: '#17a2b8',
                        backgroundColor: 'rgba(23, 162, 184, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: 'Late',
                        data: lateData,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    },
                    {
                        label: 'Excused',
                        data: excusedData,
                        borderColor: '#6c757d',
                        backgroundColor: 'rgba(108, 117, 125, 0.1)',
                        borderWidth: 2,
                        tension: 0.1,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            boxWidth: 15,
                            padding: 10,
                            font: {
                                size: 11
                            }
                        }
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: {
                                size: 10
                            }
                        }
                    },
                    x: {
                        ticks: {
                            maxRotation: 45,
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
        @endif
    });
</script>
@endpush
