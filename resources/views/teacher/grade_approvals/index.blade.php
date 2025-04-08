@extends('layouts.app')

@section('styles')
<style>
    /* Custom styles for grade approvals page */
    /* MAPEH Dropdown Styles */
    .dropdown-menu {
        min-width: 200px;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: 1px solid rgba(0, 0, 0, 0.1);
        z-index: 1050; /* Ensure dropdown appears above other elements */
        position: absolute; /* Ensure proper positioning */
    }

    .dropdown-header {
        font-weight: 600;
        color: #0d6efd;
    }

    .dropdown-item:hover {
        background-color: rgba(13, 110, 253, 0.1);
    }

    /* Fix for dropdown positioning */
    .dropdown {
        position: relative;
    }

    /* Ensure dropdown is visible */
    .dropdown-menu.show {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
        z-index: 9999 !important;
    }

    /* Custom styles for MAPEH modal */
    .modal-dialog-centered {
        display: flex;
        align-items: center;
        min-height: calc(100% - 1rem);
    }

    .list-group-item-action {
        transition: background-color 0.2s;
        padding: 12px 16px;
        border-radius: 4px;
        margin-bottom: 4px;
    }

    .list-group-item-action:hover {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }

    /* Ensure modals appear above everything else */
    .modal {
        z-index: 1060 !important;
    }

    .modal-backdrop {
        z-index: 1050 !important;
    }

    /* Style the modal header */
    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-title {
        color: #0d6efd;
        font-weight: 600;
    }

    .nav-tabs .nav-link {
        font-weight: 500;
        color: #495057;
    }

    .nav-tabs .nav-link.active {
        font-weight: 600;
        color: #0d6efd;
        border-bottom: 2px solid #0d6efd;
    }

    .card-header {
        border-bottom: 1px solid rgba(0,0,0,.125);
    }

    .approval-item {
        transition: all 0.2s ease;
        border-left: 3px solid transparent;
    }

    .approval-item.approved {
        border-left-color: #198754;
    }

    .approval-item.hidden {
        border-left-color: #6c757d;
    }

    .approval-item:hover {
        background-color: rgba(0,0,0,.03);
    }

    /* Hide items based on search or filter */
    .approval-item.search-hidden,
    .approval-item.filter-hidden {
        display: none;
    }

    .btn-group .btn.active {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }

    /* Tooltip styles */
    [title] {
        position: relative;
    }

    /* Button styles */
    .approval-actions .btn {
        min-width: 130px;
        margin-bottom: 5px;
    }

    /* Responsive adjustments */
    @media (max-width: 767.98px) {
        .approval-cards .col {
            margin-bottom: 1rem;
        }

        .approval-actions {
            display: flex;
            flex-direction: column;
            width: 100%;
            margin-top: 10px;
        }

        .approval-actions .btn {
            margin-bottom: 8px;
            width: 100%;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-check-circle mr-2 text-primary"></i> Grade Approvals
            </h1>
            <p class="text-muted">Manage approval status for your subject grades</p>
        </div>
        <div>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
        </div>
    </div>

    {{-- <!-- Search and Filter -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" id="searchInput" class="form-control" placeholder="Search subjects...">
                    </div>
                </div>
                <div class="col-md-8 text-md-end">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-secondary active" data-filter="all">All</button>
                        <button type="button" class="btn btn-outline-success" data-filter="approved">Approved</button>
                        <button type="button" class="btn btn-outline-secondary" data-filter="hidden">Hidden</button>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

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

    <!-- Info Alert -->
    <div class="alert alert-info mb-4">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-info-circle fa-2x"></i>
            </div>
            <div>
                <h6 class="alert-heading">About Grade Approvals</h6>
                <p class="mb-0">
                    When you approve grades for a subject, they will be visible in the consolidated grade reports generated by teacher admins.
                    This allows you to control when your grades are ready to be included in official reports.
                </p>
            </div>
        </div>
    </div>

    @if($teachingAssignments->isEmpty())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle me-2"></i>
            You are not assigned to teach any subjects in any sections.
        </div>
    @else
        <!-- Quarter Tabs -->
        <ul class="nav nav-tabs mb-4" id="quarterTabs" role="tablist">
            @foreach($quarters as $quarterKey => $quarterName)
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                            id="{{ $quarterKey }}-tab"
                            data-bs-toggle="tab"
                            data-bs-target="#{{ $quarterKey }}-content"
                            type="button"
                            role="tab"
                            aria-controls="{{ $quarterKey }}-content"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $quarterName }}
                    </button>
                </li>
            @endforeach
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="quarterTabsContent">
            @foreach($quarters as $quarterKey => $quarterName)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                     id="{{ $quarterKey }}-content"
                     role="tabpanel"
                     aria-labelledby="{{ $quarterKey }}-tab">

                    <!-- Group by Section -->
                    @php
                        $sectionGroups = $teachingAssignments->groupBy(function($item) {
                            return $item->section_id . '-' . $item->section_name . '-' . $item->grade_level;
                        });
                    @endphp

                    <div class="row row-cols-1 row-cols-md-2 g-4 approval-cards">
                        @foreach($sectionGroups as $sectionKey => $sectionAssignments)
                            @php
                                list($sectionId, $sectionName, $gradeLevel) = explode('-', $sectionKey);
                            @endphp
                            <div class="col section-group">
                                <div class="card h-100 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="card-title mb-0">{{ $sectionName }} (Grade {{ $gradeLevel }})</h5>
                                    </div>
                                    <div class="card-body p-0">
                                        <ul class="list-group list-group-flush subject-list">
                                            @foreach($sectionAssignments as $assignment)
                                                @php
                                                    $approvalKey = $assignment->section_id . '-' . $assignment->subject_id . '-' . $quarterKey;
                                                    $approval = $approvals[$approvalKey] ?? null;
                                                    $isApproved = $approval ? $approval->is_approved : false;
                                                    $lastUpdated = $approval ? $approval->updated_at->setTimezone('Asia/Manila')->format('M d, Y h:i A') : 'Never';
                                                @endphp
                                                <li class="list-group-item approval-item {{ $isApproved ? 'approved' : 'hidden' }}">
                                                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                                        <div class="subject-info mb-2 mb-md-0">
                                                            <h6 class="mb-0 subject-name">{{ $assignment->subject_name }}</h6>
                                                            <small class="text-muted">Last updated: {{ $lastUpdated }}</small>
                                                        </div>
                                                        <div class="d-flex flex-wrap gap-2 approval-actions">
                                                            <form action="{{ route('teacher.grade-approvals.update') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="section_id" value="{{ $assignment->section_id }}">
                                                                <input type="hidden" name="subject_id" value="{{ $assignment->subject_id }}">
                                                                <input type="hidden" name="quarter" value="{{ $quarterKey }}">

                                                                @if($isApproved)
                                                                    <input type="hidden" name="is_approved" value="0">
                                                                    <button type="submit" class="btn btn-outline-secondary">
                                                                        <i class="fas fa-eye-slash me-1"></i> Hide Grades
                                                                    </button>
                                                                @else
                                                                    <input type="hidden" name="is_approved" value="1">
                                                                    <button type="submit" class="btn btn-outline-success">
                                                                        <i class="fas fa-check me-1"></i> Approve Grades
                                                                    </button>
                                                                @endif
                                                            </form>

                                                            @php
                                                                // Check if this is a MAPEH subject
                                                                $subject = \App\Models\Subject::with('components')->find($assignment->subject_id);
                                                                $isMAPEH = $subject && $subject->getIsMAPEHAttribute();
                                                            @endphp

                                                            @if($isMAPEH)
                                                                <!-- Button trigger modal -->
                                                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#mapehModal{{ $assignment->subject_id }}{{ $quarterKey }}">
                                                                    <i class="fas fa-edit me-1"></i> Edit Grades
                                                                </button>

                                                                <!-- Modal for MAPEH components -->
                                                                <div class="modal fade" id="mapehModal{{ $assignment->subject_id }}{{ $quarterKey }}" tabindex="-1" aria-labelledby="mapehModalLabel{{ $assignment->subject_id }}{{ $quarterKey }}" aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="mapehModalLabel{{ $assignment->subject_id }}{{ $quarterKey }}">Select MAPEH Component</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="list-group">
                                                                                    @if($subject->components->count() > 0)
                                                                                        @foreach($subject->components as $component)
                                                                                            <a href="{{ route('teacher.reports.generate-class-record-get', ['section_id' => $assignment->section_id, 'subject_id' => $component->id, 'quarter' => $quarterKey]) }}"
                                                                                               class="list-group-item list-group-item-action">
                                                                                                {{ $component->name }}
                                                                                            </a>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <div class="list-group-item text-muted">No components found</div>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @else
                                                                <a href="{{ route('teacher.reports.generate-class-record-get', ['section_id' => $assignment->section_id, 'subject_id' => $assignment->subject_id, 'quarter' => $quarterKey]) }}"
                                                                   class="btn btn-outline-primary">
                                                                    <i class="fas fa-edit me-1"></i> Edit Grades
                                                                </a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="mt-2 d-flex justify-content-between align-items-center">
                                                        <span class="badge {{ $isApproved ? 'bg-success' : 'bg-secondary' }}">
                                                            {{ $isApproved ? 'Approved' : 'Hidden' }}
                                                        </span>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Grade approvals page loaded');

        // Ensure Bootstrap modals are properly initialized
        if (typeof bootstrap !== 'undefined') {
            // Initialize all modals on the page
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modalEl => {
                try {
                    // Store the modal instance for later use
                    modalEl._bsModal = new bootstrap.Modal(modalEl, {
                        backdrop: true,
                        keyboard: true,
                        focus: true
                    });
                    console.log('Modal initialized:', modalEl.id);

                    // Add event listeners for modal events
                    modalEl.addEventListener('shown.bs.modal', function() {
                        console.log('Modal shown:', this.id);
                    });

                    modalEl.addEventListener('hidden.bs.modal', function() {
                        console.log('Modal hidden:', this.id);
                    });

                } catch (err) {
                    console.error('Error initializing modal:', modalEl.id, err);
                }
            });

            // Add click handlers to all modal trigger buttons
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    const targetId = this.getAttribute('data-bs-target');
                    console.log('Modal button clicked for target:', targetId);

                    // Ensure the click event works properly
                    const modalEl = document.querySelector(targetId);
                    if (modalEl && modalEl._bsModal) {
                        e.preventDefault();
                        modalEl._bsModal.show();
                    }
                });
            });
        } else {
            console.error('Bootstrap is not available. Modals may not work properly.');

            // Fallback for when Bootstrap is not available
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const targetId = this.getAttribute('data-bs-target');
                    const modalEl = document.querySelector(targetId);

                    if (modalEl) {
                        modalEl.style.display = 'block';
                        document.body.classList.add('modal-open');

                        // Create a backdrop element
                        const backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                });
            });

            // Add close handlers for the fallback
            document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(closeBtn => {
                closeBtn.addEventListener('click', function() {
                    const modalEl = this.closest('.modal');
                    if (modalEl) {
                        modalEl.style.display = 'none';
                        document.body.classList.remove('modal-open');

                        // Remove backdrop
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.parentNode.removeChild(backdrop);
                        }
                    }
                });
            });
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');

        function applySearch() {
            const searchTerm = searchInput && searchInput.value ? searchInput.value.toLowerCase() : '';
            const subjectItems = document.querySelectorAll('.subject-list .approval-item');

            subjectItems.forEach(item => {
                const subjectName = item.querySelector('.subject-name').textContent.toLowerCase();
                if (subjectName.includes(searchTerm)) {
                    item.classList.remove('search-hidden');
                } else {
                    item.classList.add('search-hidden');
                }
            });

            updateVisibility();
        }

        // Filter buttons
        const filterButtons = document.querySelectorAll('[data-filter]');
        let currentFilter = 'all';

        function applyFilter() {
            const items = document.querySelectorAll('.approval-item');

            items.forEach(item => {
                if (currentFilter === 'all') {
                    item.classList.remove('filter-hidden');
                } else if (currentFilter === 'approved' && item.classList.contains('approved')) {
                    item.classList.remove('filter-hidden');
                } else if (currentFilter === 'hidden' && !item.classList.contains('approved')) {
                    item.classList.remove('filter-hidden');
                } else {
                    item.classList.add('filter-hidden');
                }
            });

            updateVisibility();
        }

        // Update visibility of items and sections
        function updateVisibility() {
            const items = document.querySelectorAll('.approval-item');

            // First update items visibility based on both search and filter
            items.forEach(item => {
                if (item.classList.contains('search-hidden') || item.classList.contains('filter-hidden')) {
                    item.style.display = 'none';
                } else {
                    item.style.display = '';
                }
            });

            // Then check if sections should be hidden
            document.querySelectorAll('.section-group').forEach(section => {
                const allItems = section.querySelectorAll('.approval-item');
                const hiddenItems = section.querySelectorAll('.approval-item[style="display: none"]');

                if (hiddenItems.length === allItems.length) {
                    section.style.display = 'none';
                } else {
                    section.style.display = '';
                }
            });
        }

        // Event listeners
        if (searchInput) {
            searchInput.addEventListener('keyup', applySearch);
            searchInput.addEventListener('input', applySearch);
        }

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Update active button
                filterButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Update filter and apply
                currentFilter = this.getAttribute('data-filter');
                applyFilter();
            });
        });

        // Initialize on page load
        applySearch();
        applyFilter();

        // Add tooltips to buttons
        const editButtons = document.querySelectorAll('.btn-outline-primary');
        editButtons.forEach(button => {
            button.setAttribute('title', 'Edit Grades');
        });

        const approveButtons = document.querySelectorAll('.btn-outline-success');
        approveButtons.forEach(button => {
            button.setAttribute('title', 'Approve Grades');
        });

        const hideButtons = document.querySelectorAll('.btn-outline-secondary');
        hideButtons.forEach(button => {
            if (button.querySelector('.fa-eye-slash')) {
                button.setAttribute('title', 'Hide Grades');
            }
        });
    });
</script>
@endsection
