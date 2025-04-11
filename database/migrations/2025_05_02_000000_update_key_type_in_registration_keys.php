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
        // First, convert any existing 'student' key types to 'teacher'
        DB::table('registration_keys')
            ->where('key_type', 'student')
            ->update(['key_type' => 'teacher']);
            
        // Now modify the column to use the new enum values
        // We need to drop and recreate the column since MySQL doesn't allow direct enum modification
        Schema::table('registration_keys', function (Blueprint $table) {
            $table->dropColumn('key_type');
        });
        
        Schema::table('registration_keys', function (Blueprint $table) {
            $table->enum('key_type', ['teacher', 'teacher_admin'])->default('teacher')->after('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Convert any 'teacher_admin' key types back to 'teacher'
        DB::table('registration_keys')
            ->where('key_type', 'teacher_admin')
            ->update(['key_type' => 'teacher']);
            
        // Revert the column to the original enum values
        Schema::table('registration_keys', function (Blueprint $table) {
            $table->dropColumn('key_type');
        });
        
        Schema::table('registration_keys', function (Blueprint $table) {
            $table->enum('key_type', ['teacher', 'student'])->default('teacher')->after('school_id');
        });
    }
};
