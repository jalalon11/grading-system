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
        // Check if teacher_id column already exists
        if (!Schema::hasColumn('sections', 'teacher_id')) {
            Schema::table('sections', function (Blueprint $table) {
                $table->unsignedBigInteger('teacher_id')->nullable()->after('id');
            });
        }

        // Update existing records to copy user_id to teacher_id
        DB::statement('UPDATE sections SET teacher_id = user_id WHERE teacher_id IS NULL');
        
        // Create a trigger to automatically set teacher_id to user_id if teacher_id is NULL
        DB::unprepared('
            CREATE TRIGGER set_teacher_id_from_user_id
            BEFORE INSERT ON sections
            FOR EACH ROW
            BEGIN
                IF NEW.teacher_id IS NULL THEN
                    SET NEW.teacher_id = NEW.user_id;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the trigger
        DB::unprepared('DROP TRIGGER IF EXISTS set_teacher_id_from_user_id');
        
        // Don't remove the teacher_id column as it may be needed by the application
    }
};
