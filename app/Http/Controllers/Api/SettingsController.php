<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Get all settings
     */
    public function index()
    {
        $settings = Setting::getAllSettings();
        
        return response()->json([
            'service_charge_enabled' => (bool) ($settings['service_charge_enabled'] ?? 0),
            'service_charge_rate' => (float) ($settings['service_charge_rate'] ?? 5),
            'vat_enabled' => (bool) ($settings['vat_enabled'] ?? 1),
            'vat_rate' => (float) ($settings['vat_rate'] ?? 10),
            'table_count' => (int) ($settings['table_count'] ?? 12),
            'seats_per_table' => (int) ($settings['seats_per_table'] ?? 4),
            'currency_code' => $settings['currency_code'] ?? 'VND',
            'currency_symbol' => $settings['currency_symbol'] ?? '₫',
        ]);
    }

    /**
     * Update settings
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'service_charge_enabled' => 'sometimes|boolean',
            'service_charge_rate' => 'sometimes|numeric|min:0|max:100',
            'vat_enabled' => 'sometimes|boolean',
            'vat_rate' => 'sometimes|numeric|min:0|max:100',
            'table_count' => 'sometimes|integer|min:1|max:100',
            'seats_per_table' => 'sometimes|integer|min:1|max:20',
            'currency_code' => 'sometimes|string|max:10',
            'currency_symbol' => 'sometimes|string|max:5',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return response()->json([
            'message' => 'Settings updated successfully',
            'settings' => Setting::getAllSettings(),
        ]);
    }

    /**
     * Get settings snapshot
     */
    public function snapshot()
    {
        return response()->json(Setting::getSnapshot());
    }
}
