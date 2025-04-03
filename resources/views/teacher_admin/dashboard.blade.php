@extends('layouts.app')

@section('content')
<style>
    /* Custom scrollbar styling */
    .activity-scroll {
        max-height: 400px;
        overflow-y: auto;
        scrollbar-width: thin; /* Firefox */
        scrollbar-color: rgba(0, 0, 0, 0.2) transparent; /* Firefox */
    }
    
    .activity-scroll::-webkit-scrollbar {
        width: 6px;
    }
    
    .activity-scroll::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .activity-scroll::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 10px;
    }
    
    .activity-scroll::-webkit-scrollbar-thumb:hover {
        background-color: rgba(0, 0, 0, 0.3);
    }
    
    /* Sticky header for tables */
    .sticky-header th {
        position: sticky;
        top: 0;
        background-color: #f8f9fa;
        z-index: 1;
        box-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
    }
</style>
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm bg-primary bg-gradient text-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            @if($school->logo_path)
                            <div class="me-3">
                                <img src="{{ asset($school->logo_path) }}" alt="{{ $school->name }} Logo" class="rounded" style="max-height: 60px;">
                            </div>
                            @else
                            <div class="avatar bg-white bg-opacity-25 rounded-circle p-3 me-3">
                                <i class="fas fa-user-shield fa-2x"></i>
                            </div>
                            @endif
                            <div>
                                <h2 class="fw-bold mb-1">Welcome, {{ Auth::user()->name }}</h2>
                                <p class="mb-0 opacity-75">Teacher Admin Dashboard - {{ $school->name }}</p>
                                <p class="mb-0 opacity-75">{{ now()->format('l, F d, Y') }}</p>
                            </div>
                        </div>
                        <div>
                            <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-light btn-lg">
                                <i class="fas fa-plus-circle me-2"></i> Create New Section
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
                            <i class="fas fa-external-link-alt me-1"></i> Manage
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
                            <i class="fas fa-check-circle me-1"></i> Active Subjects
                        </span>
                        <a href="{{ route('teacher-admin.subjects.index') }}" class="btn btn-sm btn-outline-success rounded-pill">
                            <i class="fas fa-external-link-alt me-1"></i> Manage
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
                            <p class="text-muted mb-1 small text-uppercase">Teachers</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['teachersCount'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-chalkboard-teacher fa-2x text-info"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success small">
                            <i class="fas fa-check-circle me-1"></i> Active Staff
                        </span>
                        <a href="#" class="btn btn-sm btn-outline-info rounded-pill disabled">
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
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-user-graduate fa-2x text-warning"></i>
                        </div>
                    </div>
                    <div class="progress" style="height: 4px;">
                        <div class="progress-bar bg-warning" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 pt-0 pb-3 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-success small">
                            <i class="fas fa-check-circle me-1"></i> All Enrolled
                        </span>
                        <a href="#" class="btn btn-sm btn-outline-warning rounded-pill disabled">
                            <i class="fas fa-external-link-alt me-1"></i> View All
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
                    <h5 class="mb-0"><i class="fas fa-chart-line text-primary me-2"></i> School Attendance Trends</h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary active attendance-period-btn" data-period="week">Week</button>
                        <button type="button" class="btn btn-sm btn-outline-secondary attendance-period-btn" data-period="month">Month</button>
                        <!-- <button type="button" class="btn btn-sm btn-outline-secondary attendance-period-btn" data-period="semester">Semester</button> -->
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
                    <h5 class="mb-0"><i class="fas fa-chart-pie text-success me-2"></i> School Grade Distribution</h5>
                </div>
                <div class="card-body">
                    <div class="grade-chart-container d-flex justify-content-center align-items-center" style="height: 300px;">
                        <canvas id="gradeDistributionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Teacher Performance & Recent Activity -->
    <div class="row mb-4">
        <!-- Teacher Performance Metrics -->
        <div class="col-lg-7 mb-4 mb-lg-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-user-check text-info me-2"></i> Teacher Performance</h5>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="topPerformersOnly">
                        <label class="form-check-label text-muted small" for="topPerformersOnly">Show Top Performers Only</label>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive activity-scroll" style="max-height: 300px;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="sticky-header bg-light">
                                <tr>
                                    <th class="border-0">Teacher</th>
                                    <th class="border-0 text-center">Subjects</th>
                                    <th class="border-0 text-center">Sections</th>
                                    <th class="border-0 text-center">Avg. Grade</th>
                                    <th class="border-0 text-center">Attendance</th>
                                    <th class="border-0 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teacherPerformance as $teacher)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar bg-info bg-opacity-10 text-info rounded-circle p-2 me-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $teacher['name'] }}</h6>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $teacher['subjectsCount'] }}</td>
                                    <td class="text-center">{{ $teacher['sectionsCount'] }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $teacher['averageGrade'] >= 85 ? 'bg-success' : ($teacher['averageGrade'] >= 75 ? 'bg-warning' : 'bg-danger') }}">
                                            {{ $teacher['averageGrade'] }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $teacher['attendanceCount'] }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success" title="Assign Subject">
                                                <i class="fas fa-book"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-history text-warning me-2"></i> Recent School Activity</h5>
                    <span class="badge bg-primary rounded-pill">{{ count($recentActivity) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush activity-scroll">
                        @foreach($recentActivity as $activity)
                        <div class="list-group-item border-0 py-3 px-3 {{ $loop->even ? 'bg-light bg-opacity-50' : '' }}">
                            <div class="d-flex align-items-center mb-2">
                                <div class="avatar rounded-circle {{ $activity['type'] == 'grade' ? 'bg-success bg-opacity-10 text-success' : 'bg-info bg-opacity-10 text-info' }} p-2 me-3">
                                    <i class="fas {{ $activity['type'] == 'grade' ? 'fa-star' : 'fa-clipboard-check' }}"></i>
                                </div>
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <h6 class="mb-0 fw-bold">
                                        <span class="badge {{ $activity['type'] == 'grade' ? 'bg-success' : 'bg-info' }} rounded-pill me-2">
                                            {{ ucfirst($activity['type']) }}
                                        </span>
                                        {{ $activity['user'] }}
                                    </h6>
                                    <small class="text-muted">{{ $activity['date']->diffForHumans() }}</small>
                                </div>
                            </div>
                            <p class="ms-5 mb-0 text-dark">{{ $activity['description'] }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Sections and Subjects -->
    <div class="row">
        <!-- Recent Sections -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-users-class text-primary me-2"></i> Recent Sections</h5>
                        <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> New Section
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentSections->count() > 0)
                        <div class="list-group list-group-flush activity-scroll">
                            @foreach($recentSections as $section)
                                <a href="{{ route('teacher-admin.sections.show', $section) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $section->name }}</h6>
                                            <small class="text-muted">Grade {{ $section->grade_level }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">{{ $section->subjects->count() }} Subjects</span>
                                            @if($section->adviser)
                                                <small class="d-block text-muted">Adviser: {{ $section->adviser->name }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-2">
                                <i class="fas fa-users-class text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <p class="text-muted mb-0">No sections have been created yet.</p>
                            <a href="{{ route('teacher-admin.sections.create') }}" class="btn btn-sm btn-primary mt-3">
                                <i class="fas fa-plus-circle me-1"></i> Create Section
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Subjects -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class="fas fa-book text-success me-2"></i> Recent Subjects</h5>
                        <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus-circle me-1"></i> New Subject
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($recentSubjects->count() > 0)
                        <div class="list-group list-group-flush activity-scroll">
                            @foreach($recentSubjects as $subject)
                                <a href="{{ route('teacher-admin.subjects.show', $subject) }}" class="list-group-item list-group-item-action">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $subject->name }}</h6>
                                            <small class="text-muted">Code: {{ $subject->code }}</small>
                                        </div>
                                        <div>
                                            <span class="badge bg-success">{{ $subject->sections_count }} Sections</span>
                                            <small class="d-block text-muted">{{ $subject->teachers ? $subject->teachers->count() : 0 }} Teachers</small>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="mb-2">
                                <i class="fas fa-book text-muted" style="font-size: 2rem;"></i>
                            </div>
                            <p class="text-muted mb-0">No subjects have been created yet.</p>
                            <a href="{{ route('teacher-admin.subjects.create') }}" class="btn btn-sm btn-success mt-3">
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
        // Get data from data attributes for charts
        const weeklyAttendanceData = JSON.parse(document.getElementById('weekly-attendance-data').getAttribute('data-attendance'));
        const gradeDistributionData = JSON.parse(document.getElementById('grade-distribution-data').getAttribute('data-grades'));
        
        // Attendance Chart
        const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceDates = weeklyAttendanceData.map(item => item.date);
        const presentData = weeklyAttendanceData.map(item => item.present);
        const absentData = weeklyAttendanceData.map(item => item.absent);
        const lateData = weeklyAttendanceData.map(item => item.late);
        
        const attendanceChart = new Chart(attendanceCtx, {
            type: 'bar',
            data: {
                labels: attendanceDates,
                datasets: [
                    {
                        label: 'Present',
                        data: presentData,
                        backgroundColor: 'rgba(40, 167, 69, 0.7)',
                        borderColor: 'rgba(40, 167, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Absent',
                        data: absentData,
                        backgroundColor: 'rgba(220, 53, 69, 0.7)',
                        borderColor: 'rgba(220, 53, 69, 1)',
                        borderWidth: 1
                    },
                    {
                        label: 'Late',
                        data: lateData,
                        backgroundColor: 'rgba(255, 193, 7, 0.7)',
                        borderColor: 'rgba(255, 193, 7, 1)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        grid: {
                            display: false
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    }
                },
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });

        // Grade Distribution Chart
        const gradeCtx = document.getElementById('gradeDistributionChart').getContext('2d');
        const gradeChart = new Chart(gradeCtx, {
            type: 'doughnut',
            data: {
                labels: ['Excellent (90-100)', 'Very Good (85-89)', 'Good (80-84)', 'Satisfactory (75-79)', 'Needs Improvement (<75)'],
                datasets: [{
                    data: [
                        gradeDistributionData.excellent,
                        gradeDistributionData.veryGood,
                        gradeDistributionData.good,
                        gradeDistributionData.satisfactory,
                        gradeDistributionData.needsImprovement
                    ],
                    backgroundColor: [
                        'rgba(25, 135, 84, 0.7)',
                        'rgba(13, 110, 253, 0.7)',
                        'rgba(0, 195, 220, 0.7)',
                        'rgba(255, 193, 7, 0.7)',
                        'rgba(220, 53, 69, 0.7)'
                    ],
                    borderColor: [
                        'rgba(25, 135, 84, 1)',
                        'rgba(13, 110, 253, 1)',
                        'rgba(0, 195, 220, 1)',
                        'rgba(255, 193, 7, 1)',
                        'rgba(220, 53, 69, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Period Selection for Attendance Chart
        document.querySelectorAll('.attendance-period-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                document.querySelectorAll('.attendance-period-btn').forEach(function(btn) {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                this.classList.add('active');
                
                // TODO: Update chart data based on selected period
                // This would typically involve an AJAX call to get data for the selected period
                // For now, we'll just simulate a data change
                var period = this.getAttribute('data-period');
                
                // Simulate data change
                var data = attendanceChart.data.datasets[0].data;
                attendanceChart.data.datasets[0].data = data.map(function() { return Math.floor(Math.random() * 50) + 20; });
                attendanceChart.data.datasets[1].data = data.map(function() { return Math.floor(Math.random() * 10); });
                attendanceChart.data.datasets[2].data = data.map(function() { return Math.floor(Math.random() * 5); });
                attendanceChart.update();
            });
        });

        // Toggle for top performers
        document.getElementById('topPerformersOnly').addEventListener('change', function() {
            // This would typically filter the table rows
            // For now, we'll just log the change
            console.log('Show top performers only:', this.checked);
        });
    });
</script>
@endpush

<!-- Hidden data elements for JS -->
<div id="weekly-attendance-data" data-attendance="{{ json_encode($attendanceStats['weeklyAttendance']) }}" style="display: none;"></div>
<div id="grade-distribution-data" data-grades="{{ json_encode($gradeDistribution) }}" style="display: none;"></div>
@endsection     