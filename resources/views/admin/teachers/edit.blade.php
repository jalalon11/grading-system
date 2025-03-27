@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-user-edit text-warning me-2"></i> Edit Teacher</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Teachers
                    </a>
                </div>
            </div>
            <p class="text-muted">Edit teacher information for: <strong>{{ $teacher->name }}</strong></p>
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
                    <form method="POST" action="{{ route('admin.teachers.update', $teacher->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $teacher->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label fw-bold">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $teacher->email) }}" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Change Password (optional)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="password" class="form-label fw-bold">New Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                    <small class="form-text text-muted">Leave blank to keep current password</small>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="password_confirmation" class="form-label fw-bold">Confirm New Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
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
                                            <option value="{{ $school->id }}" {{ old('school_id', $teacher->school_id) == $school->id ? 'selected' : '' }}>
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
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-1"></i> Update Teacher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection