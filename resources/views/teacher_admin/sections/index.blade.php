@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <!-- Main Header -->
    <div class="d-flex justify-content-between align-items-center p-3 mb-4 bg-primary rounded-3 text-white shadow-sm">
        <h4 class="mb-0">
            <i class="fas fa-door-open me-2"></i> Manage Sections
        </h4>
        <button class="btn btn-light" onclick="window.location.href='{{ route('teacher-admin.sections.create') }}'">
            <i class="fas fa-plus me-2"></i> Add New Section
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Sections -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle p-3 bg-primary bg-opacity-10">
                                <i class="fas fa-door-open fa-lg text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total Sections</p>
                            <h4 class="mb-0 fw-bold">{{ $sections->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Sections -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle p-3 bg-success bg-opacity-10">
                                <i class="fas fa-check-circle fa-lg text-success"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Active Sections</p>
                            <h4 class="mb-0 fw-bold">{{ $sections->where('is_active', true)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Students -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle p-3 bg-info bg-opacity-10">
                                <i class="fas fa-user-graduate fa-lg text-info"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total Students</p>
                            <h4 class="mb-0 fw-bold">{{ $sections->sum('students_count') }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Levels -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle p-3 bg-warning bg-opacity-10">
                                <i class="fas fa-graduation-cap fa-lg text-warning"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Grade Levels</p>
                            <h4 class="mb-0 fw-bold">{{ $sections->pluck('grade_level')->unique()->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-0">
            <div class="p-3 bg-primary text-white d-flex align-items-center">
                <i class="fas fa-filter me-2"></i>
                <span class="fw-medium">Filters and Search</span>
            </div>
            
            <div class="p-3">
                <div class="row g-3">
                    <div class="col-md-5">
                        <div class="search-box">
                            <div class="position-relative">
                                <input type="text" 
                                    id="searchInput" 
                                    class="form-control form-control-lg ps-4 border" 
                                    placeholder="Search sections..."
                                    style="padding-left: 40px !important; background-color: #f8f9fa; border-color: #dee2e6;">
                                <i class="fas fa-search position-absolute text-muted" 
                                   style="left: 14px; top: 50%; transform: translateY(-50%);"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <div class="filter-box">
                            <select id="gradeFilter" 
                                class="form-select form-select-lg border" 
                                style="background-color: #f8f9fa; border-color: #dee2e6;">
                                <option value="">All Grade Levels</option>
                                @php
                                    $school = Auth::user()->school;
                                    $gradeLevels = [];
                                    if ($school) {
                                        $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels : 
                                                    (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);
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
                        <div class="filter-box">
                            <select id="statusFilter" 
                                class="form-select form-select-lg border" 
                                style="background-color: #f8f9fa; border-color: #dee2e6;">
                                <option value="">All Status</option>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="col-md-1">
                        <button id="resetFilters" 
                            class="btn btn-outline-secondary btn-lg w-100 d-flex align-items-center justify-content-center" 
                            style="border-color: #dee2e6;">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add this CSS to your stylesheet or in a style tag -->
    <style>
        .search-box .form-control:focus,
        .filter-box .form-select:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        }
        
        .search-box .form-control,
        .filter-box .form-select,
        #resetFilters {
            height: 48px;
            transition: all 0.2s ease-in-out;
        }
        
        .search-box .form-control:hover,
        .filter-box .form-select:hover,
        #resetFilters:hover {
            border-color: #0d6efd;
        }
        
        #resetFilters {
            padding: 0;
        }
        
        #resetFilters:hover {
            background-color: #f8f9fa;
            color: #0d6efd;
        }
        
        .filter-box .form-select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='m2 5 6 6 6-6'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }

        .card .rounded-circle {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card .fa-lg {
            font-size: 1.2rem;
        }

        /* Enhanced table styles for better readability */
        .table {
            font-size: 1rem;
        }

        .table > :not(caption) > * > * {
            padding: 1.25rem 0.75rem;
            background-color: transparent;
        }

        .table thead th {
            font-size: 1.05rem;
            font-weight: 600;
            color: #2c3e50;
        }

        .table tbody td {
            color: #2c3e50;
            font-weight: 500;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table .text-muted {
            color: #495057 !important;
        }

        .btn-group .btn {
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
        }

        .btn-group .btn:hover {
            background-color: #e9ecef;
        }

        .badge {
            font-size: 0.9rem;
            font-weight: 500;
            padding: 0.5rem 0.75rem;
        }

        /* Enhanced link styles */
        .table a.text-decoration-none {
            color: #0d6efd !important;
            font-weight: 500;
        }

        .table a.text-decoration-none:hover {
            text-decoration: underline !important;
        }

        /* Enhanced icon styles */
        .table .fas {
            font-size: 1.1rem;
        }

        /* Status badge enhancements */
        .badge.bg-success {
            background-color: #198754 !important;
            color: white !important;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
        }

        .badge.bg-primary {
            color: #0d6efd !important;
        }

        /* Modal text enhancements */
        .modal-title {
            font-size: 1.25rem;
        }

        .modal-body {
            font-size: 1rem;
        }
    </style>

    <!-- Section List -->
    <div class="card border-0 shadow-sm">
        <div class="d-flex justify-content-between align-items-center p-3 bg-primary text-white">
            <div class="d-flex align-items-center">
                <i class="fas fa-list text-white me-2"></i>
                <span class="fw-medium">Section List</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="sectionsTable">
                    <thead>
                        <tr class="bg-light">
                            <th class="fw-semibold border-0 py-3 ps-4" style="width: 20%">Section Name</th>
                            <th class="fw-semibold border-0 py-3" style="width: 15%">Grade Level</th>
                            <th class="fw-semibold border-0 py-3" style="width: 20%">Adviser</th>
                            <th class="fw-semibold border-0 py-3" style="width: 15%">School Year</th>
                            <th class="fw-semibold border-0 py-3" style="width: 10%">Students</th>
                            <th class="fw-semibold border-0 py-3" style="width: 10%">Status</th>
                            <th class="fw-semibold border-0 py-3 text-end pe-4" style="width: 10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($sections as $section)
                            <tr>
                                <td class="py-3 ps-4">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-door-open text-primary me-2"></i>
                                        <a href="{{ route('teacher-admin.sections.show', $section) }}" 
                                           class="text-decoration-none fw-medium">
                                            {{ $section->name }}
                                        </a>
                                    </div>
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-primary" style="color: white !important;">
                                        {{ $section->grade_level }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    @if($section->adviser)
                                        <div class="d-flex align-items-center">
                                            <div class="rounded-circle bg-secondary bg-opacity-10 p-2 me-2">
                                                <i class="fas fa-user-tie text-secondary"></i>
                                            </div>
                                            <span class="fw-medium">{{ $section->adviser->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-muted fw-medium">Not assigned</span>
                                    @endif
                                </td>
                                <td class="py-3">
                                    <span class="badge bg-secondary text-white">
                                        {{ $section->school_year }}
                                    </span>
                                </td>
                                <td class="py-3">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-users text-primary me-2"></i>
                                        <span class="fw-medium">{{ $section->students_count }}</span>
                                    </div>
                                </td>
                                <td class="py-3">
                                    @if($section->is_active)
                                        <span class="badge bg-success text-white">
                                            <i class="fas fa-check-circle me-1"></i> Active
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white">
                                            <i class="fas fa-times-circle me-1"></i> Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('teacher-admin.sections.show', $section) }}" 
                                           class="btn btn-sm btn-light" 
                                           data-bs-toggle="tooltip" 
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teacher-admin.sections.edit', $section) }}" 
                                           class="btn btn-sm btn-light" 
                                           data-bs-toggle="tooltip" 
                                           title="Edit Section">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('teacher-admin.sections.toggle-status', $section) }}" 
                                              method="POST" 
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" 
                                                    class="btn btn-sm btn-light" 
                                                    data-bs-toggle="tooltip" 
                                                    title="{{ $section->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas {{ $section->is_active ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted' }}"></i>
                                            </button>
                                        </form>
                                        <button type="button" 
                                                class="btn btn-sm btn-light" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteModal{{ $section->id }}" 
                                                title="Delete Section">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $section->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header border-0">
                                                    <h5 class="modal-title text-danger">
                                                        <i class="fas fa-exclamation-triangle me-2"></i>
                                                        Delete Section
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete the section <strong>{{ $section->name }}</strong>?</p>
                                                    <p class="text-danger mb-0">
                                                        <small><i class="fas fa-info-circle me-1"></i> This action cannot be undone.</small>
                                                    </p>
                                                </div>
                                                <div class="modal-footer border-0">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('teacher-admin.sections.destroy', $section) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Delete Section</button>
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
                                        <div class="mb-3">
                                            <i class="fas fa-folder-open text-muted" style="font-size: 2.5rem;"></i>
                                        </div>
                                        <h6 class="text-muted mb-2">No sections found</h6>
                                        <p class="text-muted mb-3"><small>Create a section to get started</small></p>
                                        <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-primary btn-sm">
                                            <i class="fas fa-plus me-2"></i> Add New Section
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

@push('scripts')
<script>
$(document).ready(function() {
    function filterTable() {
        const searchValue = $("#searchInput").val().toLowerCase();
        const gradeValue = $("#gradeFilter").val();
        const statusValue = $("#statusFilter").val();
        
        let visibleCount = 0;
        
        $("#sectionsTable tbody tr").each(function() {
            const $row = $(this);
            let shouldShow = true;
            
            if (searchValue) {
                const textContent = $row.text().toLowerCase();
                shouldShow = textContent.includes(searchValue);
            }
            
            if (shouldShow && gradeValue) {
                const gradeText = $row.find("td:nth-child(2)").text().trim();
                shouldShow = gradeText === gradeValue;
            }
            
            if (shouldShow && statusValue) {
                const statusText = $row.find("td:nth-child(6)").text().trim();
                shouldShow = statusText === statusValue;
            }
            
            $row.toggle(shouldShow);
            
            if (shouldShow) visibleCount++;
        });
        
        $("#totalCount").text(visibleCount + (visibleCount === 1 ? " section" : " sections"));
    }

    // Add input delay for better performance
    let searchTimeout;
    $("#searchInput").on("input", function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterTable, 300);
    });
    
    $("#gradeFilter, #statusFilter").on("change", filterTable);
    
    $("#resetFilters").on("click", function() {
        $("#searchInput").val("");
        $("#gradeFilter, #statusFilter").val("");
        filterTable();
    });
    
    // Initialize tooltips
    $('[title]').tooltip();
});
</script>
@endpush
@endsection 