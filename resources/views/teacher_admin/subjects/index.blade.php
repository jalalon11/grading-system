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

    <!-- Stats Summary -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-3">
            <div class="row g-0">
                <div class="col-md-4 d-flex align-items-center border-end">
                    <div class="px-3">
                        <i class="fas fa-book text-primary me-2"></i>
                        <span class="text-muted">Subjects:</span>
                        <span class="fw-bold ms-1">{{ $subjects->count() }}</span>
                        <span class="text-muted ms-2">({{ $subjects->where('is_active', true)->count() }} active)</span>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center border-end">
                    <div class="px-3">
                        <i class="fas fa-chalkboard text-info me-2"></i>
                        <span class="text-muted">Assignments:</span>
                        <span class="fw-bold ms-1">{{ $subjects->sum('sections_count') ?? 0 }}</span>
                    </div>
                </div>
                <div class="col-md-4 d-flex align-items-center">
                    <div class="px-3">
                        <i class="fas fa-graduation-cap text-warning me-2"></i>
                        <span class="text-muted">Grade Levels:</span>
                        <span class="fw-bold ms-1">{{ $subjects->pluck('grade_level')->filter()->unique()->count() }}</span>
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
                        <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search subjects...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select id="gradeLevelFilter" class="form-select">
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

        /* MAPEH badge specific styling */
        .mapeh-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
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

    <!-- Subjects Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="d-flex align-items-center">
                <i class="fas fa-list text-primary me-2"></i>
                <span class="fw-medium">Subject List</span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="subjectsTable">
                    <thead>
                        <tr class="bg-light">
                            <th class="py-3">Subject</th>
                            <th class="py-3">Grade</th>
                            <th class="py-3 text-center">Sections</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($subjects as $subject)
                            @php
                                $isComponent = $subject->is_component ?? false;
                                if ($isComponent) continue;

                                // Check if this is a MAPEH subject
                                $hasComponents = isset($subject->components) && $subject->components->count() > 0;
                                $isMAPEH = $hasComponents && $subject->components->pluck('name')->filter(function($name) {
                                    return in_array(strtolower($name), ['music', 'arts', 'physical education', 'health']) ||
                                           in_array(strtolower(substr($name, 0, 5)), ['music', 'arts', 'physi', 'healt']);
                                })->count() == 4;
                            @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="text-decoration-none">
                                        {{ $subject->name }}
                                    </a>
                                    @if($isMAPEH)
                                        <span class="badge bg-info text-white mapeh-badge ms-1">MAPEH</span>
                                    @endif
                                    <div class="small text-muted">{{ $subject->code ?? 'No code' }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">
                                        Grade {{ $subject->grade_level }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    {{ $subject->sections_count ?? 0 }}
                                </td>
                                <td class="text-center">
                                    @if($subject->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('teacher-admin.subjects.show', $subject) }}"
                                       class="btn btn-sm btn-outline-primary action-btn">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('teacher-admin.subjects.edit', $subject) }}"
                                       class="btn btn-sm btn-outline-secondary action-btn">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger action-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal{{ $subject->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>

                                    <!-- Delete Modal -->
                                    <div class="modal fade" id="deleteModal{{ $subject->id }}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Delete Subject</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to delete <strong>{{ $subject->name }}</strong>?</p>

                                                    @if($subject->sections_count > 0)
                                                        <div class="alert alert-warning small">
                                                            <i class="fas fa-exclamation-circle me-1"></i>
                                                            This subject is assigned to {{ $subject->sections_count }} {{ Str::plural('section', $subject->sections_count) }}.
                                                        </div>
                                                    @endif

                                                    <p class="text-danger small">This action cannot be undone.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('teacher-admin.subjects.destroy', $subject) }}" method="POST">
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
                                <td colspan="5" class="text-center py-4">
                                    <i class="fas fa-book text-muted mb-2" style="font-size: 1.5rem;"></i>
                                    <p class="mb-0">No subjects found</p>
                                    <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-primary btn-sm mt-2">
                                        <i class="fas fa-plus me-1"></i> Add New Subject
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
        const gradeValue = $("#gradeLevelFilter").val();
        const statusValue = $("#statusFilter").val();

        $("#subjectsTable tbody tr").each(function() {
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
                const statusText = $row.find("td:nth-child(4)").text().trim();
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
    $("#gradeLevelFilter, #statusFilter").on("change", filterTable);

    // Reset all filters
    $("#resetFilters").on("click", function() {
        $("#searchInput").val("");
        $("#gradeLevelFilter, #statusFilter").val("");
        filterTable();
    });
});
</script>
@endpush
@endsection
