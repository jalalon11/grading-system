@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
@endsection

@php
    $maintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();
    $maintenanceMessage = \App\Models\SystemSetting::getMaintenanceMessage();
    $maintenanceDuration = \App\Models\SystemSetting::getMaintenanceDuration();
@endphp

@section('content')
<div class="container-fluid px-4">
    <!-- Modern Dashboard Header -->
    <div class="dashboard-header bg-white rounded-4 shadow-sm mb-4 p-4 position-relative overflow-hidden">
        <div class="position-absolute top-0 end-0 w-50 h-100 bg-primary bg-opacity-5 z-0 d-none d-lg-block" style="clip-path: polygon(15% 0, 100% 0%, 100% 100%, 0% 100%);"></div>
        <div class="row align-items-center position-relative z-1">
            <div class="col-lg-8">
                <div class="d-flex align-items-center">
                    <div class="dashboard-icon-wrapper bg-primary bg-opacity-10 p-3 rounded-3 me-4 d-flex align-items-center justify-content-center">
                        <i class="fas fa-tachometer-alt text-primary fa-2x"></i>
                    </div>
                    <div>
                        <h1 class="fw-bold mb-2 display-6">Admin Dashboard</h1>
                        <p class="text-muted mb-0 lead">Welcome back, {{ Auth::user()->name }}!</p>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill">
                                <i class="fas fa-check-circle me-1"></i> System Online
                            </span>
                            <div class="ms-3 d-flex align-items-center">
                                <i class="far fa-calendar-alt text-primary me-2"></i>
                                <span>{{ date('F d, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mt-4 mt-lg-0">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="quick-stat-card bg-white rounded-3 p-3 border border-light shadow-sm h-100 transition-all hover-lift">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-primary bg-opacity-10 p-2 rounded-circle">
                                    <i class="fas fa-money-bill-wave text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted small">Monthly Sales</h6>
                                    <h3 class="mb-0 fw-bold">₱{{ number_format($currentMonthSales, 0) }}</h3>
                                    <p class="small text-success mb-0">{{ date('F Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="quick-stat-card bg-white rounded-3 p-3 border border-light shadow-sm h-100 transition-all hover-lift">
                            <div class="d-flex align-items-center">
                                <div class="stat-icon bg-success bg-opacity-10 p-2 rounded-circle">
                                    <i class="fas fa-chart-line text-success"></i>
                                </div>
                                <div class="ms-3">
                                    <h6 class="mb-0 text-muted small">Yearly Sales</h6>
                                    <h3 class="mb-0 fw-bold">₱{{ number_format($currentYearSales, 0) }}</h3>
                                    <p class="small text-success mb-0">{{ date('Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white rounded-4 shadow-sm h-100 position-relative overflow-hidden transition-all hover-lift">
                <div class="position-absolute top-0 start-0 h-100 w-1 bg-primary"></div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="stat-icon-sm bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fas fa-chalkboard-teacher text-primary"></i>
                                </div>
                                <h6 class="text-uppercase fw-semibold mb-0 small">Teachers</h6>
                            </div>
                            <h3 class="fw-bold mb-0 display-6">{{ $stats['teachersCount'] }}</h3>
                            <!-- <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                            </div> -->
                            <p class="small text-muted mb-0 mt-2">
                                <i class="fas fa-user-tie me-1"></i> Active Faculty Members
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-sm btn-primary w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-eye me-2"></i> View All Teachers
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white rounded-4 shadow-sm h-100 position-relative overflow-hidden transition-all hover-lift">
                <div class="position-absolute top-0 start-0 h-100 w-1 bg-info"></div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="stat-icon-sm bg-info bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fas fa-building text-info"></i>
                                </div>
                                <h6 class="text-uppercase fw-semibold mb-0 small">School Divisions</h6>
                            </div>
                            <h3 class="fw-bold mb-0 display-6">{{ $stats['schoolDivisionsCount'] }}</h3>
                            <!-- <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 60%"></div>
                            </div> -->
                            <p class="small text-muted mb-0 mt-2">
                                <i class="fas fa-building me-1"></i> Educational Divisions
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <a href="{{ route('admin.school-divisions.index') }}" class="btn btn-sm btn-info text-white w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-eye me-2"></i> View All Divisions
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white rounded-4 shadow-sm h-100 position-relative overflow-hidden transition-all hover-lift">
                <div class="position-absolute top-0 start-0 h-100 w-1 bg-warning"></div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="stat-icon-sm bg-warning bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fas fa-school text-warning"></i>
                                </div>
                                <h6 class="text-uppercase fw-semibold mb-0 small">Schools</h6>
                            </div>
                            <h3 class="fw-bold mb-0 display-6">{{ $stats['schoolsCount'] }}</h3>
                            <!-- <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
                            </div> -->
                            <p class="small text-muted mb-0 mt-2">
                                <i class="fas fa-school me-1"></i> Registered Institutions
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <a href="{{ route('admin.schools.index') }}" class="btn btn-sm btn-warning text-white w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-eye me-2"></i> View All Schools
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="stat-card bg-white rounded-4 shadow-sm h-100 position-relative overflow-hidden transition-all hover-lift">
                <div class="position-absolute top-0 start-0 h-100 w-1 bg-danger"></div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="stat-icon-sm bg-danger bg-opacity-10 rounded-circle p-2 me-2">
                                    <i class="fas fa-user-graduate text-danger"></i>
                                </div>
                                <h6 class="text-uppercase fw-semibold mb-0 small">Students</h6>
                            </div>
                            <h3 class="fw-bold mb-0 display-6">{{ $stats['studentsCount'] }}</h3>
                            <!-- <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 90%"></div>
                            </div> -->
                            <p class="small text-muted mb-0 mt-2">
                                <i class="fas fa-user-graduate me-1"></i> Enrolled Learners
                            </p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white border-0 py-3">
                    <a href="#" class="btn btn-sm btn-danger w-100 d-flex align-items-center justify-content-center">
                        <i class="fas fa-eye me-2"></i> View All Students
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4 position-relative overflow-hidden animate__animated animate__fadeIn" style="animation-delay: 0.2s;">
                <div class="position-absolute top-0 start-0 w-100 h-1 bg-primary"></div>
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-2 rounded-circle me-2">
                            <i class="fas fa-bolt text-primary"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">Quick Actions</h5>
                    </div>
                    <span class="badge bg-primary rounded-pill px-3 py-2">
                        <i class="fas fa-tasks me-1"></i> Administrative Tasks
                    </span>
                </div>
                <div class="card-body p-0">
                    <div class="row g-0">
                        <div class="col-md-6 border-end border-bottom">
                            <a href="{{ route('admin.school-divisions.create') }}" class="quick-action-item d-flex align-items-center p-4 text-decoration-none transition-all hover-bg">
                                <div class="d-flex align-items-center justify-content-center me-3 bg-primary bg-opacity-10 rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fas fa-building text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold text-dark">Add School Division</h6>
                                    <p class="text-muted small mb-0">Create a new division with schools</p>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary ms-auto px-3 py-2 rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-md-6 border-bottom">
                            <a href="{{ route('admin.schools.create') }}" class="quick-action-item d-flex align-items-center p-4 text-decoration-none transition-all hover-bg">
                                <div class="d-flex align-items-center justify-content-center me-3 bg-success bg-opacity-10 rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fas fa-school text-success fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold text-dark">Add New School</h6>
                                    <p class="text-muted small mb-0">Register a new school</p>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success ms-auto px-3 py-2 rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-md-6 border-end">
                            <a href="{{ route('admin.teacher-admins.create') }}" class="quick-action-item d-flex align-items-center p-4 text-decoration-none transition-all hover-bg">
                                <div class="d-flex align-items-center justify-content-center me-3 bg-info bg-opacity-10 rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fas fa-user-shield text-info fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold text-dark">Assign Teacher Admin</h6>
                                    <p class="text-muted small mb-0">Designate a teacher as admin</p>
                                </div>
                                <span class="badge bg-info bg-opacity-10 text-info ms-auto px-3 py-2 rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('admin.registration-keys') }}" class="quick-action-item d-flex align-items-center p-4 text-decoration-none transition-all hover-bg">
                                <div class="d-flex align-items-center justify-content-center me-3 bg-warning bg-opacity-10 rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fas fa-key text-warning fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold text-dark">Registration Keys</h6>
                                    <p class="text-muted small mb-0">Manage access keys</p>
                                </div>
                                <span class="badge bg-warning bg-opacity-10 text-warning ms-auto px-3 py-2 rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 border-top">
                    <div class="row g-0">
                        <div class="col-md-6 border-end border-bottom">
                            <a href="{{ route('admin.reports.sales') }}" class="quick-action-item d-flex align-items-center p-4 text-decoration-none transition-all hover-bg">
                                <div class="d-flex align-items-center justify-content-center me-3 bg-primary bg-opacity-10 rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fas fa-chart-line text-primary fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold text-dark">Sales Reports</h6>
                                    <p class="text-muted small mb-0">View monthly and yearly sales</p>
                                </div>
                                <span class="badge bg-primary bg-opacity-10 text-primary ms-auto px-3 py-2 rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </a>
                        </div>
                        <div class="col-md-6 border-bottom">
                            <a href="{{ route('admin.resources.index') }}" class="quick-action-item d-flex align-items-center p-4 text-decoration-none transition-all hover-bg">
                                <div class="d-flex align-items-center justify-content-center me-3 bg-danger bg-opacity-10 rounded-circle" style="width: 60px; height: 60px;">
                                    <i class="fas fa-book-reader text-danger fa-lg"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1 fw-semibold text-dark">Manage Resources</h6>
                                    <p class="text-muted small mb-0">Add and manage learning materials</p>
                                </div>
                                <span class="badge bg-danger bg-opacity-10 text-danger ms-auto px-3 py-2 rounded-pill">
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Maintenance Mode Card -->
            <div class="card border-0 shadow-sm mb-4 position-relative overflow-hidden animate__animated animate__fadeIn" style="animation-delay: 0.3s;">
                <div class="position-absolute top-0 start-0 w-100 h-1 {{ $maintenanceMode ? 'bg-danger' : 'bg-warning' }}"></div>
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="bg-{{ $maintenanceMode ? 'danger' : 'warning' }} bg-opacity-10 p-2 rounded-circle me-2">
                            <i class="fas fa-{{ $maintenanceMode ? 'exclamation-triangle' : 'tools' }} text-{{ $maintenanceMode ? 'danger' : 'warning' }}"></i>
                        </div>
                        <h5 class="mb-0 fw-bold">System Status</h5>
                    </div>
                    <span class="badge {{ $maintenanceMode ? 'bg-danger' : 'bg-success' }} rounded-pill px-3 py-2">
                        <i class="fas fa-{{ $maintenanceMode ? 'exclamation-circle' : 'check-circle' }} me-1"></i>
                        {{ $maintenanceMode ? 'Maintenance Mode' : 'Online' }}
                    </span>
                </div>
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <div class="mx-auto mb-3">
                            <div class="p-4 rounded-circle {{ $maintenanceMode ? 'bg-danger' : 'bg-success' }} bg-opacity-10 d-inline-block">
                                <i class="fas fa-{{ $maintenanceMode ? 'server' : 'shield-alt' }} {{ $maintenanceMode ? 'text-danger' : 'text-success' }} fa-3x"></i>
                            </div>
                        </div>
                        <h5 class="fw-bold mb-2">{{ $maintenanceMode ? 'Maintenance In Progress' : 'System Operational' }}</h5>
                        <p class="text-muted mb-0">
                            {{ $maintenanceMode
                                ? 'The system is currently in maintenance mode. Only administrators can access the system.'
                                : 'All systems are operational and accessible to users.'
                            }}
                        </p>
                    </div>

                    @if($maintenanceMode)
                    <div class="maintenance-details bg-light rounded-3 p-3 mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-clock text-secondary me-2"></i>
                            <span class="text-muted">Estimated duration: {{ $maintenanceDuration }} minutes</span>
                        </div>
                        @if($maintenanceMessage)
                        <div class="d-flex align-items-start">
                            <i class="fas fa-info-circle text-secondary me-2 mt-1"></i>
                            <span class="text-muted">{{ $maintenanceMessage }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <button type="button" class="btn btn-{{ $maintenanceMode ? 'success' : 'warning' }} w-100 py-3 d-flex align-items-center justify-content-center shadow-sm"
                            data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} me-2"></i>
                        <span>{{ $maintenanceMode ? 'Disable Maintenance Mode' : 'Enable Maintenance Mode' }}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
    .h-1 {
        height: 4px;
    }
    .border-opacity-0 {
        --bs-border-opacity: 0;
    }
    .hover-border-opacity-100:hover {
        --bs-border-opacity: 1;
    }
    .transition-all {
        transition: all 0.3s ease;
    }
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
    .hover-bg {
        transition: background-color 0.3s ease;
    }
    .hover-bg:hover {
        background-color: rgba(0,0,0,0.02);
    }
    .quick-action-item {
        transition: all 0.3s ease;
        border-radius: 0.5rem;
        margin: 0.25rem;
    }
    .quick-action-item:hover {
        background-color: rgba(0,0,0,0.02);
        transform: translateY(-2px);
    }
    /* Animation styles removed as requested */
    </style>
</div>
<!-- Maintenance Mode Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 bg-{{ $maintenanceMode ? 'success' : 'warning' }} bg-opacity-10 py-4">
                <div class="d-flex align-items-center">
                    <div class="bg-{{ $maintenanceMode ? 'success' : 'warning' }} bg-opacity-25 p-3 rounded-circle me-3">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} text-{{ $maintenanceMode ? 'success' : 'warning' }} fa-lg"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-1" id="maintenanceModalLabel">
                            {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance Mode
                        </h5>
                        <p class="text-muted mb-0 small">
                            {{ $maintenanceMode ? 'Return the system to normal operation' : 'Put the system in maintenance mode' }}
                        </p>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('maintenance.toggle') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    @if($maintenanceMode)
                        <div class="alert alert-info border-0 bg-info bg-opacity-10 d-flex align-items-center rounded-3 mb-4">
                            <div class="p-2 rounded-circle bg-info bg-opacity-25 me-3">
                                <i class="fas fa-info-circle text-info"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">System Currently in Maintenance Mode</h6>
                                <p class="mb-0">Disabling maintenance mode will make the system accessible to all users again.</p>
                            </div>
                        </div>

                        <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-3">
                            <div class="me-3">
                                <i class="fas fa-users text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">User Access Will Be Restored</h6>
                                <p class="text-muted mb-0 small">All users will regain access to the system</p>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-warning border-0 bg-warning bg-opacity-10 d-flex align-items-center rounded-3 mb-4">
                            <div class="p-2 rounded-circle bg-warning bg-opacity-25 me-3">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1">Important Notice</h6>
                                <p class="mb-0">Enabling maintenance mode will prevent all non-admin users from accessing the system.</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="maintenance_message" class="form-label fw-semibold">Maintenance Message</label>
                            <textarea class="form-control border bg-white" id="maintenance_message" name="maintenance_message" rows="3" placeholder="Enter a message to display to users during maintenance...">{{ \App\Models\SystemSetting::getMaintenanceMessage() }}</textarea>
                            <div class="form-text">This message will be displayed to users on the maintenance page.</div>
                        </div>

                        <div class="mb-3">
                            <label for="maintenance_duration" class="form-label fw-semibold">Maintenance Duration (minutes)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-clock text-muted"></i>
                                </span>
                                <input type="number" class="form-control border bg-white" id="maintenance_duration" name="maintenance_duration" min="1" value="30" placeholder="Enter duration in minutes">
                            </div>
                            <div class="form-text">Estimated time for maintenance to complete. This will be displayed to users.</div>
                        </div>

                        <div class="d-flex align-items-center p-3 bg-light rounded-3 mb-0">
                            <div class="me-3">
                                <i class="fas fa-user-shield text-primary fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-semibold">Admin Access Maintained</h6>
                                <p class="text-muted mb-0 small">Administrators will still have full access to the system</p>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer border-0 pt-0 pb-4 px-4">
                    <button type="button" class="btn btn-light px-4 py-2" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-{{ $maintenanceMode ? 'success' : 'warning' }} px-4 py-2 shadow-sm">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} me-2"></i>
                        {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance Mode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection