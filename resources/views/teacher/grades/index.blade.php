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
    
    /* MAPEH Components Display */
    .mapeh-components {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        justify-content: center;
    }
    .mapeh-components .badge {
        font-size: 10px;
        padding: 3px 5px;
        white-space: nowrap;
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
    
    /* New styles for transmutation tables */
    .transmutation-info {
        font-size: 0.8rem;
        color: #6c757d;
    }
    
    /* MAPEH Component Selector */
    .mapeh-selector {
        max-width: 300px;
        margin-bottom: 15px;
    }
    
    /* Component grade highlight */
    .component-grade-highlight {
        font-weight: bold;
        padding: 3px 5px;
        border-radius: 4px;
    }
    
    .music-highlight {
        background-color: rgba(13, 110, 253, 0.15);
        color: #0d6efd;
    }
    
    .arts-highlight {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
    }
    
    .pe-highlight {
        background-color: rgba(25, 135, 84, 0.15);
        color: #198754;
    }
    
    .health-highlight {
        background-color: rgba(255, 193, 7, 0.15);
        color: #ffc107;
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
                <form method="GET" action="{{ route('teacher.grades.index') }}" id="filterForm" class="row g-3" onsubmit="return validateFilterForm()">
                    <!-- Subject Filter -->
                    <div class="col-md-3">
                        <label for="subject_id" class="form-label fw-bold">
                            <i class="fas fa-book me-1 text-primary"></i> Subject
                        </label>
                        <select class="form-select shadow-sm" id="subject_id" name="subject_id">
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ (isset($selectedSubject) && $selectedSubject->id == $subject->id) ? 'selected' : '' }}>
                                    {{ $subject->name }} - {{ $subject->code }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Select the subject to view grades for</small>
                    </div>
                    
                    <!-- Section Filter -->
                    <div class="col-md-3">
                        <label for="section_id" class="form-label fw-bold">
                            <i class="fas fa-users me-1 text-success"></i> Section
                        </label>
                        <select class="form-select shadow-sm" id="section_id" name="section_id">
                            @foreach($sections as $section)
                                @php
                                    // Get the subjects assigned to this section for this teacher
                                    $sectionSubjectIds = DB::table('section_subject')
                                        ->where('section_id', $section->id)
                                        ->where('teacher_id', Auth::id())
                                        ->pluck('subject_id')
                                        ->toArray();
                                        
                                    // Add adviser's sections as well
                                    $isAdviser = $section->adviser_id == Auth::id();
                                    
                                    // Create a data attribute with all subject IDs for this section
                                    $subjectDataAttr = implode(',', $sectionSubjectIds);
                                @endphp
                                <option value="{{ $section->id }}" 
                                    {{ $selectedSectionId == $section->id ? 'selected' : '' }}
                                    data-subjects="{{ $subjectDataAttr }}"
                                    data-is-adviser="{{ $isAdviser ? 'true' : 'false' }}">
                                    {{ $section->name }} (Grade {{ $section->grade_level }})
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Select the section to display</small>
                    </div>
                    
                    <!-- Academic Term Filter -->
                    <div class="col-md-2">
                        <label for="term" class="form-label fw-bold">
                            <i class="fas fa-calendar-alt me-1 text-warning"></i> Term
                        </label>
                        <select class="form-select shadow-sm" id="term" name="term">
                            @foreach($terms as $key => $term)
                                <option value="{{ $key }}" {{ $selectedTerm == $key ? 'selected' : '' }}>
                                    {{ $term }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted d-block mt-1">Select grading period</small>
                    </div>
                    
                    <!-- Transmutation Table Filter -->
                    <div class="col-md-4">
                        <label for="transmutation_table" class="form-label fw-bold d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-table me-1 text-info"></i> Transmutation Table
                            </div>
                        </label>
                        <div class="d-flex align-items-center">
                            <select class="form-select shadow-sm flex-grow-1" id="transmutation_table" 
                                    {{ session('locked_transmutation_table') && session('locked_transmutation_table_id') ? 'disabled' : '' }}
                                    onchange="updateHiddenTransmutationInput(this.value)">
                                <option value="1" {{ request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1)) == 1 ? 'selected' : '' }}>
                                    Table 1: DepEd Transmutation Table
                                </option>
                                <option value="2" {{ request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1)) == 2 ? 'selected' : '' }}>
                                    Table 2: Grades 1-10 & Non-Core TVL
                                </option>
                                <option value="3" {{ request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1)) == 3 ? 'selected' : '' }}>
                                    Table 3: SHS Core & Work Immersion
                                </option>
                                <option value="4" {{ request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1)) == 4 ? 'selected' : '' }}>
                                    Table 4: All other SHS Subjects
                                </option>
                            </select>
                            <div class="ms-2">
                                <div class="form-check form-switch mb-0">
                                    <input class="form-check-input" type="checkbox" id="lock_table" name="locked_transmutation_table" value="true" 
                                        {{ session('locked_transmutation_table') ? 'checked' : '' }}
                                        data-bs-toggle="tooltip" data-bs-placement="top" 
                                        title="Lock this transmutation table for consistent grading">
                                    <label class="form-check-label" for="lock_table">
                                        <i class="fas fa-lock text-secondary"></i><span class="visually-hidden">Lock</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <!-- Always include a hidden input with the transmutation table value -->
                        <input type="hidden" id="selected_transmutation_table" name="transmutation_table" value="{{ request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1)) }}">
                        <div class="d-flex justify-content-between align-items-center mt-1">
                            <small class="text-muted">Initial grade will be transmuted based on selected table</small>
                            @if(session('locked_transmutation_table'))
                                <span class="badge bg-secondary">
                                    <i class="fas fa-lock me-1"></i> Locked
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <!-- View Button -->
                    <div class="col-md-12 text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i> View Grades
                        </button>
                    </div>
                    
                    <!-- Comprehensive Grade View Toggle -->
                    <div class="col-12 mt-3">
                        <div class="card border-0 shadow-sm {{ request('view_all') == 'true' ? 'bg-primary bg-opacity-10' : 'bg-light' }}">
                            <div class="card-body d-flex align-items-center">
                                <div class="form-check form-switch me-3">
                                    <input class="form-check-input" type="checkbox" id="view_all" name="view_all" value="true" 
                                           {{ request('view_all') == 'true' ? 'checked' : '' }}
                                           data-bs-toggle="tooltip" data-bs-placement="right" 
                                           title="Show comprehensive view of all subjects and grades for each student">
                                    <label class="form-check-label fw-bold" for="view_all">
                                        <i class="fas fa-table-columns me-1"></i> Comprehensive Grade View
                                    </label>
                                </div>
                                <p class="text-muted small mb-0">
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
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-primary">
                    <i class="fas fa-user-graduate me-2"></i> Student Grades
                    </h5>
                <div class="d-flex">
                    @if($selectedSubject && $selectedSubject->getIsMAPEHAttribute())
                    <div class="mapeh-selector me-3">
                        <select id="mapehComponentSelector" class="form-select form-select-sm" onchange="switchMAPEHComponent(this.value)">
                            <option value="average" selected>MAPEH Average Grade</option>
                            @foreach($selectedSubject->components as $component)
                                @php
                                    $componentKey = '';
                                    $componentName = strtolower($component->name);
                                    if (stripos($componentName, 'music') !== false) {
                                        $componentKey = 'music';
                                    } elseif (stripos($componentName, 'art') !== false) {
                                        $componentKey = 'arts';
                                    } elseif (stripos($componentName, 'physical') !== false || stripos($componentName, 'pe') !== false) {
                                        $componentKey = 'pe';
                                    } elseif (stripos($componentName, 'health') !== false) {
                                        $componentKey = 'health';
                                    }
                                @endphp
                                <option value="{{ $componentKey }}">{{ $component->name }} Component</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <button class="btn btn-sm btn-outline-primary" id="toggleTableView">
                                <i class="fas fa-table-columns me-1"></i> Toggle View
                            </button>
                        </div>
                        <div>
                            <button id="printGradesBtn" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-print me-1"></i> Print
                    </button>
                        </div>
                    </div>
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
                                <!-- <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="compactViewToggle">
                                    <label class="form-check-label" for="compactViewToggle">
                                        <i class="fas fa-compress me-1"></i> Compact View
                                    </label>
                                </div> -->
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
                                    <th class="text-center bg-light fw-bold" style="min-width: 100px;">Initial Average</th>
                                    <th class="text-center bg-primary bg-opacity-10 fw-bold" style="min-width: 120px;">Quarterly Average</th>
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
                                                    // Get the subject
                                                    $subject = \App\Models\Subject::find($subjectId);
                                                    
                                                    // Check if this is a MAPEH subject
                                                    $isMAPEH = false;
                                                    if ($subject && isset($subject->components) && $subject->components->count() > 0) {
                                                        $componentNames = $subject->components->pluck('name')->map(fn($name) => strtolower($name))->toArray();
                                                        $requiredComponents = ['music', 'arts', 'physical education', 'health'];
                                                        
                                                        $matchedComponents = 0;
                                                        foreach ($requiredComponents as $component) {
                                                            if (in_array($component, $componentNames) || 
                                                                in_array(strtolower(substr($component, 0, 5)), $componentNames)) {
                                                                $matchedComponents++;
                                                            }
                                                        }
                                                        
                                                        $isMAPEH = $matchedComponents == 4;
                                                    }
                                                    
                                                    // Get subject's grade configuration
                                                    $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                                    
                                                    $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                                    $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                                    $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                                    
                                                    if ($isMAPEH && $subject->components) {
                                                        // For MAPEH subjects, calculate component grades individually
                                                        $componentGrades = [];
                                                        $componentTransmuted = [];
                                                        
                                                        foreach ($subject->components as $component) {
                                                            // Get component grades from database for this term
                                                            $componentWrittenWorks = \App\Models\Grade::where('student_id', $studentData['student']->id)
                                                                ->where('subject_id', $component->id)
                                                                ->where('term', request('term', 'q1'))
                                                                ->where('grade_type', 'written_work')
                                                                ->get();
                                                                
                                                            $componentPerformanceTasks = \App\Models\Grade::where('student_id', $studentData['student']->id)
                                                                ->where('subject_id', $component->id)
                                                                ->where('term', request('term', 'q1'))
                                                                ->where('grade_type', 'performance_task')
                                                                ->get();
                                                                
                                                            $componentQuarterlyAssessment = \App\Models\Grade::where('student_id', $studentData['student']->id)
                                                                ->where('subject_id', $component->id)
                                                                ->where('term', request('term', 'q1'))
                                                                ->where('grade_type', 'quarterly')
                                                                ->first();
                                                            
                                                            // Calculate component grade using weighted formula identical to regular subjects
                                                            $componentWrittenWorksAvg = $componentWrittenWorks->count() > 0 ? 
                                                                $componentWrittenWorks->average(function($grade) {
                                                                    return ($grade->score / $grade->max_score) * 100;
                                                                }) : 0;
                                                                
                                                            $componentPerformanceTasksAvg = $componentPerformanceTasks->count() > 0 ? 
                                                                $componentPerformanceTasks->average(function($grade) {
                                                                    return ($grade->score / $grade->max_score) * 100;
                                                                }) : 0;
                                                                
                                                            $componentQuarterlyScore = $componentQuarterlyAssessment ? 
                                                                ($componentQuarterlyAssessment->score / $componentQuarterlyAssessment->max_score) * 100 : 0;
                                                            
                                                            // Get component's grade configuration (or use parent if not set)
                                                            $componentConfig = \App\Models\GradeConfiguration::where('subject_id', $component->id)->first();
                                                            
                                                            $compWrittenWorkPercentage = $componentConfig ? 
                                                                $componentConfig->written_work_percentage : $writtenWorkPercentage;
                                                                
                                                            $compPerformanceTaskPercentage = $componentConfig ? 
                                                                $componentConfig->performance_task_percentage : $performanceTaskPercentage;
                                                                
                                                            $compQuarterlyAssessmentPercentage = $componentConfig ? 
                                                                $componentConfig->quarterly_assessment_percentage : $quarterlyAssessmentPercentage;
                                                                
                                                            // Calculate component final grade
                                                            $componentFinalGrade = 0;
                                                            $hasComponentGrades = false;
                                                            
                                                            if ($componentWrittenWorks->count() > 0) {
                                                                $componentFinalGrade += ($componentWrittenWorksAvg * ($compWrittenWorkPercentage / 100));
                                                                $hasComponentGrades = true;
                                                            }
                                                            
                                                            if ($componentPerformanceTasks->count() > 0) {
                                                                $componentFinalGrade += ($componentPerformanceTasksAvg * ($compPerformanceTaskPercentage / 100));
                                                                $hasComponentGrades = true;
                                                            }
                                                            
                                                            if ($componentQuarterlyAssessment) {
                                                                $componentFinalGrade += ($componentQuarterlyScore * ($compQuarterlyAssessmentPercentage / 100));
                                                                $hasComponentGrades = true;
                                                            }
                                                            
                                                            if ($hasComponentGrades) {
                                                                $componentGrades[$component->name] = round($componentFinalGrade, 1);
                                                                // Also get transmuted grade for each component
                                                                $componentTransmuted[$component->name] = getTransmutedGrade(
                                                                    $componentFinalGrade, 
                                                                    request('transmutation_table', 1)
                                                                );
                                                            } else {
                                                                $componentGrades[$component->name] = null;
                                                                $componentTransmuted[$component->name] = null;
                                                            }
                                                        }
                                                        
                                                        // Calculate MAPEH average based on available component grades
                                                        $validComponentGrades = array_filter($componentGrades, function($grade) {
                                                            return $grade !== null;
                                                        });
                                                        
                                                        if (count($validComponentGrades) > 0) {
                                                            // Calculate the MAPEH average - this is the average of all component initial grades
                                                            $mapehAverage = array_sum($validComponentGrades) / count($validComponentGrades);
                                                            $avgGrade = round($mapehAverage, 1);
                                                            
                                                            // Get the transmuted grade from this average
                                                            $transmutedGrade = getTransmutedGrade($avgGrade, request('transmutation_table', 1));
                                                            
                                                            // Update grade class
                                                            $gradeClass = 'secondary';
                                                            if ($transmutedGrade >= 90) {
                                                                $gradeClass = 'success';
                                                            } elseif ($transmutedGrade >= 80) {
                                                                $gradeClass = 'primary';
                                                            } elseif ($transmutedGrade >= 75) {
                                                                $gradeClass = 'info';
                                                            } elseif ($transmutedGrade > 0) {
                                                                $gradeClass = 'danger';
                                                            }
                                                        } else {
                                                            // No component grades available
                                                            $avgGrade = 0;
                                                            $transmutedGrade = 0;
                                                            $gradeClass = 'secondary';
                                                        }
                                                    } else {
                                                        // Regular (non-MAPEH) subject calculation
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
                                                    
                                                    // Get the transmuted grade
                                                    $transmutedGrade = getTransmutedGrade($avgGrade, request('transmutation_table', 1));
                                                    
                                                    // Determine grade color class
                                                    $gradeClass = 'secondary';
                                                    if ($transmutedGrade >= 90) {
                                                        $gradeClass = 'success';
                                                    } elseif ($transmutedGrade >= 80) {
                                                        $gradeClass = 'primary';
                                                    } elseif ($transmutedGrade >= 75) {
                                                        $gradeClass = 'info';
                                                    } elseif ($transmutedGrade > 0) {
                                                        $gradeClass = 'danger';
                                                        }
                                                    }
                                                @endphp
                                                
                                                @if($isMAPEH && isset($componentGrades) && count(array_filter($componentGrades)) > 0)
                                                    <!-- Average MAPEH grade display -->
                                                    <div class="mapeh-average-display">
                                                    <div class="mb-1">
                                                            <span class="badge bg-{{ $gradeClass }} grade-badge">{{ $transmutedGrade }}</span>
                                                    </div>
                                                        <div class="small text-muted mb-1">Initial: {{ $avgGrade }}%</div>
                                                    </div>
                                                    
                                                    <!-- Component-specific displays -->
                                                    @foreach(['music', 'arts', 'pe', 'health'] as $componentKey)
                                                        @php
                                                            $found = false;
                                                            $componentName = '';
                                                            $componentGrade = 0;
                                                            $componentTrans = 0;
                                                            
                                                            // Match component key to actual component data
                                                            foreach($componentGrades as $name => $grade) {
                                                                $lowerName = strtolower($name);
                                                                if (
                                                                    ($componentKey == 'music' && stripos($lowerName, 'music') !== false) ||
                                                                    ($componentKey == 'arts' && stripos($lowerName, 'art') !== false) ||
                                                                    ($componentKey == 'pe' && (stripos($lowerName, 'physical') !== false || stripos($lowerName, 'pe') !== false)) ||
                                                                    ($componentKey == 'health' && stripos($lowerName, 'health') !== false)
                                                                ) {
                                                                    $found = true;
                                                                    $componentName = $name;
                                                                    $componentGrade = $grade;
                                                                    $componentTrans = isset($componentTransmuted[$name]) ? $componentTransmuted[$name] : 'N/A';
                                                                    break;
                                                                }
                                                            }
                                                            
                                                            if (!$found) continue;
                                                            
                                                            // Set styles based on component
                                                                $componentClass = 'secondary';
                                                            $iconClass = 'fas fa-book';
                                                                
                                                            switch($componentKey) {
                                                                case 'music':
                                                                    $componentClass = 'primary';
                                                                    $iconClass = 'fas fa-music';
                                                                    break;
                                                                case 'arts':
                                                                    $componentClass = 'danger';
                                                                    $iconClass = 'fas fa-paint-brush';
                                                                    break;
                                                                case 'pe':
                                                                    $componentClass = 'success';
                                                                    $iconClass = 'fas fa-running';
                                                                    break;
                                                                case 'health':
                                                                    $componentClass = 'warning';
                                                                    $iconClass = 'fas fa-heartbeat';
                                                                    break;
                                                            }
                                                            
                                                            // Set grade class based on value
                                                            $compGradeClass = 'secondary';
                                                            if (is_numeric($componentTrans)) {
                                                                if ($componentTrans >= 90) {
                                                                    $compGradeClass = 'success';
                                                                } elseif ($componentTrans >= 80) {
                                                                    $compGradeClass = 'primary';
                                                                } elseif ($componentTrans >= 75) {
                                                                    $compGradeClass = 'info';
                                                                } elseif ($componentTrans > 0) {
                                                                    $compGradeClass = 'danger';
                                                                }
                                                            }
                                                            @endphp
                                                        
                                                        @if($found)
                                                        <div class="mapeh-component-single d-none" data-component="{{ $componentKey }}">
                                                            <div class="mb-1">
                                                                <span class="badge bg-{{ $compGradeClass }} grade-badge">{{ $componentTrans }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center justify-content-center">
                                                                <span class="badge bg-{{ $componentClass }} bg-opacity-25 text-{{ $componentClass }} me-1">
                                                                    <i class="{{ $iconClass }}"></i>
                                                                </span>
                                                                <small class="text-muted">{{ $componentGrade }}%</small>
                                                    </div>
                                                        </div>
                                                        @endif
                                                    @endforeach
                                                    
                                                    <!-- Component badges for the compact view -->
                                                    <div class="mapeh-components mt-2 mapeh-component-badge">
                                                        @foreach(['music', 'arts', 'pe', 'health'] as $componentKey)
                                                            @php
                                                                $found = false;
                                                                $componentName = '';
                                                                $componentGrade = 0;
                                                                $componentTrans = 0;
                                                                
                                                                // Match component key to actual component data
                                                                foreach($componentGrades as $name => $grade) {
                                                                    $lowerName = strtolower($name);
                                                                    if (
                                                                        ($componentKey == 'music' && stripos($lowerName, 'music') !== false) ||
                                                                        ($componentKey == 'arts' && stripos($lowerName, 'art') !== false) ||
                                                                        ($componentKey == 'pe' && (stripos($lowerName, 'physical') !== false || stripos($lowerName, 'pe') !== false)) ||
                                                                        ($componentKey == 'health' && stripos($lowerName, 'health') !== false)
                                                                    ) {
                                                                        $found = true;
                                                                        $componentName = $name;
                                                                        $componentGrade = $grade;
                                                                        $componentTrans = isset($componentTransmuted[$name]) ? $componentTransmuted[$name] : 'N/A';
                                                                        break;
                                                                    }
                                                                }
                                                                
                                                                if (!$found) continue;
                                                                
                                                                // Set styles based on component
                                                            $componentClass = 'secondary';
                                                                $iconClass = 'fas fa-book';
                                                            
                                                                switch($componentKey) {
                                                                    case 'music':
                                                                $componentClass = 'primary';
                                                                        $iconClass = 'fas fa-music';
                                                                        break;
                                                                    case 'arts':
                                                                $componentClass = 'danger';
                                                                        $iconClass = 'fas fa-paint-brush';
                                                                        break;
                                                                    case 'pe':
                                                                $componentClass = 'success';
                                                                        $iconClass = 'fas fa-running';
                                                                        break;
                                                                    case 'health':
                                                                $componentClass = 'warning';
                                                                        $iconClass = 'fas fa-heartbeat';
                                                                        break;
                                                            }
                                                        @endphp
                                                            
                                                            @if($found)
                                                            <span class="badge bg-{{ $componentClass }} bg-opacity-25 text-{{ $componentClass }}" title="{{ $componentName }}: {{ $componentGrade }}%">
                                                                <i class="{{ $iconClass }}"></i> {{ $componentTrans }}
                                                            </span>
                                                            @endif
                                                    @endforeach
                                                    </div>
                                                @else
                                                    <span class="badge bg-{{ $gradeClass }} grade-badge">{{ $transmutedGrade }}</span>
                                                    <div class="small text-muted">Initial: {{ $avgGrade }}%</div>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-center bg-light">
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
                                            
                                            <span class="badge bg-{{ $avgGradeClass }} bg-opacity-25 text-{{ $avgGradeClass }} fs-5 px-3">{{ $finalAverage }}%</span>
                                        </td>
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
                                            
                                            <span class="badge bg-{{ $avgGradeClass }} fs-5 px-3">{{ getTransmutedGrade($finalAverage, request('transmutation_table', 1)) }}</span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-success" 
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
                    <!-- Single Subject View -->
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
                            @if($selectedSubject && $selectedSubject->getIsMAPEHAttribute())
                                <!-- MAPEH Components Table -->
                            <table class="table table-hover align-middle mb-0 grade-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%" class="ps-4">#</th>
                                            <th width="20%">Student</th>
                                            <th width="10%">Component</th>
                                        <th width="15%" class="text-center">
                                            <div>Written Works</div>
                                            <span class="badge bg-primary rounded-pill">{{ $writtenWorkPercentage }}%</span>
                                        </th>
                                        <th width="15%" class="text-center">
                                            <div>Performance Tasks</div>
                                            <span class="badge bg-success rounded-pill">{{ $performanceTaskPercentage }}%</span>
                                        </th>
                                            <th width="10%" class="text-center">
                                            <div>Quarterly</div>
                                            <span class="badge bg-warning rounded-pill">{{ $quarterlyAssessmentPercentage }}%</span>
                                        </th>
                                        <th width="10%" class="text-center">Initial Grade</th>
                                        <th width="10%" class="text-center">Quarterly Grade</th>
                                            <th width="10%" class="text-center pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $studentData)
                                        @php
                                            $student = $studentData['student'];
                                                $subject = $selectedSubject;
                                                
                                                // Get all the component subjects of MAPEH
                                                $components = $subject->components;
                                                
                                                // Initialize component array with keys
                                                $componentKeys = ['music', 'arts', 'pe', 'health'];
                                                $componentMap = [];
                                                
                                                // Map actual component subjects to our standard keys
                                                foreach($components as $component) {
                                                    $componentName = strtolower($component->name);
                                                    $componentKey = '';
                                                    
                                                    if (stripos($componentName, 'music') !== false) {
                                                        $componentKey = 'music';
                                                    } elseif (stripos($componentName, 'art') !== false) {
                                                        $componentKey = 'arts';
                                                    } elseif (stripos($componentName, 'physical') !== false || stripos($componentName, 'pe') !== false) {
                                                        $componentKey = 'pe';
                                                    } elseif (stripos($componentName, 'health') !== false) {
                                                        $componentKey = 'health';
                                                    }
                                                    
                                                    if (!empty($componentKey)) {
                                                        $componentMap[$componentKey] = $component;
                                                    }
                                                }
                                            @endphp
                                            
                                            @foreach($componentKeys as $rowIndex => $componentKey)
                                                @php
                                                    // Skip if component doesn't exist
                                                    if (!isset($componentMap[$componentKey])) continue;
                                                    
                                                    $component = $componentMap[$componentKey];
                                                    
                                                    // Get component grades
                                                    $writtenWorks = \App\Models\Grade::where('student_id', $student->id)
                                                        ->where('subject_id', $component->id)
                                                        ->where('term', $selectedTerm)
                                                        ->where('grade_type', 'written_work')
                                                        ->get();
                                                    
                                                    $performanceTasks = \App\Models\Grade::where('student_id', $student->id)
                                                        ->where('subject_id', $component->id)
                                                        ->where('term', $selectedTerm)
                                                        ->where('grade_type', 'performance_task')
                                                        ->get();
                                                    
                                                    $quarterlyAssessment = \App\Models\Grade::where('student_id', $student->id)
                                                        ->where('subject_id', $component->id)
                                                        ->where('term', $selectedTerm)
                                                        ->where('grade_type', 'quarterly')
                                                        ->first();
                                                    
                                            // Calculate grades
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
                                                    
                                                    // Get component's grade configuration (or use parent if not set)
                                                    $componentConfig = \App\Models\GradeConfiguration::where('subject_id', $component->id)->first();
                                                    
                                                    $compWrittenWorkPercentage = $componentConfig ? 
                                                        $componentConfig->written_work_percentage : $writtenWorkPercentage;
                                                        
                                                    $compPerformanceTaskPercentage = $componentConfig ? 
                                                        $componentConfig->performance_task_percentage : $performanceTaskPercentage;
                                                        
                                                    $compQuarterlyAssessmentPercentage = $componentConfig ? 
                                                        $componentConfig->quarterly_assessment_percentage : $quarterlyAssessmentPercentage;
                                            
                                            // Calculate final grade
                                            $finalGrade = 0;
                                            if ($writtenWorksAvg > 0) {
                                                        $finalGrade += ($writtenWorksAvg * ($compWrittenWorkPercentage / 100));
                                            }
                                            if ($performanceTasksAvg > 0) {
                                                        $finalGrade += ($performanceTasksAvg * ($compPerformanceTaskPercentage / 100));
                                            }
                                            if ($quarterlyScore > 0) {
                                                        $finalGrade += ($quarterlyScore * ($compQuarterlyAssessmentPercentage / 100));
                                                    }
                                                    
                                                    $avgGrade = round($finalGrade, 1);
                                                    
                                                    // Get the transmuted grade
                                                    $transmutedGrade = getTransmutedGrade($avgGrade, request('transmutation_table', 1));
                                                    
                                                    // Determine grade color class
                                                    $gradeClass = 'secondary';
                                                    if ($transmutedGrade >= 90) {
                                                        $gradeClass = 'success';
                                                    } elseif ($transmutedGrade >= 80) {
                                                        $gradeClass = 'primary';
                                                    } elseif ($transmutedGrade >= 75) {
                                                        $gradeClass = 'info';
                                                    } elseif ($transmutedGrade > 0) {
                                                        $gradeClass = 'danger';
                                                    }
                                                    
                                                    // Set component styles based on component key
                                                    $componentClass = 'secondary';
                                                    $iconClass = 'fas fa-book';
                                                    $componentLabel = 'Unknown';
                                                    
                                                    switch($componentKey) {
                                                        case 'music':
                                                            $componentClass = 'primary';
                                                            $iconClass = 'fas fa-music';
                                                            $componentLabel = 'Music';
                                                            break;
                                                        case 'arts':
                                                            $componentClass = 'danger';
                                                            $iconClass = 'fas fa-paint-brush';
                                                            $componentLabel = 'Arts';
                                                            break;
                                                        case 'pe':
                                                            $componentClass = 'success';
                                                            $iconClass = 'fas fa-running';
                                                            $componentLabel = 'P.E.';
                                                            break;
                                                        case 'health':
                                                            $componentClass = 'warning';
                                                            $iconClass = 'fas fa-heartbeat';
                                                            $componentLabel = 'Health';
                                                            break;
                                            }
                                        @endphp
                                                
                                                <tr class="{{ $rowIndex == 0 ? 'border-top border-primary' : '' }}">
                                                    <!-- Display row number only on first component -->
                                                    <td class="ps-4">
                                                        @if($rowIndex == 0)
                                                            {{ $index + 1 }}
                                                        @endif
                                                    </td>
                                                    
                                                    <!-- Display student info only on first component -->
                                                    <td>
                                                        @if($rowIndex == 0)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-circle bg-primary bg-opacity-10 text-primary me-2">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                                        <div class="small text-muted student-id">
                                                            ID: {{ $student->student_id }}
                                                        </div>
                                                    </div>
                                                </div>
                                                        @endif
                                            </td>
                                                    
                                                    <!-- Component name -->
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-{{ $componentClass }} bg-opacity-25 text-{{ $componentClass }} me-2">
                                                                <i class="{{ $iconClass }}"></i>
                                                            </span>
                                                            <span class="fw-medium">{{ $componentLabel }}</span>
                                                        </div>
                                                    </td>
                                                    
                                                    <!-- Written Works -->
                                            <td class="text-center">
                                                @if(count($writtenWorks) > 0)
                                                            <div class="fw-bold">{{ number_format($writtenWorksAvg * $compWrittenWorkPercentage / 100, 1) }}%</div>
                                                    <div class="small text-muted">{{ count($writtenWorks) }} assessments</div>
                                                @else
                                                    <span class="badge bg-light text-muted">No data</span>
                                                @endif
                                            </td>
                                                    
                                                    <!-- Performance Tasks -->
                                            <td class="text-center">
                                                @if(count($performanceTasks) > 0)
                                                            <div class="fw-bold">{{ number_format($performanceTasksAvg * $compPerformanceTaskPercentage / 100, 1) }}%</div>
                                                    <div class="small text-muted">{{ count($performanceTasks) }} tasks</div>
                                                @else
                                                    <span class="badge bg-light text-muted">No data</span>
                                                @endif
                                            </td>
                                                    
                                                    <!-- Quarterly Assessment -->
                                            <td class="text-center">
                                                @if($quarterlyAssessment)
                                                            <div class="fw-bold">{{ number_format($quarterlyScore * $compQuarterlyAssessmentPercentage / 100, 1) }}%</div>
                                                    <div class="small text-muted">
                                                        {{ $quarterlyAssessment->score }}/{{ $quarterlyAssessment->max_score }}
                                                    </div>
                                                @else
                                                    <span class="badge bg-light text-muted">No data</span>
                                                @endif
                                            </td>
                                                    
                                                    <!-- Initial Grade -->
                                            <td class="text-center">
                                                        <div class="fw-bold">{{ number_format($avgGrade, 1) }}%</div>
                                                <span class="badge bg-{{ $gradeClass }} bg-opacity-25 text-{{ $gradeClass }} small">Initial</span>
                                            </td>
                                                    
                                                    <!-- Quarterly Grade -->
                                            <td class="text-center">
                                                        <div class="fw-bold fs-4">{{ $transmutedGrade }}</div>
                                                        <span class="badge bg-{{ $gradeClass }} small">Quarterly</span>
                                                    </td>
                                                    
                                                    <!-- Actions -->
                                                    <td class="text-center pe-4">
                                                        <div class="btn-group">
                                                            <a href="{{ route('teacher.grades.create', [
                                                                    'student_id' => $student->id, 
                                                                    'subject_id' => $component->id, 
                                                                    'term' => $selectedTerm
                                                                ]) }}" 
                                                               class="btn btn-sm btn-outline-success" 
                                                               title="Add Grade">
                                                                <i class="fas fa-plus"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                                
                                                <!-- For the last component, add a row with MAPEH average -->
                                                @if($rowIndex == count($componentKeys) - 1 || !isset($componentMap[$componentKeys[$rowIndex + 1]]))
                                                    @php
                                                        // Calculate MAPEH average from components
                                                        $componentGrades = [];
                                                        $validComponentCount = 0;
                                                        
                                                        foreach($componentKeys as $key) {
                                                            if (!isset($componentMap[$key])) continue;
                                                            
                                                            $comp = $componentMap[$key];
                                                            
                                                            // Get component grades
                                                            $compWrittenWorks = \App\Models\Grade::where('student_id', $student->id)
                                                                ->where('subject_id', $comp->id)
                                                                ->where('term', $selectedTerm)
                                                                ->where('grade_type', 'written_work')
                                                                ->get();
                                                            
                                                            $compPerformanceTasks = \App\Models\Grade::where('student_id', $student->id)
                                                                ->where('subject_id', $comp->id)
                                                                ->where('term', $selectedTerm)
                                                                ->where('grade_type', 'performance_task')
                                                                ->get();
                                                            
                                                            $compQuarterlyAssessment = \App\Models\Grade::where('student_id', $student->id)
                                                                ->where('subject_id', $comp->id)
                                                                ->where('term', $selectedTerm)
                                                                ->where('grade_type', 'quarterly')
                                                                ->first();
                                                                
                                                            // Calculate grades
                                                            $compWrittenWorksAvg = $compWrittenWorks->count() > 0 ? 
                                                                $compWrittenWorks->average(function($grade) {
                                                                    return ($grade->score / $grade->max_score) * 100;
                                                                }) : 0;
                                                            
                                                            $compPerformanceTasksAvg = $compPerformanceTasks->count() > 0 ? 
                                                                $compPerformanceTasks->average(function($grade) {
                                                                    return ($grade->score / $grade->max_score) * 100;
                                                                }) : 0;
                                                            
                                                            $compQuarterlyScore = $compQuarterlyAssessment ? 
                                                                ($compQuarterlyAssessment->score / $compQuarterlyAssessment->max_score) * 100 : 0;
                                                            
                                                            // Get component's grade configuration
                                                            $compConfig = \App\Models\GradeConfiguration::where('subject_id', $comp->id)->first();
                                                            
                                                            $cWrittenWorkPercentage = $compConfig ? 
                                                                $compConfig->written_work_percentage : $writtenWorkPercentage;
                                                                
                                                            $cPerformanceTaskPercentage = $compConfig ? 
                                                                $compConfig->performance_task_percentage : $performanceTaskPercentage;
                                                                
                                                            $cQuarterlyAssessmentPercentage = $compConfig ? 
                                                                $compConfig->quarterly_assessment_percentage : $quarterlyAssessmentPercentage;
                                                            
                                                            // Calculate component final grade
                                                            $compFinalGrade = 0;
                                                            $hasGrades = false;
                                                            
                                                            if ($compWrittenWorksAvg > 0) {
                                                                $compFinalGrade += ($compWrittenWorksAvg * ($cWrittenWorkPercentage / 100));
                                                                $hasGrades = true;
                                                            }
                                                            
                                                            if ($compPerformanceTasksAvg > 0) {
                                                                $compFinalGrade += ($compPerformanceTasksAvg * ($cPerformanceTaskPercentage / 100));
                                                                $hasGrades = true;
                                                            }
                                                            
                                                            if ($compQuarterlyScore > 0) {
                                                                $compFinalGrade += ($compQuarterlyScore * ($cQuarterlyAssessmentPercentage / 100));
                                                                $hasGrades = true;
                                                            }
                                                            
                                                            if ($hasGrades) {
                                                                $componentGrades[$key] = round($compFinalGrade, 1);
                                                                $validComponentCount++;
                                                            }
                                                        }
                                                        
                                                        // Calculate MAPEH average
                                                        $mapehAverage = 0;
                                                        if ($validComponentCount > 0) {
                                                            $mapehAverage = array_sum($componentGrades) / $validComponentCount;
                                                        }
                                                        
                                                        $mapehRoundedAvg = round($mapehAverage, 1);
                                                        $mapehTransmuted = getTransmutedGrade($mapehRoundedAvg, request('transmutation_table', 1));
                                                        
                                                        // Average grade color class
                                                        $avgGradeClass = 'secondary';
                                                        if ($mapehTransmuted >= 90) {
                                                            $avgGradeClass = 'success';
                                                        } elseif ($mapehTransmuted >= 80) {
                                                            $avgGradeClass = 'primary';
                                                        } elseif ($mapehTransmuted >= 75) {
                                                            $avgGradeClass = 'info';
                                                        } elseif ($mapehTransmuted > 0) {
                                                            $avgGradeClass = 'danger';
                                                    }
                                                @endphp
                                                    
                                                    <tr class="bg-light border-bottom border-primary">
                                                        <td class="ps-4"></td>
                                                        <td></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-primary me-2">
                                                                    <i class="fas fa-calculator"></i>
                                                                </span>
                                                                <span class="fw-bold">MAPEH Average</span>
                                                </div>
                                            </td>
                                                        <td class="text-center" colspan="3">
                                                            <div class="fw-bold">Components: {{ $validComponentCount }}/4</div>
                                                            <div class="small text-muted">Average of all component grades</div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="fw-bold">{{ $mapehRoundedAvg }}%</div>
                                                            <span class="badge bg-{{ $avgGradeClass }} bg-opacity-25 text-{{ $avgGradeClass }} small">Initial</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="fw-bold fs-4">{{ $mapehTransmuted }}</div>
                                                            <span class="badge bg-{{ $avgGradeClass }} small">Quarterly</span>
                                                        </td>
                                            <td class="text-center pe-4">
                                                    <a href="#" class="btn btn-sm btn-outline-primary" 
                                                       data-bs-toggle="modal" 
                                                       data-bs-target="#studentDetailsModal{{ $student->id }}" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                            </td>
                                        </tr>
                                                @endif
                                            @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            @else
                                <!-- Regular Subject Table - keep the existing code -->
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
                                            <th width="10%" class="text-center">Initial Grade</th>
                                            <th width="10%" class="text-center">Quarterly Grade</th>
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
                                            <!-- Keep the existing code for regular subject rows -->
                                            @include('teacher.grades.regular_rows')
                                        @endforeach
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    @endif
                @endif
            </div>
        </div>
        
        <!-- Grade Statistics Card -->
        <!-- <div class="card shadow-sm rounded-3 border-0 mb-4">
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
                                    @php
                                        // Get the selected transmutation table from request or session
                                        $selectedTableId = request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1));
                                    @endphp
                                    <div class="display-5 text-primary mb-2">
                                        {{ getTransmutedGrade($classAverage, $selectedTableId) }}
                                    </div>
                                    <div class="small text-primary mb-2">({{ number_format($classAverage, 1) }}%)</div>
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
                                    <div class="display-5 text-info mb-2">
                                        {{ getTransmutedGrade($highestGrade, $selectedTableId) }}
                                    </div>
                                    <div class="small text-info mb-2">({{ number_format($highestGrade, 1) }}%)</div>
                                    <h6 class="fw-bold text-info">Highest Grade</h6>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="card border-0 bg-warning bg-opacity-10 h-100 stat-card">
                                <div class="card-body text-center">
                                    <div class="display-5 text-warning mb-2">
                                        {{ getTransmutedGrade($lowestGrade, $selectedTableId) }}
                                    </div>
                                    <div class="small text-warning mb-2">({{ number_format($lowestGrade, 1) }}%)</div>
                                    <h6 class="fw-bold text-warning">Lowest Grade</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

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

    // Define the transmutation function based on the selected table
    function getTransmutedGrade($initialGrade, $tableType) {
        if ($initialGrade < 0) return 60;
        
        // Table 1: DepEd Transmutation Table (formerly Table 4)
        if ($tableType == 1) {
            if ($initialGrade == 100) return 100;
            elseif ($initialGrade >= 98.40) return 99;
            elseif ($initialGrade >= 96.80) return 98;
            elseif ($initialGrade >= 95.20) return 97;
            elseif ($initialGrade >= 93.60) return 96;
            elseif ($initialGrade >= 92.00) return 95;
            elseif ($initialGrade >= 90.40) return 94;
            elseif ($initialGrade >= 88.80) return 93;
            elseif ($initialGrade >= 87.20) return 92;
            elseif ($initialGrade >= 85.60) return 91;
            elseif ($initialGrade >= 84.00) return 90;
            elseif ($initialGrade >= 82.40) return 89;
            elseif ($initialGrade >= 80.80) return 88;
            elseif ($initialGrade >= 79.20) return 87;
            elseif ($initialGrade >= 77.60) return 86;
            elseif ($initialGrade >= 76.00) return 85;
            elseif ($initialGrade >= 74.40) return 84;
            elseif ($initialGrade >= 72.80) return 83;
            elseif ($initialGrade >= 71.20) return 82;
            elseif ($initialGrade >= 69.60) return 81;
            elseif ($initialGrade >= 68.00) return 80;
            elseif ($initialGrade >= 66.40) return 79;
            elseif ($initialGrade >= 64.80) return 78;
            elseif ($initialGrade >= 63.20) return 77;
            elseif ($initialGrade >= 61.60) return 76;
            elseif ($initialGrade >= 60.00) return 75;
            elseif ($initialGrade >= 56.00) return 74;
            elseif ($initialGrade >= 52.00) return 73;
            elseif ($initialGrade >= 48.00) return 72;
            elseif ($initialGrade >= 44.00) return 71;
            elseif ($initialGrade >= 40.00) return 70;
            elseif ($initialGrade >= 36.00) return 69;
            elseif ($initialGrade >= 32.00) return 68;
            elseif ($initialGrade >= 28.00) return 67;
            elseif ($initialGrade >= 24.00) return 66;
            elseif ($initialGrade >= 20.00) return 65;
            elseif ($initialGrade >= 16.00) return 64;
            elseif ($initialGrade >= 12.00) return 63;
            elseif ($initialGrade >= 8.00) return 62;
            elseif ($initialGrade >= 4.00) return 61;
            else return 60;
        }
        // Table 2: Grades 1-10 and Non-Core Subjects of TVL, Sports, and Arts & Design (formerly Table 1)
        elseif ($tableType == 2) {
            if ($initialGrade >= 80) return 100;
            elseif ($initialGrade >= 78.40) return 99;
            elseif ($initialGrade >= 76.80) return 98;
            elseif ($initialGrade >= 75.20) return 97;
            elseif ($initialGrade >= 73.60) return 96;
            elseif ($initialGrade >= 72.00) return 95;
            elseif ($initialGrade >= 70.40) return 94;
            elseif ($initialGrade >= 68.80) return 93;
            elseif ($initialGrade >= 67.20) return 92;
            elseif ($initialGrade >= 65.60) return 91;
            elseif ($initialGrade >= 64.00) return 90;
            elseif ($initialGrade >= 62.40) return 89;
            elseif ($initialGrade >= 60.80) return 88;
            elseif ($initialGrade >= 59.20) return 87;
            elseif ($initialGrade >= 57.60) return 86;
            elseif ($initialGrade >= 56.00) return 85;
            elseif ($initialGrade >= 54.40) return 84;
            elseif ($initialGrade >= 52.80) return 83;
            elseif ($initialGrade >= 51.20) return 82;
            elseif ($initialGrade >= 49.60) return 81;
            elseif ($initialGrade >= 48.00) return 80;
            elseif ($initialGrade >= 46.40) return 79;
            elseif ($initialGrade >= 44.80) return 78;
            elseif ($initialGrade >= 43.20) return 77;
            elseif ($initialGrade >= 41.60) return 76;
            elseif ($initialGrade >= 40.00) return 75;
            elseif ($initialGrade >= 38.40) return 74;
            elseif ($initialGrade >= 36.80) return 73;
            elseif ($initialGrade >= 35.20) return 72;
            elseif ($initialGrade >= 33.60) return 71;
            elseif ($initialGrade >= 32.00) return 70;
            elseif ($initialGrade >= 30.40) return 69;
            elseif ($initialGrade >= 28.80) return 68;
            elseif ($initialGrade >= 27.20) return 67;
            elseif ($initialGrade >= 25.60) return 66;
            elseif ($initialGrade >= 24.00) return 65;
            elseif ($initialGrade >= 22.40) return 64;
            elseif ($initialGrade >= 20.80) return 63;
            elseif ($initialGrade >= 19.20) return 62;
            elseif ($initialGrade >= 17.60) return 61;
            else return 60;
        }
        // Table 3: For SHS Core Subjects and Work Immersion/Research/Business Enterprise/Performance (formerly Table 2)
        elseif ($tableType == 3) {
            if ($initialGrade >= 100) return 100;
            elseif ($initialGrade >= 73.80) return 99;
            elseif ($initialGrade >= 72.60) return 98;
            elseif ($initialGrade >= 71.40) return 97;
            elseif ($initialGrade >= 70.20) return 96;
            elseif ($initialGrade >= 69.00) return 95;
            elseif ($initialGrade >= 67.80) return 94;
            elseif ($initialGrade >= 66.60) return 93;
            elseif ($initialGrade >= 65.40) return 92;
            elseif ($initialGrade >= 64.20) return 91;
            elseif ($initialGrade >= 63.00) return 90;
            elseif ($initialGrade >= 61.80) return 89;
            elseif ($initialGrade >= 60.60) return 88;
            elseif ($initialGrade >= 59.40) return 87;
            elseif ($initialGrade >= 58.20) return 86;
            elseif ($initialGrade >= 57.00) return 85;
            elseif ($initialGrade >= 55.80) return 84;
            elseif ($initialGrade >= 54.60) return 83;
            elseif ($initialGrade >= 53.40) return 82;
            elseif ($initialGrade >= 52.20) return 81;
            elseif ($initialGrade >= 51.00) return 80;
            elseif ($initialGrade >= 49.80) return 79;
            elseif ($initialGrade >= 48.60) return 78;
            elseif ($initialGrade >= 47.40) return 77;
            elseif ($initialGrade >= 46.20) return 76;
            elseif ($initialGrade >= 45.00) return 75;
            elseif ($initialGrade >= 43.80) return 74;
            elseif ($initialGrade >= 42.60) return 73;
            elseif ($initialGrade >= 41.40) return 72;
            elseif ($initialGrade >= 40.20) return 71;
            elseif ($initialGrade >= 39.00) return 70;
            elseif ($initialGrade >= 37.80) return 69;
            elseif ($initialGrade >= 36.60) return 68;
            elseif ($initialGrade >= 35.40) return 67;
            elseif ($initialGrade >= 34.20) return 66;
            elseif ($initialGrade >= 33.00) return 65;
            elseif ($initialGrade >= 31.80) return 64;
            elseif ($initialGrade >= 30.60) return 63;
            elseif ($initialGrade >= 29.40) return 62;
            elseif ($initialGrade >= 28.20) return 61;
            else return 60;
        }
        // Table 4: For all other SHS Subjects in the Academic Track (formerly Table 3)
        elseif ($tableType == 4) {
            if ($initialGrade >= 100) return 100;
            elseif ($initialGrade >= 68.90) return 99;
            elseif ($initialGrade >= 67.80) return 98;
            elseif ($initialGrade >= 66.70) return 97;
            elseif ($initialGrade >= 65.60) return 96;
            elseif ($initialGrade >= 64.50) return 95;
            elseif ($initialGrade >= 63.40) return 94;
            elseif ($initialGrade >= 62.30) return 93;
            elseif ($initialGrade >= 61.20) return 92;
            elseif ($initialGrade >= 60.10) return 91;
            elseif ($initialGrade >= 59.00) return 90;
            elseif ($initialGrade >= 57.80) return 89;
            elseif ($initialGrade >= 56.70) return 88;
            elseif ($initialGrade >= 55.60) return 87;
            elseif ($initialGrade >= 54.50) return 86;
            elseif ($initialGrade >= 53.40) return 85;
            elseif ($initialGrade >= 52.30) return 84;
            elseif ($initialGrade >= 51.20) return 83;
            elseif ($initialGrade >= 50.10) return 82;
            elseif ($initialGrade >= 49.00) return 81;
            elseif ($initialGrade >= 47.90) return 80;
            elseif ($initialGrade >= 46.80) return 79;
            elseif ($initialGrade >= 45.70) return 78;
            elseif ($initialGrade >= 44.60) return 77;
            elseif ($initialGrade >= 43.50) return 76;
            elseif ($initialGrade >= 42.40) return 75;
            elseif ($initialGrade >= 41.30) return 74;
            elseif ($initialGrade >= 40.20) return 73;
            elseif ($initialGrade >= 39.10) return 72;
            elseif ($initialGrade >= 34.00) return 71;
            elseif ($initialGrade >= 28.90) return 70;
            elseif ($initialGrade >= 23.80) return 69;
            elseif ($initialGrade >= 19.70) return 68;
            elseif ($initialGrade >= 17.60) return 67;
            elseif ($initialGrade >= 15.50) return 66;
            elseif ($initialGrade >= 13.40) return 65;
            elseif ($initialGrade >= 11.30) return 64;
            elseif ($initialGrade >= 9.20) return 63;
            elseif ($initialGrade >= 7.10) return 62;
            elseif ($initialGrade >= 5.00) return 61;
            else return 60;
        }
        else {
            // Default to table 1 (DepEd) if an invalid table type is specified
            if ($initialGrade == 100) return 100;
            elseif ($initialGrade >= 98.40) return 99;
            elseif ($initialGrade >= 96.80) return 98;
            elseif ($initialGrade >= 95.20) return 97;
            elseif ($initialGrade >= 93.60) return 96;
            elseif ($initialGrade >= 92.00) return 95;
            elseif ($initialGrade >= 90.40) return 94;
            elseif ($initialGrade >= 88.80) return 93;
            elseif ($initialGrade >= 87.20) return 92;
            elseif ($initialGrade >= 85.60) return 91;
            elseif ($initialGrade >= 84.00) return 90;
            elseif ($initialGrade >= 82.40) return 89;
            elseif ($initialGrade >= 80.80) return 88;
            elseif ($initialGrade >= 79.20) return 87;
            elseif ($initialGrade >= 77.60) return 86;
            elseif ($initialGrade >= 76.00) return 85;
            elseif ($initialGrade >= 74.40) return 84;
            elseif ($initialGrade >= 72.80) return 83;
            elseif ($initialGrade >= 71.20) return 82;
            elseif ($initialGrade >= 69.60) return 81;
            elseif ($initialGrade >= 68.00) return 80;
            elseif ($initialGrade >= 66.40) return 79;
            elseif ($initialGrade >= 64.80) return 78;
            elseif ($initialGrade >= 63.20) return 77;
            elseif ($initialGrade >= 61.60) return 76;
            elseif ($initialGrade >= 60.00) return 75;
            elseif ($initialGrade >= 56.00) return 74;
            elseif ($initialGrade >= 52.00) return 73;
            elseif ($initialGrade >= 48.00) return 72;
            elseif ($initialGrade >= 44.00) return 71;
            elseif ($initialGrade >= 40.00) return 70;
            elseif ($initialGrade >= 36.00) return 69;
            elseif ($initialGrade >= 32.00) return 68;
            elseif ($initialGrade >= 28.00) return 67;
            elseif ($initialGrade >= 24.00) return 66;
            elseif ($initialGrade >= 20.00) return 65;
            elseif ($initialGrade >= 16.00) return 64;
            elseif ($initialGrade >= 12.00) return 63;
            elseif ($initialGrade >= 8.00) return 62;
            elseif ($initialGrade >= 4.00) return 61;
            else return 60;
        }
    }

    // Get the selected transmutation table (default to table 1)
    $selectedTransmutationTable = request('transmutation_table', 1);
    @endphp

    @push('scripts')
    <script>
    // Function to validate the filter form before submission
    function validateFilterForm() {
        // Ensure the transmutation table value is correctly set
        const transmutationDropdown = document.getElementById('transmutation_table');
        const hiddenTransmutationInput = document.getElementById('selected_transmutation_table');
        
        if (transmutationDropdown && hiddenTransmutationInput) {
            hiddenTransmutationInput.value = transmutationDropdown.value;
            console.log('Form submission: setting transmutation table to', transmutationDropdown.value);
        }
        
        // Create a hidden field to store the locked state if it's checked
        const lockCheckbox = document.getElementById('lock_table');
        
        if (lockCheckbox && lockCheckbox.checked) {
            // Create a hidden input to ensure the locked state is passed properly
            let lockedInput = document.getElementById('locked_transmutation_table_hidden');
            if (!lockedInput) {
                lockedInput = document.createElement('input');
                lockedInput.type = 'hidden';
                lockedInput.id = 'locked_transmutation_table_hidden';
                lockedInput.name = 'locked_transmutation_table';
                document.getElementById('filterForm').appendChild(lockedInput);
            }
            lockedInput.value = 'true';
            console.log('Locking transmutation table to', hiddenTransmutationInput.value);
        } else {
            // Remove the hidden input if the checkbox is unchecked
            const lockedInput = document.getElementById('locked_transmutation_table_hidden');
            if (lockedInput) {
                lockedInput.remove();
            }
            // Make sure the dropdown is enabled
            if (transmutationDropdown) {
                transmutationDropdown.disabled = false;
            }
            console.log('Unlocking transmutation table');
        }
        
        return true; // Continue with form submission
    }

    // Function to update the hidden transmutation table input
    function updateHiddenTransmutationInput(value) {
        document.getElementById('selected_transmutation_table').value = value;
        console.log('Updated transmutation table selection to:', value);
    }

    // Add event listener to lock checkbox to enable/disable dropdown immediately
    document.addEventListener('DOMContentLoaded', function() {
        const lockCheckbox = document.getElementById('lock_table');
        const transmutationDropdown = document.getElementById('transmutation_table');
        
        if (lockCheckbox && transmutationDropdown) {
            lockCheckbox.addEventListener('change', function() {
                transmutationDropdown.disabled = this.checked;
                console.log(this.checked ? 'Dropdown disabled' : 'Dropdown enabled');
            });
        }
    });

    // MAPEH Component Switcher function
    function switchMAPEHComponent(selectedComponent) {
        console.log('MAPEH component selected:', selectedComponent);
        
        // Hide all component displays first
        document.querySelectorAll('.mapeh-average-display').forEach(function(el) {
            el.classList.add('d-none');
        });
        
        document.querySelectorAll('.mapeh-component-single').forEach(function(el) {
            el.classList.add('d-none');
        });
        
        document.querySelectorAll('.mapeh-component-badge').forEach(function(el) {
            el.classList.add('d-none');
        });
        
        if (selectedComponent === 'average') {
            // Show only the average MAPEH grade
            document.querySelectorAll('.mapeh-average-display').forEach(function(el) {
                el.classList.remove('d-none');
            });
            
            document.querySelectorAll('.mapeh-component-badge').forEach(function(el) {
                el.classList.remove('d-none');
            });
        } else {
            // Show only the selected component
            document.querySelectorAll('.mapeh-component-single[data-component="' + selectedComponent + '"]').forEach(function(el) {
                el.classList.remove('d-none');
            });
        }
    }

        // Print student report function
        function printStudentReport(studentId) {
            var printWindow = window.open('{{ url("teacher/grades/print-report") }}/' + studentId + '?subject_id={{ $selectedSubject->id ?? 0 }}&term={{ $selectedTerm }}', '_blank');
            printWindow.addEventListener('load', function() {
                printWindow.print();
            });
        }
        
        // Function to filter sections based on selected subject
        function filterSectionsBySubject(subjectId) {
            console.log('Filtering sections for subject ID:', subjectId);
            const sectionDropdown = document.getElementById('section_id');
            if (!sectionDropdown) return;
            
            // Get all section options
            const sectionOptions = Array.from(sectionDropdown.options);
            
            // Filter options based on data attributes
            sectionOptions.forEach(option => {
                const subjectIds = option.getAttribute('data-subjects') || '';
                
                // Only show sections that have the subject assigned, regardless of adviser status
                if (subjectIds.split(',').includes(subjectId)) {
                    option.style.display = '';
                } else {
                    option.style.display = 'none';
                }
            });
            
            // If current selection is hidden, select first visible option
            if (sectionDropdown.selectedOptions[0].style.display === 'none') {
                const firstVisibleOption = sectionOptions.find(option => option.style.display !== 'none');
                if (firstVisibleOption) {
                    sectionDropdown.value = firstVisibleOption.value;
                }
            }
            
            // If no options are visible at all, show all options as fallback
            const visibleOptions = sectionOptions.filter(option => option.style.display !== 'none');
            if (visibleOptions.length === 0) {
                sectionOptions.forEach(option => option.style.display = '');
            }
        }
        
    $(document).ready(function() {
            $('#toggleTableView').on('click', function() {
                $('#gradeTable').toggleClass('compact-view');
            });
            
        // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
            // Initialize section filtering on page load
            const subjectSelect = document.getElementById('subject_id');
            if (subjectSelect) {
                // Filter sections based on initial subject selection
                filterSectionsBySubject(subjectSelect.value);
                
                // Filter sections when subject selection changes
                subjectSelect.addEventListener('change', function() {
                    filterSectionsBySubject(this.value);
                });
            }
            
            // Print grades button
            $('#printGradesBtn').on('click', function() {
                window.print();
            });
    });
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

                            <!-- Special View for MAPEH Subject -->
                            @if($selectedSubject && $selectedSubject->getIsMAPEHAttribute())
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-bold text-primary">
                                            <i class="fas fa-chart-bar me-2"></i> MAPEH Component Grades
                                        </h6>
                                        <span class="badge bg-primary">{{ $terms[$selectedTerm] }}</span>
                                    </div>
                                    <div class="card-body p-0">
                                        <!-- MAPEH Components Summary -->
                                        <div class="d-flex flex-wrap border-bottom">
                                            @php
                                                // Get all MAPEH components
                                                $components = $selectedSubject->components;
                                                $componentKeys = ['music', 'arts', 'pe', 'health'];
                                                $componentMap = [];
                                                $componentGrades = [];
                                                $validComponentCount = 0;
                                                
                                                // Map components to their keys
                                                foreach($components as $component) {
                                                    $componentName = strtolower($component->name);
                                                    $componentKey = '';
                                                    
                                                    if (stripos($componentName, 'music') !== false) {
                                                        $componentKey = 'music';
                                                    } elseif (stripos($componentName, 'art') !== false) {
                                                        $componentKey = 'arts';
                                                    } elseif (stripos($componentName, 'physical') !== false || stripos($componentName, 'pe') !== false) {
                                                        $componentKey = 'pe';
                                                    } elseif (stripos($componentName, 'health') !== false) {
                                                        $componentKey = 'health';
                                                    }
                                                    
                                                    if (!empty($componentKey)) {
                                                        $componentMap[$componentKey] = $component;
                                                    }
                                                }
                                                
                                                // Calculate grades for each component
                                                foreach($componentKeys as $key) {
                                                    if (!isset($componentMap[$key])) continue;
                                                    
                                                    $component = $componentMap[$key];
                                                    
                                                    // Get component grades
                                                    $compWrittenWorks = \App\Models\Grade::where('student_id', $student->id)
                                                        ->where('subject_id', $component->id)
                                                        ->where('term', $selectedTerm)
                                                        ->where('grade_type', 'written_work')
                                                        ->get();
                                                    
                                                    $compPerformanceTasks = \App\Models\Grade::where('student_id', $student->id)
                                                        ->where('subject_id', $component->id)
                                                        ->where('term', $selectedTerm)
                                                        ->where('grade_type', 'performance_task')
                                                        ->get();
                                                    
                                                    $compQuarterlyAssessment = \App\Models\Grade::where('student_id', $student->id)
                                                        ->where('subject_id', $component->id)
                                                        ->where('term', $selectedTerm)
                                                        ->where('grade_type', 'quarterly')
                                                        ->first();
                                                    
                                                    // Calculate grades
                                                    $compWrittenWorksAvg = $compWrittenWorks->count() > 0 ? 
                                                        $compWrittenWorks->average(function($grade) {
                                                            return ($grade->score / $grade->max_score) * 100;
                                                        }) : 0;
                                                    
                                                    $compPerformanceTasksAvg = $compPerformanceTasks->count() > 0 ? 
                                                        $compPerformanceTasks->average(function($grade) {
                                                            return ($grade->score / $grade->max_score) * 100;
                                                        }) : 0;
                                                    
                                                    $compQuarterlyScore = $compQuarterlyAssessment ? 
                                                        ($compQuarterlyAssessment->score / $compQuarterlyAssessment->max_score) * 100 : 0;
                                                    
                                                    // Get component's grade configuration
                                                    $compConfig = \App\Models\GradeConfiguration::where('subject_id', $component->id)->first();
                                                    
                                                    $cWrittenWorkPercentage = $compConfig ? 
                                                        $compConfig->written_work_percentage : $writtenWorkPercentage;
                                                        
                                                    $cPerformanceTaskPercentage = $compConfig ? 
                                                        $compConfig->performance_task_percentage : $performanceTaskPercentage;
                                                        
                                                    $cQuarterlyAssessmentPercentage = $compConfig ? 
                                                        $compConfig->quarterly_assessment_percentage : $quarterlyAssessmentPercentage;
                                                
                                                // Calculate component final grade
                                                $compFinalGrade = 0;
                                                $hasGrades = false;
                                                
                                                if ($compWrittenWorksAvg > 0) {
                                                    $compFinalGrade += ($compWrittenWorksAvg * ($cWrittenWorkPercentage / 100));
                                                    $hasGrades = true;
                                                }
                                                
                                                if ($compPerformanceTasksAvg > 0) {
                                                    $compFinalGrade += ($compPerformanceTasksAvg * ($cPerformanceTaskPercentage / 100));
                                                    $hasGrades = true;
                                                }
                                                
                                                if ($compQuarterlyScore > 0) {
                                                    $compFinalGrade += ($compQuarterlyScore * ($cQuarterlyAssessmentPercentage / 100));
                                                    $hasGrades = true;
                                                }
                                                
                                                if ($hasGrades) {
                                                    $componentGrades[$key] = [
                                                        'initial' => round($compFinalGrade, 1),
                                                        'transmuted' => getTransmutedGrade(round($compFinalGrade, 1), request('transmutation_table', 1)),
                                                        'written' => round($compWrittenWorksAvg * ($cWrittenWorkPercentage / 100), 1),
                                                        'performance' => round($compPerformanceTasksAvg * ($cPerformanceTaskPercentage / 100), 1),
                                                        'quarterly' => round($compQuarterlyScore * ($cQuarterlyAssessmentPercentage / 100), 1),
                                                        'written_count' => $compWrittenWorks->count(),
                                                        'performance_count' => $compPerformanceTasks->count(),
                                                        'has_quarterly' => $compQuarterlyAssessment ? true : false
                                                    ];
                                                    $validComponentCount++;
                                                }
                                            }
                                            
                                            // Calculate MAPEH average
                                            $mapehAverage = 0;
                                            if ($validComponentCount > 0) {
                                                $totalGrade = 0;
                                                foreach($componentGrades as $grade) {
                                                    $totalGrade += $grade['initial'];
                                                }
                                                $mapehAverage = $totalGrade / $validComponentCount;
                                            }
                                            
                                            $mapehRoundedAvg = round($mapehAverage, 1);
                                            $mapehTransmuted = getTransmutedGrade($mapehRoundedAvg, request('transmutation_table', 1));
                                        @endphp
                                        
                                        <!-- Component Cards -->
                                        <div class="col-md-3 border-end p-3">
                                            <div class="text-center">
                                                <h5 class="display-6 fw-bold text-primary mb-0">{{ $mapehTransmuted }}</h5>
                                                <small class="text-muted d-block mb-2">Final Grade</small>
                                                <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                                                    {{ $mapehRoundedAvg }}% Initial
                                                </div>
                                                <hr>
                                                <div class="small text-muted">
                                                    Components: {{ $validComponentCount }}/4
                                                </div>
                                            </div>
                                        </div>
                                        
                                        @foreach($componentKeys as $key)
                                            @php
                                                if (!isset($componentGrades[$key])) continue;
                                                
                                                // Set component styles
                                                $componentClass = 'secondary';
                                                $iconClass = 'fas fa-book';
                                                $componentLabel = 'Unknown';
                                                $bgClass = 'light';
                                                
                                                switch($key) {
                                                    case 'music':
                                                        $componentClass = 'primary';
                                                        $iconClass = 'fas fa-music';
                                                        $componentLabel = 'Music';
                                                        $bgClass = 'primary';
                                                        break;
                                                    case 'arts':
                                                        $componentClass = 'danger';
                                                        $iconClass = 'fas fa-paint-brush';
                                                        $componentLabel = 'Arts';
                                                        $bgClass = 'danger';
                                                        break;
                                                    case 'pe':
                                                        $componentClass = 'success';
                                                        $iconClass = 'fas fa-running';
                                                        $componentLabel = 'P.E.';
                                                        $bgClass = 'success';
                                                        break;
                                                    case 'health':
                                                        $componentClass = 'warning';
                                                        $iconClass = 'fas fa-heartbeat';
                                                        $componentLabel = 'Health';
                                                        $bgClass = 'warning';
                                                        break;
                                                }
                                                
                                                // Set grade class based on value
                                                $gradeClass = 'secondary';
                                                if ($componentGrades[$key]['transmuted'] >= 90) {
                                                    $gradeClass = 'success';
                                                } elseif ($componentGrades[$key]['transmuted'] >= 80) {
                                                    $gradeClass = 'primary';
                                                } elseif ($componentGrades[$key]['transmuted'] >= 75) {
                                                    $gradeClass = 'info';
                                                } elseif ($componentGrades[$key]['transmuted'] > 0) {
                                                    $gradeClass = 'danger';
                                                }
                                            @endphp
                                            
                                            <div class="col border-end p-3">
                                                <div class="text-center">
                                                    <div class="d-flex align-items-center justify-content-center mb-2">
                                                        <div class="rounded-circle bg-{{ $bgClass }} bg-opacity-10 p-2 me-2">
                                                            <i class="{{ $iconClass }} text-{{ $componentClass }}"></i>
                                                        </div>
                                                        <h6 class="mb-0 fw-bold text-{{ $componentClass }}">{{ $componentLabel }}</h6>
                                                    </div>
                                                    <h5 class="display-6 fw-bold text-{{ $gradeClass }} mb-0">{{ $componentGrades[$key]['transmuted'] }}</h5>
                                                    <small class="text-muted d-block mb-2">Quarterly Grade</small>
                                                    <div class="badge bg-{{ $gradeClass }} bg-opacity-10 text-{{ $gradeClass }} px-3 py-2">
                                                        {{ $componentGrades[$key]['initial'] }}% Initial
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <!-- MAPEH Component Breakdown -->
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Component</th>
                                                    <th class="text-center">Written Works</th>
                                                    <th class="text-center">Performance Tasks</th>
                                                    <th class="text-center">Quarterly Assessment</th>
                                                    <th class="text-center">Initial Grade</th>
                                                    <th class="text-center">Quarterly Grade</th>
                                                    <th class="text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($componentKeys as $key)
                                                    @php
                                                        if (!isset($componentGrades[$key]) || !isset($componentMap[$key])) continue;
                                                        
                                                        // Set component styles
                                                        $componentClass = 'secondary';
                                                        $iconClass = 'fas fa-book';
                                                        $componentLabel = 'Unknown';
                                                        
                                                        switch($key) {
                                                            case 'music':
                                                                $componentClass = 'primary';
                                                                $iconClass = 'fas fa-music';
                                                                $componentLabel = 'Music';
                                                                break;
                                                            case 'arts':
                                                                $componentClass = 'danger';
                                                                $iconClass = 'fas fa-paint-brush';
                                                                $componentLabel = 'Arts';
                                                                break;
                                                            case 'pe':
                                                                $componentClass = 'success';
                                                                $iconClass = 'fas fa-running';
                                                                $componentLabel = 'P.E.';
                                                                break;
                                                            case 'health':
                                                                $componentClass = 'warning';
                                                                $iconClass = 'fas fa-heartbeat';
                                                                $componentLabel = 'Health';
                                                                break;
                                                        }
                                                        
                                                        // Set grade class based on value
                                                        $gradeClass = 'secondary';
                                                        if ($componentGrades[$key]['transmuted'] >= 90) {
                                                            $gradeClass = 'success';
                                                        } elseif ($componentGrades[$key]['transmuted'] >= 80) {
                                                            $gradeClass = 'primary';
                                                        } elseif ($componentGrades[$key]['transmuted'] >= 75) {
                                                            $gradeClass = 'info';
                                                        } elseif ($componentGrades[$key]['transmuted'] > 0) {
                                                            $gradeClass = 'danger';
                                                        }
                                                    @endphp
                                                    
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <span class="badge bg-{{ $componentClass }} bg-opacity-10 me-2 p-2">
                                                                    <i class="{{ $iconClass }} text-{{ $componentClass }}"></i>
                                                                </span>
                                                                <span>{{ $componentLabel }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="fw-bold">{{ $componentGrades[$key]['written'] }}%</div>
                                                            <div class="small text-muted">{{ $componentGrades[$key]['written_count'] }} assessments</div>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="fw-bold">{{ $componentGrades[$key]['performance'] }}%</div>
                                                            <div class="small text-muted">{{ $componentGrades[$key]['performance_count'] }} tasks</div>
                                                        </td>
                                                        <td class="text-center">
                                                            @if($componentGrades[$key]['has_quarterly'])
                                                                <div class="fw-bold">{{ $componentGrades[$key]['quarterly'] }}%</div>
                                                                <div class="small text-muted">Exam recorded</div>
                                                            @else
                                                                <span class="badge bg-light text-muted">No data</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="fw-bold">{{ $componentGrades[$key]['initial'] }}%</div>
                                                            <span class="badge bg-{{ $gradeClass }} bg-opacity-25 text-{{ $gradeClass }} small">Initial</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="fw-bold fs-5">{{ $componentGrades[$key]['transmuted'] }}</div>
                                                            <span class="badge bg-{{ $gradeClass }} small">Quarterly</span>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('teacher.grades.create', [
                                                                    'student_id' => $student->id, 
                                                                    'subject_id' => $componentMap[$key]->id, 
                                                                    'term' => $selectedTerm
                                                                ]) }}" 
                                                               class="btn btn-sm btn-outline-{{ $componentClass }}" 
                                                               title="Add Grade">
                                                                <i class="fas fa-plus"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                
                                                <!-- MAPEH Average Row -->
                                                <tr class="table-light fw-bold">
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <span class="badge bg-primary me-2 p-2">
                                                                <i class="fas fa-calculator text-white"></i>
                                                            </span>
                                                            <span>MAPEH Average</span>
                                                        </div>
                                                    </td>
                                                    <td class="text-center" colspan="3">
                                                        <div class="small text-muted">Average of all component grades ({{ $validComponentCount }}/4 components)</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="fw-bold">{{ $mapehRoundedAvg }}%</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="fw-bold fs-5">{{ $mapehTransmuted }}</div>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Grades by Type -->
                        <div class="card">
                            <div class="card-header bg-white">
                                <ul class="nav nav-tabs card-header-tabs" id="gradesTab{{ $student->id }}" role="tablist">
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
                            </div>
                            <div class="card-body">
                                <!-- Grade Summary Card for non-MAPEH subjects -->
                                @if($selectedSubject && !$selectedSubject->getIsMAPEHAttribute())
                                    <div class="card border-0 shadow-sm mb-4">
                                        <div class="card-header py-3 bg-white d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 fw-bold text-primary">
                                                <i class="fas fa-chart-bar me-2"></i> {{ $selectedSubject->name }} Grade Summary
                                            </h6>
                                            <span class="badge bg-primary">{{ $terms[$selectedTerm] }}</span>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                // Calculate grades for display
                                                $writtenWorksAvg = calculateAverage($writtenWorks);
                                                $performanceTasksAvg = calculateAverage($performanceTasks);
                                                $quarterlyScore = $quarterlyAssessment ? ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                                
                                                // Calculate weighted values
                                                $writtenWorksWeighted = $writtenWorksAvg > 0 ? 
                                                    ($writtenWorksAvg * ($writtenWorkPercentage / 100)) : 0;
                                                $performanceTasksWeighted = $performanceTasksAvg > 0 ? 
                                                    ($performanceTasksAvg * ($performanceTaskPercentage / 100)) : 0;
                                                $quarterlyWeighted = $quarterlyScore > 0 ? 
                                                    ($quarterlyScore * ($quarterlyAssessmentPercentage / 100)) : 0;
                                                
                                                // Calculate final grade
                                                $finalGrade = $writtenWorksWeighted + $performanceTasksWeighted + $quarterlyWeighted;
                                                $roundedGrade = round($finalGrade, 1);
                                                
                                                // Get the transmuted grade
                                                $selectedTableId = request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1));
                                                $transmutedGrade = getTransmutedGrade($roundedGrade, $selectedTableId);
                                                
                                                // Set grade class based on value
                                                $gradeClass = 'secondary';
                                                if ($transmutedGrade >= 90) {
                                                    $gradeClass = 'success';
                                                } elseif ($transmutedGrade >= 80) {
                                                    $gradeClass = 'primary';
                                                } elseif ($transmutedGrade >= 75) {
                                                    $gradeClass = 'info';
                                                } elseif ($transmutedGrade > 0) {
                                                    $gradeClass = 'danger';
                                                }
                                            @endphp
                                            
                                            <div class="row">
                                                <!-- Final Grade -->
                                                <div class="col-md-4 border-end">
                                                    <div class="text-center px-3 py-3">
                                                        <h5 class="display-5 fw-bold text-{{ $gradeClass }} mb-0">{{ $transmutedGrade }}</h5>
                                                        <div class="mt-2 mb-3">
                                                            <span class="badge bg-{{ $gradeClass }} px-3 py-2">Quarterly Grade</span>
                                                        </div>
                                                        <div class="badge bg-{{ $gradeClass }} bg-opacity-10 text-{{ $gradeClass }} px-3 py-2 fs-6 mb-3">
                                                            {{ $roundedGrade }}% Initial Grade
                                                        </div>
                                                        <div class="small text-muted">
                                                            Using Transmutation Table {{ $selectedTableId }}
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Grade Calculation Breakdown -->
                                                <div class="col-md-8">
                                                    <div class="py-3">
                                                        <h6 class="fw-bold mb-3">Grade Components</h6>
                                                        
                                                        <!-- Written Works -->
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge bg-primary me-2">
                                                                        <i class="fas fa-pen"></i>
                                                                    </span>
                                                                    <span class="fw-medium">Written Works</span>
                                                                </div>
                                                                <div>
                                                                    <span class="badge bg-primary me-2">{{ $writtenWorkPercentage }}%</span>
                                                                    <span class="fw-bold">{{ number_format($writtenWorksWeighted, 1) }}%</span>
                                                                </div>
                                                            </div>
                                                            <div class="progress rounded-pill mb-1" style="height: 8px">
                                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                                    style="width: {{ max(5, $writtenWorksWeighted / max(0.1, $finalGrade) * 100) }}%" 
                                                                    aria-valuenow="{{ $writtenWorksWeighted }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="small text-muted mb-3">
                                                                {{ is_array($writtenWorks) ? count($writtenWorks) : $writtenWorks->count() }} assessments | Average score: {{ number_format($writtenWorksAvg, 1) }}%
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Performance Tasks -->
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge bg-success me-2">
                                                                        <i class="fas fa-project-diagram"></i>
                                                                    </span>
                                                                    <span class="fw-medium">Performance Tasks</span>
                                                                </div>
                                                                <div>
                                                                    <span class="badge bg-success me-2">{{ $performanceTaskPercentage }}%</span>
                                                                    <span class="fw-bold">{{ number_format($performanceTasksWeighted, 1) }}%</span>
                                                                </div>
                                                            </div>
                                                            <div class="progress rounded-pill mb-1" style="height: 8px">
                                                                <div class="progress-bar bg-success" role="progressbar" 
                                                                    style="width: {{ max(5, $performanceTasksWeighted / max(0.1, $finalGrade) * 100) }}%" 
                                                                    aria-valuenow="{{ $performanceTasksWeighted }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="small text-muted mb-3">
                                                                {{ is_array($performanceTasks) ? count($performanceTasks) : $performanceTasks->count() }} tasks | Average score: {{ number_format($performanceTasksAvg, 1) }}%
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Quarterly Assessment -->
                                                        <div class="mb-3">
                                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                                <div class="d-flex align-items-center">
                                                                    <span class="badge bg-warning me-2">
                                                                        <i class="fas fa-file-alt"></i>
                                                                    </span>
                                                                    <span class="fw-medium">Quarterly Assessment</span>
                                                                </div>
                                                                <div>
                                                                    <span class="badge bg-warning me-2">{{ $quarterlyAssessmentPercentage }}%</span>
                                                                    <span class="fw-bold">{{ number_format($quarterlyWeighted, 1) }}%</span>
                                                                </div>
                                                            </div>
                                                            <div class="progress rounded-pill mb-1" style="height: 8px">
                                                                <div class="progress-bar bg-warning" role="progressbar" 
                                                                    style="width: {{ max(5, $quarterlyWeighted / max(0.1, $finalGrade) * 100) }}%" 
                                                                    aria-valuenow="{{ $quarterlyWeighted }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="small text-muted mb-3">
                                                                @if($quarterlyAssessment)
                                                                    Score: {{ $quarterlyAssessment->score }}/{{ $quarterlyAssessment->max_score }} ({{ number_format($quarterlyScore, 1) }}%)
                                                                @else
                                                                    No quarterly assessment recorded
                                                                @endif
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Actions -->
                                                        <div class="mt-4 d-flex gap-2 justify-content-end">
                                                            <a href="{{ route('teacher.grades.create', [
                                                                'student_id' => $student->id, 
                                                                'subject_id' => $selectedSubject->id, 
                                                                'term' => $selectedTerm,
                                                                'grade_type' => 'written_work'
                                                            ]) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="fas fa-plus-circle me-1"></i> Add Written Work
                                                            </a>
                                                            <a href="{{ route('teacher.grades.create', [
                                                                'student_id' => $student->id, 
                                                                'subject_id' => $selectedSubject->id, 
                                                                'term' => $selectedTerm,
                                                                'grade_type' => 'performance_task'
                                                            ]) }}" class="btn btn-sm btn-outline-success">
                                                                <i class="fas fa-plus-circle me-1"></i> Add Task
                                                            </a>
                                                            <a href="{{ route('teacher.grades.create', [
                                                                'student_id' => $student->id, 
                                                                'subject_id' => $selectedSubject->id, 
                                                                'term' => $selectedTerm,
                                                                'grade_type' => 'quarterly'
                                                            ]) }}" class="btn btn-sm btn-outline-warning">
                                                                <i class="fas fa-plus-circle me-1"></i> Add Exam
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
                                                                    <div class="small text-muted">
                                                                        {{ number_format(($grade->score / $grade->max_score) * 100, 1) }}% 
                                                                        <span class="text-primary">({{ number_format((($grade->score / $grade->max_score) * 100) * ($writtenWorkPercentage / 100), 1) }}% weighted)</span>
                                                                    </div>
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
                                                                    <div class="small text-muted">
                                                                        {{ number_format(($grade->score / $grade->max_score) * 100, 1) }}% 
                                                                        <span class="text-success">({{ number_format((($grade->score / $grade->max_score) * 100) * ($performanceTaskPercentage / 100), 1) }}% weighted)</span>
                                                                    </div>
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
                                                                <div class="small text-muted">
                                                                    {{ number_format(($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100, 1) }}%
                                                                    <span class="text-warning">({{ number_format((($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100) * ($quarterlyAssessmentPercentage / 100), 1) }}% weighted)</span>
                                                                </div>
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
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-user-graduate me-2"></i> {{ $studentData['student']->first_name }} {{ $studentData['student']->last_name }} - Comprehensive Grade Report
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <!-- Final Average Summary -->
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
                                
                                // Get the actual weighted score from written works
                                $writtenTotal = 0;
                                $writtenMaxTotal = 0;
                                
                                foreach($writtenWorks as $grade) {
                                    $writtenTotal += $grade->score;
                                    $writtenMaxTotal += $grade->max_score;
                                }
                                
                                $writtenPercentage = $writtenMaxTotal > 0 ? 
                                    ($writtenTotal / $writtenMaxTotal) * 100 : 0;
                                
                                // Get the actual weighted score from performance tasks
                                $performanceTotal = 0;
                                $performanceMaxTotal = 0;
                                
                                foreach($performanceTasks as $grade) {
                                    $performanceTotal += $grade->score;
                                    $performanceMaxTotal += $grade->max_score;
                                }
                                
                                $performancePercentage = $performanceMaxTotal > 0 ? 
                                    ($performanceTotal / $performanceMaxTotal) * 100 : 0;
                                
                                // Use the actual quarterly score
                                $quarterlyPercentage = $quarterlyAssessment ? 
                                    ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                
                                // Get subject's grade configuration
                                $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                
                                $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                
                                // Calculate weighted final grade for this subject
                                $subjectFinalGrade = 0;
                                $hasAnyComponents = false;
                                
                                if ($writtenPercentage > 0) {
                                    $subjectFinalGrade += ($writtenPercentage * ($writtenWorkPercentage / 100));
                                    $hasAnyComponents = true;
                                }
                                if ($performancePercentage > 0) {
                                    $subjectFinalGrade += ($performancePercentage * ($performanceTaskPercentage / 100));
                                    $hasAnyComponents = true;
                                }
                                if ($quarterlyPercentage > 0) {
                                    $subjectFinalGrade += ($quarterlyPercentage * ($quarterlyAssessmentPercentage / 100));
                                    $hasAnyComponents = true;
                                }
                                
                                if ($hasAnyComponents) {
                                    $calculatedSubjectFinalGrades[$subjectId] = round($subjectFinalGrade, 1);
                                    $totalSubjectGrade += $subjectFinalGrade;
                                    $validSubjectCount++;
                                }
                            }
                            
                            $finalAverage = $validSubjectCount > 0 ? round($totalSubjectGrade / $validSubjectCount, 1) : 0;
                            
                            // Get the transmuted final average
                            $transmutedFinalAverage = getTransmutedGrade($finalAverage, $selectedTransmutationTable);
                            
                            // Determine grade status text
                            $gradeStatus = 'Not Graded';
                            $avgGradeClass = 'secondary';
                            
                            if ($finalAverage > 0) {
                                if ($transmutedFinalAverage >= 90) {
                                $avgGradeClass = 'success';
                                $gradeStatus = 'Excellent';
                                } elseif ($transmutedFinalAverage >= 85) {
                                $avgGradeClass = 'primary';
                                $gradeStatus = 'Very Good';
                                } elseif ($transmutedFinalAverage >= 80) {
                                $avgGradeClass = 'info';
                                $gradeStatus = 'Good';
                                } elseif ($transmutedFinalAverage >= 75) {
                                $avgGradeClass = 'warning';
                                $gradeStatus = 'Passed';
                            } elseif ($finalAverage > 0) {
                                $avgGradeClass = 'danger';
                                $gradeStatus = 'Failed';
                                }
                            }
                        @endphp
                        
                        <div class="row align-items-center m-0 p-4 border-bottom">
                            <div class="col-md-5">
                                <h5 class="fw-bold mb-2">Overall Academic Performance</h5>
                                <p class="text-muted mb-0">{{ $terms[$selectedTerm] }} | Final average across {{ $validSubjectCount }} subjects</p>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="bg-{{ $avgGradeClass }} bg-opacity-10 p-3 rounded-3">
                                    <div class="display-3 fw-bold text-{{ $avgGradeClass }}">{{ $transmutedFinalAverage }}</div>
                                    <div class="small text-muted mb-2">(Initial: {{ $finalAverage }}%)</div>
                                    <span class="badge bg-{{ $avgGradeClass }} px-3 py-2">{{ $gradeStatus }}</span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card shadow-sm border-0 bg-light">
                                    <div class="card-body p-3">
                                        <h6 class="fw-bold mb-2">Calculated using:</h6>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-book me-2 text-primary"></i>
                                            <div>
                                                <span class="text-muted">Subjects:</span> 
                                                <span class="fw-bold">{{ $validSubjectCount }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-calendar-alt me-2 text-primary"></i>
                                            <div>
                                                <span class="text-muted">Academic Term:</span> 
                                                <span class="fw-bold">{{ $terms[$selectedTerm] }}</span>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-table me-2 text-primary"></i>
                                            <div>
                                                <span class="text-muted">Transmutation:</span> 
                                                <span class="fw-bold">Table {{ $selectedTransmutationTable }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Subject Grades Section -->
                        <div class="p-4">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Subject Performance</h6>
                            
                            <div class="row">
                                @foreach($studentData['subject_grades'] as $subjectId => $subjectGrade)
                                    @php
                                        // Calculate all values for this subject
                                        $writtenWorks = collect($subjectGrade['written_works']);
                                        $performanceTasks = collect($subjectGrade['performance_tasks']);
                                        $quarterlyAssessment = $subjectGrade['quarterly_assessment'];
                                        
                                        // Get the actual weighted score from written works
                                        $writtenTotal = 0;
                                        $writtenMaxTotal = 0;
                                        
                                        foreach($writtenWorks as $grade) {
                                            $writtenTotal += $grade->score;
                                            $writtenMaxTotal += $grade->max_score;
                                        }
                                        
                                        $writtenPercentage = $writtenMaxTotal > 0 ? 
                                            ($writtenTotal / $writtenMaxTotal) * 100 : 0;
                                        
                                        // Get the actual weighted score from performance tasks
                                        $performanceTotal = 0;
                                        $performanceMaxTotal = 0;
                                        
                                        foreach($performanceTasks as $grade) {
                                            $performanceTotal += $grade->score;
                                            $performanceMaxTotal += $grade->max_score;
                                        }
                                        
                                        $performancePercentage = $performanceMaxTotal > 0 ? 
                                            ($performanceTotal / $performanceMaxTotal) * 100 : 0;
                                        
                                        // Use the actual quarterly score
                                        $quarterlyPercentage = $quarterlyAssessment ? 
                                            ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                        
                                        // Get subject's grade configuration
                                        $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();
                                        
                                        $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                        $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                        $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;
                                        
                                        // Calculate weighted final grade
                                        $finalGrade = 0;
                                        if ($writtenPercentage > 0) {
                                            $finalGrade += ($writtenPercentage * ($writtenWorkPercentage / 100));
                                        }
                                        if ($performancePercentage > 0) {
                                            $finalGrade += ($performancePercentage * ($performanceTaskPercentage / 100));
                                        }
                                        if ($quarterlyPercentage > 0) {
                                            $finalGrade += ($quarterlyPercentage * ($quarterlyAssessmentPercentage / 100));
                                        }
                                        
                                        $avgGrade = round($finalGrade, 1);
                                        $transmutedGrade = getTransmutedGrade($avgGrade, $selectedTransmutationTable);
                                        
                                        // Grade color class
                                        $gradeClass = 'secondary';
                                        if ($transmutedGrade >= 90) {
                                            $gradeClass = 'success';
                                        } elseif ($transmutedGrade >= 80) {
                                            $gradeClass = 'primary';
                                        } elseif ($transmutedGrade >= 75) {
                                            $gradeClass = 'info';
                                        } elseif ($transmutedGrade > 0) {
                                            $gradeClass = 'danger';
                                        }

                                        $hasGrades = $writtenWorks->count() > 0 || $performanceTasks->count() > 0 || $quarterlyAssessment;
                                    @endphp
                                    
                                    <div class="col-md-6 mb-3">
                                        <div class="card shadow-sm h-100">
                                            <div class="card-header bg-light py-2 d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0 fw-bold">{{ $subjectGrade['subject_name'] }}</h6>
                                                @if($hasGrades)
                                                    <span class="badge bg-{{ $gradeClass }} px-3 py-2">{{ $transmutedGrade }}</span>
                                                @else
                                                    <span class="badge bg-secondary px-3 py-2">Not Graded</span>
                                                @endif
                                            </div>
                                            <div class="card-body">
                                                @if($hasGrades)
                                                    <div class="d-flex justify-content-around mb-3">
                                                        <div class="text-center">
                                                            <div class="small fw-bold text-primary mb-1">Written</div>
                                                            <div class="badge bg-light text-dark border px-2 py-1">
                                                                @php
                                                                    // Get the actual weighted score from written works
                                                                    $writtenTotal = 0;
                                                                    $writtenMaxTotal = 0;
                                                                    
                                                                    foreach($writtenWorks as $grade) {
                                                                        $writtenTotal += $grade->score;
                                                                        $writtenMaxTotal += $grade->max_score;
                                                                    }
                                                                    
                                                                    $writtenPercentage = $writtenMaxTotal > 0 ? 
                                                                        ($writtenTotal / $writtenMaxTotal) * 100 : 0;
                                                                    
                                                                    // Format to match the individual subject view
                                                                    echo number_format($writtenPercentage, 1);
                                                                @endphp%
                                                            </div>
                                                            <div class="small text-muted">{{ $writtenWorkPercentage }}% weight</div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="small fw-bold text-success mb-1">Performance</div>
                                                            <div class="badge bg-light text-dark border px-2 py-1">
                                                                @php
                                                                    // Get the actual weighted score from performance tasks
                                                                    $performanceTotal = 0;
                                                                    $performanceMaxTotal = 0;
                                                                    
                                                                    foreach($performanceTasks as $grade) {
                                                                        $performanceTotal += $grade->score;
                                                                        $performanceMaxTotal += $grade->max_score;
                                                                    }
                                                                    
                                                                    $performancePercentage = $performanceMaxTotal > 0 ? 
                                                                        ($performanceTotal / $performanceMaxTotal) * 100 : 0;
                                                                    
                                                                    // Format to match the individual subject view
                                                                    echo number_format($performancePercentage, 1);
                                                                @endphp%
                                                            </div>
                                                            <div class="small text-muted">{{ $performanceTaskPercentage }}% weight</div>
                                                        </div>
                                                        <div class="text-center">
                                                            <div class="small fw-bold text-warning mb-1">Quarterly</div>
                                                            <div class="badge bg-light text-dark border px-2 py-1">
                                                                @php
                                                                    // Use the actual quarterly score
                                                                    $quarterlyPercentage = $quarterlyAssessment ? 
                                                                        ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;
                                                                    
                                                                    // Format to match the individual subject view
                                                                    echo number_format($quarterlyPercentage, 1);
                                                                @endphp%
                                                            </div>
                                                            <div class="small text-muted">{{ $quarterlyAssessmentPercentage }}% weight</div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Initial Grade:</span>
                                                        <span class="fw-bold">{{ $avgGrade }}%</span>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="text-muted">Transmuted (Table {{ $selectedTransmutationTable }}):</span>
                                                        <span class="fw-bold text-{{ $gradeClass }}">{{ $transmutedGrade }}</span>
                                                    </div>
                                                @else
                                                    <div class="text-center text-muted py-3">
                                                        <i class="fas fa-exclamation-circle mb-2" style="font-size: 2rem;"></i>
                                                        <p class="mb-0">No grades have been entered for this subject.</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Grade Calculation Section -->
                        <div class="p-4 bg-light border-top">
                            <h6 class="fw-bold mb-3">Grade Calculation Details</h6>
                            <div class="card shadow-sm border-0">
                                <div class="card-body">
                                    <div class="mb-3">
                                        <h6 class="text-primary mb-2"><i class="fas fa-calculator me-2"></i>Initial Grade:</h6>
                                        <div class="table-responsive">
                                            <table class="table table-sm table-borderless">
                                                <thead class="text-muted">
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th>Initial Grade</th>
                                                        <th>Weight</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($calculatedSubjectFinalGrades as $subjectId => $grade)
                                                        <tr>
                                                            <td>{{ $studentData['subject_grades'][$subjectId]['subject_name'] }}</td>
                                                            <td>{{ $grade }}%</td>
                                                            <td>{{ number_format(100/$validSubjectCount, 1) }}%</td>
                                                        </tr>
                                                    @endforeach
                                                    <tr class="fw-bold border-top">
                                                        <td colspan="2" class="text-end">Final Initial Grade:</td>
                                                        <td>{{ $finalAverage }}%</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <h6 class="text-primary mb-2"><i class="fas fa-exchange-alt me-2"></i>Transmutation (Table {{ $selectedTransmutationTable }}):</h6>
                                        <div class="d-flex align-items-center justify-content-center">
                                            <div class="badge bg-secondary bg-opacity-10 text-dark px-3 py-2 fs-5">{{ $finalAverage }}%</div>
                                            <i class="fas fa-long-arrow-alt-right mx-3 text-muted"></i>
                                            <div class="badge bg-{{ $avgGradeClass }} px-3 py-2 fs-5">{{ $transmutedFinalAverage }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

        <!-- Comprehensive View Modals -->
        @if(isset($students) && count($students) > 0 && request()->has('view_all'))
            @foreach($students as $index => $studentData) 
                @php
                    $student = $studentData['student'];
                @endphp
                <!-- Student Details Modal for Comprehensive View -->
                <div class="modal fade" id="studentDetailsModal{{ $student->id }}" tabindex="-1" aria-labelledby="comprehensiveDetailsModal{{ $student->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header bg-primary text-white">
                                <h5 class="modal-title" id="comprehensiveDetailsModal{{ $student->id }}">
                                    <i class="fas fa-user-graduate me-2"></i> {{ $student->first_name }} {{ $student->last_name }}'s Detailed Grades
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

                                <!-- Detailed Subject Grades -->
                                <div class="card shadow-sm mb-3">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0 fw-bold">All Subject Grades ({{ $terms[$selectedTerm] }})</h6>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Subject</th>
                                                        <th class="text-center">Written Works</th>
                                                        <th class="text-center">Performance Tasks</th>
                                                        <th class="text-center">Quarterly Exam</th>
                                                        <th class="text-center">Initial Grade</th>
                                                        <th class="text-center">Quarterly Grade</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($studentData['subject_grades'] ?? [] as $subjectId => $subjectGrade)
                                                        @php
                                                            $subject = \App\Models\Subject::find($subjectId);
                                                            if (!$subject) continue;
                                                            
                                                            // Get grade components
                                                            $writtenWorksAvg = $subjectGrade['written_works_avg'] ?? 0;
                                                            $performanceTasksAvg = $subjectGrade['performance_tasks_avg'] ?? 0;
                                                            $quarterlyScore = $subjectGrade['quarterly_score'] ?? 0;
                                                            $finalGrade = $subjectGrade['final_grade'] ?? 0;
                                                            $transmutedGrade = $subjectGrade['transmuted_grade'] ?? 0;
                                                            
                                                            // Determine grade status and color
                                                            $gradeClass = 'secondary';
                                                            if ($transmutedGrade >= 90) {
                                                                $gradeClass = 'success';
                                                            } elseif ($transmutedGrade >= 85) {
                                                                $gradeClass = 'primary';
                                                            } elseif ($transmutedGrade >= 80) {
                                                                $gradeClass = 'info';
                                                            } elseif ($transmutedGrade >= 75) {
                                                                $gradeClass = 'warning';
                                                            } else {
                                                                $gradeClass = 'danger';
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                <strong>{{ $subject->name }}</strong>
                                                                <div class="small text-muted">{{ $subject->code }}</div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="fw-bold">{{ round($writtenWorksAvg, 1) }}%</div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="fw-bold">{{ round($performanceTasksAvg, 1) }}%</div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="fw-bold">{{ round($quarterlyScore, 1) }}%</div>
                                                            </td>
                                                            <td class="text-center">
                                                                <div class="fw-bold">{{ round($finalGrade, 1) }}%</div>
                                                            </td>
                                                            <td class="text-center">
                                                                <span class="badge bg-{{ $gradeClass }} grade-badge">{{ $transmutedGrade }}</span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Transmutation Information -->
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> Grades are transmuted using 
                                    {{ request('transmutation_table', 1) == 1 ? 'DepEd Order 8 s. 2015' : 'Custom Transmutation' }}.
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
        
@endsection 