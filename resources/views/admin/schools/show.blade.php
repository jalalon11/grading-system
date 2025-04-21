@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-school text-success me-2"></i> {{ $school->name }}</h2>
                <div>
                    <a href="{{ route('admin.schools.index') }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-arrow-left me-1"></i> Back to Schools
                    </a>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteSchoolModal">
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
        <!-- School Information Card -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i> School Information</h5>
                </div>
                <div class="card-body">
                    @if($school->logo_path)
                    <div class="text-center mb-4">
                        <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="img-fluid" style="max-height: 150px;">
                    </div>
                    @endif
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">School Name:</label>
                        <p class="fw-bold mb-0">{{ $school->name }}</p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">School Code:</label>
                        <p class="fw-bold mb-0"><span class="badge bg-secondary">{{ $school->code }}</span></p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Division:</label>
                        <p class="fw-bold mb-0"><span class="badge bg-primary">{{ $school->schoolDivision->name }}</span></p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Grade Levels:</label>
                        <div>
                            @php
                                $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels : json_decode($school->grade_levels) ?? [];
                            @endphp
                            @if(count($gradeLevels) > 0)
                                @foreach($gradeLevels as $level)
                                    <span class="badge bg-success me-1 mb-1">
                                        {{ $level == 'K' ? 'Kindergarten' : 'Grade '.$level }}
                                    </span>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No grade levels defined</p>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Address:</label>
                        <p class="mb-0">{{ $school->address ?: 'No address provided' }}</p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Principal:</label>
                        <p class="mb-0 fw-bold">{{ $school->principal ?: 'No principal assigned' }}</p>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted">Status:
                            @if($school->is_active ?? true)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Inactive</span>
                            @endif
                        </span>
                        <div>
                            @if($school->is_active ?? true)
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#disableSchoolModal">
                                    <i class="fas fa-ban me-1"></i> Disable
                                </button>
                            @else
                                <form action="{{ route('admin.schools.enable', $school->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-check-circle me-1"></i> Enable
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Subscription:
                            @if($school->onTrial())
                                <span class="badge bg-info">Trial</span>
                                <small class="text-muted">({{ $school->remaining_trial_days === 'Unlimited' ? 'Unlimited' : $school->remaining_trial_days . ' remaining' }})</small>
                            @elseif($school->hasActiveSubscription())
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-danger">Expired</span>
                            @endif
                        </span>
                        <a href="{{ route('admin.schools.billing', $school->id) }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-cog me-1"></i> Billing Settings
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Teacher Admins Card -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user-shield text-info me-2"></i>
                            Teacher Admins
                            <span class="badge bg-info ms-2">{{ $teacherAdmins->count() }}/2</span>
                        </h5>
                        @if($teacherAdmins->count() < 2)
                            <a href="{{ route('admin.teacher-admins.create') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-plus-circle me-1"></i>
                                Assign Teacher Admin
                            </a>
                        @endif
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($teacherAdmins->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Assigned Date</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teacherAdmins as $admin)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3 bg-info bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                        <span class="text-info">{{ strtoupper(substr($admin->name, 0, 1)) }}</span>
                                                    </div>
                                                    {{ $admin->name }}
                                                </div>
                                            </td>
                                            <td>{{ $admin->email }}</td>
                                            <td>{{ $admin->updated_at->format('M d, Y') }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('admin.teacher-admins.destroy', $admin->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to remove {{ $admin->name }} from Teacher Admin role?')">
                                                        <i class="fas fa-user-times"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-user-shield text-muted mb-3" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">No Teacher Admins assigned yet</p>
                                <a href="{{ route('admin.teacher-admins.create') }}" class="btn btn-info btn-sm mt-3">
                                    <i class="fas fa-plus-circle me-1"></i>
                                    Assign Teacher Admin
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Teachers List Card -->
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-chalkboard-teacher text-success me-2"></i>
                            Teachers
                            <span class="badge bg-success ms-2">{{ $teachers->count() }}</span>
                        </h5>
                        <a href="{{ route('admin.teachers.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus-circle me-1"></i>
                            Add Teacher
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($teachers->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Subjects</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($teachers as $teacher)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm me-3 bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center">
                                                        <span class="text-success">{{ strtoupper(substr($teacher->name, 0, 1)) }}</span>
                                                    </div>
                                                    {{ $teacher->name }}
                                                </div>
                                            </td>
                                            <td>{{ $teacher->email }}</td>
                                            <td>
                                                @if($teacher->subjects->count() > 0)
                                                    @foreach($teacher->subjects as $subject)
                                                        <span class="badge bg-primary me-1">{{ $subject->name }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted">No subjects assigned</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.teachers.show', $teacher->id) }}" class="btn btn-info btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <div class="d-flex flex-column align-items-center">
                                <i class="fas fa-chalkboard-teacher text-muted mb-3" style="font-size: 2rem;"></i>
                                <p class="text-muted mb-0">No teachers assigned to this school yet</p>
                                <a href="{{ route('admin.teachers.create') }}" class="btn btn-success btn-sm mt-3">
                                    <i class="fas fa-plus-circle me-1"></i>
                                    Add Teacher
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete School Modal -->
<div class="modal fade" id="deleteSchoolModal" tabindex="-1" aria-labelledby="deleteSchoolModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSchoolModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the school <strong>{{ $school->name }}</strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This will also delete all data associated with this school including teachers, students, and grades.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.schools.destroy', $school->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Disable School Modal -->
<div class="modal fade" id="disableSchoolModal" tabindex="-1" aria-labelledby="disableSchoolModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="disableSchoolModalLabel">Confirm Disable</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to disable the school <strong>{{ $school->name }}</strong>?</p>
                <p class="text-warning"><i class="fas fa-exclamation-triangle me-1"></i> Disabling the school will prevent teachers and students from accessing it.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.schools.disable', $school->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-danger">Disable</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
}
</style>
@endsection