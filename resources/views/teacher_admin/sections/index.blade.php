@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-users-class me-2"></i> Manage Sections
                    </h4>
                    <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-light">
                        <i class="fas fa-plus-circle me-1"></i> Add New Section
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
                        <div class="col-md-4">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Search sections...">
                                <span class="input-group-text">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select id="gradeFilter" class="form-select">
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

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle" id="sectionsTable">
                            <thead class="table-light">
                                <tr>
                                    <th>Section Name</th>
                                    <th>Grade Level</th>
                                    <th>Adviser</th>
                                    <th>School Year</th>
                                    <th>Students</th>
                                    <th>Status</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($sections as $section)
                                    <tr>
                                        <td>{{ $section->name }}</td>
                                        <td>{{ $section->grade_level }}</td>
                                        <td>
                                            @if($section->adviser)
                                                {{ $section->adviser->name }}
                                            @else
                                                <span class="text-muted">Not assigned</span>
                                            @endif
                                        </td>
                                        <td>{{ $section->school_year }}</td>
                                        <td>
                                            <span class="badge bg-info">
                                                {{ $section->students_count }} {{ Str::plural('student', $section->students_count) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $section->is_active ? 'bg-success' : 'bg-danger' }}">
                                                {{ $section->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('teacher-admin.sections.show', $section) }}" class="btn btn-sm btn-primary" title="View">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('teacher-admin.sections.edit', $section) }}" class="btn btn-sm btn-warning" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-sm btn-danger" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#deleteModal{{ $section->id }}" 
                                                        title="Delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                                <form action="{{ route('teacher-admin.sections.toggle-status', $section) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm {{ $section->is_active ? 'btn-secondary' : 'btn-success' }}" 
                                                            title="{{ $section->is_active ? 'Deactivate' : 'Activate' }}">
                                                        <i class="fas {{ $section->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal{{ $section->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-danger text-white">
                                                            <h5 class="modal-title">
                                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                                Confirm Delete
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Are you sure you want to delete the section <strong>{{ $section->name }}</strong>?</p>
                                                            <p class="text-danger mb-0">
                                                                <i class="fas fa-info-circle me-1"></i>
                                                                This action cannot be undone.
                                                            </p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('teacher-admin.sections.destroy', $section) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    Delete Section
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-4">
                                            <div class="d-flex flex-column align-items-center">
                                                <i class="fas fa-folder-open text-muted mb-3" style="font-size: 3rem;"></i>
                                                <h5 class="text-muted">No sections found</h5>
                                                <p class="text-muted mb-3">Create a section to get started</p>
                                                <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-primary">
                                                    <i class="fas fa-plus-circle me-1"></i> Add New Section
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
            $("#sectionsTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
        
        // Grade level filter
        $("#gradeFilter").on("change", function() {
            const value = $(this).val();
            if (value === "") {
                $("#sectionsTable tbody tr").show();
            } else {
                $("#sectionsTable tbody tr").hide();
                $("#sectionsTable tbody tr").each(function() {
                    const gradeText = $(this).find("td:nth-child(2)").text().trim();
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