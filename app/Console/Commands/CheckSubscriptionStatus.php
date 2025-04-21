<?php

namespace App\Console\Commands;

use App\Models\School;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckSubscriptionStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for expired trials and subscriptions and update school status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking subscription status for all schools...');

        // Check for expired trials
        $expiredTrials = School::where('subscription_status', 'trial')
            ->whereNotNull('trial_ends_at') // Skip schools with unlimited trial
            ->where('trial_ends_at', '<', now())
            ->where('is_active', true)
            ->get();

        foreach ($expiredTrials as $school) {
            $school->is_active = false;
            $school->subscription_status = 'expired';
            $school->save();

            $this->info("School {$school->name} trial has expired. School has been disabled.");
            Log::info("School {$school->name} (ID: {$school->id}) trial has expired. School has been disabled.");
        }

        // Check for expired subscriptions
        $expiredSubscriptions = School::where('subscription_status', 'active')
            ->whereNotNull('subscription_ends_at') // Skip schools with unlimited subscription
            ->where('subscription_ends_at', '<', now())
            ->where('is_active', true)
            ->get();

        foreach ($expiredSubscriptions as $school) {
            $school->is_active = false;
            $school->subscription_status = 'expired';
            $school->save();

            $this->info("School {$school->name} subscription has expired. School has been disabled.");
            Log::info("School {$school->name} (ID: {$school->id}) subscription has expired. School has been disabled.");
        }

        $this->info('Subscription check completed.');

        return 0;
    }
}
