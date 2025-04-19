<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceSummaryService
{
    /**
     * Get weekly attendance summary for a teacher's sections
     *
     * @param int $teacherId The ID of the teacher
     * @param int|null $sectionId Optional section ID to filter by
     * @param string|null $weekDate Optional date within the week to get summary for
     * @return array Weekly attendance summary data
     */
    public function getWeeklySummary(int $teacherId, ?int $sectionId = null, ?string $weekDate = null): array
    {
        // Get sections where the teacher is the adviser
        $sectionsQuery = Section::where('adviser_id', $teacherId);

        if ($sectionId) {
            $sectionsQuery->where('id', $sectionId);
        }

        $sectionIds = $sectionsQuery->pluck('id')->toArray();

        // Get the start and end of the specified week or current week
        if ($weekDate) {
            $date = Carbon::parse($weekDate);
            $startOfWeek = $date->copy()->startOfWeek();
            $endOfWeek = $date->copy()->endOfWeek();
        } else {
            $startOfWeek = Carbon::now()->startOfWeek();
            $endOfWeek = Carbon::now()->endOfWeek();
        }

        // Get all dates in the current week where attendance was created by this teacher
        $attendanceDates = Attendance::whereIn('section_id', $sectionIds)
            ->whereBetween('date', [$startOfWeek, $endOfWeek])
            ->where('teacher_id', $teacherId)
            ->select(DB::raw('DISTINCT date'))
            ->orderBy('date')
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date);
            });

        // Initialize the summary data structure
        $summary = [
            'dates' => [],
            'daily_stats' => [],
            'total_stats' => [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'half_day' => 0,
                'total_students' => 0,
                'attendance_rate' => 0,
            ],
            'students' => [],
        ];

        // Get all students in these sections
        $students = Student::whereIn('section_id', $sectionIds)
            ->select('id', 'first_name', 'middle_name', 'last_name', 'section_id')
            ->with('section:id,name')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $totalStudents = $students->count();

        // Initialize student attendance records
        foreach ($students as $student) {
            $summary['students'][$student->id] = [
                'id' => $student->id,
                'name' => $student->full_name,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'section' => $student->section->name,
                'attendance' => [],
                'stats' => [
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'excused' => 0,
                    'half_day' => 0,
                    'attendance_rate' => 0,
                    'attendance_ratio' => '0/0',
                ],
            ];
        }

        // Process each date with attendance records
        foreach ($attendanceDates as $date) {
            $dateString = $date->toDateString();
            $formattedDate = $date->format('D, M d');

            $summary['dates'][$dateString] = $formattedDate;

            // Initialize daily stats
            $summary['daily_stats'][$dateString] = [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'half_day' => 0,
                'total_students' => $totalStudents,
                'attendance_rate' => 0,
            ];

            // Get attendance records for this date and teacher
            $attendanceRecords = Attendance::whereIn('section_id', $sectionIds)
                ->where('date', $dateString)
                ->where('teacher_id', $teacherId)
                ->get();

            // Map of student IDs to their attendance status for this date
            $studentAttendance = [];
            foreach ($attendanceRecords as $record) {
                $studentAttendance[$record->student_id] = $record->status;

                // Update daily stats
                $summary['daily_stats'][$dateString][$record->status]++;
            }

            // For each student, record their attendance status for this date
            foreach ($students as $student) {
                $status = $studentAttendance[$student->id] ?? 'absent';

                // Record this student's attendance for this date
                $summary['students'][$student->id]['attendance'][$dateString] = $status;

                // Update student's overall stats
                $summary['students'][$student->id]['stats'][$status]++;
            }

            // Calculate daily attendance rate (present + late + half_day) / total
            $presentCount = $summary['daily_stats'][$dateString]['present'];
            $lateCount = $summary['daily_stats'][$dateString]['late'];
            $halfDayCount = $summary['daily_stats'][$dateString]['half_day'];

            $summary['daily_stats'][$dateString]['attendance_rate'] = $totalStudents > 0
                ? round((($presentCount + $lateCount + $halfDayCount) / $totalStudents) * 100)
                : 0;

            // Update total stats
            $summary['total_stats']['present'] += $presentCount;
            $summary['total_stats']['absent'] += $summary['daily_stats'][$dateString]['absent'];
            $summary['total_stats']['late'] += $lateCount;
            $summary['total_stats']['excused'] += $summary['daily_stats'][$dateString]['excused'];
            $summary['total_stats']['half_day'] += $halfDayCount;
        }

        // Calculate overall attendance rate
        $totalDays = count($attendanceDates);
        $totalRecords = $totalStudents * $totalDays;

        if ($totalRecords > 0) {
            $presentCount = $summary['total_stats']['present'];
            $lateCount = $summary['total_stats']['late'];
            $halfDayCount = $summary['total_stats']['half_day'];

            $summary['total_stats']['attendance_rate'] = round((($presentCount + $lateCount + $halfDayCount) / $totalRecords) * 100);
        }

        // Calculate each student's attendance rate
        foreach ($students as $student) {
            $studentStats = $summary['students'][$student->id]['stats'];
            $studentDaysPresent = $studentStats['present'] + $studentStats['late'] + $studentStats['half_day'];

            $summary['students'][$student->id]['stats']['attendance_rate'] = $totalDays > 0
                ? round(($studentDaysPresent / $totalDays) * 100)
                : 0;

            // Add attendance ratio with detailed breakdown
            $presentCount = $studentStats['present'];
            $lateCount = $studentStats['late'];
            $halfDayCount = $studentStats['half_day'];

            // Format the numerator to show half days as "Half"
            $numerator = '';
            if ($presentCount > 0 || $lateCount > 0) {
                $numerator = $presentCount + $lateCount;
            }

            if ($halfDayCount > 0) {
                if (!empty($numerator)) {
                    $numerator .= '+Half';
                } else {
                    $numerator = 'Half';
                }
            }

            if (empty($numerator)) {
                $numerator = '0';
            }

            $summary['students'][$student->id]['stats']['attendance_ratio'] = $numerator . '/' . $totalDays;
        }

        $summary['total_stats']['total_students'] = $totalStudents;
        $summary['total_stats']['total_days'] = $totalDays;

        return $summary;
    }

    /**
     * Get monthly attendance summary for a teacher's sections
     *
     * @param int $teacherId The ID of the teacher
     * @param string|null $yearMonth Optional year-month string (YYYY-MM) to filter by
     * @param int|null $sectionId Optional section ID to filter by
     * @return array Monthly attendance summary data
     */
    public function getMonthlySummary(int $teacherId, ?string $yearMonth = null, ?int $sectionId = null): array
    {
        // Get sections where the teacher is the adviser
        $sectionsQuery = Section::where('adviser_id', $teacherId);

        if ($sectionId) {
            $sectionsQuery->where('id', $sectionId);
        }

        $sectionIds = $sectionsQuery->pluck('id')->toArray();

        // Determine the month to analyze
        if ($yearMonth) {
            list($year, $month) = explode('-', $yearMonth);
            $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
            $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        } else {
            $startOfMonth = Carbon::now()->startOfMonth();
            $endOfMonth = Carbon::now()->endOfMonth();
            $yearMonth = $startOfMonth->format('Y-m');
        }

        // Get all dates in the month where attendance was created by this teacher
        $attendanceDates = Attendance::whereIn('section_id', $sectionIds)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('teacher_id', $teacherId)
            ->select(DB::raw('DISTINCT date'))
            ->orderBy('date')
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date);
            });

        // Initialize the summary data structure
        $summary = [
            'year_month' => $yearMonth,
            'month_name' => $startOfMonth->format('F Y'),
            'dates' => [],
            'daily_stats' => [],
            'weekly_stats' => [],
            'total_stats' => [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'half_day' => 0,
                'total_students' => 0,
                'attendance_rate' => 0,
            ],
            'students' => [],
        ];

        // Get all students in these sections
        $students = Student::whereIn('section_id', $sectionIds)
            ->select('id', 'first_name', 'middle_name', 'last_name', 'section_id')
            ->with('section:id,name')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $totalStudents = $students->count();

        // Initialize student attendance records
        foreach ($students as $student) {
            $summary['students'][$student->id] = [
                'id' => $student->id,
                'name' => $student->full_name,
                'first_name' => $student->first_name,
                'middle_name' => $student->middle_name,
                'last_name' => $student->last_name,
                'section' => $student->section->name,
                'attendance' => [],
                'stats' => [
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'excused' => 0,
                    'half_day' => 0,
                    'attendance_rate' => 0,
                    'attendance_ratio' => '0/0',
                ],
            ];
        }

        // Initialize weekly stats
        $weeksInMonth = [];
        $currentDate = $startOfMonth->copy();
        while ($currentDate->lte($endOfMonth)) {
            $weekNumber = $currentDate->weekOfMonth;
            if (!isset($weeksInMonth[$weekNumber])) {
                $weeksInMonth[$weekNumber] = [
                    'start_date' => $currentDate->copy()->startOfWeek()->format('M d'),
                    'end_date' => $currentDate->copy()->endOfWeek()->format('M d'),
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'excused' => 0,
                    'half_day' => 0,
                    'total_students' => $totalStudents,
                    'attendance_days' => 0,
                    'attendance_rate' => 0,
                ];
            }
            $currentDate->addDay();
        }
        $summary['weekly_stats'] = $weeksInMonth;

        // Process each date with attendance records
        foreach ($attendanceDates as $date) {
            $dateString = $date->toDateString();
            $formattedDate = $date->format('D, M d');
            $weekNumber = $date->weekOfMonth;

            $summary['dates'][$dateString] = $formattedDate;

            // Initialize daily stats
            $summary['daily_stats'][$dateString] = [
                'present' => 0,
                'absent' => 0,
                'late' => 0,
                'excused' => 0,
                'half_day' => 0,
                'total_students' => $totalStudents,
                'attendance_rate' => 0,
            ];

            // Get attendance records for this date and teacher
            $attendanceRecords = Attendance::whereIn('section_id', $sectionIds)
                ->where('date', $dateString)
                ->where('teacher_id', $teacherId)
                ->get();

            // Map of student IDs to their attendance status for this date
            $studentAttendance = [];
            foreach ($attendanceRecords as $record) {
                $studentAttendance[$record->student_id] = $record->status;

                // Update daily stats
                $summary['daily_stats'][$dateString][$record->status]++;

                // Update weekly stats
                $summary['weekly_stats'][$weekNumber][$record->status]++;
            }

            // For each student, record their attendance status for this date
            foreach ($students as $student) {
                $status = $studentAttendance[$student->id] ?? 'absent';

                // Record this student's attendance for this date
                $summary['students'][$student->id]['attendance'][$dateString] = $status;

                // Update student's overall stats
                $summary['students'][$student->id]['stats'][$status]++;
            }

            // Calculate daily attendance rate (present + late + half_day) / total
            $presentCount = $summary['daily_stats'][$dateString]['present'];
            $lateCount = $summary['daily_stats'][$dateString]['late'];
            $halfDayCount = $summary['daily_stats'][$dateString]['half_day'];

            $summary['daily_stats'][$dateString]['attendance_rate'] = $totalStudents > 0
                ? round((($presentCount + $lateCount + $halfDayCount) / $totalStudents) * 100)
                : 0;

            // Update total stats
            $summary['total_stats']['present'] += $presentCount;
            $summary['total_stats']['absent'] += $summary['daily_stats'][$dateString]['absent'];
            $summary['total_stats']['late'] += $lateCount;
            $summary['total_stats']['excused'] += $summary['daily_stats'][$dateString]['excused'];
            $summary['total_stats']['half_day'] += $halfDayCount;

            // Update weekly attendance days count
            $summary['weekly_stats'][$weekNumber]['attendance_days']++;
        }

        // Calculate weekly attendance rates
        foreach ($summary['weekly_stats'] as $weekNumber => &$weekStats) {
            if ($weekStats['attendance_days'] > 0) {
                $weeklyStudentDays = $totalStudents * $weekStats['attendance_days'];
                $presentCount = $weekStats['present'];
                $lateCount = $weekStats['late'];
                $halfDayCount = $weekStats['half_day'];

                $weekStats['attendance_rate'] = $weeklyStudentDays > 0
                    ? round((($presentCount + $lateCount + $halfDayCount) / $weeklyStudentDays) * 100)
                    : 0;
            }
        }

        // Calculate overall attendance rate
        $totalDays = count($attendanceDates);
        $totalRecords = $totalStudents * $totalDays;

        if ($totalRecords > 0) {
            $presentCount = $summary['total_stats']['present'];
            $lateCount = $summary['total_stats']['late'];
            $halfDayCount = $summary['total_stats']['half_day'];

            $summary['total_stats']['attendance_rate'] = round((($presentCount + $lateCount + $halfDayCount) / $totalRecords) * 100);
        }

        // Calculate each student's attendance rate
        foreach ($students as $student) {
            $studentStats = $summary['students'][$student->id]['stats'];
            $studentDaysPresent = $studentStats['present'] + $studentStats['late'] + $studentStats['half_day'];

            $summary['students'][$student->id]['stats']['attendance_rate'] = $totalDays > 0
                ? round(($studentDaysPresent / $totalDays) * 100)
                : 0;

            // Add attendance ratio with detailed breakdown
            $presentCount = $studentStats['present'];
            $lateCount = $studentStats['late'];
            $halfDayCount = $studentStats['half_day'];

            // Format the numerator to show half days as "Half"
            $numerator = '';
            if ($presentCount > 0 || $lateCount > 0) {
                $numerator = $presentCount + $lateCount;
            }

            if ($halfDayCount > 0) {
                if (!empty($numerator)) {
                    $numerator .= '+Half';
                } else {
                    $numerator = 'Half';
                }
            }

            if (empty($numerator)) {
                $numerator = '0';
            }

            $summary['students'][$student->id]['stats']['attendance_ratio'] = $numerator . '/' . $totalDays;
        }

        $summary['total_stats']['total_students'] = $totalStudents;
        $summary['total_stats']['total_days'] = $totalDays;

        return $summary;
    }

    /**
     * Get all weeks with attendance records for a teacher
     *
     * @param int $teacherId The ID of the teacher
     * @param int|null $sectionId Optional section ID to filter by
     * @return array Array of weeks with attendance records
     */
    public function getWeeksWithAttendance(int $teacherId, ?int $sectionId = null): array
    {
        // Get sections where the teacher is the adviser
        $sectionsQuery = Section::where('adviser_id', $teacherId);

        if ($sectionId) {
            $sectionsQuery->where('id', $sectionId);
        }

        $sectionIds = $sectionsQuery->pluck('id')->toArray();

        // Get all distinct dates with attendance records
        $attendanceDates = Attendance::whereIn('section_id', $sectionIds)
            ->where('teacher_id', $teacherId)
            ->select(DB::raw('DISTINCT date'))
            ->orderBy('date', 'desc')
            ->pluck('date')
            ->map(function ($date) {
                return Carbon::parse($date);
            });

        // Group dates by week
        $weeks = [];
        foreach ($attendanceDates as $date) {
            $weekStart = $date->copy()->startOfWeek()->format('Y-m-d');
            $weekEnd = $date->copy()->endOfWeek()->format('Y-m-d');
            $weekKey = $weekStart . '_' . $weekEnd;

            if (!isset($weeks[$weekKey])) {
                $weeks[$weekKey] = [
                    'start_date' => $weekStart,
                    'end_date' => $weekEnd,
                    'display_range' => $date->copy()->startOfWeek()->format('M d') . ' - ' . $date->copy()->endOfWeek()->format('M d, Y'),
                    'dates' => [],
                    'is_current' => $date->copy()->startOfWeek()->isCurrentWeek()
                ];
            }

            $weeks[$weekKey]['dates'][] = $date->format('Y-m-d');
        }

        // Sort weeks by start date (most recent first)
        uasort($weeks, function ($a, $b) {
            return strcmp($b['start_date'], $a['start_date']);
        });

        return array_values($weeks);
    }
}
