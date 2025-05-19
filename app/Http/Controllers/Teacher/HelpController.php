<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;

class HelpController extends Controller
{
    /**
     * Display the teacher help index page.
     */
    public function index()
    {
        return view('teacher.help.index');
    }

    /**
     * Display specific tutorial page for teachers.
     */
    public function tutorial($topic)
    {
        // Validate that the requested tutorial exists
        $validTopics = [
            'students',
            'grades',
            'attendance',
            'reports',
            'resources',
            'faq'
        ];

        if (!in_array($topic, $validTopics)) {
            return redirect()->route('teacher.help.index')
                ->with('error', 'The requested tutorial does not exist.');
        }

        return view("teacher.help.tutorials.{$topic}");
    }
}
