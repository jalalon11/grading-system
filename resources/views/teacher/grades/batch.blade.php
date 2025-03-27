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
                <div class="mb-2">
                    <span class="text-muted me-2">Maximum Score:</span>
                    <span class="fw-medium">{{ session('max_score') }} points</span>
                </div>
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
            <form method="POST" action="{{ route('teacher.grades.batch-store') }}" id="batchGradeForm">
                @csrf
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                <input type="hidden" name="term" value="{{ $request->term }}">
                <input type="hidden" name="grade_type" value="{{ $request->grade_type }}">
                <input type="hidden" name="max_score" value="{{ session('max_score') }}">
                <input type="hidden" name="assessment_name" value="{{ session('assessment_name') }}">
                <input type="hidden" name="section_id" value="{{ $section->id }}">
                
                @if(count($students) == 0)
                    <div class="text-center py-5">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x text-muted"></i>
                        </div>
                        <h5 class="text-muted">No Students Found</h5>
                        <p class="text-muted">There are no students assigned to this section yet.</p>
                    </div>
                @else
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
                    
                    <div class="mt-4 d-flex justify-content-between">
                        <div>
                            <button type="button" id="fillWithZeros" class="btn btn-outline-secondary btn-sm me-2">
                                <i class="fas fa-eraser me-1"></i> Fill All with Zeros
                            </button>
                            <button type="button" id="applyPerfect" class="btn btn-outline-success btn-sm">
                                <i class="fas fa-check-circle me-1"></i> Fill All with Perfect Scores
                            </button>
                        </div>
                        <div>
                            <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id, 'term' => $request->term]) }}" 
                               class="btn btn-outline-secondary me-2">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Save All Grades
                            </button>
                        </div>
                    </div>
                @endif
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
        
        // Score validation
        const scoreInputs = document.querySelectorAll('input[name="scores[]"]');
        scoreInputs.forEach(input => {
            const studentId = input.id.replace('score', '');
            const validIcon = document.getElementById('validScore' + studentId);
            const invalidIcon = document.getElementById('invalidScore' + studentId);
            const maxScore = parseInt(input.dataset.max);
            
            input.addEventListener('input', function() {
                const value = parseInt(this.value);
                
                if (value >= 0 && value <= maxScore) {
                    validIcon.classList.remove('d-none');
                    invalidIcon.classList.add('d-none');
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    validIcon.classList.add('d-none');
                    invalidIcon.classList.remove('d-none');
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
                
                if (this.value === '') {
                    validIcon.classList.add('d-none');
                    invalidIcon.classList.add('d-none');
                    input.classList.remove('is-valid');
                    input.classList.remove('is-invalid');
                }
            });
        });
        
        // Fill with zeros button
        document.getElementById('fillWithZeros').addEventListener('click', function() {
            if (confirm('Are you sure you want to fill all scores with zeros?')) {
                scoreInputs.forEach(input => {
                    input.value = '0';
                    input.dispatchEvent(new Event('input'));
                });
            }
        });
        
        // Fill with perfect scores button
        document.getElementById('applyPerfect').addEventListener('click', function() {
            if (confirm('Are you sure you want to fill all scores with perfect scores?')) {
                scoreInputs.forEach(input => {
                    const maxScore = input.dataset.max;
                    input.value = maxScore;
                    input.dispatchEvent(new Event('input'));
                });
            }
        });
        
        // Form validation
        document.getElementById('batchGradeForm').addEventListener('submit', function(e) {
            let valid = true;
            
            scoreInputs.forEach(input => {
                const value = parseInt(input.value);
                const maxScore = parseInt(input.dataset.max);
                
                if (isNaN(value) || value < 0 || value > maxScore) {
                    valid = false;
                    input.classList.add('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Please correct the invalid scores before submitting.');
            }
        });
    });
</script>
@endpush
@endsection 