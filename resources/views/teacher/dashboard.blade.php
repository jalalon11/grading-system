@extends('layouts.app')

@push('styles')
<link href="{{ asset('css/dashboard.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary text-white welcome-header">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                        <div class="d-flex flex-column flex-md-row align-items-center mb-3 mb-md-0">
                            @if(Auth::user()->school && Auth::user()->school->logo_path)
                            <div class="me-md-3 mb-3 mb-md-0 text-center">
                                <img src="{{ asset(Auth::user()->school->logo_path) }}" alt="{{ Auth::user()->school->name }} Logo" class="rounded" style="max-height: 60px;">
                            </div>
                            @else
                            <div class="avatar bg-white bg-opacity-25 rounded-circle p-3 me-md-3 mb-3 mb-md-0 mx-auto">
                                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                            </div>
                            @endif
                            <div class="text-center text-md-start">
                                <h3 class="fw-bold mb-1">Welcome, {{ Auth::user()->name }}</h3>
                                <p class="mb-0 opacity-75">{{ now()->format('l, F d, Y') }}</p>
                                @if(Auth::user()->school)
                                <p class="mb-0 opacity-75">{{ Auth::user()->school->name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('teacher.attendances.create') }}" class="btn btn-light">
                                <i class="fas fa-plus-circle me-2"></i> Manage Attendance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase">Sections</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['sectionsCount'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success small">
                            <i class="fas fa-check-circle me-1"></i> All Active
                        </span>
                        <a href="{{ route('teacher-admin.sections.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i> View All
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase">Subjects</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['subjectsCount'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-book fa-2x text-success"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success small">
                            <i class="fas fa-check-circle me-1"></i> All Assigned
                        </span>
                        <a href="{{ route('teacher-admin.subjects.index') }}" class="btn btn-sm btn-outline-success rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i> View All
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase">Students</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['studentsCount'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-user-graduate fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success small">
                            <i class="fas fa-check-circle me-1"></i> All Enrolled
                        </span>
                        <a href="{{ route('teacher.students.index') }}" class="btn btn-sm btn-outline-info rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i> View All
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-body position-relative p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div>
                            <p class="text-muted mb-1 small text-uppercase">Today's Attendance</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['todayAttendance'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-clipboard-check fa-2x text-warning"></i>
                        </div>
                    </div>
                   <!-- <div class="attendance-summary d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <span class="status-badge status-present me-1"></span>
                            <small>{{ $todayStats['present'] ?? 0 }} Present</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="status-badge status-absent me-1"></span>
                            <small>{{ $todayStats['absent'] ?? 0 }} Absent</small>
                        </div>
                        <div class="d-flex align-items-center">
                            <span class="status-badge status-late me-1"></span>
                            <small>{{ $todayStats['late'] ?? 0 }} Late</small>
                        </div>
                    </div> -->
                    <div class="progress" style="height: 4px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="{{ $stats['todayAttendance'] > 0 ? 'text-success' : 'text-danger' }} small">
                            <i class="{{ $stats['todayAttendance'] > 0 ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' }} me-1"></i>
                            {{ $stats['todayAttendance'] > 0 ? 'Recorded' : 'Not Recorded' }}
                        </span>
                        <a href="{{ route('teacher.attendances.index') }}" class="btn btn-sm btn-outline-primary rounded-pill">
                            <i class="fas fa-calendar-check me-1"></i> Attendance
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="row mb-4">
        <!-- Attendance Trends -->
        <div class="col-lg-12">
            @include('teacher.dashboard.attendance-charts')
        </div>
    </div>

    <!-- Quick Actions & Performance Analytics -->
    <div class="row mb-4">
        <!-- Quick Actions -->
        <div class="col-lg-4 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('teacher.students.create') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4">
                            <div class="avatar rounded-circle bg-primary bg-opacity-10 p-2 me-3">
                                <i class="fas fa-user-plus text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Add New Student</h6>
                                <small class="text-muted">Register a new student to your section</small>
                            </div>
                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                        </a>
                        <a href="{{ route('teacher.grades.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4">
                            <div class="avatar rounded-circle bg-success bg-opacity-10 p-2 me-3">
                                <i class="fas fa-star text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Manage Grades</h6>
                                <small class="text-muted">View and update student grades</small>
                            </div>
                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                        </a>
                        <a href="{{ route('teacher.attendances.create') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4">
                            <div class="avatar rounded-circle bg-info bg-opacity-10 p-2 me-3">
                                <i class="fas fa-clipboard-list text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Take Attendance</h6>
                                <small class="text-muted">Record daily student attendance</small>
                            </div>
                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                        </a>
                        <a href="{{ route('teacher.grade-approvals.index') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4">
                            <div class="avatar rounded-circle bg-warning bg-opacity-10 p-2 me-3">
                                <i class="fas fa-check-circle text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Grade Approvals</h6>
                                <small class="text-muted">Manage approval status for your grades</small>
                            </div>
                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                        </a>
                        <a href="{{ route('teacher.profile') }}" class="list-group-item list-group-item-action d-flex align-items-center py-3 px-4">
                            <div class="avatar rounded-circle bg-danger bg-opacity-10 p-2 me-3">
                                <i class="fas fa-user-cog text-danger"></i>
                            </div>
                            <div>
                                <h6 class="mb-0">Update Profile</h6>
                                <small class="text-muted">Manage your account settings</small>
                            </div>
                            <i class="fas fa-chevron-right ms-auto text-muted"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Student Performance Metrics -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-bar text-info me-2"></i> Student Performance Metrics</h5>
                    <select class="form-select form-select-sm" style="width: auto;" id="performanceMetricSection">
                        @foreach($recentSections as $section)
                            <option value="{{ $section->id }}">{{ $section->name }} (Adviser)</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="table-responsive student-performance-metrics-table">
                            <table class="table table-hover align-middle mb-0 student-performance-table">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Top Students</th>
                                        <th>Average Grade</th>
                                        <th>Attendance</th>
                                        <th>Performance</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($topStudents->count() > 0)
                                        @foreach($topStudents as $student)
                                        <tr>
                                            <td class="ps-4">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-container me-2">
                                                        <span class="avatar bg-primary text-white rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                            {{ substr($student->first_name ?? 'S', 0, 1) }}{{ substr($student->last_name ?? '', 0, 1) }}
                                                        </span>
                                                    </div>
                                                    <div>
                                                        <div class="fw-medium">{{ $student->first_name ?? 'Student' }} {{ $student->last_name ?? '' }}</div>
                                                        <small class="text-muted">ID: {{ $student->student_id ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $student->grades_avg_score >= 90 ? 'success' : ($student->grades_avg_score >= 80 ? 'primary' : ($student->grades_avg_score >= 70 ? 'info' : ($student->grades_avg_score >= 60 ? 'warning' : 'danger'))) }} rounded-pill px-3 py-2">
                                                    {{ number_format($student->grades_avg_score ?? 0, 1) }}%
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="performance-progress progress-taller">
                                                        <div class="progress-bar bg-success" role="progressbar" aria-valuenow="{{ $student->attendance_rate ?? 0 }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $student->attendance_rate ?? 0 }}%"></div>
                                                    </div>
                                                    <span class="text-muted small">{{ $student->attendance_rate ?? 0 }}%</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @php
                                                        $score = $student->grades_avg_score ?? 0;
                                                        $stars = 0;

                                                        if ($score >= 94) {
                                                            $stars = 5;
                                                        } elseif ($score >= 87) {
                                                            $stars = 4;
                                                        } elseif ($score >= 82) {
                                                            $stars = 3;
                                                        } elseif ($score >= 78) {
                                                            $stars = 2;
                                                        } elseif ($score >= 75) {
                                                            $stars = 1;
                                                        }
                                                    @endphp
                                                    @for($j = 1; $j <= 5; $j++)
                                                        <i class="fas fa-star {{ $j <= $stars ? 'text-warning' : 'text-muted' }} me-1"></i>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('teacher.students.show', $student->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="py-5">
                                                    <div class="avatar bg-light rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                                        <i class="fas fa-user-graduate text-muted fa-2x"></i>
                                                    </div>
                                                    <h6 class="text-muted">No student performance data available</h6>
                                                    <p class="text-muted small mb-3">Add grades for students to see performance metrics</p>
                                                    <a href="{{ route('teacher.grades.create') }}" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-plus-circle me-1"></i> Add Grades
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-chart-line text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No performance data available</h6>
                            <p class="text-muted small">Add students to your sections to see performance metrics</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Data Tables -->
    <div class="row">
        <!-- Recent Sections -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0"><i class="fas fa-users text-primary me-2"></i> Assigned Sections</h5>
                    <!-- <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Add Section
                    </a> -->
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="table-responsive assigned-sections-table">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Section</th>
                                        <th>Grade Level</th>
                                        <th>Students</th>
                                        {{-- <th class="text-end pe-4">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSections as $section)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $section->name ?? 'N/A' }}</td>
                                        <td>{{ $section->grade_level ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">
                                                {{ $section->students->count() }} students
                                            </span>
                                        </td>
                                        {{-- <td class="text-end pe-4">
                                            <a href="{{ route('teacher-admin.sections.show', $section->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-folder-open text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No sections found</h6>
                            <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus-circle me-1"></i> Create Section
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Subjects -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h5 class="mb-0"><i class="fas fa-book text-success me-2"></i> Assigned Subjects</h5>
                    <!-- <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus-circle me-1"></i> Add Subject
                    </a> -->
                </div>
                <div class="card-body p-0">
                    @if($recentSubjects->count() > 0)
                        <div class="table-responsive assigned-subjects-table">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Subject</th>
                                        <th>Code</th>
                                        <th>Grade Level</th>
                                        {{-- <th class="text-end pe-4">Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSubjects as $subject)
                                        @php
                                            // Get unique grade levels for this subject from its sections
                                            $gradeLevels = $subject->sections->pluck('grade_level')->unique()->sort();
                                        @endphp

                                        @foreach($gradeLevels as $gradeLevel)
                                        <tr>
                                            <td class="ps-4 fw-bold">{{ $subject->name ?? 'N/A' }}</td>
                                            <td><code>{{ $subject->code ?? 'N/A' }}</code></td>
                                            <td class="badge bg-success rounded-pill text-white">{{ $gradeLevel ?? 'N/A' }}</td>
                                            {{-- <td class="text-end pe-4">
                                                <a href="{{ route('teacher-admin.subjects.show', $subject->id) }}" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                            </td> --}}
                                        </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-book text-muted fa-3x"></i>
                            </div>
                            <h6 class="text-muted">No subjects found</h6>
                            <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-success mt-2">
                                <i class="fas fa-plus-circle me-1"></i> Create Subject
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Define API routes
        const attendanceDataUrl = "{{ route('teacher.dashboard.attendance-data') }}";
        const performanceDataUrl = "{{ route('teacher.dashboard.performance-data') }}";

        // Chart instances
        let attendanceChart, gradeChart;

        // Function to initialize charts
        function initCharts() {
            // Define colors for charts
            const fontColor = '#666';
            const gridColor = 'rgba(0, 0, 0, 0.1)';

            // Create a simple test chart
            const testChartCtx = document.getElementById('testChart');
            if (testChartCtx) {
                // Sample data
                const labels = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri'];
                const presentData = [5, 4, 3, 5, 4];
                const lateData = [0, 1, 0, 0, 1];
                const absentData = [0, 0, 2, 0, 0];

                // Create the chart
                new Chart(testChartCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Present',
                                data: presentData,
                                backgroundColor: '#28a745',
                                borderColor: '#28a745',
                                borderWidth: 1
                            },
                            {
                                label: 'Late',
                                data: lateData,
                                backgroundColor: '#ffc107',
                                borderColor: '#ffc107',
                                borderWidth: 1
                            },
                            {
                                label: 'Absent',
                                data: absentData,
                                backgroundColor: '#dc3545',
                                borderColor: '#dc3545',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        },
                        scales: {
                            x: {
                                ticks: {
                                    color: fontColor
                                },
                                grid: {
                                    color: gridColor
                                }
                            },
                            y: {
                                ticks: {
                                    color: fontColor
                                },
                                grid: {
                                    color: gridColor
                                }
                            }
                        }
                    }
                });
            }
        }

        // Initialize charts
        initCharts();

        // Handle attendance period buttons
        document.addEventListener('DOMContentLoaded', function() {
            const weeklyViewBtn = document.getElementById('weeklyViewBtn');
            const monthlyViewBtn = document.getElementById('monthlyViewBtn');
            const weeklyAttendanceView = document.getElementById('weeklyAttendanceView');
            const monthlyAttendanceView = document.getElementById('monthlyAttendanceView');

            if (weeklyViewBtn && monthlyViewBtn) {
                weeklyViewBtn.addEventListener('click', function() {
                    weeklyViewBtn.classList.add('active');
                    monthlyViewBtn.classList.remove('active');
                    weeklyAttendanceView.style.display = 'block';
                    monthlyAttendanceView.style.display = 'none';
                });

                monthlyViewBtn.addEventListener('click', function() {
                    monthlyViewBtn.classList.add('active');
                    weeklyViewBtn.classList.remove('active');
                    monthlyAttendanceView.style.display = 'block';
                    weeklyAttendanceView.style.display = 'none';
                });
            }
        });

        // Add event listener for section select change to update performance metrics
        const sectionSelect = document.getElementById('performanceMetricSection');
        if (sectionSelect) {
            sectionSelect.addEventListener('change', function() {
                const sectionId = this.value;

                // Update attendance chart for the selected section
                const activePeriodBtn = document.querySelector('.attendance-period-btn.active');
                const period = activePeriodBtn ? activePeriodBtn.dataset.period : 'week';

                // Show loading state
                const performanceTable = document.querySelector('.student-performance-table tbody');
                if (performanceTable) {
                    performanceTable.innerHTML = `
                        <tr>
                            <td colspan="5" class="text-center py-3">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2 mb-0">Loading student data...</p>
                            </td>
                        </tr>
                    `;
                }

                // Fetch attendance data for the selected section
                fetch(`${attendanceDataUrl}?period=${period}&section_id=${sectionId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Update chart with section-specific data
                        attendanceChart.data.labels = data.labels;
                        attendanceChart.data.datasets[0].data = data.present;
                        attendanceChart.data.datasets[1].data = data.late;
                        attendanceChart.data.datasets[2].data = data.half_day;
                        attendanceChart.data.datasets[3].data = data.absent;
                        attendanceChart.data.datasets[4].data = data.excused;

                        // Determine the max value for y-axis
                        const allValues = [...data.present, ...data.late, ...data.half_day, ...data.absent, ...data.excused];
                        const maxValue = Math.max(...allValues, 1); // Minimum of 1

                        // Update y-axis max value and step size
                        const stepSize = maxValue <= 10 ? 1 : Math.ceil(maxValue / 10);
                        attendanceChart.options.scales.y.ticks.stepSize = stepSize;

                        attendanceChart.update();
                    })
                    .catch(error => {
                        console.error('Error fetching section attendance data:', error);
                    });

                // Also update the student performance metrics table
                fetch(`${performanceDataUrl}?section_id=${sectionId}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        // Debug log to check response structure
                        console.log('Performance data response:', data);

                        // Update the student performance table with new data
                        if (performanceTable && data.students && data.students.length > 0) {
                            let tableHTML = '';
                            data.students.forEach(student => {
                                // Calculate stars based on new grading scale
                                let stars = 0;
                                const score = student.grades_avg_score || 0;

                                if (score >= 94) {
                                    stars = 5;
                                } else if (score >= 87) {
                                    stars = 4;
                                } else if (score >= 82) {
                                    stars = 3;
                                } else if (score >= 78) {
                                    stars = 2;
                                } else if (score >= 75) {
                                    stars = 1;
                                }

                                // Generate star icons
                                let starsHTML = '';
                                for (let i = 1; i <= 5; i++) {
                                    starsHTML += `<i class="fas fa-star ${i <= stars ? 'text-warning' : 'text-muted'} me-1"></i>`;
                                }

                                // Determine grade badge color
                                let badgeClass = 'bg-danger';
                                if (score >= 90) badgeClass = 'bg-success';
                                else if (score >= 80) badgeClass = 'bg-primary';
                                else if (score >= 70) badgeClass = 'bg-info';
                                else if (score >= 60) badgeClass = 'bg-warning';

                                // Add row to table
                                tableHTML += `
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-container me-2">
                                                    <span class="avatar bg-primary text-white rounded-circle" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                        ${student.first_name ? student.first_name.substring(0, 1) : 'S'}${student.last_name ? student.last_name.substring(0, 1) : ''}
                                                    </span>
                                                </div>
                                                <div>
                                                    <div class="fw-medium">${student.first_name || 'Student'} ${student.last_name || ''}</div>
                                                    <small class="text-muted">ID: ${student.student_id || 'N/A'}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge ${badgeClass} rounded-pill px-3 py-2">
                                                ${student.grades_avg_score ? student.grades_avg_score.toFixed(1) : '0.0'}%
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="performance-progress progress-taller">
                                                    <div class="progress-bar bg-success" role="progressbar" aria-valuenow="${student.attendance_rate || 0}" aria-valuemin="0" aria-valuemax="100" style="width: ${student.attendance_rate || 0}%"></div>
                                                </div>
                                                <span class="text-muted small">${student.attendance_rate || 0}%</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                ${starsHTML}
                                            </div>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="/teacher/students/${student.id}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
                                    </tr>
                                `;
                            });
                            performanceTable.innerHTML = tableHTML;
                        } else if (performanceTable) {
                            performanceTable.innerHTML = `
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="py-5">
                                            <div class="avatar bg-light rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                                <i class="fas fa-user-graduate text-muted fa-2x"></i>
                                            </div>
                                            <h6 class="text-muted">No student performance data available</h6>
                                            <p class="text-muted small mb-3">Add grades for students to see performance metrics</p>
                                            <a href="{{ route('teacher.grades.create') }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-plus-circle me-1"></i> Add Grades
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching performance data:', error);
                        if (performanceTable) {
                            performanceTable.innerHTML = `
                                <tr>
                                    <td colspan="5" class="text-center py-4">
                                        <div class="py-5">
                                            <div class="avatar bg-light rounded-circle mx-auto mb-3" style="width: 60px; height: 60px;">
                                                <i class="fas fa-exclamation-triangle text-danger fa-2x"></i>
                                            </div>
                                            <h6 class="text-danger">Error loading data</h6>
                                            <p class="text-muted small mb-3">There was a problem fetching student performance data: ${error.message}</p>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    });
            });
        }
    });
</script>
@endpush
@endsection