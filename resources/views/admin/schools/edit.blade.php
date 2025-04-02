@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-school text-warning me-2"></i> Edit School</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Schools
                    </a>
                </div>
            </div>
            <p class="text-muted">Edit information for school: <strong>{{ $school->name }}</strong></p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> School Information</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.schools.update', $school->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">School Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $school->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="code" class="form-label fw-bold">School Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $school->code) }}" required>
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">A unique code for this school (e.g., SCH-001)</div>
                                </div>
                                <div class="col-md-12">
                                    <label for="address" class="form-label fw-bold">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $school->address) }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="principal" class="form-label fw-bold">Principal</label>
                                    <input type="text" class="form-control @error('principal') is-invalid @enderror" id="principal" name="principal" value="{{ old('principal', $school->principal) }}" placeholder="Enter principal's full name">
                                    @error('principal')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="school_division_id" class="form-label fw-bold">School Division <span class="text-danger">*</span></label>
                                    <select class="form-select @error('school_division_id') is-invalid @enderror" id="school_division_id" name="school_division_id" required>
                                        <option value="">Select School Division</option>
                                        @foreach($divisions as $division)
                                            <option value="{{ $division->id }}" {{ old('school_division_id', $school->school_division_id) == $division->id ? 'selected' : '' }}>
                                                {{ $division->name }} ({{ $division->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_division_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Grade Levels Section -->
                                <div class="col-md-12 mt-3">
                                    <label class="form-label fw-bold">Grade Levels <span class="text-danger">*</span></label>
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                @php 
                                                    $gradelevels = old('grade_levels', $school->grade_levels) ?? [];
                                                    if (!is_array($gradelevels)) {
                                                        $gradelevels = json_decode($gradelevels) ?? [];
                                                    }
                                                @endphp
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_k" name="grade_levels[]" value="K" {{ in_array('K', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_k">Kindergarten</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_1" name="grade_levels[]" value="1" {{ in_array('1', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_1">Grade 1</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_2" name="grade_levels[]" value="2" {{ in_array('2', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_2">Grade 2</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_3" name="grade_levels[]" value="3" {{ in_array('3', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_3">Grade 3</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_4" name="grade_levels[]" value="4" {{ in_array('4', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_4">Grade 4</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_5" name="grade_levels[]" value="5" {{ in_array('5', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_5">Grade 5</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_6" name="grade_levels[]" value="6" {{ in_array('6', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_6">Grade 6</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_7" name="grade_levels[]" value="7" {{ in_array('7', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_7">Grade 7</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_8" name="grade_levels[]" value="8" {{ in_array('8', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_8">Grade 8</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_9" name="grade_levels[]" value="9" {{ in_array('9', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_9">Grade 9</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_10" name="grade_levels[]" value="10" {{ in_array('10', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_10">Grade 10</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_11" name="grade_levels[]" value="11" {{ in_array('11', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_11">Grade 11</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="grade_12" name="grade_levels[]" value="12" {{ in_array('12', $gradelevels) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="grade_12">Grade 12</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary" id="selectAllGrades">Select All</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllGrades">Clear All</button>
                                            </div>
                                            @error('grade_levels')
                                                <div class="text-danger mt-2">
                                                    <small><strong>{{ $message }}</strong></small>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" role="switch" id="is_active" name="is_active" value="1" {{ $school->is_active ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            <span class="fw-bold">Active Status</span> - Enable or disable this school
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-1"></i> Update School
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chalkboard-teacher text-info me-2"></i> Teachers in this School</h5>
                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="fas fa-plus-circle me-1"></i> Add Teacher
                    </button>
                </div>
                <div class="card-body p-0">
                    @if(isset($school->teachers) && $school->teachers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($school->teachers as $teacher)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $teacher->name }}</td>
                                            <td>{{ $teacher->email }}</td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-warning">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTeacherModal{{ $teacher->id }}">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>

                                                <!-- Delete Teacher Modal -->
                                                <div class="modal fade" id="deleteTeacherModal{{ $teacher->id }}" tabindex="-1" aria-labelledby="deleteTeacherModalLabel{{ $teacher->id }}" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="deleteTeacherModalLabel{{ $teacher->id }}">Confirm Delete</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body text-start">
                                                                <p>Are you sure you want to delete the teacher <strong>{{ $teacher->name }}</strong>?</p>
                                                                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                                <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST" class="d-inline">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-graduate text-muted mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="text-muted">No teachers found</h5>
                            <p class="text-muted">This school has no teachers yet.</p>
                            <button type="button" class="btn btn-info mt-2" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                                <i class="fas fa-plus-circle me-1"></i> Add Teacher
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">Add New Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.teachers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="teacher_name" class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="teacher_name" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label for="teacher_email" class="form-label">Email Address <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="teacher_email" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label for="teacher_password" class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="teacher_password" name="password" required>
                        </div>
                        <div class="col-md-6">
                            <label for="teacher_password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" id="teacher_password_confirmation" name="password_confirmation" required>
                        </div>
                        <input type="hidden" name="school_id" value="{{ $school->id }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Add Teacher</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Grade Levels functionality
        const selectAllGradesBtn = document.getElementById('selectAllGrades');
        const clearAllGradesBtn = document.getElementById('clearAllGrades');
        const gradeCheckboxes = document.querySelectorAll('input[name="grade_levels[]"]');

        if (selectAllGradesBtn) {
            selectAllGradesBtn.addEventListener('click', function() {
                gradeCheckboxes.forEach(checkbox => {
                    checkbox.checked = true;
                });
                const gradeLevelsCard = document.querySelector('.col-md-12.mt-3 .card');
                if (gradeLevelsCard && gradeLevelsCard.classList.contains('border-danger')) {
                    gradeLevelsCard.classList.remove('border-danger');
                }
            });
        }

        if (clearAllGradesBtn) {
            clearAllGradesBtn.addEventListener('click', function() {
                gradeCheckboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            });
        }

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            // Check if at least one grade level is selected
            const checkedGrades = document.querySelectorAll('input[name="grade_levels[]"]:checked');
            if (checkedGrades.length === 0) {
                e.preventDefault();
                const gradeLevelsCard = document.querySelector('.col-md-12.mt-3 .card');
                if (gradeLevelsCard) {
                    gradeLevelsCard.classList.add('border-danger');
                }
                alert('Please select at least one grade level');
                window.scrollTo({ top: gradeLevelsCard.offsetTop - 100, behavior: 'smooth' });
            }
        });

        // Add event listeners to remove red border when a grade level is selected
        gradeCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    const gradeLevelsCard = document.querySelector('.col-md-12.mt-3 .card');
                    if (gradeLevelsCard && gradeLevelsCard.classList.contains('border-danger')) {
                        gradeLevelsCard.classList.remove('border-danger');
                    }
                }
            });
        });
    });
</script>
@endpush 