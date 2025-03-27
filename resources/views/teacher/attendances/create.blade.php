@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Record Attendance</span>
                    <a href="{{ route('teacher.attendances.index') }}" class="btn btn-secondary">Back to List</a>
                </div>

                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('teacher.attendances.store') }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="section_id" class="form-label">Section <span class="text-danger">*</span></label>
                                <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id" required>
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                        <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                            {{ $section->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror" id="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required>
                                @error('date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        
                        <div id="students-container">
                            <div class="text-center py-4">
                                <p>Please select a section to load students</p>
                            </div>
                        </div>
                        
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Save Attendance</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionSelect = document.getElementById('section_id');
        const studentsContainer = document.getElementById('students-container');
        
        sectionSelect.addEventListener('change', function() {
            const sectionId = this.value;
            if (!sectionId) {
                studentsContainer.innerHTML = '<div class="text-center py-4"><p>Please select a section to load students</p></div>';
                return;
            }
            
            studentsContainer.innerHTML = '<div class="text-center py-4"><p>Loading students...</p></div>';
            
            fetch(`/teacher/sections/${sectionId}/students`)
                .then(response => response.json())
                .then(data => {
                    if (data.students.length === 0) {
                        studentsContainer.innerHTML = '<div class="alert alert-info">No students found in this section. <a href="/teacher/students/create">Add students</a></div>';
                        return;
                    }
                    
                    let html = `
                        <h4 class="mb-3 mt-4">Student Attendance</h4>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Student Name</th>
                                        <th>Roll Number</th>
                                        <th width="150">Present</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    data.students.forEach(student => {
                        html += `
                            <tr>
                                <td>${student.first_name} ${student.last_name}</td>
                                <td>${student.student_id}</td>
                                <td class="text-center">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="attendance[${student.id}]" id="present_${student.id}" value="present" checked>
                                        <label class="form-check-label" for="present_${student.id}">Present</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="attendance[${student.id}]" id="absent_${student.id}" value="absent">
                                        <label class="form-check-label" for="absent_${student.id}">Absent</label>
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    html += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    
                    studentsContainer.innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    studentsContainer.innerHTML = '<div class="alert alert-danger">Error loading students. Please try again.</div>';
                });
        });
        
        // Trigger change if section is already selected (e.g., when coming back from validation error)
        if (sectionSelect.value) {
            sectionSelect.dispatchEvent(new Event('change'));
        }
    });
</script>
@endpush
@endsection 