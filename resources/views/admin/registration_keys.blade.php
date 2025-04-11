@extends('layouts.app')

@push('styles')
<style>
    .key-code {
        font-family: monospace;
        background-color: #f8f9fa;
        padding: 0.25rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        max-width: 200px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        display: inline-block;
    }

    /* Custom pagination styling */
    .pagination {
        margin-bottom: 0;
    }

    .page-item.active .page-link {
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .page-link {
        color: #4e73df;
    }

    .page-link:hover {
        color: #2e59d9;
    }

    .table .copy-btn, .table .copy-bulk-btn {
        padding: 0.125rem 0.375rem;
    }

    .bulk-key {
        font-family: monospace;
        font-size: 0.875rem;
        background-color: transparent;
    }

    .bulk-keys-container {
        border-radius: 0.25rem;
    }

    .bulk-keys-container .table {
        margin-bottom: 0;
    }

    .bulk-keys-container .table th,
    .bulk-keys-container .table td {
        padding: 0.5rem;
        vertical-align: middle;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold"><i class="fas fa-key text-primary me-2"></i>Registration Key Management</h2>
            <p class="text-muted">Generate and manage registration keys for teachers and students.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('generated_key'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-info-circle me-2 fs-4"></i>
                                <div>
                                    <strong>New registration key generated</strong>
                                    @if(session('key_type'))
                                    <div class="text-muted small">Type: {{ ucfirst(session('key_type')) }} @if(session('school_name')) | School: {{ session('school_name') }}@endif</div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2 p-3 bg-light rounded d-flex align-items-center">
                                <code class="me-2 fs-5" id="keyDisplay">{{ session('generated_key') }}</code>
                                <button class="btn btn-sm btn-outline-secondary" onclick="copyToClipboard()" title="Copy to clipboard">
                                    <i class="fas fa-copy"></i>
                                </button>
                            </div>
                            <small class="mt-2 text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Save this key, it won't be displayed again!</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('bulk_keys'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="d-flex flex-column">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-layer-group me-2 fs-4"></i>
                                <div>
                                    <strong>{{ session('bulk_count') }} Teacher Keys Generated</strong>
                                    @if(session('school_name'))
                                    <div class="text-muted small">School: {{ session('school_name') }}</div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="fw-bold">Registration Keys:</span>
                                    <button class="btn btn-sm btn-outline-secondary" onclick="copyAllKeys()" title="Copy all keys">
                                        <i class="fas fa-copy me-1"></i>Copy All
                                    </button>
                                </div>
                                <div class="bulk-keys-container" style="max-height: 200px; overflow-y: auto;">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Key</th>
                                                <th width="50">Copy</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach(session('bulk_keys') as $index => $key)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><code class="bulk-key" id="bulk-key-{{ $index }}">{{ $key }}</code></td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-outline-secondary copy-bulk-btn"
                                                            data-key-index="{{ $index }}"
                                                            title="Copy to clipboard">
                                                        <i class="fas fa-copy"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <small class="mt-2 text-danger"><i class="fas fa-exclamation-triangle me-1"></i>Save these keys, they won't be displayed again!</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <!-- Key Management Cards -->
                    <div class="row mb-4">
                        <!-- Card Headers - Tabs -->
                        <div class="col-12 mb-3">
                            <ul class="nav nav-tabs" id="keyManagementTabs" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="reset-master-tab" data-bs-toggle="tab" data-bs-target="#reset-master" type="button" role="tab" aria-controls="reset-master" aria-selected="true">
                                        <i class="fas fa-key text-primary me-2"></i>Reset Master Key
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="generate-single-tab" data-bs-toggle="tab" data-bs-target="#generate-single" type="button" role="tab" aria-controls="generate-single" aria-selected="false">
                                        <i class="fas fa-ticket-alt text-success me-2"></i>Generate Single Key
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="generate-bulk-tab" data-bs-toggle="tab" data-bs-target="#generate-bulk" type="button" role="tab" aria-controls="generate-bulk" aria-selected="false">
                                        <i class="fas fa-layer-group text-warning me-2"></i>Bulk Generate Teacher Keys
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Card Contents - Tab Panes -->
                        <div class="col-12">
                            <div class="tab-content" id="keyManagementTabContent">
                                <!-- Reset Master Key Tab -->
                                <div class="tab-pane fade show active" id="reset-master" role="tabpanel" aria-labelledby="reset-master-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <p class="text-muted">
                                                The master key allows unlimited registrations. It never expires and can be used multiple times.
                                            </p>

                                            <form action="{{ route('admin.reset-master-key') }}" method="POST">
                                                @csrf

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="current_password" class="form-label">Your Account Password</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                                                        </div>
                                                        <div class="form-text text-info">This is your admin account password, not the master key.</div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="new_key" class="form-label">New Master Key</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                                                            <input type="text" class="form-control" id="new_key" name="new_key" required minlength="6">
                                                        </div>
                                                        <div class="form-text">Must be at least 6 characters long.</div>
                                                    </div>
                                                </div>

                                                <div class="d-grid col-md-4 mx-auto mt-3">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-sync-alt me-2"></i>Reset Master Key
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Generate Single Key Tab -->
                                <div class="tab-pane fade" id="generate-single" role="tabpanel" aria-labelledby="generate-single-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <p class="text-muted">
                                                One-time keys can only be used once for registration. You can specify a school, key type, and expiration date.
                                            </p>

                                            <form action="{{ route('admin.generate-one-time-key') }}" method="POST">
                                                @csrf

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="key_type" class="form-label">Key Type</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                                            <select class="form-select" id="key_type" name="key_type" required>
                                                                <option value="teacher">Teacher</option>
                                                                <option value="teacher_admin">Teacher Admin</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-text">Select the type of user this key is for. Note: Maximum 2 Teacher Admins per school.</div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="school_id" class="form-label">School <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-school"></i></span>
                                                            <select class="form-select" id="school_id" name="school_id" required>
                                                                <option value="">-- Select School --</option>
                                                                @php
                                                                    $schools = \App\Models\School::orderBy('name')->get();
                                                                    $teacherAdminCounts = [];

                                                                    // Get teacher admin counts for all schools
                                                                    foreach($schools as $school) {
                                                                        $count = \App\Models\User::where('school_id', $school->id)
                                                                            ->where('role', 'teacher')
                                                                            ->where('is_teacher_admin', true)
                                                                            ->count();
                                                                        $teacherAdminCounts[$school->id] = $count;
                                                                    }
                                                                @endphp

                                                                @foreach($schools as $school)
                                                                    <option value="{{ $school->id }}" data-admin-count="{{ $teacherAdminCounts[$school->id] }}"
                                                                            {{ $teacherAdminCounts[$school->id] >= 2 ? 'disabled' : '' }}>
                                                                        {{ $school->name }} ({{ $teacherAdminCounts[$school->id] }}/2 Teacher Admins)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-text">Teachers must be assigned to a school.</div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="expires_at" class="form-label">Expiration Date (Optional)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                            <input type="date" class="form-control" id="expires_at" name="expires_at">
                                                        </div>
                                                        <div class="form-text">Leave empty for a key that never expires.</div>
                                                    </div>
                                                </div>

                                                <div class="d-grid col-md-4 mx-auto mt-3">
                                                    <button type="submit" class="btn btn-success">
                                                        <i class="fas fa-plus-circle me-2"></i>Generate Key
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Bulk Generate Teacher Keys Tab -->
                                <div class="tab-pane fade" id="generate-bulk" role="tabpanel" aria-labelledby="generate-bulk-tab">
                                    <div class="card border-0 shadow-sm">
                                        <div class="card-body p-4">
                                            <p class="text-muted">
                                                Generate multiple teacher registration keys at once for a specific school.
                                            </p>

                                            <form action="{{ route('admin.generate-bulk-keys') }}" method="POST">
                                                @csrf

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="bulk_school_id" class="form-label">School <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-school"></i></span>
                                                            <select class="form-select" id="bulk_school_id" name="school_id" required>
                                                                <option value="">-- Select School --</option>
                                                                @foreach($schools as $school)
                                                                    <option value="{{ $school->id }}">
                                                                        {{ $school->name }} ({{ $teacherAdminCounts[$school->id] }}/2 Teacher Admins)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-text">Select the school for these teacher keys.</div>
                                                    </div>

                                                    <div class="col-md-6 mb-3">
                                                        <label for="key_count" class="form-label">Number of Keys <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                                            <input type="number" class="form-control" id="key_count" name="key_count" required min="1" max="50" value="5">
                                                        </div>
                                                        <div class="form-text">How many teacher keys to generate (max 50).</div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-6 mb-3">
                                                        <label for="bulk_expires_at" class="form-label">Expiration Date (Optional)</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                                            <input type="date" class="form-control" id="bulk_expires_at" name="expires_at">
                                                        </div>
                                                        <div class="form-text">Leave empty for keys that never expire.</div>
                                                    </div>
                                                </div>

                                                <div class="d-grid col-md-4 mx-auto mt-3">
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="fas fa-layer-group me-2"></i>Generate Bulk Keys
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Registration Keys -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="d-flex align-items-center justify-content-center me-3 bg-info bg-opacity-10 rounded-circle" style="width: 40px; height: 40px;">
                                                <i class="fas fa-list text-info"></i>
                                            </div>
                                            <h5 class="mb-0">Active Registration Keys</h5>
                                        </div>
                                        <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="false" aria-controls="filterCollapse">
                                            <i class="fas fa-filter me-1"></i> Filter
                                        </button>
                                    </div>

                                    <div class="collapse {{ isset($filters) && count($filters) > 0 ? 'show' : '' }}" id="filterCollapse">
                                        <form action="{{ route('admin.registration-keys') }}" method="GET" class="row g-3">
                                            <div class="col-md-3">
                                                <label for="filter_key_type" class="form-label small">Key Type</label>
                                                <select class="form-select form-select-sm" id="filter_key_type" name="key_type">
                                                    <option value="">All Types</option>
                                                    <option value="teacher" {{ isset($filters['key_type']) && $filters['key_type'] == 'teacher' ? 'selected' : '' }}>Teacher</option>
                                                    <option value="teacher_admin" {{ isset($filters['key_type']) && $filters['key_type'] == 'teacher_admin' ? 'selected' : '' }}>Teacher Admin</option>
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label for="filter_school_id" class="form-label small">School</label>
                                                <select class="form-select form-select-sm" id="filter_school_id" name="school_id">
                                                    <option value="">All Schools</option>
                                                    @foreach($schools as $school)
                                                        <option value="{{ $school->id }}" {{ isset($filters['school_id']) && $filters['school_id'] == $school->id ? 'selected' : '' }}>
                                                            {{ $school->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="filter_per_page" class="form-label small">Show</label>
                                                <select class="form-select form-select-sm" id="filter_per_page" name="per_page">
                                                    <option value="15" {{ !isset($filters['per_page']) || $filters['per_page'] == '15' ? 'selected' : '' }}>15 per page</option>
                                                    <option value="30" {{ isset($filters['per_page']) && $filters['per_page'] == '30' ? 'selected' : '' }}>30 per page</option>
                                                    <option value="50" {{ isset($filters['per_page']) && $filters['per_page'] == '50' ? 'selected' : '' }}>50 per page</option>
                                                    <option value="all" {{ isset($filters['per_page']) && $filters['per_page'] == 'all' ? 'selected' : '' }}>Show All</option>
                                                </select>
                                            </div>
                                            <div class="col-md-4 d-flex align-items-end">
                                                <div class="btn-group w-100">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        <i class="fas fa-search me-1"></i> Apply Filters
                                                    </button>
                                                    <a href="{{ route('admin.registration-keys') }}" class="btn btn-sm btn-outline-secondary">
                                                        <i class="fas fa-times me-1"></i> Clear
                                                    </a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Key Type</th>
                                                    <th>Registration Key</th>
                                                    <th>School</th>
                                                    <th>Created</th>
                                                    <th>Expires</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                {{-- Keys are now provided by the controller --}}

                                                @forelse($keys as $key)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-{{ $key->key_type == 'teacher_admin' ? 'info' : 'primary' }}">
                                                            <i class="fas fa-{{ $key->key_type == 'teacher_admin' ? 'user-shield' : 'chalkboard-teacher' }} me-1"></i>
                                                            {{ $key->key_type == 'teacher_admin' ? 'Teacher Admin' : 'Teacher' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($key->temporaryKey)
                                                            <div class="d-flex align-items-center">
                                                                <code class="me-2 key-code" id="key-{{ $key->id }}">{{ $key->temporaryKey->plain_key }}</code>
                                                                <button class="btn btn-sm btn-outline-secondary copy-btn"
                                                                        data-key-id="{{ $key->id }}"
                                                                        title="Copy to clipboard">
                                                                    <i class="fas fa-copy"></i>
                                                                </button>
                                                            </div>
                                                        @else
                                                            <span class="text-muted">Not available</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $key->school ? $key->school->name : 'Any School' }}</td>
                                                    <td>{{ $key->created_at->format('M d, Y') }}</td>
                                                    <td>{{ $key->expires_at ? $key->expires_at->format('M d, Y') : 'Never' }}</td>
                                                    <td>
                                                        <span class="badge bg-success">Active</span>
                                                    </td>
                                                </tr>
                                                @empty
                                                <tr>
                                                    <td colspan="6" class="text-center py-3 text-muted">No active registration keys found.</td>
                                                </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="card-footer bg-white py-3">
                                        {{ $keys->links('admin.partials.pagination') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function copyToClipboard() {
        const keyDisplay = document.getElementById('keyDisplay');
        const textArea = document.createElement('textarea');
        textArea.value = keyDisplay.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);

        // Show tooltip or some indication
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 1500);
    }

    function copyAllKeys() {
        // Get all bulk keys
        const bulkKeys = document.querySelectorAll('.bulk-key');
        let allKeys = '';

        bulkKeys.forEach((keyElement, index) => {
            allKeys += keyElement.textContent;
            if (index < bulkKeys.length - 1) {
                allKeys += '\n';
            }
        });

        // Copy to clipboard
        const textArea = document.createElement('textarea');
        textArea.value = allKeys;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);

        // Show success indicator
        const btn = event.currentTarget;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 1500);
    }

    // Handle key type change to update help text for Teacher Admin
    document.addEventListener('DOMContentLoaded', function() {
        // For single key generation form
        const keyTypeSelect = document.getElementById('key_type');
        const schoolSelect = document.getElementById('school_id');
        const schoolFormGroup = schoolSelect.closest('.col-md-6');
        const schoolHelpText = schoolFormGroup.querySelector('.form-text');

        // Store the original school options with admin counts
        const schoolOptionsWithCounts = [];
        Array.from(schoolSelect.options).forEach(option => {
            if (option.value) {
                schoolOptionsWithCounts.push({
                    value: option.value,
                    textWithCounts: option.textContent,
                    adminCount: option.getAttribute('data-admin-count'),
                    disabled: option.disabled
                });
            }
        });

        function updateSchoolDropdown() {
            if (keyTypeSelect.value === 'teacher_admin') {
                schoolHelpText.innerHTML = 'Teachers must be assigned to a school. Maximum 2 Teacher Admins per school.';

                // Restore the admin counts for Teacher Admin key type
                Array.from(schoolSelect.options).forEach((option, index) => {
                    if (option.value) { // Skip the placeholder option
                        const matchingOption = schoolOptionsWithCounts.find(o => o.value === option.value);
                        if (matchingOption) {
                            option.textContent = matchingOption.textWithCounts;
                            option.disabled = matchingOption.adminCount >= 2;
                        }
                    }
                });
            } else {
                schoolHelpText.innerHTML = 'Teachers must be assigned to a school.';

                // Reset school dropdown options to hide admin counts for regular Teacher key type
                Array.from(schoolSelect.options).forEach((option, index) => {
                    if (option.value) { // Skip the placeholder option
                        const schoolName = option.textContent.split(' (')[0]; // Get just the school name
                        option.textContent = schoolName;
                        option.disabled = false; // Enable all options for regular teachers
                    }
                });
            }
        }

        // Initial setup
        updateSchoolDropdown();

        // Listen for changes
        keyTypeSelect.addEventListener('change', updateSchoolDropdown);

        // Also trigger the update when the page loads if Teacher Admin is already selected
        if (keyTypeSelect.value === 'teacher_admin') {
            updateSchoolDropdown();
        }

        // Initialize Bootstrap tabs
        const triggerTabList = document.querySelectorAll('#keyManagementTabs button');
        triggerTabList.forEach(triggerEl => {
            const tabTrigger = new bootstrap.Tab(triggerEl);
            triggerEl.addEventListener('click', event => {
                event.preventDefault();
                tabTrigger.show();
            });
        });
        // Handle copying keys from the table
        const copyButtons = document.querySelectorAll('.copy-btn');
        copyButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const keyId = this.getAttribute('data-key-id');
                const keyElement = document.getElementById('key-' + keyId);

                // Create a temporary textarea to copy from
                const textArea = document.createElement('textarea');
                textArea.value = keyElement.textContent;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                // Show success indicator
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                }, 1500);
            });
        });

        // Handle copying bulk keys
        const copyBulkButtons = document.querySelectorAll('.copy-bulk-btn');
        copyBulkButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const keyIndex = this.getAttribute('data-key-index');
                const keyElement = document.getElementById('bulk-key-' + keyIndex);

                // Create a temporary textarea to copy from
                const textArea = document.createElement('textarea');
                textArea.value = keyElement.textContent;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);

                // Show success indicator
                const originalHTML = this.innerHTML;
                this.innerHTML = '<i class="fas fa-check"></i>';
                setTimeout(() => {
                    this.innerHTML = originalHTML;
                }, 1500);
            });
        });
    });
</script>
@endpush