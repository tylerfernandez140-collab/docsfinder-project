<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value','key')->toArray();
        return view('settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->only(['system_name', 'default_role', 'timezone', 'theme']);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return back()->with('success', 'System preferences updated successfully.');
    }
}

