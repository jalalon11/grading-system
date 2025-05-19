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

    <!-- Stats Summary -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-0">
                <div class="col-md-4 d-flex align-items-center border-end">
                    <div class="px-3">
                        <i class="fas fa-door-open text-primary me-2"></i>
                        <span class="text-muted">Sections:</span>
                        <span class="fw-bold ms-1">{{ $sections->count() }}</span>
                        <span class="text-muted ms-2">({{ $sections->where('is_active', true)->count() }} active)</span>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center border-end">
                    <div class="px-3">
                        <i class="fas fa-user-graduate text-info me-2"></i>
                        <span class="text-muted">Students:</span>
                        <span class="fw-bold ms-1">{{ $sections->sum('students_count') }}</span>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <div class="px-3">
                        <i class="fas fa-graduation-cap text-warning me-2"></i>
                        <span class="text-muted">Grade Levels:</span>
                        <span class="fw-bold ms-1">{{ $sections->pluck('grade_level')->unique()->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Simplified Search and Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-2 align-items-center">
                <div class="col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search sections...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="gradeFilter" class="form-select">
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
                <div class="col-md-3">
                    <select id="statusFilter" class="form-select">
                        <option value="">All Status</option>
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                    </select>
                </div>
                <div class="col-md-1">
                    <button id="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced CSS for better readability -->
    <style>
        /* Table styling for better readability */
        .table {
            font-size: 1rem;
            color: #333;
        }

        .table thead th {
            font-weight: 600;
            font-size: 1.05rem;
            color: #495057;
        }

        .table tbody td {
            padding-top: 0.9rem;
            padding-bottom: 0.9rem;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Link styling */
        .table a.text-decoration-none {
            color: #0d6efd;
            font-weight: 500;
        }

        .table a.text-decoration-none:hover {
            text-decoration: underline !important;
        }

        /* Badge styling */
        .badge {
            font-weight: 500;
            font-size: 0.85rem;
            padding: 0.35rem 0.65rem;
        }

        /* Action buttons */
        .action-btn {
            padding: 0.4rem;
            margin: 0 0.1rem;
        }

        /* Small text */
        .small {
            font-size: 0.85rem;
            color: #6c757d;
        }
    </style>

    <!-- Section List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-list text-primary me-2"></i>
                <span class="fw-medium">Section List</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="sectionsTable">
                    <thead>
                        <tr class="bg-light">
                            <th class="py-3">Section</th>
                            <th class="py-3">Grade</th>
                            <th class="py-3">Adviser</th>
                            <th class="py-3 text-center">Students</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $section)
                            <tr>
                                <td>
                                    <a href="{{ route('teacher-admin.sections.show', $section) }}"
                                       class="text-decoration-none">
                                        {{ $section->name }}
                                    </a>
                                    <div class="small text-muted">{{ $section->school_year }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ $section->grade_level }}
                                    </span>
                                </td>
                                <td>
                                    @if($section->adviser)
                                        {{ $section->adviser->name }}
                                    @else
                                        <span class="text-muted">Not assigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{ $section->students_count }}
                                </td>
                                <td class="text-center">
                                    @if($section->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('teacher-admin.sections.show', $section) }}"
                                       class="btn btn-sm btn-outline-primary action-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher-admin.sections.edit', $section) }}"
                                       class="btn btn-sm btn-outline-secondary action-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger action-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $section->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $section->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Section</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>{{ $section->name }}</strong>?</p>
                                                    <p class="text-danger small">This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('teacher-admin.sections.destroy', $section) }}" method="POST">
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
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <i class="fas fa-folder-open text-muted mb-2" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0">No sections found</p>
                                    <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i> Add New Section
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

@push('scripts')
<script>
$(document).ready(function() {
    // Simple table filtering function
    function filterTable() {
        const searchValue = $("#searchInput").val().toLowerCase();
        const gradeValue = $("#gradeFilter").val();
        const statusValue = $("#statusFilter").val();

        $("#sectionsTable tbody tr").each(function() {
            const $row = $(this);
            let shouldShow = true;

            // Search filter
            if (searchValue) {
                const textContent = $row.text().toLowerCase();
                shouldShow = textContent.includes(searchValue);
            }

            // Grade filter
            if (shouldShow && gradeValue) {
                const gradeText = $row.find("td:nth-child(2)").text().trim();
                shouldShow = gradeText.includes(gradeValue);
            }

            // Status filter
            if (shouldShow && statusValue) {
                const statusText = $row.find("td:nth-child(5)").text().trim();
                shouldShow = statusText.includes(statusValue);
            }

            $row.toggle(shouldShow);
        });
    }

    // Add input delay for search
    let searchTimeout;
    $("#searchInput").on("input", function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(filterTable, 300);
    });

    // Apply filters on change
    $("#gradeFilter, #statusFilter").on("change", filterTable);

    // Reset all filters
    $("#resetFilters").on("click", function() {
        $("#searchInput").val("");
        $("#gradeFilter, #statusFilter").val("");
        filterTable();
    });
});
</script>
@endpush
@endsection