@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-door-open me-2"></i> Section Details: {{ $section->name }}
                    </h4>
                    <div>
                        <a href="{{ route('teacher-admin.sections.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to Sections
                        </a>
                        <a href="{{ route('teacher-admin.sections.edit', $section) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Section
                        </a>
                    </div>
                </div>
                <div class="card-body">
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
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Basic Information</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-bordered">
                                        <tbody>
                                            <tr>
                                                <th style="width: 30%">Section Name</th>
                                                <td>{{ $section->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Grade Level</th>
                                                <td>{{ $section->grade_level }}</td>
                                            </tr>
                                            <tr>
                                                <th>School Year</th>
                                                <td>{{ $section->school_year }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge {{ $section->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $section->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Adviser</th>
                                                <td>{{ $section->adviser->name ?? 'Not assigned' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Students</th>
                                                <td>
                                                    <span class="badge bg-info">
                                                        {{ $section->students_count }} {{ Str::plural('student', $section->students_count) }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    <div class="mt-3 d-flex">
                                        <form action="{{ route('teacher-admin.sections.toggle-status', $section) }}" method="POST" class="me-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn {{ $section->is_active ? 'btn-danger' : 'btn-success' }}" title="{{ $section->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas {{ $section->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }} me-1"></i>
                                                {{ $section->is_active ? 'Deactivate' : 'Activate' }} Section
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash-alt me-1"></i> Delete Section
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Assigned Subjects</h5>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignSubjectsModal">
                                        <i class="fas fa-plus-circle me-1"></i> Assign Subjects
                                    </button>
                                </div>
                                <div class="card-body">
                                    @if($section->subjects->count() > 0)
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>Teacher</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($section->subjects as $subject)
                                                    <tr>
                                                        <td>{{ $subject->name }}</td>
                                                        <td>
                                                            @php
                                                                $teacher = App\Models\User::find($subject->pivot->teacher_id ?? 0);
                                                            @endphp
                                                            {{ $teacher ? $teacher->name : 'Not assigned' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-1"></i> No subjects assigned to this section yet.
                                            Click the "Assign Subjects" button to add subjects.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Assign Subjects Modal -->
<div class="modal fade" id="assignSubjectsModal" tabindex="-1" aria-labelledby="assignSubjectsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="assignSubjectsModalLabel">
                    <i class="fas fa-book me-1"></i> Assign Subjects to {{ $section->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('teacher-admin.sections.assign-subjects', $section) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Select subjects and assign teachers to this section.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="subjectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Subject</th>
                                    <th style="width: 50%">Teacher</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="subject-row">
                                    <td>
                                        <select class="form-select subject-select" name="subjects[0][subject_id]" required>
                                            <option value="" selected disabled>Select Subject</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select" name="subjects[0][teacher_id]" required>
                                            <option value="" selected disabled>Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-subject" disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="addSubjectRow">
                        <i class="fas fa-plus-circle me-1"></i> Add Another Subject
                    </button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i> Save Assignments
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Section Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-1"></i> Delete Section
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the section <strong>{{ $section->name }}</strong>?</p>
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('teacher-admin.sections.destroy', $section) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete Section
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        let rowCount = 1;
        
        // Add new subject row
        $('#addSubjectRow').click(function() {
            let newRow = `
                <tr class="subject-row">
                    <td>
                        <select class="form-select subject-select" name="subjects[${rowCount}][subject_id]" required>
                            <option value="" selected disabled>Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-select" name="subjects[${rowCount}][teacher_id]" required>
                            <option value="" selected disabled>Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-subject">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#subjectsTable tbody').append(newRow);
            rowCount++;
            
            // Enable the first row's remove button if we have more than one row
            if ($('.subject-row').length > 1) {
                $('.remove-subject').prop('disabled', false);
            }
        });
        
        // Remove subject row
        $(document).on('click', '.remove-subject', function() {
            $(this).closest('tr').remove();
            
            // If only one row left, disable its remove button
            if ($('.subject-row').length == 1) {
                $('.remove-subject').prop('disabled', true);
            }
        });
    });
</script>
@endpush
@endsection 