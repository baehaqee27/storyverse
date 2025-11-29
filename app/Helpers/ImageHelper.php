<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageHelper
{
    public static function getCoverUrl($path)
    {
        if (!$path) {
            return null;
        }

        try {
            // Check if it's a full URL already (e.g. from a seeder or external source)
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }

            return Storage::url($path);
        } catch (\Exception $e) {
            // Log the error but don't crash the app
            Log::warning("Failed to get cover URL for path: {$path}. Error: " . $e->getMessage());
            return null; // Return null so the view can show a placeholder
        }
    }
}
