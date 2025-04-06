<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Grade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificateController extends Controller
{
    /**
     * Display the certificates dashboard
     */
    public function index()
    {
        $teacher = Auth::user();

        // Get ONLY sections where the teacher is the adviser
        $sections = Section::where('adviser_id', $teacher->id)->get();

        // Log for debugging
        Log::info('Sections for certificates (adviser only)', [
            'teacher_id' => $teacher->id,
            'adviser_sections' => $sections->pluck('name')->toArray(),
            'count' => $sections->count()
        ]);

        return view('teacher.reports.certificates.index', compact('sections'));
    }

    /**
     * Generate certificates for students in a section
     */
    public function generate(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
        ]);

        $teacher = Auth::user();
        $section = Section::findOrFail($validated['section_id']);

        // Check if teacher is the adviser of this section
        $isAdviser = $section->adviser_id == $teacher->id;

        Log::info('Teacher authorization check for certificates', [
            'teacher_id' => $teacher->id,
            'section_id' => $section->id,
            'is_adviser' => $isAdviser
        ]);

        if (!$isAdviser) {
            return back()->with('error', 'You are not authorized to access this section. Only the adviser can generate certificates.');
        }

        // Get all students in the section
        $students = Student::where('section_id', $section->id)
            ->orderBy('gender')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Get all subjects for this section
        $subjects = $section->subjects()->with('components')->get();

        // Identify MAPEH subjects
        $mapehSubjects = $subjects->filter(function($subject) {
            return $subject->getIsMAPEHAttribute();
        });

        // Log basic information
        Log::info('Certificate generation basic info', [
            'section_id' => $section->id,
            'section_name' => $section->name,
            'quarter' => $validated['quarter'],
            'student_count' => $students->count(),
            'subject_count' => $subjects->count(),
            'mapeh_subject_count' => $mapehSubjects->count(),
            'mapeh_subjects' => $mapehSubjects->pluck('name', 'id')->toArray()
        ]);

        // Initialize student averages array
        $studentAverages = [];

        // Process each student individually
        foreach ($students as $student) {
            // Get all subjects with grades for this student
            $studentSubjects = [];
            $totalGrade = 0;
            $subjectCount = 0;
            $allPassed = true;
            $hasMapeh = false; // Flag to check if student has MAPEH grade

            // Process each subject for this student
            foreach ($subjects as $subject) {
                // Check if this is a MAPEH subject
                $isMAPEH = $subject->getIsMAPEHAttribute();

                if ($isMAPEH) {
                    // For MAPEH subjects, we need to check if any of the components have grades
                    $hasComponentGrades = false;
                    $mapehComponents = $subject->components;
                    $mapehComponentGrades = [];
                    $totalComponentGrade = 0;
                    $totalWeight = 0;
                    $componentCount = 0;

                    foreach ($mapehComponents as $component) {
                        $componentGrades = Grade::where('student_id', $student->id)
                            ->where('subject_id', $component->id)
                            ->where('term', $validated['quarter'])
                            ->get();

                        if (!$componentGrades->isEmpty()) {
                            $hasComponentGrades = true;

                            // Calculate component grade
                            $componentGrade = $this->calculateQuarterlyGrade($componentGrades, $component);
                            if ($componentGrade !== null) {
                                $mapehComponentGrades[$component->id] = [
                                    'subject' => $component,
                                    'quarterly_grade' => $componentGrade
                                ];

                                // Use component weight if available, otherwise default to equal weights
                                $componentWeight = $component->component_weight ?? 25;
                                // Add to total for MAPEH average calculation
                                $totalComponentGrade += ($componentGrade * $componentWeight);
                                $totalWeight += $componentWeight;
                                $componentCount++;
                            }
                        }
                    }

                    if ($hasComponentGrades && $componentCount > 0) {
                        $hasMapeh = true;

                        // Calculate MAPEH average grade using weighted average
                        $mapehAverageGrade = round($totalComponentGrade / $totalWeight, 1);

                        // Store the MAPEH average grade
                        $studentSubjects[$subject->id] = [
                            'subject' => $subject,
                            'quarterly_grade' => $mapehAverageGrade,
                            'is_mapeh_parent' => true,
                            'component_count' => $componentCount
                        ];

                        // MAPEH components found with grades

                        // Store component grades for later use
                        $student->mapeh_component_grades = $mapehComponentGrades;

                        // Add MAPEH grade to total
                        $totalGrade += $mapehAverageGrade;
                        $subjectCount++;

                        // Check if student passed MAPEH
                        if ($mapehAverageGrade < 75) {
                            $allPassed = false;
                        }
                    } else {
                        // MAPEH subject has no component grades
                        continue; // Skip MAPEH subjects with no component grades
                    }

                    // Skip the rest of the loop since we've already processed this MAPEH subject
                    continue;
                }

                // Get grades for this subject
                $grades = Grade::where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->where('term', $validated['quarter'])
                    ->get();

                if ($grades->isEmpty() && !$isMAPEH) {
                    // Skip non-MAPEH subjects with no grades
                    continue;
                }

                // Calculate quarterly grade for this subject
                $quarterlyGrade = $this->calculateQuarterlyGrade($grades, $subject);

                if ($quarterlyGrade === null) {
                    continue; // Skip if no quarterly grade could be calculated
                }

                // If we got here with a MAPEH subject, mark that we have MAPEH grades
                if ($isMAPEH) {
                    $hasMapeh = true;
                }

                // Add to student's subjects
                $studentSubjects[$subject->id] = [
                    'subject' => $subject,
                    'quarterly_grade' => $quarterlyGrade
                ];

                // Update totals
                $totalGrade += $quarterlyGrade;
                $subjectCount++;

                // Check if student passed this subject
                if ($quarterlyGrade < 75) {
                    $allPassed = false;
                }

                // Log subject grade
                Log::info('Subject grade calculated', [
                    'student_id' => $student->id,
                    'student_name' => $student->full_name,
                    'subject_id' => $subject->id,
                    'subject_name' => $subject->name,
                    'quarterly_grade' => $quarterlyGrade,
                    'passed' => ($quarterlyGrade >= 75)
                ]);
            }

            // Skip students with no grades
            if ($subjectCount === 0) {
                continue;
            }

            // Calculate overall average
            $overallAverage = $totalGrade / $subjectCount;
            $roundedAverage = round($overallAverage);

            // Add MAPEH component grades to student subjects if they exist
            if ($hasMapeh && isset($student->mapeh_component_grades)) {
                foreach ($student->mapeh_component_grades as $componentId => $componentData) {
                    $studentSubjects[$componentId] = $componentData;
                }
            }

            // Add to student averages
            $studentAverages[$student->id] = [
                'student' => $student,
                'average' => $roundedAverage,
                'allPassed' => $allPassed,
                'subjects' => $studentSubjects,
                'has_mapeh' => $hasMapeh // Add flag to indicate if student has MAPEH grade
            ];

            // Student average calculated
        }

        // Student averages for certificates calculated

        // Group students by award category
        $awards = [
            'highest_honors' => [], // 98-100
            'high_honors' => [],    // 95-97
            'honors' => []          // 90-94
        ];

        foreach ($studentAverages as $data) {
            $average = $data['average'];
            $allPassed = $data['allPassed'];
            $hasMapeh = $data['has_mapeh'] ?? false;

            // Check student's award eligibility

            // Only consider students who passed all subjects they have grades for
            // We're not requiring MAPEH anymore
            if ($allPassed) {
                // Check if the student has a qualifying average
                $isQualified = false;
                $awardCategory = '';

                if ($average >= 98 && $average <= 100) {
                    $isQualified = true;
                    $awardCategory = 'highest_honors';
                } elseif ($average >= 95 && $average <= 97) {
                    $isQualified = true;
                    $awardCategory = 'high_honors';
                } elseif ($average >= 90 && $average <= 94) {
                    $isQualified = true;
                    $awardCategory = 'honors';
                }

                // If the student qualifies, add them to the appropriate award category
                if ($isQualified) {
                    $awards[$awardCategory][] = $data;
                    // Student added to award category
                }
            }
        }

        // Log the final award categories
        Log::info('Final award categories', [
            'section_id' => $section->id,
            'quarter' => $validated['quarter'],
            'highest_honors_count' => count($awards['highest_honors']),
            'high_honors_count' => count($awards['high_honors']),
            'honors_count' => count($awards['honors']),
            'highest_honors' => collect($awards['highest_honors'])->map(function($data) {
                return ['name' => $data['student']->full_name, 'average' => $data['average']];
            })->toArray(),
            'high_honors' => collect($awards['high_honors'])->map(function($data) {
                return ['name' => $data['student']->full_name, 'average' => $data['average']];
            })->toArray(),
            'honors' => collect($awards['honors'])->map(function($data) {
                return ['name' => $data['student']->full_name, 'average' => $data['average']];
            })->toArray()
        ]);

        return view('teacher.reports.certificates.generate', [
            'section' => $section,
            'quarter' => $validated['quarter'],
            'awards' => $awards,
            'studentAverages' => $studentAverages
        ]);
    }

    /**
     * Preview a specific certificate
     */
    public function preview(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'section_id' => 'required|exists:sections,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
            'award_type' => 'required|in:highest_honors,high_honors,honors',
            'average' => 'required|numeric|min:75|max:100',
        ]);

        $teacher = Auth::user();
        $student = Student::findOrFail($validated['student_id']);
        $section = Section::findOrFail($validated['section_id']);

        // Check if teacher is the adviser of this section
        $isAdviser = $section->adviser_id == $teacher->id;

        Log::info('Teacher authorization check for certificate preview', [
            'teacher_id' => $teacher->id,
            'section_id' => $section->id,
            'is_adviser' => $isAdviser
        ]);

        if (!$isAdviser) {
            return back()->with('error', 'You are not authorized to access this section. Only the adviser can generate certificates.');
        }

        // Get award details
        $awardTitles = [
            'highest_honors' => 'With Highest Honors',
            'high_honors' => 'With High Honors',
            'honors' => 'With Honors'
        ];

        $awardRanges = [
            'highest_honors' => '98-100',
            'high_honors' => '95-97',
            'honors' => '90-94'
        ];

        $awardTagalog = [
            'highest_honors' => 'May Pinakamataas na Karangalan',
            'high_honors' => 'May Mataas na Karangalan',
            'honors' => 'May Karangalan'
        ];

        $award = [
            'title' => $awardTitles[$validated['award_type']],
            'range' => $awardRanges[$validated['award_type']],
            'tagalog' => $awardTagalog[$validated['award_type']],
            'average' => $validated['average']
        ];

        // Get school information
        $school = $section->school;

        return view('teacher.reports.certificates.preview', [
            'student' => $student,
            'section' => $section,
            'quarter' => $validated['quarter'],
            'award' => $award,
            'school' => $school,
            'teacher' => $teacher
        ]);
    }

    /**
     * Generate bulk certificates for all qualified students
     */
    public function generateBulk(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
        ]);

        $teacher = Auth::user();
        $section = Section::findOrFail($validated['section_id']);

        // Check if teacher is the adviser of this section
        $isAdviser = $section->adviser_id == $teacher->id;

        Log::info('Teacher authorization check for bulk certificate generation', [
            'teacher_id' => $teacher->id,
            'section_id' => $section->id,
            'is_adviser' => $isAdviser
        ]);

        if (!$isAdviser) {
            return back()->with('error', 'You are not authorized to access this section. Only the adviser can generate certificates.');
        }

        // Get award details
        $awardTitles = [
            'highest_honors' => 'With Highest Honors',
            'high_honors' => 'With High Honors',
            'honors' => 'With Honors'
        ];

        $awardRanges = [
            'highest_honors' => '98-100',
            'high_honors' => '95-97',
            'honors' => '90-94'
        ];

        $awardTagalog = [
            'highest_honors' => 'May Pinakamataas na Karangalan',
            'high_honors' => 'May Mataas na Karangalan',
            'honors' => 'May Karangalan'
        ];

        // Get school information
        $school = $section->school;

        // Get all students in the section with their averages
        $studentAverages = [];
        $awards = [
            'highest_honors' => [],
            'high_honors' => [],
            'honors' => []
        ];

        // Get all students in the section
        $students = Student::where('section_id', $section->id)
            ->orderBy('gender')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        // Get all subjects for this section
        $subjects = $section->subjects()->with('components')->get();

        // Identify MAPEH subjects
        $mapehSubjects = $subjects->filter(function($subject) {
            return $subject->getIsMAPEHAttribute();
        });

        // Process each student
        foreach ($students as $student) {
            $studentSubjects = [];
            $totalGrade = 0;
            $subjectCount = 0;
            $allPassed = true;
            $hasMapeh = false;

            // Process each subject
            foreach ($subjects as $subject) {
                // Check if this is a MAPEH subject
                $isMAPEH = $subject->getIsMAPEHAttribute();

                if ($isMAPEH) {
                    // For MAPEH subjects, we need to check if any of the components have grades
                    $hasComponentGrades = false;
                    $mapehComponents = $subject->components;
                    $mapehComponentGrades = [];
                    $totalComponentGrade = 0;
                    $totalWeight = 0;
                    $componentCount = 0;

                    foreach ($mapehComponents as $component) {
                        $componentGrades = Grade::where('student_id', $student->id)
                            ->where('subject_id', $component->id)
                            ->where('term', $validated['quarter'])
                            ->get();

                        if (!$componentGrades->isEmpty()) {
                            $hasComponentGrades = true;

                            // Calculate component grade
                            $componentGrade = $this->calculateQuarterlyGrade($componentGrades, $component);
                            if ($componentGrade !== null) {
                                $mapehComponentGrades[$component->id] = [
                                    'subject' => $component,
                                    'quarterly_grade' => $componentGrade
                                ];

                                // Use component weight if available, otherwise default to equal weights
                                $componentWeight = $component->component_weight ?? 25;
                                // Add to total for MAPEH average calculation
                                $totalComponentGrade += ($componentGrade * $componentWeight);
                                $totalWeight += $componentWeight;
                                $componentCount++;
                            }
                        }
                    }

                    if ($hasComponentGrades && $componentCount > 0) {
                        $hasMapeh = true;

                        // Calculate MAPEH average grade using weighted average
                        $mapehAverageGrade = round($totalComponentGrade / $totalWeight, 1);

                        // Store the MAPEH average grade
                        $studentSubjects[$subject->id] = [
                            'subject' => $subject,
                            'quarterly_grade' => $mapehAverageGrade,
                            'is_mapeh_parent' => true,
                            'component_count' => $componentCount
                        ];

                        // Add MAPEH grade to total
                        $totalGrade += $mapehAverageGrade;
                        $subjectCount++;

                        // Check if student passed MAPEH
                        if ($mapehAverageGrade < 75) {
                            $allPassed = false;
                        }
                    }

                    // Skip the rest of the loop since we've already processed this MAPEH subject
                    continue;
                }

                // For non-MAPEH subjects
                $grades = Grade::where('student_id', $student->id)
                    ->where('subject_id', $subject->id)
                    ->where('term', $validated['quarter'])
                    ->get();

                // Skip if no grades
                if ($grades->isEmpty()) {
                    continue;
                }

                // Calculate quarterly grade
                $quarterlyGrade = $this->calculateQuarterlyGrade($grades, $subject);

                // Skip if no quarterly grade
                if ($quarterlyGrade === null) {
                    continue;
                }

                // Add to student's subjects
                $studentSubjects[$subject->id] = [
                    'subject' => $subject,
                    'quarterly_grade' => $quarterlyGrade
                ];

                // Update totals
                $totalGrade += $quarterlyGrade;
                $subjectCount++;

                // Check if student passed this subject
                if ($quarterlyGrade < 75) {
                    $allPassed = false;
                }
            }

            // Skip students with no grades
            if ($subjectCount === 0) {
                continue;
            }

            // Calculate average
            $average = round($totalGrade / $subjectCount);

            // Store student average
            $studentAverages[$student->id] = $average;

            // Check if student qualifies for an award
            if ($allPassed) {
                if ($average >= 98 && $average <= 100) {
                    $awards['highest_honors'][] = [
                        'student' => $student,
                        'average' => $average
                    ];
                } elseif ($average >= 95 && $average <= 97) {
                    $awards['high_honors'][] = [
                        'student' => $student,
                        'average' => $average
                    ];
                } elseif ($average >= 90 && $average <= 94) {
                    $awards['honors'][] = [
                        'student' => $student,
                        'average' => $average
                    ];
                }
            }
        }

        // Prepare certificates data for all qualified students
        $certificates = [];

        // Process highest honors students
        foreach ($awards['highest_honors'] as $data) {
            $certificates[] = [
                'student' => $data['student'],
                'award' => [
                    'title' => $awardTitles['highest_honors'],
                    'range' => $awardRanges['highest_honors'],
                    'tagalog' => $awardTagalog['highest_honors'],
                    'average' => $data['average']
                ]
            ];
        }

        // Process high honors students
        foreach ($awards['high_honors'] as $data) {
            $certificates[] = [
                'student' => $data['student'],
                'award' => [
                    'title' => $awardTitles['high_honors'],
                    'range' => $awardRanges['high_honors'],
                    'tagalog' => $awardTagalog['high_honors'],
                    'average' => $data['average']
                ]
            ];
        }

        // Process honors students
        foreach ($awards['honors'] as $data) {
            $certificates[] = [
                'student' => $data['student'],
                'award' => [
                    'title' => $awardTitles['honors'],
                    'range' => $awardRanges['honors'],
                    'tagalog' => $awardTagalog['honors'],
                    'average' => $data['average']
                ]
            ];
        }

        // Prepare to return the view with certificates

        return view('teacher.reports.certificates.bulk-preview', [
            'certificates' => $certificates,
            'section' => $section,
            'quarter' => $validated['quarter'],
            'school' => $school,
            'teacher' => $teacher
        ]);
    }

    /**
     * Calculate the quarterly grade for a subject based on its grades
     *
     * @param \Illuminate\Support\Collection $grades
     * @param \App\Models\Subject $subject
     * @return int
     */
    private function calculateQuarterlyGrade($grades, $subject)
    {
        // Check if grades collection is empty
        if ($grades->isEmpty()) {
            return null;
        }

        // Check if this is a MAPEH subject
        if ($subject->getIsMAPEHAttribute()) {
            // For MAPEH subjects, we need to get the component subjects and calculate their average
            return $this->calculateMAPEHGrade($subject, $grades->first()->student_id, $grades->first()->term);
        }

        // For regular subjects, calculate the grade normally
        // Get grade configuration for this subject
        $config = $subject->gradeConfiguration;

        if (!$config) {
            // Use default configuration if not set
            $ww = 0.30; // Written work - 30%
            $pt = 0.50; // Performance task - 50%
            $qa = 0.20; // Quarterly assessment - 20%
        } else {
            $ww = $config->written_work_percentage / 100;
            $pt = $config->performance_task_percentage / 100;
            $qa = $config->quarterly_assessment_percentage / 100;
        }

        // Group grades by type
        $writtenWorks = $grades->where('grade_type', 'written_work');
        $performanceTasks = $grades->where('grade_type', 'performance_task');
        $quarterlyAssessments = $grades->where('grade_type', 'quarterly');

        // Calculate average for each component
        $wwAvg = $this->calculateComponentAverage($writtenWorks);
        $ptAvg = $this->calculateComponentAverage($performanceTasks);
        $qaAvg = $this->calculateComponentAverage($quarterlyAssessments);

        // Calculate weighted final grade
        $finalGrade = 0;
        $weightSum = 0;

        if ($wwAvg !== null) {
            $finalGrade += $wwAvg * $ww;
            $weightSum += $ww;
        }

        if ($ptAvg !== null) {
            $finalGrade += $ptAvg * $pt;
            $weightSum += $pt;
        }

        if ($qaAvg !== null) {
            $finalGrade += $qaAvg * $qa;
            $weightSum += $qa;
        }

        // Calculate initial grade (normalized if not all components are present)
        $initialGrade = $weightSum > 0 ? ($finalGrade / $weightSum) : null;

        if ($initialGrade === null) {
            return null;
        }

        // Transmute the grade according to DepEd rules
        return $this->transmutationTable1($initialGrade);
    }

    /**
     * Calculate the grade for a MAPEH subject by averaging its components
     *
     * @param \App\Models\Subject $mapehSubject
     * @param int $studentId
     * @param string $term
     * @return int|null
     */
    private function calculateMAPEHGrade($mapehSubject, $studentId, $term)
    {
        // Get the component subjects (Music, Arts, PE, Health)
        $components = $mapehSubject->components;

        if ($components->isEmpty()) {
            // If no components are defined, return null
            return null;
        }

        // Calculate the average of the component grades
        $totalGrade = 0;
        $componentCount = 0;

        foreach ($components as $component) {
            // Get grades for this component
            $componentGrades = Grade::where('student_id', $studentId)
                ->where('subject_id', $component->id)
                ->where('term', $term)
                ->get();

            if ($componentGrades->isEmpty()) {
                continue;
            }

            // Calculate the grade for this component
            $componentGrade = $this->calculateQuarterlyGrade($componentGrades, $component);

            if ($componentGrade !== null) {
                $totalGrade += $componentGrade;
                $componentCount++;
            }
        }

        // Return the average of the component grades
        return $componentCount > 0 ? round($totalGrade / $componentCount) : null;
    }

    /**
     * Calculate the average for a specific grade component
     * Uses total score divided by total max score method
     *
     * @param \Illuminate\Support\Collection $grades
     * @return float|null
     */
    private function calculateComponentAverage($grades)
    {
        if ($grades->isEmpty()) {
            return null;
        }

        $totalScore = 0;
        $totalMaxScore = 0;

        foreach ($grades as $grade) {
            if ($grade->max_score > 0) {
                $totalScore += $grade->score;
                $totalMaxScore += $grade->max_score;
            }
        }

        return $totalMaxScore > 0 ? round(($totalScore / $totalMaxScore) * 100, 1) : 0;
    }

    /**
     * DepEd Transmutation Table 1
     *
     * @param float $initialGrade
     * @return int
     */
    private function transmutationTable1($initialGrade)
    {
        if ($initialGrade >= 100) return 100;
        if ($initialGrade >= 98.40) return 99;
        if ($initialGrade >= 96.80) return 98;
        if ($initialGrade >= 95.20) return 97;
        if ($initialGrade >= 93.60) return 96;
        if ($initialGrade >= 92.00) return 95;
        if ($initialGrade >= 90.40) return 94;
        if ($initialGrade >= 88.80) return 93;
        if ($initialGrade >= 87.20) return 92;
        if ($initialGrade >= 85.60) return 91;
        if ($initialGrade >= 84.00) return 90;
        if ($initialGrade >= 82.40) return 89;
        if ($initialGrade >= 80.80) return 88;
        if ($initialGrade >= 79.20) return 87;
        if ($initialGrade >= 77.60) return 86;
        if ($initialGrade >= 76.00) return 85;
        if ($initialGrade >= 74.40) return 84;
        if ($initialGrade >= 72.80) return 83;
        if ($initialGrade >= 71.20) return 82;
        if ($initialGrade >= 69.60) return 81;
        if ($initialGrade >= 68.00) return 80;
        if ($initialGrade >= 66.40) return 79;
        if ($initialGrade >= 64.80) return 78;
        if ($initialGrade >= 63.20) return 77;
        if ($initialGrade >= 61.60) return 76;
        if ($initialGrade >= 60.00) return 75;
        if ($initialGrade >= 56.00) return 74;
        if ($initialGrade >= 52.00) return 73;
        if ($initialGrade >= 48.00) return 72;
        if ($initialGrade >= 44.00) return 71;
        if ($initialGrade >= 40.00) return 70;
        if ($initialGrade >= 36.00) return 69;
        if ($initialGrade >= 32.00) return 68;
        if ($initialGrade >= 28.00) return 67;
        if ($initialGrade >= 24.00) return 66;
        if ($initialGrade >= 20.00) return 65;
        if ($initialGrade >= 16.00) return 64;
        if ($initialGrade >= 12.00) return 63;
        if ($initialGrade >= 8.00) return 62;
        if ($initialGrade >= 4.00) return 61;
        return 60;
    }
}
