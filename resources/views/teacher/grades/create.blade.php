@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-star text-warning me-2"></i> Record Individual Grade</h2>
                <a href="{{ route('teacher.grades.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to Grades
                </a>
            </div>
            <p class="text-muted">Add a new grade for an individual student.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit me-2"></i> Grade Entry Form
                    </h5>
                </div>
                <div class="card-body">
                    @if(empty($sections) || $sections->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> You don't have any assigned sections or subjects yet.
                        </div>
                        <div class="d-grid gap-2 col-md-6 mx-auto mt-3">
                            <a href="{{ route('teacher.dashboard') }}" class="btn btn-primary">
                                <i class="fas fa-tachometer-alt me-1"></i> Return to Dashboard
                            </a>
                        </div>
                    @else
                        <form action="{{ route('teacher.grades.store') }}" method="POST">
                            @csrf
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                    <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                        <option value="">Select Section</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ (old('section_id', $sectionId) == $section->id) ? 'selected' : '' }}>
                                                {{ $section->name }} ({{ $section->grade_level ?? 'Not Set' }}) - {{ $section->school_year }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('section_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Select the section to display the subjects and students</div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="subject_id" class="form-label">Subject <span class="text-danger">*</span></label>
                                    <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
                                        <option value="">Select Subject</option>
                                        @if(!empty($subjects))
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }} {{ $subject->code ? "({$subject->code})" : '' }}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('subject_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="student_id" class="form-label">Student <span class="text-danger">*</span></label>
                                    <select class="form-select @error('student_id') is-invalid @enderror" id="student_id" name="student_id" required>
                                        <option value="">Select Student</option>
                                        @if(!empty($students))
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                                    {{ $student->first_name }} {{ $student->last_name }} ({{ $student->student_id }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    @error('student_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="term" class="form-label">Term <span class="text-danger">*</span></label>
                                    <select class="form-select @error('term') is-invalid @enderror" id="term" name="term" required>
                                        <option value="">Select Term</option>
                                        @foreach($terms as $key => $term)
                                            <option value="{{ $key }}" {{ old('term') == $key ? 'selected' : '' }}>{{ $term }}</option>
                                        @endforeach
                                    </select>
                                    @error('term')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="grade_type" class="form-label">Grade Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('grade_type') is-invalid @enderror" id="grade_type" name="grade_type" required>
                                        <option value="">Select Grade Type</option>
                                        @foreach($gradeTypes as $key => $type)
                                            <option value="{{ $key }}" {{ old('grade_type') == $key ? 'selected' : '' }}>{{ $type }}</option>
                                        @endforeach
                                    </select>
                                    @error('grade_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="assessment_name" class="form-label">Assessment Name</label>
                                    <input type="text" class="form-control @error('assessment_name') is-invalid @enderror" id="assessment_name" name="assessment_name" value="{{ old('assessment_name') }}">
                                    @error('assessment_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional name for this assessment (e.g. "Quiz 1" or "Midterm Exam")</div>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3">
                                    <label for="score" class="form-label">Score <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('score') is-invalid @enderror" id="score" name="score" value="{{ old('score') }}" min="0" required>
                                    @error('score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="max_score" class="form-label">Maximum Score <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('max_score') is-invalid @enderror" id="max_score" name="max_score" value="{{ old('max_score', 100) }}" min="1" required>
                                    @error('max_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="remarks" class="form-label">Remarks</label>
                                <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="2">{{ old('remarks') }}</textarea>
                                @error('remarks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Optional comments about this grade</div>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <a href="{{ route('teacher.grades.index') }}" class="btn btn-outline-secondary me-md-2">Cancel</a>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save me-1"></i> Save Grade
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle section change to populate subjects and students
        const sectionSelect = document.getElementById('section_id');
        
        if (sectionSelect) {
            sectionSelect.addEventListener('change', function() {
                const sectionId = this.value;
                
                if (sectionId) {
                    // Redirect to the create page with the section_id parameter
                    window.location.href = "{{ route('teacher.grades.create') }}?section_id=" + sectionId;
                }
            });
        }
    });
</script>
@endsection
