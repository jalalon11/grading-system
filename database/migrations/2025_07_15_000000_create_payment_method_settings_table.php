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
        Schema::create('payment_method_settings', function (Blueprint $table) {
            $table->id();
            $table->string('method');
            $table->boolean('enabled')->default(true);
            $table->text('message')->nullable();
            $table->timestamps();
            
            $table->unique('method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method_settings');
    }
};
