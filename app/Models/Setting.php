<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        return self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
    }

    /**
     * Get all settings as key-value pairs
     */
    public static function getAllSettings()
    {
        return self::all()->pluck('value', 'key')->toArray();
    }

    /**
     * Get current settings snapshot for orders
     */
    public static function getSnapshot()
    {
        return [
            'service_charge_enabled' => (bool) self::get('service_charge_enabled', 0),
            'service_charge_rate' => (float) self::get('service_charge_rate', 5),
            'vat_enabled' => (bool) self::get('vat_enabled', 1),
            'vat_rate' => (float) self::get('vat_rate', 10),
        ];
    }
}
