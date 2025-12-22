<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserRegistered;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        $googleEnabled = Setting::get('google_login_enabled', '0') === '1';
        if (!$googleEnabled) {
            return redirect()->route('login')->withErrors(['social' => 'Google login is not enabled']);
        }

        // Retrieve credentials from DB
        $clientId = Setting::get('google_client_id', '');
        $clientSecret = Setting::get('google_client_secret', '');
        $redirectUri = Setting::get('google_redirect_uri', '');

        // Debug logs before setting config
        \Log::info('Google OAuth DB Values:', [
            'client_id' => $clientId,
            'client_secret' => $clientSecret ? 'set' : 'not set',
            'redirect' => $redirectUri,
        ]);

        // Set config dynamically from DB
        config(['services.google.client_id' => $clientId]);
        config(['services.google.client_secret' => $clientSecret]);
        config(['services.google.redirect' => $redirectUri]);

        // Debug logs after setting config
        \Log::info('Google OAuth Config After DB:', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret') ? 'set' : 'not set',
            'redirect' => config('services.google.redirect'),
        ]);

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        // Retrieve credentials from DB and set config
        $clientId = Setting::get('google_client_id', '');
        $clientSecret = Setting::get('google_client_secret', '');
        $redirectUri = Setting::get('google_redirect_uri', '');
        config(['services.google.client_id' => $clientId]);
        config(['services.google.client_secret' => $clientSecret]);
        config(['services.google.redirect' => $redirectUri]);

        \Log::info('Google OAuth Callback Config:', [
            'client_id' => config('services.google.client_id'),
            'client_secret' => config('services.google.client_secret') ? 'set' : 'not set',
        ]);

        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // User exists, log them in
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                }
                Auth::login($user);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->getName(),
                    'email' => $googleUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(16)), // Random password since OAuth
                    'role' => 'buyer', // Default role
                    'google_id' => $googleUser->getId(),
                ]);
                UserRegistered::dispatch($user);
                Auth::login($user);
            }

            return redirect($this->getRedirectUrl($user))->with('success', 'Logged in successfully with Google!');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['social' => 'Failed to login with Google. Please try again.']);
        }
    }

    public function redirectToFacebook()
    {
        $facebookEnabled = Setting::get('facebook_login_enabled', '0') === '1';
        if (!$facebookEnabled) {
            return redirect()->route('login')->withErrors(['social' => 'Facebook login is not enabled']);
        }

        // Retrieve credentials from DB
        $clientId = Setting::get('facebook_app_id', '');
        $clientSecret = Setting::get('facebook_app_secret', '');
        $redirectUri = Setting::get('facebook_redirect_uri', '');

        // Set config dynamically from DB
        config(['services.facebook.client_id' => $clientId]);
        config(['services.facebook.client_secret' => $clientSecret]);
        config(['services.facebook.redirect' => $redirectUri]);

        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        // Retrieve credentials from DB and set config
        $clientId = Setting::get('facebook_app_id', '');
        $clientSecret = Setting::get('facebook_app_secret', '');
        $redirectUri = Setting::get('facebook_redirect_uri', '');
        config(['services.facebook.client_id' => $clientId]);
        config(['services.facebook.client_secret' => $clientSecret]);
        config(['services.facebook.redirect' => $redirectUri]);

        try {
            $facebookUser = Socialite::driver('facebook')->user();

            $user = User::where('email', $facebookUser->getEmail())->first();

            if ($user) {
                // User exists, log them in
                if (!$user->email_verified_at) {
                    $user->email_verified_at = now();
                    $user->save();
                }
                Auth::login($user);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $facebookUser->getName(),
                    'email' => $facebookUser->getEmail(),
                    'email_verified_at' => now(),
                    'password' => bcrypt(Str::random(16)), // Random password since OAuth
                    'role' => 'buyer', // Default role
                    'facebook_id' => $facebookUser->getId(),
                ]);
                UserRegistered::dispatch($user);
                Auth::login($user);
            }

            return redirect($this->getRedirectUrl($user))->with('success', 'Logged in successfully with Facebook!');
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['social' => 'Failed to login with Facebook. Please try again.']);
        }
    }

    private function getRedirectUrl(User $user): string
    {
        return route('home');
    }
}