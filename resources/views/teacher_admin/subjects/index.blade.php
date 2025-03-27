@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-book me-2"></i> Manage Subjects
                    </h4>
                    <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-light">
                        <i class="fas fa-plus-circle me-1"></i> Add New Subject
                    </a>
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

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="fas fa-search"></i>
                                </span>
                                <input type="text" id="searchInput" class="form-control" placeholder="Search subjects...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text bg-light">Grade Level</span>
                                <select id="gradeLevelFilter" class="form-select">
                                    <option value="">All Grade Levels</option>
                                    @php
                                        // Get grade levels from the user's school
                                        $school = Auth::user()->school;
                                        $gradeLevels = [];
                                        
                                        if ($school) {
                                            // Parse grade levels from school settings
                                            $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels : 
                                                        (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);
                                            
                                            // Sort them numerically
                                            sort($gradeLevels, SORT_NUMERIC);
                                        }
                                    @endphp
                                    
                                    @foreach($gradeLevels as $grade)
                                        <option value="Grade {{ $grade }}">Grade {{ $grade }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle" id="subjectsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Code</th>
                                    <th>Grade Level</th>
                                    <th>Sections</th>
                                    <th>Status</th>
                                    <th style="width: 180px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($subjects as $subject)
                                    <tr data-grade="{{ $subject->grade_level }}">
                                        <td>
                                            <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="text-decoration-none fw-bold">
                                                {{ $subject->name }}
                                            </a>
                                        </td>
                                        <td>{{ $subject->code ?? 'N/A' }}</td>
                                        <td>{{ $subject->grade_level ? "Grade {$subject->grade_level}" : 'All Grades' }}</td>
                                        <td>
                                            <span class="badge rounded-pill bg-info">
                                                {{ $subject->sections_count ?? 0 }} {{ Str::plural('section', $subject->sections_count ?? 0) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $subject->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="btn btn-sm btn-info me-1" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher-admin.subjects.edit', $subject) }}" class="btn btn-sm btn-primary me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('teacher-admin.subjects.toggle-status', $subject) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="btn btn-sm {{ $subject->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $subject->is_active ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas {{ $subject->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $subject->id }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $subject->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">
                                                                <i class="fas fa-exclamation-triangle me-1"></i> Delete Subject
                                                            </h5>
                                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete <strong>{{ $subject->name }}</strong>?</p>
                                                            
                                                            @if($subject->sections_count > 0)
                                                                <div class="alert alert-warning">
                                                                    <p class="mb-0">
                                                                        <i class="fas fa-exclamation-circle me-1"></i>
                                                                        Warning: This subject is assigned to {{ $subject->sections_count }} {{ Str::plural('section', $subject->sections_count) }}.
                                                                        Deleting it will remove it from all assigned sections.
                                                                    </p>
                                                                </div>
                                                            @endif
                                                            
                                                            <p class="text-danger"><strong>This action cannot be undone!</strong></p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('teacher-admin.subjects.destroy', $subject) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete Subject</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-book fa-3x mb-3 text-muted"></i>
                                                <h5 class="fw-light text-muted">No subjects found</h5>
                                                <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-primary mt-3">
                                                    <i class="fas fa-plus-circle me-1"></i> Add New Subject
                                                </a>
                                            </div>
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
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Search functionality
        $("#searchInput").on("keyup", function() {
            const value = $(this).val().toLowerCase();
            $("#subjectsTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Grade level filter
        $("#gradeLevelFilter").on("change", function() {
            const value = $(this).val();
            if (value === "") {
                $("#subjectsTable tbody tr").show();
            } else {
                $("#subjectsTable tbody tr").hide();
                $("#subjectsTable tbody tr").each(function() {
                    const gradeText = $(this).find("td:nth-child(3)").text().trim();
                    if (gradeText === value) {
                        $(this).show();
                    }
                });
            }
        });
    });
</script>
@endpush
@endsection 