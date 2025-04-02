@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-calendar-check text-primary me-2"></i> Attendance Records
                        </h5>
                        <a href="{{ route('teacher.attendances.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus-circle me-1"></i> Record New Attendance
                        </a>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Filter Form -->
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title mb-3">
                                <i class="fas fa-filter text-secondary me-1"></i> Filter Records
                            </h6>
                            <form method="GET" action="{{ route('teacher.attendances.index') }}" class="row g-3">
                                <div class="col-md-4">
                                    <label for="section_id" class="form-label fw-medium">Section</label>
                                    <select class="form-select" id="section_id" name="section_id">
                                        <option value="">All Sections</option>
                                        @foreach($sections as $section)
                                            <option value="{{ $section->id }}" {{ request('section_id') == $section->id ? 'selected' : '' }}>
                                                {{ $section->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="date" class="form-label fw-medium">Date</label>
                                    <input type="date" class="form-control" id="date" name="date" value="{{ request('date') }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-secondary me-2">
                                        <i class="fas fa-search me-1"></i> Apply Filters
                                    </button>
                                    <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-redo me-1"></i> Reset
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Attendance Records Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Section</th>
                                    <th class="text-center">Present</th>
                                    <th class="text-center">Late</th>
                                    <th class="text-center">Absent</th>
                                    <th class="text-center">Excused</th>
                                    <th class="text-center">Attendance Rate</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($attendances as $date => $dateGroup)
                                    @foreach ($dateGroup as $sectionId => $attendance)
                                        @php
                                            $totalStudents = $attendance['present_count'] + $attendance['late_count'] + $attendance['absent_count'] + $attendance['excused_count'];
                                            $attendanceRate = $totalStudents > 0 ? 
                                                round((($attendance['present_count'] + $attendance['late_count']) / $totalStudents) * 100, 1) : 0;
                                        @endphp
                                        <tr>
                                            <td class="fw-medium">{{ \Carbon\Carbon::parse($date)->format('M d, Y') }}</td>
                                            <td>{{ $attendance['section_name'] }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-success rounded-pill">{{ $attendance['present_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning text-dark rounded-pill">{{ $attendance['late_count'] ?? 0 }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger rounded-pill">{{ $attendance['absent_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-secondary rounded-pill">{{ $attendance['excused_count'] ?? 0 }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="progress" style="height: 8px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" 
                                                        style="width: {{ $attendanceRate }}%;" 
                                                        aria-valuenow="{{ $attendanceRate }}" 
                                                        aria-valuemin="0" 
                                                        aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <small class="d-block mt-1">{{ $attendanceRate }}%</small>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('teacher.attendances.show', ['attendance' => $sectionId, 'date' => $date]) }}" 
                                                       class="btn btn-sm btn-outline-info" 
                                                       data-bs-toggle="tooltip" 
                                                       title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('teacher.attendances.edit', ['attendance' => $sectionId, 'date' => $date]) }}" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       data-bs-toggle="tooltip" 
                                                       title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4 text-muted">
                                            <i class="fas fa-calendar-times fa-2x mb-3"></i>
                                            <p>No attendance records found</p>
                                            <a href="{{ route('teacher.attendances.create') }}" class="btn btn-sm btn-primary">
                                                Record New Attendance
                                            </a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });
    });
</script>
@endpush
@endsection 