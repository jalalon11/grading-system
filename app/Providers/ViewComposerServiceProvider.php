<?php

namespace App\Providers;

use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share pending payments count with the app layout
        View::composer('layouts.app', function ($view) {
            $pendingPaymentsCount = 0;
            
            if (Auth::check() && Auth::user()->role === 'admin') {
                $pendingPaymentsCount = Payment::where('status', 'pending')->count();
            }
            
            $view->with('pendingPaymentsCount', $pendingPaymentsCount);
        });
    }
}
