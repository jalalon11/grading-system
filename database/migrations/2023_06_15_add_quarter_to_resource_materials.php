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
        Schema::table('resource_materials', function (Blueprint $table) {
            $table->tinyInteger('quarter')->nullable()->after('category_id')->comment('1=Q1, 2=Q2, 3=Q3, 4=Q4');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('resource_materials', function (Blueprint $table) {
            $table->dropColumn('quarter');
        });
    }
};
