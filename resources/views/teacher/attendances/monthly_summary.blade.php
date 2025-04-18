@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-alt text-white me-2"></i> Monthly Attendance Summary
                        </h5>
                        <div>
                            <a href="{{ route('teacher.attendances.weekly-summary') }}" class="btn btn-light me-2">
                                <i class="fas fa-calendar-week me-1"></i> Weekly View
                            </a>
                            <a href="{{ route('teacher.attendances.index') }}" class="btn btn-light">
                                <i class="fas fa-list me-1"></i> All Records
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <!-- Filter Form -->
                    <div class="mb-4">
                        <form action="{{ route('teacher.attendances.monthly-summary') }}" method="GET" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="section_id" class="form-label fw-medium">Section</label>
                                <select class="form-select" id="section_id" name="section_id">
                                    <option value="">All Sections</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }} (Grade {{ $section->grade_level }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="month" class="form-label fw-medium">Month</label>
                                <select class="form-select" id="month" name="month">
                                    @foreach($availableMonths as $month)
                                        <option value="{{ $month->month_value }}" {{ $yearMonth == $month->month_value ? 'selected' : '' }}>
                                            {{ $month->month_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-secondary me-2">
                                    <i class="fas fa-filter me-1"></i> Apply Filters
                                </button>
                                <a href="{{ route('teacher.attendances.monthly-summary') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-redo me-1"></i> Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- School Day Indicator -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <x-school-day-indicator :schoolDays="$schoolDays" :currentMonth="$currentMonth" />
                        </div>
                    </div>

                    @if(count($summary['dates']) > 0)
                        <!-- Monthly Overview Card -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-bar text-primary me-2"></i>
                                    Monthly Overview: {{ $summary['month_name'] }}
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Attendance Stats -->
                                    <div class="col-md-8">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="card-body p-3 text-center">
                                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                                            <span class="badge bg-success rounded-circle p-2 me-2">
                                                                <i class="fas fa-check"></i>
                                                            </span>
                                                            <h6 class="mb-0">Present</h6>
                                                        </div>
                                                        <h3 class="mb-0">{{ $summary['total_stats']['present'] }}</h3>
                                                        <small class="text-muted">students</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="card-body p-3 text-center">
                                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                                            <span class="badge bg-warning rounded-circle p-2 me-2">
                                                                <i class="fas fa-clock"></i>
                                                            </span>
                                                            <h6 class="mb-0">Late</h6>
                                                        </div>
                                                        <h3 class="mb-0">{{ $summary['total_stats']['late'] }}</h3>
                                                        <small class="text-muted">students</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="card-body p-3 text-center">
                                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                                            <span class="badge bg-info rounded-circle p-2 me-2">
                                                                <i class="fas fa-adjust"></i>
                                                            </span>
                                                            <h6 class="mb-0">Half Day</h6>
                                                        </div>
                                                        <h3 class="mb-0">{{ $summary['total_stats']['half_day'] }}</h3>
                                                        <small class="text-muted">students</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="card-body p-3 text-center">
                                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                                            <span class="badge bg-danger rounded-circle p-2 me-2">
                                                                <i class="fas fa-times"></i>
                                                            </span>
                                                            <h6 class="mb-0">Absent</h6>
                                                        </div>
                                                        <h3 class="mb-0">{{ $summary['total_stats']['absent'] }}</h3>
                                                        <small class="text-muted">students</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="card-body p-3 text-center">
                                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                                            <span class="badge bg-secondary rounded-circle p-2 me-2">
                                                                <i class="fas fa-file-medical"></i>
                                                            </span>
                                                            <h6 class="mb-0">Excused</h6>
                                                        </div>
                                                        <h3 class="mb-0">{{ $summary['total_stats']['excused'] }}</h3>
                                                        <small class="text-muted">students</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="card-body p-3 text-center">
                                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                                            <span class="badge bg-primary rounded-circle p-2 me-2">
                                                                <i class="fas fa-calendar-check"></i>
                                                            </span>
                                                            <h6 class="mb-0">School Days</h6>
                                                        </div>
                                                        <h3 class="mb-0">{{ $summary['total_stats']['total_days'] }}</h3>
                                                        <small class="text-muted">days with attendance</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Overall Attendance Rate -->
                                    <div class="col-md-4">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body p-4 text-center">
                                                <h6 class="text-muted mb-3">Overall Attendance Rate</h6>
                                                <div class="d-flex justify-content-center">
                                                    <div class="position-relative" style="width: 150px; height: 150px;">
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <h2 class="mb-0 fw-bold">{{ $summary['total_stats']['attendance_rate'] }}%</h2>
                                                        </div>
                                                        <svg width="150" height="150" viewBox="0 0 36 36">
                                                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f2f2f2" stroke-width="2.5"></circle>
                                                            <circle cx="18" cy="18" r="15.9" fill="none" stroke="#0d6efd" stroke-width="2.5"
                                                                    stroke-dasharray="{{ $summary['total_stats']['attendance_rate'] * 0.01 * 100 }} 100"
                                                                    stroke-dashoffset="25"
                                                                    stroke-linecap="round">
                                                            </circle>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <p class="text-muted mt-3 mb-0">
                                                    Based on {{ $summary['total_stats']['total_students'] }} students over {{ $summary['total_stats']['total_days'] }} days
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Weekly Breakdown -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-week text-primary me-2"></i>
                                    Weekly Breakdown
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Week</th>
                                                <th class="text-center">Present</th>
                                                <th class="text-center">Late</th>
                                                <th class="text-center">Half Day</th>
                                                <th class="text-center">Absent</th>
                                                <th class="text-center">Excused</th>
                                                <th class="text-center">Days</th>
                                                <th class="text-center">Attendance Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($summary['weekly_stats'] as $weekNumber => $weekStats)
                                                @if($weekStats['attendance_days'] > 0)
                                                    <tr>
                                                        <td>Week {{ $weekNumber }} ({{ $weekStats['start_date'] }} - {{ $weekStats['end_date'] }})</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-success rounded-pill">{{ $weekStats['present'] }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-warning rounded-pill">{{ $weekStats['late'] }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-info rounded-pill">{{ $weekStats['half_day'] }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-danger rounded-pill">{{ $weekStats['absent'] }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-secondary rounded-pill">{{ $weekStats['excused'] }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <span class="badge bg-primary rounded-pill">{{ $weekStats['attendance_days'] }}</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="progress" style="height: 8px;">
                                                                <div class="progress-bar bg-primary" role="progressbar"
                                                                    style="width: {{ $weekStats['attendance_rate'] }}%;"
                                                                    aria-valuenow="{{ $weekStats['attendance_rate'] }}"
                                                                    aria-valuemin="0"
                                                                    aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                            <small class="d-block mt-1">{{ $weekStats['attendance_rate'] }}%</small>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Monthly Attendance Chart -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-chart-line text-primary me-2"></i>
                                    Daily Attendance Trends
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div style="height: 300px;">
                                    <canvas id="monthlyAttendanceChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Daily Attendance Table -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-header bg-light py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-table text-primary me-2"></i>
                                    Daily Attendance Breakdown
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Date</th>
                                                <th class="text-center">Present</th>
                                                <th class="text-center">Late</th>
                                                <th class="text-center">Half Day</th>
                                                <th class="text-center">Absent</th>
                                                <th class="text-center">Excused</th>
                                                <th class="text-center">Attendance Rate</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($summary['dates'] as $dateString => $formattedDate)
                                                <tr>
                                                    <td>{{ $formattedDate }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success rounded-pill">{{ $summary['daily_stats'][$dateString]['present'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning rounded-pill">{{ $summary['daily_stats'][$dateString]['late'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-info rounded-pill">{{ $summary['daily_stats'][$dateString]['half_day'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-danger rounded-pill">{{ $summary['daily_stats'][$dateString]['absent'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary rounded-pill">{{ $summary['daily_stats'][$dateString]['excused'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: {{ $summary['daily_stats'][$dateString]['attendance_rate'] }}%;"
                                                                aria-valuenow="{{ $summary['daily_stats'][$dateString]['attendance_rate'] }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <small class="d-block mt-1">{{ $summary['daily_stats'][$dateString]['attendance_rate'] }}%</small>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('teacher.attendances.show', ['attendance' => $sectionId ?? $sections->first()->id, 'date' => $dateString]) }}"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye me-1"></i> Details
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Student Attendance Table -->
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light py-3">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-check text-primary me-2"></i>
                                    Student Attendance Summary
                                </h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Student</th>
                                                <th>Section</th>
                                                <th class="text-center">Present</th>
                                                <th class="text-center">Late</th>
                                                <th class="text-center">Half Day</th>
                                                <th class="text-center">Absent</th>
                                                <th class="text-center">Excused</th>
                                                <th class="text-center">Attendance Ratio</th>
                                                <th class="text-center">Attendance Rate</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($summary['students'] as $student)
                                                <tr>
                                                    <td>{{ $student['name'] }}</td>
                                                    <td>{{ $student['section'] }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-success rounded-pill">{{ $student['stats']['present'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning rounded-pill">{{ $student['stats']['late'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-info rounded-pill">{{ $student['stats']['half_day'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-danger rounded-pill">{{ $student['stats']['absent'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-secondary rounded-pill">{{ $student['stats']['excused'] }}</span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge bg-primary rounded-pill">{{ $student['stats']['attendance_ratio'] }}</span>
                                                        <small class="d-block mt-1 text-muted">days present/total</small>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="progress" style="height: 8px;">
                                                            <div class="progress-bar bg-primary" role="progressbar"
                                                                style="width: {{ $student['stats']['attendance_rate'] }}%;"
                                                                aria-valuenow="{{ $student['stats']['attendance_rate'] }}"
                                                                aria-valuemin="0"
                                                                aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <small class="d-block mt-1">{{ $student['stats']['attendance_rate'] }}%</small>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
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
        // Monthly attendance chart
        const ctx = document.getElementById('monthlyAttendanceChart').getContext('2d');

        // Prepare data for chart
        const dates = @json(array_values($summary['dates']));
        const dailyData = @json($summary['daily_stats']);
        const dateKeys = @json(array_keys($summary['dates']));

        const presentData = [];
        const lateData = [];
        const halfDayData = [];
        const absentData = [];
        const excusedData = [];

        dateKeys.forEach(date => {
            presentData.push(dailyData[date]['present']);
            lateData.push(dailyData[date]['late']);
            halfDayData.push(dailyData[date]['half_day']);
            absentData.push(dailyData[date]['absent']);
            excusedData.push(dailyData[date]['excused']);
        });

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
                        label: 'Late',
                        data: lateData,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
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
                        label: 'Absent',
                        data: absentData,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
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
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        @endif
    });
</script>
@endpush
