@extends('layouts.app')

@section('content')
<div class="container">
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
                        {{ isset($teacherAdmin) ? 'Edit Teacher Admin' : 'Assign Teacher Admin' }}
                    </h5>
                </div>
                <div class="col text-end">
                    <a href="{{ route('admin.teacher-admins.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Back to List
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ isset($teacherAdmin) ? route('admin.teacher-admins.update', $teacherAdmin->id) : route('admin.teacher-admins.store') }}" method="POST">
                @csrf
                @if(isset($teacherAdmin))
                    @method('PUT')
                @endif

                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Teacher Admins have additional privileges to manage school-wide settings and assist other teachers.
                    Each school can have a maximum of 2 Teacher Admins.
                </div>

                <div class="mb-3">
                    <label for="school_id" class="form-label">Select School</label>
                    <select name="school_id" id="school_id" class="form-select @error('school_id') is-invalid @enderror" required>
                        <option value="">Choose a school...</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}" 
                                {{ old('school_id', isset($teacherAdmin) ? $teacherAdmin->school_id : '') == $school->id ? 'selected' : '' }}
                                data-admin-count="{{ $school->users()->where('is_teacher_admin', true)->count() }}">
                                {{ $school->name }}
                                ({{ $school->users()->where('is_teacher_admin', true)->count() }}/2 admins)
                            </option>
                        @endforeach
                    </select>
                    @error('school_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div id="adminLimitWarning" class="text-danger mt-2 d-none">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        This school already has the maximum number of Teacher Admins (2).
                    </div>
                </div>

                <div class="mb-3">
                    <label for="teacher_id" class="form-label">Select Teacher</label>
                    <select name="teacher_id" id="teacher_id" class="form-select @error('teacher_id') is-invalid @enderror" required disabled>
                        <option value="">First select a school...</option>
                    </select>
                    @error('teacher_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.teacher-admins.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save me-1"></i>
                        {{ isset($teacherAdmin) ? 'Update Teacher Admin' : 'Assign as Teacher Admin' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const schoolSelect = document.getElementById('school_id');
    const teacherSelect = document.getElementById('teacher_id');
    const adminLimitWarning = document.getElementById('adminLimitWarning');
    const submitBtn = document.getElementById('submitBtn');

    // Trigger change event on load to update teacher list
    if (schoolSelect.value) {
        schoolSelect.dispatchEvent(new Event('change'));
    }

    schoolSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const adminCount = parseInt(selectedOption.getAttribute('data-admin-count'));
        const schoolId = this.value;

        // Show/hide admin limit warning and manage submit button
        if (adminCount >= 2) {
            adminLimitWarning.classList.remove('d-none');
            submitBtn.disabled = true;
            teacherSelect.disabled = true;
        } else {
            adminLimitWarning.classList.add('d-none');
            submitBtn.disabled = false;
            
            // Update teachers dropdown
            if (schoolId) {
                teacherSelect.disabled = true; // Disable while loading
                fetch(`/admin/api/schools/${schoolId}/teachers`)
                    .then(response => response.json())
                    .then(data => {
                        teacherSelect.innerHTML = '<option value="">Select a teacher...</option>';
                        data.forEach(teacher => {
                            teacherSelect.innerHTML += `<option value="${teacher.id}">${teacher.name}</option>`;
                        });
                        teacherSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error loading teachers:', error);
                        teacherSelect.innerHTML = '<option value="">Error loading teachers</option>';
                        teacherSelect.disabled = true;
                    });
            } else {
                teacherSelect.innerHTML = '<option value="">First select a school...</option>';
                teacherSelect.disabled = true;
            }
        }
    });
});
</script>
@endpush
@endsection 