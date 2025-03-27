@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <!-- Welcome Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body py-4">
                    <div class="d-flex align-items-center">
                        <div class="display-4 me-3">
                            <i class="fas fa-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <h2 class="mb-1">Welcome to Your Dashboard</h2>
                            <p class="mb-0 opacity-75">Manage your classes, students, and academic activities</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Sections</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['sectionsCount'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                            <i class="fas fa-users fa-2x text-primary"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-3">
                    <a href="{{ route('teacher-admin.sections.index') }}" class="text-decoration-none">
                        <small class="text-primary fw-bold"><i class="fas fa-external-link-alt me-1"></i> View All Sections</small>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Subjects</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['subjectsCount'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-success bg-opacity-10 p-3">
                            <i class="fas fa-book fa-2x text-success"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-3">
                    <a href="{{ route('teacher-admin.subjects.index') }}" class="text-decoration-none">
                        <small class="text-success fw-bold"><i class="fas fa-external-link-alt me-1"></i> View All Subjects</small>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Students</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['studentsCount'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-info bg-opacity-10 p-3">
                            <i class="fas fa-user-graduate fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-3">
                    <a href="{{ route('teacher.students.index') }}" class="text-decoration-none">
                        <small class="text-info fw-bold"><i class="fas fa-external-link-alt me-1"></i> View All Students</small>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="col-md-3 mb-3 mb-md-0">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted mb-1">Today's Attendance</p>
                            <h2 class="display-5 fw-bold mb-0">{{ $stats['todayAttendance'] }}</h2>
                        </div>
                        <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                            <i class="fas fa-clipboard-check fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 py-3">
                    <a href="{{ route('teacher.attendances.index') }}" class="text-decoration-none">
                        <small class="text-warning fw-bold"><i class="fas fa-external-link-alt me-1"></i> View Attendance</small>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-bolt text-warning me-2"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <a href="{{ route('teacher.students.create') }}" class="btn btn-primary btn-lg w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-user-plus me-2"></i> Add New Student
                            </a>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <a href="{{ route('teacher.grades.index') }}" class="btn btn-success btn-lg w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-star me-2"></i> Manage Grades
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="{{ route('teacher.attendances.create') }}" class="btn btn-info btn-lg text-white w-100 d-flex align-items-center justify-content-center">
                                <i class="fas fa-clipboard-list me-2"></i> Take Attendance
                            </a>
                        </div>
                    </div>
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
                                        <th>School</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentSections as $section)
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $section->name ?? 'N/A' }}</td>
                                        <td>{{ $section->grade_level ?? 'N/A' }}</td>
                                        <td>{{ $section->school->name ?? 'N/A' }}</td>
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
                                    <tr>
                                        <td class="ps-4 fw-bold">{{ $subject->name ?? 'N/A' }}</td>
                                        <td><code>{{ $subject->code ?? 'N/A' }}</code></td>
                                        <td>{{ $subject->grade_level ?? 'N/A' }}</td>
                                        <td class="text-end pe-4">
                                            <a href="{{ route('teacher-admin.subjects.show', $subject->id) }}" class="btn btn-sm btn-outline-success">
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

<style>
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }
    
    table.table {
        vertical-align: middle;
    }
</style>
@endsection 