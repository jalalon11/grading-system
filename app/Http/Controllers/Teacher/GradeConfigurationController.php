<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\GradeConfiguration;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GradeConfigurationController extends Controller
{
    /**
     * Show the form for editing the grade configuration for a subject.
     */
    public function edit(string $subjectId)
    {
        $teacher = Auth::user();
        
        // Check if the teacher owns the subject
        $subject = Subject::where('id', $subjectId)
            ->where('user_id', $teacher->id)
            ->with('section')
            ->firstOrFail();
        
        // Get or create grade configuration
        $gradeConfig = $subject->gradeConfiguration ?? GradeConfiguration::create([
            'subject_id' => $subject->id,
            'written_work_percentage' => 25.00,
            'performance_task_percentage' => 50.00,
            'quarterly_assessment_percentage' => 25.00,
        ]);
        
        return view('teacher.grade-configurations.edit', compact('subject', 'gradeConfig'));
    }

    /**
     * Update the specified grade configuration.
     */
    public function update(Request $request, string $subjectId)
    {
        $request->validate([
            'written_work_percentage' => 'required|numeric|min:0|max:100',
            'performance_task_percentage' => 'required|numeric|min:0|max:100',
            'quarterly_assessment_percentage' => 'required|numeric|min:0|max:100',
        ]);
        
        // Calculate total to verify it equals 100%
        $total = $request->written_work_percentage + $request->performance_task_percentage + $request->quarterly_assessment_percentage;
        
        if (round($total, 2) != 100.00) {
            return back()->withErrors(['percentage_total' => 'The total of all percentages must equal 100%.'])->withInput();
        }
        
        $teacher = Auth::user();
        
        // Check if the teacher owns the subject
        $subject = Subject::where('id', $subjectId)
            ->where('user_id', $teacher->id)
            ->firstOrFail();
        
        // Update or create the grade configuration
        $gradeConfig = $subject->gradeConfiguration ?? new GradeConfiguration(['subject_id' => $subject->id]);
        
        $gradeConfig->written_work_percentage = $request->written_work_percentage;
        $gradeConfig->performance_task_percentage = $request->performance_task_percentage;
        $gradeConfig->quarterly_assessment_percentage = $request->quarterly_assessment_percentage;
        $gradeConfig->save();
        
        return redirect()->route('teacher.subjects.show', $subject->id)
            ->with('success', 'Grade configuration updated successfully.');
    }
}
