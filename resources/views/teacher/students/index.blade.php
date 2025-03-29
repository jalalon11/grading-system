@extends('layouts.app')

@push('styles')
<style>
    :root {
        --primary-color: #4e73df;
        --secondary-color: #858796;
        --success-color: #1cc88a;
        --info-color: #36b9cc;
        --warning-color: #f6c23e;
        --danger-color: #e74a3b;
        --light-color: #f8f9fc;
        --dark-color: #5a5c69;
    }
    
    /* Card Styles */
    .student-avatar {
        width: 42px;
        height: 42px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
        color: white;
        box-shadow: 0 3px 6px rgba(0,0,0,0.1);
        margin-right: 12px;
    }
    
    .student-card {
        transition: all 0.25s ease;
        border-radius: 10px !important;
        overflow: hidden;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    
    .student-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        border-color: var(--primary-color) !important;
    }
    
    .student-card .card-body {
        padding: 1.25rem;
    }
    
    .student-card .student-footer {
        padding: 10px 15px;
        background: rgba(78, 115, 223, 0.03);
        border-top: 1px solid rgba(0,0,0,0.05);
    }
    
    /* Statistics Cards */
    .counter-card {
        border-radius: 12px;
        overflow: hidden;
        padding: 1.25rem;
        color: white;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #4e73df 0%, #3a5ec9 100%);
    }
    
    .bg-gradient-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13a673 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }
    
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }
    
    .counter-title {
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.8;
        font-weight: 600;
        margin-bottom: 5px;
    }
    
    .counter-value {
        font-size: 2rem;
        font-weight: 700;
        line-height: 1;
        margin-bottom: 0.5rem;
    }
    
    /* Headers and Organization */
    .section-header {
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 20px;
        background: #f8f9fc;
        border-left: 4px solid var(--primary-color);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .grade-level-header {
        padding: 15px 20px;
        margin-bottom: 20px;
        background: linear-gradient(to right, rgba(78, 115, 223, 0.03), rgba(78, 115, 223, 0.1));
        border-radius: 10px;
        box-shadow: 0 3px 5px rgba(0,0,0,0.02);
    }
    
    /* Search and Filters */
    .search-wrapper {
        position: relative;
    }
    
    .search-input {
        padding-left: 42px;
        border-radius: 8px;
        height: 48px;
        border-color: rgba(0,0,0,0.1);
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    
    .search-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #a7adbf;
        font-size: 16px;
    }
    
    .filter-dropdown {
        height: 48px;
        border-radius: 8px;
        border-color: rgba(0,0,0,0.1);
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0,0,0,0.02);
    }
    
    /* Badges and Buttons */
    .btn-action {
        width: 34px;
        height: 34px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
    }
    
    .badge-count {
        padding: 0.5rem 0.75rem;
        border-radius: 30px;
        font-weight: 600;
        font-size: 0.85rem;
    }
    
    .student-info-badge {
        border-radius: 30px;
        padding: 0.25rem 0.75rem;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        background-color: rgba(0,0,0,0.05);
    }
    
    .student-info-badge i {
        margin-right: 0.35rem;
    }
    
    /* Analytics */
    .analytics-container {
        border-radius: 12px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        height: 100%;
    }
    
    .analytics-title {
        font-weight: 600;
        margin-bottom: 15px;
        color: var(--dark-color);
        font-size: 1rem;
    }
    
    .gender-stat {
        border-radius: 8px;
        padding: 12px 15px;
        background-color: rgba(78, 115, 223, 0.05);
        margin-bottom: 10px;
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
            <p class="text-muted mb-0">Manage, monitor, and organize your student records</p>
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
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle me-2"></i>
                <div>{{ session('success') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle me-2"></i>
                <div>{{ session('error') }}</div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($students->count() > 0)
        <!-- Quick Analytics -->
        <div class="row mb-4">
            <!-- Key Stats -->
            <div class="col-lg-9">
                <div class="row">
                    <!-- Total Students -->
                    <div class="col-md-3 mb-4">
                        <div class="counter-card bg-gradient-primary shadow-sm">
                            <div class="counter-title">Total Students</div>
                            <div class="counter-value">{{ $students->count() }}</div>
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-2" style="background-color: rgba(255, 255, 255, 0.25) !important;">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="small">Across all sections</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sections Count -->
                    <div class="col-md-3 mb-4">
                        <div class="counter-card bg-gradient-success shadow-sm">
                            <div class="counter-title">Active Sections</div>
                            <div class="counter-value">{{ $students->pluck('section.name')->unique()->count() }}</div>
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-2" style="background-color: rgba(255, 255, 255, 0.25) !important;">
                                    <i class="fas fa-door-open"></i>
                                </div>
                                <div class="small">Under your supervision</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Grade Levels -->
                    <div class="col-md-3 mb-4">
                        <div class="counter-card bg-gradient-info shadow-sm">
                            <div class="counter-title">Grade Levels</div>
                            <div class="counter-value">{{ $students->pluck('section.grade_level')->unique()->count() }}</div>
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-2" style="background-color: rgba(255, 255, 255, 0.25) !important;">
                                    <i class="fas fa-layer-group"></i>
                                </div>
                                <div class="small">Active curriculum levels</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Average Age -->
                    <div class="col-md-3 mb-4">
                        <div class="counter-card bg-gradient-warning shadow-sm">
                            <div class="counter-title">Average Age</div>
                            <div class="counter-value">
                                @php
                                    $totalAge = 0;
                                    $studentCount = 0;
                                    foreach($students as $student) {
                                        if ($student->birth_date) {
                                            $age = \Carbon\Carbon::parse($student->birth_date)->age;
                                            $totalAge += $age;
                                            $studentCount++;
                                        }
                                    }
                                    $averageAge = $studentCount > 0 ? round($totalAge / $studentCount, 1) : 0;
                                @endphp
                                {{ $averageAge }}
                            </div>
                            <div class="d-flex align-items-center">
                                <div class="bg-white rounded-circle p-2 me-2" style="background-color: rgba(255, 255, 255, 0.25) !important;">
                                    <i class="fas fa-birthday-cake"></i>
                                </div>
                                <div class="small">Years old</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Gender Distribution -->
            <div class="col-lg-3 mb-4">
                <div class="analytics-container h-100">
                    <h6 class="analytics-title">
                        <i class="fas fa-venus-mars text-info me-2"></i> Gender Distribution
                    </h6>
                    
                    <!-- Section selector for gender distribution -->
                    <div class="mb-3">
                        <select id="genderSectionFilter" class="form-select form-select-sm">
                            <option value="all">All Sections</option>
                            @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    @php
                        // Debug: collect all unique gender values from the database
                        $uniqueGenders = $students->pluck('gender')->unique()->toArray();
                        
                        // Convert to lowercase for case-insensitive comparison
                        $maleCount = $students->filter(function($student) {
                            return strtolower($student->gender) === 'male';
                        })->count();
                        
                        $femaleCount = $students->filter(function($student) {
                            return strtolower($student->gender) === 'female';
                        })->count();
                        
                        $totalStudents = $students->count();
                        $malePercentage = $totalStudents > 0 ? round(($maleCount / $totalStudents) * 100) : 0;
                        $femalePercentage = $totalStudents > 0 ? round(($femaleCount / $totalStudents) * 100) : 0;
                    @endphp
                    
                    <div id="gender-stats-container">
                        <div class="gender-stat d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-male text-primary me-2"></i> Male Students
                            </div>
                            <div class="fw-bold">{{ $maleCount }} ({{ $malePercentage }}%)</div>
                        </div>
                        
                        <div class="gender-stat d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-female text-danger me-2"></i> Female Students
                            </div>
                            <div class="fw-bold">{{ $femaleCount }} ({{ $femalePercentage }}%)</div>
                        </div>
                        
                        <!-- Debug information (will be removed later) -->
                        <!-- <div class="alert alert-info mt-2 small">
                            <p class="mb-1"><strong>Debug:</strong> Found {{ count($uniqueGenders) }} unique gender values</p>
                            <p class="mb-0">Values: {{ implode(', ', $uniqueGenders ?: ['none']) }}</p>
                        </div> -->
                        
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: <?php echo $malePercentage; ?>%" aria-valuenow="<?php echo $malePercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?php echo $femalePercentage; ?>%" aria-valuenow="<?php echo $femalePercentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Row -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="row g-3">
                    <!-- Search Input -->
                    <div class="col-md-6 search-wrapper">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="studentSearch" class="form-control search-input" placeholder="Search by name, ID, or section...">
                    </div>
                    
                    <!-- Filter Dropdowns -->
                    <div class="col-md-3">
                        <select id="gradeFilter" class="form-select filter-dropdown">
                            <option value="">All Grade Levels</option>
                            @foreach($students->pluck('section.grade_level')->unique()->sort() as $gradeLevel)
                                <option value="{{ $gradeLevel }}">Grade {{ $gradeLevel }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-3">
                        <select id="sectionFilter" class="form-select filter-dropdown">
                            <option value="">All Sections</option>
                            @foreach($students->pluck('section.name', 'section.id')->unique() as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Organize students by grade level and section -->
        @php
            $studentsByGradeLevel = $students->groupBy(function ($student) {
                return $student->section->grade_level ?? 'Unassigned';
            })->sortKeys();
        @endphp

        @foreach($studentsByGradeLevel as $gradeLevel => $gradeStudents)
            <div class="grade-level-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-graduation-cap text-primary me-2"></i>
                        Grade {{ $gradeLevel }}
                        <span class="badge bg-primary ms-2 badge-count">{{ $gradeStudents->count() }} students</span>
                    </h4>
                    <div>
                        <button class="btn btn-sm btn-outline-primary toggle-grade-btn" data-grade="{{ $gradeLevel }}">
                            <i class="fas fa-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Group students by section within this grade level -->
            @php
                $studentsBySection = $gradeStudents->groupBy(function ($student) {
                    return $student->section->name ?? 'Unassigned';
                })->sortKeys();
            @endphp

            <div class="grade-level-container" data-grade="{{ $gradeLevel }}">
                @foreach($studentsBySection as $sectionName => $sectionStudents)
                    <div class="section-header" data-section-id="{{ $sectionStudents->first()->section->id ?? '' }}">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-users text-info me-2"></i>
                                Section: {{ $sectionName }}
                                <span class="badge bg-info ms-2 badge-count">{{ $sectionStudents->count() }} students</span>
                            </h5>
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
                                            @php
                                                $avatarColors = [
                                                    'bg-primary' => '#4e73df',
                                                    'bg-success' => '#1cc88a',
                                                    'bg-info' => '#36b9cc',
                                                    'bg-warning' => '#f6c23e',
                                                    'bg-danger' => '#e74a3b'
                                                ];
                                                $hash = crc32($student->id . $student->first_name);
                                                $colorIndex = abs($hash) % count($avatarColors);
                                                $colorKey = array_keys($avatarColors)[$colorIndex];
                                                $bgColor = $avatarColors[$colorKey];
                                            @endphp
                                            <div class="student-avatar" style="background-color: <?php echo $bgColor; ?>">
                                                {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <h6 class="mb-0 fw-bold">{{ $student->last_name }}, {{ $student->first_name }}</h6>
                                                <div class="student-info-badge bg-light text-dark">
                                                    <i class="fas fa-id-card text-primary"></i> {{ $student->student_id }}
                                                </div>
                                                <div class="student-info-badge bg-light text-dark">
                                                    <i class="fas fa-id-badge text-info"></i> LRN: {{ $student->lrn ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mb-2">
                                            <div class="student-info-badge me-1 mb-1">
                                                <i class="fas fa-venus-mars text-primary"></i> {{ $student->gender }}
                                            </div>
                                            @if($student->birth_date)
                                            <div class="student-info-badge me-1 mb-1">
                                                <i class="fas fa-birthday-cake text-info"></i> {{ \Carbon\Carbon::parse($student->birth_date)->age }} yrs
                                            </div>
                                            @endif
                                        </div>
                                        <div class="mb-2">
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-user-shield text-success me-2 small"></i>
                                                <span class="small">{{ $student->guardian_name ?: 'Guardian not specified' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="student-footer">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary me-1 btn-action" title="View Student">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-sm btn-outline-warning me-1 btn-action" title="Edit Student">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-action" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $student->id }}" title="Delete Student">
                                                <i class="fas fa-trash"></i>
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
                                                <div class="text-center mb-3">
                                                    <i class="fas fa-exclamation-triangle text-warning fa-4x mb-3"></i>
                                                    <h5>Are you sure you want to delete this student?</h5>
                                                </div>
                                                <div class="alert alert-warning">
                                                    <p class="mb-0"><strong>{{ $student->full_name ?? $student->first_name . ' ' . $student->last_name }}</strong> will be permanently removed from your records.</p>
                                                </div>
                                                <p class="text-danger small"><i class="fas fa-info-circle me-1"></i> This action cannot be undone.</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <form action="{{ route('teacher.students.destroy', $student->id) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger">Yes, Delete Student</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endforeach

        <!-- No Results Message (hidden by default) -->
        <div id="noResults" class="text-center py-5 d-none">
            <div class="card shadow-sm border-0 p-4">
                <div class="card-body">
                    <i class="fas fa-search fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No Students Found</h4>
                    <p>Try adjusting your search or filter criteria.</p>
                    <button id="resetFilters" class="btn btn-primary mt-2">
                        <i class="fas fa-undo me-1"></i> Reset Filters
                    </button>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <div class="card shadow-sm border-0 p-5">
                <div class="card-body">
                    <i class="fas fa-user-graduate text-muted mb-3" style="font-size: 4rem;"></i>
                    <h3 class="text-muted mb-3">No Students Found</h3>
                    <p class="mb-4">You haven't added any students to your sections yet.</p>
                    <a href="{{ route('teacher.students.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Add Your First Student
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Apply CSS fixes for any visual glitches
        document.querySelectorAll('.counter-card').forEach(card => {
            card.style.height = '100%';
        });
        
        const studentSearch = document.getElementById('studentSearch');
        const gradeFilter = document.getElementById('gradeFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const genderSectionFilter = document.getElementById('genderSectionFilter');
        const resetFiltersBtn = document.getElementById('resetFilters');
        const noResults = document.getElementById('noResults');
        const studentItems = document.querySelectorAll('.student-item');
        const studentSections = document.querySelectorAll('.student-section');
        const gradeLevelHeaders = document.querySelectorAll('.grade-level-header');
        const gradeLevelContainers = document.querySelectorAll('.grade-level-container');
        const sectionHeaders = document.querySelectorAll('.section-header');
        const toggleGradeBtns = document.querySelectorAll('.toggle-grade-btn');

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
                const grade = header.querySelector('.toggle-grade-btn').getAttribute('data-grade');
                const hasVisibleStudents = visibleGrades.has(grade);
                header.classList.toggle('d-none', !hasVisibleStudents);
            });
            
            // Show/hide grade level containers
            gradeLevelContainers.forEach(container => {
                const grade = container.getAttribute('data-grade');
                const hasVisibleStudents = visibleGrades.has(grade);
                container.classList.toggle('d-none', !hasVisibleStudents);
            });
            
            // Show/hide no results message
            noResults.classList.toggle('d-none', visibleCount > 0);
        }

        // Function to update gender distribution based on selected section
        function updateGenderDistribution() {
            const selectedSectionId = genderSectionFilter.value;
            
            // Make an AJAX request to get gender data for the selected section
            fetch('/teacher/students/gender-distribution?section_id=' + selectedSectionId)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Create the gender stats HTML
                    let html = `
                        <div class="gender-stat d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-male text-primary me-2"></i> Male Students
                            </div>
                            <div class="fw-bold">${data.male_count} (${data.male_percentage}%)</div>
                        </div>
                        
                        <div class="gender-stat d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-female text-danger me-2"></i> Female Students
                            </div>
                            <div class="fw-bold">${data.female_count} (${data.female_percentage}%)</div>
                        </div>
                        
                        <div class="progress mt-3" style="height: 10px;">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: ${data.male_percentage}%" aria-valuenow="${data.male_percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                            <div class="progress-bar bg-danger" role="progressbar" style="width: ${data.female_percentage}%" aria-valuenow="${data.female_percentage}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    `;
                    
                    // Update the gender stats container
                    document.getElementById('gender-stats-container').innerHTML = html;
                })
                .catch(error => {
                    console.error('Error fetching gender distribution:', error);
                    // Display error message in the container
                    document.getElementById('gender-stats-container').innerHTML = `
                        <div class="alert alert-danger">
                            <p>Unable to load gender distribution data.</p>
                        </div>
                    `;
                });
        }

        // Call updateGenderDistribution when page loads
        updateGenderDistribution();

        // Toggle grade level sections
        toggleGradeBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const grade = this.getAttribute('data-grade');
                const container = document.querySelector(`.grade-level-container[data-grade="${grade}"]`);
                const icon = this.querySelector('i');
                
                container.classList.toggle('d-none');
                icon.classList.toggle('fa-chevron-down');
                icon.classList.toggle('fa-chevron-up');
            });
        });

        // Add event listeners
        studentSearch.addEventListener('input', filterStudents);
        gradeFilter.addEventListener('change', filterStudents);
        sectionFilter.addEventListener('change', filterStudents);
        genderSectionFilter.addEventListener('change', updateGenderDistribution);
        
        // Reset filters
        resetFiltersBtn.addEventListener('click', function() {
            studentSearch.value = '';
            gradeFilter.value = '';
            sectionFilter.value = '';
            genderSectionFilter.value = 'all';
            filterStudents();
            updateGenderDistribution();
        });
    });
</script>
@endpush
@endsection 