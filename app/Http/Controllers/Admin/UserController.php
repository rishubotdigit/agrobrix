<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ImpersonationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function showPlans(User $user)
    {
        $activePlanPurchases = $user->activePlanPurchases();
        $combinedCapabilities = $user->getCombinedCapabilities();

        return view('admin.users.plans', compact('user', 'activePlanPurchases', 'combinedCapabilities'));
    }
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:owner,agent,buyer',
        ]);

        $user->update($request->only(['name', 'email', 'role']));

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        DB::transaction(function () use ($user) {
            $user->delete();
        });
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function impersonate(Request $request, User $user)
    {
        // Check if current user is admin
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        // Prevent impersonating admins
        if ($user->role === 'admin') {
            return redirect()->back()->with('error', 'Cannot impersonate an admin user.');
        }

        // Prevent double impersonation
        if (session('impersonate')) {
            return redirect()->back()->with('error', 'Already impersonating a user.');
        }

        // Log the action
        ImpersonationLog::create([
            'admin_id' => Auth::id(),
            'impersonated_user_id' => $user->id,
            'started_at' => now(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Store original admin ID in session
        session(['impersonate' => Auth::id()]);

        // Login as the target user
        Auth::login($user);

        return redirect('/')->with('success', 'Now impersonating ' . $user->name);
    }

    public function stopImpersonating()
    {
        $originalAdminId = session('impersonate');

        if (!$originalAdminId) {
            return redirect()->back()->with('error', 'Not currently impersonating.');
        }

        // Update the log
        ImpersonationLog::where('admin_id', $originalAdminId)
            ->where('impersonated_user_id', Auth::id())
            ->whereNull('ended_at')
            ->update(['ended_at' => now()]);

        // Login back as admin
        $admin = User::find($originalAdminId);
        Auth::login($admin);

        // Clear session
        session()->forget('impersonate');

        return redirect()->route('admin.users.index')->with('success', 'Stopped impersonating.');
    }
}
