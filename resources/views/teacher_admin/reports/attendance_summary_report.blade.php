@extends('layouts.report')

@php
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
@endphp

@section('title', 'Attendance Summary Report')

@section('styles')
<style>
    .attendance-table th, .attendance-table td {
        vertical-align: middle;
    }
    .attendance-badge {
        display: inline-block;
        padding: 0.25em 0.6em;
        font-size: 0.75em;
        font-weight: 700;
        border-radius: 0.25rem;
    }
    .badge-present {
        background-color: #28a745;
        color: white;
    }
    .badge-absent {
        background-color: #dc3545;
        color: white;
    }
    .badge-late {
        background-color: #ffc107;
        color: black;
    }
    .badge-excused {
        background-color: #6c757d;
        color: white;
    }
    .badge-half-day {
        background-color: #17a2b8;
        color: white;
    }
    /* More compact tables */
    .table-responsive {
        margin-bottom: 10px;
    }
    table {
        margin-bottom: 10px;
    }
    th, td {
        padding: 3px 5px;
        font-size: 11px;
    }
    .signature-section {
        margin-top: 20px;
    }
    @media print {
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .bg-success {
            background-color: #28a745 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-danger {
            background-color: #dc3545 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-warning {
            background-color: #ffc107 !important;
            color: black !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-info {
            background-color: #17a2b8 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-secondary {
            background-color: #6c757d !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-present {
            background-color: #28a745 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-absent {
            background-color: #dc3545 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-late {
            background-color: #ffc107 !important;
            color: black !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-excused {
            background-color: #6c757d !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .badge-half-day {
            background-color: #17a2b8 !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="header">
        <div class="header-row">
            <div class="logo-left">
                @if(isset($sections->first()->school) && $sections->first()->school->logo_path)
                    <img src="{{ $sections->first()->school->logo_url }}" alt="School Logo" style="max-width: 80px; max-height: 80px;">
                @else
                    <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-school fa-3x"></i>
                    </div>
                @endif
            </div>
            <div class="title-center">
                <h1>ATTENDANCE SUMMARY REPORT</h1>
                <p>School-wide Attendance Analysis</p>
            </div>
            <div class="logo-right">
                <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo" style="max-width: 80px; max-height: 80px;">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="info-table">
            <tr>
                <td class="info-label table-header">SCHOOL</td>
                <td class="info-value">{{ isset($sections->first()->school) ? $sections->first()->school->name : 'School Name' }}</td>
                <td class="info-label table-header">DATE RANGE</td>
                <td class="info-value">{{ $summary['date_range']['start'] }} to {{ $summary['date_range']['end'] }}</td>
            </tr>
            <tr>
                <td class="info-label table-header">SCHOOL DAYS</td>
                <td class="info-value">{{ $summary['total_school_days'] }}</td>
                <td class="info-label table-header">TOTAL STUDENTS</td>
                <td class="info-value">
                    @php
                        // Get total active students count for the school
                        $totalActiveStudents = App\Models\Student::whereHas('section', function($query) {
                            $query->where('school_id', Auth::user()->school_id)
                                  ->where('is_active', true);
                        })->where('is_active', true)->count();
                        echo $totalActiveStudents;
                    @endphp
                </td>
            </tr>
            <tr>
                <td class="info-label table-header">GENERATED BY</td>
                <td class="info-value">{{ Auth::user()->name }}</td>
                <td class="info-label table-header">GENERATED ON</td>
                <td class="info-value">{{ now()->setTimezone('Asia/Manila')->format('M d, Y h:i A') }}</td>
            </tr>
        </table>
    </div>

    <!-- Overall Attendance Summary -->
    <div class="table-responsive">
        <table>
            <tr>
                <td colspan="7" class="table-header">OVERALL ATTENDANCE SUMMARY</td>
            </tr>
            <tr>
                <th class="column-header">Present</th>
                <th class="column-header">Absent</th>
                <th class="column-header">Late</th>
                <th class="column-header">Half Day</th>
                <th class="column-header">Excused</th>
                <th class="column-header">Total Records</th>
                <th class="column-header">Attendance Rate</th>
            </tr>
            <tr>
                <td class="text-center">{{ $summary['overall_stats']['present'] }}</td>
                <td class="text-center">{{ $summary['overall_stats']['absent'] }}</td>
                <td class="text-center">{{ $summary['overall_stats']['late'] }}</td>
                <td class="text-center">{{ $summary['overall_stats']['half_day'] }}</td>
                <td class="text-center">{{ $summary['overall_stats']['excused'] }}</td>
                <td class="text-center">{{ $summary['overall_stats']['total_attendance_records'] }}</td>
                <td class="text-center fw-bold">{{ $summary['overall_stats']['attendance_rate'] }}%</td>
            </tr>
        </table>
    </div>



    <!-- Attendance by Grade Level -->
    <div class="table-responsive">
        <table>
            <tr>
                <td colspan="8" class="table-header">ATTENDANCE BY GRADE LEVEL</td>
            </tr>
            <tr>
                <th class="column-header">Grade Level</th>
                <th class="column-header">Total Students</th>
                <th class="column-header">Present</th>
                <th class="column-header">Absent</th>
                <th class="column-header">Late</th>
                <th class="column-header">Half Day</th>
                <th class="column-header">Excused</th>
                <th class="column-header">Attendance Rate</th>
            </tr>
            @foreach($summary['grade_level_stats'] as $gradeLevel => $stats)
            <tr>
                <td class="text-center">{{ $gradeLevel }}</td>
                <td class="text-center">
                    @php
                        $studentCount = 0;
                        // Get active students count for this grade level
                        $activeStudents = App\Models\Student::whereHas('section', function($query) use ($gradeLevel) {
                            $query->where('grade_level', $gradeLevel)
                                  ->where('school_id', Auth::user()->school_id)
                                  ->where('is_active', true);
                        })->where('is_active', true)->count();

                        echo $activeStudents ?: '-';
                    @endphp
                </td>
                <td class="text-center">{{ $stats['present'] }}</td>
                <td class="text-center">{{ $stats['absent'] }}</td>
                <td class="text-center">{{ $stats['late'] }}</td>
                <td class="text-center">{{ $stats['half_day'] ?? 0 }}</td>
                <td class="text-center">{{ $stats['excused'] }}</td>
                <td class="text-center fw-bold">{{ $stats['attendance_rate'] }}%</td>
            </tr>
            @endforeach
        </table>
    </div>



    <!-- Attendance by Section -->
    <div class="table-responsive">
        <table>
            <tr>
                <td colspan="9" class="table-header">ATTENDANCE BY SECTION</td>
            </tr>
            <tr>
                <th class="column-header">Section</th>
                <th class="column-header">Grade Level</th>
                <th class="column-header">Total Students</th>
                <th class="column-header">Present</th>
                <th class="column-header">Absent</th>
                <th class="column-header">Late</th>
                <th class="column-header">Half Day</th>
                <th class="column-header">Excused</th>
                <th class="column-header">Attendance Rate</th>
            </tr>
            @foreach($summary['section_stats'] as $sectionId => $stats)
            <tr>
                <td>{{ $stats['name'] }}</td>
                <td class="text-center">{{ $stats['grade_level'] }}</td>
                <td class="text-center">
                    @php
                        // Get active students count for this section
                        $activeStudents = App\Models\Student::where('section_id', $sectionId)
                                                           ->where('is_active', true)
                                                           ->count();
                        echo $activeStudents ?: '-';
                    @endphp
                </td>
                <td class="text-center">{{ $stats['present'] }}</td>
                <td class="text-center">{{ $stats['absent'] }}</td>
                <td class="text-center">{{ $stats['late'] }}</td>
                <td class="text-center">{{ $stats['half_day'] ?? 0 }}</td>
                <td class="text-center">{{ $stats['excused'] }}</td>
                <td class="text-center fw-bold">{{ $stats['attendance_rate'] }}%</td>
            </tr>
            @endforeach
        </table>
    </div>

    <!-- Students with Attendance Concerns -->
    @if(count($summary['students_with_concerns']) > 0)
    <div class="table-responsive">
        <table>
            <tr>
                <td colspan="7" class="table-header">STUDENTS WITH ATTENDANCE CONCERNS</td>
            </tr>
            <tr>
                <th class="column-header">Student Name</th>
                <th class="column-header">Section</th>
                <th class="column-header">Absent</th>
                <th class="column-header">Late</th>
                <th class="column-header">Half Day</th>
                <th class="column-header">Absent Rate</th>
                <th class="column-header">Late Rate</th>
            </tr>
            @foreach($summary['students_with_concerns'] as $student)
            <tr>
                <td>{{ $student['name'] }}</td>
                <td>{{ $student['section'] }} ({{ $student['grade_level'] }})</td>
                <td class="text-center">{{ $student['absent_count'] }} / {{ $student['total_records'] }}</td>
                <td class="text-center">{{ $student['late_count'] }} / {{ $student['total_records'] }}</td>
                <td class="text-center">{{ $student['half_day_count'] ?? 0 }} / {{ $student['total_records'] }}</td>
                <td class="text-center fw-bold">{{ $student['absent_rate'] }}%</td>
                <td class="text-center fw-bold">{{ $student['late_rate'] }}%</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Days with Low Attendance -->
    @if(count($summary['low_attendance_days']) > 0)
    <div class="table-responsive">
        <table>
            <tr>
                <td colspan="7" class="table-header">DAYS WITH LOW ATTENDANCE</td>
            </tr>
            <tr>
                <th class="column-header">Date</th>
                <th class="column-header">Present</th>
                <th class="column-header">Absent</th>
                <th class="column-header">Late</th>
                <th class="column-header">Half Day</th>
                <th class="column-header">Total</th>
                <th class="column-header">Attendance Rate</th>
            </tr>
            @foreach($summary['low_attendance_days'] as $day)
            <tr>
                <td>{{ $day['date'] }}</td>
                <td class="text-center">{{ $day['present'] }}</td>
                <td class="text-center">{{ $day['absent'] ?? '-' }}</td>
                <td class="text-center">{{ $day['late'] ?? '-' }}</td>
                <td class="text-center">{{ $day['half_day'] ?? 0 }}</td>
                <td class="text-center">{{ $day['total'] }}</td>
                <td class="text-center fw-bold">{{ $day['attendance_rate'] }}%</td>
            </tr>
            @endforeach
        </table>
    </div>
    @endif

    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-item">
            <p style="margin-bottom: 15px;">Prepared by:</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px; margin-bottom: 0;"><strong>{{ Auth::user()->name }}</strong></p>
            <p style="margin-top: 0;">Teacher Administrator</p>
        </div>

        <div class="signature-item">
            <p style="margin-bottom: 15px;">Approved by:</p>
            <div class="signature-line"></div>
            <p style="margin-top: 5px; margin-bottom: 0;"><strong>{{ isset($sections->first()->school) && $sections->first()->school->principal ? $sections->first()->school->principal : 'School Principal' }}</strong></p>
            <p style="margin-top: 0;">School Principal</p>
        </div>
    </div>
</div>

@push('scripts')
<!-- No scripts needed -->
@endpush
@endsection
