@extends('layouts.app')


@section('content')
<div class="container py-4">
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0"><i class="fas fa-user-shield me-2"></i>Teacher Admin Profile</h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center">
                    @if(Auth::user()->school && Auth::user()->school->logo_path)
                    <div class="mb-4 mt-2">
                        <img src="{{ Auth::user()->school->logo_url }}" alt="{{ Auth::user()->school->name }} Logo" class="img-fluid rounded" style="max-height: 70px;">
                    </div>
                    @else
                    <div class="avatar mb-4 mt-2">
                        <span class="avatar-text bg-gradient-primary">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    @endif
                    <h4 class="mb-1">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-3">{{ Auth::user()->email }}</p>
                    <div class="badge bg-success mb-3 px-3 py-2">
                        <i class="fas fa-user-shield me-1"></i> Teacher Admin
                    </div>
                    
                    @if(Auth::user()->school)
                    <div class="mb-3 text-center">
                        <small class="text-muted d-block mb-1">School</small>
                        <span class="fw-medium">{{ Auth::user()->school->name }}</span>
                    </div>
                    @endif
                    
                    <div class="d-grid gap-2 mt-auto w-100">
                        <button class="btn btn-outline-primary" type="button" data-bs-toggle="collapse" data-bs-target="#editProfileForm" aria-expanded="false">
                            <i class="fas fa-user-edit me-1"></i> Edit Profile
                        </button>
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#changePasswordForm" aria-expanded="false">
                            <i class="fas fa-key me-1"></i> Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <!-- Profile Details Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0"><i class="fas fa-id-card me-2 text-primary"></i>Account Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3 py-2 border-bottom">
                        <div class="col-md-4 text-muted">Full Name</div>
                        <div class="col-md-8 fw-medium">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="row mb-3 py-2 border-bottom">
                        <div class="col-md-4 text-muted">Email Address</div>
                        <div class="col-md-8 fw-medium">{{ Auth::user()->email }}</div>
                    </div>
                    <div class="row mb-3 py-2 border-bottom">
                        <div class="col-md-4 text-muted">Phone Number</div>
                        <div class="col-md-8">{{ Auth::user()->phone_number ?? 'Not set' }}</div>
                    </div>
                    <div class="row mb-3 py-2 border-bottom">
                        <div class="col-md-4 text-muted">Address</div>
                        <div class="col-md-8">{{ Auth::user()->address ?? 'Not set' }}</div>
                    </div>
                    <div class="row py-2">
                        <div class="col-md-4 text-muted">School</div>
                        <div class="col-md-8">{{ Auth::user()->school ? Auth::user()->school->name : 'Not assigned' }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Edit Profile Form -->
            <div class="collapse mb-4" id="editProfileForm">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-user-edit me-2 text-primary"></i>Edit Profile</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('teacher-admin.profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone_number" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="phone_number" name="phone_number" value="{{ Auth::user()->phone_number }}">
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3">{{ Auth::user()->address }}</textarea>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-light me-2" data-bs-toggle="collapse" data-bs-target="#editProfileForm">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Save Changes
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Change Password Form -->
            <div class="collapse" id="changePasswordForm">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0"><i class="fas fa-key me-2 text-primary"></i>Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('teacher-admin.password.update') }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                                <div class="form-text">Password must be at least 8 characters long.</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-light me-2" data-bs-toggle="collapse" data-bs-target="#changePasswordForm">Cancel</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key me-1"></i> Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 90px;
    height: 90px;
    border-radius: 50%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.avatar-text {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 36px;
    font-weight: 500;
    color: white;
    border-radius: 50%;
}
.bg-gradient-primary {
    background: linear-gradient(145deg, #0d6efd, #0b5ed7);
}
.fw-medium {
    font-weight: 500;
}
</style>
@endsection 