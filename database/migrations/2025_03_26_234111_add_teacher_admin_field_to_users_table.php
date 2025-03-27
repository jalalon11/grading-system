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
        // Add teacher_admin as a valid role
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teacher', 'teacher_admin') NOT NULL");

        // Create a trigger to ensure only 2 teacher admins per school
        DB::unprepared('
            CREATE TRIGGER check_teacher_admin_limit
            BEFORE UPDATE ON users
            FOR EACH ROW
            BEGIN
                DECLARE admin_count INT;
                
                IF NEW.role = "teacher_admin" AND (OLD.role != "teacher_admin" OR OLD.role IS NULL) THEN
                    SELECT COUNT(*) INTO admin_count
                    FROM users
                    WHERE school_id = NEW.school_id AND role = "teacher_admin";
                    
                    IF admin_count >= 2 THEN
                        SIGNAL SQLSTATE "45000"
                        SET MESSAGE_TEXT = "Maximum of 2 teacher admins allowed per school";
                    END IF;
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove teacher_admin from valid roles
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'teacher') NOT NULL");

        // Drop the trigger
        DB::unprepared('DROP TRIGGER IF EXISTS check_teacher_admin_limit');
    }
};
