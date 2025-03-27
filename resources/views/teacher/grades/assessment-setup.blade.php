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
                            <span class="badge bg-primary">{{ $gradeTypes[$gradeType] ?? $gradeType }}</span>
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
</div>
@endsection 