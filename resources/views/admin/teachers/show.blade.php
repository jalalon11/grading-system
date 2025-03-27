@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-chalkboard-teacher text-info me-2"></i> {{ $teacher->name }}</h2>
                <div>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Teachers
                    </a>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTeacherModal">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i> Teacher Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="avatar-circle mx-auto mb-3">
                            <span class="initials">{{ substr($teacher->name, 0, 1) }}</span>
                        </div>
                        <h4>{{ $teacher->name }}</h4>
                        <p class="text-muted mb-0">
                            <i class="fas fa-envelope me-1"></i> {{ $teacher->email }}
                        </p>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">School:</label>
                        <p class="fw-bold mb-0">
                            @if($teacher->school)
                                <span class="badge bg-primary">{{ $teacher->school->name }}</span>
                            @else
                                <span class="badge bg-secondary">Not Assigned</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Role:</label>
                        <p class="fw-bold mb-0">
                            <span class="badge bg-info">Teacher</span>
                        </p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted">Member Since:</label>
                        <p class="mb-0">{{ $teacher->created_at->format('F d, Y') }}</p>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <button type="button" class="btn btn-warning w-100" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                        <i class="fas fa-key me-1"></i> Reset Password
                    </button>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-clipboard-list text-success me-2"></i> Teaching Information</h5>
                </div>
                <div class="card-body">
                    <!-- Teaching assignments -->
                    @if($teachingAssignments->count() > 0 || $sections->count() > 0)
                        <!-- Sections where teacher is adviser -->
                        @if($sections->count() > 0)
                            <h6 class="fw-bold mb-3">Sections as Adviser</h6>
                            <div class="table-responsive mb-4">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Section Name</th>
                                            <th>Grade Level</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($sections as $section)
                                            <tr>
                                                <td>{{ $section->name }}</td>
                                                <td>
                                                    <span class="badge bg-primary">Grade {{ $section->grade_level }}</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- Subject teaching assignments -->
                        @if($teachingAssignments->count() > 0)
                            <h6 class="fw-bold mb-3">Subject Assignments</h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Section</th>
                                            <th>Grade Level</th>
                                            <th>Subject</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($teachingAssignments as $assignment)
                                            <tr>
                                                <td>{{ $assignment->section_name }}</td>
                                                <td>
                                                    <span class="badge bg-info">Grade {{ $assignment->grade_level }}</span>
                                                </td>
                                                <td>{{ $assignment->subject_name }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i> No teaching assignments are available yet.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resetPasswordModalLabel">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.teachers.reset-password', $teacher->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Reset password for: <strong>{{ $teacher->name }}</strong></p>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required minlength="6">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Teacher Modal -->
<div class="modal fade" id="deleteTeacherModal" tabindex="-1" aria-labelledby="deleteTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteTeacherModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the teacher <strong>{{ $teacher->name }}</strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.teachers.destroy', $teacher->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .avatar-circle {
        width: 100px;
        height: 100px;
        background-color: #17a2b8;
        text-align: center;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .initials {
        font-size: 40px;
        color: white;
        font-weight: bold;
    }
</style>
@endsection 