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
            <div class="card profile-card border-0 shadow-sm h-100">
                <div class="card-header profile-header text-white py-3">
                    <h5 class="mb-0 d-flex align-items-center">
                        <i class="fas fa-chalkboard-teacher me-2"></i>
                        <span>Teacher Profile</span>
                    </h5>
                </div>
                <div class="card-body d-flex flex-column align-items-center position-relative pt-4">
                    <div class="profile-avatar-wrapper">
                        @if(Auth::user()->school && Auth::user()->school->logo_path)
                        <div class="profile-avatar">
                            <img src="{{ Auth::user()->school->logo_url }}" alt="{{ Auth::user()->school->name }} Logo" class="img-fluid rounded">
                        </div>
                        @else
                        <div class="profile-avatar">
                            <span class="profile-avatar-text">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        </div>
                        @endif
                    </div>

                    <h4 class="mb-1 mt-3 fw-bold">{{ Auth::user()->name }}</h4>
                    <p class="text-muted mb-2">{{ Auth::user()->email }}</p>

                    <div class="badge role-badge mb-3">
                        <i class="fas fa-chalkboard-teacher me-1"></i> Teacher
                    </div>

                    @if(Auth::user()->school)
                    <div class="school-info mb-4 text-center">
                        <span class="text-muted d-block mb-1">School</span>
                        <span class="fw-medium school-name">{{ Auth::user()->school->name }}</span>
                    </div>
                    @endif

                    <div class="profile-actions d-flex gap-2 mt-auto w-100">
                        <button class="btn btn-edit-profile flex-grow-1" type="button" onclick="showTab('profile')">
                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                        </button>
                        <button class="btn btn-change-password flex-grow-1" type="button" onclick="showTab('password')">
                            <i class="fas fa-key me-2"></i> Change Password
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card profile-tabs-card border-0 shadow-sm">
                <div class="card-header p-0 border-0">
                    <ul class="nav nav-tabs profile-tabs" id="profileTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="details-tab" data-bs-toggle="tab" data-bs-target="#details-tab-pane" type="button" role="tab" aria-controls="details-tab-pane" aria-selected="true">
                                <i class="fas fa-id-card me-2"></i>Account Details
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="edit-profile-tab" data-bs-toggle="tab" data-bs-target="#edit-profile-tab-pane" type="button" role="tab" aria-controls="edit-profile-tab-pane" aria-selected="false">
                                <i class="fas fa-user-edit me-2"></i>Edit Profile
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password-tab-pane" type="button" role="tab" aria-controls="change-password-tab-pane" aria-selected="false">
                                <i class="fas fa-key me-2"></i>Change Password
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-0">
                    <div class="tab-content" id="profileTabsContent">
                        <!-- Account Details Tab -->
                        <div class="tab-pane fade show active" id="details-tab-pane" role="tabpanel" aria-labelledby="details-tab" tabindex="0">
                            <div class="profile-detail-item">
                                <div class="profile-detail-icon">
                                    <i class="fas fa-user text-info"></i>
                                </div>
                                <div class="profile-detail-content">
                                    <div class="profile-detail-label">Full Name</div>
                                    <div class="profile-detail-value">{{ Auth::user()->name }}</div>
                                </div>
                            </div>

                            <div class="profile-detail-item">
                                <div class="profile-detail-icon">
                                    <i class="fas fa-envelope text-info"></i>
                                </div>
                                <div class="profile-detail-content">
                                    <div class="profile-detail-label">Email Address</div>
                                    <div class="profile-detail-value">{{ Auth::user()->email }}</div>
                                </div>
                            </div>

                            <div class="profile-detail-item">
                                <div class="profile-detail-icon">
                                    <i class="fas fa-phone text-info"></i>
                                </div>
                                <div class="profile-detail-content">
                                    <div class="profile-detail-label">Phone Number</div>
                                    <div class="profile-detail-value">{{ Auth::user()->phone_number ?? 'Not set' }}</div>
                                </div>
                            </div>

                            <div class="profile-detail-item">
                                <div class="profile-detail-icon">
                                    <i class="fas fa-map-marker-alt text-info"></i>
                                </div>
                                <div class="profile-detail-content">
                                    <div class="profile-detail-label">Address</div>
                                    <div class="profile-detail-value">{{ Auth::user()->address ?? 'Not set' }}</div>
                                </div>
                            </div>

                            <div class="profile-detail-item">
                                <div class="profile-detail-icon">
                                    <i class="fas fa-school text-info"></i>
                                </div>
                                <div class="profile-detail-content">
                                    <div class="profile-detail-label">School</div>
                                    <div class="profile-detail-value">{{ Auth::user()->school ? Auth::user()->school->name : 'Not assigned' }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Profile Tab -->
                        <div class="tab-pane fade" id="edit-profile-tab-pane" role="tabpanel" aria-labelledby="edit-profile-tab" tabindex="0">
                            <div class="p-4">
                                <form action="{{ route('teacher.profile.update') }}" method="POST" class="profile-form">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-floating mb-3">
                                        <input type="text" class="form-control" id="name" name="name" value="{{ Auth::user()->name }}" placeholder="Enter your full name" required>
                                        <label for="name">Full Name</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ Auth::user()->email }}" placeholder="Enter your email address" required>
                                        <label for="email">Email Address</label>
                                    </div>

                                    <div class="form-floating mb-3">
                                        <input type="tel" class="form-control" id="phone_number" name="phone_number" value="{{ Auth::user()->phone_number }}" placeholder="Enter your phone number">
                                        <label for="phone_number">Phone Number</label>
                                    </div>

                                    <div class="form-floating mb-4">
                                        <textarea class="form-control" id="address" name="address" style="height: 100px" placeholder="Enter your address">{{ Auth::user()->address }}</textarea>
                                        <label for="address">Address</label>
                                    </div>

                                    <div class="d-flex justify-content-end form-actions">
                                        <button type="submit" class="btn btn-save">
                                            <i class="fas fa-save me-1"></i> Save Changes
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Change Password Tab -->
                        <div class="tab-pane fade" id="change-password-tab-pane" role="tabpanel" aria-labelledby="change-password-tab" tabindex="0">
                            <div class="p-4">
                                <form action="{{ route('teacher.password.update') }}" method="POST" class="password-form">
                                    @csrf
                                    @method('PUT')

                                    <div class="form-floating mb-3 password-field">
                                        <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Enter current password" required>
                                        <label for="current_password">Current Password</label>
                                    </div>

                                    <div class="form-floating mb-3 password-field">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password" required>
                                        <label for="password">New Password</label>
                                        <div class="password-strength mt-2">
                                            <div class="form-text mb-1">Password must be at least 8 characters long.</div>
                                            <div class="progress rounded-pill" style="height: 6px;">
                                                <div class="progress-bar bg-info" role="progressbar" style="width: 0%;" id="password-strength-bar"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-floating mb-4 password-field">
                                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="Confirm new password" required>
                                        <label for="password_confirmation">Confirm New Password</label>
                                    </div>

                                    <div class="d-flex justify-content-end form-actions">
                                        <button type="submit" class="btn btn-save">
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
        </div>
    </div>
</div>

<style>
/* Profile Card Styles */
.profile-card {
    transition: all 0.3s ease;
    overflow: hidden;
    border-radius: 0.75rem;
}

.profile-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.15) !important;
}

.profile-header {
    background: linear-gradient(145deg, #0dcaf0, #0aa2c0);
    border-bottom: 4px solid rgba(255, 255, 255, 0.2);
    position: relative;
}

.profile-avatar-wrapper {
    position: relative;
    margin-bottom: 0.5rem;
}

.profile-avatar {
    width: 110px;
    height: 110px;
    border-radius: 50%;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    border: 4px solid rgba(255, 255, 255, 0.9);
    overflow: hidden;
    transition: all 0.3s ease;
}

.profile-avatar:hover {
    transform: scale(1.05);
    box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
}

.profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: contain;
}

.profile-avatar-text {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 600;
    color: white;
    background: linear-gradient(145deg, #0dcaf0, #0aa2c0);
}

.role-badge {
    background-color: #0dcaf0;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 500;
    font-size: 0.9rem;
    box-shadow: 0 2px 10px rgba(13, 202, 240, 0.2);
}

.school-info {
    background-color: rgba(13, 202, 240, 0.05);
    padding: 0.75rem;
    border-radius: 0.5rem;
    width: 100%;
}

.school-name {
    font-size: 1.1rem;
    color: #333;
}

/* Profile Actions */
.btn-edit-profile, .btn-change-password {
    padding: 0.6rem 1rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-edit-profile {
    background-color: #fff;
    color: #0dcaf0;
    border: 2px solid #0dcaf0;
}

.btn-edit-profile:hover {
    background-color: #0dcaf0;
    color: #fff;
    box-shadow: 0 4px 10px rgba(13, 202, 240, 0.2);
}

.btn-change-password {
    background-color: #fff;
    color: #6c757d;
    border: 2px solid #6c757d;
}

.btn-change-password:hover {
    background-color: #6c757d;
    color: #fff;
    box-shadow: 0 4px 10px rgba(108, 117, 125, 0.2);
}

/* Account Details Styles */
.details-card {
    border-radius: 0.75rem;
    overflow: hidden;
}

.details-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.profile-detail-item {
    display: flex;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    transition: background-color 0.2s ease;
}

.profile-detail-item:last-child {
    border-bottom: none;
}

.profile-detail-item:hover {
    background-color: rgba(13, 202, 240, 0.03);
}

.profile-detail-icon {
    width: 40px;
    height: 40px;
    min-width: 40px;
    border-radius: 50%;
    background-color: rgba(13, 202, 240, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 1rem;
}

.profile-detail-icon i {
    font-size: 1rem;
}

.profile-detail-content {
    flex: 1;
}

.profile-detail-label {
    font-size: 0.85rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.profile-detail-value {
    font-weight: 500;
    color: #333;
}

/* Profile Tabs Styles */
.profile-tabs-card {
    border-radius: 0.75rem;
    overflow: hidden;
}

.profile-tabs {
    border-bottom: none;
    background-color: #f8f9fa;
    padding: 0.5rem 1rem 0;
    border-radius: 0.75rem 0.75rem 0 0;
}

.profile-tabs .nav-link {
    color: #6c757d;
    font-weight: 500;
    padding: 0.75rem 1.25rem;
    border: none;
    border-radius: 0.5rem 0.5rem 0 0;
    margin-right: 0.25rem;
    transition: all 0.2s ease;
}

.profile-tabs .nav-link:hover {
    color: #0dcaf0;
    background-color: rgba(13, 202, 240, 0.05);
}

.profile-tabs .nav-link.active {
    color: #0dcaf0;
    background-color: #fff;
    border-bottom: 2px solid #0dcaf0;
}

/* Form Styles */
.form-card {
    border-radius: 0.75rem;
    overflow: hidden;
}

.form-card .card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.form-floating > .form-control:focus,
.form-floating > .form-control:not(:placeholder-shown) {
    padding-top: 1.625rem;
    padding-bottom: 0.625rem;
}

.form-floating > .form-control:focus ~ label,
.form-floating > .form-control:not(:placeholder-shown) ~ label {
    opacity: 0.65;
    transform: scale(0.85) translateY(-0.5rem) translateX(0.15rem);
}

.form-control:focus {
    border-color: #0dcaf0;
    box-shadow: 0 0 0 0.25rem rgba(13, 202, 240, 0.25);
}

.password-strength {
    transition: all 0.3s ease;
}

.btn-save, .btn-cancel {
    padding: 0.6rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 500;
    transition: all 0.3s ease;
}

.btn-save {
    background-color: #0dcaf0;
    color: white;
    border: none;
}

.btn-save:hover {
    background-color: #0aa2c0;
    box-shadow: 0 4px 10px rgba(13, 202, 240, 0.2);
}

.btn-cancel {
    background-color: #f8f9fa;
    color: #6c757d;
    border: 1px solid #dee2e6;
}

.btn-cancel:hover {
    background-color: #e9ecef;
}

/* Responsive Styles */
@media (max-width: 992px) {
    .profile-card, .details-card, .form-card {
        margin-bottom: 1.5rem;
    }
}

@media (max-width: 768px) {
    .profile-avatar {
        width: 100px;
        height: 100px;
    }

    .profile-avatar-text {
        font-size: 2rem;
    }

    .form-actions {
        flex-direction: column;
        width: 100%;
    }

    .form-actions .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        margin-right: 0 !important;
    }

    .btn-cancel {
        order: 2;
    }

    .btn-save {
        order: 1;
        margin-bottom: 0.5rem;
    }

    /* Responsive tabs */
    .profile-tabs {
        padding: 0.5rem 0.5rem 0;
        display: flex;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
    }

    .profile-tabs .nav-link {
        padding: 0.6rem 0.75rem;
        font-size: 0.9rem;
    }

    .profile-tabs .nav-link i {
        margin-right: 0.25rem !important;
    }
}

@media (max-width: 576px) {
    .profile-detail-item {
        padding: 1rem;
    }

    .profile-detail-icon {
        width: 36px;
        height: 36px;
        min-width: 36px;
    }

    .profile-detail-icon i {
        font-size: 0.9rem;
    }

    .profile-detail-label {
        font-size: 0.8rem;
    }

    .profile-detail-value {
        font-size: 0.95rem;
    }
}

/* Utility Classes */
.fw-medium {
    font-weight: 500;
}

.bg-gradient-info {
    background: linear-gradient(145deg, #0dcaf0, #0aa2c0);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthBar = document.getElementById('password-strength-bar');

    if (passwordInput && strengthBar) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]+/)) strength += 25;
            if (password.match(/[A-Z]+/)) strength += 25;
            if (password.match(/[0-9]+/) || password.match(/[^a-zA-Z0-9]+/)) strength += 25;

            strengthBar.style.width = strength + '%';

            if (strength < 25) {
                strengthBar.className = 'progress-bar bg-danger';
            } else if (strength < 50) {
                strengthBar.className = 'progress-bar bg-warning';
            } else if (strength < 75) {
                strengthBar.className = 'progress-bar bg-info';
            } else {
                strengthBar.className = 'progress-bar bg-success';
            }
        });
    }

    // Tab switching from profile card buttons
    window.showTab = function(tabName) {
        const tabMap = {
            'profile': 'edit-profile-tab',
            'password': 'change-password-tab',
            'details': 'details-tab'
        };

        if (tabMap[tabName]) {
            const tabElement = document.getElementById(tabMap[tabName]);
            if (tabElement) {
                const tabTrigger = new bootstrap.Tab(tabElement);
                tabTrigger.show();

                // Scroll to the tab content if on mobile
                if (window.innerWidth < 768) {
                    const tabContent = document.getElementById(tabMap[tabName] + '-pane');
                    if (tabContent) {
                        setTimeout(() => {
                            tabContent.scrollIntoView({ behavior: 'smooth' });
                        }, 150);
                    }
                }
            }
        }
    };
});
</script>
@endsection