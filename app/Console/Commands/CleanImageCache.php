<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CleanImageCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-image-cache {--days=30 : Number of days to keep files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old files from the image cache';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days');
        $this->info("Cleaning image cache files older than {$days} days...");
        
        $cacheDir = storage_path('app/public/image_cache');
        
        if (!File::isDirectory($cacheDir)) {
            $this->info("Cache directory does not exist. Nothing to clean.");
            return Command::SUCCESS;
        }
        
        // Calculate initial size
        $initialSize = $this->calculateCacheSize($cacheDir);
        $this->info("Current cache size: {$this->formatBytes($initialSize)}");
        
        $cutoffTime = time() - ($days * 24 * 60 * 60);
        $deleted = 0;
        $bytesFreed = 0;
        
        foreach (File::files($cacheDir) as $file) {
            if ($file->getMTime() < $cutoffTime) {
                $bytesFreed += $file->getSize();
                File::delete($file->getPathname());
                $deleted++;
            }
        }
        
        // Calculate final size
        $finalSize = $this->calculateCacheSize($cacheDir);
        
        $this->info("Deleted {$deleted} old cache files.");
        $this->info("Freed {$this->formatBytes($bytesFreed)} of disk space.");
        $this->info("New cache size: {$this->formatBytes($finalSize)}");
        
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
