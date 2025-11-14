<?php

if (!function_exists('setting')) {
    /**
     * Get or set application settings
     */
    function setting($key = null, $default = null)
    {
        $settingsFile = storage_path('app/settings.json');

        // Ensure file exists
        if (!file_exists($settingsFile)) {
            file_put_contents($settingsFile, json_encode([]));
        }

        // Read settings
        $settings = json_decode(file_get_contents($settingsFile), true) ?? [];

        // If no key provided, return settings helper object
        if ($key === null) {
            return new class($settings, $settingsFile) {
                private $settings;
                private $file;

                public function __construct($settings, $file)
                {
                    $this->settings = $settings;
                    $this->file = $file;
                }

                public function all()
                {
                    return $this->settings;
                }

                public function get($key, $default = null)
                {
                    return $this->settings[$key] ?? $default;
                }
            };
        }

        // Set settings if array
        if (is_array($key)) {
            $settings = array_merge($settings, $key);
            file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
            return true;
        }

        // Get single setting
        return $settings[$key] ?? $default;
    }
}
