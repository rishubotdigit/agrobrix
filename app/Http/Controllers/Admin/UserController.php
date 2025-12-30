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
    public function index(Request $request)
    {
        $query = User::query();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('mobile', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Verification status filter
        if ($request->filled('verified')) {
            if ($request->verified === 'yes') {
                $query->whereNotNull('verified_at');
            } elseif ($request->verified === 'no') {
                $query->whereNull('verified_at');
            }
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $users = $query->latest()->paginate(20)->withQueryString();
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
        $plans = \App\Models\Plan::where('status', 'active')->get();
        $activePlanId = $user->activePlanPurchase()?->plan_id;
        return view('admin.users.edit', compact('user', 'plans', 'activePlanId'));
    }


    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,owner,agent,buyer',
            'profile_photo' => 'nullable|image|max:2048',
            'plan_id' => 'nullable|exists:plans,id',
        ]);

        $data = $request->only(['name', 'email', 'role']);

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        if ($request->filled('plan_id')) {
            $planId = $request->plan_id;
            $activePurchase = $user->activePlanPurchase();
            
            if (!$activePurchase || $activePurchase->plan_id != $planId) {
                // Deactivate all existing active plans
                \App\Models\PlanPurchase::deactivateActivePlansForUser($user->id);
                
                $plan = \App\Models\Plan::find($planId);
                \App\Models\PlanPurchase::create([
                    'user_id' => $user->id,
                    'plan_id' => $planId,
                    'status' => 'activated',
                    'activated_at' => now(),
                    'expires_at' => now()->addDays($plan->getValidityDays()),
                ]);
            }
        }

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
