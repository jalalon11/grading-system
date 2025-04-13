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
        if (!Schema::hasTable('resource_materials')) {
            // Make sure resource_categories table exists first
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

                Log::info('Created resource_categories table from resource_materials migration');
            }

            Schema::create('resource_materials', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('url', 2048);
                $table->foreignId('category_id')->nullable()->constrained('resource_categories')->nullOnDelete();
                $table->boolean('is_active')->default(true);
                $table->integer('click_count')->default(0);
                $table->timestamps();
            });

            Log::info('Created resource_materials table');

            // Check if quarter column needs to be added
            if (!Schema::hasColumn('resource_materials', 'quarter')) {
                Schema::table('resource_materials', function (Blueprint $table) {
                    $table->tinyInteger('quarter')->nullable()->after('category_id')->comment('1=Q1, 2=Q2, 3=Q3, 4=Q4');
                });

                Log::info('Added quarter column to resource_materials table');
            }
        } else {
            Log::info('resource_materials table already exists, skipping creation');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_materials');
    }
};
