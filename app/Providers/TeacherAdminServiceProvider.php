<?php

namespace App\Providers;

use App\Http\Controllers\TeacherAdmin\DashboardController;
use App\Http\Controllers\TeacherAdmin\SectionController;
use App\Http\Controllers\TeacherAdmin\SubjectController;
use App\Http\Middleware\TeacherAdminMiddleware;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TeacherAdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register teacher-admin controllers explicitly
        $this->app->singleton('TeacherAdmin.DashboardController', function ($app) {
            return new DashboardController();
        });

        $this->app->singleton('TeacherAdmin.SectionController', function ($app) {
            return new SectionController();
        });

        $this->app->singleton('TeacherAdmin.SubjectController', function ($app) {
            return new SubjectController();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Explicitly register the middleware
        $this->app['router']->aliasMiddleware('teacher.admin', TeacherAdminMiddleware::class);
    }
}
