@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow border-0">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3 border-bottom-0">
                    <h4 class="mb-0 fw-bold text-primary">
                        <i class="fas fa-table me-2"></i>Generate Consolidated Grades
                    </h4>
                    <a href="{{ route('teacher-admin.reports.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Reports
                    </a>
                </div>
                <div class="card-body pt-0">
                    <p class="text-muted mb-4">Select a section and quarter to generate a consolidated grades report.</p>

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

                    <!-- Generate Report Form - Opens in new tab -->
                    <form action="{{ route('teacher-admin.reports.generate-consolidated-grades') }}" method="POST" id="reportForm" target="_blank">
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
                                        No sections found. Please add sections to your school.
                                    </div>
                                @else
                                    @foreach($sectionsByGradeLevel as $gradeLevel => $gradeSections)
                                        <div class="card mb-3 shadow-sm section-grade-card" data-grade-level="{{ $gradeLevel }}">
                                            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-graduation-cap me-2 text-primary"></i>
                                                    <h5 class="mb-0"> {{ $gradeLevel }}</h5>
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
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle sections visibility
        const toggleButtons = document.querySelectorAll('.toggle-sections');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const grade = this.getAttribute('data-grade');
                const sectionList = document.getElementById('sectionList' + grade);
                const icon = this.querySelector('i');

                if (sectionList.style.display === 'none') {
                    sectionList.style.display = '';
                    icon.classList.remove('fa-chevron-up');
                    icon.classList.add('fa-chevron-down');
                } else {
                    sectionList.style.display = 'none';
                    icon.classList.remove('fa-chevron-down');
                    icon.classList.add('fa-chevron-up');
                }
            });
        });

        // Quarter tab changes
        const quarterTabs = document.querySelectorAll('#quarterTabs .nav-link');
        quarterTabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const quarter = this.getAttribute('data-quarter');
                document.getElementById('selected_quarter').value = quarter;
            });
        });

        // Generate report button clicks - open in new tab
        const generateButtons = document.querySelectorAll('.generate-report-btn');
        generateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const sectionId = this.getAttribute('data-section-id');
                const quarter = this.getAttribute('data-quarter');

                // Set form values
                document.getElementById('selected_section_id').value = sectionId;
                document.getElementById('selected_quarter').value = quarter;

                // Show loading state
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> Generating...';
                this.disabled = true;

                // Get the form element
                const form = document.getElementById('reportForm');

                // Submit the form
                form.submit();

                // Reset button after a short delay
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-file-alt me-1"></i> Generate';
                    this.disabled = false;
                }, 1000);
            });
        });
    });
</script>
@endpush
@endsection
