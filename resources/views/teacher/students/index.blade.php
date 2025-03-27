@extends('layouts.app')

@push('styles')
<style>
    .student-count-badge {
        font-size: 0.85rem;
        padding: 0.35em 0.65em;
    }
    .student-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 15px;
    }
    .student-card {
        transition: all 0.2s ease;
        border: none !important;
    }
    .student-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .stat-card {
        border-left: 4px solid;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-5px);
    }
    .border-left-primary {
        border-left-color: #4e73df !important;
    }
    .border-left-success {
        border-left-color: #1cc88a !important;
    }
    .border-left-info {
        border-left-color: #36b9cc !important;
    }
    .border-left-warning {
        border-left-color: #f6c23e !important;
    }
    .section-header {
        padding: 10px 15px;
        border-radius: 5px;
        margin-bottom: 20px;
        background: rgba(78, 115, 223, 0.05);
        border-left: 4px solid #4e73df;
    }
    .grade-level-header {
        padding: 15px;
        margin-bottom: 20px;
        background: #f8f9fc;
        border-bottom: 1px solid #e3e6f0;
        border-radius: 5px;
    }
    .search-input {
        padding-left: 40px;
    }
    .search-icon {
        position: absolute;
        left: 25px;
        top: 10px;
        color: #d1d3e2;
    }
    .filter-dropdown {
        min-width: 220px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-graduate text-primary me-2"></i> Student Management
            </h1>
            <p class="text-muted">View, manage, and organize students by grade level and section</p>
        </div>
        <div>
            <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-home me-1"></i> Dashboard
            </a>
            <a href="{{ route('teacher.students.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus-circle me-1"></i> Add New Student
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Statistics Row -->
    <div class="row mb-4">
        <!-- Total Students Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm h-100 border-left-primary">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Students</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $students->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-primary-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gender Distribution Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm h-100 border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Gender Distribution</div>
                            <div class="small mb-1">Male: {{ $students->where('gender', 'Male')->count() }}</div>
                            <div class="small">Female: {{ $students->where('gender', 'Female')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-venus-mars fa-2x text-success-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sections Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm h-100 border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Sections</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $students->pluck('section.name')->unique()->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-door-open fa-2x text-info-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grade Levels Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-0 shadow-sm h-100 border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Grade Levels</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $students->pluck('section.grade_level')->unique()->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-layer-group fa-2x text-warning-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Row -->
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <div class="row g-3">
                <!-- Search Input -->
                <div class="col-md-6 position-relative">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="studentSearch" class="form-control search-input" placeholder="Search students by name or ID...">
                </div>
                
                <!-- Filter Dropdowns -->
                <div class="col-md-3">
                    <select id="gradeFilter" class="form-select">
                        <option value="">All Grade Levels</option>
                        @foreach($students->pluck('section.grade_level')->unique()->sort() as $gradeLevel)
                            <option value="{{ $gradeLevel }}">Grade {{ $gradeLevel }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <select id="sectionFilter" class="form-select">
                        <option value="">All Sections</option>
                        @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                            <option value="{{ $id }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>

    @if($students->count() > 0)
        <!-- Organize students by grade level and section -->
        @php
            $studentsByGradeLevel = $students->groupBy(function ($student) {
                return $student->section->grade_level ?? 'Unassigned';
            })->sortKeys();
        @endphp

        @foreach($studentsByGradeLevel as $gradeLevel => $gradeStudents)
            <div class="grade-level-header">
                <h4 class="mb-0 d-flex align-items-center">
                    <i class="fas fa-graduation-cap text-primary me-2"></i>
                    Grade {{ $gradeLevel }}
                    <span class="badge bg-primary ms-2 student-count-badge">{{ $gradeStudents->count() }} students</span>
                </h4>
            </div>

            <!-- Group students by section within this grade level -->
            @php
                $studentsBySection = $gradeStudents->groupBy(function ($student) {
                    return $student->section->name ?? 'Unassigned';
                })->sortKeys();
            @endphp

            @foreach($studentsBySection as $sectionName => $sectionStudents)
                <div class="section-header" data-section-id="{{ $sectionStudents->first()->section->id ?? '' }}">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-users text-info me-2"></i>
                            Section: {{ $sectionName }}
                        </h5>
                        <span class="badge bg-info student-count-badge">{{ $sectionStudents->count() }} students</span>
                    </div>
                </div>

                <div class="row mb-4 student-section" data-grade="{{ $gradeLevel }}" data-section-id="{{ $sectionStudents->first()->section->id ?? '' }}">
                    @foreach($sectionStudents as $student)
                        <div class="col-xl-3 col-md-6 mb-4 student-item" 
                             data-name="{{ strtolower($student->last_name . ' ' . $student->first_name) }}"
                             data-student-id="{{ strtolower($student->student_id) }}">
                            <div class="card student-card shadow-sm h-100">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="student-avatar bg-primary bg-opacity-10 text-primary">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 font-weight-bold">{{ $student->last_name }}, {{ $student->first_name }}</h6>
                                            <span class="small text-muted">ID: {{ $student->student_id }}</span>
                                        </div>
                                    </div>
                                    <div class="mb-2 small">
                                        <i class="fas fa-venus-mars me-1 text-primary"></i> {{ $student->gender }}
                                    </div>
                                    <div class="small mb-3">
                                        <i class="fas fa-user-shield me-1 text-info"></i> 
                                        Guardian: {{ $student->guardian_name ?: 'Not specified' }}
                                    </div>
                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-auto">
                                        <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye me-1"></i> View
                                        </a>
                                        <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}">
                                            <i class="fas fa-trash me-1"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteModal{{ $student->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $student->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="deleteModalLabel{{ $student->id }}">Confirm Delete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to delete student <strong>{{ $student->full_name }}</strong>?</p>
                                            <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <form action="{{ route('teacher.students.destroy', $student->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endforeach

        <!-- No Results Message (hidden by default) -->
        <div id="noResults" class="text-center py-5 d-none">
            <i class="fas fa-search fa-3x text-muted mb-3"></i>
            <h4 class="text-muted">No Students Found</h4>
            <p>Try adjusting your search or filter criteria.</p>
            <button id="resetFilters" class="btn btn-outline-primary mt-2">
                <i class="fas fa-undo me-1"></i> Reset Filters
            </button>
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-user-graduate text-muted mb-3" style="font-size: 3rem;"></i>
            <h4 class="text-muted mb-3">No Students Found</h4>
            <p class="mb-4">You haven't added any students yet.</p>
            <a href="{{ route('teacher.students.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Add New Student
            </a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const studentSearch = document.getElementById('studentSearch');
        const gradeFilter = document.getElementById('gradeFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const noResults = document.getElementById('noResults');
        const studentItems = document.querySelectorAll('.student-item');
        const studentSections = document.querySelectorAll('.student-section');
        const gradeLevelHeaders = document.querySelectorAll('.grade-level-header');
        const sectionHeaders = document.querySelectorAll('.section-header');

        // Function to filter students
        function filterStudents() {
            const searchTerm = studentSearch.value.toLowerCase();
            const selectedGrade = gradeFilter.value;
            const selectedSection = sectionFilter.value;
            
            let visibleCount = 0;
            let visibleSections = new Set();
            let visibleGrades = new Set();
            
            // Loop through all student items
            studentItems.forEach(item => {
                const studentName = item.getAttribute('data-name');
                const studentId = item.getAttribute('data-student-id');
                const studentGrade = item.closest('.student-section').getAttribute('data-grade');
                const studentSectionId = item.closest('.student-section').getAttribute('data-section-id');
                
                // Check if student matches all filters
                const matchesSearch = searchTerm === '' || 
                    studentName.includes(searchTerm) || 
                    studentId.includes(searchTerm);
                    
                const matchesGrade = selectedGrade === '' || studentGrade === selectedGrade;
                const matchesSection = selectedSection === '' || studentSectionId === selectedSection;
                
                // Show/hide based on filters
                if (matchesSearch && matchesGrade && matchesSection) {
                    item.classList.remove('d-none');
                    visibleCount++;
                    visibleSections.add(studentSectionId);
                    visibleGrades.add(studentGrade);
                } else {
                    item.classList.add('d-none');
                }
            });
            
            // Show/hide section containers based on visible students
            studentSections.forEach(section => {
                const sectionId = section.getAttribute('data-section-id');
                const hasVisibleStudents = visibleSections.has(sectionId);
                section.classList.toggle('d-none', !hasVisibleStudents);
            });
            
            // Show/hide section headers
            sectionHeaders.forEach(header => {
                const sectionId = header.getAttribute('data-section-id');
                const hasVisibleStudents = visibleSections.has(sectionId);
                header.classList.toggle('d-none', !hasVisibleStudents);
            });
            
            // Show/hide grade level headers
            gradeLevelHeaders.forEach(header => {
                const gradeText = header.querySelector('h4').textContent.trim();
                const grade = gradeText.replace('Grade ', '').split(' ')[0];
                const hasVisibleStudents = visibleGrades.has(grade);
                header.classList.toggle('d-none', !hasVisibleStudents);
            });
            
            // Show/hide no results message
            noResults.classList.toggle('d-none', visibleCount > 0);
        }

        // Add event listeners
        studentSearch.addEventListener('input', filterStudents);
        gradeFilter.addEventListener('change', filterStudents);
        sectionFilter.addEventListener('change', filterStudents);
        
        // Reset filters
        resetFiltersBtn.addEventListener('click', function() {
            studentSearch.value = '';
            gradeFilter.value = '';
            sectionFilter.value = '';
            filterStudents();
        });
    });
</script>
@endpush
@endsection 