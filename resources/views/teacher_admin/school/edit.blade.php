@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-school text-primary me-2"></i> Edit School Information</h2>
                <a href="{{ route('teacher-admin.school.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Back to School Overview
                </a>
            </div>
            <p class="text-muted">Update your school's information below. The school code can only be changed by system administrators.</p>
            @if(!$canUpdate)
            <div class="alert alert-warning">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Update Restricted:</strong> School information can only be updated once every 60 days. You can update again in {{ $school->days_until_next_update }} {{ Str::plural('day', $school->days_until_next_update) }}. You can view the information but cannot make changes at this time.
            </div>
            @else
            <div class="alert alert-info">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Important:</strong> School information can only be updated once every 60 days. After saving these changes, you'll need to wait 60 days before making further updates.
            </div>
            @endif
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
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> School Information</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('teacher-admin.school.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="name" class="form-label fw-bold">School Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $school->name) }}" required {{ !$canUpdate ? 'disabled' : '' }}>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="code" class="form-label fw-bold">School Code</label>
                                <input type="text" class="form-control bg-light" id="code" value="{{ $school->code }}" readonly>
                                <div class="form-text">School code cannot be changed. Contact system administrators in support if needed.</div>
                            </div>

                            <div class="col-md-12">
                                <label for="address" class="form-label fw-bold">Address</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" {{ !$canUpdate ? 'disabled' : '' }}>{{ old('address', $school->address) }}</textarea>
                                @error('address')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="principal" class="form-label fw-bold">Principal</label>
                                <input type="text" class="form-control @error('principal') is-invalid @enderror" id="principal" name="principal" value="{{ old('principal', $school->principal) }}" {{ !$canUpdate ? 'disabled' : '' }}>
                                @error('principal')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="logo" class="form-label fw-bold">School Logo</label>
                                <div class="row align-items-center">
                                    <div class="col-auto mb-3">
                                        @if($school->logo_path)
                                            <img src="{{ $school->logo_url }}" alt="{{ $school->name }} Logo" class="img-thumbnail" style="max-width: 120px; max-height: 120px; object-fit: contain;">
                                        @else
                                            <div class="bg-primary bg-opacity-10 p-4 rounded-circle">
                                                <i class="fas fa-school text-primary fa-4x"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col">
                                        <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo" accept="image/*" {{ !$canUpdate ? 'disabled' : '' }}>
                                        @error('logo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div class="form-text">Upload a new school logo (JPEG, PNG, GIF - max 2MB). The logo appears on reports, grade slips, and certificates.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label fw-bold">Grade Levels</label>
                                <div class="card">
                                    <div class="card-body bg-light">
                                        <p class="mb-0">
                                            <i class="fas fa-info-circle text-primary me-2"></i>
                                            Grade levels can only be modified by system administrators as they affect your subscription.
                                        </p>
                                        <div class="mt-3">
                                            @php
                                                $gradeLevels = is_array($school->grade_levels) ? $school->grade_levels :
                                                            (is_string($school->grade_levels) ? json_decode($school->grade_levels, true) : []);
                                                sort($gradeLevels);
                                            @endphp
                                            @foreach($gradeLevels as $grade)
                                                <span class="badge bg-primary me-1">{{ $grade }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4">
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('teacher-admin.school.index') }}" class="btn btn-secondary me-2">
                                        <i class="fas fa-arrow-left me-1"></i> Back
                                    </a>
                                    @if($canUpdate)
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-primary" disabled>
                                        <i class="fas fa-save me-1"></i> Save Changes
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
