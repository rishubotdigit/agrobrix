<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all();
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plans',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'max_listings' => 'required|integer|min:0',
            'max_contacts' => 'required|integer|min:0',
            'max_featured_listings' => 'nullable|integer|min:0',
            'featured_duration_days' => 'nullable|integer|min:0',
        ]);

        $capabilities = [
            'max_listings' => $validated['max_listings'],
            'max_contacts' => $validated['max_contacts'],
            'max_featured_listings' => $validated['max_featured_listings'],
            'featured_duration_days' => $validated['featured_duration_days'],
        ];

        Plan::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'capabilities' => $capabilities,
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully');
    }

    public function show(Plan $plan)
    {
        return view('admin.plans.show', compact('plan'));
    }

    public function edit(Plan $plan)
    {
        return view('admin.plans.edit', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:plans,name,' . $plan->id,
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'max_listings' => 'required|integer|min:0',
            'max_contacts' => 'required|integer|min:0',
            'max_featured_listings' => 'nullable|integer|min:0',
            'featured_duration_days' => 'nullable|integer|min:0',
        ]);

        $capabilities = [
            'max_listings' => $validated['max_listings'],
            'max_contacts' => $validated['max_contacts'],
            'max_featured_listings' => $validated['max_featured_listings'],
            'featured_duration_days' => $validated['featured_duration_days'],
        ];

        $plan->update([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'description' => $validated['description'],
            'capabilities' => $capabilities,
        ]);

        return redirect()->route('admin.plans.index')->with('success', 'Plan updated successfully');
    }

    public function destroy(Plan $plan)
    {
        // Check if plan has any plan purchases
        if ($plan->planPurchases()->exists()) {
            return redirect()->route('admin.plans.index')->with('error', 'Cannot delete plan that has active plan purchases');
        }

        $plan->delete();
        return redirect()->route('admin.plans.index')->with('success', 'Plan deleted successfully');
    }
}