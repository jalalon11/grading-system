@extends('layouts.app')

@section('content')
<div class="grade-slip-container">
    <div class="d-print-none mb-3">
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
                    <img src="{{ $section->school->logo_url }}" alt="School Logo" class="img-fluid school-logo">
                </div>
                <div class="col-8 text-center">
                    <h6 class="mb-0 small text-muted">Department of Education - {{ $region }}</h6>
                    <p class="mb-0 small text-muted">{{ $division }}{{ !empty($district) ? ' - ' . $district : '' }}</p>
                    <h5 class="mb-0 fw-bold school-name">{{ strtoupper($schoolName) }}</h5>
                    <p class="mb-0 small text-muted">{{ $address }}</p>
                    <h5 class="text-center fw-bold mt-2 mb-0 grade-slip-title">GRADE SLIP</h5>
                    <p class="mb-0 text-center small">{{ $quarterName }} | SY {{ $schoolYear }}</p>
                </div>
                <div class="col-2 text-end">
                    <img src="{{ asset('images/logo.jpg') }}" alt="DepEd Logo" class="img-fluid school-logo">
                </div>
            </div>
        </div>

        <div class="student-info mb-2">
            <div class="row gx-2">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th width="30%" class="pt-1">Student Name:</th>
                            <td class="pt-1 fw-bold">{{ $student->last_name }}, {{ $student->first_name }} {{ $student->middle_name }}</td>
                        </tr>
                        <tr>
                            <th class="pt-1">Student ID:</th>
                            <td class="pt-1">{{ $student->student_id }}</td>
                        </tr>
                        <tr>
                            <th class="pb-1">Gender:</th>
                            <td class="pb-1">{{ $student->gender }}</td>
                        </tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless mb-0">
                        <tr>
                            <th width="30%" class="pt-1">Grade Level:</th>
                            <td class="pt-1 fw-bold">{{ $section->grade_level }}</td>
                        </tr>
                        <tr>
                            <th class="pt-1">Section:</th>
                            <td class="pt-1 fw-bold">{{ $section->name }}</td>
                        </tr>
                        <tr>
                            <th class="pb-1">Adviser:</th>
                            <td class="pb-1">{{ $section->adviser->name ?? 'Not assigned' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="grades-table mb-3">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th width="3%" class="text-uppercase">#</th>
                            <th width="42%" class="text-uppercase">Subject</th>
                            <th width="15%" class="text-center text-uppercase">Quarterly Grade</th>
                            <th width="15%" class="text-center text-uppercase">Remarks</th>
                            <th width="25%" class="text-uppercase">Subject Teacher</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $index = 1; @endphp
                        @foreach($subjects as $subject)
                            @php
                                $hasGrade = isset($studentGrades[$subject->id]);
                                $isApproved = isset($extendedApprovals[$subject->id]) && $extendedApprovals[$subject->id]->is_approved;
                                $initialGrade = $hasGrade ? $studentGrades[$subject->id]->quarterly_grade : null;
                                $transmutedGrade = $hasGrade && isset($studentGrades[$subject->id]->transmuted_grade) ? $studentGrades[$subject->id]->transmuted_grade : null;
                                $remarks = $hasGrade ? $studentGrades[$subject->id]->remarks : 'No Grade';

                                // Get the subject teacher
                                $subjectTeacher = $subject->teachers->first();
                                $teacherName = $subjectTeacher ? $subjectTeacher->name : 'Not assigned';
                            @endphp
                            <tr>
                                <td>{{ $index++ }}</td>
                                <td class="{{ $subject->is_mapeh ? 'fw-bold' : '' }}">
                                    {{ $subject->name }}
                                    @if($subject->is_mapeh && isset($debug) && $debug)
                                        @php
                                            // Define standard components for debugging
                                            $standardComponents = [
                                                'Music' => 'Music',
                                                'Arts' => 'Arts',
                                                'PE' => 'P.E.',
                                                'Health' => 'Health'
                                            ];

                                            // Map existing components to their standard names
                                            $existingComponentIds = [];
                                            $components = $subject->components;

                                            foreach($components as $component) {
                                                if (strpos(strtolower($component->name), 'music') !== false) {
                                                    $existingComponentIds['Music'] = $component->id;
                                                } elseif (strpos(strtolower($component->name), 'art') !== false) {
                                                    $existingComponentIds['Arts'] = $component->id;
                                                } elseif (strpos(strtolower($component->name), 'physical') !== false ||
                                                       strpos(strtolower($component->name), 'p.e') !== false ||
                                                       strpos(strtolower($component->name), 'pe') !== false) {
                                                    $existingComponentIds['PE'] = $component->id;
                                                } elseif (strpos(strtolower($component->name), 'health') !== false) {
                                                    $existingComponentIds['Health'] = $component->id;
                                                }
                                            }
                                        @endphp
                                        <div class="mt-2 debugging-info">
                                            <div class="alert alert-info p-2 mb-0">
                                                <small><strong>MAPEH Debug Info:</strong></small>
                                                <div class="small">
                                                    <div><strong>MAPEH Subject ID:</strong> {{ $subject->id }}</div>
                                                    <div><strong>MAPEH Grade:</strong> {{ $hasGrade ? $studentGrades[$subject->id]->quarterly_grade : 'N/A' }}</div>
                                                    <div><strong>Transmuted Grade:</strong> {{ $hasGrade && isset($studentGrades[$subject->id]->transmuted_grade) ? $studentGrades[$subject->id]->transmuted_grade : 'N/A' }}</div>
                                                    <div><strong>Is Approved:</strong> {{ $isApproved ? 'Yes' : 'No' }}</div>
                                                    <div><strong>Components Found:</strong> {{ $components->count() }}</div>

                                                    <hr class="my-1">
                                                    <div><strong>Component ID Mapping:</strong></div>
                                                    <ul class="ps-3 mb-0">
                                                        @foreach($standardComponents as $key => $displayName)
                                                            <li>{{ $displayName }}: {{ isset($existingComponentIds[$key]) ? $existingComponentIds[$key] : 'Not mapped' }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($subject->is_mapeh)
                                        @php
                                            // Calculate MAPEH grade directly from component grades
                                            $mapehGrade = null;
                                            $mapehTransmutedGrade = null;
                                            $mapehRemarks = null;
                                            $hasComponentGrades = false;

                                            if (!isset($standardComponents)) {
                                                $standardComponents = [
                                                    'Music' => 'Music',
                                                    'Arts' => 'Arts',
                                                    'PE' => 'P.E.',
                                                    'Health' => 'Health'
                                                ];

                                                if (!isset($existingComponentIds)) {
                                                    $components = $subject->components;
                                                    $existingComponentIds = [];

                                                    foreach($components as $component) {
                                                        if (strpos(strtolower($component->name), 'music') !== false) {
                                                            $existingComponentIds['Music'] = $component->id;
                                                        } elseif (strpos(strtolower($component->name), 'art') !== false) {
                                                            $existingComponentIds['Arts'] = $component->id;
                                                        } elseif (strpos(strtolower($component->name), 'physical') !== false ||
                                                              strpos(strtolower($component->name), 'p.e') !== false ||
                                                              strpos(strtolower($component->name), 'pe') !== false) {
                                                            $existingComponentIds['PE'] = $component->id;
                                                        } elseif (strpos(strtolower($component->name), 'health') !== false) {
                                                            $existingComponentIds['Health'] = $component->id;
                                                        }
                                                    }
                                                }
                                            }

                                            // Calculate weighted average from components
                                            $totalWeightedGrade = 0;
                                            $totalWeight = 0;
                                            $componentCount = 0;

                                            foreach ($standardComponents as $key => $displayName) {
                                                $componentId = $existingComponentIds[$key] ?? null;

                                                if ($componentId) {
                                                    $componentGrades = \App\Models\Grade::where('student_id', $student->id)
                                                        ->where('subject_id', $componentId)
                                                        ->where('term', $quarter)
                                                        ->get();

                                                    if ($componentGrades->isNotEmpty()) {
                                                        // Get component's grade configuration
                                                        $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $componentId)->first();
                                                        if (!$gradeConfig) {
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

                                                        // Calculate component averages
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

                                                        // Calculate total weight for normalization
                                                        $compTotalWeight = 0;
                                                        if ($wwPS !== null) $compTotalWeight += $wwPercentage;
                                                        if ($ptPS !== null) $compTotalWeight += $ptPercentage;
                                                        if ($qaPS !== null) $compTotalWeight += $qaPercentage;

                                                        // Calculate component grade
                                                        $initialGrade = $wwWS + $ptWS + $qaWS;
                                                        if ($compTotalWeight > 0 && $compTotalWeight < 100) {
                                                            $initialGrade = ($initialGrade / $compTotalWeight) * 100;
                                                        }

                                                        $componentGrade = round($initialGrade, 1);
                                                        $componentWeight = $component->component_weight ?? 25; // Default to equal weights

                                                        // Add to weighted total for MAPEH average
                                                        $totalWeightedGrade += ($componentGrade * $componentWeight);
                                                        $totalWeight += $componentWeight;
                                                        $componentCount++;
                                                        $hasComponentGrades = true;
                                                    }
                                                }
                                            }

                                            // Calculate MAPEH average if we have components
                                            if ($hasComponentGrades && $totalWeight > 0) {
                                                $mapehGrade = round($totalWeightedGrade / $totalWeight, 1);
                                                $mapehTransmutedGrade = \App\Helpers\GradeHelper::getTransmutedGrade($mapehGrade, $transmutationTable ?? 1);
                                                $mapehRemarks = $mapehGrade >= 75 ? 'Passed' : 'Failed';
                                            }
                                        @endphp

                                        @if($hasComponentGrades && $isApproved && $mapehTransmutedGrade !== null)
                                            <span class="fw-bold">{{ $mapehTransmutedGrade }}</span>
                                        @elseif($hasComponentGrades && !$isApproved)
                                            <span class="text-muted">Pending approval</span>
                                        @elseif($hasGrade && $isApproved && $transmutedGrade !== null)
                                            <span class="fw-bold">{{ $transmutedGrade }}</span>
                                        @elseif($hasGrade && !$isApproved)
                                            <span class="text-muted">Pending approval</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @else
                                        @if($hasGrade && $isApproved && $transmutedGrade !== null)
                                            <span class="fw-bold">{{ $transmutedGrade }}</span>
                                        @elseif($hasGrade && !$isApproved)
                                            <span class="text-muted">Pending approval</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($subject->is_mapeh && $hasComponentGrades && $isApproved)
                                        <span class="{{ $mapehRemarks == 'Passed' ? 'text-success fw-bold' : 'text-danger fw-bold' }}">
                                            {{ $mapehRemarks }}
                                        </span>
                                    @elseif($hasGrade && $isApproved)
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
                                @php
                                    // If not already defined in debug section, define them here
                                    if (!isset($standardComponents)) {
                                        $standardComponents = [
                                            'Music' => 'Music',
                                            'Arts' => 'Arts',
                                            'PE' => 'P.E.',
                                            'Health' => 'Health'
                                        ];
                                    }

                                    if (!isset($existingComponentIds)) {
                                        $components = $subject->components;

                                        // Map existing components to their standard names
                                        $existingComponentIds = [];
                                        foreach($components as $component) {
                                            if (strpos(strtolower($component->name), 'music') !== false) {
                                                $existingComponentIds['Music'] = $component->id;
                                            } elseif (strpos(strtolower($component->name), 'art') !== false) {
                                                $existingComponentIds['Arts'] = $component->id;
                                            } elseif (strpos(strtolower($component->name), 'physical') !== false ||
                                                   strpos(strtolower($component->name), 'p.e') !== false ||
                                                   strpos(strtolower($component->name), 'pe') !== false) {
                                                $existingComponentIds['PE'] = $component->id;
                                            } elseif (strpos(strtolower($component->name), 'health') !== false) {
                                                $existingComponentIds['Health'] = $component->id;
                                            }
                                        }
                                    }
                                @endphp

                                @foreach($standardComponents as $key => $displayName)
                                    @php
                                        $componentGrade = null;
                                        $componentTransmutedGrade = null;
                                        $componentId = $existingComponentIds[$key] ?? null;
                                        $foundComponent = false;
                                        $remarks = null;

                                        // Direct lookup for component ID (calculate directly from raw grades - same as class record)
                                        if ($componentId) {
                                            $componentGrades = \App\Models\Grade::where('student_id', $student->id)
                                                ->where('subject_id', $componentId)
                                                ->where('term', $quarter)
                                                ->get();

                                            if ($componentGrades->isNotEmpty()) {
                                                // Get component's grade configuration
                                                $gradeConfig = \App\Models\GradeConfiguration::where('subject_id', $componentId)->first();
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

                                                // Group grades by type - same logic as class record
                                                $writtenWorks = $componentGrades->where('grade_type', 'written_work');
                                                $performanceTasks = $componentGrades->where('grade_type', 'performance_task');
                                                $quarterlyAssessments = $componentGrades->filter(function($grade) {
                                                    return $grade->grade_type === 'quarterly_assessment' || $grade->grade_type === 'quarterly';
                                                });

                                                // Calculate component averages - exactly as in class record
                                                // Calculate PS (Percentage Score) for each component using total score / total max score formula
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

                                                // Set remarks
                                                $remarks = $componentGrade >= 75 ? 'Passed' : 'Failed';

                                                $foundComponent = true;
                                            }
                                        }

                                        // If still not found by direct calculation, try other methods

                                        // Try from studentGrades directly
                                        if (!$foundComponent && $componentId && isset($studentGrades[$componentId])) {
                                            $componentGrade = $studentGrades[$componentId]->quarterly_grade;
                                            $componentTransmutedGrade = isset($studentGrades[$componentId]->transmuted_grade) ?
                                                $studentGrades[$componentId]->transmuted_grade : null;
                                            $remarks = $componentGrade >= 75 ? 'Passed' : 'Failed';
                                            $foundComponent = true;
                                        }

                                        // If not found directly, try from the MAPEH component_grades
                                        if (!$foundComponent && $componentId && $hasGrade && isset($studentGrades[$subject->id]->component_grades) &&
                                            is_array($studentGrades[$subject->id]->component_grades) &&
                                            isset($studentGrades[$subject->id]->component_grades[$componentId])) {
                                            $compGrade = $studentGrades[$subject->id]->component_grades[$componentId];
                                            if (is_object($compGrade)) {
                                                $componentGrade = $compGrade->quarterly_grade;
                                                $componentTransmutedGrade = isset($compGrade->transmuted_grade) ?
                                                    $compGrade->transmuted_grade : null;
                                                $remarks = $componentGrade >= 75 ? 'Passed' : 'Failed';
                                                $foundComponent = true;
                                            }
                                        }

                                        // If still not found, use the MAPEH grade as a fallback
                                        if (!$foundComponent && $hasGrade && isset($studentGrades[$subject->id]->quarterly_grade)) {
                                            // Use the MAPEH grade for all components if we can't find individual ones
                                            $componentGrade = $studentGrades[$subject->id]->quarterly_grade;
                                            $componentTransmutedGrade = isset($studentGrades[$subject->id]->transmuted_grade) ?
                                                $studentGrades[$subject->id]->transmuted_grade : null;
                                            $remarks = $componentGrade >= 75 ? 'Passed' : 'Failed';
                                            $foundComponent = true;
                                        }
                                    @endphp
                                    <tr class="mapeh-component-row">
                                        <td></td>
                                        <td class="ps-4">{{ $displayName }}</td>
                                        <td class="text-center">
                                            @if($foundComponent)
                                                @if($isApproved)
                                                    <span class="fw-bold">{{ $componentTransmutedGrade ?? $componentGrade }}</span>
                                                @else
                                                    <span class="text-muted">Pending</span>
                                                @endif
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($foundComponent)
                                                @if($isApproved)
                                                    <span class="{{ $remarks == 'Passed' ? 'text-success' : 'text-danger' }}">
                                                        {{ $remarks }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">Pending</span>
                                                @endif
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
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-6">
                <div class="summary-section mb-3">
                    <h6 class="fw-bold text-uppercase mb-2 section-title">Grade Summary</h6>
                    @php
                        // Calculate general average
                        $totalGrade = 0;
                        $subjectCount = 0;
                        $pendingApprovals = false;
                        $totalSubjects = 0;
                        $approvedSubjects = 0;

                        // Debug information
                        $debugInfo = [];

                        // First, count all non-component subjects and check their approval status
                        foreach ($subjects as $subject) {
                            if (!$subject->mapeh_component) {
                                $totalSubjects++;

                                // Check if this subject has a grade and if it's approved
                                if (isset($studentGrades[$subject->id])) {
                                    $isApproved = isset($extendedApprovals[$subject->id]) && $extendedApprovals[$subject->id]->is_approved;

                                    $gradeToUse = isset($studentGrades[$subject->id]->transmuted_grade) ?
                                        $studentGrades[$subject->id]->transmuted_grade :
                                        $studentGrades[$subject->id]->quarterly_grade;

                                    $debugInfo[$subject->id] = [
                                        'name' => $subject->name,
                                        'quarterly_grade' => $studentGrades[$subject->id]->quarterly_grade,
                                        'transmuted_grade' => isset($studentGrades[$subject->id]->transmuted_grade) ?
                                            $studentGrades[$subject->id]->transmuted_grade : 'Not set',
                                        'grade_used_for_average' => $gradeToUse,
                                        'approved' => $isApproved
                                    ];

                                    if ($isApproved) {
                                        // Only include approved grades in the average
                                        // Use transmuted grade if available, otherwise use quarterly grade
                                        $gradeToUse = isset($studentGrades[$subject->id]->transmuted_grade) ?
                                            $studentGrades[$subject->id]->transmuted_grade :
                                            $studentGrades[$subject->id]->quarterly_grade;
                                        $totalGrade += $gradeToUse;
                                        $subjectCount++;
                                        $approvedSubjects++;
                                    } else {
                                        $pendingApprovals = true;
                                    }
                                } else {
                                    // Subject has no grade data - consider it pending
                                    $pendingApprovals = true;
                                    $debugInfo[$subject->id] = [
                                        'name' => $subject->name,
                                        'quarterly_grade' => 'No data',
                                        'transmuted_grade' => 'No data',
                                        'grade_used_for_average' => 'No data',
                                        'approved' => false
                                    ];
                                }
                            }
                        }

                        $generalAverage = $subjectCount > 0 ? round($totalGrade / $subjectCount) : 0;
                        $allApproved = ($approvedSubjects == $totalSubjects) && ($totalSubjects > 0);
                    @endphp

                    <table class="table table-bordered">
                        <tr>
                            <th width="60%" class="py-2">General Average:</th>
                            <td class="text-center py-2">
                                @if($allApproved)
                                    <span class="fw-bold">{{ $generalAverage }}</span>
                                @else
                                    <span class="text-muted">Grades Unavailable - awaiting for approval</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="py-2">Final Remarks:</th>
                            <td class="text-center py-2">
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
            </div>
            <div class="col-md-6">
                <div class="academic-recognition mb-3">
                    <h6 class="fw-bold text-uppercase mb-2 section-title">Academic Recognition</h6>
                    <table class="table table-bordered">
                        <tr>
                            <th width="40%" class="py-2">Recognition:</th>
                            <td class="py-2">
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
                        <tr>
                            <th class="py-2">Grading Scale:</th>
                            <td class="py-2">
                                <span class="small d-block">With Highest Honors: 98-100</span>
                                <span class="small d-block">With High Honors: 95-97</span>
                                <span class="small d-block">With Honors: 90-94</span>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <div class="signature-section mt-4">
            <div class="row justify-content-center">
                <div class="col-md-4 text-center">
                    <div class="signature-line"></div>
                    <p class="mb-0 fw-bold">{{ strtoupper($section->adviser->name ?? '') }}</p>
                    <p class="mb-0 text-muted">Class Adviser</p>
                </div>
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
        padding-left: 0 !important;
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
