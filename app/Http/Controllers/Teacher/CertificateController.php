<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CertificateController extends Controller
{
    public function index()
    {
        $teacher = Auth::user();
        
        // Get sections where user is adviser
        $advisedSections = $teacher->sections()->pluck('id');
        
        // Get sections where user teaches subjects
        $taughtSections = DB::table('section_subject')
            ->where('teacher_id', $teacher->id)
            ->pluck('section_id');
        
        // Combine both sets of sections
        $sectionIds = $advisedSections->merge($taughtSections)->unique();
        
        // Get students from all relevant sections
        $students = Student::whereIn('section_id', $sectionIds)
            ->with('certificates') // Eager load certificates
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        
        return view('teacher.certificates.index', compact('students'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'academic_year' => 'required|string',
            'semester' => 'required|string',
        ]);

        $student = Student::findOrFail($request->student_id);
        
        // Calculate average grade
        $averageGrade = Grade::where('student_id', $student->id)
            ->where('academic_year', $request->academic_year)
            ->where('semester', $request->semester)
            ->avg('score');

        if (!$averageGrade) {
            return back()->with('error', 'No grades found for this student in the specified period.');
        }

        // Determine honor type
        $honorType = Certificate::determineHonorType($averageGrade);

        if (!$honorType) {
            return back()->with('error', 'Student does not qualify for honors certificate.');
        }

        // Create certificate
        Certificate::create([
            'student_id' => $student->id,
            'academic_year' => $request->academic_year,
            'semester' => $request->semester,
            'average_grade' => $averageGrade,
            'honor_type' => $honorType,
            'issued_date' => now(),
            'issued_by' => Auth::id(),
        ]);

        return back()->with('success', 'Certificate generated successfully.');
    }

    public function show(Certificate $certificate)
    {
        return view('teacher.certificates.show', compact('certificate'));
    }

    public function download(Certificate $certificate)
    {
        // TODO: Implement PDF generation and download
        return response()->download($certificate->generatePdf());
    }
} 