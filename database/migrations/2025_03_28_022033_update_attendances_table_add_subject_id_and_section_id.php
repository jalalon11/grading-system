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
        // First, check if the attendances table exists
        if (Schema::hasTable('attendances')) {
            // Add section_id if it doesn't exist
            if (!Schema::hasColumn('attendances', 'section_id')) {
                Schema::table('attendances', function (Blueprint $table) {
                    $table->foreignId('section_id')->nullable()->constrained()->after('student_id');
                });
            }
            
            // Add subject_id if it doesn't exist
            if (!Schema::hasColumn('attendances', 'subject_id')) {
                Schema::table('attendances', function (Blueprint $table) {
                    $table->foreignId('subject_id')->nullable()->constrained()->after('student_id');
                });
            }
            
            // Make sure we have a teacher_id column
            if (!Schema::hasColumn('attendances', 'teacher_id') && Schema::hasColumn('attendances', 'user_id')) {
                Schema::table('attendances', function (Blueprint $table) {
                    $table->renameColumn('user_id', 'teacher_id');
                });
            } else if (!Schema::hasColumn('attendances', 'teacher_id')) {
                Schema::table('attendances', function (Blueprint $table) {
                    $table->foreignId('teacher_id')->nullable()->constrained('users')->after('student_id');
                });
            }
            
            // Remove attendance_data column if it exists
            if (Schema::hasColumn('attendances', 'attendance_data')) {
                Schema::table('attendances', function (Blueprint $table) {
                    $table->dropColumn('attendance_data');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We won't try to reverse this complex migration
    }
};
