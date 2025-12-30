<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get($key, $default = null)
    {
        // Prevent crash during app boot
        if (!Schema::hasTable('settings')) {
            return $default;
        }

        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        if (!Schema::hasTable('settings')) {
            return;
        }

        self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}
