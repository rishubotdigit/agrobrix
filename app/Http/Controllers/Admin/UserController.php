<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
