@php
// Define the transmutation function based on the selected table
function getTransmutedGrade($initialGrade, $tableType) {
    if ($initialGrade < 0) return 60;
    
    // Table 1: DepEd Transmutation Table (formerly Table 4)
    if ($tableType == 1) {
        if ($initialGrade == 100) return 100;
        elseif ($initialGrade >= 98.40) return 99;
        elseif ($initialGrade >= 96.80) return 98;
        elseif ($initialGrade >= 95.20) return 97;
        elseif ($initialGrade >= 93.60) return 96;
        elseif ($initialGrade >= 92.00) return 95;
        elseif ($initialGrade >= 90.40) return 94;
        elseif ($initialGrade >= 88.80) return 93;
        elseif ($initialGrade >= 87.20) return 92;
        elseif ($initialGrade >= 85.60) return 91;
        elseif ($initialGrade >= 84.00) return 90;
        elseif ($initialGrade >= 82.40) return 89;
        elseif ($initialGrade >= 80.80) return 88;
        elseif ($initialGrade >= 79.20) return 87;
        elseif ($initialGrade >= 77.60) return 86;
        elseif ($initialGrade >= 76.00) return 85;
        elseif ($initialGrade >= 74.40) return 84;
        elseif ($initialGrade >= 72.80) return 83;
        elseif ($initialGrade >= 71.20) return 82;
        elseif ($initialGrade >= 69.60) return 81;
        elseif ($initialGrade >= 68.00) return 80;
        elseif ($initialGrade >= 66.40) return 79;
        elseif ($initialGrade >= 64.80) return 78;
        elseif ($initialGrade >= 63.20) return 77;
        elseif ($initialGrade >= 61.60) return 76;
        elseif ($initialGrade >= 60.00) return 75;
        elseif ($initialGrade >= 56.00) return 74;
        elseif ($initialGrade >= 52.00) return 73;
        elseif ($initialGrade >= 48.00) return 72;
        elseif ($initialGrade >= 44.00) return 71;
        elseif ($initialGrade >= 40.00) return 70;
        elseif ($initialGrade >= 36.00) return 69;
        elseif ($initialGrade >= 32.00) return 68;
        elseif ($initialGrade >= 28.00) return 67;
        elseif ($initialGrade >= 24.00) return 66;
        elseif ($initialGrade >= 20.00) return 65;
        elseif ($initialGrade >= 16.00) return 64;
        elseif ($initialGrade >= 12.00) return 63;
        elseif ($initialGrade >= 8.00) return 62;
        elseif ($initialGrade >= 4.00) return 61;
        else return 60;
    }
    // Table 2: Grades 1-10 and Non-Core Subjects of TVL, Sports, and Arts & Design (formerly Table 1)
    elseif ($tableType == 2) {
        if ($initialGrade >= 80) return 100;
        elseif ($initialGrade >= 78.40) return 99;
        elseif ($initialGrade >= 76.80) return 98;
        elseif ($initialGrade >= 75.20) return 97;
        elseif ($initialGrade >= 73.60) return 96;
        elseif ($initialGrade >= 72.00) return 95;
        elseif ($initialGrade >= 70.40) return 94;
        elseif ($initialGrade >= 68.80) return 93;
        elseif ($initialGrade >= 67.20) return 92;
        elseif ($initialGrade >= 65.60) return 91;
        elseif ($initialGrade >= 64.00) return 90;
        elseif ($initialGrade >= 62.40) return 89;
        elseif ($initialGrade >= 60.80) return 88;
        elseif ($initialGrade >= 59.20) return 87;
        elseif ($initialGrade >= 57.60) return 86;
        elseif ($initialGrade >= 56.00) return 85;
        elseif ($initialGrade >= 54.40) return 84;
        elseif ($initialGrade >= 52.80) return 83;
        elseif ($initialGrade >= 51.20) return 82;
        elseif ($initialGrade >= 49.60) return 81;
        elseif ($initialGrade >= 48.00) return 80;
        elseif ($initialGrade >= 46.40) return 79;
        elseif ($initialGrade >= 44.80) return 78;
        elseif ($initialGrade >= 43.20) return 77;
        elseif ($initialGrade >= 41.60) return 76;
        elseif ($initialGrade >= 40.00) return 75;
        elseif ($initialGrade >= 38.40) return 74;
        elseif ($initialGrade >= 36.80) return 73;
        elseif ($initialGrade >= 35.20) return 72;
        elseif ($initialGrade >= 33.60) return 71;
        elseif ($initialGrade >= 32.00) return 70;
        elseif ($initialGrade >= 30.40) return 69;
        elseif ($initialGrade >= 28.80) return 68;
        elseif ($initialGrade >= 27.20) return 67;
        elseif ($initialGrade >= 25.60) return 66;
        elseif ($initialGrade >= 24.00) return 65;
        elseif ($initialGrade >= 22.40) return 64;
        elseif ($initialGrade >= 20.80) return 63;
        elseif ($initialGrade >= 19.20) return 62;
        elseif ($initialGrade >= 17.60) return 61;
        else return 60;
    }
    // Table 3: For SHS Core Subjects and Work Immersion/Research/Business Enterprise/Performance (formerly Table 2)
    elseif ($tableType == 3) {
        if ($initialGrade >= 100) return 100;
        elseif ($initialGrade >= 73.80) return 99;
        elseif ($initialGrade >= 72.60) return 98;
        elseif ($initialGrade >= 71.40) return 97;
        elseif ($initialGrade >= 70.20) return 96;
        elseif ($initialGrade >= 69.00) return 95;
        elseif ($initialGrade >= 67.80) return 94;
        elseif ($initialGrade >= 66.60) return 93;
        elseif ($initialGrade >= 65.40) return 92;
        elseif ($initialGrade >= 64.20) return 91;
        elseif ($initialGrade >= 63.00) return 90;
        elseif ($initialGrade >= 61.80) return 89;
        elseif ($initialGrade >= 60.60) return 88;
        elseif ($initialGrade >= 59.40) return 87;
        elseif ($initialGrade >= 58.20) return 86;
        elseif ($initialGrade >= 57.00) return 85;
        elseif ($initialGrade >= 55.80) return 84;
        elseif ($initialGrade >= 54.60) return 83;
        elseif ($initialGrade >= 53.40) return 82;
        elseif ($initialGrade >= 52.20) return 81;
        elseif ($initialGrade >= 51.00) return 80;
        elseif ($initialGrade >= 49.80) return 79;
        elseif ($initialGrade >= 48.60) return 78;
        elseif ($initialGrade >= 47.40) return 77;
        elseif ($initialGrade >= 46.20) return 76;
        elseif ($initialGrade >= 45.00) return 75;
        elseif ($initialGrade >= 43.80) return 74;
        elseif ($initialGrade >= 42.60) return 73;
        elseif ($initialGrade >= 41.40) return 72;
        elseif ($initialGrade >= 40.20) return 71;
        elseif ($initialGrade >= 39.00) return 70;
        elseif ($initialGrade >= 37.80) return 69;
        elseif ($initialGrade >= 36.60) return 68;
        elseif ($initialGrade >= 35.40) return 67;
        elseif ($initialGrade >= 34.20) return 66;
        elseif ($initialGrade >= 33.00) return 65;
        elseif ($initialGrade >= 31.80) return 64;
        elseif ($initialGrade >= 30.60) return 63;
        elseif ($initialGrade >= 29.40) return 62;
        elseif ($initialGrade >= 28.20) return 61;
        else return 60;
    }
    // Table 4: For all other SHS Subjects in the Academic Track (formerly Table 3)
    elseif ($tableType == 4) {
        if ($initialGrade >= 100) return 100;
        elseif ($initialGrade >= 68.90) return 99;
        elseif ($initialGrade >= 67.80) return 98;
        elseif ($initialGrade >= 66.70) return 97;
        elseif ($initialGrade >= 65.60) return 96;
        elseif ($initialGrade >= 64.50) return 95;
        elseif ($initialGrade >= 63.40) return 94;
        elseif ($initialGrade >= 62.30) return 93;
        elseif ($initialGrade >= 61.20) return 92;
        elseif ($initialGrade >= 60.10) return 91;
        elseif ($initialGrade >= 59.00) return 90;
        elseif ($initialGrade >= 57.80) return 89;
        elseif ($initialGrade >= 56.70) return 88;
        elseif ($initialGrade >= 55.60) return 87;
        elseif ($initialGrade >= 54.50) return 86;
        elseif ($initialGrade >= 53.40) return 85;
        elseif ($initialGrade >= 52.30) return 84;
        elseif ($initialGrade >= 51.20) return 83;
        elseif ($initialGrade >= 50.10) return 82;
        elseif ($initialGrade >= 49.00) return 81;
        elseif ($initialGrade >= 47.90) return 80;
        elseif ($initialGrade >= 46.80) return 79;
        elseif ($initialGrade >= 45.70) return 78;
        elseif ($initialGrade >= 44.60) return 77;
        elseif ($initialGrade >= 43.50) return 76;
        elseif ($initialGrade >= 42.40) return 75;
        elseif ($initialGrade >= 41.30) return 74;
        elseif ($initialGrade >= 40.20) return 73;
        elseif ($initialGrade >= 39.10) return 72;
        elseif ($initialGrade >= 34.00) return 71;
        elseif ($initialGrade >= 28.90) return 70;
        elseif ($initialGrade >= 23.80) return 69;
        elseif ($initialGrade >= 19.70) return 68;
        elseif ($initialGrade >= 17.60) return 67;
        elseif ($initialGrade >= 15.50) return 66;
        elseif ($initialGrade >= 13.40) return 65;
        elseif ($initialGrade >= 11.30) return 64;
        elseif ($initialGrade >= 9.20) return 63;
        elseif ($initialGrade >= 7.10) return 62;
        elseif ($initialGrade >= 5.00) return 61;
        else return 60;
    }
    else {
        // Default to table 1 (DepEd) if an invalid table type is specified
        if ($initialGrade == 100) return 100;
        elseif ($initialGrade >= 98.40) return 99;
        elseif ($initialGrade >= 96.80) return 98;
        elseif ($initialGrade >= 95.20) return 97;
        elseif ($initialGrade >= 93.60) return 96;
        elseif ($initialGrade >= 92.00) return 95;
        elseif ($initialGrade >= 90.40) return 94;
        elseif ($initialGrade >= 88.80) return 93;
        elseif ($initialGrade >= 87.20) return 92;
        elseif ($initialGrade >= 85.60) return 91;
        elseif ($initialGrade >= 84.00) return 90;
        elseif ($initialGrade >= 82.40) return 89;
        elseif ($initialGrade >= 80.80) return 88;
        elseif ($initialGrade >= 79.20) return 87;
        elseif ($initialGrade >= 77.60) return 86;
        elseif ($initialGrade >= 76.00) return 85;
        elseif ($initialGrade >= 74.40) return 84;
        elseif ($initialGrade >= 72.80) return 83;
        elseif ($initialGrade >= 71.20) return 82;
        elseif ($initialGrade >= 69.60) return 81;
        elseif ($initialGrade >= 68.00) return 80;
        elseif ($initialGrade >= 66.40) return 79;
        elseif ($initialGrade >= 64.80) return 78;
        elseif ($initialGrade >= 63.20) return 77;
        elseif ($initialGrade >= 61.60) return 76;
        elseif ($initialGrade >= 60.00) return 75;
        elseif ($initialGrade >= 56.00) return 74;
        elseif ($initialGrade >= 52.00) return 73;
        elseif ($initialGrade >= 48.00) return 72;
        elseif ($initialGrade >= 44.00) return 71;
        elseif ($initialGrade >= 40.00) return 70;
        elseif ($initialGrade >= 36.00) return 69;
        elseif ($initialGrade >= 32.00) return 68;
        elseif ($initialGrade >= 28.00) return 67;
        elseif ($initialGrade >= 24.00) return 66;
        elseif ($initialGrade >= 20.00) return 65;
        elseif ($initialGrade >= 16.00) return 64;
        elseif ($initialGrade >= 12.00) return 63;
        elseif ($initialGrade >= 8.00) return 62;
        elseif ($initialGrade >= 4.00) return 61;
        else return 60;
    }
}
@endphp

@extends('layouts.app')

@push('styles')
<style>
    .profile-container {
        padding: 0;
        background-color: #fff;
        max-width: 100%;
    }
    
    .profile-header {
        background-color: #0d6efd;
        padding: 30px 0 15px;
        color: white;
        position: relative;
    }
    
    .profile-stats {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        padding: 8px 0;
    }
    
    .stat-item {
        text-align: center;
        padding: 6px 8px;
    }
    
    .stat-value {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 2px;
    }
    
    .stat-label {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .student-badge {
        display: inline-block;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
        margin-right: 6px;
        color: #fff;
    }
    
    .section-heading {
        font-size: 1rem;
        font-weight: 600;
        padding-bottom: 0.4rem;
        margin-bottom: 0.75rem;
        border-bottom: 1px solid #e9ecef;
    }
    
    .info-label {
        color: #6c757d;
        font-size: 0.8rem;
        margin-bottom: 0.1rem;
    }
    
    .info-value {
        font-weight: 500;
        margin-bottom: 0.75rem;
    }
    
    .info-card {
        height: 100%;
        border-radius: 0.5rem;
        border: none;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .card-body {
        padding: 0.75rem;
    }
    
    .gradient-blue {
        background: linear-gradient(to right, #0062cc, #0d6efd);
    }
    
    .bg-soft-blue {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
    }
    
    .bg-soft-green {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
    }
    
    .bg-soft-orange {
        background-color: rgba(255, 153, 0, 0.1);
        color: #fd7e14;
    }
    
    .attendance-dot {
        display: inline-block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        margin-right: 4px;
    }
    
    .table-grades th, 
    .table-grades td {
        padding: 0.4rem 0.5rem;
        vertical-align: middle;
        font-size: 0.9rem;
    }
    
    .btn-outline-primary {
        border-color: #0d6efd;
        color: #0d6efd;
    }
    
    .btn-outline-primary:hover {
        background-color: #0d6efd;
        color: white;
    }
    
    .student-details .list-group-item {
        border-left: none;
        border-right: none;
        padding: 0.4rem 0;
    }
    
    .student-details .list-group-item:first-child {
        border-top: none;
    }
    
    .student-details .list-group-item:last-child {
        border-bottom: none;
    }
    
    .container {
        width: auto;
        max-width: 100%;
        padding-left: 10px;
        padding-right: 10px;
    }
    
    @media (min-width: 992px) {
        .container {
            max-width: 960px;
        }
    }
    
    @media (min-width: 1200px) {
        .container {
            max-width: 1140px;
        }
    }
    
    /* Attendance cards fixes */
    .row.mb-4 .card {
        margin-bottom: 0.5rem;
    }
    
    .row.mb-4 .card .card-body {
        padding: 0.5rem;
    }
    
    /* Smaller icons */
    .row.mb-4 .card .card-body i {
        font-size: 1.2rem !important;
    }
    
    .row.mb-4 .card .card-body h4 {
        font-size: 1.2rem;
        margin-bottom: 0;
    }
    
    /* Reduce spacing in recent attendance table */
    .table.table-sm th,
    .table.table-sm td {
        padding: 0.3rem 0.5rem;
        font-size: 0.85rem;
    }
    
    /* Fix for the progress bar linter error - single line */
    .attendance-progress-bar {
        width: attr(data-width);
    }
</style>
@endpush

@section('content')
<div class="container-fluid profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <nav aria-label="breadcrumb" class="text-white">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('teacher.dashboard') }}" class="text-white">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('teacher.students.index') }}" class="text-white">Students</a></li>
                        <li class="breadcrumb-item active text-white">Student Profile</li>
                    </ol>
                </nav>
                <div>
                    <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-edit me-1"></i> Edit
                    </a>
                    <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back
                    </a>
                </div>
            </div>
            
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="mb-1">{{ $student->full_name }}</h1>
                    <div class="mb-3">
                        <span class="text-white-50">Student ID: </span>
                        <span class="fw-semibold">{{ $student->student_id }}</span>
                    </div>
                    <div>
                        <span class="student-badge bg-primary">
                            <i class="fas fa-door-open me-1"></i> {{ $student->section->name ?? 'No Section' }}
                        </span>
                        <span class="student-badge" style="background-color: #198754;">
                            <i class="fas fa-layer-group me-1"></i> Grade {{ $student->section->grade_level ?? 'Unknown' }}
                        </span>
                    </div>
                </div>
                <div class="col-lg-6 text-lg-end mt-4 mt-lg-0">
                    <div class="d-inline-block text-start">
                        <div class="mb-2">
                            <i class="fas fa-calendar-alt me-2"></i> 
                            School Year: <span class="fw-semibold">{{ $student->section->school_year ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <i class="fas fa-chalkboard-teacher me-2"></i>
                            Adviser: <span class="fw-semibold">{{ $student->section->adviser->name ?? 'No adviser assigned' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stats Row -->
    <div class="profile-stats">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-value text-primary">{{ $student->gender }}</div>
                        <div class="stat-label">Gender</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-value text-success">{{ $student->birth_date ? $student->birth_date->age : 'N/A' }}</div>
                        <div class="stat-label">Age</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-value text-info">{{ $student->grades->count() }}</div>
                        <div class="stat-label">Grades Recorded</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        @php
                            $totalDays = $student->attendances->count();
                            $presentDays = $student->attendances->where('status', 'present')->count();
                            $attendanceRate = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100) : 'N/A';
                        @endphp
                        <div class="stat-value {{ is_numeric($attendanceRate) ? ($attendanceRate >= 80 ? 'text-success' : ($attendanceRate >= 60 ? 'text-warning' : 'text-danger')) : 'text-muted' }}">
                            {{ is_numeric($attendanceRate) ? $attendanceRate . '%' : $attendanceRate }}
                        </div>
                        <div class="stat-label">Attendance Rate</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Content Area -->
    <div class="container py-4">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <!-- Left Column: Personal & Guardian Info -->
            <div class="col-lg-4 mb-4">
                <!-- Personal Information -->
                <div class="card info-card mb-4">
                    <div class="card-body">
                        <h5 class="section-heading">
                            <i class="fas fa-user text-primary me-2"></i> Personal Information
                        </h5>

                        <ul class="list-group student-details">
                            <li class="list-group-item">
                                <div class="info-label">Full Name</div>
                                <div class="info-value">{{ $student->full_name }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Student ID</div>
                                <div class="info-value">{{ $student->student_id }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Learner Reference Number (LRN)</div>
                                <div class="info-value">{{ $student->lrn ?? 'Not provided' }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Gender</div>
                                <div class="info-value">
                                    <i class="fas {{ $student->gender == 'Male' ? 'fa-mars text-primary' : 'fa-venus text-danger' }} me-2"></i>
                                    {{ $student->gender }}
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Birth Date</div>
                                <div class="info-value">
                                    <i class="fas fa-calendar-day text-info me-2"></i>
                                    {{ $student->birth_date ? $student->birth_date->format('F d, Y') : 'N/A' }}
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Age</div>
                                <div class="info-value">{{ $student->birth_date ? $student->birth_date->age . ' years old' : 'N/A' }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Address</div>
                                <div class="info-value">
                                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                    {{ $student->address ?: 'No address provided' }}
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Guardian Information -->
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="section-heading">
                            <i class="fas fa-user-shield text-success me-2"></i> Guardian Information
                        </h5>

                        <ul class="list-group student-details">
                            <li class="list-group-item">
                                <div class="info-label">Guardian Name</div>
                                <div class="info-value">{{ $student->guardian_name ?: 'Not provided' }}</div>
                            </li>
                            <li class="list-group-item">
                                <div class="info-label">Contact Number</div>
                                <div class="info-value">{{ $student->guardian_contact ?: 'Not provided' }}</div>
                            </li>
                        </ul>

                        @if($student->guardian_contact)
                            <div class="mt-3">
                                <a href="tel:{{ $student->guardian_contact }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-phone me-1"></i> Contact Guardian
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Right Column: Academic & Attendance -->
            <div class="col-lg-8">
                <!-- Academic Performance -->
                <div class="card info-card mb-4">
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="fas fa-graduation-cap text-primary me-2"></i> Academic Performance
                            </h5>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <label for="transmutation_table" class="form-label mb-0 me-2">Transmutation Table:</label>
                                    <select class="form-select form-select-sm" id="transmutation_table" name="transmutation_table" onchange="updateTransmutedGrades(this.value)">
                                        <option value="1" {{ $selectedTransmutationTable == 1 ? 'selected' : '' }}>Table 1: DepEd Transmutation</option>
                                        <option value="2" {{ $selectedTransmutationTable == 2 ? 'selected' : '' }}>Table 2: Grades 1-10 & Non-Core TVL</option>
                                        <option value="3" {{ $selectedTransmutationTable == 3 ? 'selected' : '' }}>Table 3: SHS Core & Work Immersion</option>
                                        <option value="4" {{ $selectedTransmutationTable == 4 ? 'selected' : '' }}>Table 4: All other SHS Subjects</option>
                                    </select>
                                </div>
                                <div class="btn-group" role="group" aria-label="View switcher">
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="showDashboard">
                                        <i class="fas fa-chart-bar me-1"></i> Dashboard
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" id="showTable">
                                        <i class="fas fa-table me-1"></i> Table
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @if($student->grades->count() > 0)
                            @php
                                // Get all subject IDs from the student's section to prevent duplicates
                                $sectionSubjectIdsMap = [];
                                foreach ($student->section->subjects as $subject) {
                                    $sectionSubjectIdsMap[$subject->id] = true;
                                }
                                
                                // Group grades by subject first, using only subjects from the student's section
                                $gradesBySubject = [];
                                $processedSubjects = [];
                                
                                foreach ($student->grades as $grade) {
                                    $subject = $grade->subject;
                                    if (!$subject) continue;
                                    
                                    // Skip subjects not in the student's section or already processed
                                    $subjectId = $subject->id;
                                    if (!isset($sectionSubjectIdsMap[$subjectId])) continue;
                                    
                                    // Create a unique key for this subject to avoid duplicates
                                    $subjectKey = $subjectId;
                                    
                                    if (!isset($gradesBySubject[$subjectKey])) {
                                        $gradesBySubject[$subjectKey] = [
                                            'name' => $subject->name,
                                            'code' => $subject->code,
                                            'written_works' => [],
                                            'performance_tasks' => [],
                                            'quarterly_assessments' => [],
                                            'subject_id' => $subjectId,
                                            'config' => \App\Models\GradeConfiguration::where('subject_id', $subjectId)->first()
                                        ];
                                    }
                                    
                                    // Categorize grades by type
                                    if ($grade->grade_type == 'written_work') {
                                        $gradesBySubject[$subjectKey]['written_works'][] = $grade;
                                    } elseif ($grade->grade_type == 'performance_task') {
                                        $gradesBySubject[$subjectKey]['performance_tasks'][] = $grade;
                                    } elseif ($grade->grade_type == 'quarterly') {
                                        $gradesBySubject[$subjectKey]['quarterly_assessments'][] = $grade;
                                    }
                                }
                                
                                // Calculate each subject's individual grades
                                $subjectFinalGrades = [];
                                $totalSubjectGrade = 0;
                                $validSubjectCount = 0;
                                
                                foreach ($gradesBySubject as $subjectId => &$subjectData) {
                                    // Get the subject's grade configuration
                                    $gradeConfig = $subjectData['config'];
                                    
                                    $writtenWorkPercentage = $gradeConfig ? $gradeConfig->written_work_percentage : 25;
                                    $performanceTaskPercentage = $gradeConfig ? $gradeConfig->performance_task_percentage : 50;
                                    $quarterlyAssessmentPercentage = $gradeConfig ? $gradeConfig->quarterly_assessment_percentage : 25;
                                    
                                    // Calculate averages for each type
                                    $writtenWorksTotal = 0;
                                    $writtenWorksMaxTotal = 0;
                                    foreach ($subjectData['written_works'] as $grade) {
                                        $writtenWorksTotal += $grade->score;
                                        $writtenWorksMaxTotal += $grade->max_score;
                                    }
                                    $writtenWorksAvg = $writtenWorksMaxTotal > 0 ? ($writtenWorksTotal / $writtenWorksMaxTotal) * 100 : 0;
                                    
                                    $performanceTasksTotal = 0;
                                    $performanceTasksMaxTotal = 0;
                                    foreach ($subjectData['performance_tasks'] as $grade) {
                                        $performanceTasksTotal += $grade->score;
                                        $performanceTasksMaxTotal += $grade->max_score;
                                    }
                                    $performanceTasksAvg = $performanceTasksMaxTotal > 0 ? ($performanceTasksTotal / $performanceTasksMaxTotal) * 100 : 0;
                                    
                                    $quarterlyTotal = 0;
                                    $quarterlyMaxTotal = 0;
                                    foreach ($subjectData['quarterly_assessments'] as $grade) {
                                        $quarterlyTotal += $grade->score;
                                        $quarterlyMaxTotal += $grade->max_score;
                                    }
                                    $quarterlyAvg = $quarterlyMaxTotal > 0 ? ($quarterlyTotal / $quarterlyMaxTotal) * 100 : 0;
                                    
                                    // Store component averages in the subject data
                                    $subjectData['written_works_avg'] = $writtenWorksAvg;
                                    $subjectData['performance_tasks_avg'] = $performanceTasksAvg;
                                    $subjectData['quarterly_avg'] = $quarterlyAvg;
                                    $subjectData['written_work_percentage'] = $writtenWorkPercentage;
                                    $subjectData['performance_task_percentage'] = $performanceTaskPercentage;
                                    $subjectData['quarterly_assessment_percentage'] = $quarterlyAssessmentPercentage;
                                    
                                    // Calculate weighted final grade for this subject
                                    $subjectFinalGrade = 0;
                                    $hasAnyComponents = false;
                                    
                                    if ($writtenWorksAvg > 0) {
                                        $subjectFinalGrade += ($writtenWorksAvg * ($writtenWorkPercentage / 100));
                                        $hasAnyComponents = true;
                                    }
                                    if ($performanceTasksAvg > 0) {
                                        $subjectFinalGrade += ($performanceTasksAvg * ($performanceTaskPercentage / 100));
                                        $hasAnyComponents = true;
                                    }
                                    if ($quarterlyAvg > 0) {
                                        $subjectFinalGrade += ($quarterlyAvg * ($quarterlyAssessmentPercentage / 100));
                                        $hasAnyComponents = true;
                                    }
                                    
                                    $subjectData['avg_score'] = $hasAnyComponents ? round($subjectFinalGrade, 1) : 0;
                                    
                                    if ($hasAnyComponents) {
                                        $subjectFinalGrades[$subjectId] = $subjectData['avg_score'];
                                        $totalSubjectGrade += $subjectData['avg_score'];
                                        $validSubjectCount++;
                                    }
                                }
                                
                                // Calculate the overall average
                                $avgGrade = $validSubjectCount > 0 ? round($totalSubjectGrade / $validSubjectCount, 1) : 0;
                                
                                // For display of grade components in the dashboard, use the first subject as sample
                                // or average all subjects if there are multiple
                                $firstSubjectData = reset($gradesBySubject);
                                $writtenWorksAvg = 0;
                                $performanceTasksAvg = 0; 
                                $quarterlyScore = 0;
                                $writtenWorkPercentage = 25;
                                $performanceTaskPercentage = 50;
                                $quarterlyAssessmentPercentage = 25;
                                
                                if ($firstSubjectData) {
                                    $writtenWorksAvg = $firstSubjectData['written_works_avg'];
                                    $performanceTasksAvg = $firstSubjectData['performance_tasks_avg'];
                                    $quarterlyScore = $firstSubjectData['quarterly_avg'];
                                    $writtenWorkPercentage = $firstSubjectData['written_work_percentage'];
                                    $performanceTaskPercentage = $firstSubjectData['performance_task_percentage'];
                                    $quarterlyAssessmentPercentage = $firstSubjectData['quarterly_assessment_percentage'];
                                }
                                
                                // Sort subjects by average score (highest first)
                                uasort($gradesBySubject, function($a, $b) {
                                    return $b['avg_score'] <=> $a['avg_score'];
                                });

                                // Calculate grade color and status
                                $gradeColor = 'success';
                                $gradeStatus = 'Excellent';
                                
                                if ($avgGrade < 75) {
                                    $gradeColor = 'danger';
                                    $gradeStatus = 'Needs Improvement';
                                } elseif ($avgGrade < 80) {
                                    $gradeColor = 'warning';
                                    $gradeStatus = 'Satisfactory';
                                } elseif ($avgGrade < 90) {
                                    $gradeColor = 'info';
                                    $gradeStatus = 'Good';
                                }
                                
                                // Get transmuted grade
                                $transmutedGrade = getTransmutedGrade($avgGrade, $selectedTransmutationTable);
                            @endphp
                            
                            <!-- Navigation Controls -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-group mb-0">
                                    <label for="termFilter" class="form-label mb-0 me-2">Term:</label>
                                    <select class="form-select form-select-sm d-inline-block w-auto" id="termFilter">
                                        <option value="all">All Terms</option>
                                        @php
                                            // Get all terms from grades
                                            $availableTerms = $student->grades->pluck('term')->unique()->values()->all();
                                        @endphp
                                        @foreach($availableTerms as $term)
                                            <option value="{{ $term }}">{{ $term }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <a href="{{ route('teacher.grades.index', ['student_id' => $student->id]) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-list me-1"></i> All Grades
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Dashboard View -->
                            <div id="academicDashboard">
                                <!-- Overall Grade Summary Card -->
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-md-4 text-center border-end">
                                                <h6 class="text-muted mb-1">Overall Grade</h6>
                                                <div class="d-flex align-items-baseline justify-content-center">
                                                    <div class="display-4 fw-bold text-{{ $gradeColor }} me-2">{{ $transmutedGrade }}</div>
                                                    <div class="text-muted">({{ $avgGrade }}%)</div>
                                                </div>
                                                <span class="badge bg-{{ $gradeColor }} px-3 py-2">{{ $gradeStatus }}</span>
                                            </div>
                                            <div class="col-md-8">
                                                <h6 class="text-muted mb-3">Grade Components</h6>
                                                
                                                <!-- Written Works -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                                                        <span class="fw-medium">
                                                            <i class="fas fa-pen text-primary me-1"></i> Written Works
                                                        </span>
                                                        <span class="d-flex align-items-center">
                                                            <span class="fw-semibold me-2">{{ number_format($writtenWorksAvg, 1) }}%</span>
                                                            <small class="text-muted">({{ $writtenWorkPercentage }}%)</small>
                                                        </span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-primary" role="progressbar" 
                                                            style="width: {{ min($writtenWorksAvg, 100) }}%;" 
                                                            aria-valuenow="{{ $writtenWorksAvg }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Performance Tasks -->
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                                                        <span class="fw-medium">
                                                            <i class="fas fa-tasks text-success me-1"></i> Performance Tasks
                                                        </span>
                                                        <span class="d-flex align-items-center">
                                                            <span class="fw-semibold me-2">{{ number_format($performanceTasksAvg, 1) }}%</span>
                                                            <small class="text-muted">({{ $performanceTaskPercentage }}%)</small>
                                                        </span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                            style="width: {{ min($performanceTasksAvg, 100) }}%;" 
                                                            aria-valuenow="{{ $performanceTasksAvg }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Quarterly Assessments -->
                                                <div>
                                                    <div class="d-flex justify-content-between align-items-center mb-1 small">
                                                        <span class="fw-medium">
                                                            <i class="fas fa-file-alt text-warning me-1"></i> Quarterly Assessments
                                                        </span>
                                                        <span class="d-flex align-items-center">
                                                            <span class="fw-semibold me-2">{{ number_format($quarterlyScore, 1) }}%</span>
                                                            <small class="text-muted">({{ $quarterlyAssessmentPercentage }}%)</small>
                                                        </span>
                                                    </div>
                                                    <div class="progress" style="height: 8px;">
                                                        <div class="progress-bar bg-warning" role="progressbar" 
                                                            style="width: {{ min($quarterlyScore, 100) }}%;" 
                                                            style="width: {{ min($quarterlyScore, 100) }}%" 
                                                            aria-valuenow="{{ $quarterlyScore }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Subject Performance Cards -->
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-book text-primary me-1"></i> Subject Performance
                                </h6>
                                <div class="row">
                                    @foreach($gradesBySubject as $subjectId => $subjectData)
                                        <div class="col-lg-6 mb-3 subject-grade-item">
                                            <div class="card border-0 shadow-sm h-100">
                                                <div class="card-body">
                                                    @php
                                                        $subjectGradeColor = 'secondary';
                                                        if ($subjectData['avg_score'] >= 90) {
                                                            $subjectGradeColor = 'success';
                                                        } elseif ($subjectData['avg_score'] >= 80) {
                                                            $subjectGradeColor = 'primary';
                                                        } elseif ($subjectData['avg_score'] >= 75) {
                                                            $subjectGradeColor = 'info';
                                                        } elseif ($subjectData['avg_score'] > 0) {
                                                            $subjectGradeColor = 'danger';
                                                        }
                                                        
                                                        $subjectTransmutedGrade = getTransmutedGrade($subjectData['avg_score'], $selectedTransmutationTable);
                                                    @endphp
                                                    
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <div>
                                                            <h6 class="mb-0 fw-bold">{{ $subjectData['name'] }}</h6>
                                                            <small class="text-muted">{{ $subjectData['code'] }}</small>
                                                        </div>
                                                        <div class="text-end">
                                                            <h5 class="mb-0 fw-bold text-{{ $subjectGradeColor }}">{{ $subjectTransmutedGrade }}</h5>
                                                            <small class="text-muted">{{ $subjectData['avg_score'] }}%</small>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="progress mb-3" style="height: 6px;">
                                                        <div class="progress-bar bg-{{ $subjectGradeColor }}" role="progressbar" 
                                                            style="width: {{ min($subjectData['avg_score'], 100) }}%" 
                                                            aria-valuenow="{{ $subjectData['avg_score'] }}" 
                                                            aria-valuemin="0" 
                                                            aria-valuemax="100">
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Component breakdown -->
                                                    <div class="row g-2 mb-2">
                                                        <!-- Written Works -->
                                                        <div class="col-4">
                                                            <div class="p-2 border rounded text-center">
                                                                <div class="small fw-bold text-primary mb-1">Written</div>
                                                                <div>{{ number_format($subjectData['written_works_avg'], 1) }}%</div>
                                                                <div class="text-muted small">{{ $subjectData['written_work_percentage'] }}%</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Performance Tasks -->
                                                        <div class="col-4">
                                                            <div class="p-2 border rounded text-center">
                                                                <div class="small fw-bold text-success mb-1">Performance</div>
                                                                <div>{{ number_format($subjectData['performance_tasks_avg'], 1) }}%</div>
                                                                <div class="text-muted small">{{ $subjectData['performance_task_percentage'] }}%</div>
                                                            </div>
                                                        </div>
                                                        
                                                        <!-- Quarterly Assessment -->
                                                        <div class="col-4">
                                                            <div class="p-2 border rounded text-center">
                                                                <div class="small fw-bold text-warning mb-1">Quarterly</div>
                                                                <div>{{ number_format($subjectData['quarterly_avg'], 1) }}%</div>
                                                                <div class="text-muted small">{{ $subjectData['quarterly_assessment_percentage'] }}%</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="d-flex justify-content-between align-items-center small">
                                                        <span class="text-muted">
                                                            <i class="fas fa-clipboard-check me-1"></i> 
                                                            {{ count($subjectData['written_works']) + count($subjectData['performance_tasks']) + count($subjectData['quarterly_assessments']) }} assessments
                                                        </span>
                                                        @php
                                                            $status = 'Excellent';
                                                            if ($subjectData['avg_score'] < 75) {
                                                                $status = 'Failed';
                                                            } elseif ($subjectData['avg_score'] < 80) {
                                                                $status = 'Passed';
                                                            } elseif ($subjectData['avg_score'] < 90) {
                                                                $status = 'Good';
                                                            }
                                                        @endphp
                                                        <span class="badge bg-{{ $subjectGradeColor }}">{{ $status }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Table View -->
                            <div id="academicTableView" style="display: none;">
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover align-middle border">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Subject</th>
                                                <th>Term</th>
                                                <th>Assessment</th>
                                                <th>Type</th>
                                                <th class="text-center">Initial Grade</th>
                                                <th class="text-center">Transmuted Grade</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($student->grades as $grade)
                                                <tr class="grade-row" data-term="{{ $grade->term }}" data-initial-grade="{{ number_format($grade->score, 1) }}" data-grade-type="{{ $grade->grade_type }}">
                                                    <td>{{ $grade->subject->name ?? 'Unknown' }}</td>
                                                    <td>{{ $grade->term }}</td>
                                                    <td>{{ $grade->assessment_name }}</td>
                                                    <td>
                                                        <span class="badge {{ $grade->grade_type == 'written_work' ? 'bg-primary' : ($grade->grade_type == 'performance_task' ? 'bg-success' : 'bg-warning') }}">
                                                            {{ $grade->grade_type == 'written_work' ? 'Written Work' : ($grade->grade_type == 'performance_task' ? 'Performance Task' : 'Quarterly Assessment') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $initialGrade = ($grade->score / $grade->max_score) * 100;
                                                            $scorePercentage = number_format($initialGrade, 1);
                                                            $scoreClass = '';
                                                            
                                                            if ($scorePercentage < 60) {
                                                                $scoreClass = 'text-danger';
                                                            } elseif ($scorePercentage < 75) {
                                                                $scoreClass = 'text-warning';
                                                            } elseif ($scorePercentage < 90) {
                                                                $scoreClass = 'text-info';
                                                            } else {
                                                                $scoreClass = 'text-success';
                                                            }
                                                        @endphp
                                                        <span class="{{ $scoreClass }} fw-bold">{{ $scorePercentage }}%</span>
                                                    </td>
                                                    <td class="text-center">
                                                        @php
                                                            $initialGrade = ($grade->score / $grade->max_score) * 100;
                                                            $transmutedGrade = getTransmutedGrade($initialGrade, $selectedTransmutationTable);
                                                            
                                                            $transmutedClass = '';
                                                            if ($transmutedGrade < 75) {
                                                                $transmutedClass = 'bg-danger';
                                                            } elseif ($transmutedGrade < 80) {
                                                                $transmutedClass = 'bg-warning';
                                                            } elseif ($transmutedGrade < 90) {
                                                                $transmutedClass = 'bg-info';
                                                            } else {
                                                                $transmutedClass = 'bg-success';
                                                            }
                                                        @endphp
                                                        <span class="badge {{ $transmutedClass }} px-3 py-2 transmuted-grade-badge">
                                                            <span class="transmuted-grade">{{ $transmutedGrade }}</span>
                                                        </span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-4">
                                                        <div class="d-flex flex-column align-items-center">
                                                            <div class="mb-3">
                                                                <i class="fas fa-clipboard-list fa-3x text-muted"></i>
                                                            </div>
                                                            <h5 class="mb-2">No Grade Records</h5>
                                                            <p class="text-muted">There are no grade records for this student yet.</p>
                                                            <a href="{{ route('teacher.grades.create') }}" class="btn btn-sm btn-primary">
                                                                <i class="fas fa-plus me-2"></i> Record First Grade
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            <!-- Overall Performance Summary -->
                            <div class="card border-0 {{ $avgGrade >= 75 ? 'bg-success bg-opacity-10' : 'bg-danger bg-opacity-10' }} mt-4">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-3">
                                            <i class="fas {{ $avgGrade >= 75 ? 'fa-check-circle text-success' : 'fa-exclamation-triangle text-danger' }} fa-2x"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 {{ $avgGrade >= 75 ? 'text-success' : 'text-danger' }} fw-bold">
                                                Overall Performance: {{ $transmutedGrade }}
                                                <span class="text-muted fw-normal">(Initial: {{ $avgGrade }}%)</span>
                                            </h6>
                                            <p class="mb-0 small">
                                                Student is {{ $avgGrade >= 75 ? 'performing well and meeting academic standards.' : 'may need additional support to improve academic performance.' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-chart-bar text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">No Grade Data Available</h5>
                                <p class="text-muted mb-4">No grades have been recorded for this student yet.</p>
                                <a href="{{ route('teacher.grades.create', ['student_id' => $student->id]) }}" class="btn btn-primary">
                                    <i class="fas fa-plus-circle me-1"></i> Record First Grade
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Attendance Summary -->
                <div class="card info-card">
                    <div class="card-body">
                        <h5 class="section-heading">
                            <i class="fas fa-calendar-check text-success me-2"></i> Attendance Summary
                        </h5>

                        @if($student->attendances->count() > 0)
                            @php
                                $totalDays = $student->attendances->count();
                                $presentDays = $student->attendances->where('status', 'present')->count();
                                $absentDays = $student->attendances->where('status', 'absent')->count();
                                $lateDays = $student->attendances->where('status', 'late')->count();
                                $presentPercentage = ($totalDays > 0) ? round(($presentDays / $totalDays) * 100) : 0;
                            @endphp
                            
                            <div class="row mb-4">
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-calendar text-primary" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h4 class="mb-0">{{ $totalDays }}</h4>
                                            <div class="small text-muted">Total Days</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-check-circle text-success" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h4 class="mb-0">{{ $presentDays }}</h4>
                                            <div class="small text-muted">Present</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-times-circle text-danger" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h4 class="mb-0">{{ $absentDays }}</h4>
                                            <div class="small text-muted">Absent</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-6 mb-3">
                                    <div class="card h-100">
                                        <div class="card-body text-center py-3">
                                            <div class="mb-2">
                                                <i class="fas fa-clock text-warning" style="font-size: 1.5rem;"></i>
                                            </div>
                                            <h4 class="mb-0">{{ $lateDays }}</h4>
                                            <div class="small text-muted">Late</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label d-flex justify-content-between">
                                    <span>Attendance Rate</span>
                                    <span class="{{ $presentPercentage >= 80 ? 'text-success' : ($presentPercentage >= 60 ? 'text-warning' : 'text-danger') }}">
                                        {{ $presentPercentage }}%
                                    </span>
                                </label>
                                <div class="progress" style="height: 6px;"><div class="progress-bar bg-success" role="progressbar" style="width: {{ $presentPercentage }}%" aria-valuenow="{{ $presentPercentage }}" aria-valuemin="0" aria-valuemax="100"></div></div>
                            </div>

                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Recent Attendance</h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $recentAttendance = $student->attendances->take(5)->sortByDesc('date');
                                                @endphp
                                                
                                                @foreach($recentAttendance as $attendance)
                                                    <tr>
                                                        <td>{{ $attendance->date->format('M d, Y') }}</td>
                                                        <td>
                                                            @if($attendance->status == 'present')
                                                                <span class="badge bg-success">Present</span>
                                                            @elseif($attendance->status == 'absent')
                                                                <span class="badge bg-danger">Absent</span>
                                                            @elseif($attendance->status == 'late')
                                                                <span class="badge bg-warning">Late</span>
                                                            @else
                                                                <span class="badge bg-secondary">Unknown</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-calendar-times text-muted mb-3" style="font-size: 3rem;"></i>
                                <p>No attendance records have been recorded for this student yet.</p>
                                <a href="{{ route('teacher.attendances.index') }}" class="btn btn-sm btn-primary">
                                    Record Attendance
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle between dashboard and table view
        const dashboardBtn = document.getElementById('showDashboard');
        const tableBtn = document.getElementById('showTable');
        const dashboard = document.getElementById('academicDashboard');
        const tableView = document.getElementById('academicTableView');
        
        if (dashboardBtn && tableBtn && dashboard && tableView) {
            // Set initial active state
            dashboardBtn.classList.add('active');
            dashboard.style.display = 'block';
            tableView.style.display = 'none';
            
            dashboardBtn.addEventListener('click', function() {
                dashboard.style.display = 'block';
                tableView.style.display = 'none';
                dashboardBtn.classList.add('active');
                tableBtn.classList.remove('active');
                localStorage.setItem('academicViewPreference', 'dashboard');
            });
            
            tableBtn.addEventListener('click', function() {
                dashboard.style.display = 'none';
                tableView.style.display = 'block';
                tableBtn.classList.add('active');
                dashboardBtn.classList.remove('active');
                localStorage.setItem('academicViewPreference', 'table');
            });
            
            // Load saved preference
            const savedPreference = localStorage.getItem('academicViewPreference');
            if (savedPreference === 'table') {
                dashboard.style.display = 'none';
                tableView.style.display = 'block';
                tableBtn.classList.add('active');
                dashboardBtn.classList.remove('active');
            }
        }
        
        // Term filter functionality
        const termFilter = document.getElementById('termFilter');
        const gradeRows = document.querySelectorAll('.grade-row');
        
        if (termFilter && gradeRows.length > 0) {
            termFilter.addEventListener('change', function() {
                const selectedTerm = this.value;
                
                gradeRows.forEach(row => {
                    const rowTerm = row.getAttribute('data-term');
                    if (selectedTerm === 'all' || rowTerm === selectedTerm) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Also filter subject cards in dashboard
                const subjectCards = document.querySelectorAll('.subject-grade-item');
                if (selectedTerm === 'all') {
                    subjectCards.forEach(card => {
                        card.style.display = '';
                    });
                }
            });
        }
    
        // Add the transmutation function
        function getTransmutedGrade(initialGrade, tableType) {
            if (initialGrade < 0) return 60;
            
            // Table 1: DepEd Transmutation Table (formerly Table 4)
            if (tableType == 1) {
                if (initialGrade == 100) return 100;
                else if (initialGrade >= 98.40) return 99;
                else if (initialGrade >= 96.80) return 98;
                else if (initialGrade >= 95.20) return 97;
                else if (initialGrade >= 93.60) return 96;
                else if (initialGrade >= 92.00) return 95;
                else if (initialGrade >= 90.40) return 94;
                else if (initialGrade >= 88.80) return 93;
                else if (initialGrade >= 87.20) return 92;
                else if (initialGrade >= 85.60) return 91;
                else if (initialGrade >= 84.00) return 90;
                else if (initialGrade >= 82.40) return 89;
                else if (initialGrade >= 80.80) return 88;
                else if (initialGrade >= 79.20) return 87;
                else if (initialGrade >= 77.60) return 86;
                else if (initialGrade >= 76.00) return 85;
                else if (initialGrade >= 74.40) return 84;
                else if (initialGrade >= 72.80) return 83;
                else if (initialGrade >= 71.20) return 82;
                else if (initialGrade >= 69.60) return 81;
                else if (initialGrade >= 68.00) return 80;
                else if (initialGrade >= 66.40) return 79;
                else if (initialGrade >= 64.80) return 78;
                else if (initialGrade >= 63.20) return 77;
                else if (initialGrade >= 61.60) return 76;
                else if (initialGrade >= 60.00) return 75;
                else if (initialGrade >= 56.00) return 74;
                else if (initialGrade >= 52.00) return 73;
                else if (initialGrade >= 48.00) return 72;
                else if (initialGrade >= 44.00) return 71;
                else if (initialGrade >= 40.00) return 70;
                else if (initialGrade >= 36.00) return 69;
                else if (initialGrade >= 32.00) return 68;
                else if (initialGrade >= 28.00) return 67;
                else if (initialGrade >= 24.00) return 66;
                else if (initialGrade >= 20.00) return 65;
                else if (initialGrade >= 16.00) return 64;
                else if (initialGrade >= 12.00) return 63;
                else if (initialGrade >= 8.00) return 62;
                else if (initialGrade >= 4.00) return 61;
                else return 60;
            }
            // Table 2: Grades 1-10 and Non-Core Subjects of TVL, Sports, and Arts & Design (formerly Table 1)
            else if (tableType == 2) {
                if (initialGrade >= 80) return 100;
                else if (initialGrade >= 78.40) return 99;
                else if (initialGrade >= 76.80) return 98;
                else if (initialGrade >= 75.20) return 97;
                else if (initialGrade >= 73.60) return 96;
                else if (initialGrade >= 72.00) return 95;
                else if (initialGrade >= 70.40) return 94;
                else if (initialGrade >= 68.80) return 93;
                else if (initialGrade >= 67.20) return 92;
                else if (initialGrade >= 65.60) return 91;
                else if (initialGrade >= 64.00) return 90;
                else if (initialGrade >= 62.40) return 89;
                else if (initialGrade >= 60.80) return 88;
                else if (initialGrade >= 59.20) return 87;
                else if (initialGrade >= 57.60) return 86;
                else if (initialGrade >= 56.00) return 85;
                else if (initialGrade >= 54.40) return 84;
                else if (initialGrade >= 52.80) return 83;
                else if (initialGrade >= 51.20) return 82;
                else if (initialGrade >= 49.60) return 81;
                else if (initialGrade >= 48.00) return 80;
                else if (initialGrade >= 46.40) return 79;
                else if (initialGrade >= 44.80) return 78;
                else if (initialGrade >= 43.20) return 77;
                else if (initialGrade >= 41.60) return 76;
                else if (initialGrade >= 40.00) return 75;
                else if (initialGrade >= 38.40) return 74;
                else if (initialGrade >= 36.80) return 73;
                else if (initialGrade >= 35.20) return 72;
                else if (initialGrade >= 33.60) return 71;
                else if (initialGrade >= 32.00) return 70;
                else if (initialGrade >= 30.40) return 69;
                else if (initialGrade >= 28.80) return 68;
                else if (initialGrade >= 27.20) return 67;
                else if (initialGrade >= 25.60) return 66;
                else if (initialGrade >= 24.00) return 65;
                else if (initialGrade >= 22.40) return 64;
                else if (initialGrade >= 20.80) return 63;
                else if (initialGrade >= 19.20) return 62;
                else if (initialGrade >= 17.60) return 61;
                else return 60;
            }
            // Table 3: For SHS Core Subjects and Work Immersion/Research/Business Enterprise/Performance (formerly Table 2)
            else if (tableType == 3) {
                if (initialGrade >= 100) return 100;
                else if (initialGrade >= 73.80) return 99;
                else if (initialGrade >= 72.60) return 98;
                else if (initialGrade >= 71.40) return 97;
                else if (initialGrade >= 70.20) return 96;
                else if (initialGrade >= 69.00) return 95;
                else if (initialGrade >= 67.80) return 94;
                else if (initialGrade >= 66.60) return 93;
                else if (initialGrade >= 65.40) return 92;
                else if (initialGrade >= 64.20) return 91;
                else if (initialGrade >= 63.00) return 90;
                else if (initialGrade >= 61.80) return 89;
                else if (initialGrade >= 60.60) return 88;
                else if (initialGrade >= 59.40) return 87;
                else if (initialGrade >= 58.20) return 86;
                else if (initialGrade >= 57.00) return 85;
                else if (initialGrade >= 55.80) return 84;
                else if (initialGrade >= 54.60) return 83;
                else if (initialGrade >= 53.40) return 82;
                else if (initialGrade >= 52.20) return 81;
                else if (initialGrade >= 51.00) return 80;
                else if (initialGrade >= 49.80) return 79;
                else if (initialGrade >= 48.60) return 78;
                else if (initialGrade >= 47.40) return 77;
                else if (initialGrade >= 46.20) return 76;
                else if (initialGrade >= 45.00) return 75;
                else if (initialGrade >= 43.80) return 74;
                else if (initialGrade >= 42.60) return 73;
                else if (initialGrade >= 41.40) return 72;
                else if (initialGrade >= 40.20) return 71;
                else if (initialGrade >= 39.00) return 70;
                else if (initialGrade >= 37.80) return 69;
                else if (initialGrade >= 36.60) return 68;
                else if (initialGrade >= 35.40) return 67;
                else if (initialGrade >= 34.20) return 66;
                else if (initialGrade >= 33.00) return 65;
                else if (initialGrade >= 31.80) return 64;
                else if (initialGrade >= 30.60) return 63;
                else if (initialGrade >= 29.40) return 62;
                else if (initialGrade >= 28.20) return 61;
                else return 60;
            }
            // Table 4: For all other SHS Subjects in the Academic Track (formerly Table 3)
            else if (tableType == 4) {
                if (initialGrade >= 100) return 100;
                else if (initialGrade >= 68.90) return 99;
                else if (initialGrade >= 67.80) return 98;
                else if (initialGrade >= 66.70) return 97;
                else if (initialGrade >= 65.60) return 96;
                else if (initialGrade >= 64.50) return 95;
                else if (initialGrade >= 63.40) return 94;
                else if (initialGrade >= 62.30) return 93;
                else if (initialGrade >= 61.20) return 92;
                else if (initialGrade >= 60.10) return 91;
                else if (initialGrade >= 59.00) return 90;
                else if (initialGrade >= 57.80) return 89;
                else if (initialGrade >= 56.70) return 88;
                else if (initialGrade >= 55.60) return 87;
                else if (initialGrade >= 54.50) return 86;
                else if (initialGrade >= 53.40) return 85;
                else if (initialGrade >= 52.30) return 84;
                else if (initialGrade >= 51.20) return 83;
                else if (initialGrade >= 50.10) return 82;
                else if (initialGrade >= 49.00) return 81;
                else if (initialGrade >= 47.90) return 80;
                else if (initialGrade >= 46.80) return 79;
                else if (initialGrade >= 45.70) return 78;
                else if (initialGrade >= 44.60) return 77;
                else if (initialGrade >= 43.50) return 76;
                else if (initialGrade >= 42.40) return 75;
                else if (initialGrade >= 41.30) return 74;
                else if (initialGrade >= 40.20) return 73;
                else if (initialGrade >= 39.10) return 72;
                else if (initialGrade >= 34.00) return 71;
                else if (initialGrade >= 28.90) return 70;
                else if (initialGrade >= 23.80) return 69;
                else if (initialGrade >= 19.70) return 68;
                else if (initialGrade >= 17.60) return 67;
                else if (initialGrade >= 15.50) return 66;
                else if (initialGrade >= 13.40) return 65;
                else if (initialGrade >= 11.30) return 64;
                else if (initialGrade >= 9.20) return 63;
                else if (initialGrade >= 7.10) return 62;
                else if (initialGrade >= 5.00) return 61;
                else return 60;
            }
            else {
                // Default to table 1 if an invalid table type is specified
                return getTransmutedGrade(initialGrade, 1);
            }
        }
        
        // Default to transmutation table 1 (or get from query string)
        let selectedTransmutationTable = {{ $selectedTransmutationTable ?? 1 }};
        
        // Update transmuted grades display when table changes
        function updateGradesDisplay() {
            // Update all grade displays in the table
            $('.grade-row').each(function() {
                const initialGrade = parseFloat($(this).data('initial-grade'));
                const gradeType = $(this).data('grade-type');
                const transmutedGrade = getTransmutedGrade(initialGrade, selectedTransmutationTable);
                
                // Update the transmuted grade display
                $(this).find('.transmuted-grade').text(transmutedGrade);
                
                // Update badge classes based on the transmuted grade
                const badgeElement = $(this).find('.transmuted-grade-badge');
                badgeElement.removeClass('bg-danger bg-warning bg-info bg-success');
                
                if (transmutedGrade < 75) {
                    badgeElement.addClass('bg-danger');
                } else if (transmutedGrade < 80) {
                    badgeElement.addClass('bg-warning');
                } else if (transmutedGrade < 90) {
                    badgeElement.addClass('bg-info');
                } else {
                    badgeElement.addClass('bg-success');
                }
            });
        }
        
        // Function to update grades when transmutation table changes
        function updateTransmutedGrades(tableType) {
            // Save the selected table type
            selectedTransmutationTable = tableType;
            
            // Update the grades display without reloading
            updateGradesDisplay();
            
            // Store the selection in localStorage
            localStorage.setItem('selectedTransmutationTable', tableType);
            
            // Update the URL to reflect the selected table
            const url = new URL(window.location);
            url.searchParams.set('transmutation_table', tableType);
            window.history.pushState({}, '', url);
        }
        
        // Initialize grades display
        updateGradesDisplay();
    });
</script>
@endpush