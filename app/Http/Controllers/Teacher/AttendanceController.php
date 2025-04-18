<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use App\Services\AttendanceSummaryService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * The attendance summary service instance.
     */
    protected $attendanceSummaryService;

    /**
     * Create a new controller instance.
     */
    public function __construct(AttendanceSummaryService $attendanceSummaryService)
    {
        $this->attendanceSummaryService = $attendanceSummaryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sections = Section::where('adviser_id', Auth::id())->get();

        $query = Attendance::query()
            ->join('students', 'attendances.student_id', '=', 'students.id')
            ->join('sections', 'students.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id());

        if ($request->filled('section_id')) {
            $query->where('students.section_id', $request->section_id);
        }

        if ($request->filled('date')) {
            $query->where('attendances.date', $request->date);
        }

        // Filter by month if month parameter is provided (format: YYYY-MM)
        if ($request->filled('month')) {
            $monthYear = explode('-', $request->month);
            if (count($monthYear) === 2) {
                $year = $monthYear[0];
                $month = $monthYear[1];
                $query->whereYear('attendances.date', $year)
                      ->whereMonth('attendances.date', $month);
            }
        }

        $attendanceRecords = $query->select('attendances.*', DB::raw("CONCAT(students.first_name, ' ', students.last_name) as name"), 'students.section_id')
            ->orderBy('attendances.date', 'desc')
            ->get();

        // Group attendance by date and section
        $attendances = [];
        foreach ($attendanceRecords as $record) {
            $date = $record->date->format('Y-m-d'); // Convert Carbon date to string
            $sectionId = $record->section_id;

            if (!isset($attendances[$date])) {
                $attendances[$date] = [];
            }

            if (!isset($attendances[$date][$sectionId])) {
                $sectionName = $sections->firstWhere('id', $sectionId)->name ?? 'Unknown';
                $attendances[$date][$sectionId] = [
                    'section_name' => $sectionName,
                    'present_count' => 0,
                    'late_count' => 0,
                    'absent_count' => 0,
                    'excused_count' => 0,
                    'half_day_count' => 0
                ];
            }

            if ($record->status === 'present') {
                $attendances[$date][$sectionId]['present_count']++;
            } elseif ($record->status === 'late') {
                $attendances[$date][$sectionId]['late_count']++;
            } elseif ($record->status === 'excused') {
                $attendances[$date][$sectionId]['excused_count']++;
            } elseif ($record->status === 'half_day') {
                $attendances[$date][$sectionId]['half_day_count']++;
            } else {
                $attendances[$date][$sectionId]['absent_count']++;
            }
        }

        // Get unique months from records for the month filter dropdown and calendar
        $availableMonths = Attendance::join('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id())
            ->select(
                DB::raw('DISTINCT DATE_FORMAT(date, "%Y-%m") as month_value, DATE_FORMAT(date, "%M %Y") as month_name'),
                DB::raw('YEAR(date) as year'),
                DB::raw('MONTH(date) as month')
            )
            ->orderBy('month_value', 'desc')
            ->get();

        // Calculate the number of school days (unique dates with attendance records)
        $schoolDaysQuery = Attendance::query()
            ->join('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id());

        // Apply the same filters as the main query
        if ($request->filled('section_id')) {
            $schoolDaysQuery->where('attendances.section_id', $request->section_id);
        }

        if ($request->filled('month')) {
            $monthYear = explode('-', $request->month);
            if (count($monthYear) === 2) {
                $year = $monthYear[0];
                $month = $monthYear[1];
                $schoolDaysQuery->whereYear('attendances.date', $year)
                      ->whereMonth('attendances.date', $month);
            }
        }

        $schoolDays = $schoolDaysQuery->select(DB::raw('COUNT(DISTINCT date) as count'))->first()->count;
        $currentMonth = $request->filled('month') ? Carbon::createFromFormat('Y-m', $request->month)->format('F Y') : Carbon::now()->format('F Y');

        // Get school days for the entire school year
        $currentSchoolYear = null;
        $schoolDaysForYear = 0;
        $schoolDayDates = [];

        // Get the current school year from the teacher's sections
        $teacherSection = Section::where('adviser_id', Auth::id())->first();
        if ($teacherSection) {
            $currentSchoolYear = $teacherSection->school_year;

            // Get all school days for the current school year
            $schoolYearDaysQuery = Attendance::query()
                ->join('sections', 'attendances.section_id', '=', 'sections.id')
                ->where('sections.adviser_id', Auth::id())
                ->where('sections.school_year', $currentSchoolYear)
                ->select('date')
                ->distinct();

            $schoolDaysForYear = $schoolYearDaysQuery->count();
            $schoolDayDates = $schoolYearDaysQuery->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('Y-m-d');
            })->toArray();
        }

        // Get current month and week summary data
        $currentMonthSummary = null;
        $currentWeekSummary = null;

        // Only calculate summaries if there are attendance records
        if (count($attendances) > 0) {
            $sectionId = $request->filled('section_id') ? $request->section_id : null;
            $monthParam = $request->filled('month') ? $request->month : Carbon::now()->format('Y-m');

            // Get monthly summary if month filter is applied
            if ($request->filled('month')) {
                $currentMonthSummary = $this->attendanceSummaryService->getMonthlySummary(
                    Auth::id(),
                    $monthParam,
                    $sectionId
                );
            }

            // Get weekly summary if we're viewing the current month or no month filter
            if (!$request->filled('month') || $monthParam === Carbon::now()->format('Y-m')) {
                $currentWeekSummary = $this->attendanceSummaryService->getWeeklySummary(
                    Auth::id(),
                    $sectionId
                );
            }
        }

        return view('teacher.attendances.index', compact(
            'attendances',
            'sections',
            'availableMonths',
            'currentMonthSummary',
            'currentWeekSummary',
            'schoolDays',
            'currentMonth',
            'currentSchoolYear',
            'schoolDaysForYear',
            'schoolDayDates'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::where('adviser_id', Auth::id())->get();

        // Calculate the number of school days (unique dates with attendance records)
        $schoolDays = Attendance::join('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id())
            ->whereMonth('attendances.date', Carbon::now()->month)
            ->whereYear('attendances.date', Carbon::now()->year)
            ->select(DB::raw('COUNT(DISTINCT date) as count'))
            ->first()
            ->count;

        $currentMonth = Carbon::now()->format('F Y');

        return view('teacher.attendances.create', compact('sections', 'schoolDays', 'currentMonth'));
    }

    /**
     * Check if attendance exists for a given date and section
     */
    public function checkAttendanceExists(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
        ]);

        // Check if the section belongs to this teacher
        $section = Section::where('id', $request->section_id)
                          ->where('adviser_id', Auth::id())
                          ->first();

        if (!$section) {
            return response()->json([
                'exists' => false,
                'message' => 'Section not found or does not belong to this teacher.'
            ]);
        }

        // Check if attendance for this date and section already exists
        $existingAttendance = Attendance::where('section_id', $request->section_id)
                                        ->where('date', $request->date)
                                        ->where('teacher_id', Auth::id())
                                        ->first();

        if ($existingAttendance) {
            return response()->json([
                'exists' => true,
                'message' => 'Attendance for this date and section already exists.',
                'edit_url' => route('teacher.attendances.edit', ['attendance' => $request->section_id, 'date' => $request->date])
            ]);
        }

        return response()->json([
            'exists' => false,
            'message' => 'No attendance record exists for this date and section.'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,excused,half_day',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string|max:255',
        ]);

        // Check if the section belongs to this teacher
        $section = Section::where('id', $validated['section_id'])
                          ->where('adviser_id', Auth::id())
                          ->firstOrFail();

        // Check if attendance for this date and section already exists
        $existingAttendance = Attendance::where('section_id', $validated['section_id'])
                                        ->where('date', $validated['date'])
                                        ->where('teacher_id', Auth::id())
                                        ->exists();

        if ($existingAttendance) {
            $editUrl = route('teacher.attendances.edit', ['attendance' => $validated['section_id'], 'date' => $validated['date']]);
            return redirect()->back()->with('error', "Attendance for this date and section already exists. <a href='{$editUrl}' class='alert-link'>Click here to edit the existing record</a>.");
        }

        // Begin transaction
        DB::beginTransaction();

        try {
            // Create individual attendance records for each student
            foreach ($validated['attendance'] as $studentId => $status) {
                // Verify student belongs to the section
                $student = Student::where('id', $studentId)
                                  ->where('section_id', $validated['section_id'])
                                  ->firstOrFail();

                // Get the student's subject ID (assuming they only have one subject in this section)
                $subject = $section->subjects->first();
                $subjectId = $subject ? $subject->id : null;

                if (!$subjectId) {
                    throw new \Exception('No subject found for this section');
                }

                // Get remarks if status is excused
                $remarks = null;
                if ($status === 'excused' && isset($validated['remarks'][$studentId])) {
                    $remarks = $validated['remarks'][$studentId];
                }

                // Create individual attendance record
                Attendance::create([
                    'student_id' => $studentId,
                    'section_id' => $validated['section_id'],
                    'teacher_id' => Auth::id(),
                    'subject_id' => $subjectId,
                    'date' => $validated['date'],
                    'status' => $status,
                    'remarks' => $remarks
                ]);
            }

            DB::commit();
            return redirect()->route('teacher.attendances.index')
                             ->with('success', 'Attendance recorded successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'An error occurred while recording attendance: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $date = $request->query('date');

        if (!$date) {
            return redirect()->route('teacher.attendances.index')
                             ->with('error', 'Date parameter is required.');
        }

        $section = Section::where('id', $id)
                          ->where('adviser_id', Auth::id())
                          ->firstOrFail();

        $students = Student::where('section_id', $id)->get();

        // Get attendance data for all students in this section on this date for this teacher
        $attendanceRecords = Attendance::where('section_id', $id)
                                      ->where('date', $date)
                                      ->where('teacher_id', Auth::id())
                                      ->get();

        // Map attendance data by student ID
        $attendanceData = [];
        foreach ($students as $student) {
            $attendanceData[$student->id] = 'absent'; // Default to absent
        }

        $presentCount = 0;
        $lateCount = 0;
        $excusedCount = 0;
        $halfDayCount = 0;
        foreach ($attendanceRecords as $record) {
            $attendanceData[$record->student_id] = $record->status;
            if ($record->status === 'present') {
                $presentCount++;
            } elseif ($record->status === 'late') {
                $lateCount++;
            } elseif ($record->status === 'excused') {
                $excusedCount++;
            } elseif ($record->status === 'half_day') {
                $halfDayCount++;
            }
        }

        $absentCount = count($students) - ($presentCount + $lateCount + $excusedCount + $halfDayCount);

        // Calculate the number of school days (unique dates with attendance records)
        $schoolDays = Attendance::join('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id())
            ->whereMonth('attendances.date', Carbon::parse($date)->month)
            ->whereYear('attendances.date', Carbon::parse($date)->year)
            ->select(DB::raw('COUNT(DISTINCT date) as count'))
            ->first()
            ->count;

        $currentMonth = Carbon::parse($date)->format('F Y');

        return view('teacher.attendances.show', compact('section', 'students', 'attendanceData', 'attendanceRemarks', 'date', 'presentCount', 'lateCount', 'absentCount', 'excusedCount', 'halfDayCount', 'schoolDays', 'currentMonth'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $date = $request->query('date');

        if (!$date) {
            return redirect()->route('teacher.attendances.index')
                             ->with('error', 'Date parameter is required.');
        }

        $section = Section::where('id', $id)
                          ->where('adviser_id', Auth::id())
                          ->firstOrFail();

        $students = Student::where('section_id', $id)->get();

        // Get attendance data for all students in this section on this date for this teacher
        $attendanceRecords = Attendance::where('section_id', $id)
                                      ->where('date', $date)
                                      ->where('teacher_id', Auth::id())
                                      ->get();

        // Map attendance data by student ID
        $attendanceData = [];
        $attendanceRemarks = [];
        foreach ($students as $student) {
            $attendanceData[$student->id] = 'absent'; // Default to absent
            $attendanceRemarks[$student->id] = null;
        }

        foreach ($attendanceRecords as $record) {
            $attendanceData[$record->student_id] = $record->status;
            $attendanceRemarks[$record->student_id] = $record->remarks;
        }

        // Calculate the number of school days (unique dates with attendance records)
        $schoolDays = Attendance::join('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id())
            ->whereMonth('attendances.date', Carbon::parse($date)->month)
            ->whereYear('attendances.date', Carbon::parse($date)->year)
            ->select(DB::raw('COUNT(DISTINCT date) as count'))
            ->first()
            ->count;

        $currentMonth = Carbon::parse($date)->format('F Y');

        return view('teacher.attendances.edit', compact('section', 'students', 'attendanceData', 'attendanceRemarks', 'date', 'schoolDays', 'currentMonth'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'required|in:present,absent,late,excused,half_day',
            'remarks' => 'nullable|array',
            'remarks.*' => 'nullable|string|max:255',
        ]);

        // Check if the section belongs to this teacher
        $section = Section::where('id', $validated['section_id'])
                          ->where('adviser_id', Auth::id())
                          ->firstOrFail();

        // Begin transaction
        DB::beginTransaction();

        try {
            // Delete existing attendance records for this section and date and teacher
            Attendance::where('section_id', $validated['section_id'])
                      ->where('date', $validated['date'])
                      ->where('teacher_id', Auth::id())
                      ->delete();

            // Get a subject for the section
            $subject = $section->subjects->first();
            $subjectId = $subject ? $subject->id : null;

            if (!$subjectId) {
                throw new \Exception('No subject found for this section');
            }

            // Create new attendance records for each student
            foreach ($validated['attendance'] as $studentId => $status) {
                // Verify student belongs to the section
                $student = Student::where('id', $studentId)
                                  ->where('section_id', $validated['section_id'])
                                  ->firstOrFail();

                // Get remarks if status is excused
                $remarks = null;
                if ($status === 'excused' && isset($validated['remarks'][$studentId])) {
                    $remarks = $validated['remarks'][$studentId];
                }

                // Create the attendance record
                Attendance::create([
                    'student_id' => $studentId,
                    'section_id' => $validated['section_id'],
                    'teacher_id' => Auth::id(),
                    'subject_id' => $subjectId,
                    'date' => $validated['date'],
                    'status' => $status,
                    'remarks' => $remarks
                ]);
            }

            DB::commit();
            return redirect()->route('teacher.attendances.index')
                             ->with('success', 'Attendance updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                             ->with('error', 'An error occurred while updating attendance: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Not implemented for attendance
        return redirect()->route('teacher.attendances.index')
                         ->with('error', 'Attendance deletion is not supported.');
    }

    /**
     * Display weekly attendance summary
     */
    public function weeklySummary(Request $request)
    {
        $sectionId = $request->filled('section_id') ? $request->section_id : null;

        // Get sections where the teacher is the adviser
        $sections = Section::where('adviser_id', Auth::id())->get();

        // Get weekly attendance summary
        $summary = $this->attendanceSummaryService->getWeeklySummary(
            Auth::id(),
            $sectionId
        );

        // Calculate the number of school days for the current week
        $schoolDaysQuery = Attendance::query()
            ->join('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id())
            ->whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);

        // Apply section filter if provided
        if ($sectionId) {
            $schoolDaysQuery->where('attendances.section_id', $sectionId);
        }

        $schoolDays = $schoolDaysQuery->select(DB::raw('COUNT(DISTINCT date) as count'))->first()->count;
        $currentMonth = Carbon::now()->startOfWeek()->format('M d') . ' - ' . Carbon::now()->endOfWeek()->format('M d');

        return view('teacher.attendances.weekly_summary', compact('summary', 'sections', 'sectionId', 'schoolDays', 'currentMonth'));
    }

    /**
     * Display monthly attendance summary
     */
    public function monthlySummary(Request $request)
    {
        $sectionId = $request->filled('section_id') ? $request->section_id : null;
        $yearMonth = $request->filled('month') ? $request->month : Carbon::now()->format('Y-m');

        // Get sections where the teacher is the adviser
        $sections = Section::where('adviser_id', Auth::id())->get();

        // Get available months for the dropdown
        $availableMonths = Attendance::join('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id())
            ->select(DB::raw('DISTINCT DATE_FORMAT(date, "%Y-%m") as month_value, DATE_FORMAT(date, "%M %Y") as month_name'))
            ->orderBy('month_value', 'desc')
            ->get();

        // Get monthly attendance summary
        $summary = $this->attendanceSummaryService->getMonthlySummary(
            Auth::id(),
            $yearMonth,
            $sectionId
        );

        // Calculate the number of school days for the selected month
        $schoolDaysQuery = Attendance::query()
            ->join('sections', 'attendances.section_id', '=', 'sections.id')
            ->where('sections.adviser_id', Auth::id());

        // Apply section filter if provided
        if ($sectionId) {
            $schoolDaysQuery->where('attendances.section_id', $sectionId);
        }

        // Apply month filter
        $monthYear = explode('-', $yearMonth);
        if (count($monthYear) === 2) {
            $year = $monthYear[0];
            $month = $monthYear[1];
            $schoolDaysQuery->whereYear('attendances.date', $year)
                  ->whereMonth('attendances.date', $month);
        }

        $schoolDays = $schoolDaysQuery->select(DB::raw('COUNT(DISTINCT date) as count'))->first()->count;
        $currentMonth = Carbon::createFromFormat('Y-m', $yearMonth)->format('F Y');

        return view('teacher.attendances.monthly_summary', compact('summary', 'sections', 'sectionId', 'yearMonth', 'availableMonths', 'schoolDays', 'currentMonth'));
    }
}
