<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SectionSubjectSeeder extends Seeder
{
    /**
     * Run the database seeds to fix missing section_subject assignments
     */
    public function run(): void
    {
        // Get the current user ID if running as an authenticated user
        $teacherId = 2; // Hard-coded to match the ID in your screenshot
        
        // Get data from existing tables
        $sections = DB::table('sections')->get();
        $subjects = DB::table('subjects')->get();
        
        if ($sections->isEmpty()) {
            $this->command->error('No sections found. Please create sections first.');
            return;
        }
        
        if ($subjects->isEmpty()) {
            $this->command->error('No subjects found. Please create subjects first.');
            return;
        }
        
        // For this specific case, we're directly creating an assignment
        // using the English subject with ID 1 as shown in the PHPMyAdmin screenshot
        $assignment = [
            'section_id' => 1, // Section ID from screenshot
            'subject_id' => 1, // Subject ID (English) from screenshot
            'teacher_id' => $teacherId, // Teacher ID from screenshot
            'created_at' => now(),
            'updated_at' => now()
        ];
        
        // Check if this assignment already exists
        $exists = DB::table('section_subject')
            ->where('section_id', $assignment['section_id'])
            ->where('subject_id', $assignment['subject_id'])
            ->where('teacher_id', $assignment['teacher_id'])
            ->exists();
            
        if ($exists) {
            $this->command->info('Assignment already exists');
            return;
        }
        
        // Insert the assignment
        DB::table('section_subject')->insert($assignment);
        
        $subjectName = $subjects->where('id', $assignment['subject_id'])->first()->name ?? 'Unknown';
        $sectionName = $sections->where('id', $assignment['section_id'])->first()->name ?? 'Unknown';
        
        $this->command->info("Successfully created section_subject assignment:");
        $this->command->info("- Section: {$sectionName} (ID: {$assignment['section_id']})");
        $this->command->info("- Subject: {$subjectName} (ID: {$assignment['subject_id']})");
        $this->command->info("- Teacher ID: {$assignment['teacher_id']}");
        
        // Log the assignment
        Log::info('Created section_subject assignment via seeder', [
            'section_id' => $assignment['section_id'],
            'subject_id' => $assignment['subject_id'],
            'teacher_id' => $assignment['teacher_id']
        ]);
    }
} 