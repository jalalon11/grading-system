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
        Schema::table('schools', function (Blueprint $table) {
            $table->timestamp('trial_ends_at')->nullable()->after('is_active');
            $table->enum('subscription_status', ['trial', 'active', 'expired'])->default('trial')->after('trial_ends_at');
            $table->timestamp('subscription_ends_at')->nullable()->after('subscription_status');
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly')->after('subscription_ends_at');
            $table->decimal('monthly_price', 10, 2)->default(0)->after('billing_cycle');
            $table->decimal('yearly_price', 10, 2)->default(0)->after('monthly_price');
        });
        
        // Set default trial period for existing schools (3 months from now)
        DB::table('schools')->update([
            'trial_ends_at' => now()->addMonths(3),
            'subscription_status' => 'trial'
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn([
                'trial_ends_at',
                'subscription_status',
                'subscription_ends_at',
                'billing_cycle',
                'monthly_price',
                'yearly_price'
            ]);
        });
    }
};
