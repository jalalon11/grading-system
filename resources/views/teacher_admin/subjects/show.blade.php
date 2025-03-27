@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-book me-2"></i> Subject Details: {{ $subject->name }}
                    </h4>
                    <div>
                        <a href="{{ route('teacher-admin.subjects.index') }}" class="btn btn-light me-2">
                            <i class="fas fa-arrow-left me-1"></i> Back to Subjects
                        </a>
                        <a href="{{ route('teacher-admin.subjects.edit', $subject) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit Subject
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
                                                <th style="width: 30%">Subject Name</th>
                                                <td>{{ $subject->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>Code</th>
                                                <td>{{ $subject->code ?? 'Not specified' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Grade Level</th>
                                                <td>{{ $subject->grade_level ?? 'Not specified' }}</td>
                                            </tr>
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    <span class="badge {{ $subject->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    
                                    @if($subject->description)
                                        <div class="mt-3">
                                            <h6>Description</h6>
                                            <p>{{ $subject->description }}</p>
                                        </div>
                                    @endif
                                    
                                    <div class="mt-3 d-flex">
                                        <form action="{{ route('teacher-admin.subjects.toggle-status', $subject) }}" method="POST" class="me-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn {{ $subject->is_active ? 'btn-danger' : 'btn-success' }}" title="{{ $subject->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas {{ $subject->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }} me-1"></i>
                                                {{ $subject->is_active ? 'Deactivate' : 'Activate' }} Subject
                                            </button>
                                        </form>
                                        
                                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                            <i class="fas fa-trash-alt me-1"></i> Delete Subject
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                                    <h5 class="mb-0">Assigned Sections & Teachers</h5>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignTeachersModal">
                                        <i class="fas fa-plus-circle me-1"></i> Assign Teachers
                                    </button>
                                </div>
                                <div class="card-body">
                                    @if($subject->sections->count() > 0)
                                        <table class="table table-striped table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Section</th>
                                                    <th>Grade Level</th>
                                                    <th>Teacher</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($subject->sections as $section)
                                                    <tr>
                                                        <td>{{ $section->name }}</td>
                                                        <td>{{ $section->grade_level }}</td>
                                                        <td>
                                                            @php
                                                                $teacher = App\Models\User::find($section->pivot->teacher_id ?? 0);
                                                            @endphp
                                                            {{ $teacher ? $teacher->name : 'Not assigned' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    @else
                                        <div class="alert alert-info mb-0">
                                            <i class="fas fa-info-circle me-1"></i> This subject is not assigned to any sections yet.
                                            Click the "Assign Teachers" button to add this subject to sections.
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

<!-- Assign Teachers Modal -->
<div class="modal fade" id="assignTeachersModal" tabindex="-1" aria-labelledby="assignTeachersModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="assignTeachersModalLabel">
                    <i class="fas fa-chalkboard-teacher me-1"></i> Assign Teachers to {{ $subject->name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('teacher-admin.subjects.assign-teachers', $subject) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="mb-3">Assign this subject to sections and select teachers for each section.</p>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered" id="sectionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 40%">Section</th>
                                    <th style="width: 50%">Teacher</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="section-row">
                                    <td>
                                        <select class="form-select section-select" name="sections[0][section_id]" required>
                                            <option value="" selected disabled>Select Section</option>
                                            @foreach($subject->school->sections ?? [] as $section)
                                                <option value="{{ $section->id }}">{{ $section->name }} ({{ $section->grade_level }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <select class="form-select" name="sections[0][teacher_id]" required>
                                            <option value="" selected disabled>Select Teacher</option>
                                            @foreach($teachers as $teacher)
                                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-danger btn-sm remove-section" disabled>
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <button type="button" class="btn btn-secondary btn-sm mt-2" id="addSectionRow">
                        <i class="fas fa-plus-circle me-1"></i> Add Another Section
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

<!-- Delete Subject Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-1"></i> Delete Subject
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the subject <strong>{{ $subject->name }}</strong>?</p>
                
                @if($subject->sections->count() > 0)
                    <div class="alert alert-warning">
                        <p class="mb-0">
                            <i class="fas fa-exclamation-circle me-1"></i>
                            Warning: This subject is assigned to {{ $subject->sections->count() }} {{ Str::plural('section', $subject->sections->count()) }}.
                            Deleting it will remove it from all assigned sections.
                        </p>
                    </div>
                @endif
                
                <p class="text-danger"><strong>Warning:</strong> This action cannot be undone!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('teacher-admin.subjects.destroy', $subject) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt me-1"></i> Delete Subject
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
        
        // Add new section row
        $('#addSectionRow').click(function() {
            let newRow = `
                <tr class="section-row">
                    <td>
                        <select class="form-select section-select" name="sections[${rowCount}][section_id]" required>
                            <option value="" selected disabled>Select Section</option>
                            @foreach($subject->school->sections ?? [] as $section)
                                <option value="{{ $section->id }}">{{ $section->name }} ({{ $section->grade_level }})</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select class="form-select" name="sections[${rowCount}][teacher_id]" required>
                            <option value="" selected disabled>Select Teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="text-center">
                        <button type="button" class="btn btn-danger btn-sm remove-section">
                            <i class="fas fa-times"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#sectionsTable tbody').append(newRow);
            rowCount++;
            
            // Enable the first row's remove button if we have more than one row
            if ($('.section-row').length > 1) {
                $('.remove-section').prop('disabled', false);
            }
        });
        
        // Remove section row
        $(document).on('click', '.remove-section', function() {
            $(this).closest('tr').remove();
            
            // If only one row left, disable its remove button
            if ($('.section-row').length == 1) {
                $('.remove-section').prop('disabled', true);
            }
        });
    });
</script>
@endpush
@endsection 