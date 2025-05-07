@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold"><i class="fas fa-tachometer-alt text-primary me-2"></i> Admin Dashboard</h2>
            <p class="text-muted">Welcome to the School Grading System administration panel.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <div class="d-inline-block p-2 bg-light rounded-3 shadow-sm">
                <span class="text-muted me-2">Current Date:</span>
                <strong>{{ date('F d, Y') }}</strong>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-semibold">Teachers</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['teachersCount'] }}</h3>
                            <p class="small text-success mb-0">
                                <i class="fas fa-user-tie"></i> Active Faculty Members
                            </p>
                        </div>
                        <div class="dashboard-icon bg-primary bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-chalkboard-teacher text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-2">
                    <a href="{{ route('admin.teachers.index') }}" class="text-decoration-none">
                        <small><i class="fas fa-eye me-1"></i> View All Teachers</small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-semibold">School Divisions</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['schoolDivisionsCount'] }}</h3>
                            <p class="small text-info mb-0">
                                <i class="fas fa-building"></i> Educational Divisions
                            </p>
                        </div>
                        <div class="dashboard-icon bg-info bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-building text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-2">
                    <a href="{{ route('admin.school-divisions.index') }}" class="text-decoration-none">
                        <small><i class="fas fa-eye me-1"></i> View All Divisions</small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-semibold">Schools</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['schoolsCount'] }}</h3>
                            <p class="small text-warning mb-0">
                                <i class="fas fa-school"></i> Registered Institutions
                            </p>
                        </div>
                        <div class="dashboard-icon bg-warning bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-school text-warning fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-2">
                    <a href="{{ route('admin.schools.index') }}" class="text-decoration-none">
                        <small><i class="fas fa-eye me-1"></i> View All Schools</small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted text-uppercase fw-semibold">Students</h6>
                            <h3 class="fw-bold mb-0">{{ $stats['studentsCount'] }}</h3>
                            <p class="small text-danger mb-0">
                                <i class="fas fa-user-graduate"></i> Enrolled Learners
                            </p>
                        </div>
                        <div class="dashboard-icon bg-danger bg-opacity-10 rounded-circle p-3">
                            <i class="fas fa-user-graduate text-danger fa-2x"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light border-0 py-2">
                    <a href="#" class="text-decoration-none">
                        <small><i class="fas fa-eye me-1"></i> View All Students</small>
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-3 p-3">
                                <i class="fas fa-user-shield text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-1">{{ $stats['teacherAdminsCount'] }}</h3>
                            <p class="text-muted mb-0">Teacher Admins</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions</h5>
                    <span class="badge bg-primary">Administrative Tasks</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('admin.school-divisions.create') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center me-3 bg-primary bg-opacity-10 rounded-circle" style="width: 45px; height: 45px;">
                                <i class="fas fa-plus-circle text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Add New School Division</h6>
                                <p class="text-muted small mb-0">Create a new division with schools and teachers</p>
                            </div>
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.schools.create') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center me-3 bg-success bg-opacity-10 rounded-circle" style="width: 45px; height: 45px;">
                                <i class="fas fa-plus-circle text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Add New School</h6>
                                <p class="text-muted small mb-0">Register a new school with teachers</p>
                            </div>
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.teacher-admins.create') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center me-3 bg-info bg-opacity-10 rounded-circle" style="width: 45px; height: 45px;">
                                <i class="fas fa-user-shield text-info"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Assign Teacher Admin</h6>
                                <p class="text-muted small mb-0">Designate a teacher as Teacher Admin</p>
                            </div>
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.registration-keys') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center me-3 bg-warning bg-opacity-10 rounded-circle" style="width: 45px; height: 45px;">
                                <i class="fas fa-key text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Manage Registration Keys</h6>
                                <p class="text-muted small mb-0">Generate and reset registration access keys</p>
                            </div>
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                        <a href="{{ route('admin.resources.index') }}" class="list-group-item list-group-item-action py-3 px-4 d-flex align-items-center">
                            <div class="d-flex align-items-center justify-content-center me-3 bg-danger bg-opacity-10 rounded-circle" style="width: 45px; height: 45px;">
                                <i class="fas fa-book-reader text-danger"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Manage Learning Resources</h6>
                                <p class="text-muted small mb-0">Add and manage resource materials for teachers</p>
                            </div>
                            <i class="fas fa-chevron-right ms-auto"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <!-- Maintenance Mode Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-tools text-warning me-2"></i> Maintenance Mode</h5>
                    @php
                        $maintenanceMode = \App\Models\SystemSetting::isMaintenanceMode();
                    @endphp
                    <span class="badge {{ $maintenanceMode ? 'bg-danger' : 'bg-success' }}">
                        {{ $maintenanceMode ? 'Enabled' : 'Disabled' }}
                    </span>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-3">
                        {{ $maintenanceMode
                            ? 'The system is currently in maintenance mode. Only administrators can access the system.'
                            : 'The system is currently active and accessible to all users.'
                        }}
                    </p>

                    <button type="button" class="btn btn-{{ $maintenanceMode ? 'success' : 'warning' }} w-100"
                            data-bs-toggle="modal" data-bs-target="#maintenanceModal">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} me-2"></i>
                        {{ $maintenanceMode ? 'Disable Maintenance Mode' : 'Enable Maintenance Mode' }}
                    </button>
                </div>
            </div>

            <!-- System Information Card -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle text-info me-2"></i> System Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span><i class="fas fa-code-branch text-muted me-2"></i> Version:</span>
                            <span class="badge bg-success rounded-pill">1.0</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span><i class="fas fa-users text-muted me-2"></i> Developer:</span>
                            <span>Vincent Jalalon</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span><i class="fas fa-headset text-muted me-2"></i> Support:</span>
                            <span>vinz0779@gmail.com</span>
                        </li>
                        <li class="list-group-item px-0 d-flex justify-content-between">
                            <span><i class="fas fa-file-alt text-muted me-2"></i> Documentation:</span>
                            <a href="#" class="text-decoration-none">View Docs</a>
                        </li>
                    </ul>
                </div>
                <div class="card-footer bg-light">
                    <div class="small text-center">
                        <p class="mb-0">Need assistance? <a href="#" class="text-decoration-none">Contact support</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Maintenance Mode Modal -->
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-labelledby="maintenanceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="maintenanceModalLabel">
                    <i class="fas fa-tools me-2 text-warning"></i>
                    {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance Mode
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('maintenance.toggle') }}" method="POST">
                @csrf
                <div class="modal-body">
                    @if($maintenanceMode)
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Disabling maintenance mode will make the system accessible to all users again.
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Enabling maintenance mode will prevent all non-admin users from accessing the system.
                        </div>

                        <div class="mb-3">
                            <label for="maintenance_message" class="form-label">Maintenance Message</label>
                            <textarea class="form-control" id="maintenance_message" name="maintenance_message" rows="3" placeholder="Enter a message to display to users during maintenance...">{{ \App\Models\SystemSetting::getMaintenanceMessage() }}</textarea>
                            <div class="form-text">This message will be displayed to users on the maintenance page.</div>
                        </div>

                        <div class="mb-3">
                            <label for="maintenance_duration" class="form-label">Maintenance Duration (minutes)</label>
                            <input type="number" class="form-control" id="maintenance_duration" name="maintenance_duration" min="1" value="30" placeholder="Enter duration in minutes">
                            <div class="form-text">Estimated time for maintenance to complete. This will be displayed to users.</div>
                        </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-{{ $maintenanceMode ? 'success' : 'warning' }}">
                        <i class="fas fa-{{ $maintenanceMode ? 'power-off' : 'tools' }} me-2"></i>
                        {{ $maintenanceMode ? 'Disable' : 'Enable' }} Maintenance Mode
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection