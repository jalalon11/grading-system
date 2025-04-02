@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-table me-2"></i>Generate Class Record
                    </h5>
                    <a href="{{ route('teacher.reports.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Back to Reports
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('teacher.reports.generate-class-record') }}" method="POST" target="_blank">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="section_id" class="form-label">Section</label>
                                <select name="section_id" id="section_id" class="form-select @error('section_id') is-invalid @enderror" required>
                                    <option value="">Select Section</option>
                                    @foreach($sections as $section)
                                    <option value="{{ $section->id }}">{{ $section->name }}</option>
                                    @endforeach
                                </select>
                                @error('section_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="subject_id" class="form-label">Subject</label>
                                <select name="subject_id" id="subject_id" class="form-select @error('subject_id') is-invalid @enderror" required disabled>
                                    <option value="">Select a section first</option>
                                </select>
                                @error('subject_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="quarter" class="form-label">Quarter</label>
                                <select name="quarter" id="quarter" class="form-select @error('quarter') is-invalid @enderror" required>
                                    <option value="">Select Quarter</option>
                                    <option value="Q1">First Quarter</option>
                                    <option value="Q2">Second Quarter</option>
                                    <option value="Q3">Third Quarter</option>
                                    <option value="Q4">Fourth Quarter</option>
                                </select>
                                @error('quarter')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mt-3 d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-file-alt me-2"></i> Generate Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sectionSelect = document.getElementById('section_id');
        const subjectSelect = document.getElementById('subject_id');
        
        // Function to load subjects based on selected section
        function loadSubjects(sectionId) {
            if (!sectionId) {
                subjectSelect.innerHTML = '<option value="">Select a section first</option>';
                subjectSelect.disabled = true;
                return;
            }
            
            // Show loading
            subjectSelect.innerHTML = '<option value="">Loading subjects...</option>';
            subjectSelect.disabled = true;
            
            // Make API call to get subjects for this section
            fetch(`{{ route('teacher.reports.section-subjects') }}?section_id=${sectionId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Clear and populate subjects dropdown
                subjectSelect.innerHTML = '';
                
                if (data.length === 0) {
                    subjectSelect.innerHTML = '<option value="">No subjects assigned</option>';
                    subjectSelect.disabled = true;
                } else {
                    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        subjectSelect.appendChild(option);
                    });
                    subjectSelect.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectSelect.innerHTML = '<option value="">Error loading subjects</option>';
            });
        }
        
        // Event listener for section change
        sectionSelect.addEventListener('change', function() {
            loadSubjects(this.value);
        });
    });
</script>
@endpush 