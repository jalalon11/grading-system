<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\School;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CacheSchoolLogos extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cache-school-logos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache all school logos locally to improve performance';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to cache school logos...');

        // Make sure the cache directory exists
        $cacheDir = storage_path('app/public/image_cache');
        File::ensureDirectoryExists($cacheDir);

        // Get all schools with logos
        $schools = School::whereNotNull('logo_path')->get();

        $this->info("Found {$schools->count()} schools with logos.");

        // Calculate current cache size before update
        $initialSize = $this->calculateCacheSize($cacheDir);
        $this->info("Current cache size: {$this->formatBytes($initialSize)}");

        $bar = $this->output->createProgressBar($schools->count());
        $bar->start();

        $cached = 0;
        $skipped = 0;
        $errors = 0;
        $totalBytes = 0;

        foreach ($schools as $school) {
            try {
                $path = $school->logo_path;
                $localCachePath = storage_path('app/public/image_cache/' . md5($path));

                // Check if already cached and not older than 7 days
                if (File::exists($localCachePath) &&
                    (time() - File::lastModified($localCachePath) < 7 * 24 * 60 * 60)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Check if the file exists in R2 storage
                if (!Storage::disk('r2')->exists($path)) {
                    $this->error("Logo not found for school: {$school->name}");
                    $errors++;
                    $bar->advance();
                    continue;
                }

                // Get the file contents from R2
                $fileContents = Storage::disk('r2')->get($path);
                $fileSize = strlen($fileContents);
                $totalBytes += $fileSize;

                // Save to local cache
                File::put($localCachePath, $fileContents);

                $cached++;

                // Log detailed info for each cached file
                $this->line("\n<fg=green>✓</> Cached {$school->name} logo ({$this->formatBytes($fileSize)})");
            } catch (\Exception $e) {
                $this->error("Error caching logo for school {$school->name}: {$e->getMessage()}");
                $errors++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        // Calculate new cache size
        $finalSize = $this->calculateCacheSize($cacheDir);

        $this->info("Completed caching school logos.");
        $this->info("Cached: {$cached} | Skipped: {$skipped} | Errors: {$errors} | Total: {$schools->count()}");
        $this->info("Cache storage: {$this->formatBytes($finalSize)} (Added: {$this->formatBytes($totalBytes)})");

        return Command::SUCCESS;
    }

    /**
     * Calculate the total size of files in a directory
     *
     * @param string $dir
     * @return int
     */
    private function calculateCacheSize(string $dir): int
    {
        if (!File::isDirectory($dir)) {
            return 0;
        }

        $size = 0;
        foreach (File::files($dir) as $file) {
            $size += $file->getSize();
        }

        return $size;
    }

    /**
     * Format bytes to human-readable format
     *
     * @param int $bytes
     * @return string
     */
    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
