<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This is a complete database setup migration.
     * By default, it will not run with regular migrate commands.
     * To use this migration, explicitly call:
     * php artisan migrate --path=database/migrations/2025_03_27_030920_setup_database_from_scratch.php
     */
    public function up(): void
    {
        // Skip this migration during regular migrate:fresh
        // This is a safety measure to prevent data loss
        if (!$this->shouldRunMigration()) {
            return;
        }
        
        // Drop all existing tables
        $this->dropAllTables();

        // Create users table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['admin', 'teacher', 'student'])->default('student');
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_teacher_admin')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        // Create school_divisions table
        Schema::create('school_divisions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create schools table
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address')->nullable();
            $table->string('contact_number')->nullable();
            $table->json('grade_levels')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Update users with school_id
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->after('is_teacher_admin')->constrained()->onDelete('set null');
        });

        // Create grade_levels table
        Schema::create('grade_levels', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Create sections table
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

        // Create subjects table
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

        // Create section_subject pivot table
        Schema::create('section_subject', function (Blueprint $table) {
            $table->id();
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            $table->unique(['section_id', 'subject_id']);
        });

        // Create students table
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->date('birth_date');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->string('student_id')->unique();
            $table->text('address')->nullable();
            $table->string('guardian_name');
            $table->string('guardian_contact')->nullable();
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
        });

        // Create attendances table
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'excused']);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Create grades table
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('grading_period');
            $table->decimal('score', 5, 2);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Create grade_configurations table
        Schema::create('grade_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('grading_period');
            $table->json('components');
            $table->timestamps();
        });

        // Create cache tables
        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        // Create jobs tables
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });

        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // Create migrations table if it doesn't exist
        if (!Schema::hasTable('migrations')) {
            Schema::create('migrations', function (Blueprint $table) {
                $table->id();
                $table->string('migration');
                $table->integer('batch');
            });
        }
    }

    /**
     * Check if this migration should run
     * 
     * Only runs if explicitly called with the path parameter
     */
    private function shouldRunMigration(): bool
    {
        // This is a special migration that should only run when explicitly called
        // Check if this migration is specifically requested
        $migrationFile = basename(__FILE__, '.php');
        $requestedMigrations = $_SERVER['argv'] ?? [];
        
        foreach ($requestedMigrations as $arg) {
            if (strpos($arg, $migrationFile) !== false) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to implement down as this is a full database setup
        // If you want to revert, simply run migrate:fresh again
    }

    /**
     * Drop all tables in the database
     */
    private function dropAllTables(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Get all table names
        $tables = DB::select('SHOW TABLES');
        $dbname = env('DB_DATABASE');
        $tableKey = "Tables_in_" . $dbname;

        // Drop each table
        foreach ($tables as $table) {
            $dropQuery = "DROP TABLE IF EXISTS " . $table->$tableKey;
            DB::statement($dropQuery);
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
};
