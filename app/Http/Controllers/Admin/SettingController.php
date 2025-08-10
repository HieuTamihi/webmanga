<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function edit()
    {
        $setting = \App\Models\Setting::first();
        return view('admin.settings.edit', compact('setting'));
    }

    public function update(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'site_title' => 'nullable|string|max:255',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,svg|max:2048',
        ]);

        $setting = \App\Models\Setting::firstOrCreate([]);

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('logos', 'public');
            $data['logo'] = $path;
        }

        $setting->update($data);
        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated');
    }
}
