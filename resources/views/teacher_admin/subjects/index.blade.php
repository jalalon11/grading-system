@extends('layouts.app')

@section('content')
<div class="container-fluid p-4">
    <!-- Main Header -->
    <div class="d-flex justify-content-between align-items-center p-3 mb-4 bg-primary rounded-3 text-white shadow-sm">
        <h4 class="mb-0">
            <i class="fas fa-book me-2"></i> Manage Subjects
        </h4>
        <button class="btn btn-light" onclick="window.location.href='{{ route('teacher-admin.subjects.create') }}'">
            <i class="fas fa-plus me-2"></i> Add New Subject
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <!-- Total Subjects -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle p-3 bg-primary bg-opacity-10">
                                <i class="fas fa-book fa-lg text-primary"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total Subjects</p>
                            <h4 class="mb-0 fw-bold">{{ $subjects->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Subjects -->
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
                            <p class="text-muted mb-1 small">Active Subjects</p>
                            <h4 class="mb-0 fw-bold">{{ $subjects->where('is_active', true)->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Assignments -->
        <div class="col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-3">
                            <div class="rounded-circle p-3 bg-info bg-opacity-10">
                                <i class="fas fa-chalkboard fa-lg text-info"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-muted mb-1 small">Total Assignments</p>
                            <h4 class="mb-0 fw-bold">{{ $subjects->sum('sections_count') ?? 0 }}</h4>
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
                            <h4 class="mb-0 fw-bold">{{ $subjects->pluck('grade_level')->filter()->unique()->count() }}</h4>
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
                                    placeholder="Search subjects..."
                                    style="padding-left: 40px !important; background-color: #f8f9fa; border-color: #dee2e6;">
                                <i class="fas fa-search position-absolute text-muted"
                                   style="left: 14px; top: 50%; transform: translateY(-50%);"></i>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="filter-box">
                            <select id="gradeLevelFilter"
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

        /* Additional styles for the table */
        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            padding: 0.5rem 0.75rem;
            font-size: 1rem;
        }

        .btn-group .btn:hover {
            background-color: #e9ecef;
        }

        .rounded-circle {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
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

        /* Badge styles */
        .table > :not(caption) > * > * {
            padding: 1rem 0.75rem;
        }

        .table th {
            font-weight: 600;
            color: #4a5568;
            background-color: #f8f9fa;
            border-bottom: 2px solid #e9ecef;
        }

        .table td {
            vertical-align: middle;
        }

        .rounded-circle.bg-primary.bg-opacity-10 {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Link styles */
        .table a.text-dark {
            font-weight: 500;
        }

        .table a.text-dark:hover {
            color: #0d6efd !important;
        }
    </style>

    <!-- Subjects Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="d-flex justify-content-between align-items-center p-3 bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-list me-2"></i>
                    <span class="fw-medium">Subject List</span>
                </div>
                {{-- <span class="badge bg-light text-primary" id="subjectCount">{{ $subjects->count() }} subjects</span> --}}
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="subjectsTable">
                    <thead>
                        <tr>
                            <th class="fw-semibold border-0 py-3 ps-4">Subject Name</th>
                            <th class="fw-semibold border-0 py-3">Code</th>
                            <th class="fw-semibold border-0 py-3">Grade Level</th>
                            <th class="fw-semibold border-0 py-3">Sections</th>
                            <th class="fw-semibold border-0 py-3">Status</th>
                            <th class="fw-semibold border-0 py-3 text-end pe-4" style="width: 10%">Actions</th>
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
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="rounded-circle p-2 bg-primary bg-opacity-10">
                                                <i class="fas fa-book text-primary"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="text-decoration-none text-dark">
                                                {{ $subject->name }}
                                            </a>
                                            @if($isMAPEH)
                                                <span class="badge bg-info text-white rounded-pill ms-2">MAPEH</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $subject->code ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary" style="color: white !important;">
                                        Grade {{ $subject->grade_level }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">
                                        {{ $subject->sections_count ?? 0 }} {{ Str::plural('section', $subject->sections_count ?? 0) }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $subject->is_active ? 'bg-success' : 'bg-danger' }}">
                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="py-3 text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('teacher-admin.subjects.show', $subject) }}"
                                           class="btn btn-sm btn-light"
                                           data-bs-toggle="tooltip"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('teacher-admin.subjects.edit', $subject) }}"
                                           class="btn btn-sm btn-light"
                                           data-bs-toggle="tooltip"
                                           title="Edit Subject">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('teacher-admin.subjects.toggle-status', $subject) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="btn btn-sm btn-light"
                                                    data-bs-toggle="tooltip"
                                                    title="{{ $subject->is_active ? 'Deactivate' : 'Activate' }}">
                                                <i class="fas {{ $subject->is_active ? 'fa-toggle-on text-success' : 'fa-toggle-off text-muted' }}"></i>
                                            </button>
                                        </form>
                                        <button type="button"
                                                class="btn btn-sm btn-light"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal{{ $subject->id }}"
                                                title="Delete Subject">
                                            <i class="fas fa-trash-alt"></i>
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
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endpush
@endsection
