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
     * 
     * This migration is disabled during normal migrate:fresh to avoid conflicts.
     * To run this migration, explicitly specify the path:
     * php artisan migrate --path=database/migrations/2025_03_27_022531_recreate_subjects_table.php
     */
    public function up(): void
    {
        // Skip this migration during normal migrate:fresh
        if ($this->shouldSkipMigration()) {
            Log::info('Skipping recreate_subjects_table migration during normal migrate operation');
            return;
        }
        
        try {
            // First, identify tables with foreign keys to subjects
            $this->dropAllForeignKeysToSubjects();
            
            // Now we can safely drop and recreate the tables
            if (Schema::hasTable('section_subject')) {
                Schema::dropIfExists('section_subject');
                Log::info("Dropped section_subject table");
            }
            
            if (Schema::hasTable('subjects')) {
                Schema::dropIfExists('subjects');
                Log::info("Dropped subjects table");
            }

            // Recreate the subjects table
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
            Log::info("Created subjects table");

            // Create the section_subject pivot table
            if (!Schema::hasTable('section_subject')) {
                Schema::create('section_subject', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('section_id')->constrained()->onDelete('cascade');
                    $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                    $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
                    $table->timestamps();
                });
                Log::info("Created section_subject pivot table");
            }
            
            Log::info('Successfully recreated subjects table');
        } catch (\Exception $e) {
            Log::error('Error in subjects migration: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if this migration should be skipped during normal operation
     */
    private function shouldSkipMigration(): bool
    {
        // Skip unless specifically requested via migration path parameter
        $migrationFile = basename(__FILE__, '.php');
        $requestedMigrations = $_SERVER['argv'] ?? [];
        
        // If this migration is specifically requested, don't skip it
        foreach ($requestedMigrations as $arg) {
            if (strpos($arg, $migrationFile) !== false) {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No actual rollback implemented as this is a cleanup migration
        Log::info('Rollback not implemented for subjects table recreation migration');
    }

    /**
     * Drop all foreign keys that reference the subjects table
     */
    private function dropAllForeignKeysToSubjects(): void
    {
        // Disable foreign key checks for this operation
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        try {
            // Directly check for known tables that might have FK to subjects
            $potentialTables = [
                'section_subject',
                'grades',
                'assessments',
                'teaching_loads',
                'teacher_subjects',
                'student_grades',
                'class_schedules',
            ];
            
            foreach ($potentialTables as $tableName) {
                if (Schema::hasTable($tableName)) {
                    // Get and drop all foreign keys referencing subjects
                    $this->dropForeignKeysToSubjects($tableName);
                }
            }
            
            // Handle any custom pivot tables
            $this->dropForeignKeysToSubjectsInAllTables();
            
            Log::info("Successfully dropped all foreign keys to subjects table");
        } catch (\Exception $e) {
            Log::error("Error dropping foreign keys to subjects: " . $e->getMessage());
        } finally {
            // Re-enable foreign key checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }
    
    /**
     * Drop foreign keys in a specific table that reference the subjects table
     */
    private function dropForeignKeysToSubjects(string $tableName): void
    {
        try {
            // Get all foreign key constraints that reference the subjects table
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND REFERENCED_TABLE_NAME = 'subjects'
            ", [$tableName]);
            
            if (!empty($foreignKeys)) {
                foreach ($foreignKeys as $foreignKey) {
                    $constraintName = $foreignKey->CONSTRAINT_NAME;
                    
                    // Drop the foreign key
                    DB::statement("
                        ALTER TABLE {$tableName}
                        DROP FOREIGN KEY {$constraintName}
                    ");
                    
                    Log::info("Dropped foreign key {$constraintName} from {$tableName}");
                }
            }
        } catch (\Exception $e) {
            Log::error("Error dropping foreign keys from {$tableName} to subjects: " . $e->getMessage());
        }
    }
    
    /**
     * Check all tables and drop any foreign keys to subjects
     */
    private function dropForeignKeysToSubjectsInAllTables(): void
    {
        try {
            // Get all tables in the database
            $tables = DB::select('SHOW TABLES');
            
            foreach ($tables as $table) {
                $tableName = array_values(get_object_vars($table))[0];
                
                // Skip the subjects table itself
                if ($tableName === 'subjects') {
                    continue;
                }
                
                $this->dropForeignKeysToSubjects($tableName);
            }
        } catch (\Exception $e) {
            Log::error("Error checking all tables for foreign keys to subjects: " . $e->getMessage());
        }
    }
};
