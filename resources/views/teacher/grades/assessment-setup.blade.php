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
                                    You will set up assessments for each component separately.
                                </p>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('teacher.grades.store-assessment-setup') }}">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        <input type="hidden" name="term" value="{{ $term }}">
                        <input type="hidden" name="grade_type" value="{{ $gradeType }}">
                        <input type="hidden" name="section_id" value="{{ $section->id ?? request()->get('section_id', 1) }}">
                        <input type="hidden" name="is_mapeh" value="1">
                        
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
                                Provide a descriptive name for this assessment that will be used across all MAPEH components.
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <label class="form-label">Maximum Score for Each Component <span class="text-danger">*</span></label>
                            </div>
                            
                            @foreach($subject->components as $component)
                            <div class="col-md-3 mb-3">
                                <div class="card h-100">
                                    <div class="card-header bg-light py-2">
                                        <h6 class="mb-0">{{ $component->name }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <input type="number" class="form-control" 
                                            id="max_score_{{ $component->id }}" 
                                            name="component_max_score[{{ $component->id }}]" 
                                            value="{{ old('component_max_score.' . $component->id, 100) }}" 
                                            min="1" required>
                                        <div class="form-text small">
                                            Maximum score for {{ $component->name }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <div class="alert alert-warning">
                            <div class="d-flex">
                                <div class="me-3">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                                <div>
                                    <h6 class="alert-heading">Assessment Type: {{ $gradeTypes[$gradeType] ?? $gradeType }}</h6>
                                    <p class="mb-0">
                                        For MAPEH subjects, you will enter grades for each component separately. The final grade will be calculated as a weighted average based on the component weights.
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
@endsection 