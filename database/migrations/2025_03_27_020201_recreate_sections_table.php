<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, drop existing table if it exists along with its relationships
        $this->dropExistingTable();
        
        // Create sections table with correct structure
        Schema::create('sections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('grade_level');
            $table->foreignId('adviser_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->string('school_year');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        // Create section_subject pivot table
        if (!Schema::hasTable('section_subject')) {
            Schema::create('section_subject', function (Blueprint $table) {
                $table->id();
                $table->foreignId('section_id')->constrained()->onDelete('cascade');
                $table->foreignId('subject_id')->constrained()->onDelete('cascade');
                $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
                $table->timestamps();
                
                $table->unique(['section_id', 'subject_id']);
            });
        }
    }

    /**
     * Drop existing sections table and all its relationships
     */
    private function dropExistingTable()
    {
        // First, drop all foreign keys referencing sections
        try {
            $foreignKeys = DB::select("
                SELECT TABLE_NAME, CONSTRAINT_NAME
                FROM information_schema.KEY_COLUMN_USAGE
                WHERE REFERENCED_TABLE_SCHEMA = DATABASE()
                AND REFERENCED_TABLE_NAME = 'sections'
                AND REFERENCED_COLUMN_NAME = 'id'
            ");
            
            foreach ($foreignKeys as $key) {
                try {
                    DB::statement("
                        ALTER TABLE `{$key->TABLE_NAME}`
                        DROP FOREIGN KEY `{$key->CONSTRAINT_NAME}`
                    ");
                } catch (\Exception $e) {
                    // Ignore errors
                }
            }
            
            // Special handling for section_subject table
            if (Schema::hasTable('section_subject')) {
                Schema::dropIfExists('section_subject');
            }
            
            // Now drop the sections table
            Schema::dropIfExists('sections');
        } catch (\Exception $e) {
            // Log error but continue
            \Log::error('Error dropping sections table: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('section_subject');
        Schema::dropIfExists('sections');
    }
};
