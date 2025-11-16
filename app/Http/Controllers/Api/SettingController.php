<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = Setting::all()
            ->pluck('value', 'key')
            ->toArray();

        return response()->json([
            'settings' => $settings,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'app_name' => ['nullable', 'string', 'max:100'],
            'app_description' => ['nullable', 'string'],
            'currency_symbol' => ['nullable', 'string', 'max:5'],
            'warning_quantity' => ['nullable', 'integer', 'min:0'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return response()->json([
            'message' => 'Settings saved.',
            'settings' => Setting::all()->pluck('value', 'key')->toArray(),
        ]);
    }
}
