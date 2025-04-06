@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0">Grade Slips - {{ $section->name }}</h2>
                <a href="{{ route('teacher.reports.grade-slips') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Grade Slips
                </a>
            </div>
            <p class="text-muted">{{ $quarterName }} | School Year: {{ $schoolYear }}</p>
            <p class="small text-muted">Using {{ $transmutationTableNames[$transmutationTable] }}</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Student List</h5>
                    <div>
                        <button class="btn btn-primary" onclick="window.print()">
                            <i class="fas fa-print me-2"></i> Print All Grade Slips
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($students->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            No students found in this section.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Student ID</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Subjects with Grades</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($students as $index => $student)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $student->student_id }}</td>
                                            <td>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>
                                            <td>{{ $student->gender }}</td>
                                            <td>
                                                @php
                                                    $subjectCount = 0;
                                                    if (isset($studentGrades[$student->id])) {
                                                        $subjectCount = count($studentGrades[$student->id]);
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $subjectCount > 0 ? 'success' : 'warning' }} bg-opacity-10 text-{{ $subjectCount > 0 ? 'success' : 'warning' }} px-2 py-1">
                                                    {{ $subjectCount }} / {{ $subjects->count() }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('teacher.reports.grade-slip-preview', [
                                                    'student_id' => $student->id,
                                                    'section_id' => $section->id,
                                                    'quarter' => $quarter
                                                ]) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                    <i class="fas fa-eye me-1"></i> Preview
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Grade Slip Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Section Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="30%">Section Name:</th>
                                    <td>{{ $section->name }}</td>
                                </tr>
                                <tr>
                                    <th>Grade Level:</th>
                                    <td>{{ $section->grade_level }}</td>
                                </tr>
                                <tr>
                                    <th>Adviser:</th>
                                    <td>{{ $section->adviser->name ?? 'Not assigned' }}</td>
                                </tr>
                                <tr>
                                    <th>School Year:</th>
                                    <td>{{ $schoolYear }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Grade Slip Details</h6>
                            <table class="table table-sm">
                                <tr>
                                    <th width="30%">Quarter:</th>
                                    <td>{{ $quarterName }}</td>
                                </tr>
                                <tr>
                                    <th>Total Students:</th>
                                    <td>{{ $students->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Total Subjects:</th>
                                    <td>{{ $subjects->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Generated On:</th>
                                    <td>{{ isset($currentTime) ? $currentTime->format('F d, Y h:i A') : now()->format('F d, Y h:i A') }} (Manila Time)</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(isset($debug) && $debug)
    <div class="row mt-4 d-print-none">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Debug Information</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        This section is only visible in debug mode and shows detailed information about the grade calculations.
                    </div>

                    <h6 class="mb-3">Section Information</h6>
                    <pre class="bg-light p-3 rounded">{{ json_encode(['id' => $section->id, 'name' => $section->name, 'grade_level' => $section->grade_level], JSON_PRETTY_PRINT) }}</pre>

                    <h6 class="mb-3">MAPEH Configuration</h6>
                    <pre class="bg-light p-3 rounded">{{ json_encode(['mapeh_subjects' => $mapehSubjects->pluck('name', 'id')->toArray(), 'mapeh_components' => $mapehComponents->pluck('name', 'id')->toArray(), 'mapeh_parent_map' => $mapehParentMap], JSON_PRETTY_PRINT) }}</pre>

                    <h6 class="mb-3">Grade Approvals</h6>
                    <pre class="bg-light p-3 rounded">{{ json_encode($gradeApprovals->map(function($approval) { return ['subject_id' => $approval->subject_id, 'is_approved' => $approval->is_approved]; })->toArray(), JSON_PRETTY_PRINT) }}</pre>

                    <h6 class="mb-3">Sample Student Grade Data</h6>
                    @if($students->isNotEmpty() && isset($studentGrades[$students->first()->id]))
                        <pre class="bg-light p-3 rounded">{{ json_encode($debugData[$students->first()->id], JSON_PRETTY_PRINT) }}</pre>

                        <h6 class="mb-3 mt-4">Grade Component Analysis</h6>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            This table shows the component breakdown for each subject for the first student in the list.
                        </div>
                        <table class="table table-sm table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject</th>
                                    <th>Data Source</th>
                                    <th>Written Works</th>
                                    <th>Performance Tasks</th>
                                    <th>Quarterly Assessment</th>
                                    <th>Final Grade</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $firstStudentId = $students->first()->id; @endphp
                                @foreach($subjects->reject(function($subject) { return $subject->mapeh_component; }) as $subject)
                                    @if(isset($studentGrades[$firstStudentId][$subject->id]))
                                        @php
                                            $grade = $studentGrades[$firstStudentId][$subject->id];
                                            $fromGradeSummary = isset($grade->raw_data['from_grade_summary']) && $grade->raw_data['from_grade_summary'];
                                        @endphp
                                        <tr>
                                            <td>{{ $subject->name }}</td>
                                            <td>
                                                @if($fromGradeSummary)
                                                    <span class="badge bg-success bg-opacity-10 text-success">
                                                        Class Record Data
                                                    </span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning">
                                                        Calculated
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($fromGradeSummary)
                                                    <div class="mt-1 small">PS: {{ number_format($grade->components['written_work']['ps'], 1) }}%</div>
                                                    <div class="small">WS: {{ number_format($grade->components['written_work']['ws'], 1) }}</div>
                                                @elseif(isset($grade->raw_data['written_works_count']))
                                                    <span class="badge bg-{{ $grade->raw_data['written_works_count'] > 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $grade->raw_data['written_works_count'] > 0 ? 'success' : 'danger' }}">
                                                        {{ $grade->raw_data['written_works_count'] }} items
                                                    </span>
                                                    @if(isset($grade->components['written_work']['ps']))
                                                        <div class="mt-1 small">PS: {{ number_format($grade->components['written_work']['ps'], 1) }}%</div>
                                                        <div class="small">WS: {{ number_format($grade->components['written_work']['ws'], 1) }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No data</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($fromGradeSummary)
                                                    <div class="mt-1 small">PS: {{ number_format($grade->components['performance_task']['ps'], 1) }}%</div>
                                                    <div class="small">WS: {{ number_format($grade->components['performance_task']['ws'], 1) }}</div>
                                                @elseif(isset($grade->raw_data['performance_tasks_count']))
                                                    <span class="badge bg-{{ $grade->raw_data['performance_tasks_count'] > 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $grade->raw_data['performance_tasks_count'] > 0 ? 'success' : 'danger' }}">
                                                        {{ $grade->raw_data['performance_tasks_count'] }} items
                                                    </span>
                                                    @if(isset($grade->components['performance_task']['ps']))
                                                        <div class="mt-1 small">PS: {{ number_format($grade->components['performance_task']['ps'], 1) }}%</div>
                                                        <div class="small">WS: {{ number_format($grade->components['performance_task']['ws'], 1) }}</div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No data</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($fromGradeSummary)
                                                    <div class="mt-1 small">PS: {{ number_format($grade->components['quarterly_assessment']['ps'], 1) }}%</div>
                                                    <div class="small">WS: {{ number_format($grade->components['quarterly_assessment']['ws'], 1) }}</div>
                                                @elseif(isset($grade->raw_data['quarterly_assessments_count']))
                                                    <span class="badge bg-{{ $grade->raw_data['quarterly_assessments_count'] > 0 ? 'success' : 'danger' }} bg-opacity-10 text-{{ $grade->raw_data['quarterly_assessments_count'] > 0 ? 'success' : 'danger' }}">
                                                        {{ $grade->raw_data['quarterly_assessments_count'] }} items
                                                    </span>
                                                    @if(isset($grade->components['quarterly_assessment']['ps']))
                                                        <div class="mt-1 small">PS: {{ number_format($grade->components['quarterly_assessment']['ps'], 1) }}%</div>
                                                        <div class="small">WS: {{ number_format($grade->components['quarterly_assessment']['ws'], 1) }}</div>
                                                    @else
                                                        <div class="mt-1 small text-danger">Missing data</div>
                                                    @endif
                                                @else
                                                    <span class="text-muted">No data</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="fw-bold">{{ number_format($grade->quarterly_grade, 1) }}</span>
                                                <div class="small">{{ $grade->remarks }}</div>
                                                @if($fromGradeSummary && isset($grade->raw_data['initial_grade']))
                                                    <div class="small text-muted">Initial: {{ number_format($grade->raw_data['initial_grade'], 1) }}</div>
                                                    <div class="small text-muted">Quarterly: {{ number_format($grade->raw_data['quarterly_grade'], 1) }}</div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-warning">No student grade data available for debugging.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .container, .row, .col-md-12, .card {
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
        }

        .no-print {
            display: none !important;
        }
    }
</style>
@endsection
