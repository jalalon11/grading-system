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
        Schema::table('subjects', function (Blueprint $table) {
            $table->foreignId('parent_subject_id')->nullable()->after('school_id')
                  ->constrained('subjects')->onDelete('cascade');
            $table->boolean('is_component')->default(false)->after('parent_subject_id');
            $table->decimal('component_weight', 5, 2)->default(25.00)->after('is_component');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('subjects', function (Blueprint $table) {
            $table->dropForeign(['parent_subject_id']);
            $table->dropColumn(['parent_subject_id', 'is_component', 'component_weight']);
        });
    }
}; 