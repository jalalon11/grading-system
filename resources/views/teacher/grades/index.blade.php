@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/grades.css') }}">
<style>
    .assessment-card {
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        border: none !important;
    }
    .assessment-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .bg-light-blue {
        background-color: rgba(78, 115, 223, 0.1) !important;
        border-left: 4px solid #4e73df !important;
    }
    .bg-light-green {
        background-color: rgba(28, 200, 138, 0.1) !important;
        border-left: 4px solid #1cc88a !important;
    }
    .bg-light-yellow {
        background-color: rgba(246, 194, 62, 0.1) !important;
        border-left: 4px solid #f6c23e !important;
    }
    .avatar-circle {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    .grade-table th, .grade-table td {
        vertical-align: middle;
    }
    .stat-card {
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: scale(1.03);
    }
    .progress {
        overflow: visible;
    }
    .progress-label {
        position: absolute;
        right: 0;
        top: -25px;
    }
    
    /* New styles for comprehensive grade view */
    .table-responsive {
        overflow-x: auto;
        scrollbar-width: thin;
    }
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: #ccc;
        border-radius: 4px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #aaa;
    }
    .subject-column {
        white-space: nowrap;
        max-width: 150px;
    }
    .grade-badge {
        font-size: 14px;
        padding: 6px 8px;
        min-width: 40px;
    }
    #gradeTable thead th {
        position: sticky;
        top: 0;
        z-index: 1;
        background-color: #f8f9fa;
    }
    #gradeTable th:first-child,
    #gradeTable td:first-child {
        position: sticky;
        left: 0;
        z-index: 2;
        background-color: #f8f9fa;
    }
    #gradeTable th:nth-child(2),
    #gradeTable td:nth-child(2) {
        position: sticky;
        left: 40px;
        z-index: 2;
        background-color: #f8f9fa;
    }
    #gradeTable.compact-view .student-id,
    #gradeTable.compact-view .subject-code {
        display: none;
    }
    #gradeTable.compact-view .subject-column {
        max-width: 80px;
        min-width: 80px;
    }
    #gradeTable.compact-view .avatar-circle {
        width: 30px;
        height: 30px;
        font-size: 12px;
    }
    #gradeTable.compact-view td,
    #gradeTable.compact-view th {
        padding: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-graduation-cap mr-2 text-primary"></i> Grade Management
            </h1>
            <p class="text-muted">Manage and track student performance across all subjects and terms</p>
        </div>
        <div>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-home me-1"></i> Dashboard
            </a>
            <a href="{{ route('teacher.grades.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Record New Grade
            </a>
        </div>
    </div>

    <!-- Status Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filter Selection Card -->
    <div class="card shadow-sm rounded-3 mb-4 border-0">
        <div class="card-header py-3 bg-white">
            <h5 class="mb-0 fw-bold text-primary">
                <i class="fas fa-filter me-2"></i> Grade Filters
            </h5>
        </div>
        <div class="card-body bg-light py-4">
            @if(empty($subjects) || $subjects->isEmpty())
                <!-- No Subjects View -->
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-book-open fa-3x text-muted"></i>
                    </div>
                    <h5>No Subjects Assigned</h5>
                    <p class="text-muted">You don't have any subjects assigned to your account yet.</p>
                    <div class="mt-3">
                        <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary me-2">
                            <i class="fas fa-home me-1"></i> Return to Dashboard
                        </a>
                        <a href="{{ route('teacher.grades.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-sync-alt me-1"></i> Refresh Page
                        </a>
                    </div>
                    
                    <!-- Emergency Access -->
                    <div class="border-top mt-4 pt-4 text-start w-75 mx-auto">
                        <h6 class="fw-bold text-muted mb-3">Emergency Access</h6>
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Note:</strong> If you should have access to subjects, you can try the emergency access option below.
                        </div>
                        
                        @php
                            $directSubject = DB::table('subjects')->where('id', 1)->first();
                        @endphp
                        
                        @if($directSubject)
                            <a href="{{ route('teacher.grades.index', ['subject_id' => $directSubject->id]) }}" 
                               class="btn btn-warning">
                                <i class="fas fa-unlock-alt me-1"></i> Access {{ $directSubject->name }}
                            </a>
                        @endif
                    </div>
                </div>
            @else
                <form method="GET" action="{{ route('teacher.grades.index') }}" id="filterForm" class="row g-3">
                    <div class="col-md-4">
                        <label for="subject_id" class="form-label fw-bold">Subject</label>
                        <select class="form-select shadow-sm" id="subject_id" name="subject_id">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (isset($selectedSubject) && $selectedSubject->id == $subject->id) ? 'selected' : '' }}>
                                    {{ $subject->name }} - {{ $subject->code }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="section_id" class="form-label fw-bold">Section</label>
                        <select class="form-select shadow-sm" id="section_id" name="section_id">
                            @foreach($sections as $section)
                                <option value="{{ $section->id }}" {{ $selectedSectionId == $section->id ? 'selected' : '' }}>
                                    {{ $section->name }} (Grade {{ $section->grade_level }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="term" class="form-label fw-bold">Academic Term</label>
                        <select class="form-select shadow-sm" id="term" name="term">
                            @foreach($terms as $key => $term)
                                <option value="{{ $key }}" {{ $selectedTerm == $key ? 'selected' : '' }}>
                                    {{ $term }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-2"></i> View Grades
                        </button>
                    </div>
                    
                    <div class="col-12 mt-3">
                        <div class="card border-0 {{ request('view_all') == 'true' ? 'bg-primary bg-opacity-10' : 'bg-light' }}">
                            <div class="card-body">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="view_all" name="view_all" value="true" 
                                           {{ request('view_all') == 'true' ? 'checked' : '' }}
                                           data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Show comprehensive view of all subjects and grades for each student">
                                    <label class="form-check-label fw-bold" for="view_all">
                                        <i class="fas fa-table-columns me-1"></i> Comprehensive Grade View
                                    </label>
                                </div>
                                <p class="text-muted small mb-0 mt-1 ms-4">
                                    <i class="fas fa-info-circle me-1"></i> 
                                    {{ request('view_all') == 'true' 
                                        ? 'Currently showing all subjects and calculating overall averages. Each column represents a different subject.' 
                                        : 'Enable to view all subjects and calculate overall averages for students in this section.' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>

    @if(isset($selectedSubject) && $selectedSubject)
        <!-- Selected Subject Overview -->
        <div class="row mb-4">
            <!-- Grade Weight Distribution -->
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="card shadow-sm rounded-3 border-0 h-100">
                    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-chart-pie me-2"></i> Grade Components
                        </h5>
                        <a href="{{ route('teacher.grades.configure', ['subject_id' => $selectedSubject->id]) }}" 
                           class="btn btn-sm btn-outline-primary"
                           data-bs-toggle="tooltip" 
                           data-bs-placement="left" 
                           title="Adjust how different assessment types contribute to the final grade">
                            <i class="fas fa-cog me-1"></i> Configure
                        </a>
                    </div>
                    <div class="card-body">
                        @php
                            $writtenWorkPercentage = $selectedSubject->gradeConfiguration->written_work_percentage ?? 30;
                            $performanceTaskPercentage = $selectedSubject->gradeConfiguration->performance_task_percentage ?? 50;
                            $quarterlyAssessmentPercentage = $selectedSubject->gradeConfiguration->quarterly_assessment_percentage ?? 20;
                        @endphp
                        
                        <!-- Written Works -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Written Works</span>
                                <span class="badge bg-primary rounded-pill">{{ $writtenWorkPercentage }}%</span>
                            </div>
                            <div class="position-relative">
                                <div class="progress rounded-pill" style="height: 10px;">
                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $writtenWorkPercentage }}%" 
                                        aria-valuenow="{{ $writtenWorkPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 small text-muted">
                                <i class="fas fa-info-circle me-1"></i> Quizzes, seatwork, and written exercises
                            </div>
                        </div>
                        
                        <!-- Performance Tasks -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Performance Tasks</span>
                                <span class="badge bg-success rounded-pill">{{ $performanceTaskPercentage }}%</span>
                            </div>
                            <div class="position-relative">
                                <div class="progress rounded-pill" style="height: 10px;">
                                    <div class="progress-bar bg-success" role="progressbar" style="width: {{ $performanceTaskPercentage }}%" 
                                        aria-valuenow="{{ $performanceTaskPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 small text-muted">
                                <i class="fas fa-info-circle me-1"></i> Projects, presentations, and activities
                            </div>
                        </div>
                        
                        <!-- Quarterly Assessment -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fw-bold">Quarterly Assessment</span>
                                <span class="badge bg-warning rounded-pill">{{ $quarterlyAssessmentPercentage }}%</span>
                            </div>
                            <div class="position-relative">
                                <div class="progress rounded-pill" style="height: 10px;">
                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $quarterlyAssessmentPercentage }}%" 
                                        aria-valuenow="{{ $quarterlyAssessmentPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2 small text-muted">
                                <i class="fas fa-info-circle me-1"></i> Final exams and quarterly tests
                            </div>
                        </div>
                        
                        <!-- Subject Details -->
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="fw-bold mb-3">Subject Information</h6>
                            <div class="mb-2">
                                <span class="text-muted me-2">Subject Code:</span>
                                <span class="fw-medium">{{ $selectedSubject->code }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted me-2">Grade Level:</span>
                                <span class="fw-medium">{{ $selectedSubject->grade_level ?? 'Not specified' }}</span>
                            </div>
                            <div class="mb-2">
                                <span class="text-muted me-2">Term:</span>
                                <span class="fw-medium">{{ $terms[$selectedTerm] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Assessment Options -->
            <div class="col-md-8">
                <div class="card shadow-sm rounded-3 border-0">
                    <div class="card-header py-3 bg-white">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fas fa-clipboard-list me-2"></i> 
                            Record Assessments for {{ $selectedSubject->name }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Written Works Card -->
                            <div class="col-md-4">
                                <div class="card assessment-card shadow-sm bg-light-blue">
                                    <div class="card-body p-4">
                                        <div class="text-center mb-3">
                                            <div class="bg-primary bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                                <i class="fas fa-pen text-primary fa-2x"></i>
                                            </div>
                                            <h5 class="fw-bold">Written Works</h5>
                                        </div>
                                        <p class="text-muted small">Record scores for quizzes, homework, and written assessments</p>
                                        <div class="d-grid mt-3">
                                            <a href="{{ route('teacher.grades.assessment-setup', [
                                                'subject_id' => $selectedSubject->id, 
                                                'term' => $selectedTerm, 
                                                'grade_type' => 'written_work',
                                                'section_id' => $selectedSectionId ?? 1
                                            ]) }}" class="btn btn-primary"
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="bottom" 
                                               title="Create a new written assessment for the class">
                                                <i class="fas fa-plus-circle me-1"></i> Add Assessment
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Performance Tasks Card -->
                            <div class="col-md-4">
                                <div class="card assessment-card shadow-sm bg-light-green">
                                    <div class="card-body p-4">
                                        <div class="text-center mb-3">
                                            <div class="bg-success bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                                <i class="fas fa-project-diagram text-success fa-2x"></i>
                                            </div>
                                            <h5 class="fw-bold">Performance Tasks</h5>
                                        </div>
                                        <p class="text-muted small">Record scores for projects, presentations, and practical activities</p>
                                        <div class="d-grid mt-3">
                                            <a href="{{ route('teacher.grades.assessment-setup', [
                                                'subject_id' => $selectedSubject->id, 
                                                'term' => $selectedTerm, 
                                                'grade_type' => 'performance_task',
                                                'section_id' => $selectedSectionId ?? 1
                                            ]) }}" class="btn btn-success"
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="bottom" 
                                               title="Create a new performance task for the class">
                                                <i class="fas fa-plus-circle me-1"></i> Add Assessment
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Quarterly Assessment Card -->
                            <div class="col-md-4">
                                <div class="card assessment-card shadow-sm bg-light-yellow">
                                    <div class="card-body p-4">
                                        <div class="text-center mb-3">
                                            <div class="bg-warning bg-opacity-10 rounded-circle p-3 d-inline-block mb-2">
                                                <i class="fas fa-file-alt text-warning fa-2x"></i>
                                            </div>
                                            <h5 class="fw-bold">Quarterly Exam</h5>
                                        </div>
                                        <p class="text-muted small">Record scores for final exams and quarterly assessments</p>
                                        <div class="d-grid mt-3">
                                            <a href="{{ route('teacher.grades.assessment-setup', [
                                                'subject_id' => $selectedSubject->id, 
                                                'term' => $selectedTerm, 
                                                'grade_type' => 'quarterly',
                                                'section_id' => $selectedSectionId ?? 1
                                            ]) }}" class="btn btn-warning"
                                               data-bs-toggle="tooltip" 
                                               data-bs-placement="bottom" 
                                               title="Create a new quarterly assessment for the class">
                                                <i class="fas fa-plus-circle me-1"></i> Add Assessment
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Student Grades Table -->
        <div class="card shadow-sm rounded-3 border-0 mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                @if(isset($students[0]['view_all']) && $students[0]['view_all'])
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-table-columns me-2"></i> Comprehensive Grade Report - {{ $terms[$selectedTerm] }}
                    </h5>
                @else
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-users me-2"></i> {{ $sections->find($selectedSectionId)?->name }} - {{ $selectedSubject->name }} Grades
                    </h5>
                @endif
                
                <div>
                    @if(!isset($students[0]['view_all']) || !$students[0]['view_all'])
                    <a href="{{ route('teacher.grades.batch-create', [
                        'subject_id' => $selectedSubject->id,
                        'section_id' => $selectedSectionId,
                        'term' => $selectedTerm
                    ]) }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-list-alt me-1"></i> Batch Grade Entry
                    </a>
                    @endif
                    <button type="button" class="btn btn-outline-secondary btn-sm ms-2" id="printGradesBtn">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                @if(isset($students[0]['view_all']) && $students[0]['view_all'])
                    <!-- All Subjects View -->
                    <div class="alert alert-info mb-2">
                        <div class="d-flex align-items-center">
                            <div class="me-3"><i class="fas fa-info-circle fa-2x"></i></div>
                            <div>
                                <strong>Comprehensive Grade View</strong>
                                <p class="mb-0">You are viewing the combined grades for all subjects assigned to this section. Click on the <i class="fas fa-chart-line"></i> button to see detailed breakdowns for each student.</p>
                            </div>
                        </div>
                    </div>
                    <div class="card border-0 shadow-sm mb-3">
                        <div class="card-body p-2">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="compactViewToggle">
                                    <label class="form-check-label" for="compactViewToggle">
                                        <i class="fas fa-compress me-1"></i> Compact View
                                    </label>
                                </div>
                                <div>
                                    <span class="badge bg-light text-secondary border"><i class="fas fa-arrows-left-right me-1"></i> Scroll horizontally to view all subjects</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover grade-table mb-0" id="gradeTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40px; min-width: 40px;" class="text-center">#</th>
                                    <th style="width: 200px; min-width: 200px;">Student</th>
                                    @foreach($students[0]['subject_grades'] as $subjectId => $subjectGrade)
                                        <th class="text-center subject-column" style="min-width: 100px;">
                                            <div class="subject-name">{{ $subjectGrade['subject_name'] }}</div>
                                            <div class="subject-code small text-muted">{{ \App\Models\Subject::find($subjectId)->code ?? '' }}</div>
                                        </th>
                                    @endforeach
                                    <th class="text-center bg-primary bg-opacity-10 fw-bold" style="min-width: 120px;">Final Average</th>
                                    <th class="text-center" style="width: 100px; min-width: 100px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $index => $studentData)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary text-white me-2">
                                                    {{ strtoupper(substr($studentData['student']->first_name, 0, 1)) }}{{ strtoupper(substr($studentData['student']->last_name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold student-name">{{ $studentData['student']->last_name }}, {{ $studentData['student']->first_name }}</div>
                                                    <div class="small text-muted student-id">
                                                        #{{ $studentData['student']->student_id }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        @foreach($studentData['subject_grades'] as $subjectId => $subjectGrade)
                                            <td class="text-center subject-grade">
                                                @php
                                                    // Calculate grade for this subject
                                                    $writtenWorks = collect($subjectGrade['written_works']);
                                                    $performanceTasks = collect($subjectGrade['performance_tasks']);
                                                    $quarterlyAssessment = $subjectGrade['quarterly_assessment'];
                                                    
                                                    $writtenWorksAvg = $writtenWorks->count() > 0 ? 
                                                        $writtenWorks->average(function($grade) {
                                                            return ($grade->score / $grade->max_score) * 100;
                                                        }) : 0;
                                                    
                                                    $performanceTasksAvg = $performanceTasks->count() > 0 ? 
                                                        $performanceTasks->average(function($grade) {
                                                            return ($grade->score / $grade->max_score) * 100;
                                                        }) : 0;
                                                    
                                                    $quarterlyScore = $quarterlyAssessment ? 
                                                        ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                                    
                                                    // Get subject's grade configuration
                                                    $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                                    
                                                    $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                                    $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                                    $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                                    
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
                                                    
                                                    // Grade color class
                                                    $gradeClass = 'secondary';
                                                    if ($avgGrade >= 90) {
                                                        $gradeClass = 'success';
                                                    } elseif ($avgGrade >= 80) {
                                                        $gradeClass = 'primary';
                                                    } elseif ($avgGrade >= 70) {
                                                        $gradeClass = 'info';
                                                    } elseif ($avgGrade >= 60) {
                                                        $gradeClass = 'warning';
                                                    } elseif ($avgGrade > 0) {
                                                        $gradeClass = 'danger';
                                                    }
                                                @endphp
                                                
                                                @if($writtenWorks->count() > 0 || $performanceTasks->count() > 0 || $quarterlyAssessment)
                                                    <span class="badge bg-{{ $gradeClass }} grade-badge">{{ $avgGrade }}</span>
                                                @else
                                                    <span class="text-muted small">N/A</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-center bg-primary bg-opacity-10">
                                            @php
                                                // Calculate the average grade across all subjects
                                                $totalSubjectGrade = 0;
                                                $subjectCount = count($studentData['subject_grades']);
                                                $validSubjectCount = 0;
                                                
                                                foreach($studentData['subject_grades'] as $subjectId => $subjectGrade) {
                                                    $writtenWorks = collect($subjectGrade['written_works']);
                                                    $performanceTasks = collect($subjectGrade['performance_tasks']);
                                                    $quarterlyAssessment = $subjectGrade['quarterly_assessment'];
                                                    
                                                    $hasGrades = $writtenWorks->count() > 0 || $performanceTasks->count() > 0 || $quarterlyAssessment;
                                                    
                                                    if($hasGrades) {
                                                        $writtenWorksAvg = $writtenWorks->count() > 0 ? 
                                                            $writtenWorks->average(function($grade) {
                                                                return ($grade->score / $grade->max_score) * 100;
                                                            }) : 0;
                                                        
                                                        $performanceTasksAvg = $performanceTasks->count() > 0 ? 
                                                            $performanceTasks->average(function($grade) {
                                                                return ($grade->score / $grade->max_score) * 100;
                                                            }) : 0;
                                                        
                                                        $quarterlyScore = $quarterlyAssessment ? 
                                                            ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                                        
                                                        // Get subject's grade configuration
                                                        $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                                        
                                                        $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                                        $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                                        $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                                        
                                                        // Calculate weighted final grade for this subject
                                                        $subjectFinalGrade = 0;
                                                        if ($writtenWorksAvg > 0) {
                                                            $subjectFinalGrade += ($writtenWorksAvg * ($writtenWorkPercentage / 100));
                                                        }
                                                        if ($performanceTasksAvg > 0) {
                                                            $subjectFinalGrade += ($performanceTasksAvg * ($performanceTaskPercentage / 100));
                                                        }
                                                        if ($quarterlyScore > 0) {
                                                            $subjectFinalGrade += ($quarterlyScore * ($quarterlyAssessmentPercentage / 100));
                                                        }
                                                        
                                                        $totalSubjectGrade += $subjectFinalGrade;
                                                        $validSubjectCount++;
                                                    }
                                                }
                                                
                                                $finalAverage = $validSubjectCount > 0 ? $totalSubjectGrade / $validSubjectCount : 0;
                                                $finalAverage = round($finalAverage, 1);
                                                
                                                // Grade color class
                                                $avgGradeClass = 'secondary';
                                                if ($finalAverage >= 90) {
                                                    $avgGradeClass = 'success';
                                                } elseif ($finalAverage >= 80) {
                                                    $avgGradeClass = 'primary';
                                                } elseif ($finalAverage >= 70) {
                                                    $avgGradeClass = 'info';
                                                } elseif ($finalAverage >= 60) {
                                                    $avgGradeClass = 'warning';
                                                } elseif ($finalAverage > 0) {
                                                    $avgGradeClass = 'danger';
                                                }
                                            @endphp
                                            
                                            <span class="badge bg-{{ $avgGradeClass }} fs-5 px-3">{{ $finalAverage }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-info" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#wholeGradeModal{{ $studentData['student']->id }}">
                                                    <i class="fas fa-chart-line me-1"></i> Details
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <!-- Single Subject View (Original code) -->
                    @if(empty($students) || count($students) == 0)
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-users fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Students Found</h5>
                            <p class="text-muted">There are no students assigned to this section yet.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0 grade-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="ps-4">#</th>
                                        <th width="25%">Student</th>
                                        <th width="15%" class="text-center">
                                            <div>Written Works</div>
                                            <span class="badge bg-primary rounded-pill">{{ $writtenWorkPercentage }}%</span>
                                        </th>
                                        <th width="15%" class="text-center">
                                            <div>Performance Tasks</div>
                                            <span class="badge bg-success rounded-pill">{{ $performanceTaskPercentage }}%</span>
                                        </th>
                                        <th width="15%" class="text-center">
                                            <div>Quarterly</div>
                                            <span class="badge bg-warning rounded-pill">{{ $quarterlyAssessmentPercentage }}%</span>
                                        </th>
                                        <th width="10%" class="text-center">Final Grade</th>
                                        <th width="15%" class="text-center pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $studentData)
                                        @php
                                            $student = $studentData['student'];
                                            $isViewAll = isset($studentData['view_all']) && $studentData['view_all'];
                                            
                                            // Only get these directly if we're not in view_all mode
                                            if (!$isViewAll) {
                                                $writtenWorks = $studentData['written_works'] ?? [];
                                                $performanceTasks = $studentData['performance_tasks'] ?? [];
                                                $quarterlyAssessment = $studentData['quarterly_assessment'] ?? null;
                                            } else {
                                                // For view_all, these aren't directly accessible
                                                $writtenWorks = [];
                                                $performanceTasks = [];
                                                $quarterlyAssessment = null;
                                                // We'll access them through subject_grades instead
                                            }
                                            
                                            // Calculate grades
                                            $writtenWorksAvg = calculateAverage($writtenWorks);
                                            
                                            $performanceTasksAvg = calculateAverage($performanceTasks);
                                            
                                            $quarterlyScore = $quarterlyAssessment ? ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                            
                                            // Calculate final grade
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
                                            
                                            // Determine grade status and color
                                            $gradeColor = 'text-danger';
                                            $gradeStatus = 'Failed';
                                            
                                            if ($finalGrade >= 90) {
                                                $gradeColor = 'text-success';
                                                $gradeStatus = 'Excellent';
                                            } elseif ($finalGrade >= 85) {
                                                $gradeColor = 'text-primary';
                                                $gradeStatus = 'Very Good';
                                            } elseif ($finalGrade >= 80) {
                                                $gradeColor = 'text-info';
                                                $gradeStatus = 'Good';
                                            } elseif ($finalGrade >= 75) {
                                                $gradeColor = 'text-warning';
                                                $gradeStatus = 'Passed';
                                            }
                                        @endphp
                                        <tr>
                                            <td class="ps-4">{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                                        <div class="small text-muted">ID: {{ $student->student_id }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                @if(count($writtenWorks) > 0)
                                                    <div class="fw-bold">{{ number_format($writtenWorksAvg, 1) }}%</div>
                                                    <div class="small text-muted">{{ count($writtenWorks) }} assessments</div>
                                                @else
                                                    <span class="badge bg-light text-muted">No data</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(count($performanceTasks) > 0)
                                                    <div class="fw-bold">{{ number_format($performanceTasksAvg, 1) }}%</div>
                                                    <div class="small text-muted">{{ count($performanceTasks) }} tasks</div>
                                                @else
                                                    <span class="badge bg-light text-muted">No data</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if($quarterlyAssessment)
                                                    <div class="fw-bold">{{ number_format($quarterlyScore, 1) }}%</div>
                                                    <div class="small text-muted">
                                                        {{ $quarterlyAssessment->score }}/{{ $quarterlyAssessment->max_score }}
                                                    </div>
                                                @else
                                                    <span class="badge bg-light text-muted">No data</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="fw-bold fs-5 {{ $gradeColor }}">
                                                    {{ number_format($finalGrade, 1) }}%
                                                </div>
                                                <span class="badge {{ str_replace('text', 'bg', $gradeColor) }} bg-opacity-10 {{ $gradeColor }}">
                                                    {{ $gradeStatus }}
                                                </span>
                                            </td>
                                            <td class="text-center pe-4">
                                                <div class="btn-group">
                                                    <a href="#" class="btn btn-sm btn-outline-primary" 
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#studentDetailsModal{{ $student->id }}" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('teacher.grades.create', [
                                                            'student_id' => $student->id, 
                                                            'subject_id' => $selectedSubject->id, 
                                                            'term' => $selectedTerm
                                                        ]) }}" 
                                                       class="btn btn-sm btn-outline-success" 
                                                       title="Add Grade">
                                                        <i class="fas fa-plus"></i>
                                                    </a>
                                                    <button type="button" 
                                                            class="btn btn-sm btn-outline-info"
                                                            onclick="printStudentReport({{ $student->id }})"
                                                            title="Print Report">
                                                        <i class="fas fa-file-alt"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                @endif
            </div>
        </div>
        
        <!-- Grade Statistics Card -->
        <div class="card shadow-sm rounded-3 border-0 mb-4">
            <div class="card-header py-3 bg-white">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-chart-line me-2"></i> Class Performance Summary
                </h5>
            </div>
            <div class="card-body">
                @if(empty($students) || count($students) == 0)
                    <div class="text-center py-4">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No student data available to generate statistics.
                        </div>
                    </div>
                @else
                    @php
                        // Calculate class statistics
                        $totalStudents = count($students);
                        $passedStudents = 0;
                        $failedStudents = 0;
                        $totalClassGrade = 0;
                        $highestGrade = 0;
                        $lowestGrade = 100;
                        
                        // Check if we're in view_all mode
                        $isViewAll = isset($students[0]['view_all']) && $students[0]['view_all'];
                        
                        foreach ($students as $studentData) {
                            if ($isViewAll) {
                                // For view_all mode, calculate average across all subjects
                                $studentTotalGrade = 0;
                                $subjectCount = count($studentData['subject_grades']);
                                
                                if ($subjectCount > 0) {
                                    foreach ($studentData['subject_grades'] as $subjectId => $subjectGrade) {
                                        // Calculate grade for this subject
                                        $writtenWorks = collect($subjectGrade['written_works']);
                                        $performanceTasks = collect($subjectGrade['performance_tasks']);
                                        $quarterlyAssessment = $subjectGrade['quarterly_assessment'];
                                        
                                        $writtenWorksAvg = $writtenWorks->count() > 0 ? 
                                            $writtenWorks->average(function($grade) {
                                                return ($grade->score / $grade->max_score) * 100;
                                            }) : 0;
                                        
                                        $performanceTasksAvg = $performanceTasks->count() > 0 ? 
                                            $performanceTasks->average(function($grade) {
                                                return ($grade->score / $grade->max_score) * 100;
                                            }) : 0;
                                        
                                        $quarterlyScore = $quarterlyAssessment ? 
                                            ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                        
                                        // Get subject's grade configuration
                                        $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                        
                                        $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                        $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                        $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                        
                                        // Calculate weighted final grade for this subject
                                        $subjectFinalGrade = 0;
                                        if ($writtenWorksAvg > 0) {
                                            $subjectFinalGrade += ($writtenWorksAvg * ($writtenWorkPercentage / 100));
                                        }
                                        if ($performanceTasksAvg > 0) {
                                            $subjectFinalGrade += ($performanceTasksAvg * ($performanceTaskPercentage / 100));
                                        }
                                        if ($quarterlyScore > 0) {
                                            $subjectFinalGrade += ($quarterlyScore * ($quarterlyAssessmentPercentage / 100));
                                        }
                                        
                                        $studentTotalGrade += $subjectFinalGrade;
                                    }
                                    
                                    // Calculate student's average grade across all subjects
                                    $finalGrade = $studentTotalGrade / $subjectCount;
                                } else {
                                    $finalGrade = 0;
                                }
                            } else {
                                // For regular mode (single subject)
                                // Get individual components
                                $writtenWorks = $studentData['written_works'] ?? [];
                                $writtenWorksAvg = calculateAverage($writtenWorks);
                                
                                $performanceTasks = $studentData['performance_tasks'] ?? [];
                                $performanceTasksAvg = calculateAverage($performanceTasks);
                                
                                $quarterlyAssessment = $studentData['quarterly_assessment'] ?? null;
                                $quarterlyScore = $quarterlyAssessment ? ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                
                                // Calculate final grade
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
                            }
                            
                            // Update statistics
                            $totalClassGrade += $finalGrade;
                            
                            if ($finalGrade > $highestGrade) {
                                $highestGrade = $finalGrade;
                            }
                            
                            if ($finalGrade < $lowestGrade && $finalGrade > 0) {
                                $lowestGrade = $finalGrade;
                            }
                            
                            if ($finalGrade >= 75) {
                                $passedStudents++;
                            } else {
                                $failedStudents++;
                            }
                        }
                        
                        $classAverage = $totalStudents > 0 ? $totalClassGrade / $totalStudents : 0;
                        $passRate = $totalStudents > 0 ? ($passedStudents / $totalStudents) * 100 : 0;
                        
                        // For empty data
                        if ($lowestGrade == 100 && $highestGrade == 0) {
                            $lowestGrade = 0;
                        }
                    @endphp
                    
                    <div class="row g-4">
                        <div class="col-md-3">
                            <div class="card border-0 bg-primary bg-opacity-10 h-100 stat-card">
                                <div class="card-body text-center">
                                    <div class="display-5 text-primary mb-3">
                                        {{ number_format($classAverage, 1) }}%
                                    </div>
                                    <h6 class="fw-bold text-primary">Class Average</h6>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-0 bg-success bg-opacity-10 h-100 stat-card">
                                <div class="card-body text-center">
                                    <div class="display-5 text-success mb-3">
                                        {{ number_format($passRate, 1) }}%
                                    </div>
                                    <h6 class="fw-bold text-success">Pass Rate</h6>
                                    <div class="small text-muted">{{ $passedStudents }} of {{ $totalStudents }} students</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-0 bg-info bg-opacity-10 h-100 stat-card">
                                <div class="card-body text-center">
                                    <div class="display-5 text-info mb-3">
                                        {{ number_format($highestGrade, 1) }}%
                                    </div>
                                    <h6 class="fw-bold text-info">Highest Grade</h6>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-0 bg-warning bg-opacity-10 h-100 stat-card">
                                <div class="card-body text-center">
                                    <div class="display-5 text-warning mb-3">
                                        {{ number_format($lowestGrade, 1) }}%
                                    </div>
                                    <h6 class="fw-bold text-warning">Lowest Grade</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>

@php
function calculateAverage($grades) {
    if (count($grades) == 0) {
        return 0;
    }
    
    $total = 0;
    foreach ($grades as $grade) {
        $total += ($grade->score / $grade->max_score) * 100;
    }
    
    return $total / count($grades);
}
@endphp

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Initialize accordions with custom functionality
    document.querySelectorAll('.accordion-button').forEach(button => {
        button.addEventListener('click', function() {
            const target = document.querySelector(this.getAttribute('data-bs-target'));
            if (target) {
                if (target.classList.contains('show')) {
                    target.classList.remove('show');
                    this.classList.add('collapsed');
                    this.setAttribute('aria-expanded', 'false');
                } else {
                    // Close all other accordions in this group
                    const parent = target.getAttribute('data-bs-parent');
                    if (parent) {
                        document.querySelectorAll(parent + ' .accordion-collapse.show').forEach(item => {
                            if (item !== target) {
                                item.classList.remove('show');
                                const button = document.querySelector(`[data-bs-target="#${item.id}"]`);
                                if (button) {
                                    button.classList.add('collapsed');
                                    button.setAttribute('aria-expanded', 'false');
                                }
                            }
                        });
                    }
                    
                    target.classList.add('show');
                    this.classList.remove('collapsed');
                    this.setAttribute('aria-expanded', 'true');
                }
            }
        });
    });
    
    // Toggle compact view for grade table
    const compactViewToggle = document.getElementById('compactViewToggle');
    const gradeTable = document.getElementById('gradeTable');
    
    if (compactViewToggle && gradeTable) {
        compactViewToggle.addEventListener('change', function() {
            if (this.checked) {
                gradeTable.classList.add('compact-view');
                localStorage.setItem('gradeTableCompactView', 'true');
            } else {
                gradeTable.classList.remove('compact-view');
                localStorage.setItem('gradeTableCompactView', 'false');
            }
        });
        
        // Load saved preference
        if (localStorage.getItem('gradeTableCompactView') === 'true') {
            compactViewToggle.checked = true;
            gradeTable.classList.add('compact-view');
        }
    }
    
    // Auto-submit the form when changing filters
    $('#subject_id, #section_id, #term, #view_all').on('change', function() {
        $('#filterForm').submit();
    });
    
    // Subject-section mappings
    const subjectSections = {
        @foreach($subjects as $subject)
            {{ $subject->id }}: [
                @php
                    // Get sections for this subject
                    $subjectSectionIds = DB::table('section_subject')
                        ->where('subject_id', $subject->id)
                        ->where('teacher_id', Auth::id())
                        ->pluck('section_id')
                        ->toArray();
                @endphp
                @foreach($subjectSectionIds as $sectionId)
                    {{ $sectionId }},
                @endforeach
            ],
        @endforeach
    };
    
    // Function to filter sections based on selected subject
    function filterSectionsBySubject(subjectId) {
        const sectionDropdown = document.getElementById('section_id');
        if (!sectionDropdown) return;
        
        const availableSections = subjectSections[subjectId] || [];
        
        // Hide all options first
        Array.from(sectionDropdown.options).forEach(option => {
            const sectionId = parseInt(option.value);
            if (availableSections.includes(sectionId)) {
                option.style.display = '';
            } else {
                option.style.display = 'none';
            }
        });
        
        // If current selection is not valid, select first available
        const currentSelection = parseInt(sectionDropdown.value);
        if (!availableSections.includes(currentSelection) && availableSections.length > 0) {
            sectionDropdown.value = availableSections[0];
        }
    }
    
    // Filter sections on load
    const subjectSelect = document.getElementById('subject_id');
    if (subjectSelect) {
        filterSectionsBySubject(subjectSelect.value);
        
        // Auto-submit form when subject changes
        subjectSelect.addEventListener('change', function() {
            filterSectionsBySubject(this.value);
            document.getElementById('filterForm').submit();
        });
    }
    
    // Auto-submit form when section or term changes
    document.getElementById('section_id')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    document.getElementById('term')?.addEventListener('change', function() {
        document.getElementById('filterForm').submit();
    });
    
    // Print all grades
    document.getElementById('printGrades')?.addEventListener('click', function() {
        window.print();
    });
    
    // Export functionality placeholder
    document.getElementById('exportGrades')?.addEventListener('click', function() {
        alert('Export functionality will be available soon!');
    });
});

// Function to print individual student report
function printStudentReport(studentId) {
    // Create a popup window with the student's report
    let url = "{{ route('teacher.grades.show', ['grade' => 'STUDENT_ID', 'subject_id' => $selectedSubject->id ?? 0, 'term' => $selectedTerm ?? 'Q1']) }}";
    url = url.replace('STUDENT_ID', studentId);
    window.open(url, 'studentReport', 'width=800,height=600');
}
</script>
@endpush

<!-- Student Details Modals -->
@if(isset($students) && count($students) > 0)
    @foreach($students as $studentData)
        @php
            $student = $studentData['student'];
            $isViewAll = isset($studentData['view_all']) && $studentData['view_all'];
            
            // Only get these directly if we're not in view_all mode
            if (!$isViewAll) {
                $writtenWorks = $studentData['written_works'] ?? [];
                $performanceTasks = $studentData['performance_tasks'] ?? [];
                $quarterlyAssessment = $studentData['quarterly_assessment'] ?? null;
            } else {
                // For view_all, these aren't directly accessible
                $writtenWorks = [];
                $performanceTasks = [];
                $quarterlyAssessment = null;
                // We'll access them through subject_grades instead
            }
        @endphp

        <div class="modal fade" id="studentDetailsModal{{ $student->id }}" tabindex="-1" aria-labelledby="studentDetailsModalLabel{{ $student->id }}" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="studentDetailsModalLabel{{ $student->id }}">
                            <i class="fas fa-user-graduate me-2"></i> {{ $student->first_name }} {{ $student->last_name }}'s Grades
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Student Info -->
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                            <div>
                                <h5 class="mb-1">{{ $student->first_name }} {{ $student->last_name }}</h5>
                                <div class="text-muted">
                                    ID: {{ $student->student_id }} | 
                                    Section: {{ $student->section->name ?? 'Unassigned' }} |
                                    Grade {{ $student->section->grade_level ?? 'Unassigned' }}
                                </div>
                            </div>
                        </div>

                        <!-- Grades by Type -->
                        <ul class="nav nav-tabs mb-3" id="gradesTab{{ $student->id }}" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="writtenWorks-tab{{ $student->id }}" data-bs-toggle="tab" 
                                    data-bs-target="#writtenWorks{{ $student->id }}" type="button" role="tab" aria-selected="true">
                                    Written Works
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="performanceTasks-tab{{ $student->id }}" data-bs-toggle="tab" 
                                    data-bs-target="#performanceTasks{{ $student->id }}" type="button" role="tab" aria-selected="false">
                                    Performance Tasks
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="quarterly-tab{{ $student->id }}" data-bs-toggle="tab" 
                                    data-bs-target="#quarterly{{ $student->id }}" type="button" role="tab" aria-selected="false">
                                    Quarterly Assessment
                                </button>
                            </li>
                        </ul>
                        
                        <div class="tab-content" id="gradesTabContent{{ $student->id }}">
                            <!-- Written Works Tab -->
                            <div class="tab-pane fade show active" id="writtenWorks{{ $student->id }}" role="tabpanel">
                                @if(count($writtenWorks) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Assessment</th>
                                                    <th class="text-center">Score</th>
                                                    <th>Date</th>
                                                    <th>Remarks</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($writtenWorks as $grade)
                                                    <tr>
                                                        <td>{{ $grade->assessment_name }}</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-primary">{{ $grade->score }}/{{ $grade->max_score }}</span>
                                                            <div class="small text-muted">{{ number_format(($grade->score / $grade->max_score) * 100, 1) }}%</div>
                                                        </td>
                                                        <td>{{ $grade->created_at->format('M d, Y') }}</td>
                                                        <td>{{ $grade->remarks ?? 'No remarks' }}</td>
                                                        <td class="text-end">
                                                            <a href="{{ route('teacher.grades.edit', $grade->id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="avatar bg-light rounded-circle p-3 mx-auto mb-3">
                                            <i class="fas fa-file-alt text-muted fa-2x"></i>
                                        </div>
                                        <h6 class="text-muted">No Written Works Recorded</h6>
                                        <p class="small text-muted mb-3">Add assessments to track student's performance</p>
                                        <a href="{{ route('teacher.grades.create', [
                                            'student_id' => $student->id, 
                                            'subject_id' => $selectedSubject->id, 
                                            'term' => $selectedTerm,
                                            'grade_type' => 'written_work'
                                        ]) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-plus-circle me-1"></i> Add Assessment
                                        </a>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Performance Tasks Tab -->
                            <div class="tab-pane fade" id="performanceTasks{{ $student->id }}" role="tabpanel">
                                @if(count($performanceTasks) > 0)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Assessment</th>
                                                    <th class="text-center">Score</th>
                                                    <th>Date</th>
                                                    <th>Remarks</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($performanceTasks as $grade)
                                                    <tr>
                                                        <td>{{ $grade->assessment_name }}</td>
                                                        <td class="text-center">
                                                            <span class="badge bg-success">{{ $grade->score }}/{{ $grade->max_score }}</span>
                                                            <div class="small text-muted">{{ number_format(($grade->score / $grade->max_score) * 100, 1) }}%</div>
                                                        </td>
                                                        <td>{{ $grade->created_at->format('M d, Y') }}</td>
                                                        <td>{{ $grade->remarks ?? 'No remarks' }}</td>
                                                        <td class="text-end">
                                                            <a href="{{ route('teacher.grades.edit', $grade->id) }}" class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-edit"></i> Edit
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="avatar bg-light rounded-circle p-3 mx-auto mb-3">
                                            <i class="fas fa-project-diagram text-muted fa-2x"></i>
                                        </div>
                                        <h6 class="text-muted">No Performance Tasks Recorded</h6>
                                        <p class="small text-muted mb-3">Add performance tasks to track student's practical skills</p>
                                        <a href="{{ route('teacher.grades.create', [
                                            'student_id' => $student->id, 
                                            'subject_id' => $selectedSubject->id, 
                                            'term' => $selectedTerm,
                                            'grade_type' => 'performance_task'
                                        ]) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus-circle me-1"></i> Add Task
                                        </a>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Quarterly Assessment Tab -->
                            <div class="tab-pane fade" id="quarterly{{ $student->id }}" role="tabpanel">
                                @if($quarterlyAssessment)
                                    <div class="table-responsive">
                                        <table class="table table-hover align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Assessment</th>
                                                    <th class="text-center">Score</th>
                                                    <th>Date</th>
                                                    <th>Remarks</th>
                                                    <th class="text-end">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $quarterlyAssessment->assessment_name }}</td>
                                                    <td class="text-center">
                                                        <span class="badge bg-warning">{{ $quarterlyAssessment->score }}/{{ $quarterlyAssessment->max_score }}</span>
                                                        <div class="small text-muted">{{ number_format(($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100, 1) }}%</div>
                                                    </td>
                                                    <td>{{ $quarterlyAssessment->created_at->format('M d, Y') }}</td>
                                                    <td>{{ $quarterlyAssessment->remarks ?? 'No remarks' }}</td>
                                                    <td class="text-end">
                                                        <a href="{{ route('teacher.grades.edit', $quarterlyAssessment->id) }}" class="btn btn-sm btn-outline-warning">
                                                            <i class="fas fa-edit"></i> Edit
                                                        </a>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-center py-4">
                                        <div class="avatar bg-light rounded-circle p-3 mx-auto mb-3">
                                            <i class="fas fa-file-alt text-muted fa-2x"></i>
                                        </div>
                                        <h6 class="text-muted">No Quarterly Assessment Recorded</h6>
                                        <p class="small text-muted mb-3">Add quarterly assessment to evaluate overall performance</p>
                                        <a href="{{ route('teacher.grades.create', [
                                            'student_id' => $student->id, 
                                            'subject_id' => $selectedSubject->id, 
                                            'term' => $selectedTerm,
                                            'grade_type' => 'quarterly'
                                        ]) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-plus-circle me-1"></i> Add Assessment
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <a href="{{ route('teacher.grades.create', [
                                'student_id' => $student->id, 
                                'subject_id' => $selectedSubject->id, 
                                'term' => $selectedTerm
                            ]) }}" 
                           class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Add New Grade
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

<!-- Whole Grade Detail Modals -->
@if(isset($students) && count($students) > 0 && isset($students[0]['view_all']) && $students[0]['view_all'])
    @foreach($students as $studentData)
        <div class="modal fade" id="wholeGradeModal{{ $studentData['student']->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-user-graduate me-2"></i> {{ $studentData['student']->first_name }} {{ $studentData['student']->last_name }} - Comprehensive Grade Report
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Final Average Summary -->
                        <div class="card mb-4 bg-light border-0 shadow-sm">
                            <div class="card-body">
                                @php
                                    // Calculate the average grade across all subjects
                                    $totalSubjectGrade = 0;
                                    $subjectCount = count($studentData['subject_grades']);
                                    $validSubjectCount = 0;
                                    $calculatedSubjectFinalGrades = [];
                                    
                                    foreach($studentData['subject_grades'] as $subjectId => $subjectGrade) {
                                        $writtenWorks = collect($subjectGrade['written_works']);
                                        $performanceTasks = collect($subjectGrade['performance_tasks']);
                                        $quarterlyAssessment = $subjectGrade['quarterly_assessment'];
                                        
                                        $writtenWorksAvg = $writtenWorks->count() > 0 ? 
                                            $writtenWorks->average(function($grade) {
                                                return ($grade->score / $grade->max_score) * 100;
                                            }) : 0;
                                        
                                        $performanceTasksAvg = $performanceTasks->count() > 0 ? 
                                            $performanceTasks->average(function($grade) {
                                                return ($grade->score / $grade->max_score) * 100;
                                            }) : 0;
                                        
                                        $quarterlyScore = $quarterlyAssessment ? 
                                            ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                        
                                        // Get subject's grade configuration
                                        $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                        
                                        $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                        $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                        $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                        
                                        // Calculate weighted final grade for this subject
                                        $subjectFinalGrade = 0;
                                        $hasAnyComponents = false;
                                        
                                        if ($writtenWorksAvg > 0) {
                                            $subjectFinalGrade += ($writtenWorksAvg * ($writtenWorkPercentage / 100));
                                            $hasAnyComponents = true;
                                        }
                                        if ($performanceTasksAvg > 0) {
                                            $subjectFinalGrade += ($performanceTasksAvg * ($performanceTaskPercentage / 100));
                                            $hasAnyComponents = true;
                                        }
                                        if ($quarterlyScore > 0) {
                                            $subjectFinalGrade += ($quarterlyScore * ($quarterlyAssessmentPercentage / 100));
                                            $hasAnyComponents = true;
                                        }
                                        
                                        if ($hasAnyComponents) {
                                            $totalSubjectGrade += $subjectFinalGrade;
                                            $validSubjectCount++;
                                            $calculatedSubjectFinalGrades[$subjectId] = round($subjectFinalGrade, 1);
                                        }
                                    }
                                    
                                    $finalAverage = $validSubjectCount > 0 ? $totalSubjectGrade / $validSubjectCount : 0;
                                    $finalAverage = round($finalAverage, 1);
                                    
                                    // Grade color class and status
                                    $avgGradeClass = 'secondary';
                                    $gradeStatus = 'Not Graded';
                                    
                                    if ($finalAverage >= 90) {
                                        $avgGradeClass = 'success';
                                        $gradeStatus = 'Excellent';
                                    } elseif ($finalAverage >= 85) {
                                        $avgGradeClass = 'primary';
                                        $gradeStatus = 'Very Good';
                                    } elseif ($finalAverage >= 80) {
                                        $avgGradeClass = 'info';
                                        $gradeStatus = 'Good';
                                    } elseif ($finalAverage >= 75) {
                                        $avgGradeClass = 'warning';
                                        $gradeStatus = 'Passed';
                                    } elseif ($finalAverage > 0) {
                                        $avgGradeClass = 'danger';
                                        $gradeStatus = 'Failed';
                                    }
                                @endphp
                                
                                <div class="row align-items-center">
                                    <div class="col-md-5">
                                        <h5 class="mb-1">Overall Academic Performance</h5>
                                        <p class="text-muted mb-0">{{ $terms[$selectedTerm] }} | Final average across {{ $validSubjectCount }} subjects</p>
                                    </div>
                                    <div class="col-md-4 text-center">
                                        <div class="bg-{{ $avgGradeClass }} bg-opacity-10 py-3 rounded-3 mb-2">
                                            <div class="display-4 fw-bold text-{{ $avgGradeClass }}">{{ $finalAverage }}%</div>
                                            <span class="badge bg-{{ $avgGradeClass }} px-3 py-2 mt-1">{{ $gradeStatus }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="small mb-1"><i class="fas fa-book me-2 text-muted"></i> <span class="text-muted">Total Subjects:</span> <span class="fw-bold">{{ $subjectCount }}</span></div>
                                        <div class="small mb-1"><i class="fas fa-check-circle me-2 text-muted"></i> <span class="text-muted">Graded Subjects:</span> <span class="fw-bold">{{ $validSubjectCount }}</span></div>
                                        <div class="small"><i class="fas fa-calendar-alt me-2 text-muted"></i> <span class="text-muted">Academic Term:</span> <span class="fw-bold">{{ $terms[$selectedTerm] }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Grade Distribution Chart -->
                        <div class="card mb-4 border-0 shadow-sm">
                            <div class="card-header bg-white">
                                <h6 class="mb-0 fw-bold">Grade Distribution by Subject</h6>
                            </div>
                            <div class="card-body pb-0">
                                <div class="row">
                                    @foreach($studentData['subject_grades'] as $subjectId => $subjectGrade)
                                        @php
                                            // Calculate all values for this subject
                                            $writtenWorks = collect($subjectGrade['written_works']);
                                            $performanceTasks = collect($subjectGrade['performance_tasks']);
                                            $quarterlyAssessment = $subjectGrade['quarterly_assessment'];
                                            
                                            $writtenWorksAvg = $writtenWorks->count() > 0 ? 
                                                $writtenWorks->average(function($grade) {
                                                    return ($grade->score / $grade->max_score) * 100;
                                                }) : 0;
                                            
                                            $performanceTasksAvg = $performanceTasks->count() > 0 ? 
                                                $performanceTasks->average(function($grade) {
                                                    return ($grade->score / $grade->max_score) * 100;
                                                }) : 0;
                                            
                                            $quarterlyScore = $quarterlyAssessment ? 
                                                ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                            
                                            // Get subject's grade configuration
                                            $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                            
                                            $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                            $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                            $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                            
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
                                            
                                            // Grade color class
                                            $gradeClass = 'secondary';
                                            if ($avgGrade >= 90) {
                                                $gradeClass = 'success';
                                            } elseif ($avgGrade >= 80) {
                                                $gradeClass = 'primary';
                                            } elseif ($avgGrade >= 70) {
                                                $gradeClass = 'info';
                                            } elseif ($avgGrade >= 60) {
                                                $gradeClass = 'warning';
                                            } elseif ($avgGrade > 0) {
                                                $gradeClass = 'danger';
                                            }

                                            $hasGrades = $writtenWorks->count() > 0 || $performanceTasks->count() > 0 || $quarterlyAssessment;
                                        @endphp
                                        
                                        <div class="col-md-6 mb-4">
                                            <div class="d-flex align-items-center mb-2">
                                                <h6 class="mb-0 flex-grow-1">{{ $subjectGrade['subject_name'] }}</h6>
                                                @if($hasGrades)
                                                    <span class="badge bg-{{ $gradeClass }} px-2">{{ $avgGrade }}%</span>
                                                @else
                                                    <span class="badge bg-secondary px-2">Not Graded</span>
                                                @endif
                                            </div>
                                            
                                            <div class="mb-3 position-relative">
                                                <div class="d-flex justify-content-between small mb-1">
                                                    <span>Written ({{ $writtenWorkPercentage }}%)</span>
                                                    <span>Performance ({{ $performanceTaskPercentage }}%)</span>
                                                    <span>Quarterly ({{ $quarterlyAssessmentPercentage }}%)</span>
                                                </div>
                                                <div class="progress rounded-0" style="height: 24px;">
                                                    @if($hasGrades)
                                                        <div class="progress-bar bg-primary" role="progressbar" 
                                                            style="width: {{ $writtenWorksAvg > 0 ? ($writtenWorksAvg * $writtenWorkPercentage) / 100 : 0 }}%;" 
                                                            aria-valuenow="{{ $writtenWorksAvg }}" aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($writtenWorksAvg, 0) }}%
                                                        </div>
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                            style="width: {{ $performanceTasksAvg > 0 ? ($performanceTasksAvg * $performanceTaskPercentage) / 100 : 0 }}%;" 
                                                            aria-valuenow="{{ $performanceTasksAvg }}" aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($performanceTasksAvg, 0) }}%
                                                        </div>
                                                        <div class="progress-bar bg-warning" role="progressbar" 
                                                            style="width: {{ $quarterlyScore > 0 ? ($quarterlyScore * $quarterlyAssessmentPercentage) / 100 : 0 }}%;" 
                                                            aria-valuenow="{{ $quarterlyScore }}" aria-valuemin="0" aria-valuemax="100">
                                                            {{ number_format($quarterlyScore, 0) }}%
                                                        </div>
                                                    @else
                                                        <div class="progress-bar bg-secondary" role="progressbar" style="width: 100%">No grades recorded</div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Subject Details -->
                        <div class="accordion" id="subjectAccordion{{ $studentData['student']->id }}">
                            @foreach($studentData['subject_grades'] as $subjectId => $subjectGrade)
                                @php
                                    // Calculate all values for this subject
                                    $writtenWorks = collect($subjectGrade['written_works']);
                                    $performanceTasks = collect($subjectGrade['performance_tasks']);
                                    $quarterlyAssessment = $subjectGrade['quarterly_assessment'];
                                    
                                    $writtenWorksAvg = $writtenWorks->count() > 0 ? 
                                        $writtenWorks->average(function($grade) {
                                            return ($grade->score / $grade->max_score) * 100;
                                        }) : 0;
                                    
                                    $performanceTasksAvg = $performanceTasks->count() > 0 ? 
                                        $performanceTasks->average(function($grade) {
                                            return ($grade->score / $grade->max_score) * 100;
                                        }) : 0;
                                    
                                    $quarterlyScore = $quarterlyAssessment ? 
                                        ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                    
                                    // Get subject's grade configuration
                                    $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                    
                                    $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                    $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                    $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                    
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
                                    
                                    // Grade color class
                                    $gradeClass = 'secondary';
                                    if ($avgGrade >= 90) {
                                        $gradeClass = 'success';
                                    } elseif ($avgGrade >= 80) {
                                        $gradeClass = 'primary';
                                    } elseif ($avgGrade >= 70) {
                                        $gradeClass = 'info';
                                    } elseif ($avgGrade >= 60) {
                                        $gradeClass = 'warning';
                                    } elseif ($avgGrade > 0) {
                                        $gradeClass = 'danger';
                                    }

                                    $hasGrades = $writtenWorks->count() > 0 || $performanceTasks->count() > 0 || $quarterlyAssessment;
                                @endphp
                                
                                <div class="accordion-item border mb-3 rounded shadow-sm">
                                    <h2 class="accordion-header" id="heading{{ $subjectId }}">
                                        <button class="accordion-button collapsed" type="button" 
                                                data-bs-toggle="collapse" 
                                                data-bs-target="#collapse{{ $subjectId }}{{ $studentData['student']->id }}" 
                                                aria-expanded="false" 
                                                aria-controls="collapse{{ $subjectId }}{{ $studentData['student']->id }}">
                                            <div class="d-flex w-100 justify-content-between align-items-center">
                                                <span class="fw-bold">{{ $subjectGrade['subject_name'] }}</span>
                                                @if($hasGrades)
                                                    <span class="badge bg-{{ $gradeClass }} ms-2">{{ $avgGrade }}%</span>
                                                @else
                                                    <span class="badge bg-secondary ms-2">Not Graded</span>
                                                @endif
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $subjectId }}{{ $studentData['student']->id }}" class="accordion-collapse collapse" 
                                         aria-labelledby="heading{{ $subjectId }}" 
                                         data-bs-parent="#subjectAccordion{{ $studentData['student']->id }}">
                                        <div class="accordion-body">
                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <div class="card h-100 shadow-sm border-primary border-opacity-25">
                                                        <div class="card-header bg-primary bg-opacity-10 py-2">
                                                            <h6 class="mb-0 text-primary">Written Works</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="text-center mb-3">
                                                                <span class="h3">{{ number_format($writtenWorksAvg, 1) }}%</span>
                                                                <div class="small text-muted">{{ $writtenWorkPercentage }}% of final grade</div>
                                                            </div>
                                                            <div class="table-responsive">
                                                                <table class="table table-sm">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Assessment</th>
                                                                            <th class="text-end">Score</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if($writtenWorks->count() > 0)
                                                                            @foreach($writtenWorks as $grade)
                                                                                <tr>
                                                                                    <td>{{ $grade->assessment_name }}</td>
                                                                                    <td class="text-end">
                                                                                        {{ $grade->score }}/{{ $grade->max_score }}
                                                                                        <div class="small text-muted">{{ number_format(($grade->score / $grade->max_score) * 100, 1) }}%</div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="2" class="text-center text-muted">No written works recorded</td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="card h-100 shadow-sm border-success border-opacity-25">
                                                        <div class="card-header bg-success bg-opacity-10 py-2">
                                                            <h6 class="mb-0 text-success">Performance Tasks</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="text-center mb-3">
                                                                <span class="h3">{{ number_format($performanceTasksAvg, 1) }}%</span>
                                                                <div class="small text-muted">{{ $performanceTaskPercentage }}% of final grade</div>
                                                            </div>
                                                            <div class="table-responsive">
                                                                <table class="table table-sm">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Assessment</th>
                                                                            <th class="text-end">Score</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if($performanceTasks->count() > 0)
                                                                            @foreach($performanceTasks as $grade)
                                                                                <tr>
                                                                                    <td>{{ $grade->assessment_name }}</td>
                                                                                    <td class="text-end">
                                                                                        {{ $grade->score }}/{{ $grade->max_score }}
                                                                                        <div class="small text-muted">{{ number_format(($grade->score / $grade->max_score) * 100, 1) }}%</div>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="2" class="text-center text-muted">No performance tasks recorded</td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <div class="card h-100 shadow-sm border-warning border-opacity-25">
                                                        <div class="card-header bg-warning bg-opacity-10 py-2">
                                                            <h6 class="mb-0 text-warning">Quarterly Assessment</h6>
                                                        </div>
                                                        <div class="card-body">
                                                            <div class="text-center mb-3">
                                                                <span class="h3">{{ number_format($quarterlyScore, 1) }}%</span>
                                                                <div class="small text-muted">{{ $quarterlyAssessmentPercentage }}% of final grade</div>
                                                            </div>
                                                            <div class="table-responsive">
                                                                <table class="table table-sm">
                                                                    <thead class="table-light">
                                                                        <tr>
                                                                            <th>Assessment</th>
                                                                            <th class="text-end">Score</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        @if($quarterlyAssessment)
                                                                            <tr>
                                                                                <td>{{ $quarterlyAssessment->assessment_name }}</td>
                                                                                <td class="text-end">
                                                                                    {{ $quarterlyAssessment->score }}/{{ $quarterlyAssessment->max_score }}
                                                                                    <div class="small text-muted">{{ number_format(($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100, 1) }}%</div>
                                                                                </td>
                                                                            </tr>
                                                                        @else
                                                                            <tr>
                                                                                <td colspan="2" class="text-center text-muted">No quarterly assessment recorded</td>
                                                                            </tr>
                                                                        @endif
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($hasGrades)
                                                <div class="alert alert-info mt-3 mb-0">
                                                    <div class="d-flex align-items-center">
                                                        <div class="me-3"><i class="fas fa-calculator text-primary"></i></div>
                                                        <div>
                                                            <strong>Final Grade Calculation</strong>
                                                            <p class="mb-0">
                                                                (Written {{ number_format($writtenWorksAvg, 1) }}% × {{ $writtenWorkPercentage }}%) + 
                                                                (Performance {{ number_format($performanceTasksAvg, 1) }}% × {{ $performanceTaskPercentage }}%) + 
                                                                (Quarterly {{ number_format($quarterlyScore, 1) }}% × {{ $quarterlyAssessmentPercentage }}%) = 
                                                                <strong>{{ $avgGrade }}%</strong>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary print-detail" data-student-id="{{ $studentData['student']->id }}">
                            <i class="fas fa-print me-1"></i> Print Report
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

@endsection 