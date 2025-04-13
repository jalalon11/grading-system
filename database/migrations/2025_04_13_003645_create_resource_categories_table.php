<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the table already exists
        if (!Schema::hasTable('resource_categories')) {
            Schema::create('resource_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->text('description')->nullable();
                $table->string('icon')->default('folder');
                $table->string('color')->default('primary');
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            Log::info('Created resource_categories table');
        } else {
            Log::info('resource_categories table already exists, skipping creation');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_categories');
    }
};
