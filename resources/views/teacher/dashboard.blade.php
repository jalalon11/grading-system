@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-gradient-primary text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="avatar bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <i class="fas fa-chalkboard-teacher fa-2x"></i>
                            </div>
                            <div>
                                <h2 class="fw-bold mb-1">Welcome, {{ Auth::user()->name }}</h2>
                                <p class="mb-0 opacity-75">{{ now()->format('l, F d, Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('teacher.attendances.create') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-clipboard-list me-2"></i> Take Today's Attendance
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
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['todayAttendance'] > 0 ? '100%' : '0%' }}" aria-valuenow="{{ $stats['todayAttendance'] > 0 ? '100' : '0' }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="{{ $stats['todayAttendance'] > 0 ? 'text-success' : 'text-danger' }} small">
                            <i class="{{ $stats['todayAttendance'] > 0 ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' }} me-1"></i> 
                            {{ $stats['todayAttendance'] > 0 ? 'Recorded' : 'Not Recorded' }}
                        </span>
                        <a href="{{ route('teacher.attendances.index') }}" class="btn btn-sm btn-outline-warning rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i> View Records
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="row mb-4">
        <!-- Attendance Trends -->
        <div class="col-lg-8 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-chart-line text-primary me-2"></i> Attendance Trends</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary active attendance-period-btn" data-period="week">Week</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary attendance-period-btn" data-period="month">Month</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary attendance-period-btn" data-period="semester">Semester</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="attendance-chart-container" style="height: 300px;">
                        <canvas id="attendanceChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Grade Distribution -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-pie text-success me-2"></i> Grade Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="grade-chart-container d-flex justify-content-center align-items-center" style="height: 300px;">
                        <canvas id="gradeDistributionChart"></canvas>
                    </div>
                </div>
            </div>
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
                            <option value="{{ $section->id }}">{{ $section->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
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
                                                    @php $rating = $student->performance_rating ?? 0; $stars = min(5, max(1, round($rating / 20))); @endphp
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
                    <h5 class="mb-0"><i class="fas fa-users text-primary me-2"></i> Recent Sections</h5>
                    <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> Add Section
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Section</th>
                                        <th>Grade Level</th>
                                        <th>Students</th>
                                        <th class="text-end pe-4">Action</th>
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
                                        <td class="text-end pe-4">
                                            <a href="{{ route('teacher-admin.sections.show', $section->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye me-1"></i> View
                                            </a>
                                        </td>
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
                    <h5 class="mb-0"><i class="fas fa-book text-success me-2"></i> Recent Subjects</h5>
                    <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus-circle me-1"></i> Add Subject
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($recentSubjects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Subject</th>
                                        <th>Code</th>
                                        <th>Grade Level</th>
                                        <th class="text-end pe-4">Action</th>
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
                                            <td>{{ $gradeLevel ?? 'N/A' }}</td>
                                            <td class="text-end pe-4">
                                                <a href="{{ route('teacher-admin.subjects.show', $subject->id) }}" class="btn btn-sm btn-outline-success">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </a>
                                            </td>
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

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    
    .avatar {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 45px;
        height: 45px;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(to right, #4e73df, #224abe);
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    .attendance-period-btn.active {
        background-color: #4e73df;
        color: white;
        border-color: #4e73df;
    }
    
    .progress {
        height: 4px;
        margin-bottom: 0;
    }
    
    .progress-taller {
        height: 6px;
    }
    
    .performance-progress {
        flex-grow: 1;
        margin-right: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Attendance Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(attendanceCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($last7Days as $day)
                        '{{ $day['date'] }}',
                    @endforeach
                ],
                datasets: [
                    {
                        label: 'Present',
                        data: [
                            @foreach($attendanceTrends as $trend)
                                {{ $trend['present'] }},
                            @endforeach
                        ],
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Late',
                        data: [
                            @foreach($attendanceTrends as $trend)
                                {{ $trend['late'] }},
                            @endforeach
                        ],
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Absent',
                        data: [
                            @foreach($attendanceTrends as $trend)
                                {{ $trend['absent'] }},
                            @endforeach
                        ],
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
        
        // Initialize Grade Distribution Chart
        const gradeCtx = document.getElementById('gradeDistributionChart').getContext('2d');
        const gradeChart = new Chart(gradeCtx, {
            type: 'doughnut',
            data: {
                labels: ['A (90-100)', 'B (80-89)', 'C (70-79)', 'D (60-69)', 'F (Below 60)'],
                datasets: [{
                    data: [
                        {{ $gradeDistributionPercentage['A'] ?? 0 }},
                        {{ $gradeDistributionPercentage['B'] ?? 0 }},
                        {{ $gradeDistributionPercentage['C'] ?? 0 }},
                        {{ $gradeDistributionPercentage['D'] ?? 0 }},
                        {{ $gradeDistributionPercentage['F'] ?? 0 }}
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#17a2b8',
                        '#ffc107',
                        '#fd7e14',
                        '#dc3545'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });
        
        // Handle attendance period buttons
        document.querySelectorAll('.attendance-period-btn').forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.attendance-period-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // Get selected period
                const period = this.dataset.period;
                
                // Get selected section ID (if any)
                const sectionSelect = document.getElementById('performanceMetricSection');
                const sectionId = sectionSelect ? sectionSelect.value : null;
                
                // Fetch attendance data by period via AJAX
                fetch(`/teacher/dashboard/attendance-data?period=${period}${sectionId ? '&section_id='+sectionId : ''}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update chart with real data
                        attendanceChart.data.labels = data.labels;
                        attendanceChart.data.datasets[0].data = data.present;
                        attendanceChart.data.datasets[1].data = data.late;
                        attendanceChart.data.datasets[2].data = data.absent;
                        attendanceChart.update();
                    })
                    .catch(error => {
                        console.error('Error fetching attendance data:', error);
                        // Fallback to initial data if fetch fails
                    });
            });
        });
        
        // Add event listener for section select change to update performance metrics
        const sectionSelect = document.getElementById('performanceMetricSection');
        if (sectionSelect) {
            sectionSelect.addEventListener('change', function() {
                const sectionId = this.value;
                
                // Update attendance chart for the selected section
                const activePeriodBtn = document.querySelector('.attendance-period-btn.active');
                const period = activePeriodBtn ? activePeriodBtn.dataset.period : 'week';
                
                // Fetch attendance data for the selected section
                fetch(`/teacher/dashboard/attendance-data?period=${period}&section_id=${sectionId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update chart with section-specific data
                        attendanceChart.data.labels = data.labels;
                        attendanceChart.data.datasets[0].data = data.present;
                        attendanceChart.data.datasets[1].data = data.late;
                        attendanceChart.data.datasets[2].data = data.absent;
                        attendanceChart.update();
                    })
                    .catch(error => {
                        console.error('Error fetching section attendance data:', error);
                    });
                
                // Also update the student performance metrics table
                fetch(`/teacher/dashboard/performance-data?section_id=${sectionId}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update the student performance table with new data
                        // This will be handled by the backend already returning the right view
                    })
                    .catch(error => {
                        console.error('Error fetching performance data:', error);
                    });
            });
        }
    });
</script>
@endpush
@endsection 