@extends('layouts.app')

@section('content')
<div class="grade-slip-container">
    <div class="d-print-none mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="mb-0">Grade Slip - All Quarters</h2>
            <div>
                <button class="btn btn-primary me-2" onclick="window.print()" id="printGradeSlip">
                    <i class="fas fa-print me-2"></i> Print Grade Slip
                </button>
                <a href="{{ route('teacher.reports.generate-grade-slips', ['section_id' => $section->id, 'quarter' => $quarter, 'transmutation_table' => $transmutationTable ?? 1]) }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i> Back to Grade Slips
                </a>

            </div>
        </div>
    </div>

    <div class="grade-slip">
        @php
            $schoolName = isset($section) && $section->school ? $section->school->name : 'St. Anthony Parish School';
            $region = isset($section) && $section->school && $section->school->schoolDivision ? $section->school->schoolDivision->region : 'Region XI';
            $division = isset($section) && $section->school && $section->school->schoolDivision ? $section->school->schoolDivision->name : 'Division of Davao del Sur';
            $district = isset($section) && $section->school ? $section->school->district : '';
            $address = isset($section) && $section->school ? $section->school->address : '';
        @endphp

        <div class="header">
            <div class="row align-items-center mb-2">
                <div class="col-2 text-start">
                    <img src="{{ asset($section->school->logo_path) }}" alt="School Logo" class="img-fluid school-logo">
                </div>
                <div class="col-8 text-center">
                    <h6 class="mb-0 small text-muted">Department of Education - {{ $region }}</h6>
                    <p class="mb-0 small text-muted">{{ $division }}{{ !empty($district) ? ' - ' . $district : '' }}</p>
                    <h5 class="mb-0 fw-bold school-name">{{ strtoupper($schoolName) }}</h5>
                    <p class="mb-0 small text-muted">{{ $address }}</p>
                    <h5 class="text-center fw-bold mt-2 mb-0 grade-slip-title">GRADE SLIP</h5>
                    <p class="mb-0 text-center small">SY {{ $schoolYear }}</p>
                </div>
                <div class="col-2 text-end">
                    <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo" class="img-fluid school-logo">
                </div>
            </div>
        </div>

        <div class="student-info mb-2">
            <table class="table table-sm table-borderless mb-0">
                <tr>
                    <th width="10%" class="pt-1">Name:</th>
                    <td width="40%" class="pt-1 fw-bold">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>
                    <th width="15%" class="pt-1">Gr.&Section:</th>
                    <td width="35%" class="pt-1 fw-bold">{{ $section->grade_level }} {{ $section->name }}</td>
                </tr>
                <tr>
                    <th width="10%" class="pb-1">Student ID:</th>
                    <td width="40%" class="pb-1">{{ $student->student_id ?? 'Not available' }}</td>
                    <th width="15%" class="pb-1">Adviser:</th>
                    <td width="35%" class="pb-1">{{ $section->adviser->name ?? 'Not assigned' }}</td>
                </tr>
            </table>
        </div>

        <div class="grades-table mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th width="30%" class="align-middle text-uppercase">LEARNING AREAS</th>
                            <th width="12%" class="text-center text-uppercase">First<br>Quarter</th>
                            <th width="12%" class="text-center text-uppercase">Second<br>Quarter</th>
                            <th width="12%" class="text-center text-uppercase">Third<br>Quarter</th>
                            <th width="12%" class="text-center text-uppercase">Fourth<br>Quarter</th>
                            <th width="12%" class="text-center text-uppercase">FINAL<br>GRADE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp
                        @foreach($subjects as $subject)
                            @php
                                $isMAPEH = $subject->getIsMAPEHAttribute();
                                $hasComponents = $isMAPEH && $mapehComponents->filter(function($comp) use ($subject) {
                                    return $comp->parent_subject_id == $subject->id;
                                })->count() > 0;
                            @endphp
                            <tr>
                                <td class="{{ $hasComponents ? 'fw-bold' : '' }}">
                                    {{ $subject->name }}
                                </td>
                                @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $termQuarter)
                                    <td class="text-center">
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
                                <td class="text-center">
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
                                    $components = $mapehComponents->filter(function($comp) use ($subject) {
                                        return $comp->parent_subject_id == $subject->id;
                                    });

                                    // Always create standard components for MAPEH to ensure consistent display
                                    if ($isMAPEH) {
                                        // Get the actual components from the database
                                        $actualComponents = $mapehComponents->filter(function($comp) use ($subject) {
                                            return $comp->parent_subject_id == $subject->id;
                                        });

                                        // If no components found, create dummy ones
                                        if ($actualComponents->isEmpty()) {
                                            // Create a standard component collection for display purposes
                                            $components = collect([
                                                (object)['id' => 'music_' . $subject->id, 'name' => 'Music', 'parent_subject_id' => $subject->id],
                                                (object)['id' => 'arts_' . $subject->id, 'name' => 'Arts', 'parent_subject_id' => $subject->id],
                                                (object)['id' => 'pe_' . $subject->id, 'name' => 'Physical Education', 'parent_subject_id' => $subject->id],
                                                (object)['id' => 'health_' . $subject->id, 'name' => 'Health', 'parent_subject_id' => $subject->id]
                                            ]);
                                        } else {
                                            // Map existing components to standard ones
                                            $standardComponentObjects = collect([
                                                (object)['id' => 'music_' . $subject->id, 'name' => 'Music', 'parent_subject_id' => $subject->id],
                                                (object)['id' => 'arts_' . $subject->id, 'name' => 'Arts', 'parent_subject_id' => $subject->id],
                                                (object)['id' => 'pe_' . $subject->id, 'name' => 'Physical Education', 'parent_subject_id' => $subject->id],
                                                (object)['id' => 'health_' . $subject->id, 'name' => 'Health', 'parent_subject_id' => $subject->id]
                                            ]);

                                            // Map existing components to standard ones
                                            foreach ($actualComponents as $component) {
                                                $name = strtolower($component->name);
                                                if (strpos($name, 'music') !== false) {
                                                    $standardComponentObjects[0]->id = $component->id;
                                                    $standardComponentObjects[0]->name = $component->name;
                                                } elseif (strpos($name, 'art') !== false) {
                                                    $standardComponentObjects[1]->id = $component->id;
                                                    $standardComponentObjects[1]->name = $component->name;
                                                } elseif (strpos($name, 'physical') !== false || strpos($name, 'p.e') !== false || strpos($name, 'pe') !== false) {
                                                    $standardComponentObjects[2]->id = $component->id;
                                                    $standardComponentObjects[2]->name = $component->name;
                                                } elseif (strpos($name, 'health') !== false) {
                                                    $standardComponentObjects[3]->id = $component->id;
                                                    $standardComponentObjects[3]->name = $component->name;
                                                }
                                            }

                                            $components = $standardComponentObjects;
                                        }
                                    }
                                @endphp
                                @php
                                    // Define standard components for consistency
                                    $standardComponents = [
                                        'Music' => 'Music',
                                        'Arts' => 'Arts',
                                        'PE' => 'P.E.',
                                        'Health' => 'Health'
                                    ];

                                    // Map components to their standard names
                                    $existingComponentIds = [
                                        'Music' => $components[0]->id,
                                        'Arts' => $components[1]->id,
                                        'PE' => $components[2]->id,
                                        'Health' => $components[3]->id
                                    ];
                                @endphp

                                @foreach($standardComponents as $key => $displayName)
                                    @php
                                        $componentId = $existingComponentIds[$key] ?? null;
                                        $component = $componentId ? $mapehComponents->firstWhere('id', $componentId) : null;
                                    @endphp
                                    <tr class="mapeh-component-row">
                                            <td class="py-0 ps-4">
                                                {{ $displayName }}
                                            </td>
                                            @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $termQuarter)
                                                <td class="text-center py-0">
                                                    @php
                                                        $componentGrade = null;
                                                        $componentTransmutedGrade = null;
                                                        $foundComponent = false;
                                                        $parentApproved = isset($allQuartersApprovals[$termQuarter][$subject->id]) &&
                                                                        $allQuartersApprovals[$termQuarter][$subject->id]->is_approved;
                                                        $componentApproved = $component && isset($allQuartersApprovals[$termQuarter][$component->id]) &&
                                                                            $allQuartersApprovals[$termQuarter][$component->id]->is_approved;
                                                        $isApproved = $parentApproved || $componentApproved;

                                                        // Method 1: Try from component_grades in MAPEH subject
                                                        if ($component && isset($allQuartersGrades[$termQuarter][$subject->id]) &&
                                                            isset($allQuartersGrades[$termQuarter][$subject->id]->component_grades[$component->id])) {
                                                            $compGrade = $allQuartersGrades[$termQuarter][$subject->id]->component_grades[$component->id];
                                                            if (is_object($compGrade)) {
                                                                $componentGrade = $compGrade->quarterly_grade;
                                                                $componentTransmutedGrade = isset($compGrade->transmuted_grade) ?
                                                                    $compGrade->transmuted_grade : null;
                                                                $foundComponent = true;
                                                            }
                                                        }

                                                        // Method 2: Try direct lookup from allQuartersGrades
                                                        if (!$foundComponent && $component && isset($allQuartersGrades[$termQuarter][$component->id])) {
                                                            $componentGrade = $allQuartersGrades[$termQuarter][$component->id]->quarterly_grade;
                                                            $componentTransmutedGrade = isset($allQuartersGrades[$termQuarter][$component->id]->transmuted_grade) ?
                                                                $allQuartersGrades[$termQuarter][$component->id]->transmuted_grade : null;
                                                            $foundComponent = true;
                                                        }

                                                        // Method 3: Calculate directly from raw grades if still not found
                                                        if (!$foundComponent && $component) {
                                                            $componentGrades = \App\Models\Grade::where('student_id', $student->id)
                                                                ->where('subject_id', $component->id)
                                                                ->where('term', $termQuarter)
                                                                ->get();

                                                            if ($componentGrades->isNotEmpty()) {
                                                                // Get component's grade configuration
                                                                $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $component->id)->first();
                                                                if (!$gradeConfig) {
                                                                    // Use default percentages
                                                                    $wwPercentage = 25;
                                                                    $ptPercentage = 50;
                                                                    $qaPercentage = 25;
                                                                } else {
                                                                    $wwPercentage = $gradeConfig->written_work_percentage;
                                                                    $ptPercentage = $gradeConfig->performance_task_percentage;
                                                                    $qaPercentage = $gradeConfig->quarterly_assessment_percentage;
                                                                }

                                                                // Group grades by type
                                                                $writtenWorks = $componentGrades->where('grade_type', 'written_work');
                                                                $performanceTasks = $componentGrades->where('grade_type', 'performance_task');
                                                                $quarterlyAssessments = $componentGrades->filter(function($grade) {
                                                                    return $grade->grade_type === 'quarterly_assessment' || $grade->grade_type === 'quarterly';
                                                                });

                                                                // Calculate component averages using total score / total max score formula
                                                                $wwTotal = 0;
                                                                $wwMaxTotal = 0;
                                                                foreach ($writtenWorks as $grade) {
                                                                    $wwTotal += $grade->score;
                                                                    $wwMaxTotal += $grade->max_score;
                                                                }
                                                                $wwPS = $wwMaxTotal > 0 ? ($wwTotal / $wwMaxTotal) * 100 : null;

                                                                $ptTotal = 0;
                                                                $ptMaxTotal = 0;
                                                                foreach ($performanceTasks as $grade) {
                                                                    $ptTotal += $grade->score;
                                                                    $ptMaxTotal += $grade->max_score;
                                                                }
                                                                $ptPS = $ptMaxTotal > 0 ? ($ptTotal / $ptMaxTotal) * 100 : null;

                                                                $qaTotal = 0;
                                                                $qaMaxTotal = 0;
                                                                foreach ($quarterlyAssessments as $grade) {
                                                                    $qaTotal += $grade->score;
                                                                    $qaMaxTotal += $grade->max_score;
                                                                }
                                                                $qaPS = $qaMaxTotal > 0 ? ($qaTotal / $qaMaxTotal) * 100 : null;

                                                                // Calculate weighted scores
                                                                $wwWS = $wwPS !== null ? ($wwPS * $wwPercentage) / 100 : 0;
                                                                $ptWS = $ptPS !== null ? ($ptPS * $ptPercentage) / 100 : 0;
                                                                $qaWS = $qaPS !== null ? ($qaPS * $qaPercentage) / 100 : 0;

                                                                // Calculate total weight for normalization if some components are missing
                                                                $totalWeight = 0;
                                                                if ($wwPS !== null) $totalWeight += $wwPercentage;
                                                                if ($ptPS !== null) $totalWeight += $ptPercentage;
                                                                if ($qaPS !== null) $totalWeight += $qaPercentage;

                                                                // Calculate initial grade (normalized if needed)
                                                                $initialGrade = $wwWS + $ptWS + $qaWS;
                                                                if ($totalWeight > 0 && $totalWeight < 100) {
                                                                    $initialGrade = ($initialGrade / $totalWeight) * 100;
                                                                }

                                                                // Round to 1 decimal place
                                                                $componentGrade = round($initialGrade, 1);

                                                                // Get transmuted grade using the system's helper
                                                                $componentTransmutedGrade = \App\Helpers\GradeHelper::getTransmutedGrade($componentGrade, $transmutationTable ?? 1);
                                                                $foundComponent = true;
                                                            }
                                                        }
                                                    @endphp

                                                    @if($foundComponent && $isApproved && $componentTransmutedGrade !== null)
                                                        <span class="fw-bold">{{ $componentTransmutedGrade }}</span>
                                                    @elseif($foundComponent && !$isApproved)
                                                        <span class="text-muted">Pending approval</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                            <td class="text-center">
                                                @php
                                                    $allComponentQuartersApproved = true;
                                                    $componentQuarterCount = 0;
                                                    $totalComponentGrade = 0;

                                                    foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $checkQuarter) {
                                                        $parentApproved = isset($allQuartersApprovals[$checkQuarter][$subject->id]) &&
                                                                        $allQuartersApprovals[$checkQuarter][$subject->id]->is_approved;
                                                        $componentApproved = $component && isset($allQuartersApprovals[$checkQuarter][$component->id]) &&
                                                                            $allQuartersApprovals[$checkQuarter][$component->id]->is_approved;
                                                        $quarterApproved = $parentApproved || $componentApproved;

                                                        // Check if we have a grade for this component in this quarter
                                                        $hasGradeThisQuarter = false;
                                                        $quarterGrade = null;

                                                        // Try from component_grades in MAPEH subject
                                                        if ($component && isset($allQuartersGrades[$checkQuarter][$subject->id]) &&
                                                            isset($allQuartersGrades[$checkQuarter][$subject->id]->component_grades[$component->id])) {
                                                            $compGrade = $allQuartersGrades[$checkQuarter][$subject->id]->component_grades[$component->id];
                                                            if (is_object($compGrade) && isset($compGrade->transmuted_grade)) {
                                                                $hasGradeThisQuarter = true;
                                                                $quarterGrade = $compGrade->transmuted_grade;
                                                            }
                                                        }

                                                        // Try direct lookup from allQuartersGrades
                                                        if (!$hasGradeThisQuarter && $component && isset($allQuartersGrades[$checkQuarter][$component->id]) &&
                                                            isset($allQuartersGrades[$checkQuarter][$component->id]->transmuted_grade)) {
                                                            $hasGradeThisQuarter = true;
                                                            $quarterGrade = $allQuartersGrades[$checkQuarter][$component->id]->transmuted_grade;
                                                        }

                                                        if ($hasGradeThisQuarter) {
                                                            if (!$quarterApproved) {
                                                                $allComponentQuartersApproved = false;
                                                                break;
                                                            } else {
                                                                $totalComponentGrade += $quarterGrade;
                                                                $componentQuarterCount++;
                                                            }
                                                        }
                                                    }

                                                    // Calculate final component grade
                                                    $finalComponentGrade = $componentQuarterCount > 0 ? round($totalComponentGrade / $componentQuarterCount) : null;
                                                @endphp

                                                @if($finalComponentGrade && $allComponentQuartersApproved)
                                                    <span class="fw-bold">{{ $finalComponentGrade }}</span>
                                                @elseif(!$allComponentQuartersApproved)
                                                    <span class="text-muted">Pending approval</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                @endforeach
                            @endif
                        @endforeach
                        <tr class="table-secondary">
                            <td class="fw-bold text-uppercase">GENERAL AVERAGE</td>
                            @foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $termQuarter)
                                <td class="text-center fw-bold">
                                    @php
                                        $hasUnapprovedGrades = false;
                                        foreach($subjects as $checkSubject) {
                                            if ($checkSubject->mapeh_component) continue;

                                            if (isset($allQuartersGrades[$termQuarter][$checkSubject->id]) &&
                                                (!isset($allQuartersApprovals[$termQuarter][$checkSubject->id]) ||
                                                !$allQuartersApprovals[$termQuarter][$checkSubject->id]->is_approved)) {
                                                $hasUnapprovedGrades = true;
                                                break;
                                            }
                                        }
                                    @endphp
                                    @if(isset($quarterlyAverages[$termQuarter]) && !$hasUnapprovedGrades)
                                        {{ $quarterlyAverages[$termQuarter] }}
                                    @elseif($hasUnapprovedGrades)
                                        <span class="text-muted small">Pending</span>
                                    @else
                                        -
                                    @endif
                                </td>
                            @endforeach
                            <td class="text-center fw-bold">
                                @php
                                    $hasAnyUnapprovedGrades = false;
                                    foreach(['Q1', 'Q2', 'Q3', 'Q4'] as $checkQuarter) {
                                        foreach($subjects as $checkSubject) {
                                            if ($checkSubject->mapeh_component) continue;

                                            if (isset($allQuartersGrades[$checkQuarter][$checkSubject->id]) &&
                                                (!isset($allQuartersApprovals[$checkQuarter][$checkSubject->id]) ||
                                                !$allQuartersApprovals[$checkQuarter][$checkSubject->id]->is_approved)) {
                                                $hasAnyUnapprovedGrades = true;
                                                break 2;
                                            }
                                        }
                                    }
                                @endphp
                                @if(isset($overallFinalAverage) && !$hasAnyUnapprovedGrades)
                                    {{ $overallFinalAverage }}
                                @elseif($hasAnyUnapprovedGrades)
                                    <span class="text-muted small">Pending</span>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="signature-section">
                    <h6 class="fw-bold text-uppercase mb-2 section-title">PARENTS'/ GUARDIAN'S SIGNATURE</h6>
                    <table class="table table-bordered">
                        <tr>
                            <td class="py-2">1<sup>st</sup> Quarter: _______________________</td>
                        </tr>
                        <tr>
                            <td class="py-2">2<sup>nd</sup> Quarter: _______________________</td>
                        </tr>
                        <tr>
                            <td class="py-2">3<sup>rd</sup> Quarter: _______________________</td>
                        </tr>
                        <tr>
                            <td class="py-2">4<sup>th</sup> Quarter: _______________________</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="col-md-6">
                <div class="grading-scale">
                    <h6 class="fw-bold text-uppercase mb-2 section-title">Grading Scale</h6>
                    <table class="table table-bordered">
                        <tr>
                            <td>
                                <strong>With Highest Honors</strong><br>
                                <em class="text-muted">May Pinakamataas na Karangalan</em>
                            </td>
                            <td width="30%" class="text-center align-middle">98-100</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>With High Honors</strong><br>
                                <em class="text-muted">May Mataas na Karangalan</em>
                            </td>
                            <td class="text-center align-middle">95-97</td>
                        </tr>
                        <tr>
                            <td>
                                <strong>With Honors</strong><br>
                                <em class="text-muted">May Karangalan</em>
                            </td>
                            <td class="text-center align-middle">90-94</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="text-center mt-5 mb-3">
            <div class="adviser-signature">
                <div class="signature-line"></div>
                <p class="mb-0 fw-bold">{{ strtoupper($section->adviser->name ?? '') }}{{ $section->adviser->credentials ? ', '.$section->adviser->credentials : '' }}</p>
                <p class="text-muted">Class Adviser</p>
            </div>
        </div>
    </div>
</div>

<style>
    .grade-slip-container {
        max-width: 1100px;
        margin: 0 auto;
    }

    .grade-slip {
        background-color: white;
        padding: 1.5rem;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        border: 1px solid #dee2e6;
    }

    .school-logo {
        max-height: 70px;
    }

    .school-name {
        font-size: 1.3rem;
        letter-spacing: 0.5px;
    }

    .grade-slip-title {
        letter-spacing: 2px;
        font-size: 1.4rem;
        margin-top: 0.5rem;
    }

    .student-info {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 0.5rem;
        margin-bottom: 1rem;
        border: 1px solid #e9ecef;
    }

    .grades-table .table {
        border: 2px solid #dee2e6;
    }

    .grades-table .thead-light th {
        background-color: #e9ecef;
        color: #495057;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .grades-table tbody tr:nth-of-type(even) {
        background-color: rgba(0,0,0,0.02);
    }

    .section-title {
        border-bottom: 2px solid #dee2e6;
        padding-bottom: 0.5rem;
        color: #495057;
    }

    .signature-line {
        border-top: 1px solid #000;
        width: 60%;
        margin: 0.5rem auto;
    }

    .mapeh-component-row {
        background-color: #f8f9fa;
    }

    .mapeh-component-row td {
        padding-top: 0.1rem !important;
        padding-bottom: 0.1rem !important;
        font-style: italic;
        color: #666;
    }

    .mapeh-component-row td:first-child {
        padding-left: 25px !important;
    }

    .table-secondary {
        background-color: #e2e3e5 !important;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        .grade-slip, .grade-slip * {
            visibility: visible;
        }

        .grade-slip {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            border: none;
            box-shadow: none;
            padding: 0.5cm;
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

        .school-logo {
            max-height: 1.5cm;
        }

        .school-name {
            font-size: 14pt;
        }

        .grade-slip-title {
            font-size: 16pt;
            margin-top: 0.2cm;
        }

        .student-info {
            margin-bottom: 0.3cm;
            padding: 0.2cm;
        }

        .grades-table {
            margin-bottom: 0.3cm;
        }

        .table {
            font-size: 9pt;
            margin-bottom: 0.2cm;
        }

        .signature-line {
            margin: 0.3cm auto;
        }
    }
</style>
@endsection
