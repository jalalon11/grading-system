<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration ensures that all tables have the correct dependencies
     * and will work with migrate:fresh while preserving the data structure.
     */
    public function up(): void
    {
        // Temporarily disable foreign key checks to avoid constraint issues
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        
        // Ensure foreign keys don't conflict during migration:fresh
        $this->fixForeignKeyConstraints();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Fix foreign key constraints to ensure proper order
     */
    private function fixForeignKeyConstraints(): void
    {
        // Fix the subjects table - remove any existing section_id foreign key
        if (Schema::hasTable('subjects') && Schema::hasColumn('subjects', 'section_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                // Drop the foreign key if it exists
                try {
                    $table->dropForeign(['section_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, so handle silently
                }
                
                // Make the column nullable if it's not already
                $table->foreignId('section_id')->nullable()->change();
            });
        }
        
        // Fix the students table - remove any existing section_id foreign key
        if (Schema::hasTable('students') && Schema::hasColumn('students', 'section_id')) {
            Schema::table('students', function (Blueprint $table) {
                // Drop the foreign key if it exists
                try {
                    $table->dropForeign(['section_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist, so handle silently
                }
                
                // Make the column nullable if it's not already
                $table->foreignId('section_id')->nullable()->change();
            });
        }
        
        // Fix other potential circular dependencies here if found
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to implement down as these are fixes to make migrations work properly
    }
};
