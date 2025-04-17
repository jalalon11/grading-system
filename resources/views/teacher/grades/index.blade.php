@extends('layouts.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/grades.css') }}">
<style>
    /* Assessment Modal Styles */
    .assessment-option {
        transition: transform 0.2s, box-shadow 0.2s;
        border: none !important;
        margin-bottom: 0.5rem;
    }

    .bg-light-blue {
        background-color: rgba(13, 110, 253, 0.05);
    }

    .bg-light-green {
        background-color: rgba(25, 135, 84, 0.05);
    }

    .bg-light-yellow {
        background-color: rgba(255, 193, 7, 0.05);
    }

    /* Mobile styles for assessment modal */
    @media (max-width: 768px) {
        .modal-dialog {
            margin: 0.5rem;
        }

        .modal-body {
            padding: 1rem;
        }

        .assessment-option .card-body {
            padding: 0.75rem;
        }
    }

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

    /* Dark mode styles */
    .dark #gradeTable thead th {
        background-color: var(--bg-card-header) !important;
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark #gradeTable th:first-child,
    .dark #gradeTable td:first-child,
    .dark #gradeTable th:nth-child(2),
    .dark #gradeTable td:nth-child(2) {
        background-color: var(--bg-card-header) !important;
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .table-responsive::-webkit-scrollbar-track {
        background: var(--bg-card);
    }

    .dark .table-responsive::-webkit-scrollbar-thumb {
        background: var(--border-color);
    }

    .dark .table-responsive::-webkit-scrollbar-thumb:hover {
        background: #4a4a4a;
    }

    .dark .assessment-card:hover {
        box-shadow: 0 10px 20px rgba(0,0,0,0.5) !important;
    }

    .dark .bg-light-blue {
        background-color: rgba(78, 115, 223, 0.15) !important;
    }

    .dark .bg-light-green {
        background-color: rgba(28, 200, 138, 0.15) !important;
    }

    .dark .bg-light-yellow {
        background-color: rgba(246, 194, 62, 0.15) !important;
    }

    .dark .music-highlight {
        background-color: rgba(13, 110, 253, 0.25);
    }

    .dark .arts-highlight {
        background-color: rgba(220, 53, 69, 0.25);
    }

    .dark .pe-highlight {
        background-color: rgba(25, 135, 84, 0.25);
    }

    .dark .health-highlight {
        background-color: rgba(255, 193, 7, 0.25);
    }

    .dark .badge.bg-light.text-dark {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
        border-color: var(--border-color) !important;
    }

    .dark .badge.bg-secondary.bg-opacity-10.text-dark {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
    }

    .dark .badge.bg-success,
    .dark .badge.bg-primary,
    .dark .badge.bg-warning,
    .dark .badge.bg-danger {
        opacity: 0.9;
    }

    .dark .card-header.bg-white {
        background-color: var(--bg-card-header) !important;
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .card-body.bg-light {
        background-color: var(--bg-card) !important;
        color: var(--text-color);
    }



    /* Fix table header and row issues */
    .dark table thead th,
    .dark .table thead th {
        color: var(--text-color) !important;
        background-color: var(--bg-card-header) !important;
        border-color: var(--border-color) !important;
    }

    .dark table tbody tr,
    .dark .table tbody tr {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
    }

    .dark table tr td,
    .dark .table tr td {
        color: var(--text-color) !important;
        border-color: var(--border-color) !important;
    }

    /* Handle any specific card or table styling that might be causing the white background */
    .dark .card,
    .dark .card-body {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
    }

    .dark tr.bg-white,
    .dark tr[class*="bg-light"],
    .dark td.bg-white,
    .dark td[class*="bg-light"] {
        background-color: var(--bg-card) !important;
    }

    /* Additional specific fixes for the student grades table */
    .dark .student-row,
    .dark .grade-row {
        background-color: var(--bg-card) !important;
    }

    /* More specific fixes for the student grades table */
    .dark #studentGradesTable,
    .dark .student-grades-table,
    .dark table[id*="grade"],
    .dark table[class*="grade"] {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
    }

    .dark #studentGradesTable tr,
    .dark .student-grades-table tr,
    .dark table[id*="grade"] tr,
    .dark table[class*="grade"] tr {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
    }

    .dark #studentGradesTable td,
    .dark .student-grades-table td,
    .dark table[id*="grade"] td,
    .dark table[class*="grade"] td {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
        border-color: var(--border-color) !important;
    }

    /* Target the specific white row with student information */
    .dark tr.student-info-row,
    .dark tr.data-row,
    .dark tr[style*="background-color: white"],
    .dark tr[style*="background-color: #fff"],
    .dark tr[style*="background-color: #ffffff"] {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
    }

    /* Target avatar circles */
    .dark .avatar-circle {
        background-color: var(--border-color) !important;
        color: var(--text-color) !important;
    }



    /* Student grades table row hover effect - blue highlight to match student page */
    .dark table[id*="grade"] tbody tr:hover,
    .dark table[class*="grade"] tbody tr:hover,
    .dark .student-grades-table tbody tr:hover,
    .dark #studentGradesTable tbody tr:hover,
    .dark tr.student-info-row:hover,
    .dark tr.data-row:hover,
    .dark .container-fluid table tbody tr:hover,
    .dark table tbody tr:hover {
        background-color: #242e48 !important; /* Darker navy blue to match student page */
        transition: background-color 0.2s ease !important;
        color: white !important;
    }

    /* Also apply to regular tables and light mode for consistency */
    table[id*="grade"] tbody tr:hover,
    table[class*="grade"] tbody tr:hover,
    .student-grades-table tbody tr:hover,
    #studentGradesTable tbody tr:hover,
    tr.student-info-row:hover,
    tr.data-row:hover,
    .container-fluid table tbody tr:hover,
    table tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.1) !important;
        transition: background-color 0.2s ease !important;
    }

    /* Force hover effect for student rows */
    .dark table tr:hover td,
    .dark .table tr:hover td {
        background-color: #242e48 !important;
    }

    /* Ensure no other styles are overriding our hover effect */
    .dark table tr:hover,
    .dark .table tr:hover {
        background-color: #242e48 !important;
    }

    /* MAPEH Component Tab Styles */
    #mapehComponentTabs .nav-link {
        font-weight: 500;
        color: #6c757d;
        border-radius: 0;
        padding: 0.75rem 1.25rem;
    }

    #mapehComponentTabs .nav-link.active {
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
        background-color: transparent;
    }

    /* Grade Display Animation */
    .mapeh-grade-value, .final-grade-value, .grade-descriptor {
        transition: all 0.3s ease-in-out;
    }

    /* Component-specific grade colors */
    .music-active .mapeh-grade-value,
    .music-active .final-grade-value {
        color: #6f42c1; /* Purple for Music */
    }

    .arts-active .mapeh-grade-value,
    .arts-active .final-grade-value {
        color: #fd7e14; /* Orange for Arts */
    }

    .pe-active .mapeh-grade-value,
    .pe-active .final-grade-value {
        color: #20c997; /* Teal for PE */
    }

    .health-active .mapeh-grade-value,
    .health-active .final-grade-value {
        color: #0dcaf0; /* Cyan for Health */
    }

    /* Dark mode compatibility */
    .dark .mapeh-grade-value,
    .dark .final-grade-value,
    .dark .grade-descriptor {
        color: var(--text-color, #fff) !important;
    }
</style>
@endpush

@section('content')
<!-- Record Grade Modal -->
<div class="modal fade" id="recordGradeModal" tabindex="-1" aria-labelledby="recordGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="recordGradeModalLabel"><i class="fas fa-plus-circle me-2"></i> Record New Grade</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="mb-4">Select an assessment type to create:</p>

                <div class="row g-3">
                    <!-- Written Works -->
                    <div class="col-md-12">
                        <a href="{{ route('teacher.grades.assessment-setup', ['subject_id' => $selectedSubject->id ?? 1, 'term' => $selectedTerm ?? 1, 'grade_type' => 'written_work', 'section_id' => $selectedSectionId ?? 1]) }}" class="text-decoration-none">
                            <div class="card assessment-option bg-light-blue">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-pen text-primary"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Written Works</h6>
                                            <small class="text-muted">Quizzes, homework, and written assessments</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Performance Tasks -->
                    <div class="col-md-12">
                        <a href="{{ route('teacher.grades.assessment-setup', ['subject_id' => $selectedSubject->id ?? 1, 'term' => $selectedTerm ?? 1, 'grade_type' => 'performance_task', 'section_id' => $selectedSectionId ?? 1]) }}" class="text-decoration-none">
                            <div class="card assessment-option bg-light-green">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-tasks text-success"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Performance Tasks</h6>
                                            <small class="text-muted">Projects, presentations, and activities</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Quarterly Assessment -->
                    <div class="col-md-12">
                        <a href="{{ route('teacher.grades.assessment-setup', ['subject_id' => $selectedSubject->id ?? 1, 'term' => $selectedTerm ?? 1, 'grade_type' => 'quarterly', 'section_id' => $selectedSectionId ?? 1]) }}" class="text-decoration-none">
                            <div class="card assessment-option bg-light-yellow">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-file-alt text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">Quarterly Assessment</h6>
                                            <small class="text-muted">Final exams and quarterly tests</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
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
            <a href="#" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#recordGradeModal">
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
                    <!--  Commented Out and will be used for debugging  -->
                    <!-- Comprehensive Grade View Toggle -->
                    <!-- <div class="col-12 mt-3">
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
                    </div> -->
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
                                        @php
                                            $config = App\Models\GradeConfiguration::where('subject_id', $selectedSubject->id)->first();
                                            $writtenWorkPercentage = $config ? $config->written_work_percentage : 30;
                                        @endphp
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
                                        @php
                                            $performanceTaskPercentage = $config ? $config->performance_task_percentage : 50;
                                        @endphp
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
                                        @php
                                            $quarterlyAssessmentPercentage = $config ? $config->quarterly_assessment_percentage : 20;
                                        @endphp
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
            <div class="card-header py-3 bg-white">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-2 mb-md-0">
                    <h5 class="mb-2 mb-md-0 fw-bold text-primary">
                        <i class="fas fa-user-graduate me-2"></i> Student Grades
                    </h5>
                </div>

                <div class="selected-info mt-2">
                    <div class="d-flex flex-column flex-sm-row gap-2 shadow-sm rounded-3 border bg-white p-2">
                        <div class="d-flex align-items-center flex-grow-1 p-1">
                            <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-users text-success"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block">Section</small>
                                <span class="fw-bold">{{ isset($sections) ? $sections->where('id', $selectedSectionId)->first()->name ?? 'N/A' : 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="d-none d-sm-block vr mx-1"></div>
                        <div class="border-top border-sm-0 pt-2 pt-sm-0 mt-1 mt-sm-0 d-sm-none"></div>

                        <div class="d-flex align-items-center flex-grow-1 p-1">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                                <i class="fas fa-book text-primary"></i>
                            </div>
                            <div class="text-truncate">
                                <small class="text-muted d-block">Subject</small>
                                <span class="fw-bold">{{ $selectedSubject->name ?? 'N/A' }}</span>
                            </div>
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
                                                    $isMAPEH = $subject && $subject->getIsMAPEHAttribute();

                                                    // Get subject's grade configuration
                                                    $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first();

                                                    $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 30;
                                                    $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                                    $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 20;

                                                    if ($isMAPEH && $subject->components->count() > 0) {
                                                        // For MAPEH subjects, calculate component grades individually
                                                        $componentGrades = [];
                                                        $componentTransmuted = [];

                                                        foreach ($subject->components as $component) {
                                                            // Get component grades for this term
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

                                                            // Calculate component written works using total score / total max score
                                                            $componentWrittenWorksTotal = 0;
                                                            $componentWrittenWorksMaxTotal = 0;
                                                            foreach($componentWrittenWorks as $grade) {
                                                                $componentWrittenWorksTotal += $grade->score;
                                                                $componentWrittenWorksMaxTotal += $grade->max_score;
                                                            }
                                                            $componentWrittenWorksAvg = $componentWrittenWorksMaxTotal > 0 ?
                                                                ($componentWrittenWorksTotal / $componentWrittenWorksMaxTotal) * 100 : 0;

                                                            // Calculate component performance tasks using total score / total max score
                                                            $componentPerformanceTasksTotal = 0;
                                                            $componentPerformanceTasksMaxTotal = 0;
                                                            foreach($componentPerformanceTasks as $grade) {
                                                                $componentPerformanceTasksTotal += $grade->score;
                                                                $componentPerformanceTasksMaxTotal += $grade->max_score;
                                                            }
                                                            $componentPerformanceTasksAvg = $componentPerformanceTasksMaxTotal > 0 ?
                                                                ($componentPerformanceTasksTotal / $componentPerformanceTasksMaxTotal) * 100 : 0;

                                                            // Calculate component quarterly assessment using total score / total max score
                                                            $componentQuarterlyTotal = 0;
                                                            $componentQuarterlyMaxTotal = 0;
                                                            if ($componentQuarterlyAssessment) {
                                                                $componentQuarterlyTotal += $componentQuarterlyAssessment->score;
                                                                $componentQuarterlyMaxTotal += $componentQuarterlyAssessment->max_score;
                                                            }
                                                            $componentQuarterlyScore = $componentQuarterlyMaxTotal > 0 ?
                                                                ($componentQuarterlyTotal / $componentQuarterlyMaxTotal) * 100 : 0;

                                                            // Get component's grade configuration
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
                                                            $mapehAverage = array_sum($validComponentGrades) / count($validComponentGrades);
                                                            $avgGrade = round($mapehAverage, 1);
                                                            $transmutedGrade = getTransmutedGrade($avgGrade, request('transmutation_table', 1));

                                                            // Grade class based on value
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
                                                            $avgGrade = 0;
                                                            $transmutedGrade = 0;
                                                            $gradeClass = 'secondary';
                                                        }
                                                    } else {
                                                        // Regular subject calculation
                                                        $writtenWorks = collect($subjectGrade['written_works']);
                                                        $performanceTasks = collect($subjectGrade['performance_tasks']);
                                                        $quarterlyAssessment = $subjectGrade['quarterly_assessment'];

                                                        // Calculate written works using total score / total max score method
                                                        $writtenWorksTotal = 0;
                                                        $writtenWorksMaxTotal = 0;
                                                        foreach($writtenWorks as $grade) {
                                                            $writtenWorksTotal += $grade->score;
                                                            $writtenWorksMaxTotal += $grade->max_score;
                                                        }
                                                        $writtenWorksAvg = $writtenWorksMaxTotal > 0 ?
                                                            ($writtenWorksTotal / $writtenWorksMaxTotal) * 100 : 0;

                                                        // Calculate performance tasks using total score / total max score method
                                                        $performanceTasksTotal = 0;
                                                        $performanceTasksMaxTotal = 0;
                                                        foreach($performanceTasks as $grade) {
                                                            $performanceTasksTotal += $grade->score;
                                                            $performanceTasksMaxTotal += $grade->max_score;
                                                        }
                                                        $performanceTasksAvg = $performanceTasksMaxTotal > 0 ?
                                                            ($performanceTasksTotal / $performanceTasksMaxTotal) * 100 : 0;

                                                        // Calculate quarterly assessment using total score / total max score method
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
                                                        $transmutedGrade = getTransmutedGrade($avgGrade, request('transmutation_table', 1));

                                                        // Grade class based on value
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
                                                    <!-- MAPEH Grade Display -->
                                                    <div class="mapeh-grade-display">
                                                        <div class="mb-1">
                                                            <span class="badge bg-{{ $gradeClass }} grade-badge">{{ $transmutedGrade }}</span>
                                                        </div>
                                                        <div class="small text-muted mb-1">Initial: {{ $avgGrade }}%</div>

                                                        <!-- Component badges -->
                                                        <div class="mapeh-components mt-2">
                                                            @foreach(['Music', 'Arts', 'PE', 'Health'] as $componentKey)
                                                                @php
                                                                    $found = false;
                                                                    $componentName = '';
                                                                    $componentGrade = 0;
                                                                    $componentTrans = 0;
                                                                    $componentClass = 'secondary';
                                                                    $iconClass = 'fas fa-book';

                                                                    // Match component key to actual component data
                                                                    foreach($componentGrades as $name => $grade) {
                                                                        $lowerName = strtolower($name);
                                                                        if (
                                                                            ($componentKey == 'Music' && stripos($lowerName, 'music') !== false) ||
                                                                            ($componentKey == 'Arts' && stripos($lowerName, 'art') !== false) ||
                                                                            ($componentKey == 'PE' && (
                                                                                stripos($lowerName, 'physical') !== false ||
                                                                                stripos($lowerName, 'pe') !== false ||
                                                                                stripos($lowerName, 'p.e') !== false
                                                                            )) ||
                                                                            ($componentKey == 'Health' && stripos($lowerName, 'health') !== false)
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
                                                                    switch($componentKey) {
                                                                        case 'Music':
                                                                            $componentClass = 'primary';
                                                                            $iconClass = 'fas fa-music';
                                                                            break;
                                                                        case 'Arts':
                                                                            $componentClass = 'danger';
                                                                            $iconClass = 'fas fa-paint-brush';
                                                                            break;
                                                                        case 'PE':
                                                                            $componentClass = 'success';
                                                                            $iconClass = 'fas fa-running';
                                                                            break;
                                                                        case 'Health':
                                                                            $componentClass = 'warning';
                                                                            $iconClass = 'fas fa-heartbeat';
                                                                            break;
                                                                    }
                                                                @endphp

                                                                @if($found)
                                                                    <span class="badge bg-{{ $componentClass }} bg-opacity-25 text-{{ $componentClass }}"
                                                                          title="{{ $componentName }}: {{ $componentGrade }}%">
                                                                        <i class="{{ $iconClass }}"></i> {{ $componentTrans }}
                                                                    </span>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @else
                                                    <!-- Regular Subject Grade Display -->
                                                    <span class="badge bg-{{ $gradeClass }} grade-badge">{{ $transmutedGrade }}</span>
                                                    <div class="small text-muted">Initial: {{ $avgGrade }}%</div>
                                                @endif
                                            </td>
                                        @endforeach

                                        <!-- Initial Average Column -->
                                        <td class="text-center bg-light">
                                            @php
                                                // Calculate the average grade across all subjects assigned to this section
                                                $totalSubjectGrade = 0;
                                                $validSubjectCount = 0;
                                                $debugInfo = [];

                                                // Get section subjects directly from the current grades data
                                                // (these are guaranteed to be the subjects assigned to this section)
                                                $sectionSubjectIds = array_keys($studentData['subject_grades']);
                                                $debugInfo[] = "Found " . count($sectionSubjectIds) . " subject(s) assigned to this section";

                                                // Special handling for MAPEH subjects
                                                $mapehParentIds = [];
                                                $mapehComponentIds = [];
                                                $mapehComponentGrades = [];

                                                // First identify MAPEH subjects and their components
                                                foreach($sectionSubjectIds as $subjectId) {
                                                    $subject = \App\Models\Subject::find($subjectId);
                                                    if ($subject && $subject->getIsMAPEHAttribute()) {
                                                        $mapehParentIds[] = $subjectId;

                                                        // Get component subjects
                                                        $components = $subject->components;
                                                        foreach($components as $component) {
                                                            $mapehComponentIds[$component->id] = $subjectId; // Map component to parent
                                                        }

                                                        $debugInfo[] = "Found MAPEH subject: " . $subject->name . " with " . $components->count() . " components";
                                                    } else if ($subject && $subject->is_component && $subject->parent_subject_id) {
                                                        $parentSubject = \App\Models\Subject::find($subject->parent_subject_id);
                                                        if ($parentSubject && $parentSubject->getIsMAPEHAttribute()) {
                                                            $mapehComponentIds[$subjectId] = $subject->parent_subject_id;
                                                            $debugInfo[] = "Found MAPEH component: " . $subject->name;
                                                        }
                                                    }
                                                }

                                                // Process all subjects that have grades
                                                foreach($sectionSubjectIds as $subjectId) {
                                                    // Skip MAPEH components as they will be calculated as part of MAPEH
                                                    if (isset($mapehComponentIds[$subjectId])) {
                                                        continue;
                                                    }

                                                    $subject = \App\Models\Subject::find($subjectId);
                                                    $subjectName = $subject ? $subject->name : "Subject ID: ".$subjectId;
                                                    $subjectGrade = $studentData['subject_grades'][$subjectId];

                                                    // Special handling for MAPEH subjects
                                                    if ($subject && $subject->getIsMAPEHAttribute()) {
                                                        // Get all MAPEH component grades
                                                        $componentsWithGrades = 0;
                                                        $componentGradeSum = 0;

                                                        foreach($subject->components as $component) {
                                                            // Check if the student has grades for this component
                                                            $componentGrades = \App\Models\Grade::where('student_id', $studentData['student']->id)
                                                                ->where('subject_id', $component->id)
                                                                ->where('term', $selectedTerm)
                                                                ->get();

                                                            if ($componentGrades->isNotEmpty()) {
                                                                $writtenWorks = $componentGrades->where('grade_type', 'written_work');
                                                                $performanceTasks = $componentGrades->where('grade_type', 'performance_task');
                                                                $quarterlyAssessment = $componentGrades->where('grade_type', 'quarterly')->first();

                                                                $hasGrades = $writtenWorks->count() > 0 || $performanceTasks->count() > 0 || $quarterlyAssessment;

                                                                if ($hasGrades) {
                                                                    // Calculate the component grade the same way as in Performance Report modal

                                                                    // 1. Get Written Works average
                                                                    $writtenWorksAvg = $writtenWorks->count() > 0 ?
                                                                        $writtenWorks->average(function($grade) {
                                                                            return ($grade->score / $grade->max_score) * 100;
                                                                        }) : 0;

                                                                    // 2. Get Performance Tasks average
                                                                    $performanceTasksAvg = $performanceTasks->count() > 0 ?
                                                                        $performanceTasks->average(function($grade) {
                                                                            return ($grade->score / $grade->max_score) * 100;
                                                                        }) : 0;

                                                                    // 3. Get Quarterly Assessment
                                                                    $quarterlyScore = $quarterlyAssessment ?
                                                                        ($quarterlyAssessment->score / $quarterlyAssessment->max_score) * 100 : 0;

                                                                    // Get component's grade configuration
                                                                    $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $component->id)->first();

                                                                    $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 25;
                                                                    $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                                                    $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 25;

                                                                    // Calculate weighted contributions
                                                                    $writtenWorkContribution = $writtenWorksAvg * ($writtenWorkPercentage / 100);
                                                                    $performanceTaskContribution = $performanceTasksAvg * ($performanceTaskPercentage / 100);
                                                                    $quarterlyAssessmentContribution = $quarterlyScore * ($quarterlyAssessmentPercentage / 100);

                                                                    // Final component grade
                                                                    $componentFinalGrade = $writtenWorkContribution + $performanceTaskContribution + $quarterlyAssessmentContribution;

                                                                    // Get component weight from the database or calculate dynamically
                                                                    $componentWeight = $component->component_weight ?: (100 / count($subject->components));
                                                                    $componentGradeSum += $componentFinalGrade * ($componentWeight / 100);
                                                                    $componentsWithGrades++;

                                                                    $debugInfo[] = "MAPEH Component " . $component->name . ": " . round($componentFinalGrade, 1) . "% (WW:" .
                                                                        round($writtenWorkContribution, 1) . " + PT:" .
                                                                        round($performanceTaskContribution, 1) . " + QE:" .
                                                                        round($quarterlyAssessmentContribution, 1) . ", weight: " . $componentWeight . "%)";
                                                                }
                                                            }
                                                        }

                                                        // If we have component grades, calculate MAPEH average
                                                        if ($componentsWithGrades > 0) {
                                                            // Calculate weighted average using actual component weights
                                                            $mapehAverage = $componentGradeSum;

                                                            // If some components are missing, indicate in the debug info
                                                            if ($componentsWithGrades < count($subject->components)) {
                                                                $debugInfo[] = "Note: " . (count($subject->components) - $componentsWithGrades) . " component(s) missing grades";
                                                            }

                                                            $totalSubjectGrade += $mapehAverage;
                                                            $validSubjectCount++;
                                                            $debugInfo[] = "MAPEH Average (from " . $componentsWithGrades . " components): " . round($mapehAverage, 1) . "%";
                                                        } else {
                                                            $debugInfo[] = "MAPEH has no component grades, skipping";
                                                        }
                                                    } else {
                                                        // Regular subjects processing
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
                                                            $debugInfo[] = "Subject " . $subjectName . ": " . round($subjectFinalGrade, 1) . "%";
                                                        } else {
                                                            $debugInfo[] = "Subject " . $subjectName . " has no grades.";
                                                        }
                                                    }
                                                }

                                                $finalAverage = $validSubjectCount > 0 ? $totalSubjectGrade / $validSubjectCount : 0;
                                                $finalAverage = round($finalAverage, 1);
                                                $debugInfo[] = "Total Subjects: " . $validSubjectCount;
                                                $debugInfo[] = "Final Average: " . $finalAverage . "%";

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

                                            <!-- Debug Information -->
                                            {{-- <div class="mt-2 small text-start" style="max-height: 200px; overflow-y: auto;">
                                                @foreach($debugInfo as $info)
                                                    <div class="text-muted">{{ $info }}</div>
                                                @endforeach
                                            </div> --}}
                                        </td>

                                        <!-- Quarterly Average Column -->
                                        <td class="text-center bg-primary bg-opacity-10">
                                            @php
                                                // Use the same finalAverage but get the transmuted grade
                                                $transmutedFinalGrade = getTransmutedGrade($finalAverage, request('transmutation_table', 1));
                                            @endphp

                                            <span class="badge bg-{{ $avgGradeClass }} fs-5 px-3">{{ $transmutedFinalGrade }}</span>
                                        </td>

                                        <!-- Actions Column -->
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#wholeGradeModal{{ $studentData['student']->id }}">
                                                <i class="fas fa-chart-line me-1"></i> Details
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- JavaScript for Table Functionality -->
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // Compact view toggle
                            const compactViewToggle = document.getElementById('compactViewToggle');
                            const gradeTable = document.getElementById('gradeTable');

                            if (compactViewToggle && gradeTable) {
                                compactViewToggle.addEventListener('change', function() {
                                    if (this.checked) {
                                        gradeTable.classList.add('compact-view');
                                    } else {
                                        gradeTable.classList.remove('compact-view');
                                    }
                                });
                            }
                        });
                    </script>
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

                                        // Calculate written works using total score / total max score
                                        $writtenWorksTotal = 0;
                                        $writtenWorksMaxTotal = 0;
                                        foreach($writtenWorks as $grade) {
                                            $writtenWorksTotal += $grade->score;
                                            $writtenWorksMaxTotal += $grade->max_score;
                                        }
                                        $writtenWorksAvg = $writtenWorksMaxTotal > 0 ?
                                            ($writtenWorksTotal / $writtenWorksMaxTotal) * 100 : 0;

                                        // Calculate performance tasks using total score / total max score
                                        $performanceTasksTotal = 0;
                                        $performanceTasksMaxTotal = 0;
                                        foreach($performanceTasks as $grade) {
                                            $performanceTasksTotal += $grade->score;
                                            $performanceTasksMaxTotal += $grade->max_score;
                                        }
                                        $performanceTasksAvg = $performanceTasksMaxTotal > 0 ?
                                            ($performanceTasksTotal / $performanceTasksMaxTotal) * 100 : 0;

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

        $totalScore = 0;
        $totalMaxScore = 0;

        foreach ($grades as $grade) {
            if ($grade->max_score > 0) {
                $totalScore += $grade->score;
                $totalMaxScore += $grade->max_score;
            }
        }

        return $totalMaxScore > 0 ? ($totalScore / $totalMaxScore) * 100 : 0;
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

            <!-- Old modal removed - now using the comprehensive one -->

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
                                    // Get the subject
                                    $subject = \App\Models\Subject::find($subjectId);

                                    // Check if this is a MAPEH subject
                                    $isMAPEH = $subject && $subject->getIsMAPEHAttribute();

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
                                            // Get the subject
                                            $subject = \App\Models\Subject::find($subjectId);

                                            // Check if this is a MAPEH subject
                                            $isMAPEH = $subject && $subject->getIsMAPEHAttribute();

                                            // Handle MAPEH with its components
                                            $mapehComponentGrades = [];
                                            $mapehFinalGrade = 0;

                                            if ($isMAPEH && isset($subject->components) && $subject->components->count() > 0) {
                                                $totalWeightedGrade = 0;
                                                $totalWeight = 0;

                                                foreach ($subject->components as $component) {
                                                    // Get component grades for this student and term
                                                    $componentGrades = \App\Models\Grade::where('student_id', $studentData['student']->id)
                                                        ->where('subject_id', $component->id)
                                                        ->where('term', $selectedTerm)
                                                        ->get();

                                                    if ($componentGrades->isNotEmpty()) {
                                                        $compWrittenWorks = $componentGrades->where('grade_type', 'written_work');
                                                        $compPerformanceTasks = $componentGrades->where('grade_type', 'performance_task');
                                                        $compQuarterlyAssessment = $componentGrades->where('grade_type', 'quarterly')->first();

                                                        // Calculate component grade using total score / total max score
                                                        $compWrittenWorksTotal = 0;
                                                        $compWrittenWorksMaxTotal = 0;
                                                        foreach($compWrittenWorks as $grade) {
                                                            $compWrittenWorksTotal += $grade->score;
                                                            $compWrittenWorksMaxTotal += $grade->max_score;
                                                        }
                                                        $compWrittenWorksAvg = $compWrittenWorksMaxTotal > 0 ?
                                                            ($compWrittenWorksTotal / $compWrittenWorksMaxTotal) * 100 : 0;

                                                        // Calculate component performance tasks using total score / total max score
                                                        $compPerformanceTasksTotal = 0;
                                                        $compPerformanceTasksMaxTotal = 0;
                                                        foreach($compPerformanceTasks as $grade) {
                                                            $compPerformanceTasksTotal += $grade->score;
                                                            $compPerformanceTasksMaxTotal += $grade->max_score;
                                                        }
                                                        $compPerformanceTasksAvg = $compPerformanceTasksMaxTotal > 0 ?
                                                            ($compPerformanceTasksTotal / $compPerformanceTasksMaxTotal) * 100 : 0;

                                                        // Calculate component quarterly assessment using total score / total max score
                                                        $compQuarterlyTotal = 0;
                                                        $compQuarterlyMaxTotal = 0;
                                                        if ($compQuarterlyAssessment) {
                                                            $compQuarterlyTotal += $compQuarterlyAssessment->score;
                                                            $compQuarterlyMaxTotal += $compQuarterlyAssessment->max_score;
                                                        }
                                                        $compQuarterlyScore = $compQuarterlyMaxTotal > 0 ?
                                                            ($compQuarterlyTotal / $compQuarterlyMaxTotal) * 100 : 0;

                                                        // Get component's grade configuration
                                                        $compConfig = \App\Models\GradeConfiguration::where('subject_id', $component->id)->first();

                                                        $cWrittenWorkPercentage = $compConfig ?
                                                            $compConfig->written_work_percentage : $writtenWorkPercentage;

                                                        $cPerformanceTaskPercentage = $compConfig ?
                                                            $compConfig->performance_task_percentage : $performanceTaskPercentage;

                                                        $cQuarterlyPercentage = $compConfig ?
                                                            $compConfig->quarterly_assessment_percentage : $quarterlyAssessmentPercentage;

                                                        // Calculate component final grade
                                                        $componentFinalGrade = 0;
                                                        $hasComponentGrades = false;

                                                        if ($compWrittenWorksAvg > 0) {
                                                            $componentFinalGrade += ($compWrittenWorksAvg * ($cWrittenWorkPercentage / 100));
                                                            $hasComponentGrades = true;
                                                        }

                                                        if ($compPerformanceTasksAvg > 0) {
                                                            $componentFinalGrade += ($compPerformanceTasksAvg * ($cPerformanceTaskPercentage / 100));
                                                            $hasComponentGrades = true;
                                                        }

                                                        if ($compQuarterlyScore > 0) {
                                                            $componentFinalGrade += ($compQuarterlyScore * ($cQuarterlyPercentage / 100));
                                                            $hasComponentGrades = true;
                                                        }

                                                        if ($hasComponentGrades) {
                                                            $componentFinalGrade = round($componentFinalGrade, 1);
                                                            $mapehComponentGrades[$component->name] = $componentFinalGrade;

                                                            // Add to weighted total
                                                            $totalWeightedGrade += ($componentFinalGrade * $component->component_weight);
                                                            $totalWeight += $component->component_weight;
                                                        }
                                                    }
                                                }

                                                // Calculate MAPEH final grade as weighted average of components
                                                if ($totalWeight > 0) {
                                                    $mapehFinalGrade = round($totalWeightedGrade / $totalWeight, 1);

                                                    // Update the calculated grade for MAPEH
                                                    if (isset($calculatedSubjectFinalGrades[$subjectId])) {
                                                        $calculatedSubjectFinalGrades[$subjectId] = $mapehFinalGrade;
                                                    }
                                                }
                                            }
                                        @endphp

                                        <div class="col-md-6 mb-4">
                                            <div class="card h-100 {{ isset($calculatedSubjectFinalGrades[$subjectId]) ? 'border-0 shadow-sm' : 'bg-light border-0' }}">
                                                <div class="card-header bg-white d-flex justify-content-between align-items-center py-2">
                                                    <h6 class="mb-0">{{ $studentData['subject_grades'][$subjectId]['subject_name'] }}</h6>

                                                    @if(isset($calculatedSubjectFinalGrades[$subjectId]))
                                                        @php
                                                            $finalGrade = $calculatedSubjectFinalGrades[$subjectId];
                                                            $transmutedGrade = getTransmutedGrade($finalGrade, $selectedTransmutationTable);

                                                            $gradeClass = 'success';
                                                            if ($transmutedGrade < 75) {
                                                                $gradeClass = 'danger';
                                                            } elseif ($transmutedGrade < 80) {
                                                                $gradeClass = 'warning';
                                                            } elseif ($transmutedGrade < 85) {
                                                                $gradeClass = 'info';
                                                            } elseif ($transmutedGrade < 90) {
                                                                $gradeClass = 'primary';
                                                            }
                                                        @endphp

                                                        <span class="badge bg-{{ $gradeClass }} rounded-pill">
                                                            {{ $transmutedGrade }}
                                                        </span>
                                                    @else
                                                        @if($isMAPEH && isset($subject->components) && $subject->components->count() > 0)
                                                            @php
                                                                // Check if any component has grades
                                                                $hasComponentGrades = false;
                                                                $mapehGrade = 0;
                                                                $totalWeightedGrade = 0;
                                                                $totalWeight = 0;

                                                                foreach ($subject->components as $component) {
                                                                    // Get component grades for this student and term
                                                                    $componentGrades = \App\Models\Grade::where('student_id', $studentData['student']->id)
                                                                        ->where('subject_id', $component->id)
                                                                        ->where('term', $selectedTerm)
                                                                        ->get();

                                                                    if ($componentGrades->isNotEmpty()) {
                                                                        $compWrittenWorks = $componentGrades->where('grade_type', 'written_work');
                                                                        $compPerformanceTasks = $componentGrades->where('grade_type', 'performance_task');
                                                                        $compQuarterlyAssessment = $componentGrades->where('grade_type', 'quarterly')->first();

                                                                        // Calculate component grade
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

                                                                        $cQuarterlyPercentage = $compConfig ?
                                                                            $compConfig->quarterly_assessment_percentage : $quarterlyAssessmentPercentage;

                                                                        // Calculate component final grade
                                                                        $componentFinalGrade = 0;
                                                                        $componentHasGrades = false;

                                                                        if ($compWrittenWorksAvg > 0) {
                                                                            $componentFinalGrade += ($compWrittenWorksAvg * ($cWrittenWorkPercentage / 100));
                                                                            $componentHasGrades = true;
                                                                        }

                                                                        if ($compPerformanceTasksAvg > 0) {
                                                                            $componentFinalGrade += ($compPerformanceTasksAvg * ($cPerformanceTaskPercentage / 100));
                                                                            $componentHasGrades = true;
                                                                        }

                                                                        if ($compQuarterlyScore > 0) {
                                                                            $componentFinalGrade += ($compQuarterlyScore * ($cQuarterlyPercentage / 100));
                                                                            $componentHasGrades = true;
                                                                        }

                                                                        if ($componentHasGrades) {
                                                                            $componentFinalGrade = round($componentFinalGrade, 1);
                                                                            // Add to weighted total
                                                                            $totalWeightedGrade += ($componentFinalGrade * $component->component_weight);
                                                                            $totalWeight += $component->component_weight;
                                                                            $hasComponentGrades = true;
                                                                        }
                                                                    }
                                                                }

                                                                // Calculate MAPEH final grade as weighted average of components
                                                                if ($totalWeight > 0) {
                                                                    $mapehGrade = round($totalWeightedGrade / $totalWeight, 1);
                                                                    $calculatedSubjectFinalGrades[$subjectId] = $mapehGrade;
                                                                }

                                                                if ($hasComponentGrades) {
                                                                    $transmutedGrade = getTransmutedGrade($mapehGrade, $selectedTransmutationTable);

                                                                    $gradeClass = 'success';
                                                                    if ($transmutedGrade < 75) {
                                                                        $gradeClass = 'danger';
                                                                    } elseif ($transmutedGrade < 80) {
                                                                        $gradeClass = 'warning';
                                                                    } elseif ($transmutedGrade < 85) {
                                                                        $gradeClass = 'info';
                                                                    } elseif ($transmutedGrade < 90) {
                                                                        $gradeClass = 'primary';
                                                                    }
                                                                }
                                                            @endphp

                                                            @if($hasComponentGrades)
                                                                <span class="badge bg-{{ $gradeClass }} rounded-pill">
                                                                    {{ $transmutedGrade }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary rounded-pill">
                                                                    Not Graded
                                                                </span>
                                                            @endif
                                                        @else
                                                            <span class="badge bg-secondary rounded-pill">
                                                                Not Graded
                                                            </span>
                                                        @endif
                                                    @endif
                                                </div>

                                                <div class="card-body p-3">
                                                    @if(isset($calculatedSubjectFinalGrades[$subjectId]))
                                                        @if($isMAPEH && count($mapehComponentGrades) > 0)
                                                            <!-- MAPEH specific display -->
                                                            <div class="mb-3">
                                                                <h6 class="text-muted small mb-2">MAPEH Components</h6>
                                                                <div class="table-responsive">
                                                                    <table class="table table-sm table-borderless mb-0">
                                                                        <tbody>
                                                                            @foreach($mapehComponentGrades as $componentName => $componentGrade)
                                                                                <tr>
                                                                                    <td>{{ $componentName }}</td>
                                                                                    <td class="text-end">{{ $componentGrade }}%</td>
                                                                                    <td class="text-end">{{ getTransmutedGrade($componentGrade, $selectedTransmutationTable) }}</td>
                                                                                </tr>
                                                                            @endforeach
                                                                            <tr class="border-top">
                                                                                <td class="fw-bold">MAPEH Average</td>
                                                                                <td class="text-end fw-bold">{{ $mapehFinalGrade }}%</td>
                                                                                <td class="text-end fw-bold">{{ $transmutedGrade }}</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <!-- Regular subject grades display -->
                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div class="text-muted small">Written Works ({{ $writtenWorkPercentage }}%)</div>
                                                                <div class="small fw-medium">{{ number_format($writtenPercentage, 1) }}%</div>
                                                            </div>
                                                            <div class="progress mb-3" style="height: 6px;">
                                                                <div class="progress-bar bg-primary" style="width: {{ min(100, $writtenPercentage) }}%"></div>
                                                            </div>

                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div class="text-muted small">Performance Tasks ({{ $performanceTaskPercentage }}%)</div>
                                                                <div class="small fw-medium">{{ number_format($performancePercentage, 1) }}%</div>
                                                            </div>
                                                            <div class="progress mb-3" style="height: 6px;">
                                                                <div class="progress-bar bg-success" style="width: {{ min(100, $performancePercentage) }}%"></div>
                                                            </div>

                                                            <div class="d-flex justify-content-between mb-2">
                                                                <div class="text-muted small">Quarterly Assessment ({{ $quarterlyAssessmentPercentage }}%)</div>
                                                                <div class="small fw-medium">{{ number_format($quarterlyPercentage, 1) }}%</div>
                                                            </div>
                                                            <div class="progress mb-3" style="height: 6px;">
                                                                <div class="progress-bar bg-warning" style="width: {{ min(100, $quarterlyPercentage) }}%"></div>
                                                            </div>

                                                            <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                                                <div class="fw-medium">Final Grade:</div>
                                                                <div class="fw-bold">{{ $finalGrade }}% → {{ $transmutedGrade }}</div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        @if($isMAPEH)
                                                            @php
                                                                // For MAPEH, try to fetch component grades even if there's no direct grade for MAPEH
                                                                $mapehComponentGrades = [];
                                                                $mapehFinalGrade = 0;
                                                                $hasComponentData = false;
                                                                $totalWeightedGrade = 0;
                                                                $totalWeight = 0;

                                                                // Initialize the grade for this MAPEH subject
                                                                if (!isset($calculatedSubjectFinalGrades[$subjectId])) {
                                                                    $calculatedSubjectFinalGrades[$subjectId] = 0;
                                                                }

                                                                if (isset($subject->components) && $subject->components->count() > 0) {
                                                                    foreach ($subject->components as $component) {
                                                                        // Get component grades for this student and term
                                                                        $componentGrades = \App\Models\Grade::where('student_id', $studentData['student']->id)
                                                                            ->where('subject_id', $component->id)
                                                                            ->where('term', $selectedTerm)
                                                                            ->get();

                                                                        if ($componentGrades->isNotEmpty()) {
                                                                            $compWrittenWorks = $componentGrades->where('grade_type', 'written_work');
                                                                            $compPerformanceTasks = $componentGrades->where('grade_type', 'performance_task');
                                                                            $compQuarterlyAssessment = $componentGrades->where('grade_type', 'quarterly')->first();

                                                                            // Calculate component grade
                                                                            // Calculate component written works using total score / total max score
                                                                            $compWrittenWorksTotal = 0;
                                                                            $compWrittenWorksMaxTotal = 0;
                                                                            foreach($compWrittenWorks as $grade) {
                                                                                $compWrittenWorksTotal += $grade->score;
                                                                                $compWrittenWorksMaxTotal += $grade->max_score;
                                                                            }
                                                                            $compWrittenWorksAvg = $compWrittenWorksMaxTotal > 0 ?
                                                                                ($compWrittenWorksTotal / $compWrittenWorksMaxTotal) * 100 : 0;

                                                                            // Calculate component performance tasks using total score / total max score
                                                                            $compPerformanceTasksTotal = 0;
                                                                            $compPerformanceTasksMaxTotal = 0;
                                                                            foreach($compPerformanceTasks as $grade) {
                                                                                $compPerformanceTasksTotal += $grade->score;
                                                                                $compPerformanceTasksMaxTotal += $grade->max_score;
                                                                            }
                                                                            $compPerformanceTasksAvg = $compPerformanceTasksMaxTotal > 0 ?
                                                                                ($compPerformanceTasksTotal / $compPerformanceTasksMaxTotal) * 100 : 0;

                                                                            // Calculate quarterly assessment using total score / total max score
                                                                            $compQuarterlyTotal = 0;
                                                                            $compQuarterlyMaxTotal = 0;
                                                                            if ($compQuarterlyAssessment) {
                                                                                $compQuarterlyTotal += $compQuarterlyAssessment->score;
                                                                                $compQuarterlyMaxTotal += $compQuarterlyAssessment->max_score;
                                                                            }
                                                                            $compQuarterlyScore = $compQuarterlyMaxTotal > 0 ?
                                                                                ($compQuarterlyTotal / $compQuarterlyMaxTotal) * 100 : 0;

                                                                            // Get component's grade configuration
                                                                            $compConfig = \App\Models\GradeConfiguration::where('subject_id', $component->id)->first();

                                                                            $cWrittenWorkPercentage = $compConfig ?
                                                                                $compConfig->written_work_percentage : $writtenWorkPercentage;

                                                                            $cPerformanceTaskPercentage = $compConfig ?
                                                                                $compConfig->performance_task_percentage : $performanceTaskPercentage;

                                                                            $cQuarterlyPercentage = $compConfig ?
                                                                                $compConfig->quarterly_assessment_percentage : $quarterlyAssessmentPercentage;

                                                                            // Calculate component final grade
                                                                            $componentFinalGrade = 0;
                                                                            $hasComponentGrades = false;

                                                                            if ($compWrittenWorksAvg > 0) {
                                                                                $componentFinalGrade += ($compWrittenWorksAvg * ($cWrittenWorkPercentage / 100));
                                                                                $hasComponentGrades = true;
                                                                            }

                                                                            if ($compPerformanceTasksAvg > 0) {
                                                                                $componentFinalGrade += ($compPerformanceTasksAvg * ($cPerformanceTaskPercentage / 100));
                                                                                $hasComponentGrades = true;
                                                                            }

                                                                            if ($compQuarterlyScore > 0) {
                                                                                $componentFinalGrade += ($compQuarterlyScore * ($cQuarterlyPercentage / 100));
                                                                                $hasComponentGrades = true;
                                                                            }

                                                                            if ($hasComponentGrades) {
                                                                                $componentFinalGrade = round($componentFinalGrade, 1);
                                                                                $mapehComponentGrades[$component->name] = $componentFinalGrade;

                                                                                // Add to weighted total
                                                                                $totalWeightedGrade += ($componentFinalGrade * $component->component_weight);
                                                                                $totalWeight += $component->component_weight;
                                                                                $hasComponentData = true;
                                                                            }
                                                                        }
                                                                    }

                                                                    // Calculate MAPEH final grade as weighted average of components
                                                                    if ($totalWeight > 0) {
                                                                        $mapehFinalGrade = round($totalWeightedGrade / $totalWeight, 1);
                                                                        // Update the main subject grade
                                                                        $calculatedSubjectFinalGrades[$subjectId] = $mapehFinalGrade;
                                                                    }
                                                                }

                                                                $finalGrade = $mapehFinalGrade;
                                                                $transmutedGrade = getTransmutedGrade($finalGrade, $selectedTransmutationTable);

                                                                $gradeClass = 'success';
                                                                if ($transmutedGrade < 75) {
                                                                    $gradeClass = 'danger';
                                                                } elseif ($transmutedGrade < 80) {
                                                                    $gradeClass = 'warning';
                                                                } elseif ($transmutedGrade < 85) {
                                                                    $gradeClass = 'info';
                                                                } elseif ($transmutedGrade < 90) {
                                                                    $gradeClass = 'primary';
                                                                }
                                                            @endphp

                                                            @if($hasComponentData)
                                                                <!-- MAPEH specific display with component data -->
                                                                <div class="mb-3">
                                                                    <h6 class="text-muted small mb-2">MAPEH Components</h6>
                                                                    <div class="table-responsive">
                                                                        <table class="table table-sm table-borderless mb-0">
                                                                            <tbody>
                                                                                @foreach($mapehComponentGrades as $componentName => $componentGrade)
                                                                                    <tr>
                                                                                        <td>{{ $componentName }}</td>
                                                                                        <td class="text-end">{{ $componentGrade }}%</td>
                                                                                        <td class="text-end">{{ getTransmutedGrade($componentGrade, $selectedTransmutationTable) }}</td>
                                                                                    </tr>
                                                                                @endforeach
                                                                                <tr class="border-top">
                                                                                    <td class="fw-bold">MAPEH Average</td>
                                                                                    <td class="text-end fw-bold">{{ $mapehFinalGrade }}%</td>
                                                                                    <td class="text-end fw-bold">{{ $transmutedGrade }}</td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>

                                                                    <div class="d-flex justify-content-between border-top pt-2 mt-2">
                                                                        <div class="fw-medium">Final Grade:</div>
                                                                        <div class="fw-bold">{{ $mapehFinalGrade }}% → {{ $transmutedGrade }}</div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <div class="text-center text-muted py-4">
                                                                    <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                                                    <p class="mb-0">No component grades have been entered for this MAPEH subject.</p>
                                                                </div>
                                                            @endif
                                                        @else
                                                            <div class="text-center text-muted py-4">
                                                                <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                                                                <p class="mb-0">No grades have been entered for this subject.</p>
                                                            </div>
                                                        @endif
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
                                                            <th>Calculation</th>
                                                            <th>Weight</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($calculatedSubjectFinalGrades as $subjectId => $grade)
                                                            @php
                                                                $writtenWorks = collect($studentData['subject_grades'][$subjectId]['written_works']);
                                                                $performanceTasks = collect($studentData['subject_grades'][$subjectId]['performance_tasks']);
                                                                $quarterlyAssessment = $studentData['subject_grades'][$subjectId]['quarterly_assessment'];

                                            // Get the actual weighted score from written works
                                            $writtenTotal = 0;
                                            $writtenMaxTotal = 0;

                                                                foreach($writtenWorks as $gradeItem) {
                                                                    $writtenTotal += $gradeItem->score;
                                                                    $writtenMaxTotal += $gradeItem->max_score;
                                            }

                                            $writtenPercentage = $writtenMaxTotal > 0 ?
                                                ($writtenTotal / $writtenMaxTotal) * 100 : 0;

                                            // Get the actual weighted score from performance tasks
                                            $performanceTotal = 0;
                                            $performanceMaxTotal = 0;

                                                                foreach($performanceTasks as $gradeItem) {
                                                                    $performanceTotal += $gradeItem->score;
                                                                    $performanceMaxTotal += $gradeItem->max_score;
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

                                            $weightedWritten = number_format($writtenPercentage * ($writtenWorkPercentage / 100), 1);
                                            $weightedPerformance = number_format($performancePercentage * ($performanceTaskPercentage / 100), 1);
                                            $weightedQuarterly = number_format($quarterlyPercentage * ($quarterlyAssessmentPercentage / 100), 1);
                                        @endphp
                                        <tr>
                                            <td>{{ $studentData['subject_grades'][$subjectId]['subject_name'] }}</td>
                                            <td>{{ $grade }}%</td>
                                            <td>
                                                <small>
                                                    ({{ number_format($writtenPercentage, 1) }}% × {{ $writtenWorkPercentage }}%) +
                                                    ({{ number_format($performancePercentage, 1) }}% × {{ $performanceTaskPercentage }}%) +
                                                    ({{ number_format($quarterlyPercentage, 1) }}% × {{ $quarterlyAssessmentPercentage }}%) =
                                                    {{ $weightedWritten }} + {{ $weightedPerformance }} + {{ $weightedQuarterly }}
                                                </small>
                                            </td>
                                            <td>{{ number_format(100/$validSubjectCount, 1) }}%</td>
                                        </tr>
                                    @endforeach
                                    <tr class="fw-bold border-top">
                                        <td colspan="3" class="text-end">Final Initial Grade:</td>
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
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
        {{-- <button type="button" class="btn btn-primary print-detail" data-student-id="{{ $studentData['student']->id }}">
            <i class="fas fa-print me-1"></i> Print Report
        </button> --}}
    </div>
</div>
</div>
</div>
@endforeach
@endif

<!-- Comprehensive View Modals -->
@if(isset($students) && count($students) > 0)
@foreach($students as $index => $studentData)
@php
$student = $studentData['student'];
@endphp
<!-- Student Details Modal for Comprehensive View -->
<div class="modal fade" id="studentDetailsModal{{ $student->id }}" tabindex="-1" aria-labelledby="comprehensiveDetailsModal{{ $student->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="comprehensiveDetailsModal{{ $student->id }}">
                    <i class="fas fa-user-graduate me-2"></i> {{ $student->first_name }} {{ $student->last_name }}'s {{ $selectedSubject->name }} Performance Report
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <!-- Student Info Card -->
                <div class="bg-light p-4 border-bottom">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <div class="avatar-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; font-size: 2rem;">
                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                            </div>
                        </div>
                        <div class="col">
                            <h4 class="mb-1 fw-bold">{{ $student->first_name }} {{ $student->last_name }}</h4>
                            <div class="d-flex flex-wrap gap-3 text-secondary">
                                <div><i class="fas fa-id-card me-1"></i> ID: <span class="fw-medium">{{ $student->student_id }}</span></div>
                                <div><i class="fas fa-users me-1"></i> Section: <span class="fw-medium">{{ $student->section->name ?? 'Unassigned' }}</span></div>
                                <div><i class="fas fa-graduation-cap me-1"></i> Grade Level: <span class="fw-medium">{{ $student->section->grade_level ?? 'Unassigned' }}</span></div>
                                <div><i class="fas fa-calendar-alt me-1"></i> Term: <span class="fw-medium">{{ $terms[$selectedTerm] }}</span></div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            @php
                                // Calculate subject grade
                                $isViewAll = isset($studentData['view_all']) && $studentData['view_all'];

                                if (!$isViewAll) {
                                    $writtenWorks = $studentData['written_works'] ?? [];
                                    $performanceTasks = $studentData['performance_tasks'] ?? [];
                                    $quarterlyAssessment = $studentData['quarterly_assessment'] ?? null;

                                    // Calculate grades
                                    $writtenWorksAvg = calculateAverage($writtenWorks);
                                    $performanceTasksAvg = calculateAverage($performanceTasks);
                                    // Calculate quarterly assessment using total score / total max score
                                    $quarterlyTotal = 0;
                                    $quarterlyMaxTotal = 0;
                                    if ($quarterlyAssessment) {
                                        $quarterlyTotal += $quarterlyAssessment->score;
                                        $quarterlyMaxTotal += $quarterlyAssessment->max_score;
                                    }
                                    $quarterlyScore = $quarterlyMaxTotal > 0 ? ($quarterlyTotal / $quarterlyMaxTotal) * 100 : 0;

                                    // Calculate final grade
                                    $finalGrade = (($writtenWorksAvg * $writtenWorkPercentage) +
                                                   ($performanceTasksAvg * $performanceTaskPercentage) +
                                                   ($quarterlyScore * $quarterlyAssessmentPercentage)) / 100;
                                }

                                // Get the transmuted grade using the selected table
                                $selectedTableId = request('transmutation_table', session('locked_transmutation_table_id', $preferredTableId ?? 1));
                                $transmutedGrade = getTransmutedGrade($finalGrade, $selectedTableId);

                                $subjectGradeInfo = [
                                    'written_works_avg' => $writtenWorksAvg,
                                    'performance_tasks_avg' => $performanceTasksAvg,
                                    'quarterly_score' => $quarterlyScore,
                                    'final_grade' => $finalGrade,
                                    'transmuted_grade' => $transmutedGrade
                                ];

                                // Determine grade status color
                                $gradeClass = 'danger';
                            if ($transmutedGrade >= 90) {
                                $gradeClass = 'success';
                                    $descriptor = 'Outstanding';
                                } elseif ($transmutedGrade >= 85) {
                                    $gradeClass = 'primary';
                                    $descriptor = 'Very Satisfactory';
                            } elseif ($transmutedGrade >= 80) {
                                    $gradeClass = 'info';
                                    $descriptor = 'Satisfactory';
                                } elseif ($transmutedGrade >= 75) {
                                    $gradeClass = 'warning';
                                    $descriptor = 'Fairly Satisfactory';
                                } else {
                                    $gradeClass = 'danger';
                                    $descriptor = 'Did Not Meet Expectations';
                                }
                            @endphp
                            <div class="card bg-white shadow-sm border-0 h-100">
                                <div class="card-body p-3 text-center">
                                    <h6 class="text-uppercase text-secondary mb-2 small">{{ $selectedSubject->name }} Grade</h6>
                                    <div class="display-4 fw-bold text-{{ $gradeClass }}">{{ $transmutedGrade }}</div>
                                    <div class="mt-2 small text-{{ $gradeClass }}">
                                        {{ $descriptor }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                                <div class="p-4">
                                    <!-- Subject Performance Details -->
                                    <div class="card shadow-sm border-0 mb-4">
                                        <div class="card-header bg-white">
                                            <h6 class="card-title mb-0"><i class="fas fa-chart-bar me-2"></i>{{ $selectedSubject->name }} Performance Breakdown</h6>
                                        </div>
                                        <div class="card-body">
                                            @php
                                                // Check if this is a MAPEH subject
                                                    $isMAPEH = false;
                                                $mapehComponents = [];

                                                if ($selectedSubject && isset($selectedSubject->components) && $selectedSubject->components->count() > 0) {
                                                    // Check for MAPEH by name/code pattern or component structure
                                                    $isMAPEH = (stripos($selectedSubject->name, 'MAPEH') !== false) ||
                                                              (isset($selectedSubject->code) && stripos($selectedSubject->code, 'MAPEH') !== false) ||
                                                              $selectedSubject->getIsMAPEHAttribute();

                                                    // If this is a MAPEH subject, use its components
                                                    if ($isMAPEH) {
                                                        $mapehComponents = $selectedSubject->components;
                                                    }

                                                    // Check if this is a component of a MAPEH subject
                                                    elseif (isset($selectedSubject->parent_subject_id) && $selectedSubject->parent_subject_id) {
                                                        // Get the parent subject
                                                        $parentSubject = \App\Models\Subject::find($selectedSubject->parent_subject_id);

                                                        // Check if parent is a MAPEH subject
                                                        if ($parentSubject &&
                                                            (stripos($parentSubject->name, 'MAPEH') !== false ||
                                                             (isset($parentSubject->code) && stripos($parentSubject->code, 'MAPEH') !== false) ||
                                                             $parentSubject->getIsMAPEHAttribute())) {

                                                            $mapehComponents = $parentSubject->components;
                                                            $isMAPEH = true;
                                                        }
                                                    }
                                                }
                                            @endphp

                                            @if($isMAPEH && count($mapehComponents) > 0)
                                                <!-- MAPEH Tabs -->
                                                <ul class="nav nav-tabs mb-4" id="mapehComponentTabs" role="tablist">
                                                    <li class="nav-item" role="presentation">
                                                        <button class="nav-link active" id="mapeh-overall-tab" data-bs-toggle="tab"
                                                                data-bs-target="#mapeh-overall" type="button" role="tab"
                                                                aria-controls="mapeh-overall" aria-selected="true">
                                                            MAPEH Overall
                                                        </button>
                                                    </li>
                                                    @foreach($mapehComponents as $component)
                                                        @php
                                                            $componentId = str_replace(' ', '-', strtolower($component->name));
                                                            // Special handling for Physical Education
                                                            if (stripos($component->name, 'Physical Education') !== false) {
                                                                $componentId = 'physical-education';
                                                            } elseif (stripos($component->name, 'P.E') !== false || stripos($component->name, 'PE') !== false) {
                                                                $componentId = 'physical-education';
                                                            }
                                                        @endphp
                                                        <li class="nav-item" role="presentation">
                                                            <button class="nav-link" id="mapeh-{{ $componentId }}-tab" data-bs-toggle="tab"
                                                                    data-bs-target="#mapeh-{{ $componentId }}" type="button" role="tab"
                                                                    aria-controls="mapeh-{{ $componentId }}" aria-selected="false">
                                                                {{ $component->name }}
                                                            </button>
                                                        </li>
                                                    @endforeach
                                                </ul>

                                                <div class="tab-content" id="mapehComponentTabsContent">
                                                    <!-- Overall MAPEH Tab -->
                                                    <div class="tab-pane fade show active" id="mapeh-overall" role="tabpanel" aria-labelledby="mapeh-overall-tab">
                                                        <div class="row g-4">
                                                            <div class="col-md-4">
                                                                <div class="p-3 rounded bg-primary bg-opacity-10">
                                                                    <h6 class="text-primary">Written Works ({{ $writtenWorkPercentage }}%)</h6>
                                                                    <div class="display-6 fw-bold text-primary mb-2">{{ number_format($writtenWorksAvg, 1) }}%</div>
                                                                    <div class="progress mb-2" style="height: 8px;">
                                                                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $writtenWorksAvg) }}%" aria-valuenow="{{ $writtenWorksAvg }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                    <div class="small text-muted">
                                                                        Contribution: {{ number_format($writtenWorksAvg * ($writtenWorkPercentage / 100), 1) }} points
                                                                    </div>
                                                                    <div class="mt-3">
                                                                        <p class="mb-1 small fw-medium text-dark">Assessment Breakdown:</p>
                                                                        @if(count($writtenWorks) > 0)
                                                                            <ul class="list-unstyled mb-0 small">
                                                                            @foreach($writtenWorks as $work)
                                                                                <li class="d-flex justify-content-between align-items-center mb-1">
                                                                                    <span>{{ $work->assessment_name }}</span>
                                                                                    <span>{{ $work->score }}/{{ $work->max_score }}</span>
                                                                                </li>
                                                                            @endforeach
                                                                            </ul>
                                                                        @else
                                                                            <div class="text-muted small">No written works recorded yet.</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="p-3 rounded bg-success bg-opacity-10">
                                                                    <h6 class="text-success">Performance Tasks ({{ $performanceTaskPercentage }}%)</h6>
                                                                    <div class="display-6 fw-bold text-success mb-2">{{ number_format($performanceTasksAvg, 1) }}%</div>
                                                                    <div class="progress mb-2" style="height: 8px;">
                                                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $performanceTasksAvg) }}%" aria-valuenow="{{ $performanceTasksAvg }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                    <div class="small text-muted">
                                                                        Contribution: {{ number_format($performanceTasksAvg * ($performanceTaskPercentage / 100), 1) }} points
                                                                    </div>
                                                                    <div class="mt-3">
                                                                        <p class="mb-1 small fw-medium text-dark">Tasks Breakdown:</p>
                                                                        @if(count($performanceTasks) > 0)
                                                                            <ul class="list-unstyled mb-0 small">
                                                                            @foreach($performanceTasks as $task)
                                                                                <li class="d-flex justify-content-between align-items-center mb-1">
                                                                                    <span>{{ $task->assessment_name }}</span>
                                                                                    <span>{{ $task->score }}/{{ $task->max_score }}</span>
                                                                                </li>
                                                                            @endforeach
                                                                            </ul>
                                                                        @else
                                                                            <div class="text-muted small">No performance tasks recorded yet.</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="p-3 rounded bg-warning bg-opacity-10">
                                                                    <h6 class="text-warning">Quarterly Exam ({{ $quarterlyAssessmentPercentage }}%)</h6>
                                                                    <div class="display-6 fw-bold text-warning mb-2">{{ number_format($quarterlyScore, 1) }}%</div>
                                                                    <div class="progress mb-2" style="height: 8px;">
                                                                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min(100, $quarterlyScore) }}%" aria-valuenow="{{ $quarterlyScore }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                    </div>
                                                                    <div class="small text-muted">
                                                                        Contribution: {{ number_format($quarterlyScore * ($quarterlyAssessmentPercentage / 100), 1) }} points
                                                                    </div>
                                                                    <div class="mt-3">
                                                                        <p class="mb-1 small fw-medium text-dark">Exam Details:</p>
                                                                        @if($quarterlyAssessment)
                                                                            <div class="d-flex justify-content-between align-items-center small">
                                                                                <span>{{ $quarterlyAssessment->assessment_name }}</span>
                                                                                <span>{{ $quarterlyAssessment->score }}/{{ $quarterlyAssessment->max_score }}</span>
                                                                            </div>
                                                                        @else
                                                                            <div class="text-muted small">No quarterly exam recorded yet.</div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Individual Component Tabs -->
                                                    @foreach($mapehComponents as $component)
                                                        @php
                                                            $componentId = str_replace(' ', '-', strtolower($component->name));
                                                            // Special handling for Physical Education
                                                            if (stripos($component->name, 'Physical Education') !== false) {
                                                                $componentId = 'physical-education';
                                                            } elseif (stripos($component->name, 'P.E') !== false || stripos($component->name, 'PE') !== false) {
                                                                $componentId = 'physical-education';
                                                            }
                                                        @endphp
                                                        <div class="tab-pane fade" id="mapeh-{{ $componentId }}" role="tabpanel"
                                                             aria-labelledby="mapeh-{{ $componentId }}-tab">

                                                            @php
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

                                                                // Calculate component grade using weighted formula
                                                            // Calculate component written works using total score / total max score
                                                            $componentWrittenWorksTotal = 0;
                                                            $componentWrittenWorksMaxTotal = 0;
                                                            foreach($componentWrittenWorks as $grade) {
                                                                $componentWrittenWorksTotal += $grade->score;
                                                                $componentWrittenWorksMaxTotal += $grade->max_score;
                                                            }
                                                            $componentWrittenWorksAvg = $componentWrittenWorksMaxTotal > 0 ?
                                                                ($componentWrittenWorksTotal / $componentWrittenWorksMaxTotal) * 100 : 0;

                                                            // Calculate component performance tasks using total score / total max score
                                                            $componentPerformanceTasksTotal = 0;
                                                            $componentPerformanceTasksMaxTotal = 0;
                                                            foreach($componentPerformanceTasks as $grade) {
                                                                $componentPerformanceTasksTotal += $grade->score;
                                                                $componentPerformanceTasksMaxTotal += $grade->max_score;
                                                            }
                                                            $componentPerformanceTasksAvg = $componentPerformanceTasksMaxTotal > 0 ?
                                                                ($componentPerformanceTasksTotal / $componentPerformanceTasksMaxTotal) * 100 : 0;

                                                            // Calculate component quarterly assessment using total score / total max score
                                                            $componentQuarterlyTotal = 0;
                                                            $componentQuarterlyMaxTotal = 0;
                                                            if ($componentQuarterlyAssessment) {
                                                                $componentQuarterlyTotal += $componentQuarterlyAssessment->score;
                                                                $componentQuarterlyMaxTotal += $componentQuarterlyAssessment->max_score;
                                                            }
                                                            $componentQuarterlyScore = $componentQuarterlyMaxTotal > 0 ?
                                                                ($componentQuarterlyTotal / $componentQuarterlyMaxTotal) * 100 : 0;

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

                                                            if ($componentWrittenWorks->count() > 0) {
                                                                $componentFinalGrade += ($componentWrittenWorksAvg * ($compWrittenWorkPercentage / 100));
                                                            }

                                                            if ($componentPerformanceTasks->count() > 0) {
                                                                $componentFinalGrade += ($componentPerformanceTasksAvg * ($compPerformanceTaskPercentage / 100));
                                                            }

                                                            if ($componentQuarterlyAssessment) {
                                                                $componentFinalGrade += ($componentQuarterlyScore * ($compQuarterlyAssessmentPercentage / 100));
                                                                }

                                                                // Get transmuted grade for this component
                                                                $componentTransmutedGrade = getTransmutedGrade(
                                                                    $componentFinalGrade,
                                                                    request('transmutation_table', 1)
                                                                );
                                                            @endphp

                                                            <div class="row g-4">
                                                                <div class="col-md-4">
                                                                    <div class="p-3 rounded bg-primary bg-opacity-10">
                                                                        <h6 class="text-primary">Written Works ({{ $compWrittenWorkPercentage }}%)</h6>
                                                                        <div class="display-6 fw-bold text-primary mb-2">{{ number_format($componentWrittenWorksAvg, 1) }}%</div>
                                                                        <div class="progress mb-2" style="height: 8px;">
                                                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $componentWrittenWorksAvg) }}%;" aria-valuenow="{{ $componentWrittenWorksAvg }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                        </div>
                                                                        <div class="small text-muted">
                                                                            Contribution: {{ number_format($componentWrittenWorksAvg * ($compWrittenWorkPercentage / 100), 1) }} points
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <p class="mb-1 small fw-medium text-dark">Assessment Breakdown:</p>
                                                                            @if($componentWrittenWorks->count() > 0)
                                                                                <ul class="list-unstyled mb-0 small">
                                                                                @foreach($componentWrittenWorks as $work)
                                                                                    <li class="d-flex justify-content-between align-items-center mb-1">
                                                                                        <span>{{ $work->assessment_name }}</span>
                                                                                        <span>{{ $work->score }}/{{ $work->max_score }}</span>
                                                                                    </li>
                                                                                @endforeach
                                                                                </ul>
                                                                            @else
                                                                                <div class="text-muted small">No written works recorded yet.</div>
                                                                            @endif
                                                                        </div>

                                                                        <!-- Debugging Section (Hidden) -->
                                                                        @if(false)
                                                                        <div class="mt-3">
                                                                            <p class="mb-1 small fw-medium text-primary cursor-pointer"
                                                                               data-bs-toggle="collapse"
                                                                               data-bs-target="#writtenWorksDebug"
                                                                               aria-expanded="false"
                                                                               aria-controls="writtenWorksDebug">
                                                                                <i class="bi bi-info-circle me-1"></i> Debug Calculation <i class="bi bi-chevron-down ms-1 small"></i>
                                                                            </p>
                                                                            <div class="collapse" id="writtenWorksDebug">
                                                                                <div class="card card-body p-2 mt-2 bg-light">
                                                                                    <h6 class="small fw-bold mb-2">Raw Data:</h6>
                                                                                    <ul class="list-unstyled mb-2 small">
                                                                                        @php
                                                                                            $totalScore = 0;
                                                                                            $totalMaxScore = 0;
                                                                                        @endphp
                                                                                        @foreach($componentWrittenWorks as $work)
                                                                                            @php
                                                                                                $totalScore += $work->score;
                                                                                                $totalMaxScore += $work->max_score;
                                                                                                $individualPercentage = $work->max_score > 0 ? ($work->score / $work->max_score) * 100 : 0;
                                                                                            @endphp
                                                                                            <li class="mb-1">
                                                                                                <strong>{{ $work->assessment_name }}</strong>:
                                                                                                Score: {{ $work->score }},
                                                                                                Max: {{ $work->max_score }},
                                                                                                Individual %: {{ number_format($individualPercentage, 1) }}%
                                                                                            </li>
                                                                                        @endforeach
                                                                                    </ul>
                                                                                    <h6 class="small fw-bold mb-2">Calculation:</h6>
                                                                                    <ul class="list-unstyled mb-0 small">
                                                                                        <li>Total Score: {{ $totalScore }}</li>
                                                                                        <li>Total Max Score: {{ $totalMaxScore }}</li>
                                                                                        <li>Calculation: ({{ $totalScore }} / {{ $totalMaxScore }}) × 100 = {{ number_format(($totalScore / $totalMaxScore) * 100, 1) }}%</li>
                                                                                        <li class="mt-2 fw-bold">Result: {{ number_format($componentWrittenWorksAvg, 1) }}%</li>
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="p-3 rounded bg-success bg-opacity-10">
                                                                        <h6 class="text-success">Performance Tasks ({{ $compPerformanceTaskPercentage }}%)</h6>
                                                                        <div class="display-6 fw-bold text-success mb-2">{{ number_format($componentPerformanceTasksAvg, 1) }}%</div>
                                                                        <div class="progress mb-2" style="height: 8px;">
                                                                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $componentPerformanceTasksAvg) }}%;" aria-valuenow="{{ $componentPerformanceTasksAvg }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                        </div>
                                                                        <div class="small text-muted">
                                                                            Contribution: {{ number_format($componentPerformanceTasksAvg * ($compPerformanceTaskPercentage / 100), 1) }} points
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <p class="mb-1 small fw-medium text-dark">Tasks Breakdown:</p>
                                                                            @if($componentPerformanceTasks->count() > 0)
                                                                                <ul class="list-unstyled mb-0 small">
                                                                                @foreach($componentPerformanceTasks as $task)
                                                                                    <li class="d-flex justify-content-between align-items-center mb-1">
                                                                                        <span>{{ $task->assessment_name }}</span>
                                                                                        <span>{{ $task->score }}/{{ $task->max_score }}</span>
                                                                                    </li>
                                                                                @endforeach
                                                                                </ul>
                                                                            @else
                                                                                <div class="text-muted small">No performance tasks recorded yet.</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-4">
                                                                    <div class="p-3 rounded bg-warning bg-opacity-10">
                                                                        <h6 class="text-warning">Quarterly Exam ({{ $compQuarterlyAssessmentPercentage }}%)</h6>
                                                                        <div class="display-6 fw-bold text-warning mb-2">{{ number_format($componentQuarterlyScore, 1) }}%</div>
                                                                        <div class="progress mb-2" style="height: 8px;">
                                                                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min(100, $componentQuarterlyScore) }}%;" aria-valuenow="{{ $componentQuarterlyScore }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                        </div>
                                                                        <div class="small text-muted">
                                                                            Contribution: {{ number_format($componentQuarterlyScore * ($compQuarterlyAssessmentPercentage / 100), 1) }} points
                                                                        </div>
                                                                        <div class="mt-3">
                                                                            <p class="mb-1 small fw-medium text-dark">Exam Details:</p>
                                                                            @if($componentQuarterlyAssessment)
                                                                                <div class="d-flex justify-content-between align-items-center small">
                                                                                    <span>{{ $componentQuarterlyAssessment->assessment_name }}</span>
                                                                                    <span>{{ $componentQuarterlyAssessment->score }}/{{ $componentQuarterlyAssessment->max_score }}</span>
                                                                                </div>
                                                                            @else
                                                                                <div class="text-muted small">No quarterly exam recorded yet.</div>
                                                                            @endif
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Component Grade Summary -->
                                                            <div class="mt-4 p-3 rounded bg-light">
                                                                <div class="row align-items-center">
                                                                    <div class="col-md-8">
                                                                        <h6>{{ $component->name }} Overall Grade</h6>
                                                                        <div class="d-flex align-items-center mt-2">
                                                                            <div class="progress flex-grow-1 me-3" style="height: 8px;">
                                                                                <div class="progress-bar bg-info" role="progressbar"
                                                                                     style="width: {{ min(100, $componentFinalGrade) }}%;"
                                                                                     aria-valuenow="{{ $componentFinalGrade }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                                            </div>
                                                                            <div class="fw-bold">{{ number_format($componentFinalGrade, 1) }}%</div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4 text-center">
                                                                        <div class="display-6 fw-bold text-info">{{ $componentTransmutedGrade }}</div>
                                                                        <div class="small text-muted">Transmuted Grade</div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <!-- Regular Subject Display (Non-MAPEH) -->
                                                <div class="row g-4">
                                                    <div class="col-md-4">
                                                        <div class="p-3 rounded bg-primary bg-opacity-10">
                                                            <h6 class="text-primary">Written Works ({{ $writtenWorkPercentage }}%)</h6>
                                                            <div class="display-6 fw-bold text-primary mb-2">{{ number_format($writtenWorksAvg, 1) }}%</div>
                                                            <div class="progress mb-2" style="height: 8px;">
                                                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, $writtenWorksAvg) }}%" aria-valuenow="{{ $writtenWorksAvg }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="small text-muted">
                                                                Contribution: {{ number_format($writtenWorksAvg * ($writtenWorkPercentage / 100), 1) }} points
                                                            </div>
                                                            <div class="mt-3">
                                                                <p class="mb-1 small fw-medium text-dark">Assessment Breakdown:</p>
                                                                @if(count($writtenWorks) > 0)
                                                                    <ul class="list-unstyled mb-0 small">
                                                                    @foreach($writtenWorks as $work)
                                                                        <li class="d-flex justify-content-between align-items-center mb-1">
                                                                            <span>{{ $work->assessment_name }}</span>
                                                                            <span>{{ $work->score }}/{{ $work->max_score }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                    </ul>
                                                                @else
                                                                    <div class="text-muted small">No written works recorded yet.</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="p-3 rounded bg-success bg-opacity-10">
                                                            <h6 class="text-success">Performance Tasks ({{ $performanceTaskPercentage }}%)</h6>
                                                            <div class="display-6 fw-bold text-success mb-2">{{ number_format($performanceTasksAvg, 1) }}%</div>
                                                            <div class="progress mb-2" style="height: 8px;">
                                                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, $performanceTasksAvg) }}%" aria-valuenow="{{ $performanceTasksAvg }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="small text-muted">
                                                                Contribution: {{ number_format($performanceTasksAvg * ($performanceTaskPercentage / 100), 1) }} points
                                                            </div>
                                                            <div class="mt-3">
                                                                <p class="mb-1 small fw-medium text-dark">Tasks Breakdown:</p>
                                                                @if(count($performanceTasks) > 0)
                                                                    <ul class="list-unstyled mb-0 small">
                                                                    @foreach($performanceTasks as $task)
                                                                        <li class="d-flex justify-content-between align-items-center mb-1">
                                                                            <span>{{ $task->assessment_name }}</span>
                                                                            <span>{{ $task->score }}/{{ $task->max_score }}</span>
                                                                        </li>
                                                                    @endforeach
                                                                    </ul>
                                                                @else
                                                                    <div class="text-muted small">No performance tasks recorded yet.</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="p-3 rounded bg-warning bg-opacity-10">
                                                            <h6 class="text-warning">Quarterly Exam ({{ $quarterlyAssessmentPercentage }}%)</h6>
                                                            <div class="display-6 fw-bold text-warning mb-2">{{ number_format($quarterlyScore, 1) }}%</div>
                                                            <div class="progress mb-2" style="height: 8px;">
                                                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ min(100, $quarterlyScore) }}%" aria-valuenow="{{ $quarterlyScore }}" aria-valuemin="0" aria-valuemax="100"></div>
                                                            </div>
                                                            <div class="small text-muted">
                                                                Contribution: {{ number_format($quarterlyScore * ($quarterlyAssessmentPercentage / 100), 1) }} points
                                                            </div>
                                                            <div class="mt-3">
                                                                <p class="mb-1 small fw-medium text-dark">Exam Details:</p>
                                                                @if($quarterlyAssessment)
                                                                    <div class="d-flex justify-content-between align-items-center small">
                                                                        <span>{{ $quarterlyAssessment->assessment_name }}</span>
                                                                        <span>{{ $quarterlyAssessment->score }}/{{ $quarterlyAssessment->max_score }}</span>
                                                                    </div>
                                                                @else
                                                                    <div class="text-muted small">No quarterly exam recorded yet.</div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Final Grade Calculation -->
                                    <div class="card shadow-sm border-0 mb-4">
                                        <div class="card-header bg-white">
                                            <h6 class="card-title mb-0"><i class="fas fa-calculator me-2"></i>Grade Calculation</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row align-items-center">
                                                <div class="col-md-8">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead class="table-light">
                                                                <tr>
                                                                    <th>Component</th>
                                                                    <th class="text-center">Raw Average</th>
                                                                    <th class="text-center">Weight</th>
                                                                    <th class="text-center">Weighted Score</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr>
                                                                    <td>Written Works</td>
                                                                    <td class="text-center">{{ number_format($writtenWorksAvg, 1) }}%</td>
                                                                    <td class="text-center">{{ $writtenWorkPercentage }}%</td>
                                                                    <td class="text-center">{{ number_format($writtenWorksAvg * ($writtenWorkPercentage / 100), 1) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Performance Tasks</td>
                                                                    <td class="text-center">{{ number_format($performanceTasksAvg, 1) }}%</td>
                                                                    <td class="text-center">{{ $performanceTaskPercentage }}%</td>
                                                                    <td class="text-center">{{ number_format($performanceTasksAvg * ($performanceTaskPercentage / 100), 1) }}</td>
                                                                </tr>
                                                                <tr>
                                                                    <td>Quarterly Exam</td>
                                                                    <td class="text-center">{{ number_format($quarterlyScore, 1) }}%</td>
                                                                    <td class="text-center">{{ $quarterlyAssessmentPercentage }}%</td>
                                                                    <td class="text-center">{{ number_format($quarterlyScore * ($quarterlyAssessmentPercentage / 100), 1) }}</td>
                                                                </tr>
                                                                <tr class="table-active fw-bold">
                                                                    <td colspan="3" class="text-end">Initial Grade (Total)</td>
                                                                    <td class="text-center">{{ number_format($finalGrade, 1) }}%</td>
                                                                </tr>
                                                                <tr class="table-active fw-bold">
                                                                    <td colspan="3" class="text-end">Transmuted Grade</td>
                                                                    <td class="text-center">{{ $transmutedGrade }}</td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="card h-100 bg-{{ $gradeClass }} bg-opacity-10 border-0">
                                                        <div class="card-body text-center p-4">
                                                            <h6 class="text-{{ $gradeClass }} fw-bold mb-3">FINAL GRADE</h6>
                                                            <div class="display-2 fw-bold text-{{ $gradeClass }} mb-2">{{ $transmutedGrade }}</div>
                                                            <div class="badge bg-{{ $gradeClass }} px-3 py-2 mb-3">{{ $descriptor }}</div>
                                                            <div class="text-muted small">Initial Grade: {{ number_format($finalGrade, 1) }}%</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Transmutation Information -->
                                    <div class="alert alert-info">
                                        <div class="d-flex">
                                            <div class="me-3">
                                                <i class="fas fa-info-circle fa-2x"></i>
                                            </div>
                                            <div>
                                                <h6 class="alert-heading mb-1">Grading Information</h6>
                                                <p class="mb-0">Grades are transmuted using {{ request('transmutation_table', 1) == 1 ? 'DepEd Order 8 s. 2015' : 'Custom Transmutation' }}. The passing grade is 75.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                {{-- <button type="button" class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i> Print Report
                                </button> --}}
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // No toggle needed anymore since we're focusing on a single subject

                        // Add specific handling for MAPEH tabs when they exist
                        if (document.getElementById('mapehComponentTabs')) {
                            // Make sure all tab buttons work correctly
                            const tabLinks = document.querySelectorAll('#mapehComponentTabs button');

                            // Track original overall grade values
                            const originalFinalGrade = document.querySelector('.final-grade-value')?.textContent || '';
                            const originalInitialGrade = document.querySelector('.initial-grade-value')?.textContent || '';
                            const originalGradeDescriptor = document.querySelector('.grade-descriptor')?.textContent || '';

                            // Component-specific grade data
                            const componentGrades = {
                                'overall': {
                                    initial: originalInitialGrade,
                                    final: originalFinalGrade,
                                    descriptor: originalGradeDescriptor
                                }
                            };

                            tabLinks.forEach(function(tabLink) {
                                tabLink.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    const targetId = this.getAttribute('data-bs-target');
                                    const componentName = this.textContent.trim();
                                    const modalContainer = document.querySelector('.modal-content');

                                    // Physical Education needs special handling for ID
                                    let fixedTargetId = targetId;
                                    if (componentName.includes('Physical Education')) {
                                        // The ID could be any of these variations
                                        const possibleIds = [
                                            '#mapeh-physical-education',
                                            '#mapeh-physical\\ education',
                                            '#mapeh-pe'
                                        ];

                                        // Find which ID actually exists in the DOM
                                        for (const id of possibleIds) {
                                            if (document.querySelector(id)) {
                                                fixedTargetId = id;
                                                break;
                                            }
                                        }
                                    }

                                    // Hide all tabs
                                    document.querySelectorAll('#mapehComponentTabsContent .tab-pane').forEach(function(tab) {
                                        tab.classList.remove('show', 'active');
                                    });

                                    // Show the target tab
                                    const targetTab = document.querySelector(fixedTargetId);
                                    if (targetTab) {
                                        targetTab.classList.add('show', 'active');
                                    } else {
                                        console.error('Tab not found:', fixedTargetId);
                                    }

                                    // Mark this tab button as active
                                    tabLinks.forEach(function(link) {
                                        link.classList.remove('active');
                                    });
                                    this.classList.add('active');

                                    // Reset component theme classes
                                    if (modalContainer) {
                                        modalContainer.classList.remove('music-active', 'arts-active', 'pe-active', 'health-active');

                                        // Add appropriate theme class
                                        if (componentName.toLowerCase().includes('music')) {
                                            modalContainer.classList.add('music-active');
                                        } else if (componentName.toLowerCase().includes('arts')) {
                                            modalContainer.classList.add('arts-active');
                                        } else if (componentName.toLowerCase().includes('physical') || componentName.toLowerCase().includes('pe')) {
                                            modalContainer.classList.add('pe-active');
                                        } else if (componentName.toLowerCase().includes('health')) {
                                            modalContainer.classList.add('health-active');
                                        }
                                    }

                                    // Update grade display based on selected component
                                    const finalGradeDisplay = document.querySelector('.final-grade-display');
                                    const mapehGradeDisplay = document.querySelector('.mapeh-grade-display');

                                    if (componentName === 'MAPEH Overall') {
                                        // Show overall MAPEH grade
                                        if (finalGradeDisplay) {
                                            finalGradeDisplay.querySelector('.final-grade-value').textContent = componentGrades.overall.final;
                                            finalGradeDisplay.querySelector('.initial-grade-value').textContent = componentGrades.overall.initial;
                                            finalGradeDisplay.querySelector('.grade-descriptor').textContent = componentGrades.overall.descriptor;
                                        }

                                        if (mapehGradeDisplay) {
                                            mapehGradeDisplay.querySelector('.mapeh-grade-value').textContent = componentGrades.overall.final;
                                            mapehGradeDisplay.querySelector('.mapeh-grade-descriptor').textContent = componentGrades.overall.descriptor;
                                        }
                                    } else {
                                        // Get component grade from tab content
                                        const componentGradeElement = targetTab.querySelector('.component-grade-value');
                                        const componentDescriptorElement = targetTab.querySelector('.component-grade-descriptor');
                                        const componentInitialElement = targetTab.querySelector('.component-initial-grade');

                                        if (componentGradeElement) {
                                            const componentGrade = componentGradeElement.textContent;
                                            const componentDescriptor = componentDescriptorElement ? componentDescriptorElement.textContent : '';
                                            const componentInitial = componentInitialElement ? componentInitialElement.textContent : '';

                                            // Store component grade if not already stored
                                            if (!componentGrades[componentName]) {
                                                componentGrades[componentName] = {
                                                    initial: componentInitial,
                                                    final: componentGrade,
                                                    descriptor: componentDescriptor
                                                };
                                            }

                                            // Update displays
                                            if (finalGradeDisplay) {
                                                finalGradeDisplay.querySelector('.final-grade-value').textContent = componentGrade;
                                                if (componentInitial) {
                                                    finalGradeDisplay.querySelector('.initial-grade-value').textContent = componentInitial;
                                                }
                                                if (componentDescriptor) {
                                                    finalGradeDisplay.querySelector('.grade-descriptor').textContent = componentDescriptor;
                                                }
                                            }

                                            if (mapehGradeDisplay) {
                                                const titleElement = mapehGradeDisplay.querySelector('.mapeh-grade-title');
                                                if (titleElement) {
                                                    if (componentName === 'MAPEH Overall') {
                                                        titleElement.textContent = 'MAPEH GRADE';
                                                    } else {
                                                        titleElement.textContent = componentName + ' GRADE';
                                                    }
                                                }

                                                mapehGradeDisplay.querySelector('.mapeh-grade-value').textContent = componentGrade;
                                                if (componentDescriptor) {
                                                    mapehGradeDisplay.querySelector('.mapeh-grade-descriptor').textContent = componentDescriptor;
                                                }
                                            }
                                        }
                                    }
                                });
                            });

                            // Special handling for PE tab - ensure it works with exact database match
                            const peTab = document.querySelector('[data-bs-target="#mapeh-physical-education"]');
                            if (!peTab) {
                                // Try alternate format if the properly formatted one doesn't exist
                                const altPeTab = document.querySelector('[data-bs-target="#mapeh-physical education"]');
                                if (altPeTab) {
                                    // Correct the tab target attribute to match the id pattern
                                    altPeTab.setAttribute('data-bs-target', '#mapeh-physical-education');

                                    // Also correct the corresponding tab pane id if needed
                                    const pePane = document.getElementById('mapeh-physical education');
                                    if (pePane) {
                                        pePane.id = 'mapeh-physical-education';
                                    }
                                }
                            }

                            // Add proper classes to grade elements for targeting
                            const mapehGradeElement = document.querySelector('.mapeh-grade');
                            if (mapehGradeElement) {
                                mapehGradeElement.classList.add('mapeh-grade-display');
                                mapehGradeElement.querySelector('h5, h6')?.classList.add('mapeh-grade-title');
                                mapehGradeElement.querySelector('.display-2, .display-3, .display-4')?.classList.add('mapeh-grade-value');
                                mapehGradeElement.querySelector('.small, .text-muted')?.classList.add('mapeh-grade-descriptor');
                            }

                            const finalGradeElement = document.querySelector('.final-grade');
                            if (finalGradeElement) {
                                finalGradeElement.classList.add('final-grade-display');
                                const gradeValueElement = finalGradeElement.querySelector('.display-2, .display-3, .display-4');
                                if (gradeValueElement) gradeValueElement.classList.add('final-grade-value');

                                const descriptorElement = finalGradeElement.querySelector('.badge, .small');
                                if (descriptorElement) descriptorElement.classList.add('grade-descriptor');

                                const initialGradeElement = finalGradeElement.querySelector('.text-muted .small');
                                if (initialGradeElement) initialGradeElement.classList.add('initial-grade-value');
                            }

                            // Add class to component grades in component tabs
                            document.querySelectorAll('#mapehComponentTabsContent .tab-pane').forEach(function(tab) {
                                if (tab.id !== 'mapeh-overall') {
                                    const componentGradeElements = tab.querySelectorAll('.display-6, .fw-bold.text-info');
                                    componentGradeElements.forEach(function(el) {
                                        el.classList.add('component-grade-value');
                                    });

                                    const componentDescriptorElements = tab.querySelectorAll('.small.text-muted, .badge');
                                    componentDescriptorElements.forEach(function(el) {
                                        el.classList.add('component-grade-descriptor');
                                    });

                                    const componentInitialElements = tab.querySelectorAll('.fw-bold');
                                    componentInitialElements.forEach(function(el) {
                                        if (el.textContent.includes('%')) {
                                            el.classList.add('component-initial-grade');
                                        }
                                    });
                                }
                            });
                        }
                    });
                </script>
                                                    @endforeach
                                            @endif

@push('scripts')
<script>
    // Record Grade Modal Functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Get modal elements
        const recordGradeModal = document.getElementById('recordGradeModal');
        const writtenWorkLink = document.getElementById('writtenWorkLink');
        const performanceTaskLink = document.getElementById('performanceTaskLink');
        const quarterlyLink = document.getElementById('quarterlyLink');

        // Add hover effect to assessment options
        document.querySelectorAll('.assessment-option').forEach(option => {
            option.addEventListener('mouseenter', function() {
                this.classList.add('shadow-sm');
                this.style.transform = 'translateY(-3px)';
                this.style.transition = 'transform 0.2s, box-shadow 0.2s';
            });

            option.addEventListener('mouseleave', function() {
                this.classList.remove('shadow-sm');
                this.style.transform = 'translateY(0)';
            });
        });
    });
</script>
@endpush

@endsection