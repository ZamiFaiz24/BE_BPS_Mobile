<?php

use App\Models\Setting;

if (!function_exists('setting')) {
    function setting($key, $default = null)
    {
        if (is_array($key)) {
            // Set multiple
            foreach ($key as $k => $v) {
                Setting::updateOrCreate(['key' => $k], ['value' => $v]);
            }
            return true;
        } else {
            // Get
            $row = Setting::where('key', $key)->first();
            return $row ? $row->value : $default;
        }
    }
}
