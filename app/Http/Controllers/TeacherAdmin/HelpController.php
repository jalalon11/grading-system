<?php

namespace App\Http\Controllers\TeacherAdmin;

use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    /**
     * Display the teacher admin help index page.
     */
    public function index()
    {
        return view('teacher_admin.help.index');
    }

    /**
     * Display specific tutorial page for teacher admins.
     */
    public function tutorial($topic)
    {
        // Validate that the requested tutorial exists
        $validTopics = [
            'school',
            'sections',
            'subjects',
            'reports',
            'payments',
            'faq',
            'support',
            'registration_keys'
        ];

        if (!in_array($topic, $validTopics)) {
            return redirect()->route('teacher-admin.help.index')
                ->with('error', 'The requested tutorial does not exist.');
        }

        return view("teacher_admin.help.tutorials.{$topic}");
    }
}
