@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-book me-2"></i> Manage Subjects
                    </h4>
                    <div>
                        <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-light">
                            <i class="fas fa-plus-circle me-1"></i> Add New Subject
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

                    <!-- Dashboard Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-primary text-white h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fas fa-book fa-3x me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $subjects->count() }}</h5>
                                        <p class="mb-0">Total Subjects</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-success text-white h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-3x me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $subjects->where('is_active', true)->count() }}</h5>
                                        <p class="mb-0">Active Subjects</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-info text-white h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fas fa-chalkboard fa-3x me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $subjects->sum('sections_count') ?? 0 }}</h5>
                                        <p class="mb-0">Total Assignments</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-warning text-dark h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fas fa-school fa-3x me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $subjects->pluck('grade_level')->filter()->unique()->count() }}</h5>
                                        <p class="mb-0">Grade Levels</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Filters and Search -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">
                                <i class="fas fa-filter me-1"></i> Filters and Search
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-search"></i>
                                        </span>
                                        <input type="text" id="searchInput" class="form-control" placeholder="Search subjects...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Grade</span>
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
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Status</span>
                                        <select id="statusFilter" class="form-select">
                                            <option value="">All Status</option>
                                            <option value="Active">Active</option>
                                            <option value="Inactive">Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <button id="resetFilters" class="btn btn-secondary w-100">
                                        <i class="fas fa-sync-alt me-1"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Subjects Table -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-1"></i> Subject List
                            </h5>
                            <span class="badge bg-primary" id="subjectCount">{{ $subjects->count() }} subjects</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="subjectsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-3">Subject Name</th>
                                            <th>Code</th>
                                            <th>Grade Level</th>
                                            <th>Sections</th>
                                            <th>Status</th>
                                            <th class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($subjects as $subject)
                                            @php
                                                $isComponent = $subject->is_component ?? false;
                                                $parentSubject = $isComponent ? $subject->parentSubject : null;
                                                
                                                // Don't display component subjects directly in the list
                                                if ($isComponent) continue;
                                                
                                                // Check if this is a MAPEH subject with components
                                                $hasComponents = isset($subject->components) && $subject->components->count() > 0;
                                                $isMAPEH = $hasComponents && $subject->components->pluck('name')->filter(function($name) {
                                                    return in_array(strtolower($name), ['music', 'arts', 'physical education', 'health']) ||
                                                           in_array(strtolower(substr($name, 0, 5)), ['music', 'arts', 'physi', 'healt']);
                                                })->count() == 4;
                                            @endphp
                                            <tr data-grade="{{ $subject->grade_level }}">
                                                <td class="px-3">
                                                    <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="text-decoration-none fw-bold">
                                                        {{ $subject->name }}
                                                    </a>
                                                    @if($isMAPEH)
                                                        <span class="badge bg-info rounded-pill ms-2">MAPEH</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary rounded-pill">
                                                        {{ $subject->code ?? 'N/A' }}
                                                    </span>
                                                </td>
                                                <td>{{ $subject->grade_level ? "Grade {$subject->grade_level}" : 'All Grades' }}</td>
                                                <td>
                                                    <span class="badge rounded-pill bg-info">
                                                        {{ $subject->sections_count ?? 0 }} {{ Str::plural('section', $subject->sections_count ?? 0) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge rounded-pill {{ $subject->is_active ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('teacher-admin.subjects.edit', $subject) }}" class="btn btn-sm btn-outline-warning" title="Edit Subject">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('teacher-admin.subjects.toggle-status', $subject) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm {{ $subject->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" title="{{ $subject->is_active ? 'Deactivate' : 'Activate' }}">
                                                                <i class="fas {{ $subject->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $subject->id }}" title="Delete Subject">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </div>
                                                    
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
                                                <td colspan="6" class="text-center py-5">
                                                    <div class="d-flex flex-column align-items-center">
                                                        <i class="fas fa-book fa-3x mb-3 text-muted"></i>
                                                        <h5 class="fw-light text-muted">No subjects found</h5>
                                                        <p class="text-muted mb-3">Add subjects to the system to get started</p>
                                                        <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-primary mt-2">
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
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Search functionality
        $("#searchInput").on("keyup", function() {
            filterTable();
        });
        
        // Grade level filter
        $("#gradeLevelFilter, #statusFilter").on("change", function() {
            filterTable();
        });
        
        // Reset filters
        $("#resetFilters").on("click", function() {
            $("#searchInput").val("");
            $("#gradeLevelFilter, #statusFilter").val("");
            filterTable();
        });
        
        function filterTable() {
            const searchValue = $("#searchInput").val().toLowerCase();
            const gradeValue = $("#gradeLevelFilter").val();
            const statusValue = $("#statusFilter").val();
            
            let visibleCount = 0;
            
            $("#subjectsTable tbody tr").each(function() {
                let shouldShow = true;
                
                // Text search
                if (searchValue) {
                    shouldShow = $(this).text().toLowerCase().indexOf(searchValue) > -1;
                }
                
                // Grade filter
                if (shouldShow && gradeValue) {
                    const gradeText = $(this).find("td:nth-child(3)").text().trim();
                    shouldShow = gradeText === gradeValue;
                }
                
                // Status filter
                if (shouldShow && statusValue) {
                    const statusText = $(this).find("td:nth-child(5)").text().trim();
                    shouldShow = statusText === statusValue;
                }
                
                $(this).toggle(shouldShow);
                
                if (shouldShow) visibleCount++;
            });
            
            // Update counter
            $("#subjectCount").text(visibleCount + " subjects");
        }
        
        // Initialize tooltips
        $('[title]').tooltip();
    });
</script>
@endpush
@endsection 