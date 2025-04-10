@extends('layouts.report')

@php
// Import the GradeHelper class
use App\Helpers\GradeHelper;

// Define a local helper function if the global one is not available
if (!function_exists('getTransmutedGrade')) {
    function getTransmutedGrade($initialGrade, $tableType) {
        return GradeHelper::getTransmutedGrade($initialGrade, $tableType);
    }
}
@endphp

@section('styles')
<style>
    .consolidated-grade-table th, .consolidated-grade-table td {
        vertical-align: middle;
    }
    .grade-cell {
        position: relative;
    }
    .inherited-check {
        position: absolute;
        top: 2px;
        right: 2px;
        font-size: 10px;
    }
    @media print {
        .card {
            border: none !important;
            box-shadow: none !important;
        }
        .bg-primary {
            background-color: #0d6efd !important;
            color: white !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .bg-info {
            background-color: #0dcaf0 !important;
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
        .bg-light {
            background-color: #f8f9fa !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .text-white {
            color: white !important;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Transmutation form moved to layout -->

    <div class="header">
        <div class="header-row">
            <div class="logo-left">
                @if(isset($section) && $section->school && $section->school->logo_path)
                    <img src="{{ $section->school->logo_url }}" alt="School Logo">
                @else
                    <div style="width: 80px; height: 80px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-school fa-3x"></i>
                    </div>
                @endif
            </div>
            <div class="title-center">
                <h1>CONSOLIDATED GRADING SHEET</h1>
                <p>(Pursuant to DepEd Order 8 series of 2015)</p>
            </div>
            <div class="logo-right">
                <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo">
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="info-table">
            <tr>
                <td class="info-label table-header">REGION</td>
                <td class="info-value">{{ isset($section) && $section->school && $section->school->schoolDivision ? $section->school->schoolDivision->region : 'Region' }}</td>
                <td class="info-label table-header">QUARTER</td>
                <td class="info-value">{{ $quarterName }}</td>
            </tr>
            <tr>
                <td class="info-label table-header">DIVISION</td>
                <td class="info-value">{{ isset($section) && $section->school && $section->school->schoolDivision ? $section->school->schoolDivision->name : 'Division' }}</td>
                <td class="info-label table-header">SCHOOL YEAR</td>
                <td class="info-value">{{ $schoolYear }}</td>
            </tr>
            <tr>
                <td class="info-label table-header">SCHOOL NAME</td>
                <td class="info-value">{{ $section->school->name }}</td>
                <td class="info-label table-header">SECTION</td>
                <td class="info-value"> {{ $section->grade_level }} - {{ $section->name }}</td>
            </tr>
        </table>
    </div>

    <div class="table-responsive">
        <table style="table-layout: fixed;">
            <tr>
                <td style="width: 20%;" class="table-header">{{ $quarterName }}</td>
                <td style="width: 30%;" class="table-header">GRADE & SECTION: {{ $section->grade_level }} - {{ $section->name }}</td>
                <td style="width: 25%;" class="table-header">ADVISER: {{ $section->adviser->name }}</td>
                <td style="width: 25%;" class="table-header">SCHOOL YEAR: {{ $schoolYear }}</td>
            </tr>
        </table>
    </div>

    <!-- Male Students -->
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;" class="column-header">No.</th>
                    <th rowspan="2" style="width: 20%;" class="column-header">LEARNERS' NAMES</th>

                    <!-- MAPEH Subjects -->
                    @foreach($subjects->where('is_component', false) as $subject)
                        @if($subject->getIsMAPEHAttribute())
                            <th colspan="5" class="text-center column-header">{{ $subject->name }}</th>
                        @else
                            <th rowspan="2" class="text-center column-header">{{ $subject->name }}</th>
                        @endif
                    @endforeach

                    <th rowspan="2" class="text-center column-header">Rating</th>
                </tr>
                <tr>
                    <!-- MAPEH Components -->
                    @foreach($subjects->where('is_component', false) as $subject)
                        @if($subject->getIsMAPEHAttribute())
                            @foreach($subject->components as $component)
                                <th class="text-center column-header">
                                    @if(strtolower($component->name) == 'physical education')
                                        P.E.
                                    @else
                                        {{ $component->name }}
                                    @endif
                                </th>
                            @endforeach
                            <th class="text-center column-header">MAPEH</th>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    <th class="table-header"></th>
                    <th class="table-header">Male</th>
                    <th colspan="98" class="table-header"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($maleStudents as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>

                                @foreach($subjects->where('is_component', false) as $subject)
                                    @if($subject->getIsMAPEHAttribute())
                                        @foreach($subject->components as $component)
                                            <td class="text-center">
                                                @if(isset($studentGrades[$student->id][$component->id]) &&
                                                    isset($gradeApprovals[$component->id]) &&
                                                    $gradeApprovals[$component->id]->is_approved)
                                                    <div class="grade-cell">
                                                        @php
                                                            $initialGrade = $studentGrades[$student->id][$component->id]->quarterly_grade;
                                                            $transmutedGrade = getTransmutedGrade($initialGrade, $preferredTableId);
                                                        @endphp
                                                        <span class="transmuted-grade fw-bold">{{ $transmutedGrade }}</span>
                                                        @if(isset($gradeApprovals[$component->id]->inherited_from_parent))
                                                            <!-- Indicate this approval is inherited from parent with just a small icon -->
                                                            <small class="text-success inherited-check"><i class="fas fa-check-circle"></i></small>
                                                        @endif
                                                    </div>
                                                @elseif(isset($studentGrades[$student->id][$component->id]))
                                                    <!-- Has grade but not approved -->
                                                    <small class="text-danger d-block">
                                                        Not approved
                                                    </small>
                                                @else
                                                    <!-- Debug info -->
                                                    <small class="text-danger">
                                                        No grade
                                                    </small>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-center fw-bold">
                                            @if(isset($studentGrades[$student->id][$subject->id]) &&
                                                isset($gradeApprovals[$subject->id]) &&
                                                $gradeApprovals[$subject->id]->is_approved)
                                                @php
                                                    $initialGrade = $studentGrades[$student->id][$subject->id]->quarterly_grade;
                                                    $transmutedGrade = getTransmutedGrade($initialGrade, $preferredTableId);
                                                @endphp
                                                <span class="transmuted-grade fw-bold">{{ $transmutedGrade }}</span>
                                            @elseif(isset($studentGrades[$student->id][$subject->id]))
                                                <!-- Has grade but not approved -->
                                                <small class="text-danger d-block">
                                                    Not approved
                                                </small>
                                            @else
                                                <!-- Debug info -->
                                                <small class="text-danger">
                                                    No grade
                                                </small>
                                            @endif
                                        </td>
                                    @else
                                        <!-- Final Grade -->
                                        <td class="text-center">
                                            @if(isset($studentGrades[$student->id][$subject->id]) &&
                                                isset($gradeApprovals[$subject->id]) &&
                                                $gradeApprovals[$subject->id]->is_approved)
                                                @php
                                                    $initialGrade = $studentGrades[$student->id][$subject->id]->quarterly_grade;
                                                    $transmutedGrade = getTransmutedGrade($initialGrade, $preferredTableId);
                                                @endphp
                                                <span class="transmuted-grade fw-bold">{{ $transmutedGrade }}</span>
                                            @elseif(isset($studentGrades[$student->id][$subject->id]))
                                                <!-- Has grade but not approved -->
                                                <small class="text-danger d-block">
                                                    Not approved
                                                </small>
                                            @else
                                                <!-- Debug info -->
                                                <small class="text-danger">
                                                    No grade
                                                </small>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                <td class="text-center">
                                    <!-- Calculate average of all subjects -->
                                    @php
                                        $totalGrade = 0;
                                        $subjectCount = 0;
                                        $totalSubjects = $subjects->where('is_component', false)->count();
                                        $approvedSubjects = 0;

                                        foreach($subjects->where('is_component', false) as $subject) {
                                            if(isset($studentGrades[$student->id][$subject->id]) &&
                                               isset($gradeApprovals[$subject->id]) &&
                                               $gradeApprovals[$subject->id]->is_approved) {
                                                $totalGrade += $studentGrades[$student->id][$subject->id]->quarterly_grade;
                                                $subjectCount++;
                                                $approvedSubjects++;
                                            }
                                        }

                                        $allSubjectsApproved = ($approvedSubjects == $totalSubjects);
                                        $average = $subjectCount > 0 ? round($totalGrade / $subjectCount) : '';
                                        $transmutedAverage = $average ? getTransmutedGrade($average, $preferredTableId) : '';
                                    @endphp
                                    @if($average && $allSubjectsApproved)
                                        <span class="transmuted-grade fw-bold">{{ $transmutedAverage }}</span>
                                    @elseif(!$allSubjectsApproved)
                                        <small class="text-danger">Not all subjects approved</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">No male students found in this section.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Female Students -->
            <div class="table-responsive">
                <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width: 5%;" class="column-header">No.</th>
                    <th rowspan="2" style="width: 20%;" class="column-header">LEARNERS' NAMES</th>

                    <!-- MAPEH Subjects -->
                    @foreach($subjects->where('is_component', false) as $subject)
                        @if($subject->getIsMAPEHAttribute())
                            <th colspan="5" class="text-center column-header">{{ $subject->name }}</th>
                        @else
                            <th rowspan="2" class="text-center column-header">{{ $subject->name }}</th>
                        @endif
                    @endforeach

                    <th rowspan="2" class="text-center column-header">Rating</th>
                </tr>
                <tr>
                    <!-- MAPEH Components -->
                    @foreach($subjects->where('is_component', false) as $subject)
                        @if($subject->getIsMAPEHAttribute())
                            @foreach($subject->components as $component)
                                <th class="text-center column-header">
                                    @if(strtolower($component->name) == 'physical education')
                                        P.E.
                                    @else
                                        {{ $component->name }}
                                    @endif
                                </th>
                            @endforeach
                            <th class="text-center column-header">MAPEH</th>
                        @endif
                    @endforeach
                </tr>
                <tr>
                    <th class="table-header"></th>
                    <th class="table-header">Female</th>
                    <th colspan="98" class="table-header"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($femaleStudents as $index => $student)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>

                                @foreach($subjects->where('is_component', false) as $subject)
                                    @if($subject->getIsMAPEHAttribute())
                                        @foreach($subject->components as $component)
                                            <td class="text-center">
                                                @if(isset($studentGrades[$student->id][$component->id]) &&
                                                    isset($gradeApprovals[$component->id]) &&
                                                    $gradeApprovals[$component->id]->is_approved)
                                                    <div class="grade-cell">
                                                        @php
                                                            $initialGrade = $studentGrades[$student->id][$component->id]->quarterly_grade;
                                                            $transmutedGrade = getTransmutedGrade($initialGrade, $preferredTableId);
                                                        @endphp
                                                        <span class="transmuted-grade fw-bold">{{ $transmutedGrade }}</span>
                                                        @if(isset($gradeApprovals[$component->id]->inherited_from_parent))
                                                            <!-- Indicate this approval is inherited from parent with just a small icon -->
                                                            <small class="text-success inherited-check"><i class="fas fa-check-circle"></i></small>
                                                        @endif
                                                    </div>
                                                @elseif(isset($studentGrades[$student->id][$component->id]))
                                                    <!-- Has grade but not approved -->
                                                    <small class="text-danger d-block">
                                                        Not approved
                                                    </small>
                                                @else
                                                    <!-- Debug info -->
                                                    <small class="text-danger">
                                                        No grade
                                                    </small>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td class="text-center fw-bold">
                                            @if(isset($studentGrades[$student->id][$subject->id]) &&
                                                isset($gradeApprovals[$subject->id]) &&
                                                $gradeApprovals[$subject->id]->is_approved)
                                                @php
                                                    $initialGrade = $studentGrades[$student->id][$subject->id]->quarterly_grade;
                                                    $transmutedGrade = getTransmutedGrade($initialGrade, $preferredTableId);
                                                @endphp
                                                <span class="transmuted-grade fw-bold">{{ $transmutedGrade }}</span>
                                            @elseif(isset($studentGrades[$student->id][$subject->id]))
                                                <!-- Has grade but not approved -->
                                                <small class="text-danger d-block">
                                                    Not approved
                                                </small>
                                            @else
                                                <!-- Debug info -->
                                                <small class="text-danger">
                                                    No grade
                                                </small>
                                            @endif
                                        </td>
                                    @else
                                        <!-- Final Grade -->
                                        <td class="text-center">
                                            @if(isset($studentGrades[$student->id][$subject->id]) &&
                                                isset($gradeApprovals[$subject->id]) &&
                                                $gradeApprovals[$subject->id]->is_approved)
                                                @php
                                                    $initialGrade = $studentGrades[$student->id][$subject->id]->quarterly_grade;
                                                    $transmutedGrade = getTransmutedGrade($initialGrade, $preferredTableId);
                                                @endphp
                                                <span class="transmuted-grade fw-bold">{{ $transmutedGrade }}</span>
                                            @elseif(isset($studentGrades[$student->id][$subject->id]))
                                                <!-- Has grade but not approved -->
                                                <small class="text-danger d-block">
                                                    Not approved
                                                </small>
                                            @else
                                                <!-- Debug info -->
                                                <small class="text-danger">
                                                    No grade
                                                </small>
                                            @endif
                                        </td>
                                    @endif
                                @endforeach

                                <td class="text-center">
                                    <!-- Calculate average of all subjects -->
                                    @php
                                        $totalGrade = 0;
                                        $subjectCount = 0;
                                        $totalSubjects = $subjects->where('is_component', false)->count();
                                        $approvedSubjects = 0;

                                        foreach($subjects->where('is_component', false) as $subject) {
                                            if(isset($studentGrades[$student->id][$subject->id]) &&
                                               isset($gradeApprovals[$subject->id]) &&
                                               $gradeApprovals[$subject->id]->is_approved) {
                                                $totalGrade += $studentGrades[$student->id][$subject->id]->quarterly_grade;
                                                $subjectCount++;
                                                $approvedSubjects++;
                                            }
                                        }

                                        $allSubjectsApproved = ($approvedSubjects == $totalSubjects);
                                        $average = $subjectCount > 0 ? round($totalGrade / $subjectCount) : '';
                                        $transmutedAverage = $average ? getTransmutedGrade($average, $preferredTableId) : '';
                                    @endphp
                                    @if($average && $allSubjectsApproved)
                                        <span class="transmuted-grade fw-bold">{{ $transmutedAverage }}</span>
                                    @elseif(!$allSubjectsApproved)
                                        <small class="text-danger">Not all subjects approved</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="100%" class="text-center">No female students found in this section.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>



    <!-- Signature Section -->
    <div class="signature-section">
        <div class="signature-item">
            <p style="margin-bottom: 30px;">Prepared by:</p>
            <div class="signature-line"></div>
            <p><strong>{{ $section->adviser->name }}</strong></p>
            <p>{{ $section->grade_level }}-{{ $section->name }} Homeroom Adviser</p>
        </div>

        <!-- <div class="signature-item">
            <p style="margin-bottom: 30px;">Checked by:</p>
            <div class="signature-line"></div>
            <p><strong>{{ $section->coordinator_name ?? 'Subject Coordinator' }}</strong></p>
            <p>Subject Coordinator</p>
        </div> -->

        <div class="signature-item">
            <p style="margin-bottom: 30px;">Approved by:</p>
            <div class="signature-line"></div>
            <p><strong>{{ isset($section) && $section->school && $section->school->principal ? $section->school->principal : 'School Principal' }}</strong></p>
            <p>School Principal</p>
        </div>
    </div>
</div>

<style>
    /* General table styling */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 1rem;
        font-size: 0.85rem;
    }

    th, td {
        padding: 0.5rem;
        text-align: center;
        border: 1px solid #000;
        vertical-align: middle;
    }

    th {
        font-weight: bold;
        background-color: #f2f2f2;
        border: 1px solid #000;
    }

    /* Enhanced table header styling */
    .table-header {
        font-weight: bold;
        text-align: center;
        background-color: #e0e0e0;
        border: 1px solid #000;
    }

    /* Additional styling for column headers in the main grades table */
    .column-header {
        background-color: #f8f8f8;
        font-weight: bold;
        text-align: center;
        padding: 3px;
        border: 1px solid #000;
    }

    /* Header styling */
    .header {
        margin-bottom: 1.5rem;
    }

    .header-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1rem;
    }

    .logo-left, .logo-right {
        width: 15%;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .logo-left img, .logo-right img {
        max-width: 100px;
        max-height: 80px;
        object-fit: contain;
    }

    .title-center {
        width: 70%;
        text-align: center;
    }

    .title-center h1 {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.25rem;
        text-align: center;
    }

    .title-center p {
        font-size: 0.9rem;
        text-align: center;
        margin: 0;
    }

    /* Grade styling */
    .transmuted-grade {
        display: inline-block;
        font-weight: bold;
        color: #0d6efd;
    }

    /* Signature section */
    .signature-section {
        margin-top: 2rem;
        display: flex;
        justify-content: space-between;
    }

    .signature-item {
        width: 30%;
        text-align: center;
    }

    .signature-line {
        border-bottom: 1px solid #000;
        margin: 0 auto 0.5rem;
        width: 80%;
    }

    /* Print styles */
    @media print {
        body {
            margin: 0;
            padding: 0;
            font-size: 11pt;
        }

        .no-print {
            display: none !important;
        }

        table {
            page-break-inside: avoid;
            width: 100%;
        }

        th, td {
            font-size: 9pt;
            padding: 3px;
        }

        .header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-left, .logo-right {
            width: 15%;
            text-align: center;
        }

        .logo-left img, .logo-right img {
            max-width: 80px;
            max-height: 80px;
        }

        .title-center {
            width: 70%;
        }

        /* Ensure table headers maintain background color when printing */
        th {
            background-color: #f2f2f2 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .table-header {
            background-color: #e0e0e0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .info-label {
            background-color: #e0e0e0 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-weight: bold !important;
        }

        .column-header {
            background-color: #f8f8f8 !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .signature-section {
            margin-top: 30px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Set the selected transmutation table based on the preferred table ID
        const transmutationSelect = document.getElementById('transmutation_table');
        if (transmutationSelect) {
            transmutationSelect.value = '{{ $preferredTableId }}';
        }
    });

    function updateTransmutedGrades() {
        // Get the selected transmutation table
        const tableId = document.getElementById('transmutation_table').value;

        // Reload the page with the new transmutation table
        const currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('transmutation_table', tableId);
        window.location.href = currentUrl.toString();
    }
</script>
@endsection
