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
        Schema::create('grade_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->decimal('written_work_percentage', 5, 2)->default(25.00);
            $table->decimal('performance_task_percentage', 5, 2)->default(50.00);
            $table->decimal('quarterly_assessment_percentage', 5, 2)->default(25.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grade_configurations');
    }
};
