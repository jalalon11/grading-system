@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-building text-primary me-2"></i> {{ $division->name }}</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.school-divisions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Divisions
                    </a>
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
        <!-- Division Information Card -->
        <div class="col-lg-5 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-info-circle text-primary me-2"></i> Division Information</h5>
                    <div class="btn-group" role="group">
                        <a href="{{ route('admin.school-divisions.edit', $division->id) }}" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit me-1"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteDivisionModal">
                            <i class="fas fa-trash me-1"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Division Name:</label>
                        <p class="fw-bold mb-0">{{ $division->name }}</p>
                    </div>
                    <div class="mb-3 pb-3 border-bottom">
                        <label class="text-muted">Division Code:</label>
                        <p class="fw-bold mb-0"><span class="badge bg-secondary">{{ $division->code }}</span></p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Address:</label>
                        <p class="mb-0">{{ $division->address ?: 'No address provided' }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted">Region:</label>
                        <p class="mb-0">{{ $division->region ?: 'No region specified' }}</p>
                    </div>
                </div>
                <div class="card-footer bg-white py-3">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i> Created: {{ $division->created_at->format('F d, Y') }}
                    </small>
                </div>
            </div>
        </div>

        <!-- Schools Card -->
        <div class="col-lg-7 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-school text-success me-2"></i> Schools ({{ $division->schools->count() }})</h5>
                    <a href="{{ route('admin.schools.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus-circle me-1"></i> Add School
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($division->schools->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Code</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($division->schools as $school)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $school->name }}</td>
                                            <td><span class="badge bg-secondary">{{ $school->code }}</span></td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <a href="{{ route('admin.schools.show', $school->id) }}" class="btn btn-info">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-warning">
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
                            <i class="fas fa-school text-muted mb-3" style="font-size: 2.5rem;"></i>
                            <h5 class="text-muted">No schools found</h5>
                            <p class="text-muted">Start by adding a school to this division.</p>
                            <a href="{{ route('admin.schools.create') }}" class="btn btn-success mt-2">
                                <i class="fas fa-plus-circle me-1"></i> Add School
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Row -->
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-chart-bar text-info me-2"></i> Division Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="p-3 bg-light rounded">
                                <h3 class="display-4 fw-bold text-primary">{{ $division->schools->count() }}</h3>
                                <p class="text-muted mb-0">Schools</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center mb-3 mb-md-0">
                            <div class="p-3 bg-light rounded">
                                <h3 class="display-4 fw-bold text-info">{{ $division->schools->flatMap->teachers->count() }}</h3>
                                <p class="text-muted mb-0">Teachers</p>
                            </div>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="p-3 bg-light rounded">
                                <h3 class="display-4 fw-bold text-success">{{ $division->created_at->diffForHumans() }}</h3>
                                <p class="text-muted mb-0">Age</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Division Modal -->
<div class="modal fade" id="deleteDivisionModal" tabindex="-1" aria-labelledby="deleteDivisionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteDivisionModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the division <strong>{{ $division->name }}</strong>?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This will also delete all schools and teachers associated with this division.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.school-divisions.destroy', $division->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 