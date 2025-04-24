@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <h2 class="mb-0"><i class="fas fa-percentage text-primary me-2"></i> Grade Calculation Settings</h2>
                <div>
                    <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id]) }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Grades
                    </a>
                </div>
            </div>
            <p class="text-muted mt-2">Configure how different assessment types contribute to the final grade for {{ $subject->name }}.</p>
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
        <div class="col-12 col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i> Grade Weightings for {{ $subject->name }}
                    </h5>
                </div>
                <div class="card-body p-3 p-md-4">
                    <form method="POST" action="{{ route('teacher.grades.store-configure') }}" id="gradeConfigForm">
                        @csrf
                        <input type="hidden" name="subject_id" value="{{ $subject->id }}">

                        <div class="alert alert-info d-flex align-items-center">
                            <i class="fas fa-info-circle me-2 fs-5"></i>
                            <div>The total of all percentages must equal 100%.</div>
                        </div>

                        <div class="mb-4">
                            <label for="written_work_percentage" class="form-label fw-medium">Written Works Percentage</label>
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control form-control-lg @error('written_work_percentage') is-invalid @enderror"
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
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle me-1 text-primary"></i> This includes quizzes, unit tests, and other written assessments.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="performance_task_percentage" class="form-label fw-medium">Performance Tasks Percentage</label>
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control form-control-lg @error('performance_task_percentage') is-invalid @enderror"
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
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle me-1 text-primary"></i> This includes projects, presentations, lab work, and other practical assessments.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="quarterly_assessment_percentage" class="form-label fw-medium">Quarterly Assessment Percentage</label>
                            <div class="input-group input-group-lg">
                                <input type="number" class="form-control form-control-lg @error('quarterly_assessment_percentage') is-invalid @enderror"
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
                            <div class="form-text mt-2">
                                <i class="fas fa-info-circle me-1 text-primary"></i> This is the final exam or assessment for the quarter.
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="card bg-light shadow-sm border-0">
                                <div class="card-body p-3 p-md-4">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="card-title mb-0 fw-bold">Grade Distribution</h6>
                                        <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 fs-6">
                                            Total: <span id="totalPercentage" class="fw-bold">{{
                                            ($subject->gradeConfiguration->written_work_percentage ?? 30) +
                                            ($subject->gradeConfiguration->performance_task_percentage ?? 50) +
                                            ($subject->gradeConfiguration->quarterly_assessment_percentage ?? 20)
                                        }}%</span>
                                        </div>
                                    </div>

                                    <div class="progress mb-3" style="height: 25px;">
                                        @php
                                            $wwWidth = $subject->gradeConfiguration->written_work_percentage ?? 30;
                                            $ptWidth = $subject->gradeConfiguration->performance_task_percentage ?? 50;
                                            $qaWidth = $subject->gradeConfiguration->quarterly_assessment_percentage ?? 20;
                                        @endphp
                                        <div class="progress-bar bg-primary" role="progressbar"
                                             aria-valuenow="{{ $wwWidth }}" aria-valuemin="0" aria-valuemax="100"
                                             style="width: {{ $wwWidth }}%">
                                            <span class="d-none d-md-inline">Written Works</span>
                                            <span class="d-inline d-md-none">WW</span>
                                        </div>
                                        <div class="progress-bar bg-success" role="progressbar"
                                             aria-valuenow="{{ $ptWidth }}" aria-valuemin="0" aria-valuemax="100"
                                             style="width: {{ $ptWidth }}%">
                                            <span class="d-none d-md-inline">Performance Tasks</span>
                                            <span class="d-inline d-md-none">PT</span>
                                        </div>
                                        <div class="progress-bar bg-warning" role="progressbar"
                                             aria-valuenow="{{ $qaWidth }}" aria-valuemin="0" aria-valuemax="100"
                                             style="width: {{ $qaWidth }}%">
                                            <span class="d-none d-md-inline">Quarterly</span>
                                            <span class="d-inline d-md-none">QA</span>
                                        </div>
                                    </div>

                                    <div class="row g-2 text-center">
                                        <div class="col-4">
                                            <div class="p-2 rounded bg-primary bg-opacity-10 text-primary">
                                                <small class="d-block">Written Works</small>
                                                <span class="fw-bold" id="written-percent-display">{{ $wwWidth }}%</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded bg-success bg-opacity-10 text-success">
                                                <small class="d-block">Performance</small>
                                                <span class="fw-bold" id="performance-percent-display">{{ $ptWidth }}%</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="p-2 rounded bg-warning bg-opacity-10 text-warning">
                                                <small class="d-block">Quarterly</small>
                                                <span class="fw-bold" id="quarterly-percent-display">{{ $qaWidth }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column flex-sm-row justify-content-between gap-3">
                            <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id]) }}" class="btn btn-outline-secondary btn-lg order-2 order-sm-1">
                                <i class="fas fa-times me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg order-1 order-sm-2">
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

        // Get the percentage display elements using IDs
        const writtenPercentDisplay = document.getElementById('written-percent-display');
        const performancePercentDisplay = document.getElementById('performance-percent-display');
        const quarterlyPercentDisplay = document.getElementById('quarterly-percent-display');

        const updateTotal = function() {
            const written = parseFloat(writtenWorkInput.value) || 0;
            const performance = parseFloat(performanceTaskInput.value) || 0;
            const quarterly = parseFloat(quarterlyAssessmentInput.value) || 0;

            const total = written + performance + quarterly;
            totalPercentage.textContent = total.toFixed(2) + '%';

            // Update the individual percentage displays
            if (writtenPercentDisplay) writtenPercentDisplay.textContent = written + '%';
            if (performancePercentDisplay) performancePercentDisplay.textContent = performance + '%';
            if (quarterlyPercentDisplay) quarterlyPercentDisplay.textContent = quarterly + '%';

            // Update progress bars - ensure correct order (Written Works, Performance Tasks, Quarterly Assessment)
            const progressBars = document.querySelectorAll('.progress-bar');

            // Written Works - blue/primary
            const writtenBar = document.querySelector('.progress-bar.bg-primary');
            writtenBar.style.width = written + '%';
            writtenBar.setAttribute('aria-valuenow', written);

            // Performance Tasks - green/success
            const performanceBar = document.querySelector('.progress-bar.bg-success');
            performanceBar.style.width = performance + '%';
            performanceBar.setAttribute('aria-valuenow', performance);

            // Quarterly Assessment - yellow/warning
            const quarterlyBar = document.querySelector('.progress-bar.bg-warning');
            quarterlyBar.style.width = quarterly + '%';
            quarterlyBar.setAttribute('aria-valuenow', quarterly);

            // Change color if not equal to 100%
            if (Math.round(total * 100) / 100 !== 100) {
                totalPercentage.classList.add('text-danger');
            } else {
                totalPercentage.classList.remove('text-danger');
            }

            // Ensure progress bars show at least 5% width for visibility on small screens
            progressBars.forEach(bar => {
                const currentWidth = parseFloat(bar.style.width);
                if (currentWidth > 0 && currentWidth < 5) {
                    bar.style.minWidth = '5%';
                } else {
                    bar.style.minWidth = '';
                }
            });
        };

        // Add touch-friendly event listeners for mobile
        const addInputListeners = (input) => {
            input.addEventListener('input', updateTotal);
            input.addEventListener('change', updateTotal);
            input.addEventListener('blur', updateTotal);
        };

        addInputListeners(writtenWorkInput);
        addInputListeners(performanceTaskInput);
        addInputListeners(quarterlyAssessmentInput);

        // Initial update
        updateTotal();

        // Validate form on submit
        document.getElementById('gradeConfigForm').addEventListener('submit', function(e) {
            // Check if the form is already being submitted
            if (this.dataset.submitting === 'true') {
                console.log('Grade config form already being submitted, preventing duplicate submission');
                e.preventDefault();
                return false;
            }

            const written = parseFloat(writtenWorkInput.value) || 0;
            const performance = parseFloat(performanceTaskInput.value) || 0;
            const quarterly = parseFloat(quarterlyAssessmentInput.value) || 0;

            const total = written + performance + quarterly;

            if (Math.round(total * 100) / 100 !== 100) {
                e.preventDefault();
                alert('The total of all percentages must equal 100%.');
                return false;
            }

            // Mark the form as being submitted and disable the submit button
            this.dataset.submitting = 'true';
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn && !submitBtn.disabled) {
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Saving...';
                submitBtn.disabled = true;
            }

            return true;
        });
    });
</script>
@endpush
@endsection