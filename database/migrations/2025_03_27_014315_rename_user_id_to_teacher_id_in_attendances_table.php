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
        // Check if columns exist
        $hasUserIdColumn = Schema::hasColumn('attendances', 'user_id');
        $hasTeacherIdColumn = Schema::hasColumn('attendances', 'teacher_id');

        if ($hasUserIdColumn && !$hasTeacherIdColumn) {
            // Rename user_id to teacher_id
            Schema::table('attendances', function (Blueprint $table) {
                // Drop foreign key if it exists
                try {
                    $table->dropForeign(['user_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                
                // Rename the column
                DB::statement('ALTER TABLE attendances CHANGE user_id teacher_id BIGINT UNSIGNED NULL');
                
                // Add foreign key
                $table->foreign('teacher_id')->references('id')->on('users')->onDelete('cascade');
            });
        } elseif (!$hasUserIdColumn && !$hasTeacherIdColumn) {
            // Add teacher_id if neither column exists
            Schema::table('attendances', function (Blueprint $table) {
                $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Check if columns exist
        $hasTeacherIdColumn = Schema::hasColumn('attendances', 'teacher_id');
        $hasUserIdColumn = Schema::hasColumn('attendances', 'user_id');

        if ($hasTeacherIdColumn && !$hasUserIdColumn) {
            Schema::table('attendances', function (Blueprint $table) {
                // Drop foreign key if it exists
                try {
                    $table->dropForeign(['teacher_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                
                // Rename the column back
                DB::statement('ALTER TABLE attendances CHANGE teacher_id user_id BIGINT UNSIGNED NULL');
                
                // Add foreign key
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });
        }
    }
};
