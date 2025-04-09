@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check text-white me-2"></i> Attendance Records
                        </h5>
                        <div>
                            <a href="{{ route('teacher.attendances.weekly-summary') }}" class="btn btn-light me-2">
                                <i class="fas fa-calendar-week me-1"></i> Weekly Summary
                            </a>
                            <a href="{{ route('teacher.attendances.monthly-summary') }}" class="btn btn-light me-2">
                                <i class="fas fa-calendar-alt me-1"></i> Monthly Summary
                            </a>
                            <a href="{{ route('teacher.attendances.create') }}" class="btn btn-light">
                                <i class="fas fa-plus-circle me-1"></i> Record New Attendance
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Weekly Summary Card (if available) -->
                    @if(isset($currentWeekSummary) && count($currentWeekSummary['dates']) > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-week text-primary me-2"></i>
                                    Weekly Attendance Summary ({{ now()->startOfWeek()->format('M d') }} - {{ now()->endOfWeek()->format('M d') }})
                                </h5>
                                <a href="{{ route('teacher.attendances.weekly-summary') }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-chart-bar me-1"></i> View Detailed Report
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-success rounded-circle p-2 me-2">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                <h6 class="mb-0">Present</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentWeekSummary['total_stats']['present'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-warning rounded-circle p-2 me-2">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                                <h6 class="mb-0">Late</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentWeekSummary['total_stats']['late'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-info rounded-circle p-2 me-2">
                                                    <i class="fas fa-adjust"></i>
                                                </span>
                                                <h6 class="mb-0">Half Day</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentWeekSummary['total_stats']['half_day'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-danger rounded-circle p-2 me-2">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                                <h6 class="mb-0">Absent</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentWeekSummary['total_stats']['absent'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-secondary rounded-circle p-2 me-2">
                                                    <i class="fas fa-file-medical"></i>
                                                </span>
                                                <h6 class="mb-0">Excused</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentWeekSummary['total_stats']['excused'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-primary rounded-circle p-2 me-2">
                                                    <i class="fas fa-calendar-check"></i>
                                                </span>
                                                <h6 class="mb-0">Ratio</h6>
                                            </div>
                                            <h3 class="mb-0">
                                                @php
                                                    $presentCount = $currentWeekSummary['total_stats']['present'];
                                                    $lateCount = $currentWeekSummary['total_stats']['late'];
                                                    $halfDayCount = $currentWeekSummary['total_stats']['half_day'];
                                                    $totalDays = $currentWeekSummary['total_stats']['total_days'] * $currentWeekSummary['total_stats']['total_students'];

                                                    $numerator = '';
                                                    if ($presentCount > 0 || $lateCount > 0) {
                                                        $numerator = $presentCount + $lateCount;
                                                    }

                                                    if ($halfDayCount > 0) {
                                                        if (!empty($numerator)) {
                                                            $numerator .= '+Half';
                                                        } else {
                                                            $numerator = 'Half';
                                                        }
                                                    }

                                                    if (empty($numerator)) {
                                                        $numerator = '0';
                                                    }

                                                    echo $numerator . '/' . $totalDays;
                                                @endphp
                                            </h3>
                                            <small class="text-muted">present/total</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-primary rounded-circle p-2 me-2">
                                                    <i class="fas fa-percentage"></i>
                                                </span>
                                                <h6 class="mb-0">Rate</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentWeekSummary['total_stats']['attendance_rate'] }}%</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Monthly Summary Card (if available) -->
                    @if(isset($currentMonthSummary) && count($currentMonthSummary['dates']) > 0)
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-light py-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    Monthly Attendance Summary: {{ $currentMonthSummary['month_name'] }}
                                </h5>
                                <a href="{{ route('teacher.attendances.monthly-summary', ['month' => $currentMonthSummary['year_month']]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-chart-bar me-1"></i> View Detailed Report
                                </a>
                            </div>
                        </div>
                        <div class="card-body p-3">
                            <div class="row g-3">
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-success rounded-circle p-2 me-2">
                                                    <i class="fas fa-check"></i>
                                                </span>
                                                <h6 class="mb-0">Present</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentMonthSummary['total_stats']['present'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-warning rounded-circle p-2 me-2">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                                <h6 class="mb-0">Late</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentMonthSummary['total_stats']['late'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-info rounded-circle p-2 me-2">
                                                    <i class="fas fa-adjust"></i>
                                                </span>
                                                <h6 class="mb-0">Half Day</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentMonthSummary['total_stats']['half_day'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-danger rounded-circle p-2 me-2">
                                                    <i class="fas fa-times"></i>
                                                </span>
                                                <h6 class="mb-0">Absent</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentMonthSummary['total_stats']['absent'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-secondary rounded-circle p-2 me-2">
                                                    <i class="fas fa-file-medical"></i>
                                                </span>
                                                <h6 class="mb-0">Excused</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentMonthSummary['total_stats']['excused'] }}</h3>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-primary rounded-circle p-2 me-2">
                                                    <i class="fas fa-calendar-check"></i>
                                                </span>
                                                <h6 class="mb-0">Ratio</h6>
                                            </div>
                                            <h3 class="mb-0">
                                                @php
                                                    $presentCount = $currentMonthSummary['total_stats']['present'];
                                                    $lateCount = $currentMonthSummary['total_stats']['late'];
                                                    $halfDayCount = $currentMonthSummary['total_stats']['half_day'];
                                                    $totalDays = $currentMonthSummary['total_stats']['total_days'] * $currentMonthSummary['total_stats']['total_students'];

                                                    $numerator = '';
                                                    if ($presentCount > 0 || $lateCount > 0) {
                                                        $numerator = $presentCount + $lateCount;
                                                    }

                                                    if ($halfDayCount > 0) {
                                                        if (!empty($numerator)) {
                                                            $numerator .= '+Half';
                                                        } else {
                                                            $numerator = 'Half';
                                                        }
                                                    }

                                                    if (empty($numerator)) {
                                                        $numerator = '0';
                                                    }

                                                    echo $numerator . '/' . $totalDays;
                                                @endphp
                                            </h3>
                                            <small class="text-muted">present/total</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-3 text-center">
                                            <div class="d-flex align-items-center justify-content-center mb-2">
                                                <span class="badge bg-primary rounded-circle p-2 me-2">
                                                    <i class="fas fa-percentage"></i>
                                                </span>
                                                <h6 class="mb-0">Rate</h6>
                                            </div>
                                            <h3 class="mb-0">{{ $currentMonthSummary['total_stats']['attendance_rate'] }}%</h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Filter Form -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="fas fa-filter text-secondary me-1"></i> Filter Records
                            </h6>
                            <form method="GET" action="{{ route('teacher.attendances.index') }}" class="row g-3">
                                <div class="col-md-3">
                                    <label for="section_id" class="form-label fw-medium">Section</label>
                                    <select class="form-select" id="section_id" name="section_id">
                                        <option value="">All Sections</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="month" class="form-label fw-medium">Month</label>
                                    <select class="form-select" id="month" name="month">
                                        <option value="">All Months</option>
                                        @foreach($availableMonths as $month)
                                            <option value="{{ $month->month_value }}" {{ request('month') == $month->month_value ? 'selected' : '' }}>
                                                {{ $month->month_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="date" class="form-label fw-medium">Specific Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-secondary me-2">
                                        <i class="fas fa-search me-1"></i> Apply Filters
                                    </button>
                                    <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Monthly Summary Card (only shows when month filter is applied) -->
                    @if(request('month'))
                    <div class="card border-0 shadow-sm mb-4">
                        <div class="card-header bg-primary text-white py-3">
                            <h5 class="mb-0">
                                <i class="fas fa-chart-bar me-2"></i>
                                Monthly Attendance Summary: {{ \Carbon\Carbon::createFromFormat('Y-m', request('month'))->format('F Y') }}
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                @php
                                    $totalPresent = 0;
                                    $totalLate = 0;
                                    $totalAbsent = 0;
                                    $totalExcused = 0;
                                    $totalHalfDay = 0;
                                    $totalStudents = 0;

                                    foreach ($attendances as $dateGroup) {
                                        foreach ($dateGroup as $attendance) {
                                            $totalPresent += $attendance['present_count'];
                                            $totalLate += $attendance['late_count'];
                                            $totalAbsent += $attendance['absent_count'];
                                            $totalExcused += $attendance['excused_count'];
                                            $totalHalfDay += $attendance['half_day_count'];
                                        }
                                    }

                                    $totalStudents = $totalPresent + $totalLate + $totalAbsent + $totalExcused + $totalHalfDay;
                                    $attendanceRate = $totalStudents > 0 ?
                                        round((($totalPresent + $totalLate + ($totalHalfDay * 0.5)) / $totalStudents) * 100, 1) : 0;
                                @endphp

                                <!-- Overall Attendance Rate -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-4 text-center">
                                            <h6 class="text-muted mb-3">Overall Attendance Rate</h6>
                                            <div class="d-flex justify-content-center">
                                                <div class="position-relative" style="width: 150px; height: 150px;">
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <h2 class="mb-0 fw-bold">{{ $attendanceRate }}%</h2>
                                                    </div>
                                                    <svg width="150" height="150" viewBox="0 0 36 36">
                                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#f2f2f2" stroke-width="2.5"></circle>
                                                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#0d6efd" stroke-width="2.5"
                                                                stroke-dasharray="{{ $attendanceRate * 0.01 * 100 }} 100"
                                                                stroke-dashoffset="25" class="progress-circle"></circle>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="text-muted mt-2">
                                                Total Records: {{ $totalStudents }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status Breakdown -->
                                <div class="col-md-6">
                                    <div class="card h-100 border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <h6 class="text-muted mb-3 text-center">Attendance Breakdown</h6>
                                            <div class="d-flex flex-column gap-3 mt-4">
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span><i class="fas fa-circle text-success me-2"></i> Present</span>
                                                        <span class="fw-medium">{{ $totalPresent }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalPresent / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span><i class="fas fa-circle text-warning me-2"></i> Late</span>
                                                        <span class="fw-medium">{{ $totalLate }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalLate / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span><i class="fas fa-circle text-info me-2"></i> Half Day</span>
                                                        <span class="fw-medium">{{ $totalHalfDay }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-info" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalHalfDay / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span><i class="fas fa-circle text-danger me-2"></i> Absent</span>
                                                        <span class="fw-medium">{{ $totalAbsent }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-danger" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalAbsent / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span><i class="fas fa-circle text-secondary me-2"></i> Excused</span>
                                                        <span class="fw-medium">{{ $totalExcused }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-secondary" role="progressbar"
                                                            style="width: {{ $totalStudents > 0 ? ($totalExcused / $totalStudents) * 100 : 0 }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Attendance Records Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Section</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Half Day</th>
                                    <th class="text-center">Absent</th>
                                    <th class="text-center">Excused</th>
                                    <th class="text-center">Attendance Rate</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $date => $dateGroup)
                                    @foreach ($dateGroup as $sectionId => $attendance)
                                        @php
                                            $totalStudents = $attendance['present_count'] + $attendance['late_count'] + $attendance['absent_count'] + $attendance['excused_count'] + $attendance['half_day_count'];
                                            $attendanceRate = $totalStudents > 0 ?
                                                round((($attendance['present_count'] + $attendance['late_count'] + ($attendance['half_day_count'] * 0.5)) / $totalStudents) * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td class="fw-medium">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                            <td>{{ $attendance['section_name'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-success rounded-pill">{{ $attendance['present_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark rounded-pill">{{ $attendance['late_count'] ?? 0 }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info text-dark rounded-pill">{{ $attendance['half_day_count'] ?? 0 }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger rounded-pill">{{ $attendance['absent_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary rounded-pill">{{ $attendance['excused_count'] ?? 0 }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-primary" role="progressbar"
                                                        style="width: {{ $attendanceRate }}%;"
                                                        aria-valuenow="{{ $attendanceRate }}"
                                                        aria-valuemin="0"
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="d-block mt-1">{{ $attendanceRate }}%</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('teacher.attendances.show', ['attendance' => $sectionId, 'date' => $date]) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       data-bs-toggle="tooltip"
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('teacher.attendances.edit', ['attendance' => $sectionId, 'date' => $date]) }}"
                                                       class="btn btn-sm btn-outline-primary"
                                                       data-bs-toggle="tooltip"
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                            <p>No attendance records found</p>
                                            <a href="{{ route('teacher.attendances.create') }}" class="btn btn-sm btn-primary">
                                                Record New Attendance
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush

@push('styles')
<style>
    .progress-circle {
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
        transition: stroke-dasharray 0.5s ease;
    }
</style>
@endpush
@endsection