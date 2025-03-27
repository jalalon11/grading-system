<?php

namespace Database\Seeders;

use App\Models\GradeLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradeLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $gradeLevels = [
            [
                'name' => 'Kindergarten',
                'code' => 'K',
                'description' => 'Kindergarten level for early childhood education',
            ],
            [
                'name' => 'Grade 1',
                'code' => 'G1',
                'description' => 'Elementary School - Grade 1',
            ],
            [
                'name' => 'Grade 2',
                'code' => 'G2',
                'description' => 'Elementary School - Grade 2',
            ],
            [
                'name' => 'Grade 3',
                'code' => 'G3',
                'description' => 'Elementary School - Grade 3',
            ],
            [
                'name' => 'Grade 4',
                'code' => 'G4',
                'description' => 'Elementary School - Grade 4',
            ],
            [
                'name' => 'Grade 5',
                'code' => 'G5',
                'description' => 'Elementary School - Grade 5',
            ],
            [
                'name' => 'Grade 6',
                'code' => 'G6',
                'description' => 'Elementary School - Grade 6',
            ],
            [
                'name' => 'Grade 7',
                'code' => 'G7',
                'description' => 'Junior High School - Grade 7',
            ],
            [
                'name' => 'Grade 8',
                'code' => 'G8',
                'description' => 'Junior High School - Grade 8',
            ],
            [
                'name' => 'Grade 9',
                'code' => 'G9',
                'description' => 'Junior High School - Grade 9',
            ],
            [
                'name' => 'Grade 10',
                'code' => 'G10',
                'description' => 'Junior High School - Grade 10',
            ],
            [
                'name' => 'Grade 11',
                'code' => 'G11',
                'description' => 'Senior High School - Grade 11',
            ],
            [
                'name' => 'Grade 12',
                'code' => 'G12',
                'description' => 'Senior High School - Grade 12',
            ],
        ];

        foreach ($gradeLevels as $gradeLevel) {
            GradeLevel::create($gradeLevel);
        }
    }
}
