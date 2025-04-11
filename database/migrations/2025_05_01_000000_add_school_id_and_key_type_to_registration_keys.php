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
        Schema::table('registration_keys', function (Blueprint $table) {
            $table->foreignId('school_id')->nullable()->constrained()->onDelete('cascade')->after('is_used');
            $table->enum('key_type', ['teacher', 'student'])->default('teacher')->after('school_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_keys', function (Blueprint $table) {
            $table->dropForeign(['school_id']);
            $table->dropColumn(['school_id', 'key_type']);
        });
    }
};
