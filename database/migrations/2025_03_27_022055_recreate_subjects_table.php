<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            // Check if the subjects table already exists with the expected structure
            if (Schema::hasTable('subjects') && 
                Schema::hasColumn('subjects', 'school_id') && 
                Schema::hasColumn('subjects', 'is_active')) {
                // The table already exists with the proper structure
                Log::info('Subjects table already exists with expected structure, skipping recreation');
                return;
            }
            
            // Check if there are referencing tables - if so, we need to be careful
            $hasForeignKeys = $this->hasReferencingForeignKeys();
            
            if ($hasForeignKeys) {
                // Update the existing table without dropping it
                $this->updateExistingTable();
            } else {
                // Safe to drop and recreate the table
                $this->dropAndRecreateTable();
            }
            
            // Re-create section_subject pivot table if needed
            if (!Schema::hasTable('section_subject')) {
                Schema::create('section_subject', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('section_id')->constrained()->onDelete('cascade');
                    $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                    $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
                    $table->timestamps();
                });
            }

            // Log successful migration
            Log::info('Subjects table migration completed successfully');
        } catch (\Exception $e) {
            Log::error('Error in subjects table migration: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Check if there are foreign keys pointing to the subjects table
     */
    private function hasReferencingForeignKeys(): bool
    {
        $foreignKeys = DB::select("
            SELECT 
                CONSTRAINT_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE REFERENCED_TABLE_NAME = 'subjects'
            AND CONSTRAINT_SCHEMA = DATABASE()
        ");

        return count($foreignKeys) > 0;
    }
    
    /**
     * Update the existing table without dropping it
     */
    private function updateExistingTable(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            // Make code nullable if it's not already
            if (Schema::hasColumn('subjects', 'code')) {
                DB::statement('ALTER TABLE subjects MODIFY COLUMN code VARCHAR(255) NULL');
            }
            
            // Add school_id if it doesn't exist
            if (!Schema::hasColumn('subjects', 'school_id')) {
                $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            }
            
            // Add is_active if it doesn't exist
            if (!Schema::hasColumn('subjects', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            
            // Add grade_level if it doesn't exist
            if (!Schema::hasColumn('subjects', 'grade_level')) {
                $table->string('grade_level')->nullable();
            }
        });
    }
    
    /**
     * Drop and recreate the subjects table
     */
    private function dropAndRecreateTable(): void
    {
        // Make sure foreign keys are disabled
        Schema::disableForeignKeyConstraints();
        
        // Drop the table
        Schema::dropIfExists('subjects');
        
        // Create subjects table with correct structure
        Schema::create('subjects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->nullable();
            $table->text('description')->nullable();
            $table->string('grade_level')->nullable();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Re-enable foreign key constraints
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse this migration since it's an update/rebuild
    }
};
