@extends('layouts.app')

@section('content')
@include('teacher.grades.modals.edit-assessment-modal')
<style>
    /* Dark mode styles */
    .dark .card-header.bg-white {
        background-color: var(--bg-card-header) !important;
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .card-body {
        background-color: var(--bg-card);
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .badge.bg-light.text-dark {
        background-color: var(--bg-card) !important;
        color: var(--text-color) !important;
        border-color: var(--border-color) !important;
    }

    .dark .form-control {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .form-text {
        color: var(--text-muted);
    }

    .dark .text-muted {
        color: var(--text-muted) !important;
    }

    .dark .alert-info {
        background-color: rgba(13, 202, 240, 0.15);
        border-color: rgba(13, 202, 240, 0.4);
        color: var(--text-color);
    }

    .dark .alert-warning {
        background-color: rgba(255, 193, 7, 0.15);
        border-color: rgba(255, 193, 7, 0.4);
        color: var(--text-color);
    }

    .dark .component-card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .component-card .card-header {
        background-color: var(--bg-card-header) !important;
    }

    /* Component Card Styles */
    .component-card {
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .component-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .component-input:focus {
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .badge-sm {
        font-size: 0.7rem;
        padding: 0.2rem 0.4rem;
    }

    /* Assessment Table Styles */
    .assessment-table {
        font-size: 0.9rem;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .assessment-table thead th {
        background-color: #f8f9fa;
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .assessment-table tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .assessment-table .btn {
        border-radius: 4px;
        transition: all 0.2s;
    }

    .assessment-table .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
    }

    /* Progress bar styles */
    .progress {
        border-radius: 10px;
        background-color: #e9ecef;
        overflow: hidden;
    }

    .progress-bar {
        border-radius: 10px;
        transition: width 0.6s ease;
    }

    /* Dark mode overrides for new styles */
    .dark .assessment-table thead th {
        background-color: var(--bg-card-header);
        border-bottom: 2px solid var(--border-color);
        color: var(--text-color);
    }

    .dark .assessment-table {
        border-color: var(--border-color);
    }

    .dark .assessment-table tbody tr:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }

    .dark .progress {
        background-color: rgba(255, 255, 255, 0.1);
    }
</style>
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-tools text-primary me-2"></i> Assessment Setup</h2>
                <div>
                    <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id, 'term' => $term]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Grades
                    </a>
                </div>
            </div>
            <p class="text-muted">Set up the assessment details before entering student grades.</p>
        </div>
    </div>

    @php
        // Check if this is a MAPEH subject
        $isMAPEH = false;
        $mapehComponents = [];
        if (isset($subject->components) && $subject->components->count() > 0) {
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

            if ($isMAPEH) {
                // Organize the component objects for easy access
                foreach ($subject->components as $component) {
                    $componentName = strtolower($component->name);
                    if (stripos($componentName, 'music') !== false) {
                        $mapehComponents['music'] = $component;
                    } elseif (stripos($componentName, 'art') !== false) {
                        $mapehComponents['arts'] = $component;
                    } elseif (stripos($componentName, 'physical') !== false || stripos($componentName, 'pe') !== false) {
                        $mapehComponents['pe'] = $component;
                    } elseif (stripos($componentName, 'health') !== false) {
                        $mapehComponents['health'] = $component;
                    }
                }
            }
        }
    @endphp

    @if($isMAPEH)
    <!-- MAPEH Subject Layout -->
    <div class="row">
        <div class="col-md-10 mx-auto">
            <!-- Flash Messages -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-2"></i> {{ $subject->name }} (MAPEH)
                            @if(isset($section) && $section)
                            - {{ $section->name }}
                            @endif
                        </h5>
                        <div>
                            <span class="badge bg-light text-dark">{{ $term }}</span>
                            <span class="badge {{ $gradeType == 'written_work' ? 'bg-primary' : ($gradeType == 'performance_task' ? 'bg-success' : 'bg-warning') }}">{{ $gradeTypes[$gradeType] ?? $gradeType }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info">
                        <div class="d-flex">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h6 class="alert-heading">MAPEH Subject</h6>
                                <p class="mb-0">
                                    MAPEH is a consolidated subject with 4 components: Music, Arts, Physical Education, and Health.
                                    Select which component(s) you want to assess with this assessment.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('teacher.grades.store-assessment-setup') }}" id="mapehAssessmentForm">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <input type="hidden" name="term" value="{{ $term }}">
                        <input type="hidden" name="grade_type" value="{{ $gradeType }}">
                        <input type="hidden" name="section_id" value="{{ $section->id ?? request()->get('section_id', 1) }}">
                        <input type="hidden" name="is_mapeh" value="1">

                        <!-- Debug Info -->
                        @if($errors->any())
                        <div class="alert alert-danger mb-4">
                            <h6>Form Submission Errors:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <div class="mb-4">
                            <label for="assessment_name" class="form-label">Assessment Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('assessment_name') is-invalid @enderror"
                                id="assessment_name" name="assessment_name" value="{{ old('assessment_name') }}" required
                                placeholder="e.g., Quiz 1, Performance Task 2, Final Exam">
                            @error('assessment_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">
                                Provide a descriptive name for this assessment.
                            </div>

                            <!-- Assessment Progress Indicator -->
                            <div class="mt-3">
                                @php
                                    $maxAssessments = $assessmentLimits[$gradeType] ?? 0;
                                    $currentCount = $assessmentCount ?? 0;
                                @endphp
                                <small class="text-muted">
                                    <!-- <div class="d-flex align-items-center gap-2 mb-2"> -->
                                        <!-- <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($currentCount / $maxAssessments) * 100 }}%"
                                                aria-valuenow="{{ $currentCount }}" aria-valuemin="0" aria-valuemax="{{ $maxAssessments }}"></div>
                                        </div> -->
                                        <!-- <span class="badge bg-primary">{{ $currentCount }}/{{ $maxAssessments }}</span> -->
                                    <!-- </div> -->

                                    @if($currentCount > 0)
                                        <div class="mt-3">
                                            <h6 class="fw-bold mb-2"><i class="fas fa-clipboard-list me-1"></i> Existing Assessments</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover table-bordered assessment-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col" width="70%">Assessment Name</th>
                                                            <th scope="col" width="30%" class="text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($existingAssessments ?? [] as $existingAssessment)
                                                            <tr>
                                                                <td class="align-middle">{{ $existingAssessment }}</td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-primary edit-assessment-btn"
                                                                        data-subject-id="{{ $subject->id }}"
                                                                        data-term="{{ $term }}"
                                                                        data-grade-type="{{ $gradeType }}"
                                                                        data-assessment-name="{{ $existingAssessment }}"
                                                                        data-is-mapeh="0"
                                                                        data-component-id="">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </small>
                            </div>
                        </div>

                        <!-- Component Selection -->
                        @if($gradeType == 'quarterly')
                            <!-- For Quarterly Assessment, show single score input -->
                            <div class="mb-4">
                                <label for="quarterly_max_score" class="form-label">Maximum Score for All Components <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('max_score') is-invalid @enderror"
                                    id="quarterly_max_score" name="quarterly_max_score"
                                    value="{{ old('quarterly_max_score') }}" min="1" required placeholder="Enter maximum score">
                                <div class="form-text">
                                    <i class="fas fa-info-circle me-1"></i> This score will be applied to all MAPEH components (Music, Arts, Physical Education, and Health).
                                </div>
                            </div>

                            <!-- Hidden inputs for components -->
                            @foreach(['music', 'arts', 'pe', 'health'] as $componentKey)
                                @if(isset($mapehComponents[$componentKey]))
                                    <input type="hidden" name="selected_components[]" value="{{ $mapehComponents[$componentKey]->id }}">
                                    <input type="hidden" class="component-score-input" name="component_max_score[{{ $mapehComponents[$componentKey]->id }}]" value="100">
                                @endif
                            @endforeach
                        @else
                            <div class="mb-4">
                                <label class="form-label d-block">Select MAPEH Component(s) <span class="text-danger">*</span></label>

                                <div class="row">
                                    @php
                                        $componentClasses = [
                                            'music' => 'border-primary text-primary',
                                            'arts' => 'border-danger text-danger',
                                            'pe' => 'border-success text-success',
                                            'health' => 'border-warning text-warning'
                                        ];

                                        $componentFullNames = [
                                            'music' => 'Music',
                                            'arts' => 'Arts',
                                            'pe' => 'Physical Education',
                                            'health' => 'Health'
                                        ];

                                        $componentIcons = [
                                            'music' => 'fas fa-music',
                                            'arts' => 'fas fa-paint-brush',
                                            'pe' => 'fas fa-running',
                                            'health' => 'fas fa-heartbeat'
                                        ];
                                    @endphp

                                    @foreach(['music', 'arts', 'pe', 'health'] as $componentKey)
                                        @if(isset($mapehComponents[$componentKey]))
                                            <div class="col-md-6 col-lg-3 mb-3">
                                                <div class="card h-100 component-card">
                                                    <div class="card-header border-bottom-0 bg-white pt-3">
                                                        <div class="form-check">
                                                            <input class="form-check-input component-checkbox" type="checkbox"
                                                                name="selected_components[]"
                                                                value="{{ $mapehComponents[$componentKey]->id }}"
                                                                id="component_{{ $mapehComponents[$componentKey]->id }}"
                                                                checked>
                                                            <label class="form-check-label fw-bold {{ $componentClasses[$componentKey] }}"
                                                                   for="component_{{ $mapehComponents[$componentKey]->id }}">
                                                                <i class="{{ $componentIcons[$componentKey] }} me-2"></i>
                                                                {{ $componentFullNames[$componentKey] }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="card-body pt-0">
                                                        <small class="text-muted d-block mb-3">
                                                            Component of {{ $subject->name }}
                                                        </small>

                                                        <div class="mb-3">
                                                            <label for="max_score_{{ $mapehComponents[$componentKey]->id }}" class="form-label small">
                                                                Maximum Score
                                                            </label>
                                                            <input type="number" class="form-control form-control-sm component-input"
                                                                id="max_score_{{ $mapehComponents[$componentKey]->id }}"
                                                                name="component_max_score[{{ $mapehComponents[$componentKey]->id }}]"
                                                                value="{{ old('component_max_score.' . $mapehComponents[$componentKey]->id) }}"
                                                                min="1" required placeholder="Enter maximum score">
                                                        </div>

                                                        <!-- Component Assessment Indicators -->
                                                        @php
                                                            $componentId = $mapehComponents[$componentKey]->id;
                                                            $componentCount = $componentAssessmentCounts[$componentId] ?? 0;
                                                            $maxAssessments = $assessmentLimits[$gradeType] ?? 0;
                                                        @endphp
                                                        <div class="mt-2">
                                                            <small class="text-muted">
                                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                                    <div class="progress flex-grow-1" style="height: 8px;">
                                                                        <div class="progress-bar bg-{{ $componentKey == 'music' ? 'primary' : ($componentKey == 'arts' ? 'danger' : ($componentKey == 'pe' ? 'success' : 'warning')) }}"
                                                                             role="progressbar"
                                                                             style="width: {{ ($componentCount / $maxAssessments) * 100 }}%"
                                                                             aria-valuenow="{{ $componentCount }}"
                                                                             aria-valuemin="0"
                                                                             aria-valuemax="{{ $maxAssessments }}"></div>
                                                                    </div>
                                                                    <span class="badge bg-{{ $componentKey == 'music' ? 'primary' : ($componentKey == 'arts' ? 'danger' : ($componentKey == 'pe' ? 'success' : 'warning')) }}">{{ $componentCount }}/{{ $maxAssessments }}</span>
                                                                </div>
                                                                @if(isset($componentExistingAssessments[$componentId]) && count($componentExistingAssessments[$componentId]) > 0)
                                                                    <div class="mt-3 small">
                                                                        <h6 class="fw-bold mb-2"><i class="fas fa-clipboard-list me-1"></i> Existing Assessments</h6>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-sm table-hover table-bordered assessment-table">
                                                                                <thead class="table-light">
                                                                                    <tr>
                                                                                        <th scope="col" width="70%">Assessment Name</th>
                                                                                        <th scope="col" width="30%" class="text-center">Actions</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    @foreach($componentExistingAssessments[$componentId] as $componentAssessment)
                                                                                        <tr>
                                                                                            <td class="align-middle">{{ $componentAssessment }}</td>
                                                                                            <td class="text-center">
                                                                                                <button type="button" class="btn btn-sm btn-{{ $componentKey == 'music' ? 'primary' : ($componentKey == 'arts' ? 'danger' : ($componentKey == 'pe' ? 'success' : 'warning')) }} edit-assessment-btn"
                                                                                                    data-subject-id="{{ $subject->id }}"
                                                                                                    data-term="{{ $term }}"
                                                                                                    data-grade-type="{{ $gradeType }}"
                                                                                                    data-assessment-name="{{ $componentAssessment }}"
                                                                                                    data-is-mapeh="1"
                                                                                                    data-component-id="{{ $componentId }}">
                                                                                                    <i class="fas fa-edit"></i> Edit
                                                                                                </button>
                                                                                            </td>
                                                                                        </tr>
                                                                                    @endforeach
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>

                                <div class="form-text mt-2">
                                    <i class="fas fa-info-circle me-1"></i> Select at least one component to include in this assessment.
                                </div>
                            </div>
                        @endif

                        <!-- Alert Info -->
                        <div class="alert alert-{{ $gradeType == 'quarterly' ? 'warning' : 'info' }}">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-{{ $gradeType == 'quarterly' ? 'exclamation-triangle' : 'info-circle' }} fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading">Assessment Type: {{ $gradeTypes[$gradeType] ?? $gradeType }}</h6>
                                    <p class="mb-0">
                                        @if($gradeType == 'quarterly')
                                            For Quarterly Assessment, all MAPEH components (Music, Arts, Physical Education, and Health) are automatically included with the same maximum score of Teacher's Choice.
                                        @else
                                            Each MAPEH component is graded separately. The final MAPEH grade will be calculated as the average of all component grades after each component has its own written work, performance task, and quarterly assessments computed.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id, 'term' => $term]) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            @php
                                $maxAssessments = $assessmentLimits[$gradeType] ?? 0;
                                $currentCount = $assessmentCount ?? 0;
                                $isMaxReached = $currentCount >= $maxAssessments;

                                // For MAPEH, check if any component has reached max
                                $anyComponentMaxReached = false;
                                if ($isMAPEH) {
                                    foreach ($subject->components as $component) {
                                        $componentId = $component->id;
                                        $componentCount = $componentAssessmentCounts[$componentId] ?? 0;
                                        if ($componentCount >= $maxAssessments) {
                                            $anyComponentMaxReached = true;
                                            break;
                                        }
                                    }
                                }
                            @endphp

                            @if($isMaxReached || $anyComponentMaxReached)
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="fas fa-ban me-1"></i> Maximum Assessments Reached
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-arrow-right me-1"></i> Proceed to Grade Entry
                                </button>
                            @endif
                        </div>

                        @if($isMaxReached || $anyComponentMaxReached)
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Cannot proceed to grade entry:</strong> You have reached the maximum limit of {{ $maxAssessments }} assessments for {{ $gradeTypes[$gradeType] }}.
                                <p class="mt-2 mb-0">You can still view the Assessment Setup page, but you cannot create more assessments. Please use existing assessments or delete an assessment to create a new one.</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Regular Subject Layout -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Flash Messages -->
            @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-triangle me-2"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif
            
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-book me-2"></i> {{ $subject->name }}
                            @if(isset($section) && $section)
                            - {{ $section->name }}
                            @endif
                        </h5>
                        <div>
                            <span class="badge bg-info">{{ $term }}</span>
                            <span class="badge {{ $gradeType == 'written_work' ? 'bg-primary' : ($gradeType == 'performance_task' ? 'bg-success' : 'bg-warning') }}">{{ $gradeTypes[$gradeType] ?? $gradeType }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('teacher.grades.store-assessment-setup') }}">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <input type="hidden" name="term" value="{{ $term }}">
                        <input type="hidden" name="grade_type" value="{{ $gradeType }}">
                        <input type="hidden" name="section_id" value="{{ $section->id ?? request()->get('section_id', 1) }}">

                        <div class="mb-4">
                            <label for="assessment_name" class="form-label">Assessment Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('assessment_name') is-invalid @enderror"
                                id="assessment_name" name="assessment_name" value="{{ old('assessment_name') }}" required
                                placeholder="e.g., Quiz 1, Performance Task 2, Final Exam">
                            @error('assessment_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">
                                Provide a descriptive name for this assessment that identifies it from others.
                            </div>

                            <!-- Assessment Count Indicator -->
                            <div class="mt-3">
                                <h6 class="mb-2">Assessment Indicators:</h6>
                                <div class="d-flex flex-wrap gap-2 mb-2">
                                    @php
                                        $maxAssessments = $assessmentLimits[$gradeType] ?? 0;
                                        $currentCount = $assessmentCount ?? 0;
                                    @endphp

                                    @for($i = 1; $i <= $maxAssessments; $i++)
                                        @if($i <= $currentCount)
                                            <span class="badge bg-success">{{ $i }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ $i }}</span>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar bg-primary" role="progressbar" style="width: {{ ($currentCount / $maxAssessments) * 100 }}%"
                                                aria-valuenow="{{ $currentCount }}" aria-valuemin="0" aria-valuemax="{{ $maxAssessments }}"></div>
                                        </div>
                                        <span class="badge bg-primary">{{ $currentCount }}/{{ $maxAssessments }}</span>
                                    </div>

                                    @if($currentCount > 0)
                                        <div class="mt-3">
                                            <h6 class="fw-bold mb-2"><i class="fas fa-clipboard-list me-1"></i> Existing Assessments</h6>
                                            <div class="table-responsive">
                                                <table class="table table-sm table-hover table-bordered assessment-table">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col" width="70%">Assessment Name</th>
                                                            <th scope="col" width="30%" class="text-center">Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($existingAssessments ?? [] as $existingAssessment)
                                                            <tr>
                                                                <td class="align-middle">{{ $existingAssessment }}</td>
                                                                <td class="text-center">
                                                                    <button type="button" class="btn btn-sm btn-primary edit-assessment-btn"
                                                                        data-subject-id="{{ $subject->id }}"
                                                                        data-term="{{ $term }}"
                                                                        data-grade-type="{{ $gradeType }}"
                                                                        data-assessment-name="{{ $existingAssessment }}"
                                                                        data-is-mapeh="0"
                                                                        data-component-id="">
                                                                        <i class="fas fa-edit"></i> Edit
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="max_score" class="form-label">Maximum Score/Total Items <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_score') is-invalid @enderror"
                                id="max_score" name="max_score" value="{{ old('max_score') }}" min="1" required placeholder="Enter maximum score">
                            @error('max_score')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <div class="form-text">
                                Enter the total possible points for this assessment. Student scores will be measured against this value.
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading">Assessment Type: {{ $gradeTypes[$gradeType] ?? $gradeType }}</h6>
                                    <p class="mb-0">
                                        @if($gradeType == 'written_work')
                                            Written Works include quizzes, unit tests, and other written assessments that measure knowledge acquisition.
                                        @elseif($gradeType == 'performance_task')
                                            Performance Tasks measure how students apply knowledge and skills in authentic contexts.
                                        @elseif($gradeType == 'quarterly')
                                            Quarterly Assessments are comprehensive exams that measure overall learning for the quarter.
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id, 'term' => $term]) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            @php
                                $maxAssessments = $assessmentLimits[$gradeType] ?? 0;
                                $currentCount = $assessmentCount ?? 0;
                                $isMaxReached = $currentCount >= $maxAssessments;
                            @endphp

                            @if($isMaxReached)
                                <button type="button" class="btn btn-secondary" disabled>
                                    <i class="fas fa-ban me-1"></i> Maximum Assessments Reached
                                </button>
                            @else
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-arrow-right me-1"></i> Proceed to Grade Entry
                                </button>
                            @endif
                        </div>

                        @if($isMaxReached)
                            <div class="alert alert-warning mt-3">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Cannot proceed to grade entry:</strong> You have reached the maximum limit of {{ $maxAssessments }} assessments for {{ $gradeTypes[$gradeType] }}.
                                <p class="mt-2 mb-0">You can still view the Assessment Setup page, but you cannot create more assessments. Please use existing assessments or delete an assessment to create a new one.</p>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mapehForm = document.getElementById('mapehAssessmentForm');
        if (mapehForm) {
            const isQuarterlyAssessment = '{{ $gradeType }}' === 'quarterly';

            if (isQuarterlyAssessment) {
                // Handle quarterly max score changes
                const quarterlyScoreInput = document.getElementById('quarterly_max_score');
                const componentScoreInputs = document.querySelectorAll('.component-score-input');

                quarterlyScoreInput.addEventListener('input', function() {
                    const newValue = this.value;
                    componentScoreInputs.forEach(input => {
                        input.value = newValue;
                    });
                });

                // Set initial values
                const initialValue = quarterlyScoreInput.value;
                componentScoreInputs.forEach(input => {
                    input.value = initialValue;
                });
            } else {
                // Handle component checkbox changes for non-quarterly assessments
                const componentCheckboxes = document.querySelectorAll('.component-checkbox');
                componentCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const componentId = this.value;
                        const inputs = document.querySelectorAll(`input[name^="component_max_score[${componentId}]"]`);

                        inputs.forEach(input => {
                            input.disabled = !this.checked;
                            if (!this.checked) {
                                input.classList.add('bg-light');
                            } else {
                                input.classList.remove('bg-light');
                            }
                        });

                        // Update parent card styling
                        const parentCard = this.closest('.component-card');
                        if (parentCard) {
                            if (this.checked) {
                                parentCard.classList.add('border');
                                parentCard.classList.remove('opacity-50');
                            } else {
                                parentCard.classList.remove('border');
                                parentCard.classList.add('opacity-50');
                            }
                        }
                    });
                });
            }

            // Form validation
            mapehForm.addEventListener('submit', function(event) {
                if (!isQuarterlyAssessment && document.querySelectorAll('.component-checkbox:checked').length === 0) {
                    event.preventDefault();
                    alert('Please select at least one MAPEH component for this assessment.');
                }
            });
        }

        // Edit Assessment Functionality
        const editModal = new bootstrap.Modal(document.getElementById('editAssessmentModal'));
        const editForm = document.getElementById('editAssessmentForm');

        // Add event listeners to all edit buttons
        document.querySelectorAll('.edit-assessment-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Get data attributes
                const subjectId = this.getAttribute('data-subject-id');
                const term = this.getAttribute('data-term');
                const gradeType = this.getAttribute('data-grade-type');
                const assessmentName = this.getAttribute('data-assessment-name');
                const isMAPEH = this.getAttribute('data-is-mapeh');
                const componentId = this.getAttribute('data-component-id');

                // Set form values
                document.getElementById('edit_subject_id').value = subjectId;
                document.getElementById('edit_term').value = term;
                document.getElementById('edit_grade_type').value = gradeType;
                document.getElementById('edit_old_assessment_name').value = assessmentName;
                document.getElementById('edit_assessment_name').value = assessmentName;
                document.getElementById('edit_is_mapeh').value = isMAPEH;

                // Handle component_id field
                if (isMAPEH === '1' && componentId && componentId !== '' && componentId !== '0') {
                    document.getElementById('edit_component_id').value = componentId;
                } else {
                    document.getElementById('edit_component_id').value = '';
                }

                // Set a default value for max score
                document.getElementById('edit_max_score').value = 100;

                // Get the max score for this assessment
                const targetSubjectId = isMAPEH === '1' && componentId ? componentId : subjectId;
                const maxScoreUrl = `/api/teacher/grades/get-assessment-max-score?subject_id=${targetSubjectId}&term=${term}&grade_type=${gradeType}&assessment_name=${encodeURIComponent(assessmentName)}`;

                fetch(maxScoreUrl)
                .then(response => response.json())
                .then(data => {
                    if (data && data.success && data.max_score) {
                        const maxScore = Math.round(data.max_score);
                        document.getElementById('edit_max_score').value = maxScore;
                    }
                })
                .catch(error => {
                    console.error('Error fetching max score:', error);
                });

                // Show the modal
                editModal.show();
            });
        });

        // Form validation for the edit form
        editForm.addEventListener('submit', function(event) {
            const assessmentName = document.getElementById('edit_assessment_name').value.trim();
            const maxScore = document.getElementById('edit_max_score').value;

            if (!assessmentName) {
                event.preventDefault();
                alert('Please enter an assessment name.');
                return false;
            }

            if (!maxScore || isNaN(parseFloat(maxScore)) || parseFloat(maxScore) <= 0) {
                event.preventDefault();
                alert('Please enter a valid maximum score (greater than 0).');
                return false;
            }

            // Form is valid, submission will proceed
            return true;
        });
    });
</script>
@endpush

@endsection