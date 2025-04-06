<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GradeApproval;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GradeApprovalController extends Controller
{
    /**
     * Display a listing of the grade approvals.
     */
    public function index()
    {
        $teacher = Auth::user();

        // Get sections and subjects where this teacher teaches
        $teachingAssignments = DB::table('section_subject')
            ->where('teacher_id', $teacher->id)
            ->join('sections', 'section_subject.section_id', '=', 'sections.id')
            ->join('subjects', 'section_subject.subject_id', '=', 'subjects.id')
            ->select(
                'sections.id as section_id',
                'sections.name as section_name',
                'sections.grade_level',
                'subjects.id as subject_id',
                'subjects.name as subject_name'
            )
            ->orderBy('sections.name')
            ->orderBy('subjects.name')
            ->get();

        // Get existing approvals for this teacher
        $approvals = GradeApproval::where('teacher_id', $teacher->id)
                                 ->get()
                                 ->keyBy(function($item) {
                                     return $item->section_id . '-' . $item->subject_id . '-' . $item->quarter;
                                 });

        $quarters = [
            'Q1' => '1st Quarter',
            'Q2' => '2nd Quarter',
            'Q3' => '3rd Quarter',
            'Q4' => '4th Quarter'
        ];

        return view('teacher.grade_approvals.index', compact('teachingAssignments', 'approvals', 'quarters'));
    }

    /**
     * Update the approval status.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'section_id' => 'required|exists:sections,id',
            'subject_id' => 'required|exists:subjects,id',
            'quarter' => 'required|in:Q1,Q2,Q3,Q4',
            'is_approved' => 'required|boolean',
            'notes' => 'nullable|string|max:255',
        ]);

        $teacher = Auth::user();

        // Check if the teacher is assigned to this section-subject
        $isAssigned = DB::table('section_subject')
            ->where('teacher_id', $teacher->id)
            ->where('section_id', $validated['section_id'])
            ->where('subject_id', $validated['subject_id'])
            ->exists();

        if (!$isAssigned) {
            return redirect()->back()->with('error', 'You are not authorized to approve grades for this subject in this section.');
        }

        // Update or create the approval record
        GradeApproval::updateOrCreate(
            [
                'teacher_id' => $teacher->id,
                'section_id' => $validated['section_id'],
                'subject_id' => $validated['subject_id'],
                'quarter' => $validated['quarter'],
            ],
            [
                'is_approved' => $validated['is_approved'],
                'notes' => $request->input('notes', null), // Make notes optional
            ]
        );

        $status = $validated['is_approved'] ? 'approved' : 'hidden';

        return redirect()->route('teacher.grade-approvals.index')
            ->with('success', "Grades for this subject have been {$status} successfully.");
    }
}
