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
                                <i class="fas fa-calendar-week text-primary me-2"></i> Weekly Attendance
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('teacher.attendances.monthly-summary') }}" class="btn btn-outline-info">
                                    <i class="fas fa-calendar-alt me-1"></i> Monthly View
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
                                        <i class="fas fa-calendar-week text-primary me-2"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $currentWeek }}</h6>
                                            <div class="small text-muted">{{ $schoolDays }} school days</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <form action="{{ route('teacher.attendances.weekly-summary') }}" method="GET" class="row g-3">
                                <!-- Week Selection -->
                                <div class="col-md-5">
                                    <label for="week" class="form-label">Week</label>
                                    <select class="form-select" id="week" name="week">
                                        <option value="">Current Week</option>
                                        @foreach($availableWeeks as $week)
                                            <option value="{{ $week['start_date'] }}" {{ request('week') == $week['start_date'] ? 'selected' : '' }}>
                                                {{ $week['display_range'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

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
                        <!-- Weekly Overview Card - Simplified -->
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

                        <!-- Daily Attendance Chart - Simplified -->
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
                                    <div class="chart-container" style="position: relative; height: 250px; width: 100%;">
                                        <canvas id="dailyAttendanceChart"></canvas>
                                    </div>
                            </div>
                        </div>

                        <!-- Daily Attendance List - Simplified -->
                        <div class="card border-0 shadow-sm mb-4">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge bg-primary p-2 me-2">
                                        <i class="fas fa-calendar-day"></i>
                                    </span>
                                    <h6 class="mb-0">Daily Records</h6>
                                </div>

                                <div>
                                    <div class="list-group">
                                        @foreach($summary['dates'] as $dateString => $formattedDate)
                                            <div class="list-group-item list-group-item-action p-3">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <h6 class="mb-1">{{ $formattedDate }}</h6>
                                                        <div class="d-flex flex-wrap gap-2 small">
                                                            <span class="text-success">Present: {{ $summary['daily_stats'][$dateString]['present'] }}</span>
                                                            <span class="text-danger">Absent: {{ $summary['daily_stats'][$dateString]['absent'] }}</span>
                                                            <span class="text-warning">Late: {{ $summary['daily_stats'][$dateString]['late'] }}</span>
                                                            <span class="text-info">Half: {{ $summary['daily_stats'][$dateString]['half_day'] }}</span>
                                                            <span class="text-secondary">Excused: {{ $summary['daily_stats'][$dateString]['excused'] }}</span>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <span class="badge bg-primary rounded-pill mb-2 d-block">{{ $summary['daily_stats'][$dateString]['attendance_rate'] }}%</span>
                                                        <a href="{{ route('teacher.attendances.show', ['attendance' => $sectionId ?? $sections->first()->id, 'date' => $dateString]) }}"
                                                           class="btn btn-sm btn-outline-primary">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
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
                            <i class="fas fa-info-circle me-2"></i> No attendance records found for this week. Please create attendance records first.
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
        // No toggle button functionality needed anymore

        // No filter toggle code needed anymore
        @if(count($summary['dates']) > 0)
        // Simple attendance chart
        const ctx = document.getElementById('dailyAttendanceChart').getContext('2d');

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
            type: 'bar',
            data: {
                labels: dates,
                datasets: [
                    {
                        label: 'Present',
                        data: presentData,
                        backgroundColor: '#28a745',
                        borderRadius: 4
                    },
                    {
                        label: 'Absent',
                        data: absentData,
                        backgroundColor: '#dc3545',
                        borderRadius: 4
                    },
                    {
                        label: 'Half Day',
                        data: halfDayData,
                        backgroundColor: '#17a2b8',
                        borderRadius: 4
                    },
                    {
                        label: 'Late',
                        data: lateData,
                        backgroundColor: '#ffc107',
                        borderRadius: 4
                    },
                    {
                        label: 'Excused',
                        data: excusedData,
                        backgroundColor: '#6c757d',
                        borderRadius: 4
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
                    x: {
                        stacked: true,
                        ticks: {
                            maxRotation: 45,
                            font: {
                                size: 10
                            }
                        }
                    },
                    y: {
                        stacked: true,
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
