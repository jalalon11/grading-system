<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if resource_materials table exists
        if (!Schema::hasTable('resource_materials')) {
            // Create the resource_materials table first
            Schema::create('resource_materials', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->text('description')->nullable();
                $table->string('url', 2048);
                $table->foreignId('category_id')->nullable();
                $table->boolean('is_active')->default(true);
                $table->integer('click_count')->default(0);
                $table->timestamps();
            });

            Log::info('Created resource_materials table');
        }

        // Check if resource_categories table exists (needed for foreign key)
        if (!Schema::hasTable('resource_categories')) {
            // Create the resource_categories table
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
        }

        // Now add the quarter column if it doesn't exist
        if (Schema::hasTable('resource_materials') && !Schema::hasColumn('resource_materials', 'quarter')) {
            Schema::table('resource_materials', function (Blueprint $table) {
                $table->tinyInteger('quarter')->nullable()->after('category_id')->comment('1=Q1, 2=Q2, 3=Q3, 4=Q4');
            });

            Log::info('Added quarter column to resource_materials table');
        }

        // Add foreign key constraint if it doesn't exist
        if (Schema::hasTable('resource_materials') && Schema::hasTable('resource_categories')) {
            // Check if the foreign key already exists
            $foreignKeys = $this->getForeignKeys('resource_materials');
            $hasConstraint = false;

            foreach ($foreignKeys as $key) {
                if ($key->REFERENCED_TABLE_NAME === 'resource_categories') {
                    $hasConstraint = true;
                    break;
                }
            }

            if (!$hasConstraint) {
                Schema::table('resource_materials', function (Blueprint $table) {
                    $table->foreign('category_id')->references('id')->on('resource_categories')->nullOnDelete();
                });

                Log::info('Added foreign key constraint to resource_materials table');
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('resource_materials') && Schema::hasColumn('resource_materials', 'quarter')) {
            Schema::table('resource_materials', function (Blueprint $table) {
                $table->dropColumn('quarter');
            });
        }
    }

    /**
     * Get foreign keys for a table
     */
    private function getForeignKeys(string $tableName)
    {
        $database = config('database.connections.mysql.database');
        return DB::select(
            "SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND REFERENCED_TABLE_NAME IS NOT NULL",
            [$database, $tableName]
        );
    }
};
