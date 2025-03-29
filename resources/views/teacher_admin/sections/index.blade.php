@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
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

                    <!-- Dashboard Cards -->
                    <div class="row mb-4">
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-primary text-white h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fas fa-users-class fa-3x me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $sections->count() }}</h5>
                                        <p class="mb-0">Total Sections</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-success text-white h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fas fa-check-circle fa-3x me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $sections->where('is_active', true)->count() }}</h5>
                                        <p class="mb-0">Active Sections</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-info text-white h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fas fa-user-graduate fa-3x me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $sections->sum('students_count') }}</h5>
                                        <p class="mb-0">Total Students</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 mb-3">
                            <div class="card bg-warning text-dark h-100 shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <i class="fas fa-school fa-3x me-3"></i>
                                    <div>
                                        <h5 class="mb-0">{{ $sections->pluck('grade_level')->unique()->count() }}</h5>
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
                                        <input type="text" id="searchInput" class="form-control" placeholder="Search sections...">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">Grade</span>
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

                    <!-- Sections Table -->
                    <div class="card shadow-sm">
                        <div class="card-header bg-light d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-1"></i> Section List
                            </h5>
                            <span class="badge bg-primary" id="totalCount">{{ $sections->count() }} sections</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0" id="sectionsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="px-3">Section Name</th>
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
                                                <td class="px-3">
                                                    <a href="{{ route('teacher-admin.sections.show', $section) }}" class="text-decoration-none fw-bold">
                                                        {{ $section->name }}
                                                    </a>
                                                </td>
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
                                                    <span class="badge bg-info rounded-pill">
                                                        {{ $section->students_count }} {{ Str::plural('student', $section->students_count) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $section->is_active ? 'bg-success' : 'bg-danger' }} rounded-pill">
                                                        {{ $section->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <div class="btn-group">
                                                        <a href="{{ route('teacher-admin.sections.show', $section) }}" class="btn btn-sm btn-outline-primary" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                        <a href="{{ route('teacher-admin.sections.edit', $section) }}" class="btn btn-sm btn-outline-warning" title="Edit Section">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                        <form action="{{ route('teacher-admin.sections.toggle-status', $section) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm {{ $section->is_active ? 'btn-outline-danger' : 'btn-outline-success' }}" 
                                                                    title="{{ $section->is_active ? 'Deactivate' : 'Activate' }}">
                                                                <i class="fas {{ $section->is_active ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#deleteModal{{ $section->id }}" 
                                                                title="Delete Section">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </button>
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
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                <td colspan="7" class="text-center py-5">
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
        $("#gradeFilter, #statusFilter").on("change", function() {
            filterTable();
        });
        
        // Reset filters
        $("#resetFilters").on("click", function() {
            $("#searchInput").val("");
            $("#gradeFilter, #statusFilter").val("");
            filterTable();
        });
        
        function filterTable() {
            const searchValue = $("#searchInput").val().toLowerCase();
            const gradeValue = $("#gradeFilter").val();
            const statusValue = $("#statusFilter").val();
            
            let visibleCount = 0;
            
            $("#sectionsTable tbody tr").each(function() {
                let shouldShow = true;
                
                // Text search
                if (searchValue) {
                    shouldShow = $(this).text().toLowerCase().indexOf(searchValue) > -1;
                }
                
                // Grade filter
                if (shouldShow && gradeValue) {
                    const gradeText = $(this).find("td:nth-child(2)").text().trim();
                    shouldShow = gradeText === gradeValue;
                }
                
                // Status filter
                if (shouldShow && statusValue) {
                    const statusText = $(this).find("td:nth-child(6)").text().trim();
                    shouldShow = statusText === statusValue;
                }
                
                $(this).toggle(shouldShow);
                
                if (shouldShow) visibleCount++;
            });
            
            // Update counter
            $("#totalCount").text(visibleCount + " sections");
        }
        
        // Initialize tooltips
        $('[title]').tooltip();
    });
</script>
@endpush
@endsection 