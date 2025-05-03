<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Slip - {{ $student->last_name }}, {{ $student->first_name }}</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script>
        // Automatically trigger print dialog when the page loads
        window.onload = function() {
            // Small delay to ensure everything is loaded
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
    <style>
        /* Reset styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #333;
            background-color: white;
        }

        /* Container */
        .grade-slip-container {
            width: 100%;
            max-width: 21cm; /* A4 width */
            margin: 0 auto;
            padding: 1.5cm 1cm;
        }

        /* Header */
        .header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .logo-container {
            width: 15%;
            text-align: center;
        }

        .school-logo {
            max-height: 70px;
            max-width: 100%;
        }

        .header-text {
            width: 70%;
            text-align: center;
        }

        .school-info {
            margin-bottom: 5px;
        }

        .school-name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 5px 0;
        }

        .grade-slip-title {
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 2px;
            margin-top: 10px;
            text-transform: uppercase;
        }

        /* Student info */
        .student-info {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }

        .student-info-col {
            width: 50%;
        }

        .info-row {
            margin-bottom: 5px;
            display: flex;
        }

        .info-label {
            font-weight: bold;
            width: 120px;
            flex-shrink: 0;
        }

        .info-value {
            flex-grow: 1;
        }

        /* Grades table */
        .grades-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .grades-table th,
        .grades-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }

        .grades-table th {
            background-color: #e9ecef;
            font-weight: bold;
            text-transform: uppercase;
            text-align: left;
        }

        .grades-table .center {
            text-align: center;
        }

        .mapeh-component {
            padding-left: 25px !important;
            font-style: italic;
            color: #666;
            background-color: #f8f9fa;
        }

        /* Summary section */
        .summary-section {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
        }

        .summary-col {
            width: 50%;
            padding: 0 10px;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 3px;
            margin-bottom: 5px;
            font-size: 11pt;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }

        /* Signature section */
        .signature-section {
            text-align: center;
            margin-top: 40px;
        }

        .signature-spacer {
            height: 30px; /* Add extra space for signature */
        }

        .signature-line {
            width: 60%;
            margin: 0 auto;
            border-top: 1px solid #000;
            margin-bottom: 5px;
        }

        /* Utility classes */
        .text-center {
            text-align: center;
        }

        .text-success {
            color: #28a745;
        }

        .text-danger {
            color: #dc3545;
        }

        .fw-bold {
            font-weight: bold;
        }

        .small {
            font-size: 10pt;
        }

        .text-muted {
            color: #6c757d;
        }

        .mt-3 {
            margin-top: 15px;
        }

        /* Grading scale specific styling */
        .compact-grading-scale {
            margin-top: 5px;
            font-size: 11pt;
            line-height: 1.3;
        }

        .compact-grading-scale div {
            margin-bottom: 3px;
        }
    </style>
</head>
<body>
    <div style="position: fixed; top: 10px; right: 10px; z-index: 1000; display: none;" class="print-controls">
        <a href="{{ route('teacher.reports.grade-slip-preview', [
            'student_id' => $student->id,
            'section_id' => $section->id,
            'quarter' => $quarter,
            'transmutation_table' => $transmutationTable ?? 1
        ]) }}" class="back-button" style="display: inline-block; padding: 8px 15px; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; font-weight: bold;">
            Back to Preview
        </a>
    </div>

    <script>
        // Show the back button only when not printing
        window.addEventListener('afterprint', function() {
            document.querySelector('.print-controls').style.display = 'block';
        });

        // Also show if the user cancels the print dialog
        setTimeout(function() {
            document.querySelector('.print-controls').style.display = 'block';
        }, 2000);
    </script>

    <div class="grade-slip-container">
        <!-- Header -->
        <div class="header">
            <div class="logo-container">
                <img src="{{ $section->school->logo_url }}" alt="School Logo" class="school-logo">
            </div>
            <div class="header-text">
                <div class="school-info small text-muted">Department of Education - {{ $region }}</div>
                <div class="school-info small text-muted">{{ $division }}{{ !empty($district) ? ' - ' . $district : '' }}</div>
                <div class="school-name">{{ strtoupper($schoolName) }}</div>
                <div class="school-info small text-muted">{{ $address }}</div>
                <div class="grade-slip-title">GRADE SLIP</div>
                <div class="school-info small">{{ $quarterName }} | SY {{ $schoolYear }}</div>
            </div>
            <div class="logo-container">
                <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo" class="school-logo">
            </div>
        </div>

        <!-- Student Info -->
        <div class="student-info">
            <div class="student-info-col">
                <div class="info-row">
                    <span class="info-label">Student Name:</span>
                    <span class="info-value fw-bold">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Student ID:</span>
                    <span class="info-value">{{ $student->student_id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Gender:</span>
                    <span class="info-value">{{ $student->gender }}</span>
                </div>
            </div>
            <div class="student-info-col">
                <div class="info-row">
                    <span class="info-label">Grade Level:</span>
                    <span class="info-value fw-bold">{{ $section->grade_level }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Section:</span>
                    <span class="info-value fw-bold">{{ $section->name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Adviser:</span>
                    <span class="info-value">{{ $section->adviser->name ?? 'Not assigned' }}</span>
                </div>
            </div>
        </div>

        <!-- Grades Table -->
        <table class="grades-table">
            <thead>
                <tr>
                    <th width="3%">#</th>
                    <th width="42%">SUBJECT</th>
                    <th width="15%" class="center">QUARTERLY GRADE</th>
                    <th width="15%" class="center">REMARKS</th>
                    <th width="25%">SUBJECT TEACHER</th>
                </tr>
            </thead>
            <tbody>
                @php $index = 1; @endphp
                @foreach($subjects as $subject)
                    @php
                        $hasGrade = isset($studentGrades[$subject->id]);
                        $isApproved = isset($extendedApprovals[$subject->id]) && $extendedApprovals[$subject->id]->is_approved;
                        $transmutedGrade = $hasGrade && isset($studentGrades[$subject->id]->transmuted_grade) ? $studentGrades[$subject->id]->transmuted_grade : null;
                        $remarks = $hasGrade ? $studentGrades[$subject->id]->remarks : 'No Grade';

                        // Get the subject teacher
                        $subjectTeacher = $subject->teachers->first();
                        $teacherName = $subjectTeacher ? $subjectTeacher->name : 'Not assigned';
                    @endphp
                    <tr>
                        <td>{{ $index++ }}</td>
                        <td class="{{ $subject->is_mapeh ? 'fw-bold' : '' }}">{{ $subject->name }}</td>
                        <td class="center">
                            @if($hasGrade && $isApproved && $transmutedGrade !== null)
                                <span class="fw-bold">{{ $transmutedGrade }}</span>
                            @elseif($hasGrade && !$isApproved)
                                <span class="text-muted">Pending approval</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="center">
                            @if($hasGrade && $isApproved)
                                <span class="{{ $remarks == 'Passed' ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                    {{ $remarks }}
                                </span>
                            @elseif($hasGrade && !$isApproved)
                                <span class="text-muted">Pending</span>
                            @else
                                <span class="text-muted">No Grade</span>
                            @endif
                        </td>
                        <td>{{ $teacherName }}</td>
                    </tr>

                    @if($subject->is_mapeh)
                        @foreach($standardComponents as $key => $displayName)
                            <tr class="mapeh-component">
                                <td></td>
                                <td>{{ $displayName }}</td>
                                <td class="center">
                                    @if(isset($componentGrades[$key]) && $isApproved)
                                        <span class="fw-bold">{{ $componentGrades[$key] }}</span>
                                    @elseif(isset($componentGrades[$key]) && !$isApproved)
                                        <span class="text-muted">Pending</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="center">
                                    @if(isset($componentRemarks[$key]) && $isApproved)
                                        <span class="{{ $componentRemarks[$key] == 'Passed' ? 'text-success' : 'text-danger' }}">
                                            {{ $componentRemarks[$key] }}
                                        </span>
                                    @elseif(isset($componentGrades[$key]) && !$isApproved)
                                        <span class="text-muted">Pending</span>
                                    @else
                                        <span class="text-muted">No Grade</span>
                                    @endif
                                </td>
                                <td></td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
            </tbody>
        </table>

        <!-- Summary Section -->
        <div class="summary-section">
            <div class="summary-col">
                <div class="section-title">Grade Summary</div>
                <table class="summary-table">
                    <tr>
                        <th width="60%">General Average:</th>
                        <td class="center">
                            @if($allApproved)
                                <span class="fw-bold">{{ $generalAverage }}</span>
                            @else
                                <span class="text-muted">Grades Unavailable - awaiting for approval</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Final Remarks:</th>
                        <td class="center">
                            @if($allApproved)
                                @if($generalAverage >= 75)
                                    <span class="text-success fw-bold">PASSED</span>
                                @else
                                    <span class="text-danger fw-bold">FAILED</span>
                                @endif
                            @else
                                <span class="text-muted">Pending</span>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="summary-col">
                <div class="section-title">Academic Recognition</div>
                <table class="summary-table">
                    <tr>
                        <th width="40%">Recognition:</th>
                        <td>
                            @if($allApproved)
                                @if($generalAverage >= 98)
                                    <span class="fw-bold">With Highest Honors</span>
                                @elseif($generalAverage >= 95)
                                    <span class="fw-bold">With High Honors</span>
                                @elseif($generalAverage >= 90)
                                    <span class="fw-bold">With Honors</span>
                                @else
                                    <span class="text-muted">Not qualified for honors</span>
                                @endif
                            @else
                                <span class="text-muted">Pending</span>
                            @endif
                        </td>
                    </tr>
                </table>

                <!-- Grading scale removed from print layout -->
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-spacer"></div>
            <div class="signature-line"></div>
            <p class="fw-bold">{{ strtoupper($section->adviser->name ?? '') }}</p>
            <p class="text-muted">Class Adviser</p>
        </div>
    </div>
</body>
</html>
