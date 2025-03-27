<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Disable foreign key checks to avoid constraint issues
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            
            // First check if the subjects table exists
            if (!Schema::hasTable('subjects')) {
                $this->createSubjectsTable();
            } else {
                $this->updateSubjectsTable();
            }

            // Create or update the section_subject pivot table
            $this->ensureSectionSubjectTableExists();
            
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            Log::info('Subjects table updated successfully');
        } catch (\Exception $e) {
            // Re-enable foreign key checks in case of exception
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
            
            Log::error('Error updating subjects table: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback is implemented as this is an update migration
    }

    /**
     * Create the subjects table if it doesn't exist
     */
    private function createSubjectsTable(): void
    {
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->string('grade_level')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Log::info('Created new subjects table');
    }

    /**
     * Update the existing subjects table
     */
    private function updateSubjectsTable(): void
    {
        // Check for and handle foreign key constraints
        $this->handleForeignKeyConstraints();
        
        Schema::table('subjects', function (Blueprint $table) {
            // Add columns if they don't exist
            if (!Schema::hasColumn('subjects', 'code')) {
                $table->string('code')->nullable();
            } else {
                // Make sure code is nullable
                DB::statement('ALTER TABLE subjects MODIFY code VARCHAR(255) NULL');
            }
            
            if (!Schema::hasColumn('subjects', 'grade_level')) {
                $table->string('grade_level')->nullable();
            }
            
            if (!Schema::hasColumn('subjects', 'description')) {
                $table->text('description')->nullable();
            }
            
            if (!Schema::hasColumn('subjects', 'school_id')) {
                $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            }
            
            if (!Schema::hasColumn('subjects', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
        
        Log::info('Updated existing subjects table');
    }
    
    /**
     * Handle foreign key constraints on the subjects table
     */
    private function handleForeignKeyConstraints(): void
    {
        try {
            // Get all foreign key constraints on the subjects table
            $constraintsOnSubjects = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.TABLE_CONSTRAINTS
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = 'subjects'
                AND CONSTRAINT_TYPE = 'FOREIGN KEY'
            ");
            
            foreach ($constraintsOnSubjects as $constraint) {
                $constraintName = $constraint->CONSTRAINT_NAME;
                
                // Check if this is section_id constraint
                $columnInfo = DB::select("
                    SELECT COLUMN_NAME
                    FROM information_schema.KEY_COLUMN_USAGE
                    WHERE CONSTRAINT_NAME = ?
                    AND TABLE_NAME = 'subjects'
                ", [$constraintName]);
                
                if (!empty($columnInfo) && $columnInfo[0]->COLUMN_NAME === 'section_id') {
                    // Drop this constraint as sections table may not exist yet
                    DB::statement("
                        ALTER TABLE subjects
                        DROP FOREIGN KEY {$constraintName}
                    ");
                    
                    Log::info("Dropped foreign key {$constraintName} from subjects table");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error handling foreign key constraints on subjects table: " . $e->getMessage());
        }
    }

    /**
     * Create or update section_subject pivot table 
     */
    private function ensureSectionSubjectTableExists(): void
    {
        if (!Schema::hasTable('section_subject')) {
            // Only create if the sections table exists
            if (Schema::hasTable('sections')) {
                Schema::create('section_subject', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('section_id')->constrained()->onDelete('cascade');
                    $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                    $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
                    $table->timestamps();
                });
                
                Log::info('Created section_subject pivot table');
            } else {
                Log::info('Skipped section_subject creation as sections table does not exist yet');
            }
        } else {
            // Check if all necessary columns exist
            Schema::table('section_subject', function (Blueprint $table) {
                if (!Schema::hasColumn('section_subject', 'teacher_id')) {
                    // Add teacher_id column
                    $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
                }
            });
            
            Log::info('Updated section_subject pivot table');
        }
    }
};
