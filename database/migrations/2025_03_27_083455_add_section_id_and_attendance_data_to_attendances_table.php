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
        Schema::table('attendances', function (Blueprint $table) {
            // Add section_id column if it doesn't exist
            if (!Schema::hasColumn('attendances', 'section_id')) {
                $table->foreignId('section_id')->nullable()->after('teacher_id')->constrained()->onDelete('cascade');
            }
            
            // Add attendance_data column if it doesn't exist
            if (!Schema::hasColumn('attendances', 'attendance_data')) {
                $table->json('attendance_data')->nullable()->after('remarks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Drop columns if they exist
            if (Schema::hasColumn('attendances', 'section_id')) {
                $table->dropForeign(['section_id']);
                $table->dropColumn('section_id');
            }
            
            if (Schema::hasColumn('attendances', 'attendance_data')) {
                $table->dropColumn('attendance_data');
            }
        });
    }
};
