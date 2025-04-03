@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-school text-success me-2"></i> Schools</h2>
                <a href="{{ route('admin.schools.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-1"></i> Add New School
                </a>
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

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-school text-success fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Schools</h6>
                            <h3 class="mb-0">{{ $schools->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-chalkboard-teacher text-info fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Teachers</h6>
                            <h3 class="mb-0">{{ $schools->flatMap->teachers->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-building text-primary fa-2x"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="text-muted mb-1">Total Divisions</h6>
                            <h3 class="mb-0">{{ $schools->pluck('schoolDivision')->unique()->count() }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('admin.schools.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="fas fa-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0" placeholder="Search schools..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="division" class="form-select">
                        <option value="">All Divisions</option>
                        @foreach($schools->pluck('schoolDivision')->unique() as $division)
                            <option value="{{ $division->id }}" {{ request('division') == $division->id ? 'selected' : '' }}>
                                {{ $division->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="sort" class="form-select">
                        <option value="">Sort by...</option>
                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name</option>
                        <option value="division" {{ request('sort') == 'division' ? 'selected' : '' }}>Division</option>
                        <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Date Created</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="order" class="form-select">
                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>Ascending</option>
                        <option value="desc" {{ request('order') == 'desc' ? 'selected' : '' }}>Descending</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="fas fa-filter me-1"></i> Apply
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Schools Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">School</th>
                            <th scope="col">Division</th>
                            <th scope="col">Code</th>
                            <th scope="col">Teachers</th>
                            <th scope="col" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($schools as $school)
                            <tr>
                                <th scope="row">{{ $loop->iteration }}</th>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 me-2">
                                            @if($school->logo_path)
                                                <img src="{{ asset($school->logo_path) }}" alt="{{ $school->name }} Logo" class="rounded" style="width: 40px; height: 40px; object-fit: contain;">
                                            @else
                                                <div class="bg-success bg-opacity-10 rounded-circle p-2">
                                                    <i class="fas fa-school text-success"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $school->name }}</h6>
                                            <small class="text-muted">{{ Str::limit($school->address, 30) ?: 'No address' }}</small>
                                            @if($school->principal)
                                                <small class="d-block text-primary">Principal: {{ $school->principal }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ $school->schoolDivision->name }}
                                    </span>
                                </td>
                                <td><span class="badge bg-secondary">{{ $school->code }}</span></td>
                                <td>
                                    <span class="badge bg-info rounded-pill">
                                        {{ $school->teachers->count() }} {{ Str::plural('Teacher', $school->teachers->count()) }}
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('admin.schools.show', $school->id) }}" class="btn btn-info" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.schools.edit', $school->id) }}" class="btn btn-warning" title="Edit School">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger" title="Delete School" data-bs-toggle="modal" data-bs-target="#deleteSchoolModal{{ $school->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-school fa-2x mb-3"></i>
                                        <h5>No Schools Found</h5>
                                        <p>Start by adding a new school.</p>
                                        <a href="{{ route('admin.schools.create') }}" class="btn btn-success">
                                            <i class="fas fa-plus-circle me-1"></i> Add New School
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
</div>

<!-- Delete School Modals -->
@foreach($schools as $school)
<div class="modal fade" id="deleteSchoolModal{{ $school->id }}" tabindex="-1" aria-labelledby="deleteSchoolModalLabel{{ $school->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteSchoolModalLabel{{ $school->id }}">Delete School</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the school "{{ $school->name }}"?</p>
                <p class="text-danger"><i class="fas fa-exclamation-triangle me-1"></i> This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.schools.destroy', $school->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i> Delete School
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection 