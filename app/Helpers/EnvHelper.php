<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Artisan;

class EnvHelper
{
    /**
     * Update nilai di file .env
     */
    public static function updateEnv($key, $value)
    {
        $path = base_path('.env');

        if (!file_exists($path)) {
            return false;
        }

        // Escape special characters
        $value = str_replace(['\\', '"'], ['\\\\', '\"'], $value);

        // Read .env file
        $envContent = file_get_contents($path);

        // Pattern untuk key yang ada
        $pattern = "/^{$key}=.*/m";

        // Cek apakah key sudah ada
        if (preg_match($pattern, $envContent)) {
            // Update existing key
            $envContent = preg_replace(
                $pattern,
                "{$key}=\"{$value}\"",
                $envContent
            );
        } else {
            // Tambah key baru di akhir file
            $envContent .= "\n{$key}=\"{$value}\"";
        }

        // Write back to .env
        file_put_contents($path, $envContent);

        // Clear config cache
        try {
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
        } catch (\Exception $e) {
            // Ignore cache clear errors
        }

        return true;
    }
}
