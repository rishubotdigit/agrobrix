<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            if (!$user->isVerified()) {
                Auth::logout();
                return back()->withErrors(['email' => 'Please verify your account first.']);
            }
            
            // Check if user had a pending deletion request and cancel it
            $deletionCancelled = false;
            if ($user->isPendingDeletion()) {
                $user->cancelDeletionRequest();
                $deletionCancelled = true;
            }
            
            $intended = session('url.intended', $this->getRedirectUrl($user));
            
            if ($deletionCancelled) {
                return redirect($intended)->with('success', 'Welcome back! Your account deletion request has been cancelled and your account is now active.');
            }
            
            return redirect($intended)->with('success', 'Login successful! Welcome back.');
        }
        return back()->withErrors(['email' => 'Invalid credentials']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    private function getRedirectUrl(User $user): string
    {
        if ($user->role === 'owner' || $user->role === 'agent') {
            return route('owner.dashboard');
        }
        if ($user->role === 'admin') {
            return route('admin.dashboard');
        }
        return route('home');
    }
}