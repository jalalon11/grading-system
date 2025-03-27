<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * This migration ensures that foreign keys are added in the correct order
     * after all tables are created.
     */
    public function up(): void
    {
        // Add section_id foreign key to subjects table if it doesn't already have one
        if (Schema::hasTable('subjects') && Schema::hasTable('sections') && Schema::hasColumn('subjects', 'section_id')) {
            Schema::table('subjects', function (Blueprint $table) {
                // Check if the foreign key constraint already exists
                $foreignKeys = $this->getForeignKeys('subjects');
                $hasConstraint = false;
                
                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey->COLUMN_NAME === 'section_id') {
                        $hasConstraint = true;
                        break;
                    }
                }
                
                // Add the foreign key if it doesn't exist
                if (!$hasConstraint) {
                    $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
                }
            });
        }
        
        // Add section_id foreign key to students table if it doesn't already have one
        if (Schema::hasTable('students') && Schema::hasTable('sections') && Schema::hasColumn('students', 'section_id')) {
            Schema::table('students', function (Blueprint $table) {
                // Check if the foreign key constraint already exists
                $foreignKeys = $this->getForeignKeys('students');
                $hasConstraint = false;
                
                foreach ($foreignKeys as $foreignKey) {
                    if ($foreignKey->COLUMN_NAME === 'section_id') {
                        $hasConstraint = true;
                        break;
                    }
                }
                
                // Add the foreign key if it doesn't exist
                if (!$hasConstraint) {
                    $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Get all foreign keys for a table
     */
    private function getForeignKeys(string $tableName)
    {
        return DB::select("
            SELECT 
                COLUMN_NAME
            FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
            WHERE 
                REFERENCED_TABLE_NAME IS NOT NULL
                AND TABLE_NAME = ?
                AND TABLE_SCHEMA = DATABASE()
        ", [$tableName]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed for this migration as it's just ensuring consistency
    }
};
