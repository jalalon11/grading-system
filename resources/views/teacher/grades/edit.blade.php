@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit mr-2 text-primary"></i> Edit Grade
            </h1>
            <p class="text-muted">Update grade for {{ $grade->student->first_name }} {{ $grade->student->last_name }}</p>
        </div>
        <div>
            <a href="{{ route('teacher.grades.index', ['subject_id' => $grade->subject_id, 'term' => $grade->term]) }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Grades
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

    <!-- Edit Grade Card -->
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm rounded-3 border-0 mb-4">
                <div class="card-header py-3 bg-white">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-graduation-cap me-2"></i> Grade Details
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.grades.update', $grade->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row mb-3">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Student</label>
                                <input type="text" class="form-control bg-light" value="{{ $grade->student->first_name }} {{ $grade->student->last_name }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Subject</label>
                                <input type="text" class="form-control bg-light" value="{{ $subject->name }} ({{ $subject->code }})" readonly>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Term</label>
                                <input type="text" class="form-control bg-light" value="{{ $terms[$grade->term] ?? $grade->term }}" readonly>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <label class="form-label fw-bold">Assessment Type</label>
                                <input type="text" class="form-control bg-light" value="{{ $gradeTypes[$grade->grade_type] ?? $grade->grade_type }}" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label fw-bold">Assessment Name</label>
                                <input type="text" class="form-control bg-light" value="{{ $grade->assessment_name }}" readonly>
                            </div>
                        </div>
                        
                        <hr class="my-4">
                        
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label for="score" class="form-label fw-bold required">Score</label>
                                <input type="number" class="form-control @error('score') is-invalid @enderror" 
                                       id="score" name="score" value="{{ old('score', $grade->score) }}" step="0.01" min="0" required>
                                @error('score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter the points earned by the student</div>
                            </div>
                            <div class="col-md-6">
                                <label for="max_score" class="form-label fw-bold required">Maximum Score</label>
                                <input type="number" class="form-control @error('max_score') is-invalid @enderror" 
                                       id="max_score" name="max_score" value="{{ old('max_score', $grade->max_score) }}" step="0.01" min="1" required>
                                @error('max_score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Enter the maximum possible points for this assessment</div>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label for="remarks" class="form-label fw-bold">Remarks</label>
                            <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                      id="remarks" name="remarks" rows="3">{{ old('remarks', $grade->remarks) }}</textarea>
                            @error('remarks')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Optional comments or feedback for the student</div>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('teacher.grades.index', ['subject_id' => $grade->subject_id, 'term' => $grade->term]) }}" class="btn btn-light">
                                Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Update Grade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Current Grade Summary -->
            <div class="card shadow-sm rounded-3 border-0">
                <div class="card-header py-3 bg-white">
                    <h5 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i> Grade Summary
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Current Performance</h6>
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-3 text-center" style="width: 60px">
                                            <div class="display-6 fw-bold text-primary">
                                                {{ number_format(($grade->score / $grade->max_score) * 100, 1) }}%
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="progress" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar" 
                                                     style="width: {{ ($grade->score / $grade->max_score) * 100 }}%" 
                                                     aria-valuenow="{{ ($grade->score / $grade->max_score) * 100 }}" 
                                                     aria-valuemin="0" aria-valuemax="100"></div>
                                            </div>
                                            <div class="d-flex justify-content-between mt-1">
                                                <small class="text-muted">{{ $grade->score }} points</small>
                                                <small class="text-muted">out of {{ $grade->max_score }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-0 bg-light h-100">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Assessment Information</h6>
                                    <ul class="list-group list-group-flush bg-transparent">
                                        <li class="d-flex justify-content-between align-items-center px-0 py-1 border-bottom">
                                            <span class="text-muted">Date Created:</span>
                                            <span class="fw-medium">{{ $grade->created_at->format('M d, Y') }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center px-0 py-1 border-bottom">
                                            <span class="text-muted">Last Updated:</span>
                                            <span class="fw-medium">{{ $grade->updated_at->format('M d, Y h:i A') }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center px-0 py-1 border-bottom">
                                            <span class="text-muted">Assessment:</span>
                                            <span class="fw-medium">{{ $grade->assessment_name }}</span>
                                        </li>
                                        <li class="d-flex justify-content-between align-items-center px-0 py-1">
                                            <span class="text-muted">Term:</span>
                                            <span class="fw-medium">{{ $terms[$grade->term] }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .required:after {
        content: " *";
        color: #dc3545;
    }
</style>
@endpush 