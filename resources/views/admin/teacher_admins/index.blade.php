@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">
                        <i class="fas fa-user-shield text-info me-2"></i>
                        Teacher Admins
                    </h5>
                </div>
                <div class="col text-end">
                    <a href="{{ route('admin.teacher-admins.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i>
                        Assign Teacher Admin
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>School</th>
                            <th>Assigned Date</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($teacherAdmins as $admin)
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
                                <td>{{ $admin->school->name }}</td>
                                <td>{{ $admin->updated_at->format('M d, Y') }}</td>
                                <td class="text-end">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeAdminModal{{ $admin->id }}">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </div>

                                    <!-- Remove Admin Modal -->
                                    <div class="modal fade" id="removeAdminModal{{ $admin->id }}" tabindex="-1" aria-labelledby="removeAdminModalLabel{{ $admin->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="removeAdminModalLabel{{ $admin->id }}">Remove Teacher Admin</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Are you sure you want to remove <strong>{{ $admin->name }}</strong> from the Teacher Admin role? They will be converted back to a regular teacher.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <form action="{{ route('admin.teacher-admins.destroy', $admin->id) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger">Remove Admin Role</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-user-shield text-muted mb-3" style="font-size: 2rem;"></i>
                                        <p class="text-muted mb-0">No Teacher Admins assigned yet</p>
                                        <a href="{{ route('admin.teacher-admins.create') }}" class="btn btn-primary btn-sm mt-3">
                                            <i class="fas fa-plus-circle me-1"></i>
                                            Assign Teacher Admin
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($teacherAdmins->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $teacherAdmins->links() }}
        </div>
    @endif
</div>

<style>
.avatar-sm {
    width: 32px;
    height: 32px;
}
</style>
@endsection 