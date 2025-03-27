<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Skip section_subject table creation for now - will be handled in recreate_sections_table migration
        
        Schema::table('subjects', function (Blueprint $table) {
            // Remove the section_id column if it exists
            if (Schema::hasColumn('subjects', 'section_id')) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            }
            
            // Remove the user_id column if it exists
            if (Schema::hasColumn('subjects', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            // Make sure school_id exists
            if (!Schema::hasColumn('subjects', 'school_id')) {
                $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade');
            }
            
            // Add is_active column if it doesn't exist
            if (!Schema::hasColumn('subjects', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to revert these changes as they're fixing issues
    }
};
