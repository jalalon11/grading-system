@php
/**
 * Student Profile View - Teacher Module
 * This page shows comprehensive student information and academic performance.
 */

// Define the transmutation function based on the selected table
function getTransmutedGrade($initialGrade, $tableType) {
    if ($initialGrade < 0) return 60;
    
    // Table 1: DepEd Transmutation Table (Default)
    if ($tableType == 1) {
        if ($initialGrade == 100) return 100;
        elseif ($initialGrade >= 98.40) return 99;
        elseif ($initialGrade >= 96.80) return 98;
        elseif ($initialGrade >= 95.20) return 97;
        elseif ($initialGrade >= 93.60) return 96;
        elseif ($initialGrade >= 92.00) return 95;
        elseif ($initialGrade >= 90.40) return 94;
        elseif ($initialGrade >= 88.80) return 93;
        elseif ($initialGrade >= 87.20) return 92;
        elseif ($initialGrade >= 85.60) return 91;
        elseif ($initialGrade >= 84.00) return 90;
        elseif ($initialGrade >= 82.40) return 89;
        elseif ($initialGrade >= 80.80) return 88;
        elseif ($initialGrade >= 79.20) return 87;
        elseif ($initialGrade >= 77.60) return 86;
        elseif ($initialGrade >= 76.00) return 85;
        elseif ($initialGrade >= 74.40) return 84;
        elseif ($initialGrade >= 72.80) return 83;
        elseif ($initialGrade >= 71.20) return 82;
        elseif ($initialGrade >= 69.60) return 81;
        elseif ($initialGrade >= 68.00) return 80;
        elseif ($initialGrade >= 66.40) return 79;
        elseif ($initialGrade >= 64.80) return 78;
        elseif ($initialGrade >= 63.20) return 77;
        elseif ($initialGrade >= 61.60) return 76;
        elseif ($initialGrade >= 60.00) return 75;
        else return 60;
    }
    else {
        // Default to table 1 if an invalid table type is specified
        return getTransmutedGrade($initialGrade, 1);
    }
}

// Get grades by subject and term
$gradesBySubject = [];
$terms = ['Q1', 'Q2', 'Q3', 'Q4'];
$averageGrades = [];
$overallAverage = ['Q1' => 0, 'Q2' => 0, 'Q3' => 0, 'Q4' => 0, 'Final' => 0];

// If we're viewing from an assigned section, only show grades for the assigned subject
if (isset($isFromAssignedSection) && $isFromAssignedSection && isset($subject)) {
    // Filter grades to only show the assigned subject
    $filteredGrades = $student->grades;
    
    $subjectId = is_object($subject) ? $subject->id : (is_array($subject) ? $subject['id'] : null);
    $subjectName = is_object($subject) ? $subject->name : (is_array($subject) ? $subject['name'] : 'Subject');
    
    if ($filteredGrades->isEmpty()) {
        // No grades found for this subject
        $gradesBySubject = [
            $subjectId => [
                'name' => $subjectName,
                'grades' => []
            ]
        ];
    } else {
        foreach($filteredGrades as $grade) {
            $gradeSubjectId = $grade->subject_id;
            
            if (!isset($gradesBySubject[$gradeSubjectId])) {
                $gradesBySubject[$gradeSubjectId] = [
                    'name' => $subjectName,
                    'grades' => []
                ];
            }
            
            if (!isset($gradesBySubject[$gradeSubjectId]['grades'][$grade->term])) {
                $gradesBySubject[$gradeSubjectId]['grades'][$grade->term] = [];
            }
            
            $gradesBySubject[$gradeSubjectId]['grades'][$grade->term][] = $grade;
        }
    }
} else {
    // Original code for showing all grades
    foreach($student->grades as $grade) {
        $subjectId = $grade->subject_id;
        $subjectName = $grade->subject->name ?? 'Unknown Subject';
        
        if (!isset($gradesBySubject[$subjectId])) {
            $gradesBySubject[$subjectId] = [
                'name' => $subjectName,
                'grades' => []
            ];
        }
        
        if (!isset($gradesBySubject[$subjectId]['grades'][$grade->term])) {
            $gradesBySubject[$subjectId]['grades'][$grade->term] = [];
        }
        
        $gradesBySubject[$subjectId]['grades'][$grade->term][] = $grade;
    }
}

// Calculate average grade per subject per term
$subjectCount = count($gradesBySubject);

foreach($gradesBySubject as $subjectId => $subject) {
    $averageGrades[$subjectId] = [];
    $termTotals = [];
    
    foreach($terms as $term) {
        if (isset($subject['grades'][$term])) {
            $totalPercentage = 0;
            $totalWeight = 0;
            
            foreach ($subject['grades'][$term] as $grade) {
                $percentage = $grade->score / $grade->max_score * 100;
                $totalPercentage += $percentage;
                $totalWeight++;
            }
            
            if ($totalWeight > 0) {
                $averagePercentage = $totalPercentage / $totalWeight;
                $transmutedGrade = getTransmutedGrade($averagePercentage, $selectedTransmutationTable);
                $averageGrades[$subjectId][$term] = $transmutedGrade;
                
                if (!isset($termTotals[$term])) {
                    $termTotals[$term] = 0;
                }
                $termTotals[$term] += $transmutedGrade;
            }
        }
    }
    
    // Calculate final average for the subject
    $validTerms = 0;
    $termSum = 0;
    
    foreach($terms as $term) {
        if (isset($averageGrades[$subjectId][$term])) {
            $termSum += $averageGrades[$subjectId][$term];
            $validTerms++;
        }
    }
    
    if ($validTerms > 0) {
        $averageGrades[$subjectId]['Final'] = round($termSum / $validTerms, 2);
    }
}

// Calculate overall average per term and final
foreach($terms as $term) {
    $validSubjects = 0;
    foreach($averageGrades as $subjectId => $grades) {
        if (isset($grades[$term])) {
            $overallAverage[$term] += $grades[$term];
            $validSubjects++;
        }
    }
    
    if ($validSubjects > 0) {
        $overallAverage[$term] = round($overallAverage[$term] / $validSubjects, 2);
    }
}

// Calculate final overall average
$validTerms = 0;
$termSum = 0;

foreach($terms as $term) {
    if ($overallAverage[$term] > 0) {
        $termSum += $overallAverage[$term];
        $validTerms++;
    }
}

if ($validTerms > 0) {
    $overallAverage['Final'] = round($termSum / $validTerms, 2);
}

// Attendance summary
$attendanceSummary = [
    'present' => 0,
    'late' => 0,
    'absent' => 0,
    'excused' => 0,
    'total' => 0
];

foreach($student->attendances as $attendance) {
    $attendanceSummary['total']++;
    if ($attendance->status == 'present') {
        $attendanceSummary['present']++;
    } elseif ($attendance->status == 'late') {
        $attendanceSummary['late']++;
    } elseif ($attendance->status == 'absent') {
        $attendanceSummary['absent']++;
    } elseif ($attendance->status == 'excused') {
        $attendanceSummary['excused']++;
    }
}

// Calculate attendance percentage
$attendancePercentage = $attendanceSummary['total'] > 0 
    ? round(($attendanceSummary['present'] + $attendanceSummary['late']) / $attendanceSummary['total'] * 100, 1) 
    : 0;

// Calculate student's age
$birthDate = $student->birth_date;
$today = new \DateTime();
$age = $birthDate->diff($today)->y;
@endphp

@extends('layouts.app')

@push('styles')
<style>
    /* Main Containers */
    .profile-container {
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem rgba(33, 37, 41, 0.15);
        margin-bottom: 24px;
    }
    
    /* Profile Header */
    .profile-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        color: white;
        border-radius: 10px 10px 0 0;
        padding: 25px;
        position: relative;
        border-bottom: 4px solid #f0ce0d;
    }
    
    .profile-header::before {
        content: '👨‍🎓 Student Profile';
        position: absolute;
        top: -12px;
        right: 20px;
        background: #f0ce0d;
        padding: 4px 15px;
        border-radius: 15px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #000;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    /* Assigned Subject Header Style */
    .assigned-subject-header {
        border-bottom: 4px solid #ffc107;
    }
    
    .assigned-subject-header::before {
        content: '📚 Assigned Subject View';
        background: #ffc107;
        color: #000;
    }
    
    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.9);
        color: #28a745;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 600;
        border: 4px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }
    
    .dark .profile-avatar {
        background-color: rgba(255, 255, 255, 0.9);
        color: #28a745;
        border-color: rgba(255, 255, 255, 0.5);
    }
    
    /* Info Cards */
    .info-card {
        background-color: #f8f9fa;
        border-radius: 12px;
        padding: 15px;
        height: 100%;
        transition: transform 0.2s;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    
    .card-title {
        font-size: 0.85rem;
        font-weight: 500;
        color: #6c757d;
        margin-bottom: 8px;
    }
    
    .card-value {
        font-size: 1.25rem;
        font-weight: 600;
        color: #212529;
    }
    
    .info-icon {
        font-size: 1.5rem;
        color: rgba(108, 117, 125, 0.3);
        margin-right: 15px;
    }
    
    /* Stat Cards */
    .stat-card {
        border-radius: 10px;
        padding: 15px;
        margin-bottom: 20px;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border-left: 4px solid;
    }
    
    .stat-card.present {
        border-left-color: #28a745;
        background-color: rgba(40, 167, 69, 0.08);
    }
    
    .stat-card.late {
        border-left-color: #ffc107;
        background-color: rgba(255, 193, 7, 0.08);
    }
    
    .stat-card.absent {
        border-left-color: #dc3545;
        background-color: rgba(220, 53, 69, 0.08);
    }
    
    .stat-card.excused {
        border-left-color: #6c757d;
        background-color: rgba(108, 117, 125, 0.08);
    }
    
    /* Tables */
    .table-grades {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }
    
    .table-grades th {
        background-color: #f8f9fa;
        color: #495057;
        font-weight: 600;
        text-align: center;
        border-bottom: 2px solid #e9ecef;
        padding: 12px 15px;
    }
    
    .table-grades td {
        padding: 12px 15px;
        border-bottom: 1px solid #e9ecef;
        vertical-align: middle;
    }
    
    .table-grades tr:last-child td {
        border-bottom: none;
    }
    
    .table-grades tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
    }
    
    /* Grade styling */
    .grade-value {
        font-weight: 500;
    }
    
    .grade-failing {
        color: #dc3545;
    }
    
    .final-column {
        font-weight: 700;
        color: #28a745;
    }
    
    .final-column.grade-failing {
        color: #dc3545;
    }
    
    /* Transmutation Selector */
    .transmutation-selector {
        margin-bottom: 20px;
    }
    
    /* Misc Elements */
    .section-title {
        position: relative;
        font-size: 1.25rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }
    
    .section-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 50px;
        background-color: #0d6efd;
    }
    
    .badge-lg {
        padding: 6px 12px;
        font-size: 0.875rem;
    }
    
    .progress {
        height: 8px;
        border-radius: 4px;
    }
    
    .bg-soft-blue {
        background-color: rgba(13, 110, 253, 0.1);
    }

    /* For Mobile Responsiveness */
    @media (max-width: 767.98px) {
        .profile-header {
            text-align: center;
        }
        
        .profile-avatar {
            margin: 0 auto 15px;
        }
        
        .stats-container {
            margin-top: 20px;
        }
    }

    /* Profile styling */
    .profile-section {
        background-color: white;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #e9ecef;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }

    .profile-header {
        padding-bottom: 1rem;
        margin-bottom: 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .profile-info-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid #e9ecef;
    }

    .profile-info-label {
        font-weight: 600;
        color: #6c757d;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 1rem;
        border: 3px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    }

    /* Dark mode support */
    .dark .profile-section {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .profile-header {
        border-bottom-color: var(--border-color);
    }

    .dark .profile-info-item {
        border-bottom-color: var(--border-color);
    }

    .dark .profile-info-label {
        color: var(--text-muted);
    }

    .dark .profile-info-value {
        color: var(--text-color);
    }

    .dark .profile-avatar {
        border-color: var(--border-color);
    }

    .dark .nav-tabs {
        border-bottom-color: var(--border-color);
    }

    .dark .nav-tabs .nav-link {
        color: var(--text-muted);
        background-color: transparent;
        border-color: transparent;
    }

    .dark .nav-tabs .nav-link:hover {
        color: var(--text-color);
        border-color: var(--border-color);
        isolation: isolate;
    }

    .dark .nav-tabs .nav-link.active {
        color: #4361ee;
        background-color: var(--bg-card);
        border-color: var(--border-color);
        border-bottom-color: var(--bg-card);
    }

    .dark .table {
        color: var(--text-color);
    }

    .dark .table thead th {
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .table td {
        border-color: var(--border-color);
    }

    .dark .table tbody tr:hover {
        background-color: rgba(67, 97, 238, 0.1);
    }

    .dark .badge {
        border: 1px solid var(--border-color);
    }

    .dark .badge.bg-light {
        background-color: var(--bg-card-header) !important;
        color: var(--text-color) !important;
    }

    .dark .card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .card-header {
        background-color: var(--bg-card-header);
        border-bottom-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .card-body {
        color: var(--text-color);
    }

    .dark .text-muted {
        color: var(--text-muted) !important;
    }

    .dark .btn-outline-secondary {
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .btn-outline-secondary:hover {
        background-color: var(--border-color);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .progress {
        background-color: var(--border-color);
    }

    .dark .breadcrumb {
        background-color: var(--bg-card-header);
    }

    .dark .breadcrumb-item {
        color: var(--text-muted);
    }

    .dark .breadcrumb-item.active {
        color: var(--text-color);
    }

    .dark .breadcrumb-item + .breadcrumb-item::before {
        color: var(--text-muted);
    }

    /* Dark mode grades section */
    .dark .grade-card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .grade-header {
        background-color: var(--bg-card-header);
        border-bottom-color: var(--border-color);
    }

    .dark .grade-value {
        color: var(--text-color);
    }

    .dark .grade-label {
        color: var(--text-muted);
    }

    /* Dark mode charts */
    .dark .chart-container {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .chart-title {
        color: var(--text-color);
    }

    .dark .chart-legend {
        color: var(--text-muted);
    }

    /* Profile Card Styles */
    .profile-header {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        padding: 2rem;
        border-radius: 0.5rem 0.5rem 0 0;
        position: relative;
        border-bottom: 4px solid rgba(255, 255, 255, 0.1);
    }

    .dark .profile-header {
        border-bottom-color: rgba(255, 255, 255, 0.1);
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.9);
        color: #28a745;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        font-weight: 600;
        border: 4px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 1rem;
    }

    .dark .profile-avatar {
        background-color: rgba(255, 255, 255, 0.9);
        color: #28a745;
        border-color: rgba(255, 255, 255, 0.5);
    }

    .profile-info {
        background-color: #fff;
        border-radius: 0 0 0.5rem 0.5rem;
        padding: 2rem;
    }

    .info-item {
        padding: 1rem;
        border-radius: 0.5rem;
        background-color: #f8f9fa;
        margin-bottom: 1rem;
    }

    .info-label {
        color: #6c757d;
        font-size: 0.875rem;
        margin-bottom: 0.25rem;
    }

    .info-value {
        font-weight: 500;
        color: #212529;
    }

    .performance-card {
        background-color: #fff;
        border-radius: 0.5rem;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .grade-table th {
        background-color: #f8f9fa;
    }

    /* Dark mode support */
    .dark .profile-info {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .info-item {
        background-color: var(--bg-card-header);
        border: 1px solid var(--border-color);
    }

    .dark .info-label {
        color: var(--text-muted);
    }

    .dark .info-value {
        color: var(--text-color);
    }

    .dark .performance-card {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
    }

    .dark .grade-table th {
        background-color: var(--bg-card-header) !important;
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .grade-table td {
        background-color: var(--bg-card);
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .nav-tabs {
        border-color: var(--border-color);
    }

    .dark .nav-tabs .nav-link {
        color: var(--text-muted);
        background-color: transparent;
        border-color: transparent;
    }

    .dark .nav-tabs .nav-link:hover {
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .nav-tabs .nav-link.active {
        color: var(--text-color);
        background-color: var(--bg-card);
        border-color: var(--border-color);
        border-bottom-color: var(--bg-card);
    }

    .dark .tab-content {
        background-color: var(--bg-card);
        border: 1px solid var(--border-color);
        border-top: none;
    }

    .dark .btn-edit {
        background-color: var(--bg-card-header);
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .btn-edit:hover {
        background-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .student-badge {
        background-color: var(--bg-card-header);
        color: var(--text-color);
        border: 1px solid var(--border-color);
    }

    .dark .section-badge {
        background-color: #4361ee;
        color: #ffffff;
    }

    .dark .grade-badge {
        background-color: #6c757d;
        color: #ffffff;
    }

    .dark .academic-stats {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .stats-value {
        color: var(--text-color);
    }

    .dark .stats-label {
        color: var(--text-muted);
    }

    .dark .transmutation-table {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .transmutation-table th,
    .dark .transmutation-table td {
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .below-75 {
        color: #dc3545;
    }

    .dark .needs-improvement {
        color: #ffc107;
    }

    /* Dark mode support for Academic Performance and Attendance Summary */
    .dark .profile-container {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .info-card {
        background-color: var(--bg-card-header);
        border: 1px solid var(--border-color);
    }

    .dark .info-icon {
        color: var(--text-muted);
    }

    .dark .card-title {
        color: var(--text-muted);
    }

    .dark .card-value {
        color: var(--text-color);
    }

    .dark .section-title {
        color: var(--text-color);
    }

    .dark .section-title:after {
        background-color: #4361ee;
    }

    .dark .transmutation-selector .form-select {
        background-color: var(--bg-card-header);
        border-color: var(--border-color);
        color: var(--text-color);
    }

    .dark .transmutation-selector .form-label {
        color: var(--text-color);
    }

    .dark .table-grades {
        color: var(--text-color);
    }

    .dark .table-grades th {
        background-color: var(--bg-card-header);
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .dark .table-grades td {
        border-color: var(--border-color);
    }

    .dark .table-grades tr:hover {
        background-color: rgba(67, 97, 238, 0.1);
    }

    .dark .text-muted {
        color: var(--text-muted) !important;
    }

    .dark .bg-soft-blue {
        background-color: var(--bg-card-header);
        border: 1px solid var(--border-color);
    }

    /* Attendance Summary dark mode support */
    .dark .stat-card {
        background-color: var(--bg-card);
        border-color: var(--border-color);
    }

    .dark .stat-card.present {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .dark .stat-card.late {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .dark .stat-card.absent {
        background-color: rgba(220, 53, 69, 0.1);
    }

    .dark .stat-card.excused {
        background-color: rgba(108, 117, 125, 0.1);
    }

    .dark .progress {
        background-color: var(--border-color);
    }

    .dark .progress-bar {
        background-color: #28a745;
    }

    .dark tr[style*="background-color: #f8f9fa"] {
        background-color: var(--bg-card-header) !important;
    }

    .dark .table-grades tr:last-child {
        background-color: var(--bg-card-header) !important;
    }

    .dark .table-grades tr:last-child td {
        color: var(--text-color);
    }

    /* Fix for the grade legend */
    .dark .grade-legend {
        color: var(--text-muted);
    }

    .dark .grade-failing {
        color: #dc3545 !important;
    }

    /* Fix for contact details section */
    .dark .bg-soft-blue {
        background-color: var(--bg-card-header);
    }

    .dark .bg-soft-blue h5 {
        color: var(--text-color);
    }

    .dark .bg-soft-blue p,
    .dark .bg-soft-blue strong {
        color: var(--text-color);
    }

    /* Fix for overall average row */
    .dark .table-grades tr[style*="background-color: #f8f9fa"] {
        background-color: var(--bg-card-header) !important;
    }

    .dark .table-grades tr[style*="background-color: #f8f9fa"] td {
        color: var(--text-color);
    }

    /* Fix for the transmutation table dropdown */
    .dark select option {
        background-color: var(--bg-card);
        color: var(--text-color);
    }

    .dark .form-select:focus {
        border-color: #4361ee;
        box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-graduate text-primary me-2"></i> Student Profile
            </h1>
            @if(isset($isFromAssignedSection) && $isFromAssignedSection && isset($subject))
                <p class="text-muted mb-0">Viewing {{ is_object($subject) ? ($subject->name ?? 'Subject') : (is_array($subject) && isset($subject['name']) ? $subject['name'] : 'Subject') }} grades for this student</p>
            @else
                <p class="text-muted mb-0">Comprehensive student information and academic performance</p>
            @endif
        </div>
        <div>
            <a href="{{ route('teacher.students.index') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Students
            </a>
            
            @if(!isset($isFromAssignedSection) || !$isFromAssignedSection)
            <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-edit me-1"></i> Edit Student
            </a>
            @endif
        </div>
    </div>

    <!-- Alert for Assigned Subject View -->
    @if(isset($isFromAssignedSection) && $isFromAssignedSection && isset($subject))
    <div class="alert alert-info alert-dismissible fade show shadow-sm mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-info-circle me-2"></i>
            <div>You are viewing this student's grades for <strong>{{ is_object($subject) ? ($subject->name ?? 'Subject') : (is_array($subject) && isset($subject['name']) ? $subject['name'] : 'Subject') }}</strong> only. You can only view and manage grades for the subjects you teach.</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Student Profile Header -->
    <div class="profile-container">
        <div class="profile-header {{ isset($isFromAssignedSection) && $isFromAssignedSection ? 'assigned-subject-header' : '' }}">
            <div class="row align-items-center">
                <div class="col-md-2 text-center text-md-start">
                    <div class="profile-avatar mx-auto mx-md-0">
                        {{ strtoupper(substr($student->first_name, 0, 1) . substr($student->last_name, 0, 1)) }}
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-start">
                    <h2 class="mb-1">{{ $student->last_name }}, {{ $student->first_name }}</h2>
                    <p class="mb-2">
                        <span class="badge bg-light text-dark me-2">Student ID: {{ $student->student_id }}</span>
                        <span class="badge bg-light text-dark">LRN: {{ $student->lrn }}</span>
                    </p>
                    <p class="mb-0">
                        <span class="badge bg-primary badge-lg">
                            <i class="fas fa-users me-1"></i> {{ $student->section->name }}
                        </span>
                        <span class="badge bg-secondary badge-lg">
                            <i class="fas fa-layer-group me-1"></i> Grade {{ $student->section->grade_level }}
                        </span>
                    </p>
                </div>
                <div class="col-md-4 mt-3 mt-md-0">
                    <div class="bg-white bg-opacity-10 rounded p-3">
                        <div class="row">
                            <div class="col-6 border-end">
                                <div class="text-center">
                                    <h3 class="mb-0">{{ isset($overallAverage) && isset($overallAverage['Final']) ? $overallAverage['Final'] : 'N/A' }}</h3>
                                    <small>Average</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center">
                                    @if(!isset($isFromAssignedSection) || !$isFromAssignedSection)
                                    <h3 class="mb-0">{{ isset($attendancePercentage) ? $attendancePercentage : 0 }}%</h3>
                                    <small>Attendance</small>
                                    @else
                                    <h3 class="mb-0">{{ isset($attendancePercentage) ? $attendancePercentage : 0 }}%</h3>
                                    <small>Attendance</small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Key Information Cards -->
        <div class="p-4">
            <div class="row">
                <div class="col-md col-sm-6 mb-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center">
                            <div class="info-icon">
                                <i class="fas fa-venus-mars"></i>
                            </div>
                            <div>
                                <div class="card-title">Gender</div>
                                <div class="card-value">{{ $student->gender }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md col-sm-6 mb-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center">
                            <div class="info-icon">
                                <i class="fas fa-birthday-cake"></i>
                            </div>
                            <div>
                                <div class="card-title">Date of Birth</div>
                                <div class="card-value">{{ $student->birth_date->format('M d, Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md col-sm-6 mb-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center">
                            <div class="info-icon">
                                <i class="fas fa-user-clock"></i>
                            </div>
                            <div>
                                <div class="card-title">Age</div>
                                <div class="card-value">{{ $age }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md col-sm-6 mb-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center">
                            <div class="info-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <div>
                                <div class="card-title">Guardian</div>
                                <div class="card-value">{{ $student->guardian_name ?: 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md col-sm-6 mb-4">
                    <div class="info-card">
                        <div class="d-flex align-items-center">
                            <div class="info-icon">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <div>
                                <div class="card-title">Contact</div>
                                <div class="card-value">{{ $student->guardian_contact ?: 'Not specified' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic Performance Section (modified for assigned subject view) -->
    <div class="profile-container p-4">
        <h3 class="section-title">
            @if(isset($isFromAssignedSection) && $isFromAssignedSection && isset($subject))
                {{ is_object($subject) ? ($subject->name ?? 'Subject') : (is_array($subject) && isset($subject['name']) ? $subject['name'] : 'Subject') }} Performance
            @else
                Academic Performance
            @endif
        </h3>
        
        <!-- Transmutation Table Selector -->
        <div class="transmutation-selector">
            <form action="{{ route('teacher.students.show', array_merge(['student' => $student->id], request()->query())) }}" method="GET" class="d-flex align-items-center">
                @if(isset($isFromAssignedSection) && $isFromAssignedSection && isset($subject))
                    <input type="hidden" name="from_assigned" value="1">
                    <input type="hidden" name="subject_id" value="{{ is_object($subject) ? ($subject->id ?? '') : (is_array($subject) && isset($subject['id']) ? $subject['id'] : '') }}">
                @endif
                <label for="transmutation_table" class="form-label me-2 mb-0">Transmutation Table:</label>
                <select name="transmutation_table" id="transmutation_table" class="form-select form-select-sm" style="width: auto;" onchange="this.form.submit()">
                    <option value="1" {{ $selectedTransmutationTable == 1 ? 'selected' : '' }}>DepEd Transmutation Table</option>
                    <option value="2" {{ $selectedTransmutationTable == 2 ? 'selected' : '' }}>Grades 1-10 & Non-Core Subjects</option>
                    <option value="3" {{ $selectedTransmutationTable == 3 ? 'selected' : '' }}>SHS Core Subjects</option>
                    <option value="4" {{ $selectedTransmutationTable == 4 ? 'selected' : '' }}>SHS Academic Track</option>
                </select>
            </form>
        </div>
        
        <!-- Grades Table -->
        <div class="table-responsive">
            <table class="table-grades">
                <thead>
                    <tr>
                        <th style="text-align: left;">Subject</th>
                        <th>Q1</th>
                        <th>Q2</th>
                        <th>Q3</th>
                        <th>Q4</th>
                        <th>Final</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($gradesBySubject) == 0)
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-info-circle me-2"></i>
                                    No grades have been entered for this student yet.
                                </div>
                            </td>
                        </tr>
                    @else
                        @foreach($gradesBySubject as $subjectId => $subject)
                            <tr>
                                <td style="text-align: left; font-weight: 500;">{{ $subject['name'] }}</td>
                                
                                @foreach($terms as $term)
                                    <td class="text-center">
                                        @if(isset($averageGrades[$subjectId][$term]))
                                            <span class="grade-value {{ $averageGrades[$subjectId][$term] < 75 ? 'grade-failing' : '' }}">
                                                {{ $averageGrades[$subjectId][$term] }}
                                            </span>
                                        @else
                                            <span class="text-muted">--</span>
                                        @endif
                                    </td>
                                @endforeach
                                
                                <td class="text-center">
                                    @if(isset($averageGrades[$subjectId]['Final']))
                                        <span class="grade-value final-column {{ $averageGrades[$subjectId]['Final'] < 75 ? 'grade-failing' : '' }}">
                                            {{ $averageGrades[$subjectId]['Final'] }}
                                        </span>
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    
                    <!-- Overall Average Row -->
                    @if(count($gradesBySubject) > 0)
                    <tr style="background-color: #f8f9fa; font-weight: bold;">
                        <td style="text-align: left;">Overall Average</td>
                        
                        @foreach($terms as $term)
                            <td class="text-center">
                                @if(isset($overallAverage) && isset($overallAverage[$term]) && $overallAverage[$term] > 0)
                                    <span class="grade-value {{ $overallAverage[$term] < 75 ? 'grade-failing' : '' }}">
                                        {{ $overallAverage[$term] }}
                                    </span>
                                @else
                                    <span class="text-muted">--</span>
                                @endif
                            </td>
                        @endforeach
                        
                        <td class="text-center">
                            @if(isset($overallAverage) && isset($overallAverage['Final']) && $overallAverage['Final'] > 0)
                                <span class="grade-value final-column {{ $overallAverage['Final'] < 75 ? 'grade-failing' : '' }}" style="font-size: 1.1rem;">
                                    {{ $overallAverage['Final'] }}
                                </span>
                            @else
                                <span class="text-muted">--</span>
                            @endif
                        </td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
        
        <!-- Grade Legend -->
        <div class="mt-4">
            <div class="d-flex flex-wrap gap-3">
                <div class="d-flex align-items-center">
                    <span class="grade-failing me-2" style="font-weight: bold;">Below 75</span>
                    <small>Needs Improvement</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Conditionally show other sections only if not viewing from assigned subject -->
    @if(!isset($isFromAssignedSection) || !$isFromAssignedSection)
    <div class="row">
        <!-- Attendance Summary Section -->
        <div class="col-p-4">
            <div class="profile-container p-4">
                <h3 class="section-title">Attendance Summary</h3>
                
                <!-- Attendance Overview -->
                <div class="mb-4">
                    <h5 class="text-muted mb-3">Overall Attendance Rate</h5>
                    <div class="d-flex align-items-center mb-2">
                        <div class="flex-grow-1 me-3">
                            <div class="progress">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ isset($attendancePercentage) ? $attendancePercentage : 0 }}%" aria-valuenow="{{ isset($attendancePercentage) ? $attendancePercentage : 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="fw-bold">{{ isset($attendancePercentage) ? $attendancePercentage : 0 }}%</div>
                    </div>
                    <div class="text-muted small">
                        <span class="text-success">{{ isset($attendanceSummary) ? ($attendanceSummary['present'] + $attendanceSummary['late']) : 0 }}</span> present out of {{ isset($attendanceSummary) ? $attendanceSummary['total'] : 0 }} school days
                    </div>
                </div>
                
                <!-- Attendance Stats -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="stat-card present">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-check-circle fa-2x text-success"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ isset($attendanceSummary) ? $attendanceSummary['present'] : 0 }}</h4>
                                    <div class="text-muted small">Present</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card late">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-clock fa-2x text-warning"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ isset($attendanceSummary) ? $attendanceSummary['late'] : 0 }}</h4>
                                    <div class="text-muted small">Late</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card absent">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ isset($attendanceSummary) ? $attendanceSummary['absent'] : 0 }}</h4>
                                    <div class="text-muted small">Absent</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="stat-card excused">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <i class="fas fa-file-alt fa-2x text-secondary"></i>
                                </div>
                                <div>
                                    <h4 class="mb-0">{{ isset($attendanceSummary) ? $attendanceSummary['excused'] : 0 }}</h4>
                                    <div class="text-muted small">Excused</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Info -->
                <div class="mt-4">
                    <h3 class="section-title">Contact Details</h3>
                    <div class="bg-soft-blue p-3 rounded mb-3">
                        <h5 class="mb-3">Address</h5>
                        <p class="mb-0">{{ $student->address ?: 'No address on record' }}</p>
                    </div>
                    <div class="bg-soft-blue p-3 rounded">
                        <h5 class="mb-3">Guardian Information</h5>
                        <div class="mb-2">
                            <strong>Name:</strong> {{ $student->guardian_name ?: 'Not specified' }}
                        </div>
                        <div>
                            <strong>Contact:</strong> {{ $student->guardian_contact ?: 'Not specified' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        // Toggle between dashboard and table view
        const dashboardBtn = document.getElementById('showDashboard');
        const tableBtn = document.getElementById('showTable');
        const dashboard = document.getElementById('academicDashboard');
        const tableView = document.getElementById('academicTableView');
        
        if (dashboardBtn && tableBtn && dashboard && tableView) {
            // Set initial active state
            dashboardBtn.classList.add('active');
            dashboard.style.display = 'block';
            tableView.style.display = 'none';
            
            dashboardBtn.addEventListener('click', function() {
                dashboard.style.display = 'block';
                tableView.style.display = 'none';
                dashboardBtn.classList.add('active');
                tableBtn.classList.remove('active');
                localStorage.setItem('academicViewPreference', 'dashboard');
            });
            
            tableBtn.addEventListener('click', function() {
                dashboard.style.display = 'none';
                tableView.style.display = 'block';
                tableBtn.classList.add('active');
                dashboardBtn.classList.remove('active');
                localStorage.setItem('academicViewPreference', 'table');
            });
            
            // Load saved preference
            const savedPreference = localStorage.getItem('academicViewPreference');
            if (savedPreference === 'table') {
                dashboard.style.display = 'none';
                tableView.style.display = 'block';
                tableBtn.classList.add('active');
                dashboardBtn.classList.remove('active');
            }
        }
        
        // Term filter functionality
        const termFilter = document.getElementById('termFilter');
        const gradeRows = document.querySelectorAll('.grade-row');
        
        if (termFilter && gradeRows.length > 0) {
            termFilter.addEventListener('change', function() {
                const selectedTerm = this.value;
                
                gradeRows.forEach(row => {
                    const rowTerm = row.getAttribute('data-term');
                    if (selectedTerm === 'all' || rowTerm === selectedTerm) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Also filter subject cards in dashboard
                const subjectCards = document.querySelectorAll('.subject-grade-item');
                if (selectedTerm === 'all') {
                    subjectCards.forEach(card => {
                        card.style.display = '';
                    });
                }
            });
        }
    
        // Add the transmutation function
        function getTransmutedGrade(initialGrade, tableType) {
            if (initialGrade < 0) return 60;
            
            // Table 1: DepEd Transmutation Table (formerly Table 4)
            if (tableType == 1) {
                if (initialGrade == 100) return 100;
                else if (initialGrade >= 98.40) return 99;
                else if (initialGrade >= 96.80) return 98;
                else if (initialGrade >= 95.20) return 97;
                else if (initialGrade >= 93.60) return 96;
                else if (initialGrade >= 92.00) return 95;
                else if (initialGrade >= 90.40) return 94;
                else if (initialGrade >= 88.80) return 93;
                else if (initialGrade >= 87.20) return 92;
                else if (initialGrade >= 85.60) return 91;
                else if (initialGrade >= 84.00) return 90;
                else if (initialGrade >= 82.40) return 89;
                else if (initialGrade >= 80.80) return 88;
                else if (initialGrade >= 79.20) return 87;
                else if (initialGrade >= 77.60) return 86;
                else if (initialGrade >= 76.00) return 85;
                else if (initialGrade >= 74.40) return 84;
                else if (initialGrade >= 72.80) return 83;
                else if (initialGrade >= 71.20) return 82;
                else if (initialGrade >= 69.60) return 81;
                else if (initialGrade >= 68.00) return 80;
                else if (initialGrade >= 66.40) return 79;
                else if (initialGrade >= 64.80) return 78;
                else if (initialGrade >= 63.20) return 77;
                else if (initialGrade >= 61.60) return 76;
                else if (initialGrade >= 60.00) return 75;
                else return 60;
            }
            // Table 2: Grades 1-10 and Non-Core Subjects of TVL, Sports, and Arts & Design (formerly Table 1)
            else if (tableType == 2) {
                if (initialGrade >= 80) return 100;
                else if (initialGrade >= 78.40) return 99;
                else if (initialGrade >= 76.80) return 98;
                else if (initialGrade >= 75.20) return 97;
                else if (initialGrade >= 73.60) return 96;
                else if (initialGrade >= 72.00) return 95;
                else if (initialGrade >= 70.40) return 94;
                else if (initialGrade >= 68.80) return 93;
                else if (initialGrade >= 67.20) return 92;
                else if (initialGrade >= 65.60) return 91;
                else if (initialGrade >= 64.00) return 90;
                else if (initialGrade >= 62.40) return 89;
                else if (initialGrade >= 60.80) return 88;
                else if (initialGrade >= 59.20) return 87;
                else if (initialGrade >= 57.60) return 86;
                else if (initialGrade >= 56.00) return 85;
                else if (initialGrade >= 54.40) return 84;
                else if (initialGrade >= 52.80) return 83;
                else if (initialGrade >= 51.20) return 82;
                else if (initialGrade >= 49.60) return 81;
                else if (initialGrade >= 48.00) return 80;
                else if (initialGrade >= 46.40) return 79;
                else if (initialGrade >= 44.80) return 78;
                else if (initialGrade >= 43.20) return 77;
                else if (initialGrade >= 41.60) return 76;
                else if (initialGrade >= 40.00) return 75;
                else if (initialGrade >= 38.40) return 74;
                else if (initialGrade >= 36.80) return 73;
                else if (initialGrade >= 35.20) return 72;
                else if (initialGrade >= 33.60) return 71;
                else if (initialGrade >= 32.00) return 70;
                else if (initialGrade >= 30.40) return 69;
                else if (initialGrade >= 28.80) return 68;
                else if (initialGrade >= 27.20) return 67;
                else if (initialGrade >= 25.60) return 66;
                else if (initialGrade >= 24.00) return 65;
                else if (initialGrade >= 22.40) return 64;
                else if (initialGrade >= 20.80) return 63;
                else if (initialGrade >= 19.20) return 62;
                else if (initialGrade >= 17.60) return 61;
                else return 60;
            }
            // Table 3: For SHS Core Subjects and Work Immersion/Research/Business Enterprise/Performance (formerly Table 2)
            else if (tableType == 3) {
                if (initialGrade >= 100) return 100;
                else if (initialGrade >= 73.80) return 99;
                else if (initialGrade >= 72.60) return 98;
                else if (initialGrade >= 71.40) return 97;
                else if (initialGrade >= 70.20) return 96;
                else if (initialGrade >= 69.00) return 95;
                else if (initialGrade >= 67.80) return 94;
                else if (initialGrade >= 66.60) return 93;
                else if (initialGrade >= 65.40) return 92;
                else if (initialGrade >= 64.20) return 91;
                else if (initialGrade >= 63.00) return 90;
                else if (initialGrade >= 61.80) return 89;
                else if (initialGrade >= 60.60) return 88;
                else if (initialGrade >= 59.40) return 87;
                else if (initialGrade >= 58.20) return 86;
                else if (initialGrade >= 57.00) return 85;
                else if (initialGrade >= 55.80) return 84;
                else if (initialGrade >= 54.60) return 83;
                else if (initialGrade >= 53.40) return 82;
                else if (initialGrade >= 52.20) return 81;
                else if (initialGrade >= 51.00) return 80;
                else if (initialGrade >= 49.80) return 79;
                else if (initialGrade >= 48.60) return 78;
                else if (initialGrade >= 47.40) return 77;
                else if (initialGrade >= 46.20) return 76;
                else if (initialGrade >= 45.00) return 75;
                else if (initialGrade >= 43.80) return 74;
                else if (initialGrade >= 42.60) return 73;
                else if (initialGrade >= 41.40) return 72;
                else if (initialGrade >= 40.20) return 71;
                else if (initialGrade >= 39.00) return 70;
                else if (initialGrade >= 37.80) return 69;
                else if (initialGrade >= 36.60) return 68;
                else if (initialGrade >= 35.40) return 67;
                else if (initialGrade >= 34.20) return 66;
                else if (initialGrade >= 33.00) return 65;
                else if (initialGrade >= 31.80) return 64;
                else if (initialGrade >= 30.60) return 63;
                else if (initialGrade >= 29.40) return 62;
                else if (initialGrade >= 28.20) return 61;
                else return 60;
            }
            // Table 4: For all other SHS Subjects in the Academic Track (formerly Table 3)
            else if (tableType == 4) {
                if (initialGrade >= 100) return 100;
                else if (initialGrade >= 68.90) return 99;
                else if (initialGrade >= 67.80) return 98;
                else if (initialGrade >= 66.70) return 97;
                else if (initialGrade >= 65.60) return 96;
                else if (initialGrade >= 64.50) return 95;
                else if (initialGrade >= 63.40) return 94;
                else if (initialGrade >= 62.30) return 93;
                else if (initialGrade >= 61.20) return 92;
                else if (initialGrade >= 60.10) return 91;
                else if (initialGrade >= 59.00) return 90;
                else if (initialGrade >= 57.80) return 89;
                else if (initialGrade >= 56.70) return 88;
                else if (initialGrade >= 55.60) return 87;
                else if (initialGrade >= 54.50) return 86;
                else if (initialGrade >= 53.40) return 85;
                else if (initialGrade >= 52.30) return 84;
                else if (initialGrade >= 51.20) return 83;
                else if (initialGrade >= 50.10) return 82;
                else if (initialGrade >= 49.00) return 81;
                else if (initialGrade >= 47.90) return 80;
                else if (initialGrade >= 46.80) return 79;
                else if (initialGrade >= 45.70) return 78;
                else if (initialGrade >= 44.60) return 77;
                else if (initialGrade >= 43.50) return 76;
                else if (initialGrade >= 42.40) return 75;
                else if (initialGrade >= 41.30) return 74;
                else if (initialGrade >= 40.20) return 73;
                else if (initialGrade >= 39.10) return 72;
                else if (initialGrade >= 34.00) return 71;
                else if (initialGrade >= 28.90) return 70;
                else if (initialGrade >= 23.80) return 69;
                else if (initialGrade >= 19.70) return 68;
                else if (initialGrade >= 17.60) return 67;
                else if (initialGrade >= 15.50) return 66;
                else if (initialGrade >= 13.40) return 65;
                else if (initialGrade >= 11.30) return 64;
                else if (initialGrade >= 9.20) return 63;
                else if (initialGrade >= 7.10) return 62;
                else if (initialGrade >= 5.00) return 61;
                else return 60;
            }
            else {
                // Default to table 1 if an invalid table type is specified
                return getTransmutedGrade(initialGrade, 1);
            }
        }
        
        // Default to transmutation table 1 (or get from query string)
        let selectedTransmutationTable = {{ $selectedTransmutationTable ?? 1 }};
        
        // Update transmuted grades display when table changes
        function updateGradesDisplay() {
            // Update all grade displays in the table
            $('.grade-row').each(function() {
                const initialGrade = parseFloat($(this).data('initial-grade'));
                const gradeType = $(this).data('grade-type');
                const transmutedGrade = getTransmutedGrade(initialGrade, selectedTransmutationTable);
                
                // Update the transmuted grade display
                $(this).find('.transmuted-grade').text(transmutedGrade);
                
                // Update badge classes based on the transmuted grade
                const badgeElement = $(this).find('.transmuted-grade-badge');
                badgeElement.removeClass('bg-danger bg-warning bg-info bg-success');
                
                if (transmutedGrade < 75) {
                    badgeElement.addClass('bg-danger');
                } else if (transmutedGrade < 80) {
                    badgeElement.addClass('bg-warning');
                } else if (transmutedGrade < 90) {
                    badgeElement.addClass('bg-info');
                } else {
                    badgeElement.addClass('bg-success');
                }
            });
        }
        
        // Function to update grades when transmutation table changes
        function updateTransmutedGrades(tableType) {
            // Save the selected table type
            selectedTransmutationTable = tableType;
            
            // Update the grades display without reloading
            updateGradesDisplay();
            
            // Store the selection in localStorage
            localStorage.setItem('selectedTransmutationTable', tableType);
            
            // Update the URL to reflect the selected table
            const url = new URL(window.location);
            url.searchParams.set('transmutation_table', tableType);
            window.history.pushState({}, '', url);
        }
        
        // Initialize grades display
        updateGradesDisplay();
    });
</script>
@endpush


