<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class EmergencyFixSeeder extends Seeder
{
    /**
     * Run the database seeds to fix the specific issue shown in the screenshot
     */
    public function run(): void
    {
        $this->command->info("Starting emergency fix seeder...");
        
        // Hardcoded values from screenshots
        $teacherId = 2; // Jennifer S. Bagas
        $sectionId = 1; // St. Raphael section
        $subjectId = 1; // English subject
        
        $this->command->info("Checking for teacher ID $teacherId, section ID $sectionId, subject ID $subjectId");
        
        // Check if entries exist
        $teacherExists = DB::table('users')->where('id', $teacherId)->exists();
        $sectionExists = DB::table('sections')->where('id', $sectionId)->exists();
        $subjectExists = DB::table('subjects')->where('id', $subjectId)->exists();
        
        if (!$teacherExists) {
            $this->command->error("Teacher with ID $teacherId does not exist!");
            return;
        }
        
        if (!$sectionExists) {
            $this->command->error("Section with ID $sectionId does not exist!");
            return;
        }
        
        if (!$subjectExists) {
            $this->command->error("Subject with ID $subjectId does not exist!");
            return;
        }
        
        $this->command->info("All required entities exist. Checking section_subject relationship...");
        
        // Check if assignment already exists
        $exists = DB::table('section_subject')
            ->where('section_id', $sectionId)
            ->where('subject_id', $subjectId)
            ->where('teacher_id', $teacherId)
            ->exists();
            
        if ($exists) {
            $this->command->info("Section-subject assignment already exists.");
            
            // Force update assignment with current timestamp to ensure it's refreshed
            DB::table('section_subject')
                ->where('section_id', $sectionId)
                ->where('subject_id', $subjectId)
                ->where('teacher_id', $teacherId)
                ->update([
                    'updated_at' => now()
                ]);
                
            $this->command->info("Updated existing assignment timestamp.");
        } else {
            // Create the assignment
            $assignment = [
                'section_id' => $sectionId,
                'subject_id' => $subjectId,
                'teacher_id' => $teacherId,
                'created_at' => now(),
                'updated_at' => now()
            ];
            
            DB::table('section_subject')->insert($assignment);
            $this->command->info("Created new section_subject assignment.");
        }
        
        // Ensure section has teacher as adviser
        $section = DB::table('sections')->where('id', $sectionId)->first();
        if ($section && $section->adviser_id != $teacherId) {
            DB::table('sections')
                ->where('id', $sectionId)
                ->update([
                    'adviser_id' => $teacherId
                ]);
            $this->command->info("Updated section to have teacher $teacherId as adviser.");
        }
        
        // Clear cache
        $this->command->info("Clearing cache to ensure changes take effect...");
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        
        // Log the completion
        Log::info('Applied emergency fix to ensure subject shows', [
            'section_id' => $sectionId,
            'subject_id' => $subjectId,
            'teacher_id' => $teacherId
        ]);
        
        $this->command->info("Emergency fix completed successfully!");
    }
} 