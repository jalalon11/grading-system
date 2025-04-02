@extends('layouts.app')

@push('styles')
<style>
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
    }
    .grade-input {
        max-width: 90px;
        margin: 0 auto;
    }
    .batch-table th, .batch-table td {
        vertical-align: middle;
    }
    .batch-header {
        background-color: rgba(0,123,255,0.05);
        border-left: 4px solid #0d6efd;
        padding: 15px;
        margin-bottom: 25px;
        border-radius: 5px;
    }
    .score-container {
        position: relative;
    }
    .score-validation {
        position: absolute;
        top: 0;
        right: -20px;
    }
    .hover-row:hover {
        background-color: rgba(0,0,0,0.02);
    }
    .component-header {
        background-color: #f8f9fa;
        font-weight: bold;
    }
    .tab-content {
        padding-top: 20px;
    }
    .nav-tabs .nav-link.active {
        font-weight: bold;
    }
    .component-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .component-card:hover {
        transform: translateY(-3px);
    }
    .music-border {
        border-left-color: #3498db !important;
    }
    .arts-border {
        border-left-color: #e74c3c !important;
    }
    .pe-border {
        border-left-color: #2ecc71 !important;
    }
    .health-border {
        border-left-color: #f39c12 !important;
    }
    
    /* Dark mode styles */
    .dark .batch-header {
        background-color: rgba(13, 110, 253, 0.15);
        border-color: #0d6efd;
    }
    
    .dark .card-header.bg-white {
        background-color: var(--bg-card-header) !important;
        color: var(--text-color);
        border-color: var(--border-color);
    }
    
    .dark .card-body {
        background-color: var(--bg-card);
        color: var(--text-color);
    }
    
    .dark .component-header {
        background-color: var(--bg-card-header);
        color: var(--text-color);
    }
    
    .dark .hover-row:hover {
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    .dark .form-control {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        color: var(--text-color);
    }
    
    .dark .form-control:focus {
        background-color: var(--bg-card);
        border-color: #4361ee;
        color: var(--text-color);
    }
    
    .dark .table {
        color: var(--text-color);
    }
    
    .dark .table th, 
    .dark .table td {
        border-color: var(--border-color);
    }
    
    .dark .alert-info {
        background-color: rgba(13, 202, 240, 0.15);
        border-color: rgba(13, 202, 240, 0.4);
        color: var(--text-color);
    }
    
    .dark .alert-secondary {
        background-color: rgba(108, 117, 125, 0.15);
        border-color: rgba(108, 117, 125, 0.4);
        color: var(--text-color);
    }
    
    .dark .text-muted {
        color: var(--text-muted) !important;
    }
    
    .dark .nav-tabs {
        border-bottom-color: var(--border-color);
    }
    
    .dark .nav-tabs .nav-link {
        color: var(--text-muted);
    }
    
    .dark .nav-tabs .nav-link:hover {
        border-color: var(--border-color);
        color: var(--text-color);
    }
    
    .dark .nav-tabs .nav-link.active {
        background-color: var(--bg-card);
        border-color: var(--border-color);
        border-bottom-color: var(--bg-card);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-users-class text-primary me-2"></i> Batch Grade Entry</h2>
                <div>
                    <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id, 'term' => $request->term]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Grades
                    </a>
                </div>
            </div>
            <p class="text-muted">Enter grades for multiple students at once for this assessment.</p>
        </div>
    </div>

    <!-- Assessment Information Box -->
    <div class="batch-header shadow-sm mb-4">
        <div class="row">
            <div class="col-md-6">
                <h5 class="mb-2 fw-bold text-primary">Assessment Details</h5>
                <div class="mb-2">
                    <span class="text-muted me-2">Subject:</span>
                    <span class="fw-medium">{{ $subject->name }} ({{ $subject->code }})</span>
                    @if($isMAPEH)
                        <span class="badge bg-info ms-2">MAPEH</span>
                    @endif
                </div>
                <div class="mb-2">
                    <span class="text-muted me-2">Section:</span>
                    <span class="fw-medium">{{ $section->name }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-muted me-2">Academic Term:</span>
                    <span class="fw-medium">{{ $terms[$request->term] ?? $request->term }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <h5 class="mb-2 fw-bold text-primary">Grading Information</h5>
                <div class="mb-2">
                    <span class="text-muted me-2">Assessment Name:</span>
                    <span class="fw-medium">{{ session('assessment_name') }}</span>
                </div>
                <div class="mb-2">
                    <span class="text-muted me-2">Assessment Type:</span>
                    <span class="fw-medium">{{ $gradeTypes[$request->grade_type] ?? $request->grade_type }}</span>
                </div>
                @if(!$isMAPEH)
                <div class="mb-2">
                    <span class="text-muted me-2">Maximum Score:</span>
                    <span class="fw-medium">{{ session('max_score') }} points</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-clipboard-check me-2"></i> Student Scores for {{ session('assessment_name') }}
                </h5>
                <span class="badge bg-primary">{{ count($students) }} Students</span>
            </div>
        </div>
        <div class="card-body">
            @if(count($students) == 0)
                <div class="text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-users fa-3x text-muted"></i>
                    </div>
                    <h5 class="text-muted">No Students Found</h5>
                    <p class="text-muted">There are no students assigned to this section yet.</p>
                </div>
            @else
                <form method="POST" action="{{ route('teacher.grades.batch-store') }}" id="batchGradeForm">
                    @csrf
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <input type="hidden" name="term" value="{{ $request->term }}">
                    <input type="hidden" name="grade_type" value="{{ $request->grade_type }}">
                    <input type="hidden" name="assessment_name" value="{{ session('assessment_name') }}">
                    <input type="hidden" name="section_id" value="{{ $section->id }}">
                    
                    @if($isMAPEH)
                        <!-- MAPEH Subject Form -->
                        <input type="hidden" name="is_mapeh" value="1">
                        
                        <div class="alert alert-info mb-4">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-info-circle fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading mb-2">MAPEH Components</h6>
                                    <p class="mb-0">
                                        For MAPEH subjects, you will enter grades for each component separately. Use the tabs below to switch between components.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Component Navigation Tabs -->
                        <ul class="nav nav-tabs mb-3" id="mapehTabs" role="tablist">
                            @foreach($subject->components as $index => $component)
                                @php
                                    $componentClass = '';
                                    $componentSlug = strtolower(str_replace(' ', '-', $component->name));
                                    
                                    if (stripos($component->name, 'music') !== false) {
                                        $componentClass = 'text-primary';
                                    } elseif (stripos($component->name, 'art') !== false) {
                                        $componentClass = 'text-danger';
                                    } elseif (stripos($component->name, 'physical') !== false || stripos($component->name, 'pe') !== false) {
                                        $componentClass = 'text-success';
                                    } elseif (stripos($component->name, 'health') !== false) {
                                        $componentClass = 'text-warning';
                                    }
                                    
                                    // Check if this component was selected in assessment setup
                                    $isSelectedComponent = in_array($component->id, session('selected_components', []));
                                    $componentMaxScore = session('component_max_score.' . $component->id, 100);
                                @endphp
                                
                                @if($isSelectedComponent)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $index === 0 ? 'active' : '' }} {{ $componentClass }}" 
                                            id="{{ $componentSlug }}-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#{{ $componentSlug }}-content" 
                                            type="button" 
                                            role="tab" 
                                            aria-controls="{{ $componentSlug }}-content" 
                                            aria-selected="{{ $index === 0 ? 'true' : 'false' }}">
                                        {{ $component->name }}
                                        <input type="hidden" name="component_ids[]" value="{{ $component->id }}">
                                        <input type="hidden" name="component_max_scores[{{ $component->id }}]" value="{{ $componentMaxScore }}">
                                    </button>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                        
                        <!-- Debug Information -->
                        <div class="alert alert-secondary mb-3 small d-none">
                            <h6>Debug Info:</h6>
                            <p>Selected Components: {{ implode(', ', session('selected_components', [])) }}</p>
                            <p>Component Max Scores: {{ json_encode(session('component_max_score', [])) }}</p>
                        </div>
                        
                        <!-- Component Tab Contents -->
                        <div class="tab-content" id="mapehTabContent">
                            @php $firstTab = true; @endphp
                            @foreach($subject->components as $index => $component)
                                @php
                                    $componentSlug = strtolower(str_replace(' ', '-', $component->name));
                                    $componentClass = '';
                                    
                                    if (stripos($component->name, 'music') !== false) {
                                        $componentClass = 'music-border';
                                    } elseif (stripos($component->name, 'art') !== false) {
                                        $componentClass = 'arts-border';
                                    } elseif (stripos($component->name, 'physical') !== false || stripos($component->name, 'pe') !== false) {
                                        $componentClass = 'pe-border';
                                    } elseif (stripos($component->name, 'health') !== false) {
                                        $componentClass = 'health-border';
                                    }
                                    
                                    // Check if this component was selected in assessment setup
                                    $isSelectedComponent = in_array($component->id, session('selected_components', []));
                                    $componentMaxScore = session('component_max_score.' . $component->id, 100);
                                @endphp
                                
                                @if($isSelectedComponent)
                                <div class="tab-pane fade {{ $firstTab ? 'show active' : '' }}" 
                                     id="{{ $componentSlug }}-content" 
                                     role="tabpanel" 
                                     aria-labelledby="{{ $componentSlug }}-tab">
                                    
                                    <div class="card mb-3 component-card {{ $componentClass }}">
                                        <div class="card-body py-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">{{ $component->name }} - Maximum Score: {{ $componentMaxScore }}</h6>
                                                <div>
                                                    <button type="button" class="btn btn-sm btn-outline-secondary fill-zeros" data-component="{{ $component->id }}">
                                                        <i class="fas fa-eraser me-1"></i> Fill With Zeros
                                                    </button>
                                                    <button type="button" class="btn btn-sm btn-outline-success fill-perfect" data-component="{{ $component->id }}" data-max="{{ $componentMaxScore }}">
                                                        <i class="fas fa-check-circle me-1"></i> Fill With Perfect Scores
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="table-responsive">
                                        <table class="table batch-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th width="5%">#</th>
                                                    <th width="45%">Student</th>
                                                    <th width="25%" class="text-center">{{ $component->name }} Score<br>(max: {{ $componentMaxScore }})</th>
                                                    <th width="25%">Remarks (Optional)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($students as $studentIndex => $student)
                                                    <tr class="hover-row">
                                                        <td>{{ $studentIndex + 1 }}</td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="student-avatar bg-primary bg-opacity-10 text-primary">
                                                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                                </div>
                                                                <div>
                                                                    <div class="fw-medium">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                                                    <div class="small text-muted">ID: {{ $student->student_id }}</div>
                                                                </div>
                                                            </div>
                                                            @if($firstTab)
                                                                <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="score-container">
                                                                <input type="number" class="form-control form-control-sm grade-input component-score" 
                                                                    id="score_component{{ $component->id }}_student{{ $student->id }}" 
                                                                    name="component_scores[{{ $component->id }}][{{ $student->id }}]" 
                                                                    min="0" 
                                                                    max="{{ $componentMaxScore }}" 
                                                                    value="{{ old('component_scores.' . $component->id . '.' . $student->id, '') }}"
                                                                    data-component="{{ $component->id }}"
                                                                    data-max="{{ $componentMaxScore }}"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="Enter score (0-{{ $componentMaxScore }})"
                                                                    required>
                                                                <div class="score-validation">
                                                                    <i class="fas fa-check text-success d-none"></i>
                                                                    <i class="fas fa-exclamation-triangle text-danger d-none"></i>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if($firstTab)
                                                                <input type="text" class="form-control form-control-sm" 
                                                                    id="remarks{{ $student->id }}" 
                                                                    name="remarks[]" 
                                                                    placeholder="Optional comments" 
                                                                    maxlength="255"
                                                                    value="{{ old('remarks.' . $studentIndex, '') }}"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-placement="top"
                                                                    title="Add optional comments or feedback">
                                                            @else
                                                                <div class="text-muted small">(Remarks entered in first tab apply to all components)</div>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                @php $firstTab = false; @endphp
                                @endif
                            @endforeach
                        </div>
                    @else
                        <!-- Regular Subject Form -->
                        <input type="hidden" name="max_score" value="{{ session('max_score') }}">
                        
                        <div class="table-responsive">
                            <table class="table batch-table">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">#</th>
                                        <th width="40%">Student</th>
                                        <th width="20%" class="text-center">Score (out of {{ session('max_score') }})</th>
                                        <th width="35%">Remarks (Optional)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr class="hover-row">
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="student-avatar bg-primary bg-opacity-10 text-primary">
                                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $student->last_name }}, {{ $student->first_name }}</div>
                                                        <div class="small text-muted">ID: {{ $student->student_id }}</div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="student_ids[]" value="{{ $student->id }}">
                                            </td>
                                            <td class="text-center">
                                                <div class="score-container">
                                                    <input type="number" class="form-control form-control-sm grade-input" 
                                                        id="score{{ $student->id }}" 
                                                        name="scores[]" 
                                                        min="0" 
                                                        max="{{ session('max_score') }}" 
                                                        value="{{ old('scores.' . $index, '') }}"
                                                        data-max="{{ session('max_score') }}"
                                                        data-bs-toggle="tooltip"
                                                        data-bs-placement="top"
                                                        title="Enter score (0-{{ session('max_score') }})"
                                                        required>
                                                    <div class="score-validation">
                                                        <i class="fas fa-check text-success d-none" id="validScore{{ $student->id }}"></i>
                                                        <i class="fas fa-exclamation-triangle text-danger d-none" id="invalidScore{{ $student->id }}"></i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control form-control-sm" 
                                                    id="remarks{{ $student->id }}" 
                                                    name="remarks[]" 
                                                    placeholder="Optional comments" 
                                                    maxlength="255"
                                                    value="{{ old('remarks.' . $index, '') }}"
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="Add optional comments or feedback">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 d-flex">
                            <button type="button" id="fillWithZeros" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="fas fa-eraser me-1"></i> Fill All with Zeros
                            </button>
                            <button type="button" id="applyPerfect" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-check-circle me-1"></i> Fill All with Perfect Scores
                            </button>
                        </div>
                    @endif
                    
                    <div class="mt-4 d-flex justify-content-between border-top pt-4">
                        <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id, 'term' => $request->term]) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save All Grades
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Make sure Bootstrap tabs are properly initialized
        $('#mapehTabs button').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>

@if($isMAPEH)
    <script src="{{ asset('js/mapeh-grading.js') }}"></script>
@else
    <script src="{{ asset('js/regular-grading.js') }}"></script>
@endif
@endpush
@endsection 