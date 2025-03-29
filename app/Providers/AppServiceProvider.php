<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\GradeHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Make the getTransmutedGrade function available globally
        if (!function_exists('getTransmutedGrade')) {
            function getTransmutedGrade($initialGrade, $tableType) {
                if ($initialGrade < 0) return 60;
                
                // Table 1: DepEd Transmutation Table (formerly Table 4)
                if ($tableType == 1) {
                    if ($initialGrade == 100) return 100;
                    elseif ($initialGrade >= 98.40) return 99;
                    elseif ($initialGrade >= 96.80) return 98;
                    elseif ($initialGrade >= 95.20) return 97;
                    elseif ($initialGrade >= 93.60) return 96;
                    elseif ($initialGrade >= 92.00) return 95;
                    elseif ($initialGrade >= 90.40) return 94;
                    elseif ($initialGrade >= 88.80) return 93;
                    elseif ($initialGrade >= 87.20) return 92;
                    elseif ($initialGrade >= 85.60) return 91;
                    elseif ($initialGrade >= 84.00) return 90;
                    elseif ($initialGrade >= 82.40) return 89;
                    elseif ($initialGrade >= 80.80) return 88;
                    elseif ($initialGrade >= 79.20) return 87;
                    elseif ($initialGrade >= 77.60) return 86;
                    elseif ($initialGrade >= 76.00) return 85;
                    elseif ($initialGrade >= 74.40) return 84;
                    elseif ($initialGrade >= 72.80) return 83;
                    elseif ($initialGrade >= 71.20) return 82;
                    elseif ($initialGrade >= 69.60) return 81;
                    elseif ($initialGrade >= 68.00) return 80;
                    elseif ($initialGrade >= 66.40) return 79;
                    elseif ($initialGrade >= 64.80) return 78;
                    elseif ($initialGrade >= 63.20) return 77;
                    elseif ($initialGrade >= 61.60) return 76;
                    elseif ($initialGrade >= 60.00) return 75;
                    elseif ($initialGrade >= 56.00) return 74;
                    elseif ($initialGrade >= 52.00) return 73;
                    elseif ($initialGrade >= 48.00) return 72;
                    elseif ($initialGrade >= 44.00) return 71;
                    elseif ($initialGrade >= 40.00) return 70;
                    elseif ($initialGrade >= 36.00) return 69;
                    elseif ($initialGrade >= 32.00) return 68;
                    elseif ($initialGrade >= 28.00) return 67;
                    elseif ($initialGrade >= 24.00) return 66;
                    elseif ($initialGrade >= 20.00) return 65;
                    elseif ($initialGrade >= 16.00) return 64;
                    elseif ($initialGrade >= 12.00) return 63;
                    elseif ($initialGrade >= 8.00) return 62;
                    elseif ($initialGrade >= 4.00) return 61;
                    else return 60;
                }
                // Table 2: Grades 1-10 and Non-Core Subjects of TVL, Sports, and Arts & Design (formerly Table 1)
                elseif ($tableType == 2) {
                    if ($initialGrade >= 80) return 100;
                    elseif ($initialGrade >= 78.40) return 99;
                    elseif ($initialGrade >= 76.80) return 98;
                    elseif ($initialGrade >= 75.20) return 97;
                    elseif ($initialGrade >= 73.60) return 96;
                    elseif ($initialGrade >= 72.00) return 95;
                    elseif ($initialGrade >= 70.40) return 94;
                    elseif ($initialGrade >= 68.80) return 93;
                    elseif ($initialGrade >= 67.20) return 92;
                    elseif ($initialGrade >= 65.60) return 91;
                    elseif ($initialGrade >= 64.00) return 90;
                    elseif ($initialGrade >= 62.40) return 89;
                    elseif ($initialGrade >= 60.80) return 88;
                    elseif ($initialGrade >= 59.20) return 87;
                    elseif ($initialGrade >= 57.60) return 86;
                    elseif ($initialGrade >= 56.00) return 85;
                    elseif ($initialGrade >= 54.40) return 84;
                    elseif ($initialGrade >= 52.80) return 83;
                    elseif ($initialGrade >= 51.20) return 82;
                    elseif ($initialGrade >= 49.60) return 81;
                    elseif ($initialGrade >= 48.00) return 80;
                    elseif ($initialGrade >= 46.40) return 79;
                    elseif ($initialGrade >= 44.80) return 78;
                    elseif ($initialGrade >= 43.20) return 77;
                    elseif ($initialGrade >= 41.60) return 76;
                    elseif ($initialGrade >= 40.00) return 75;
                    elseif ($initialGrade >= 38.40) return 74;
                    elseif ($initialGrade >= 36.80) return 73;
                    elseif ($initialGrade >= 35.20) return 72;
                    elseif ($initialGrade >= 33.60) return 71;
                    elseif ($initialGrade >= 32.00) return 70;
                    elseif ($initialGrade >= 30.40) return 69;
                    elseif ($initialGrade >= 28.80) return 68;
                    elseif ($initialGrade >= 27.20) return 67;
                    elseif ($initialGrade >= 25.60) return 66;
                    elseif ($initialGrade >= 24.00) return 65;
                    elseif ($initialGrade >= 22.40) return 64;
                    elseif ($initialGrade >= 20.80) return 63;
                    elseif ($initialGrade >= 19.20) return 62;
                    elseif ($initialGrade >= 17.60) return 61;
                    else return 60;
                }
                // Table 3: For SHS Core Subjects and Work Immersion/Research/Business Enterprise/Performance (formerly Table 2)
                elseif ($tableType == 3) {
                    if ($initialGrade >= 100) return 100;
                    elseif ($initialGrade >= 73.80) return 99;
                    elseif ($initialGrade >= 72.60) return 98;
                    elseif ($initialGrade >= 71.40) return 97;
                    elseif ($initialGrade >= 70.20) return 96;
                    elseif ($initialGrade >= 69.00) return 95;
                    elseif ($initialGrade >= 67.80) return 94;
                    elseif ($initialGrade >= 66.60) return 93;
                    elseif ($initialGrade >= 65.40) return 92;
                    elseif ($initialGrade >= 64.20) return 91;
                    elseif ($initialGrade >= 63.00) return 90;
                    elseif ($initialGrade >= 61.80) return 89;
                    elseif ($initialGrade >= 60.60) return 88;
                    elseif ($initialGrade >= 59.40) return 87;
                    elseif ($initialGrade >= 58.20) return 86;
                    elseif ($initialGrade >= 57.00) return 85;
                    elseif ($initialGrade >= 55.80) return 84;
                    elseif ($initialGrade >= 54.60) return 83;
                    elseif ($initialGrade >= 53.40) return 82;
                    elseif ($initialGrade >= 52.20) return 81;
                    elseif ($initialGrade >= 51.00) return 80;
                    elseif ($initialGrade >= 49.80) return 79;
                    elseif ($initialGrade >= 48.60) return 78;
                    elseif ($initialGrade >= 47.40) return 77;
                    elseif ($initialGrade >= 46.20) return 76;
                    elseif ($initialGrade >= 45.00) return 75;
                    elseif ($initialGrade >= 43.80) return 74;
                    elseif ($initialGrade >= 42.60) return 73;
                    elseif ($initialGrade >= 41.40) return 72;
                    elseif ($initialGrade >= 40.20) return 71;
                    elseif ($initialGrade >= 39.00) return 70;
                    elseif ($initialGrade >= 37.80) return 69;
                    elseif ($initialGrade >= 36.60) return 68;
                    elseif ($initialGrade >= 35.40) return 67;
                    elseif ($initialGrade >= 34.20) return 66;
                    elseif ($initialGrade >= 33.00) return 65;
                    elseif ($initialGrade >= 31.80) return 64;
                    elseif ($initialGrade >= 30.60) return 63;
                    elseif ($initialGrade >= 29.40) return 62;
                    elseif ($initialGrade >= 28.20) return 61;
                    else return 60;
                }
                // Table 4: For all other SHS Subjects in the Academic Track (formerly Table 3)
                elseif ($tableType == 4) {
                    if ($initialGrade >= 100) return 100;
                    elseif ($initialGrade >= 68.90) return 99;
                    elseif ($initialGrade >= 67.80) return 98;
                    elseif ($initialGrade >= 66.70) return 97;
                    elseif ($initialGrade >= 65.60) return 96;
                    elseif ($initialGrade >= 64.50) return 95;
                    elseif ($initialGrade >= 63.40) return 94;
                    elseif ($initialGrade >= 62.30) return 93;
                    elseif ($initialGrade >= 61.20) return 92;
                    elseif ($initialGrade >= 60.10) return 91;
                    elseif ($initialGrade >= 59.00) return 90;
                    elseif ($initialGrade >= 57.80) return 89;
                    elseif ($initialGrade >= 56.70) return 88;
                    elseif ($initialGrade >= 55.60) return 87;
                    elseif ($initialGrade >= 54.50) return 86;
                    elseif ($initialGrade >= 53.40) return 85;
                    elseif ($initialGrade >= 52.30) return 84;
                    elseif ($initialGrade >= 51.20) return 83;
                    elseif ($initialGrade >= 50.10) return 82;
                    elseif ($initialGrade >= 49.00) return 81;
                    elseif ($initialGrade >= 47.90) return 80;
                    elseif ($initialGrade >= 46.80) return 79;
                    elseif ($initialGrade >= 45.70) return 78;
                    elseif ($initialGrade >= 44.60) return 77;
                    elseif ($initialGrade >= 43.50) return 76;
                    elseif ($initialGrade >= 42.40) return 75;
                    elseif ($initialGrade >= 41.30) return 74;
                    elseif ($initialGrade >= 40.20) return 73;
                    elseif ($initialGrade >= 39.10) return 72;
                    elseif ($initialGrade >= 34.00) return 71;
                    elseif ($initialGrade >= 28.90) return 70;
                    elseif ($initialGrade >= 23.80) return 69;
                    elseif ($initialGrade >= 19.70) return 68;
                    elseif ($initialGrade >= 17.60) return 67;
                    elseif ($initialGrade >= 15.50) return 66;
                    elseif ($initialGrade >= 13.40) return 65;
                    elseif ($initialGrade >= 11.30) return 64;
                    elseif ($initialGrade >= 9.20) return 63;
                    elseif ($initialGrade >= 7.10) return 62;
                    elseif ($initialGrade >= 5.00) return 61;
                    else return 60;
                }
                else {
                    // Default to table 1 (DepEd) if an invalid table type is specified
                    return getTransmutedGrade($initialGrade, 1);
                }
            }
        }
    }
}
