<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class ImageProxyController extends Controller
{
    /**
     * Proxy images from Cloudflare R2 storage
     *
     * @param string $path
     * @return \Illuminate\Http\Response
     */
    public function proxyImage($path)
    {
        try {
            // Check if we have this image cached locally
            $localCachePath = storage_path('app/public/image_cache/' . md5($path));

            // If the file doesn't exist in local cache, fetch it from R2
            if (!File::exists($localCachePath)) {
                // Check if the file exists in R2 storage
                if (!Storage::disk('r2')->exists($path)) {
                    return response()->json(['error' => 'Image not found'], 404);
                }

                // Make sure the cache directory exists
                File::ensureDirectoryExists(storage_path('app/public/image_cache'));

                // Get the file contents from R2
                $fileContents = Storage::disk('r2')->get($path);

                // Save to local cache
                File::put($localCachePath, $fileContents);
            } else {
                // Get file from local cache
                $fileContents = File::get($localCachePath);
            }

            // Determine mime type
            $mimeType = File::mimeType($localCachePath);

            // Return the file with appropriate headers
            return response($fileContents)
                ->header('Content-Type', $mimeType)
                ->header('Cache-Control', 'public, max-age=86400'); // Cache for 24 hours
        } catch (\Exception $e) {
            Log::error('Error proxying image: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load image'], 500);
        }
    }
}
