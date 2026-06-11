<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::first() ?? new Setting();
        return view('admin.settings', compact('setting'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'warung_name' => 'required|string|max:255',
            'warung_address' => 'nullable|string',
            'warung_phone' => 'nullable|string|max:50',
            'warung_description' => 'nullable|string',
        ]);

        $setting = Setting::first();
        if ($setting) {
            $setting->update($validated);
        } else {
            Setting::create($validated);
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan sistem berhasil diperbarui.');
    }
}
