@extends('layouts.app')

@section('content')
<div class="container grade-slip-container">
    <div class="d-print-none mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Grade Slip Preview</h2>
            <div>
                <button class="btn btn-primary me-2" onclick="window.print()">
                    <i class="fas fa-print me-2"></i> Print Grade Slip
                </button>
                <a href="{{ route('teacher.reports.generate-grade-slips', ['section_id' => $section->id, 'quarter' => $quarter, 'transmutation_table' => $transmutationTable ?? 1]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Grade Slips
                </a>
            </div>
        </div>
    </div>

    <div class="grade-slip">
        <div class="header text-center mb-4">
            <h4 class="mb-0">{{ $section->school->name ?? 'School Name' }}</h4>
            <p class="mb-0">{{ $section->school->address ?? 'School Address' }}</p>
            <h5 class="mt-3 mb-0">STUDENT GRADE SLIP</h5>
            <p class="mb-0">{{ $quarterName }} | School Year {{ $schoolYear }}</p>
            <p class="small text-muted mb-0">Using {{ $transmutationTableNames[$transmutationTable] }}</p>
        </div>

        <div class="student-info mb-4">
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="30%">Student Name:</th>
                            <td><strong>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</strong></td>
                        </tr>
                        <tr>
                            <th>Student ID:</th>
                            <td>{{ $student->student_id }}</td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td>{{ $student->gender }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr>
                            <th width="30%">Grade Level:</th>
                            <td>{{ $section->grade_level }}</td>
                        </tr>
                        <tr>
                            <th>Section:</th>
                            <td>{{ $section->name }}</td>
                        </tr>
                        <tr>
                            <th>Adviser:</th>
                            <td>{{ $section->adviser->name ?? 'Not assigned' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="grades-table mb-4">
            <h6 class="mb-3">Subject Grades</h6>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">#</th>
                            <th width="35%">Subject</th>
                            <th width="12%">Initial Grade</th>
                            <th width="12%">Transmuted</th>
                            <th width="12%">Remarks</th>
                            <th width="24%">Subject Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp
                        @foreach($subjects as $subject)
                            @php
                                $hasGrade = isset($studentGrades[$subject->id]);
                                $isApproved = isset($gradeApprovals[$subject->id]) && $gradeApprovals[$subject->id]->is_approved;
                                $initialGrade = $hasGrade ? $studentGrades[$subject->id]->quarterly_grade : null;
                                $transmutedGrade = $hasGrade && isset($studentGrades[$subject->id]->transmuted_grade) ? $studentGrades[$subject->id]->transmuted_grade : null;
                                $remarks = $hasGrade ? $studentGrades[$subject->id]->remarks : 'No Grade';

                                // Get the subject teacher
                                $subjectTeacher = $subject->teachers->first();
                                $teacherName = $subjectTeacher ? $subjectTeacher->name : 'Not assigned';
                            @endphp
                            <tr>
                                <td>{{ $index++ }}</td>
                                <td>
                                    {{ $subject->name }}
                                    @if($subject->is_mapeh)
                                        <div class="mt-2">
                                            @php
                                                $components = $mapehComponents->filter(function($comp) use ($subject) {
                                                    return $comp->parent_subject_id == $subject->id;
                                                });
                                            @endphp
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Component</th>
                                                        <th width="20%">Initial</th>
                                                        <th width="20%">Transmuted</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($components as $component)
                                                        @php
                                                            $componentGrade = null;
                                                            $componentTransmutedGrade = null;
                                                            if ($hasGrade && isset($studentGrades[$subject->id]->component_grades[$component->id])) {
                                                                $componentGrade = $studentGrades[$subject->id]->component_grades[$component->id]->quarterly_grade;
                                                                $componentTransmutedGrade = isset($studentGrades[$subject->id]->component_grades[$component->id]->transmuted_grade) ?
                                                                    $studentGrades[$subject->id]->component_grades[$component->id]->transmuted_grade : null;
                                                            }
                                                        @endphp
                                                        <tr>
                                                            <td>{{ $component->name }}</td>
                                                            <td class="text-center">
                                                                @if($componentGrade !== null)
                                                                    {{ number_format($componentGrade, 1) }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td class="text-center">
                                                                @if($componentTransmutedGrade !== null)
                                                                    {{ $componentTransmutedGrade }}
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($hasGrade && $isApproved)
                                        <span class="fw-bold">{{ number_format($initialGrade, 1) }}</span>
                                    @elseif($hasGrade && !$isApproved)
                                        <span class="text-muted">Pending approval</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($hasGrade && $isApproved && $transmutedGrade !== null)
                                        <span class="fw-bold">{{ $transmutedGrade }}</span>
                                    @elseif($hasGrade && !$isApproved)
                                        <span class="text-muted">Pending</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($hasGrade && $isApproved)
                                        <span class="badge bg-{{ $remarks == 'Passed' ? 'success' : 'danger' }} bg-opacity-10 text-{{ $remarks == 'Passed' ? 'success' : 'danger' }} px-2 py-1">
                                            {{ $remarks }}
                                        </span>
                                    @elseif($hasGrade && !$isApproved)
                                        <span class="badge bg-warning bg-opacity-10 text-warning px-2 py-1">Pending</span>
                                    @else
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">No Grade</span>
                                    @endif
                                </td>
                                <td>{{ $teacherName }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="summary mb-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Grade Summary</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <tr>
                                    <th width="60%">Overall Average:</th>
                                    <td>
                                        @php
                                            $transmutedOverallAverage = \App\Helpers\GradeHelper::getTransmutedGrade($overallAverage, $transmutationTable ?? 1);
                                        @endphp
                                        <span class="fw-bold">{{ $transmutedOverallAverage }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status:</th>
                                    <td>
                                        @if($overallAverage > 0)
                                            <span class="badge bg-{{ $transmutedOverallAverage >= 75 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $transmutedOverallAverage >= 75 ? 'success' : 'danger' }} px-2 py-1">
                                                {{ $transmutedOverallAverage >= 75 ? 'Passed' : 'For Remedial' }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1">
                                                No Grades Available
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0">Attendance Summary</h6>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-0">Attendance information not available in this version.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="signatures mt-5">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center">
                        <div class="signature-line"></div>
                        <p class="mb-0"><strong>{{ $section->adviser->name ?? 'Class Adviser' }}</strong></p>
                        <p class="text-muted">Class Adviser</p>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center">
                        <div class="signature-line"></div>
                        <p class="mb-0"><strong>{{ $section->school->principal_name ?? 'School Principal' }}</strong></p>
                        <p class="text-muted">School Principal</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="footer mt-5 pt-3 border-top">
            <div class="row">
                <div class="col-md-6">
                    <p class="text-muted mb-0"><small>Generated on: {{ isset($currentTime) ? $currentTime->format('F d, Y h:i A') : now()->format('F d, Y h:i A') }} (Manila Time)</small></p>
                </div>
                <div class="col-md-6 text-end">
                    <p class="text-muted mb-0"><small>School Grading System</small></p>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .grade-slip-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .grade-slip {
        background-color: white;
        padding: 30px;
        border-radius: 5px;
    }

    .signature-line {
        border-top: 1px solid #000;
        width: 80%;
        margin: 50px auto 10px;
    }

    @media print {
        body {
            background-color: white;
        }

        .d-print-none {
            display: none !important;
        }

        .grade-slip {
            padding: 0;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin: 0;
        }

        .card {
            border: 1px solid #ddd !important;
        }
    }
</style>
@endsection
