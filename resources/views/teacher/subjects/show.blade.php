@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="m-0 fw-bold text-dark">
                <i class="fas fa-book text-primary me-2"></i> {{ $subject->name }}
            </h2>
            <p class="text-muted mt-2 mb-0">Subject details and configuration</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('teacher.subjects.edit', $subject->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit me-1"></i> Edit Subject
            </a>
            <a href="{{ route('teacher.subjects.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Subjects
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <!-- Subject Information Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i> Subject Information
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <span class="text-muted">Subject Code</span>
                        <p class="mb-0 fw-medium fs-5">{{ $subject->code }}</p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <span class="text-muted">Grade Level</span>
                        <p class="mb-0 fw-medium fs-5">Grade {{ $subject->grade_level }}</p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <span class="text-muted">Section</span>
                        <p class="mb-0 fw-medium fs-5">{{ $subject->section->name ?? 'Not assigned' }}</p>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted">Description</span>
                        <p class="mb-0">{{ $subject->description ?: 'No description provided' }}</p>
                    </div>
                </div>
                <div class="card-footer bg-white py-3 text-center">
                    <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id]) }}" class="btn btn-primary">
                        <i class="fas fa-calculator me-1"></i> View Grades
                    </a>
                </div>
            </div>
        </div>

        <!-- Assessment Configuration Card -->
        <div class="col-md-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-percentage text-success me-2"></i> Assessment Configuration
                    </h5>
                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editAssessmentModal">
                        <i class="fas fa-edit me-1"></i> Edit Settings
                    </button>
                </div>
                <div class="card-body">
                    @php
                        // Get or create grade configuration
                        $gradeConfig = $subject->gradeConfiguration ?? new \App\Models\GradeConfiguration([
                            'written_work_percentage' => 25,
                            'performance_task_percentage' => 50,
                            'quarterly_assessment_percentage' => 25
                        ]);
                        
                        $writtenWorkPercentage = $gradeConfig->written_work_percentage ?? 25;
                        $performanceTaskPercentage = $gradeConfig->performance_task_percentage ?? 50;
                        $quarterlyAssessmentPercentage = $gradeConfig->quarterly_assessment_percentage ?? 25;
                    @endphp

                    <div class="row">
                        <div class="col-md-8">
                            <div class="progress mb-4" style="height: 30px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $writtenWorkPercentage }}%" 
                                    aria-valuenow="{{ $writtenWorkPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    Written Works {{ $writtenWorkPercentage }}%
                                </div>
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $performanceTaskPercentage }}%" 
                                    aria-valuenow="{{ $performanceTaskPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    Performance {{ $performanceTaskPercentage }}%
                                </div>
                                <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $quarterlyAssessmentPercentage }}%" 
                                    aria-valuenow="{{ $quarterlyAssessmentPercentage }}" aria-valuemin="0" aria-valuemax="100">
                                    Exams {{ $quarterlyAssessmentPercentage }}%
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <div class="alert alert-info">
                                    <h6 class="fw-bold"><i class="fas fa-lightbulb me-2"></i> How Grades Are Calculated</h6>
                                    <p class="mb-0 small">
                                        Final grades are calculated by combining scores from all three assessment types according to 
                                        their weight percentages. The total must add up to 100%.
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card mb-3 border-primary border-start border-4">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-1">Written Works</h6>
                                    <p class="mb-0 small">Quizzes, homework, and other written assessments</p>
                                    <div class="text-end">
                                        <span class="badge bg-primary">{{ $writtenWorkPercentage }}%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-3 border-success border-start border-4">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-1">Performance Tasks</h6>
                                    <p class="mb-0 small">Projects, presentations, and practical work</p>
                                    <div class="text-end">
                                        <span class="badge bg-success">{{ $performanceTaskPercentage }}%</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card mb-0 border-warning border-start border-4">
                                <div class="card-body p-3">
                                    <h6 class="fw-bold mb-1">Quarterly Assessment</h6>
                                    <p class="mb-0 small">Final exams and quarterly assessments</p>
                                    <div class="text-end">
                                        <span class="badge bg-warning">{{ $quarterlyAssessmentPercentage }}%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Students Card -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="card-title fw-bold mb-0">
                        <i class="fas fa-user-graduate text-primary me-2"></i> Students Enrolled
                    </h5>
                    <a href="{{ route('teacher.students.create', ['section_id' => $subject->section_id]) }}" class="btn btn-sm btn-success">
                        <i class="fas fa-user-plus me-1"></i> Add Student
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle border-0 mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4">#</th>
                                    <th>Student Name</th>
                                    <th>Student ID</th>
                                    <th>Gender</th>
                                    <th>Section</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($subject->section->students ?? [] as $index => $student)
                                    <tr>
                                        <td class="ps-4">{{ $index + 1 }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-secondary bg-opacity-25 me-2 d-flex align-items-center justify-content-center rounded-circle" style="width: 40px; height: 40px;">
                                                    <span class="fw-bold">{{ strtoupper(substr($student->first_name, 0, 1)) }}{{ strtoupper(substr($student->last_name, 0, 1)) }}</span>
                                                </div>
                                                <div>
                                                    <p class="fw-bold mb-0">{{ $student->first_name }} {{ $student->last_name }}</p>
                                                    <small class="text-muted">{{ $student->middle_name ? strtoupper(substr($student->middle_name, 0, 1)) . '. ' : '' }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $student->student_id }}</td>
                                        <td>{{ $student->gender }}</td>
                                        <td>{{ $student->section->name ?? 'No Section' }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-sm btn-info me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.grades.index', ['subject_id' => $subject->id, 'student_id' => $student->id]) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-book"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <img src="https://cdn-icons-png.flaticon.com/512/1162/1162431.png" alt="No students" style="width: 70px; opacity: 0.5;" class="mb-3">
                                            <p class="text-muted">No students enrolled in this subject yet.</p>
                                            <a href="{{ route('teacher.students.create', ['section_id' => $subject->section_id]) }}" class="btn btn-primary">
                                                <i class="fas fa-user-plus me-1"></i> Add Student
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Assessment Modal -->
    <div class="modal fade" id="editAssessmentModal" tabindex="-1" aria-labelledby="editAssessmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('teacher.grade-configurations.update', $subject->id) }}" id="gradeConfigForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAssessmentModalLabel">Edit Assessment Weights</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle me-2"></i> All percentages must add up to 100%.
                        </div>
                        
                        <div class="mb-3">
                            <label for="written_work_percentage" class="form-label">Written Works Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="written_work_percentage" name="written_work_percentage" 
                                    value="{{ $writtenWorkPercentage }}" min="0" max="100" step="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Quizzes, unit tests, and other written assessments</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="performance_task_percentage" class="form-label">Performance Tasks Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="performance_task_percentage" name="performance_task_percentage" 
                                    value="{{ $performanceTaskPercentage }}" min="0" max="100" step="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Projects, presentations, and practical assessments</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="quarterly_assessment_percentage" class="form-label">Quarterly Assessment Percentage</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="quarterly_assessment_percentage" name="quarterly_assessment_percentage" 
                                    value="{{ $quarterlyAssessmentPercentage }}" min="0" max="100" step="0.01" required>
                                <span class="input-group-text">%</span>
                            </div>
                            <div class="form-text">Final exams and quarterly assessments</div>
                        </div>
                        
                        <div class="card bg-light">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h6 class="card-title mb-0">Total:</h6>
                                    <span id="totalPercentage" class="fw-bold">{{ $writtenWorkPercentage + $performanceTaskPercentage + $quarterlyAssessmentPercentage }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
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
            
            // Change color if not equal to 100%
            if (Math.round(total * 100) / 100 !== 100) {
                totalPercentage.classList.add('text-danger');
            } else {
                totalPercentage.classList.remove('text-danger');
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
<style>
    .avatar-circle {
        font-size: 14px;
    }
</style>
@endpush
@endsection 