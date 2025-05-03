<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Slip (All Quarters) - {{ $student->last_name }}, {{ $student->first_name }}</title>
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
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 5px;
        }

        .student-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .student-info-table th {
            width: 15%;
            text-align: left;
            padding: 5px;
        }

        .student-info-table td {
            padding: 5px;
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

        .table-secondary {
            background-color: #e2e3e5;
        }

        /* Signature and grading scale sections */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }

        .col-half {
            width: 50%;
            padding: 0 10px;
        }

        .section-title {
            font-weight: bold;
            text-transform: uppercase;
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 5px;
            margin-bottom: 10px;
        }

        .signature-table,
        .grading-scale-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .signature-table td,
        .grading-scale-table td {
            border: 1px solid #dee2e6;
            padding: 8px;
        }

        /* Signature section */
        .adviser-signature {
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

        .text-muted {
            color: #6c757d;
        }

        .fw-bold {
            font-weight: bold;
        }

        .small {
            font-size: 10pt;
        }

        .mt-3 {
            margin-top: 15px;
        }

        .mb-3 {
            margin-bottom: 15px;
        }

        .mt-5 {
            margin-top: 25px;
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
            'quarter' => 'all',
            'transmutation_table' => $transmutationTable ?? 1,
            'all_quarters' => true
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
                <div class="school-info small">SY {{ $schoolYear }}</div>
            </div>
            <div class="logo-container">
                <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo" class="school-logo">
            </div>
        </div>

        <!-- Student Info -->
        <div class="student-info">
            <table class="student-info-table">
                <tr>
                    <th>Name:</th>
                    <td class="fw-bold">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>
                    <th>Gr.&Section:</th>
                    <td class="fw-bold">{{ $section->grade_level }} {{ $section->name }}</td>
                </tr>
                <tr>
                    <th>Student ID:</th>
                    <td>{{ $student->student_id ?? 'Not available' }}</td>
                    <th>Adviser:</th>
                    <td>{{ $section->adviser->name ?? 'Not assigned' }}</td>
                </tr>
            </table>
        </div>

        <!-- Grades Table -->
        <table class="grades-table">
            <thead>
                <tr>
                    <th width="30%">LEARNING AREAS</th>
                    <th width="12%" class="center">FIRST<br>QUARTER</th>
                    <th width="12%" class="center">SECOND<br>QUARTER</th>
                    <th width="12%" class="center">THIRD<br>QUARTER</th>
                    <th width="12%" class="center">FOURTH<br>QUARTER</th>
                    <th width="12%" class="center">FINAL<br>GRADE</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $subject)
                    @php
                        $isMAPEH = $subject->getIsMAPEHAttribute();
                        $hasComponents = $isMAPEH && $mapehComponents->filter(function($comp) use ($subject) {
                            return $comp->parent_subject_id == $subject->id;
                        })->count() > 0;
                    @endphp
                    <tr>
                        <td class="{{ $hasComponents ? 'fw-bold' : '' }}">{{ $subject->name }}</td>
                        @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $termQuarter)
                            <td class="center">
                                @if(isset($allQuartersGrades[$termQuarter][$subject->id]))
                                    @php
                                        $isApproved = isset($allQuartersApprovals[$termQuarter][$subject->id]) && $allQuartersApprovals[$termQuarter][$subject->id]->is_approved;
                                    @endphp
                                    @if($isApproved)
                                        <span class="fw-bold">{{ $allQuartersGrades[$termQuarter][$subject->id]->transmuted_grade ?? '-' }}</span>
                                    @else
                                        <span class="text-muted small">Pending</span>
                                    @endif
                                @else
                                    -
                                @endif
                            </td>
                        @endforeach
                        <td class="center">
                            @php
                                $allQuartersApproved = true;
                                foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $checkQuarter) {
                                    if (!isset($allQuartersApprovals[$checkQuarter][$subject->id]) ||
                                        !$allQuartersApprovals[$checkQuarter][$subject->id]->is_approved) {
                                        if (isset($allQuartersGrades[$checkQuarter][$subject->id])) {
                                            $allQuartersApproved = false;
                                            break;
                                        }
                                    }
                                }
                            @endphp
                            @if(isset($finalGrades[$subject->id]) && $allQuartersApproved)
                                <span class="fw-bold">{{ $finalGrades[$subject->id] }}</span>
                            @elseif(!$allQuartersApproved)
                                <span class="text-muted small">Pending</span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>

                    @if($isMAPEH && !$subject->mapeh_component)
                        @php
                            // Define standard components for consistency
                            $standardComponents = [
                                'Music' => 'Music',
                                'Arts' => 'Arts',
                                'PE' => 'P.E.',
                                'Health' => 'Health'
                            ];

                            // Get components for this MAPEH subject
                            $components = $mapehComponents->filter(function($comp) use ($subject) {
                                return $comp->parent_subject_id == $subject->id;
                            });

                            // Map components to their standard names
                            $existingComponentIds = [];
                            foreach($components as $component) {
                                $name = strtolower($component->name);
                                if (strpos($name, 'music') !== false) {
                                    $existingComponentIds['Music'] = $component->id;
                                } elseif (strpos($name, 'art') !== false) {
                                    $existingComponentIds['Arts'] = $component->id;
                                } elseif (strpos($name, 'physical') !== false ||
                                         strpos($name, 'p.e') !== false ||
                                         strpos($name, 'pe') !== false) {
                                    $existingComponentIds['PE'] = $component->id;
                                } elseif (strpos($name, 'health') !== false) {
                                    $existingComponentIds['Health'] = $component->id;
                                }
                            }
                        @endphp

                        @foreach($standardComponents as $key => $displayName)
                            @php
                                $componentId = $existingComponentIds[$key] ?? null;
                                $component = $componentId ? $mapehComponents->firstWhere('id', $componentId) : null;
                            @endphp
                            <tr class="mapeh-component">
                                <td style="padding-left: 25px;">{{ $displayName }}</td>
                                @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $termQuarter)
                                    <td class="center">
                                        @php
                                            $componentGrade = null;
                                            $componentTransmutedGrade = null;
                                            $foundComponent = false;
                                            $parentApproved = isset($allQuartersApprovals[$termQuarter][$subject->id]) &&
                                                            $allQuartersApprovals[$termQuarter][$subject->id]->is_approved;
                                            $componentApproved = $component && isset($allQuartersApprovals[$termQuarter][$component->id]) &&
                                                                $allQuartersApprovals[$termQuarter][$component->id]->is_approved;
                                            $isApproved = $parentApproved || $componentApproved;

                                            // Try from component_grades in MAPEH subject
                                            if ($component && isset($allQuartersGrades[$termQuarter][$subject->id]) &&
                                                isset($allQuartersGrades[$termQuarter][$subject->id]->component_grades[$component->id])) {
                                                $compGrade = $allQuartersGrades[$termQuarter][$subject->id]->component_grades[$component->id];
                                                if (is_object($compGrade)) {
                                                    $componentTransmutedGrade = isset($compGrade->transmuted_grade) ?
                                                        $compGrade->transmuted_grade : null;
                                                    $foundComponent = true;
                                                }
                                            }

                                            // Try direct lookup from allQuartersGrades
                                            if (!$foundComponent && $component && isset($allQuartersGrades[$termQuarter][$component->id])) {
                                                $componentTransmutedGrade = isset($allQuartersGrades[$termQuarter][$component->id]->transmuted_grade) ?
                                                    $allQuartersGrades[$termQuarter][$component->id]->transmuted_grade : null;
                                                $foundComponent = true;
                                            }
                                        @endphp

                                        @if($foundComponent && $isApproved && $componentTransmutedGrade !== null)
                                            <span class="fw-bold">{{ $componentTransmutedGrade }}</span>
                                        @elseif($foundComponent && !$isApproved)
                                            <span class="text-muted">Pending</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                @endforeach
                                <td class="center">
                                    @php
                                        $finalComponentGrade = isset($finalComponentGrades[$component->id ?? 0]) ?
                                            $finalComponentGrades[$component->id ?? 0] : null;
                                    @endphp

                                    @if($finalComponentGrade)
                                        <span class="fw-bold">{{ $finalComponentGrade }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                @endforeach
                <tr class="table-secondary">
                    <td class="fw-bold">GENERAL AVERAGE</td>
                    @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $termQuarter)
                        <td class="center fw-bold">
                            @if(isset($quarterlyAverages[$termQuarter]))
                                {{ $quarterlyAverages[$termQuarter] }}
                            @else
                                -
                            @endif
                        </td>
                    @endforeach
                    <td class="center fw-bold">
                        @if(isset($overallFinalAverage))
                            {{ $overallFinalAverage }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Signature and Grading Scale -->
        <div class="row mt-3">
            <div class="col-half">
                <div class="section-title">PARENTS'/ GUARDIAN'S SIGNATURE</div>
                <table class="signature-table">
                    <tr>
                        <td>1<sup>st</sup> Quarter: _______________________</td>
                    </tr>
                    <tr>
                        <td>2<sup>nd</sup> Quarter: _______________________</td>
                    </tr>
                    <tr>
                        <td>3<sup>rd</sup> Quarter: _______________________</td>
                    </tr>
                    <tr>
                        <td>4<sup>th</sup> Quarter: _______________________</td>
                    </tr>
                </table>
            </div>
            <div class="col-half">
                <!-- Grading scale removed from print layout -->
            </div>
        </div>

        <!-- Adviser Signature -->
        <div class="adviser-signature mt-5 mb-3">
            <div class="signature-spacer"></div>
            <div class="signature-line"></div>
            <p class="fw-bold">{{ strtoupper($section->adviser->name ?? '') }}{{ $section->adviser->credentials ? ', '.$section->adviser->credentials : '' }}</p>
            <p class="text-muted">Class Adviser</p>
        </div>
    </div>
</body>
</html>
