@extends('layouts.app')

@section('content')
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
                        </div>
                        
                        <!-- Component Selection -->
                        @if($gradeType == 'quarterly')
                            <!-- For Quarterly Assessment, show single score input -->
                            <div class="mb-4">
                                <label for="quarterly_max_score" class="form-label">Maximum Score for All Components <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('max_score') is-invalid @enderror" 
                                    id="quarterly_max_score" name="quarterly_max_score" 
                                    value="{{ old('quarterly_max_score', 100) }}" min="1" required>
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
                                                                value="{{ old('component_max_score.' . $mapehComponents[$componentKey]->id, 100) }}" 
                                                                min="1" required>
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
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right me-1"></i> Proceed to Grade Entry
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Regular Subject Layout -->
    <div class="row">
        <div class="col-md-8 mx-auto">
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
                        </div>
                        
                        <div class="mb-4">
                            <label for="max_score" class="form-label">Maximum Score/Total Items <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('max_score') is-invalid @enderror" 
                                id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="1" required>
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
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-arrow-right me-1"></i> Proceed to Grade Entry
                            </button>
                        </div>
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
    });
</script>
@endpush

@endsection 