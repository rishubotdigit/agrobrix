<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SocialLoginController extends Controller
{
    public function index()
    {
        $settings = [
            'google_login_enabled' => Setting::get('google_login_enabled', '0'),
            'google_client_id' => Setting::get('google_client_id', ''),
            'google_client_secret' => Setting::get('google_client_secret', ''),
            'google_redirect_uri' => Setting::get('google_redirect_uri', ''),
            'facebook_login_enabled' => Setting::get('facebook_login_enabled', '0'),
            'facebook_app_id' => Setting::get('facebook_app_id', ''),
            'facebook_app_secret' => Setting::get('facebook_app_secret', ''),
            'facebook_redirect_uri' => Setting::get('facebook_redirect_uri', ''),
        ];

        return view('admin.social-logins.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'google_login_enabled' => 'nullable|boolean',
            'google_client_id' => 'nullable|string|max:255',
            'google_client_secret' => 'nullable|string|max:255',
            'google_redirect_uri' => 'nullable|url|max:255',
            'facebook_login_enabled' => 'nullable|boolean',
            'facebook_app_id' => 'nullable|string|max:255',
            'facebook_app_secret' => 'nullable|string|max:255',
            'facebook_redirect_uri' => 'nullable|url|max:255',
        ]);

        Setting::set('google_login_enabled', $request->has('google_login_enabled') ? '1' : '0');
        Setting::set('google_client_id', $request->google_client_id ?? '');
        Setting::set('google_client_secret', $request->google_client_secret ?? '');
        Setting::set('google_redirect_uri', $request->google_redirect_uri ?? '');

        Setting::set('facebook_login_enabled', $request->has('facebook_login_enabled') ? '1' : '0');
        Setting::set('facebook_app_id', $request->facebook_app_id ?? '');
        Setting::set('facebook_app_secret', $request->facebook_app_secret ?? '');
        Setting::set('facebook_redirect_uri', $request->facebook_redirect_uri ?? '');

        return redirect()->route('admin.social-logins.index')->with('success', 'Social login settings updated successfully.');
    }
}