<?php

namespace App\Jobs;

use App\Models\School;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CacheSchoolLogoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The school ID to process
     *
     * @var int
     */
    protected $schoolId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $schoolId)
    {
        $this->schoolId = $schoolId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $school = School::find($this->schoolId);
            
            if (!$school || !$school->logo_path) {
                Log::warning("CacheSchoolLogoJob: School not found or has no logo", [
                    'school_id' => $this->schoolId
                ]);
                return;
            }
            
            $path = $school->logo_path;
            $cacheDir = storage_path('app/public/image_cache');
            File::ensureDirectoryExists($cacheDir);
            
            $localCachePath = storage_path('app/public/image_cache/' . md5($path));
            
            // Check if already cached and not older than 7 days
            if (File::exists($localCachePath) &&
                (time() - File::lastModified($localCachePath) < 7 * 24 * 60 * 60)) {
                Log::info("CacheSchoolLogoJob: Logo already cached", [
                    'school_id' => $this->schoolId,
                    'school_name' => $school->name
                ]);
                return;
            }
            
            // Check if the file exists in R2 storage
            if (!Storage::disk('r2')->exists($path)) {
                Log::error("CacheSchoolLogoJob: Logo not found for school", [
                    'school_id' => $this->schoolId,
                    'school_name' => $school->name,
                    'path' => $path
                ]);
                return;
            }
            
            // Get the file contents from R2
            $fileContents = Storage::disk('r2')->get($path);
            
            // Save to local cache
            File::put($localCachePath, $fileContents);
            
            Log::info("CacheSchoolLogoJob: Successfully cached logo", [
                'school_id' => $this->schoolId,
                'school_name' => $school->name,
                'size' => strlen($fileContents)
            ]);
            
        } catch (\Exception $e) {
            Log::error("CacheSchoolLogoJob: Error caching logo", [
                'school_id' => $this->schoolId,
                'error' => $e->getMessage()
            ]);
        }
    }
}
