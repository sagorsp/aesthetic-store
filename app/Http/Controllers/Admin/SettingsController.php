<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        return view('admin.settings', [
            'settings' => Setting::pluck('value', 'key')->all()
        ]);
    }

    public function update(Request $r)
    {
        $d = $r->validate([
            'hero_title' => 'required|string|max:190',
            'hero_subtitle' => 'required|string|max:500',
            'hero_eyebrow' => 'nullable|string|max:80',
            'hero_button_text' => 'nullable|string|max:60',
            'hero_button_url' => 'nullable|string|max:500',
            'hero_banner' => 'nullable|image|max:5120',

            'store_logo' => 'nullable|image|max:3072',
            'support_phone' => 'required|string|max:40',
            'support_email' => 'required|email|max:190',
            'address' => 'nullable|string|max:500',

            'facebook_url' => 'nullable|url|max:500',
            'instagram_url' => 'nullable|url|max:500',
            'tiktok_url' => 'nullable|url|max:500',
            'youtube_url' => 'nullable|url|max:500',
        ]);

        unset($d['hero_banner'], $d['store_logo']);

        if ($r->hasFile('hero_banner')) {
            $old = Setting::where('key', 'hero_banner')->value('value');
            if ($old) Storage::disk('public')->delete($old);
            $d['hero_banner'] = $r->file('hero_banner')->store('banners', 'public');
        }

        if ($r->hasFile('store_logo')) {
            $old = Setting::where('key', 'store_logo')->value('value');
            if ($old) Storage::disk('public')->delete($old);
            $d['store_logo'] = $r->file('store_logo')->store('branding', 'public');
        }

        foreach ($d as $k => $v) {
            Setting::updateOrCreate(['key' => $k], ['value' => $v ?? '']);
        }

        return back()->with('success', 'Settings saved.');
    }
}
