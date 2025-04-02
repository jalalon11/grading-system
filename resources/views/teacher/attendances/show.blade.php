@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-check text-primary me-2"></i> 
                            Attendance Details
                        </h5>
                        <div>
                            <a href="{{ route('teacher.attendances.edit', ['attendance' => $section->id, 'date' => $date]) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-1"></i> Edit Attendance
                            </a>
                            <a href="{{ route('teacher.attendances.index') }}" class="btn btn-outline-secondary ms-2">
                                <i class="fas fa-arrow-left me-1"></i> Back to Records
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-info-circle fa-2x"></i>
                            </div>
                            <div>
                                <h5 class="alert-heading mb-1">{{ $section->name }} - {{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</h5>
                                <p class="mb-0">Viewing attendance records for {{ count($students) }} students in this section.</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-lg-7">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-chart-pie me-2"></i> Attendance Summary</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="attendance-chart mb-3">
                                                <canvas id="attendanceChart" width="100%" height="220"></canvas>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="attendance-stats">
                                                <div class="attendance-stat-item mb-3 p-3 rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Present</span>
                                                        <span class="badge bg-success rounded-pill">{{ $presentCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-success" style="width: {{ count($students) > 0 ? ($presentCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="attendance-stat-item mb-3 p-3 rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Late</span>
                                                        <span class="badge bg-warning text-dark rounded-pill">{{ $lateCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-warning" style="width: {{ count($students) > 0 ? ($lateCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="attendance-stat-item p-3 rounded bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Absent</span>
                                                        <span class="badge bg-danger rounded-pill">{{ $absentCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-danger" style="width: {{ count($students) > 0 ? ($absentCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>
                                                
                                                <div class="attendance-stat-item p-3 rounded bg-light mt-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Excused</span>
                                                        <span class="badge bg-secondary rounded-pill">{{ $excusedCount }}</span>
                                                    </div>
                                                    <div class="progress" style="height: 6px;">
                                                        <div class="progress-bar bg-secondary" style="width: {{ count($students) > 0 ? ($excusedCount / count($students) * 100) : 0 }}%"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-5">
                            <div class="card h-100">
                                <div class="card-header bg-light">
                                    <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i> Attendance Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label text-muted small">SECTION</div>
                                            <div class="detail-value fw-medium">{{ $section->name }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label text-muted small">DATE</div>
                                            <div class="detail-value fw-medium">{{ \Carbon\Carbon::parse($date)->format('F d, Y') }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label text-muted small">TOTAL STUDENTS</div>
                                            <div class="detail-value fw-medium">{{ count($students) }}</div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="detail-label text-muted small">RECORDED BY</div>
                                            <div class="detail-value fw-medium">{{ Auth::user()->name }}</div>
                                        </div>
                                        <div class="col-12">
                                            <div class="detail-label text-muted small">ATTENDANCE RATE</div>
                                            <div class="d-flex align-items-center">
                                                <div class="attendance-rate-display me-3">
                                                    <h2 class="mb-0 text-primary">
                                                        @if(count($students) > 0)
                                                            {{ round((($presentCount + $lateCount) / count($students)) * 100, 1) }}%
                                                        @else
                                                            N/A
                                                        @endif
                                                    </h2>
                                                </div>
                                                <div class="attendance-rate-progress flex-grow-1">
                                                    <div class="progress" style="height: 10px;">
                                                        @if(count($students) > 0)
                                                            <div class="progress-bar bg-success" role="progressbar" 
                                                                 style="width: {{ ($presentCount / count($students) * 100) }}%"></div>
                                                            <div class="progress-bar bg-warning" role="progressbar" 
                                                                 style="width: {{ ($lateCount / count($students) * 100) }}%"></div>
                                                        @else
                                                            <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                                        @endif
                                                    </div>
                                                    <div class="mt-1 d-flex justify-content-between small text-muted">
                                                        <span>0%</span>
                                                        <span>50%</span>
                                                        <span>100%</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-users me-2"></i> Student Attendance Details
                                </h6>
                                <div class="text-muted small">
                                    <span class="badge-dot bg-success me-1"></span> Present: {{ $presentCount }}
                                    <span class="badge-dot bg-warning mx-1"></span> Late: {{ $lateCount }}
                                    <span class="badge-dot bg-danger mx-1"></span> Absent: {{ $absentCount }}
                                    <span class="badge-dot bg-secondary mx-1"></span> Excused: {{ $excusedCount }}
                                </div>
                            </div>
                        </div>
                        
                        @if(count($students) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 50px">#</th>
                                            <th>Student Name</th>
                                            <th>Student ID</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($students as $index => $student)
                                            <tr>
                                                <td class="text-muted">{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-container me-2">
                                                            <span class="avatar rounded-circle d-flex align-items-center justify-content-center" 
                                                                  style="width: 35px; height: 35px; background-color: #e0f2ff; color: #0d6efd;">
                                                                {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <div class="fw-medium">{{ $student->first_name }} {{ $student->last_name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-secondary bg-opacity-10 text-secondary">
                                                        {{ $student->student_id }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    @if($attendanceData[$student->id] == 'present')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i> Present
                                                        </span>
                                                    @elseif($attendanceData[$student->id] == 'late')
                                                        <span class="badge bg-warning text-dark">
                                                            <i class="fas fa-clock me-1"></i> Late
                                                        </span>
                                                    @elseif($attendanceData[$student->id] == 'excused')
                                                        <span class="badge bg-secondary">
                                                            <i class="fas fa-file-alt me-1"></i> Excused
                                                        </span>
                                                    @else
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i> Absent
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="card-body py-5">
                                <div class="text-center text-muted">
                                    <i class="fas fa-users fa-3x mb-3"></i>
                                    <p>No students found in this section.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .badge-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
    }
    
    .detail-label {
        margin-bottom: 2px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .detail-value {
        font-size: 1rem;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Create attendance pie chart
        const ctx = document.getElementById('attendanceChart').getContext('2d');
        const attendanceChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Present', 'Late', 'Absent', 'Excused'],
                datasets: [{
                    data: [{{ $presentCount }}, {{ $lateCount }}, {{ $absentCount }}, {{ $excusedCount }}],
                    backgroundColor: [
                        '#28a745',  // Present - green
                        '#ffc107',  // Late - yellow
                        '#dc3545',  // Absent - red
                        '#6c757d'   // Excused - gray
                    ],
                    borderWidth: 0,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 15
                        }
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                let percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection 