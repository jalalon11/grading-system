<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Subject;
use App\Models\Section;
use App\Models\User;

class FixSectionSubjectTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fix-section-subject-table {teacher_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the section_subject table by adding missing entries';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking section_subject table...');
        
        // Count existing records
        $existingCount = DB::table('section_subject')->count();
        $this->info("Found {$existingCount} existing records in section_subject table");
        
        // Get teacher ID from argument or ask for it
        $teacherId = $this->argument('teacher_id');
        if (!$teacherId) {
            $teacherIdFromInput = $this->ask('Enter the ID of the teacher to assign subjects to (leave empty to show all teachers):');
            if ($teacherIdFromInput) {
                $teacherId = $teacherIdFromInput;
            }
        }
        
        // Check if teacher exists
        if ($teacherId) {
            $teacher = User::where('id', $teacherId)->where('role', 'teacher')->first();
            if (!$teacher) {
                $this->error("Teacher with ID {$teacherId} not found or is not a teacher");
                return 1;
            }
            
            $this->info("Teacher: {$teacher->name} (ID: {$teacher->id})");
        } else {
            // List all teachers for selection
            $teachers = User::where('role', 'teacher')->get();
            if ($teachers->isEmpty()) {
                $this->error('No teachers found in the system');
                return 1;
            }
            
            $this->info('Available teachers:');
            foreach ($teachers as $t) {
                $this->line("ID: {$t->id}, Name: {$t->name}");
            }
            
            $teacherId = $this->ask('Enter the ID of the teacher to assign subjects to:');
            $teacher = $teachers->firstWhere('id', $teacherId);
            if (!$teacher) {
                $this->error("Invalid teacher ID selected");
                return 1;
            }
        }
        
        // Get sections
        $sections = Section::all();
        if ($sections->isEmpty()) {
            $this->error('No sections found. Please create sections first.');
            return 1;
        }
        
        // Get subject
        $subjects = Subject::all();
        if ($subjects->isEmpty()) {
            $this->error('No subjects found. Please create subjects first.');
            return 1;
        }
        
        // List sections and subjects for selection
        $this->info('Available sections:');
        foreach ($sections as $index => $section) {
            $this->line("{$index}: ID: {$section->id}, Name: {$section->name}, Grade: {$section->grade_level}");
        }
        
        $sectionIndex = $this->ask('Enter the index of the section to assign:');
        if (!isset($sections[$sectionIndex])) {
            $this->error('Invalid section selection');
            return 1;
        }
        $selectedSection = $sections[$sectionIndex];
        
        $this->info('Available subjects:');
        foreach ($subjects as $index => $subject) {
            $this->line("{$index}: ID: {$subject->id}, Name: {$subject->name}, Code: {$subject->code}");
        }
        
        $subjectIndex = $this->ask('Enter the index of the subject to assign:');
        if (!isset($subjects[$subjectIndex])) {
            $this->error('Invalid subject selection');
            return 1;
        }
        $selectedSubject = $subjects[$subjectIndex];
        
        // Check if assignment already exists
        $exists = DB::table('section_subject')
            ->where('section_id', $selectedSection->id)
            ->where('subject_id', $selectedSubject->id)
            ->where('teacher_id', $teacherId)
            ->exists();
            
        if ($exists) {
            $this->info("Assignment already exists for section '{$selectedSection->name}', subject '{$selectedSubject->name}', and teacher ID {$teacherId}");
            return 0;
        }
        
        // Create the assignment
        $assignment = [
            'section_id' => $selectedSection->id,
            'subject_id' => $selectedSubject->id,
            'teacher_id' => $teacherId,
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        DB::table('section_subject')->insert($assignment);
        
        $this->info("Successfully assigned subject '{$selectedSubject->name}' to section '{$selectedSection->name}' with teacher ID {$teacherId}");
        
        // Log the new assignment
        Log::info('Created section_subject assignment', [
            'section_id' => $selectedSection->id, 
            'section_name' => $selectedSection->name,
            'subject_id' => $selectedSubject->id,
            'subject_name' => $selectedSubject->name,
            'teacher_id' => $teacherId
        ]);
        
        return 0;
    }
} 