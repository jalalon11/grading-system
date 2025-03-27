<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if section 1 exists (St. Raphael)
        $sectionExists = DB::table('sections')->where('id', 1)->exists();
        
        if (!$sectionExists) {
            $this->command->error("Section with ID 1 does not exist!");
            return;
        }
        
        // Add a test student to section 1
        DB::table('students')->insert([
            'first_name' => 'John',
            'last_name' => 'Smith',
            'gender' => 'Male',
            'birth_date' => '2010-01-01',
            'student_id' => 'S2023-001',
            'section_id' => 1, // St. Raphael
            'guardian_name' => 'Parent Smith',
            'guardian_contact' => '123-456-7890',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        $this->command->info("Test student added to section 1 (St. Raphael)");
    }
} 