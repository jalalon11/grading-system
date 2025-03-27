@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-percentage text-primary me-2"></i> Grade Calculation Settings</h2>
                <div>
                    <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Grades
                    </a>
                </div>
            </div>
            <p class="text-muted">Configure how different assessment types contribute to the final grade for {{ $subject->name }}.</p>
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

    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i> Grade Weightings for {{ $subject->name }}
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('teacher.grades.store-configure') }}" id="gradeConfigForm">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> The total of all percentages must equal 100%.
                        </div>
                        
                        <div class="mb-4">
                            <label for="written_work_percentage" class="form-label">Written Works Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('written_work_percentage') is-invalid @enderror" 
                                    id="written_work_percentage" name="written_work_percentage" 
                                    value="{{ old('written_work_percentage', $subject->gradeConfiguration->written_work_percentage ?? 30) }}"
                                    min="0" max="100" step="0.01" required>
                                <span class="input-group-text">%</span>
                                @error('written_work_percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-text">
                                This includes quizzes, unit tests, and other written assessments.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="performance_task_percentage" class="form-label">Performance Tasks Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('performance_task_percentage') is-invalid @enderror" 
                                    id="performance_task_percentage" name="performance_task_percentage" 
                                    value="{{ old('performance_task_percentage', $subject->gradeConfiguration->performance_task_percentage ?? 50) }}"
                                    min="0" max="100" step="0.01" required>
                                <span class="input-group-text">%</span>
                                @error('performance_task_percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-text">
                                This includes projects, presentations, lab work, and other practical assessments.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="quarterly_assessment_percentage" class="form-label">Quarterly Assessment Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('quarterly_assessment_percentage') is-invalid @enderror" 
                                    id="quarterly_assessment_percentage" name="quarterly_assessment_percentage" 
                                    value="{{ old('quarterly_assessment_percentage', $subject->gradeConfiguration->quarterly_assessment_percentage ?? 20) }}"
                                    min="0" max="100" step="0.01" required>
                                <span class="input-group-text">%</span>
                                @error('quarterly_assessment_percentage')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-text">
                                This is the final exam or assessment for the quarter.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">Total: <span id="totalPercentage">{{ 
                                        ($subject->gradeConfiguration->written_work_percentage ?? 30) + 
                                        ($subject->gradeConfiguration->performance_task_percentage ?? 50) + 
                                        ($subject->gradeConfiguration->quarterly_assessment_percentage ?? 20) 
                                    }}%</span></h6>
                                    <div class="progress" style="height: 20px;">
                                        @php
                                            $wwWidth = $subject->gradeConfiguration->written_work_percentage ?? 30;
                                            $ptWidth = $subject->gradeConfiguration->performance_task_percentage ?? 50;
                                            $qaWidth = $subject->gradeConfiguration->quarterly_assessment_percentage ?? 20;
                                        @endphp
                                        <div class="progress-bar bg-primary" role="progressbar" 
                                             aria-valuenow="{{ $wwWidth }}" aria-valuemin="0" aria-valuemax="100"
                                             style="width: {{ $wwWidth }}%">
                                            Written Works
                                        </div>
                                        <div class="progress-bar bg-success" role="progressbar" 
                                             aria-valuenow="{{ $ptWidth }}" aria-valuemin="0" aria-valuemax="100"
                                             style="width: {{ $ptWidth }}%">
                                            Performance Tasks
                                        </div>
                                        <div class="progress-bar bg-warning" role="progressbar" 
                                             aria-valuenow="{{ $qaWidth }}" aria-valuemin="0" aria-valuemax="100"
                                             style="width: {{ $qaWidth }}%">
                                            Quarterly
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id]) }}" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const writtenWorkInput = document.getElementById('written_work_percentage');
        const performanceTaskInput = document.getElementById('performance_task_percentage');
        const quarterlyAssessmentInput = document.getElementById('quarterly_assessment_percentage');
        const totalPercentage = document.getElementById('totalPercentage');
        
        const updateTotal = function() {
            const written = parseFloat(writtenWorkInput.value) || 0;
            const performance = parseFloat(performanceTaskInput.value) || 0;
            const quarterly = parseFloat(quarterlyAssessmentInput.value) || 0;
            
            const total = written + performance + quarterly;
            totalPercentage.textContent = total.toFixed(2) + '%';
            
            // Update progress bars
            const progressBars = document.querySelectorAll('.progress-bar');
            progressBars[0].style.width = written + '%';
            progressBars[0].setAttribute('aria-valuenow', written);
            
            progressBars[1].style.width = performance + '%';
            progressBars[1].setAttribute('aria-valuenow', performance);
            
            progressBars[2].style.width = quarterly + '%';
            progressBars[2].setAttribute('aria-valuenow', quarterly);
            
            // Change color if not equal to 100%
            if (Math.round(total * 100) / 100 !== 100) {
                totalPercentage.classList.add('text-danger');
                totalPercentage.classList.add('fw-bold');
            } else {
                totalPercentage.classList.remove('text-danger');
                totalPercentage.classList.remove('fw-bold');
            }
        };
        
        writtenWorkInput.addEventListener('input', updateTotal);
        performanceTaskInput.addEventListener('input', updateTotal);
        quarterlyAssessmentInput.addEventListener('input', updateTotal);
        
        // Validate form on submit
        document.getElementById('gradeConfigForm').addEventListener('submit', function(e) {
            const written = parseFloat(writtenWorkInput.value) || 0;
            const performance = parseFloat(performanceTaskInput.value) || 0;
            const quarterly = parseFloat(quarterlyAssessmentInput.value) || 0;
            
            const total = written + performance + quarterly;
            
            if (Math.round(total * 100) / 100 !== 100) {
                e.preventDefault();
                alert('The total of all percentages must equal 100%.');
            }
        });
    });
</script>
@endpush
@endsection 