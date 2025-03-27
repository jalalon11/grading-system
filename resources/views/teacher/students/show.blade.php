@extends('layouts.app')

@push('styles')
<style>
    .profile-container {
        padding: 0;
        background-color: #fff;
        max-width: 100%;
    }
    
    .profile-header {
        background-color: #0d6efd;
        padding: 30px 0 15px;
        color: white;
        position: relative;
    }
    
    .profile-stats {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 8px 0;
    }
    
    .stat-item {
        text-align: center;
        padding: 6px 8px;
    }
    
    .stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 2px;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .student-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-right: 6px;
        color: #fff;
    }
    
    .section-heading {
        font-size: 1rem;
        font-weight: 600;
        padding-bottom: 0.4rem;
        margin-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .info-label {
        color: #6c757d;
        font-size: 0.8rem;
        margin-bottom: 0.1rem;
    }
    
    .info-value {
        font-weight: 500;
        margin-bottom: 0.75rem;
    }
    
    .info-card {
        height: 100%;
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .gradient-blue {
        background: linear-gradient(to right, #0062cc, #0d6efd);
    }
    
    .bg-soft-blue {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .bg-soft-green {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    
    .bg-soft-orange {
        background-color: rgba(255, 153, 0, 0.1);
        color: #fd7e14;
    }
    
    .attendance-dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-right: 4px;
    }
    
    .table-grades th, 
    .table-grades td {
        padding: 0.4rem 0.5rem;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    .btn-outline-primary {
        border-color: #0d6efd;
        color: #0d6efd;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    .student-details .list-group-item {
        border-left: none;
        border-right: none;
        padding: 0.4rem 0;
    }
    
    .student-details .list-group-item:first-child {
        border-top: none;
    }
    
    .student-details .list-group-item:last-child {
        border-bottom: none;
    }
    
    .container {
        width: auto;
        max-width: 100%;
        padding-left: 10px;
        padding-right: 10px;
    }
    
    @media (min-width: 992px) {
        .container {
            max-width: 960px;
        }
    }
    
    @media (min-width: 1200px) {
        .container {
            max-width: 1140px;
        }
    }
    
    /* Attendance cards fixes */
    .row.mb-4 .card {
        margin-bottom: 0.5rem;
    }
    
    .row.mb-4 .card .card-body {
        padding: 0.5rem;
    }
    
    /* Smaller icons */
    .row.mb-4 .card .card-body i {
        font-size: 1.2rem !important;
    }
    
    .row.mb-4 .card .card-body h4 {
        font-size: 1.2rem;
        margin-bottom: 0;
    }
    
    /* Reduce spacing in recent attendance table */
    .table.table-sm th,
    .table.table-sm td {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
    }
    
    /* Fix for the progress bar linter error - single line */
    .attendance-progress-bar {
        width: attr(data-width);
    }
</style>
@endpush

@section('content')
<div class="container-fluid profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <nav aria-label="breadcrumb" class="text-white">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}" class="text-white">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.students.index') }}" class="text-white">Students</a></li>
                        <li class="breadcrumb-item active text-white">Student Profile</li>
                    </ol>
                </nav>
                <div>
                    <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="mb-1">{{ $student->full_name }}</h1>
                    <div class="mb-3">
                        <span class="text-white-50">Student ID: </span>
                        <span class="fw-semibold">{{ $student->student_id }}</span>
                    </div>
                    <div>
                        <span class="student-badge bg-primary">
                            <i class="fas fa-door-open me-1"></i> {{ $student->section->name ?? 'No Section' }}
                        </span>
                        <span class="student-badge" style="background-color: #198754;">
                            <i class="fas fa-layer-group me-1"></i> Grade {{ $student->section->grade_level ?? 'Unknown' }}
                        </span>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                    <div class="d-inline-block text-start">
                        <div class="mb-2">
                            <i class="fas fa-calendar-alt me-2"></i> 
                            School Year: <span class="fw-semibold">{{ $student->section->school_year ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Adviser: <span class="fw-semibold">{{ $student->section->adviser->name ?? 'No adviser assigned' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Row -->
    <div class="profile-stats">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-value text-primary">{{ $student->gender }}</div>
                        <div class="stat-label">Gender</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-value text-success">{{ $student->birth_date ? $student->birth_date->age : 'N/A' }}</div>
                        <div class="stat-label">Age</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-value text-info">{{ $student->grades->count() }}</div>
                        <div class="stat-label">Grades Recorded</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        @php
                            $totalDays = $student->attendances->count();
                            $presentDays = $student->attendances->where('status', 'present')->count();
                            $attendanceRate = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100) : 'N/A';
                        @endphp
                        <div class="stat-value {{ is_numeric($attendanceRate) ? ($attendanceRate >= 80 ? 'text-success' : ($attendanceRate >= 60 ? 'text-warning' : 'text-danger')) : 'text-muted' }}">
                            {{ is_numeric($attendanceRate) ? $attendanceRate . '%' : $attendanceRate }}
                        </div>
                        <div class="stat-label">Attendance Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Area -->
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column: Personal & Guardian Info -->
            <div class="col-lg-4 mb-4">
                <!-- Personal Information -->
                <div class="card info-card mb-4">
                    <div class="card-body">
                        <h5 class="section-heading">
                            <i class="fas fa-user text-primary me-2"></i> Personal Information
                        </h5>

                        <ul class="list-group student-details">
                            <li class="list-group-item">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $student->full_name }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Student ID</div>
                                <div class="info-value">{{ $student->student_id }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Gender</div>
                                <div class="info-value">
                                    <i class="fas {{ $student->gender == 'Male' ? 'fa-mars text-primary' : 'fa-venus text-danger' }} me-2"></i>
                                    {{ $student->gender }}
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Birth Date</div>
                                <div class="info-value">
                                    <i class="fas fa-calendar-day text-info me-2"></i>
                                    {{ $student->birth_date ? $student->birth_date->format('F d, Y') : 'N/A' }}
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Age</div>
                                <div class="info-value">{{ $student->birth_date ? $student->birth_date->age . ' years old' : 'N/A' }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Address</div>
                                <div class="info-value">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    {{ $student->address ?: 'No address provided' }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Guardian Information -->
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="section-heading">
                            <i class="fas fa-user-shield text-success me-2"></i> Guardian Information
                        </h5>

                        <ul class="list-group student-details">
                            <li class="list-group-item">
                                <div class="info-label">Guardian Name</div>
                                <div class="info-value">{{ $student->guardian_name ?: 'Not provided' }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Contact Number</div>
                                <div class="info-value">{{ $student->guardian_contact ?: 'Not provided' }}</div>
                            </li>
                        </ul>

                        @if($student->guardian_contact)
                            <div class="mt-3">
                                <a href="tel:{{ $student->guardian_contact }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-phone me-1"></i> Contact Guardian
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Academic & Attendance -->
            <div class="col-lg-8">
                <!-- Academic Performance -->
                <div class="card info-card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="section-heading mb-0">
                                <i class="fas fa-chart-line text-primary me-2"></i> Academic Performance
                            </h5>
                            <a href="{{ route('teacher.grades.index', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-list me-1"></i> View All Grades
                            </a>
                        </div>

                        @if($student->grades->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-grades">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Subject</th>
                                            <th>Term</th>
                                            <th>Type</th>
                                            <th class="text-end">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($student->grades->take(5) as $grade)
                                            <tr>
                                                <td class="fw-medium">{{ $grade->subject->name ?? 'Unknown' }}</td>
                                                <td>{{ $grade->term }}</td>
                                                <td>
                                                    @php
                                                        $badgeClass = 'bg-secondary';
                                                        
                                                        if ($grade->grade_type == 'written_work') {
                                                            $badgeClass = 'bg-soft-blue';
                                                        } elseif ($grade->grade_type == 'performance_task') {
                                                            $badgeClass = 'bg-soft-green';
                                                        } elseif ($grade->grade_type == 'quarterly') {
                                                            $badgeClass = 'bg-soft-orange';
                                                        }
                                                    @endphp
                                                    <span class="badge {{ $badgeClass }}">
                                                        {{ ucfirst(str_replace('_', ' ', $grade->grade_type)) }}
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="badge {{ $grade->score >= 75 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $grade->score }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @php
                                // Group grades by type
                                $writtenWorks = $student->grades->where('grade_type', 'written_work')->all();
                                $performanceTasks = $student->grades->where('grade_type', 'performance_task')->all();
                                $quarterlyAssessments = $student->grades->where('grade_type', 'quarterly')->all();
                                
                                // Calculate averages for each type
                                $writtenWorksAvg = count($writtenWorks) > 0 ? 
                                    collect($writtenWorks)->average(function($grade) {
                                        return ($grade->score / $grade->max_score) * 100;
                                    }) : 0;
                                
                                $performanceTasksAvg = count($performanceTasks) > 0 ? 
                                    collect($performanceTasks)->average(function($grade) {
                                        return ($grade->score / $grade->max_score) * 100;
                                    }) : 0;
                                
                                $quarterlyScore = count($quarterlyAssessments) > 0 ? 
                                    collect($quarterlyAssessments)->average(function($grade) {
                                        return ($grade->score / $grade->max_score) * 100;
                                    }) : 0;
                                
                                // Get default percentages
                                $writtenWorkPercentage = 30;
                                $performanceTaskPercentage = 50;
                                $quarterlyAssessmentPercentage = 20;
                                
                                // If there are subjects, use the first subject's configuration
                                if($student->grades->isNotEmpty()) {
                                    $subjectId = $student->grades->first()->subject_id;
                                    $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                    
                                    if($gradeConfig) {
                                        $writtenWorkPercentage = $gradeConfig->written_work_percentage;
                                        $performanceTaskPercentage = $gradeConfig->performance_task_percentage;
                                        $quarterlyAssessmentPercentage = $gradeConfig->quarterly_assessment_percentage;
                                    }
                                }
                                
                                // Calculate weighted final grade
                                $finalGrade = 0;
                                if ($writtenWorksAvg > 0) {
                                    $finalGrade += ($writtenWorksAvg * ($writtenWorkPercentage / 100));
                                }
                                if ($performanceTasksAvg > 0) {
                                    $finalGrade += ($performanceTasksAvg * ($performanceTaskPercentage / 100));
                                }
                                if ($quarterlyScore > 0) {
                                    $finalGrade += ($quarterlyScore * ($quarterlyAssessmentPercentage / 100));
                                }
                                
                                $avgGrade = round($finalGrade, 1);
                            @endphp
                            <div class="alert {{ $avgGrade >= 75 ? 'alert-success' : 'alert-warning' }} d-flex align-items-center">
                                <i class="fas fa-info-circle me-2"></i>
                                <div>
                                    <strong>Average Score: {{ $avgGrade }}</strong>
                                    <span class="ms-2">
                                        {{ $avgGrade >= 75 ? 'Student is performing well!' : 'Student needs additional support.' }}
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-chart-bar text-muted mb-3" style="font-size: 3rem;"></i>
                                <p>No grades have been recorded for this student yet.</p>
                                <a href="{{ route('teacher.grades.index') }}" class="btn btn-sm btn-primary">
                                    Record Grades
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Attendance Summary -->
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="section-heading">
                            <i class="fas fa-calendar-check text-success me-2"></i> Attendance Summary
                        </h5>

                        @if($student->attendances->count() > 0)
                            @php
                                $totalDays = $student->attendances->count();
                                $presentDays = $student->attendances->where('status', 'present')->count();
                                $absentDays = $student->attendances->where('status', 'absent')->count();
                                $lateDays = $student->attendances->where('status', 'late')->count();
                                $presentPercentage = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100) : 0;
                            @endphp
                            
                            <div class="row mb-4">
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-calendar text-primary" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h4 class="mb-0">{{ $totalDays }}</h4>
                                            <div class="small text-muted">Total Days</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h4 class="mb-0">{{ $presentDays }}</h4>
                                            <div class="small text-muted">Present</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-times-circle text-danger" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h4 class="mb-0">{{ $absentDays }}</h4>
                                            <div class="small text-muted">Absent</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-clock text-warning" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h4 class="mb-0">{{ $lateDays }}</h4>
                                            <div class="small text-muted">Late</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Attendance Rate</span>
                                    <span class="{{ $presentPercentage >= 80 ? 'text-success' : ($presentPercentage >= 60 ? 'text-warning' : 'text-danger') }}">
                                        {{ $presentPercentage }}%
                                    </span>
                                </label>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-success" role="progressbar" style="width: {{ $presentPercentage }}%" aria-valuenow="{{ $presentPercentage }}" aria-valuemin="0" aria-valuemax="100"></div></div>
                            </div>

                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Recent Attendance</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $recentAttendance = $student->attendances->take(5)->sortByDesc('date');
                                                @endphp
                                                
                                                @foreach($recentAttendance as $attendance)
                                                    <tr>
                                                        <td>{{ $attendance->date->format('M d, Y') }}</td>
                                                        <td>
                                                            @if($attendance->status == 'present')
                                                                <span class="badge bg-success">Present</span>
                                                            @elseif($attendance->status == 'absent')
                                                                <span class="badge bg-danger">Absent</span>
                                                            @elseif($attendance->status == 'late')
                                                                <span class="badge bg-warning">Late</span>
                                                            @else
                                                                <span class="badge bg-secondary">Unknown</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times text-muted mb-3" style="font-size: 3rem;"></i>
                                <p>No attendance records have been recorded for this student yet.</p>
                                <a href="{{ route('teacher.attendances.index') }}" class="btn btn-sm btn-primary">
                                    Record Attendance
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 