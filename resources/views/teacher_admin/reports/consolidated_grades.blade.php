@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 fw-bold text-primary">
            <i class="fas fa-table me-2"></i> Generate Consolidated Grades
        </h4>
        <a href="{{ route('teacher-admin.reports.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-1"></i> Back to Reports
        </a>
    </div>

    <p class="text-muted mb-4">Select a section and quarter to generate a consolidated grades report.</p>

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fas fa-search text-muted"></i>
                </span>
                <input type="text" id="searchInput" class="form-control border-start-0" placeholder="Search sections...">
            </div>
        </div>
        <div class="col-md-4">
            <div class="dropdown">
                <button class="btn btn-outline-secondary dropdown-toggle w-100 d-flex justify-content-between align-items-center" type="button" id="gradeFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="selectedGradeText">All Grades</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="dropdown-menu w-100" id="gradeFilterMenu" aria-labelledby="gradeFilterDropdown">
                    <li><a class="dropdown-item grade-filter-item" href="#" data-grade="all">All Grades</a></li>
                    <li><hr class="dropdown-divider"></li>
                    @foreach($gradeLevels as $level)
                        <li><a class="dropdown-item grade-filter-item" href="#" data-grade="{{ $level }}">Grade {{ $level }}</a></li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-2">
            <button id="filterButton" class="btn btn-primary w-100">
                <i class="fas fa-filter me-2"></i> Filter
            </button>
        </div>
    </div>

    <div class="alert alert-info mb-4">
        <div class="d-flex">
            <div class="me-3">
                <i class="fas fa-info-circle fa-2x"></i>
            </div>
            <div>
                <h6 class="alert-heading">Important Note</h6>
                <p class="mb-0">
                    This report will only show grades for subjects that have been approved by the subject teachers.
                    Subject teachers need to approve their grades in the <a href="{{ route('teacher.grade-approvals.index') }}" class="alert-link">Grade Approvals</a> section.
                </p>
            </div>
        </div>
    </div>

    <!-- Generate Report Form -->
    <form action="{{ route('teacher-admin.reports.generate-consolidated-grades') }}" method="POST" id="reportForm">
        @csrf
        <input type="hidden" name="section_id" id="selected_section_id">
        <input type="hidden" name="quarter" id="selected_quarter" value="Q1">
    </form>

    <!-- Quarter Selector Tabs -->
    <ul class="nav nav-tabs mb-4" id="quarterTabs" role="tablist">
        @foreach($quarters as $key => $value)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                        id="{{ $key }}-tab"
                        data-bs-toggle="tab"
                        data-bs-target="#{{ $key }}-content"
                        type="button"
                        role="tab"
                        aria-controls="{{ $key }}-content"
                        aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                        data-quarter="{{ $key }}">
                    {{ $value }}
                </button>
            </li>
        @endforeach
    </ul>

    <!-- Sections by Grade Level -->
    <div class="tab-content" id="quarterTabsContent">
        @foreach($quarters as $quarterKey => $quarterValue)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ $quarterKey }}-content" role="tabpanel" aria-labelledby="{{ $quarterKey }}-tab">
                @if($sectionsByGradeLevel->isEmpty())
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        No sections found. Please check your filters or add sections to your school.
                    </div>
                @else
                    @foreach($sectionsByGradeLevel as $gradeLevel => $gradeSections)
                        <div class="card mb-3 shadow-sm section-grade-card" data-grade-level="{{ $gradeLevel }}">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                    <h5 class="mb-0">Grade {{ $gradeLevel }}</h5>
                                    <span class="badge bg-primary ms-2">{{ $gradeSections->count() }} {{ Str::plural('section', $gradeSections->count()) }}</span>
                                </div>
                                <button class="btn btn-sm btn-link toggle-sections" data-grade="{{ $gradeLevel }}">
                                    <i class="fas fa-chevron-down"></i>
                                </button>
                            </div>
                            <div class="card-body p-0 section-list" id="sectionList{{ $gradeLevel }}">
                                <div class="list-group list-group-flush">
                                    @foreach($gradeSections as $section)
                                        <div class="list-group-item section-item" data-section-name="{{ strtolower($section->name) }}">
                                            <div class="d-flex justify-content-between align-items-center py-2">
                                                <div>
                                                    <h6 class="mb-0">{{ $section->name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-user-tie me-1"></i> Adviser: {{ $section->adviser->name ?? 'Not assigned' }}
                                                    </small>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-primary generate-report-btn"
                                                        data-section-id="{{ $section->id }}"
                                                        data-section-name="{{ $section->name }}"
                                                        data-quarter="{{ $quarterKey }}">
                                                    <i class="fas fa-file-alt me-1"></i> Generate
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        @endforeach
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle dropdown selection
        const gradeFilterItems = document.querySelectorAll('.grade-filter-item');
        const selectedGradeText = document.getElementById('selectedGradeText');
        let selectedGrade = 'all'; // Default to All Grades

        // Initialize dropdown with All Grades
        if (gradeFilterItems.length > 0) {
            const allGradesItem = document.querySelector('.grade-filter-item[data-grade="all"]');
            if (allGradesItem) {
                selectedGradeText.textContent = allGradesItem.textContent;
            }
        }

        gradeFilterItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                selectedGrade = this.getAttribute('data-grade');
                selectedGradeText.textContent = this.textContent;

                // Apply filter immediately when dropdown item is clicked
                filterSections();
            });
        });

        // Handle filter button click
        const filterButton = document.getElementById('filterButton');
        filterButton.addEventListener('click', function() {
            console.log('Filter button clicked with selectedGrade:', selectedGrade);
            filterSections();
        });

        // Handle search input
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('keyup', function() {
            filterSections();
        });

        // Filter sections based on search and grade level
        function filterSections() {
            const searchTerm = searchInput.value.toLowerCase();
            const sectionCards = document.querySelectorAll('.section-grade-card');

            // Log the current state for debugging
            console.log('Filtering with:', {
                searchTerm: searchTerm,
                selectedGrade: selectedGrade
            });

            sectionCards.forEach(card => {
                const gradeLevel = card.getAttribute('data-grade-level');
                const sectionItems = card.querySelectorAll('.section-item');
                let visibleSections = 0;

                // If grade filter is active and doesn't match this card, hide it
                if (selectedGrade && selectedGrade !== 'all' && gradeLevel !== selectedGrade) {
                    card.style.display = 'none';
                    console.log('Hiding card for grade level:', gradeLevel, 'because selected grade is:', selectedGrade);
                    return;
                }

                // Otherwise, show the card and filter its sections
                card.style.display = 'block';

                sectionItems.forEach(item => {
                    const sectionName = item.getAttribute('data-section-name');
                    if (sectionName.includes(searchTerm)) {
                        item.style.display = 'block';
                        visibleSections++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // If no sections are visible in this card, hide the card
                if (visibleSections === 0) {
                    card.style.display = 'none';
                }
            });

            // Update the dropdown button text to reflect the current selection
            if (selectedGrade === 'all') {
                selectedGradeText.textContent = 'All Grades';
            } else {
                selectedGradeText.textContent = 'Grade ' + selectedGrade;
            }
        }

        // Toggle sections visibility
        const toggleButtons = document.querySelectorAll('.toggle-sections');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const grade = this.getAttribute('data-grade');
                const sectionList = document.getElementById('sectionList' + grade);
                const icon = this.querySelector('i');

                if (sectionList.style.display === 'none') {
                    sectionList.style.display = 'block';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    sectionList.style.display = 'none';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });

        // Handle quarter tab changes
        const quarterTabs = document.querySelectorAll('#quarterTabs .nav-link');
        quarterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const quarter = this.getAttribute('data-quarter');
                document.getElementById('selected_quarter').value = quarter;
            });
        });

        // Handle generate report button clicks
        const generateButtons = document.querySelectorAll('.generate-report-btn');
        generateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-section-id');
                const sectionName = this.getAttribute('data-section-name');
                const quarter = this.getAttribute('data-quarter');

                document.getElementById('selected_section_id').value = sectionId;
                document.getElementById('selected_quarter').value = quarter;

                // Show loading state
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Generating...';
                this.disabled = true;

                // Submit the form
                document.getElementById('reportForm').submit();
            });
        });

        // Run filter on page load to initialize the view
        filterSections();
    });
</script>
@endpush
@endsection
