@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-user-plus text-info me-2"></i> Add New Teacher</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Teachers
                    </a>
                </div>
            </div>
            <p class="text-muted">Create a new teacher account in the system.</p>
        </div>
    </div>

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-edit me-2"></i> Teacher Information</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.teachers.store') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Credentials</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">School Assignment</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="school_id" class="form-label fw-bold">School <span class="text-danger">*</span></label>
                                    <select class="form-select @error('school_id') is-invalid @enderror" id="school_id" name="school_id" required>
                                        <option value="">-- Select School --</option>
                                        @foreach($schools as $school)
                                            <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                                                {{ $school->name }} ({{ $school->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('school_id')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-info px-4">
                                <i class="fas fa-save me-1"></i> Create Teacher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 