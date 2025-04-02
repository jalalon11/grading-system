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
        Schema::create('grade_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('quarter');
            $table->decimal('written_work_ps', 8, 2)->default(0);
            $table->decimal('written_work_ws', 8, 2)->default(0);
            $table->decimal('performance_task_ps', 8, 2)->default(0);
            $table->decimal('performance_task_ws', 8, 2)->default(0);
            $table->decimal('quarterly_assessment_ps', 8, 2)->default(0);
            $table->decimal('quarterly_assessment_ws', 8, 2)->default(0);
            $table->decimal('initial_grade', 8, 2)->default(0);
            $table->integer('quarterly_grade')->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();
            
            // Add unique constraint to prevent duplicate records
            $table->unique(['student_id', 'section_id', 'subject_id', 'quarter']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_summaries');
    }
};
