<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Section;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
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
                    'excused_count' => 0
                ];
            }
            
            if ($record->status === 'present') {
                $attendances[$date][$sectionId]['present_count']++;
            } elseif ($record->status === 'late') {
                $attendances[$date][$sectionId]['late_count']++;
            } elseif ($record->status === 'excused') {
                $attendances[$date][$sectionId]['excused_count']++;
            } else {
                $attendances[$date][$sectionId]['absent_count']++;
            }
        }
        
        return view('teacher.attendances.index', compact('attendances', 'sections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::where('adviser_id', Auth::id())->get();
        return view('teacher.attendances.create', compact('sections'));
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
            'attendance.*' => 'required|in:present,absent,late,excused',
        ]);
        
        // Check if the section belongs to this teacher
        $section = Section::where('id', $validated['section_id'])
                          ->where('adviser_id', Auth::id())
                          ->firstOrFail();
        
        // Check if attendance for this date and section already exists
        $existingAttendance = Attendance::where('section_id', $validated['section_id'])
                                        ->where('date', $validated['date'])
                                        ->exists();
        
        if ($existingAttendance) {
            return redirect()->back()->with('error', 'Attendance for this date and section already exists. Please edit the existing record.');
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
                
                // Create individual attendance record
                Attendance::create([
                    'student_id' => $studentId,
                    'section_id' => $validated['section_id'],
                    'teacher_id' => Auth::id(),
                    'subject_id' => $subjectId,
                    'date' => $validated['date'],
                    'status' => $status,
                    'remarks' => null
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
        
        // Get attendance data for all students in this section on this date
        $attendanceRecords = Attendance::where('section_id', $id)
                                      ->where('date', $date)
                                      ->get();
        
        // Map attendance data by student ID
        $attendanceData = [];
        foreach ($students as $student) {
            $attendanceData[$student->id] = 'absent'; // Default to absent
        }
        
        $presentCount = 0;
        $lateCount = 0;
        $excusedCount = 0;
        foreach ($attendanceRecords as $record) {
            $attendanceData[$record->student_id] = $record->status;
            if ($record->status === 'present') {
                $presentCount++;
            } elseif ($record->status === 'late') {
                $lateCount++;
            } elseif ($record->status === 'excused') {
                $excusedCount++;
            }
        }
        
        $absentCount = count($students) - ($presentCount + $lateCount + $excusedCount);
        
        return view('teacher.attendances.show', compact('section', 'students', 'attendanceData', 'date', 'presentCount', 'lateCount', 'absentCount', 'excusedCount'));
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
        
        // Get attendance data for all students in this section on this date
        $attendanceRecords = Attendance::where('section_id', $id)
                                      ->where('date', $date)
                                      ->get();
        
        // Map attendance data by student ID
        $attendanceData = [];
        foreach ($students as $student) {
            $attendanceData[$student->id] = 'absent'; // Default to absent
        }
        
        foreach ($attendanceRecords as $record) {
            $attendanceData[$record->student_id] = $record->status;
        }
        
        return view('teacher.attendances.edit', compact('section', 'students', 'attendanceData', 'date'));
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
            'attendance.*' => 'required|in:present,absent,late,excused',
        ]);
        
        // Check if the section belongs to this teacher
        $section = Section::where('id', $validated['section_id'])
                          ->where('adviser_id', Auth::id())
                          ->firstOrFail();
        
        // Begin transaction
        DB::beginTransaction();
        
        try {
            // Delete existing attendance records for this section and date
            Attendance::where('section_id', $validated['section_id'])
                      ->where('date', $validated['date'])
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
                
                // Create the attendance record
                Attendance::create([
                    'student_id' => $studentId,
                    'section_id' => $validated['section_id'],
                    'teacher_id' => Auth::id(),
                    'subject_id' => $subjectId,
                    'date' => $validated['date'],
                    'status' => $status,
                    'remarks' => null
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
}
