@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                    <h4 class="mb-0 fw-bold text-success">
                        <i class="fas fa-clipboard-check me-2"></i>Attendance Summary Report
                    </h4>
                    <a href="{{ route('teacher-admin.reports.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Reports
                    </a>
                </div>
                <div class="card-body">
                    <p class="text-muted mb-4">Generate a comprehensive attendance summary report for your school. Select the parameters below to customize your report.</p>

                    <form action="{{ route('teacher-admin.reports.generate-attendance-summary') }}" method="POST" id="attendanceReportForm">
                        @csrf
                        <div class="row g-3">
                            <!-- Date Range Selection -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0"><i class="fas fa-calendar-alt text-success me-2"></i>Date Range</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date', now()->subDays(30)->format('Y-m-d')) }}" required>
                                                    <label for="start_date">Start Date</label>
                                                    @error('start_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}" required>
                                                    <label for="end_date">End Date</label>
                                                    @error('end_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="fas fa-info-circle me-1"></i> Select a date range to analyze attendance patterns.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Section/Grade Level Selection -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-header bg-white py-3">
                                        <h5 class="mb-0"><i class="fas fa-filter text-success me-2"></i>Filter Options (Optional)</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <select class="form-select @error('grade_level') is-invalid @enderror" id="grade_level" name="grade_level">
                                                        <option value="">All Grade Levels</option>
                                                        @foreach($gradeLevels as $gradeLevel)
                                                            <option value="{{ $gradeLevel }}" {{ old('grade_level') == $gradeLevel ? 'selected' : '' }}>
                                                                {{ $gradeLevel }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="grade_level">Grade Level</label>
                                                    @error('grade_level')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <select class="form-select @error('section_id') is-invalid @enderror" id="section_id" name="section_id">
                                                        <option value="">All Sections</option>
                                                        @foreach($sectionsByGradeLevel as $gradeLevel => $gradeSections)
                                                            <optgroup label="Grade {{ $gradeLevel }}">
                                                                @foreach($gradeSections as $section)
                                                                    <option value="{{ $section->id }}" {{ old('section_id') == $section->id ? 'selected' : '' }}>
                                                                        {{ $section->name }}
                                                                    </option>
                                                                @endforeach
                                                            </optgroup>
                                                        @endforeach
                                                    </select>
                                                    <label for="section_id">Section</label>
                                                    @error('section_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-text mt-2">
                                            <i class="fas fa-info-circle me-1"></i> Leave blank to include all grade levels and sections.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-grid gap-2 col-md-4 mx-auto mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-chart-bar me-2"></i>Generate Attendance Report
                            </button>
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
        const gradeSelect = document.getElementById('grade_level');
        const sectionSelect = document.getElementById('section_id');
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        // Filter sections based on selected grade level
        gradeSelect.addEventListener('change', function() {
            const selectedGrade = this.value;
            
            // Reset section select
            sectionSelect.innerHTML = '<option value="">All Sections</option>';
            
            if (!selectedGrade) {
                // If no grade is selected, show all sections grouped by grade
                @foreach($sectionsByGradeLevel as $gradeLevel => $gradeSections)
                    const optgroup{{ $gradeLevel }} = document.createElement('optgroup');
                    optgroup{{ $gradeLevel }}.label = 'Grade {{ $gradeLevel }}';
                    
                    @foreach($gradeSections as $section)
                        const option{{ $section->id }} = document.createElement('option');
                        option{{ $section->id }}.value = '{{ $section->id }}';
                        option{{ $section->id }}.textContent = '{{ $section->name }}';
                        optgroup{{ $gradeLevel }}.appendChild(option{{ $section->id }});
                    @endforeach
                    
                    sectionSelect.appendChild(optgroup{{ $gradeLevel }});
                @endforeach
            } else {
                // If a grade is selected, only show sections for that grade
                @foreach($sectionsByGradeLevel as $gradeLevel => $gradeSections)
                    if (selectedGrade === '{{ $gradeLevel }}') {
                        const optgroup = document.createElement('optgroup');
                        optgroup.label = 'Grade {{ $gradeLevel }}';
                        
                        @foreach($gradeSections as $section)
                            const option = document.createElement('option');
                            option.value = '{{ $section->id }}';
                            option.textContent = '{{ $section->name }}';
                            optgroup.appendChild(option);
                        @endforeach
                        
                        sectionSelect.appendChild(optgroup);
                    }
                @endforeach
            }
        });

        // Validate date range
        endDateInput.addEventListener('change', function() {
            const startDate = new Date(startDateInput.value);
            const endDate = new Date(this.value);
            
            if (endDate < startDate) {
                alert('End date cannot be earlier than start date');
                this.value = startDateInput.value;
            }
        });

        startDateInput.addEventListener('change', function() {
            const startDate = new Date(this.value);
            const endDate = new Date(endDateInput.value);
            
            if (startDate > endDate) {
                endDateInput.value = this.value;
            }
        });
    });
</script>
@endpush
@endsection
