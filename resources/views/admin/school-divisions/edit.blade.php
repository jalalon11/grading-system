@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2><i class="fas fa-building text-warning me-2"></i> Edit School Division</h2>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-primary me-2">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.school-divisions.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Divisions
                    </a>
                </div>
            </div>
            <p class="text-muted">Edit school division information for: <strong>{{ $division->name }}</strong></p>
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
                    <h5 class="mb-0"><i class="fas fa-edit me-2"></i> Division Information</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.school-divisions.update', $division->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <h6 class="text-muted mb-3">Basic Information</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label fw-bold">Division Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $division->name) }}" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="code" class="form-label fw-bold">Division Code <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $division->code) }}" required>
                                    @error('code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <div class="form-text">A unique code for this division (e.g., DIV-001)</div>
                                </div>
                                <div class="col-md-12">
                                    <label for="address" class="form-label fw-bold">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $division->address) }}</textarea>
                                    @error('address')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="col-md-12">
                                    <label for="region" class="form-label fw-bold">Region</label>
                                    <select class="form-select @error('region') is-invalid @enderror" id="region" name="region">
                                        <option value="">-- Select Region --</option>
                                        <option value="Region I - Ilocos Region" {{ old('region', $division->region) == 'Region I - Ilocos Region' ? 'selected' : '' }}>Region I - Ilocos Region</option>
                                        <option value="Region II - Cagayan Valley" {{ old('region', $division->region) == 'Region II - Cagayan Valley' ? 'selected' : '' }}>Region II - Cagayan Valley</option>
                                        <option value="Region III - Central Luzon" {{ old('region', $division->region) == 'Region III - Central Luzon' ? 'selected' : '' }}>Region III - Central Luzon</option>
                                        <option value="Region IV-A - CALABARZON" {{ old('region', $division->region) == 'Region IV-A - CALABARZON' ? 'selected' : '' }}>Region IV-A - CALABARZON</option>
                                        <option value="MIMAROPA Region" {{ old('region', $division->region) == 'MIMAROPA Region' ? 'selected' : '' }}>MIMAROPA Region</option>
                                        <option value="Region V - Bicol Region" {{ old('region', $division->region) == 'Region V - Bicol Region' ? 'selected' : '' }}>Region V - Bicol Region</option>
                                        <option value="Region VI - Western Visayas" {{ old('region', $division->region) == 'Region VI - Western Visayas' ? 'selected' : '' }}>Region VI - Western Visayas</option>
                                        <option value="Region VII - Central Visayas" {{ old('region', $division->region) == 'Region VII - Central Visayas' ? 'selected' : '' }}>Region VII - Central Visayas</option>
                                        <option value="Region VIII - Eastern Visayas" {{ old('region', $division->region) == 'Region VIII - Eastern Visayas' ? 'selected' : '' }}>Region VIII - Eastern Visayas</option>
                                        <option value="Region IX - Zamboanga Peninsula" {{ old('region', $division->region) == 'Region IX - Zamboanga Peninsula' ? 'selected' : '' }}>Region IX - Zamboanga Peninsula</option>
                                        <option value="Region X - Northern Mindanao" {{ old('region', $division->region) == 'Region X - Northern Mindanao' ? 'selected' : '' }}>Region X - Northern Mindanao</option>
                                        <option value="Region XI - Davao Region" {{ old('region', $division->region) == 'Region XI - Davao Region' ? 'selected' : '' }}>Region XI - Davao Region</option>
                                        <option value="Region XII - SOCCSKSARGEN" {{ old('region', $division->region) == 'Region XII - SOCCSKSARGEN' ? 'selected' : '' }}>Region XII - SOCCSKSARGEN</option>
                                        <option value="Region XIII - Caraga" {{ old('region', $division->region) == 'Region XIII - Caraga' ? 'selected' : '' }}>Region XIII - Caraga</option>
                                        <option value="NCR - National Capital Region" {{ old('region', $division->region) == 'NCR - National Capital Region' ? 'selected' : '' }}>NCR - National Capital Region</option>
                                        <option value="CAR - Cordillera Administrative Region" {{ old('region', $division->region) == 'CAR - Cordillera Administrative Region' ? 'selected' : '' }}>CAR - Cordillera Administrative Region</option>
                                        <option value="BARMM - Bangsamoro Autonomous Region in Muslim Mindanao" {{ old('region', $division->region) == 'BARMM - Bangsamoro Autonomous Region in Muslim Mindanao' ? 'selected' : '' }}>BARMM - Bangsamoro Autonomous Region in Muslim Mindanao</option>
                                        <option value="NIR - Negros Island Region" {{ old('region', $division->region) == 'NIR - Negros Island Region' ? 'selected' : '' }}>NIR - Negros Island Region</option>
                                    </select>
                                    @error('region')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-warning px-4">
                                <i class="fas fa-save me-1"></i> Update Division
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-school text-success me-2"></i> Schools in this Division</h5>
                    <a href="{{ route('admin.schools.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus-circle me-1"></i> Add New School
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($division->schools && $division->schools->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Code</th>
                                        <th scope="col">Address</th>
                                        <th scope="col" class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($division->schools as $school)
                                        <tr>
                                            <th scope="row">{{ $loop->iteration }}</th>
                                            <td>{{ $school->name }}</td>
                                            <td><span class="badge bg-secondary">{{ $school->code }}</span></td>
                                            <td>{{ Str::limit($school->address, 30) ?: 'N/A' }}</td>
                                            <td class="text-end">
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
                            <p class="text-muted">This division has no schools yet.</p>
                            <a href="{{ route('admin.schools.create') }}" class="btn btn-success mt-2">
                                <i class="fas fa-plus-circle me-1"></i> Add New School
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 