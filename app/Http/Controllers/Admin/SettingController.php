<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'login_enabled' => Setting::get('login_enabled', '1'),
            'registration_enabled' => Setting::get('registration_enabled', '1'),
            'otp_verification_enabled' => Setting::get('otp_verification_enabled', '1'),
            'mobile_integration_enabled' => Setting::get('mobile_integration_enabled', '1'),
            'whatsapp_notifications_enabled' => Setting::get('whatsapp_notifications_enabled', '1'),
            'google_maps_api_key' => Setting::get('google_maps_api_key', ''),
            'google_login_enabled' => Setting::get('google_login_enabled', '0'),
            'facebook_login_enabled' => Setting::get('facebook_login_enabled', '0'),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'login_enabled' => 'nullable|boolean',
            'registration_enabled' => 'nullable|boolean',
            'otp_verification_enabled' => 'nullable|boolean',
            'mobile_integration_enabled' => 'nullable|boolean',
            'whatsapp_notifications_enabled' => 'nullable|boolean',
            'google_maps_api_key' => 'nullable|string|max:255',
            'google_login_enabled' => 'nullable|boolean',
            'facebook_login_enabled' => 'nullable|boolean',
        ]);

        Setting::set('login_enabled', $request->has('login_enabled') ? '1' : '0');
        Setting::set('registration_enabled', $request->has('registration_enabled') ? '1' : '0');
        Setting::set('otp_verification_enabled', $request->has('otp_verification_enabled') ? '1' : '0');
        Setting::set('mobile_integration_enabled', $request->has('mobile_integration_enabled') ? '1' : '0');
        Setting::set('whatsapp_notifications_enabled', $request->has('whatsapp_notifications_enabled') ? '1' : '0');
        Setting::set('google_maps_api_key', $request->input('google_maps_api_key', ''));
        Setting::set('google_login_enabled', $request->has('google_login_enabled') ? '1' : '0');
        Setting::set('facebook_login_enabled', $request->has('facebook_login_enabled') ? '1' : '0');

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
