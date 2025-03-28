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
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary me-2" id="toggleGradeView">
                                    <i class="fas fa-th-large me-1"></i> <span id="toggleViewText">Dashboard View</span>
                                </button>
                                <a href="{{ route('teacher.grades.index', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-list me-1"></i> View All Grades
                                </a>
                            </div>
                        </div>

                        @if($student->grades->count() > 0)
                            @php
                                // Group grades by type
                                $writtenWorks = $student->grades->where('grade_type', 'written_work')->all();
                                $performanceTasks = $student->grades->where('grade_type', 'performance_task')->all();
                                $quarterlyAssessments = $student->grades->where('grade_type', 'quarterly')->all();
                                
                                // Get all terms from grades
                                $availableTerms = $student->grades->pluck('term')->unique()->values()->all();
                                
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
                                
                                // Calculate grade for color
                                $gradeColor = 'success';
                                $gradeStatus = 'Excellent';
                                
                                if ($avgGrade < 75) {
                                    $gradeColor = 'danger';
                                    $gradeStatus = 'Needs Improvement';
                                } elseif ($avgGrade < 80) {
                                    $gradeColor = 'warning';
                                    $gradeStatus = 'Satisfactory';
                                } elseif ($avgGrade < 90) {
                                    $gradeColor = 'info';
                                    $gradeStatus = 'Good';
                                }
                                
                                // Group grades by subject
                                $gradesBySubject = [];
                                foreach ($student->grades as $grade) {
                                    $subject = $grade->subject;
                                    if (!$subject) continue;
                                    
                                    if (!isset($gradesBySubject[$subject->id])) {
                                        $gradesBySubject[$subject->id] = [
                                            'name' => $subject->name,
                                            'code' => $subject->code,
                                            'grades' => [],
                                            'avg_score' => 0
                                        ];
                                    }
                                    
                                    $gradesBySubject[$subject->id]['grades'][] = $grade;
                                }
                                
                                // Calculate average score for each subject
                                foreach ($gradesBySubject as $subjectId => &$subjectData) {
                                    $total = 0;
                                    $count = 0;
                                    
                                    foreach ($subjectData['grades'] as $grade) {
                                        $total += ($grade->score / $grade->max_score) * 100;
                                        $count++;
                                    }
                                    
                                    $subjectData['avg_score'] = $count > 0 ? round($total / $count, 1) : 0;
                                }
                                
                                // Sort subjects by average score (highest first)
                                uasort($gradesBySubject, function($a, $b) {
                                    return $b['avg_score'] <=> $a['avg_score'];
                                });
                            @endphp
                            
                            <!-- Term Filter -->
                            <div class="mb-3">
                                <div class="form-group">
                                    <label for="termFilter" class="mb-2 small text-muted">Filter by Term:</label>
                                    <select class="form-select form-select-sm" id="termFilter">
                                        <option value="all">All Terms</option>
                                        @foreach($availableTerms as $term)
                                            <option value="{{ $term }}">{{ $term }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div id="academicDashboard" class="mb-4" style="display: none;">
                                <!-- Academic Performance Dashboard -->
                                <div class="row">
                                    <!-- Overall Grade Card -->
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 border-0 shadow-sm">
                                            <div class="card-body text-center">
                                                <h6 class="text-muted mb-2">Overall Grade</h6>
                                                <div class="display-4 fw-bold text-{{ $gradeColor }}">{{ $avgGrade }}</div>
                                                <span class="badge bg-{{ $gradeColor }} px-3 py-2 mt-2">{{ $gradeStatus }}</span>
                                                <div class="small text-muted mt-2">Based on {{ $student->grades->count() }} assessments</div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Grade Components Card -->
                                    <div class="col-md-8 mb-3">
                                        <div class="card border-0 shadow-sm h-100">
                                            <div class="card-body">
                                                <h6 class="text-muted mb-2">Performance by Component</h6>
                                                <!-- Written Works -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                                                        <span class="fw-medium text-primary">Written Works</span>
                                                        <span class="text-dark">{{ number_format($writtenWorksAvg, 1) }}%</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-primary" role="progressbar" 
                                                             style="width: {{ $writtenWorksAvg }}%" 
                                                             aria-valuenow="{{ $writtenWorksAvg }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between small mt-1">
                                                        <span class="text-muted">{{ count($writtenWorks) }} assessments</span>
                                                        <span class="text-muted">{{ $writtenWorkPercentage }}% of grade</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Performance Tasks -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                                                        <span class="fw-medium text-success">Performance Tasks</span>
                                                        <span class="text-dark">{{ number_format($performanceTasksAvg, 1) }}%</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                             style="width: {{ $performanceTasksAvg }}%" 
                                                             aria-valuenow="{{ $performanceTasksAvg }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between small mt-1">
                                                        <span class="text-muted">{{ count($performanceTasks) }} assessments</span>
                                                        <span class="text-muted">{{ $performanceTaskPercentage }}% of grade</span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Quarterly Assessments -->
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                                                        <span class="fw-medium text-warning">Quarterly Assessments</span>
                                                        <span class="text-dark">{{ number_format($quarterlyScore, 1) }}%</span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" 
                                                             style="width: {{ $quarterlyScore }}%" 
                                                             aria-valuenow="{{ $quarterlyScore }}" 
                                                             aria-valuemin="0" 
                                                             aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-between small mt-1">
                                                        <span class="text-muted">{{ count($quarterlyAssessments) }} assessments</span>
                                                        <span class="text-muted">{{ $quarterlyAssessmentPercentage }}% of grade</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Performance by Subject -->
                                <div class="mb-3">
                                    <h6 class="text-muted mb-3">Performance by Subject</h6>
                                    <div class="row">
                                        @foreach($gradesBySubject as $subjectId => $subjectData)
                                            <div class="col-lg-6 mb-3 subject-grade-item">
                                                <div class="card border-0 shadow-sm h-100">
                                                    <div class="card-body">
                                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                                            <div>
                                                                <h6 class="mb-0">{{ $subjectData['name'] }}</h6>
                                                                <small class="text-muted">{{ $subjectData['code'] }}</small>
                                                            </div>
                                                            <div>
                                                                @php
                                                                    $subjectGradeColor = 'secondary';
                                                                    if ($subjectData['avg_score'] >= 90) {
                                                                        $subjectGradeColor = 'success';
                                                                    } elseif ($subjectData['avg_score'] >= 80) {
                                                                        $subjectGradeColor = 'primary';
                                                                    } elseif ($subjectData['avg_score'] >= 75) {
                                                                        $subjectGradeColor = 'info';
                                                                    } elseif ($subjectData['avg_score'] > 0) {
                                                                        $subjectGradeColor = 'danger';
                                                                    }
                                                                @endphp
                                                                <span class="badge bg-{{ $subjectGradeColor }} px-2 py-1">{{ $subjectData['avg_score'] }}%</span>
                                                            </div>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar bg-{{ $subjectGradeColor }}" role="progressbar" 
                                                                 style="width: {{ $subjectData['avg_score'] }}%" 
                                                                 aria-valuenow="{{ $subjectData['avg_score'] }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                        <div class="small text-muted mt-2">
                                                            Based on {{ count($subjectData['grades']) }} assessments
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Academic Performance Table View -->
                            <div id="academicTableView">
                                <div class="table-responsive">
                                    <table class="table table-grades" id="gradesTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Term</th>
                                                <th>Assessment</th>
                                                <th>Type</th>
                                                <th class="text-end">Score</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($student->grades->sortByDesc('created_at') as $grade)
                                                <tr class="grade-row" data-term="{{ $grade->term }}">
                                                    <td class="fw-medium">{{ $grade->subject->name ?? 'Unknown' }}</td>
                                                    <td>{{ $grade->term }}</td>
                                                    <td>{{ $grade->assessment_name }}</td>
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
                                                        @php
                                                            $scorePercentage = ($grade->score / $grade->max_score) * 100;
                                                            $scoreClass = 'bg-danger';
                                                            if ($scorePercentage >= 90) {
                                                                $scoreClass = 'bg-success';
                                                            } elseif ($scorePercentage >= 80) {
                                                                $scoreClass = 'bg-primary';
                                                            } elseif ($scorePercentage >= 75) {
                                                                $scoreClass = 'bg-info';
                                                            }
                                                        @endphp
                                                        <span class="badge {{ $scoreClass }}">
                                                            {{ $grade->score }}/{{ $grade->max_score }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <div class="d-flex align-items-center p-2 rounded-3 {{ $avgGrade >= 75 ? 'bg-success bg-opacity-10 text-success' : 'bg-danger bg-opacity-10 text-danger' }} mt-3">
                                <i class="fas {{ $avgGrade >= 75 ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-3 fs-4"></i>
                                <div>
                                    <strong>Overall Performance: {{ $avgGrade }}%</strong>
                                    <p class="mb-0 small">
                                        {{ $avgGrade >= 75 ? 'Student is performing well and meeting academic standards.' : 'Student may need additional support to improve academic performance.' }}
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-chart-bar text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">No Grade Data Available</h5>
                                <p class="text-muted mb-4">No grades have been recorded for this student yet.</p>
                                <a href="{{ route('teacher.grades.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i> Record First Grade
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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle between dashboard and table view
        const toggleBtn = document.getElementById('toggleGradeView');
        const toggleText = document.getElementById('toggleViewText');
        const dashboard = document.getElementById('academicDashboard');
        const tableView = document.getElementById('academicTableView');
        
        if (toggleBtn && dashboard && tableView) {
            toggleBtn.addEventListener('click', function() {
                if (dashboard.style.display === 'none') {
                    dashboard.style.display = 'block';
                    tableView.style.display = 'none';
                    toggleText.innerText = 'Table View';
                    toggleBtn.querySelector('i').className = 'fas fa-table me-1';
                    localStorage.setItem('academicViewPreference', 'dashboard');
                } else {
                    dashboard.style.display = 'none';
                    tableView.style.display = 'block';
                    toggleText.innerText = 'Dashboard View';
                    toggleBtn.querySelector('i').className = 'fas fa-th-large me-1';
                    localStorage.setItem('academicViewPreference', 'table');
                }
            });
            
            // Load saved preference
            const savedPreference = localStorage.getItem('academicViewPreference');
            if (savedPreference === 'dashboard') {
                dashboard.style.display = 'block';
                tableView.style.display = 'none';
                toggleText.innerText = 'Table View';
                toggleBtn.querySelector('i').className = 'fas fa-table me-1';
            }
        }
        
        // Term filter functionality
        const termFilter = document.getElementById('termFilter');
        const gradeRows = document.querySelectorAll('.grade-row');
        
        if (termFilter && gradeRows.length > 0) {
            termFilter.addEventListener('change', function() {
                const selectedTerm = this.value;
                
                gradeRows.forEach(row => {
                    const rowTerm = row.getAttribute('data-term');
                    if (selectedTerm === 'all' || rowTerm === selectedTerm) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Also filter subject cards in dashboard
                const subjectCards = document.querySelectorAll('.subject-grade-item');
                if (selectedTerm === 'all') {
                    subjectCards.forEach(card => {
                        card.style.display = '';
                    });
                } else {
                    // This is simplified - in a real app you'd need to filter based on term data
                    // For now we just keep all subjects visible regardless of term filter
                }
            });
        }
    });
</script>
@endpush 