<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::all()->groupBy('role');
        return view('admin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('admin.plans.create');
    }

    public function store(Request $request)
    {
        try {
            Log::info('PlanController store method reached', ['request' => $request->all()]);

            $rules = [
                'role' => 'required|in:buyer,owner,agent',
                'name' => 'required|string|max:255|unique:plans,name,NULL,id,role,' . $request->role,
                'original_price' => 'required|numeric|min:0',
                'offer_price' => 'nullable|numeric|min:0',
                'discount' => 'nullable|numeric|min:0|max:100',
                'validity_days' => 'required|integer|min:1',
                'status' => 'required|in:active,inactive',
                'description' => 'nullable|string',
            ];

            if ($request->role === 'buyer') {
                $rules['contacts_to_unlock'] = 'required|integer|min:0';
                $rules['persona'] = 'nullable|string';
            } elseif (in_array($request->role, ['owner', 'agent'])) {
                $rules['max_listings'] = 'nullable|integer|min:0';
                $rules['max_contacts'] = 'nullable|integer|min:0';
                $rules['max_featured_listings'] = 'nullable|integer|min:0';
                $rules['featured_duration_days'] = 'nullable|integer|min:0';
            }

            Log::info('About to validate request');
            $validated = $request->validate($rules);
            Log::info('Validation passed', ['validated_keys' => array_keys($validated)]);

            // Check features for owner/agent
            if ($request->role !== 'buyer') {
                if (!$request->has('features') || trim($request->features) === '') {
                    return redirect()->back()->withErrors(['features' => 'Features are required.'])->withInput();
                }
            }

            // Normalize features to array
            if ($request->has('features')) {
                $features = $request->features;
                if (is_string($features)) {
                    $features = array_filter(array_map('trim', explode("\n", $features)));
                } elseif (is_array($features)) {
                    $features = array_filter(array_map('trim', $features));
                } else {
                    $features = [$features];
                }
                $validated['features'] = $features;
            }

            $price = $validated['offer_price'] ?? $validated['original_price'];
            if ($price === '' || $price === null) {
                $price = 0;
            }
            $validity_period_days = $validated['validity_days'];
            $capabilities = [];
            if ($validated['role'] === 'buyer') {
                $capabilities['max_contacts'] = $validated['contacts_to_unlock'];
            } elseif (in_array($validated['role'], ['owner', 'agent'])) {
                $capabilities['max_listings'] = $validated['max_listings'] ?? 0;
                $capabilities['max_contacts'] = $validated['max_contacts'] ?? 0;
                $capabilities['max_featured_listings'] = $validated['max_featured_listings'] ?? 0;
                $capabilities['featured_duration_days'] = $validated['featured_duration_days'] ?? 0;
            }

            $planData = [
                'name' => $validated['name'],
                'price' => $price,
                'original_price' => $validated['original_price'],
                'offer_price' => $validated['offer_price'],
                'discount' => $validated['discount'],
                'validity_period_days' => $validity_period_days,
                'validity_days' => $validated['validity_days'],
                'role' => $validated['role'],
                'capabilities' => $capabilities,
                'features' => $validated['role'] !== 'buyer' ? $validated['features'] : null,
                'contacts_to_unlock' => $validated['role'] === 'buyer' ? $validated['contacts_to_unlock'] : null,
                'persona' => $validated['role'] === 'buyer' ? $validated['persona'] : null,
                'status' => $validated['status'],
                'description' => $validated['description'],
            ];

            Log::info('About to create plan', ['plan_data' => $planData]);
            $plan = Plan::create($planData);
            Log::info('Plan created successfully', ['plan_id' => $plan->id]);

            return redirect()->route('admin.plans.index')->with('success', 'Plan created successfully');
        } catch (\Exception $e) {
            Log::error('Error in PlanController store', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);
            return redirect()->back()->with('error', 'Failed to create plan. Please try again.');
        }
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
        $role = $request->role ?? $plan->role;

        Log::info('Update request features', ['features' => $request->features, 'type' => gettype($request->features)]);

        $rules = [
            'role' => 'nullable|in:buyer,owner,agent',
            'name' => 'required|string|max:255|unique:plans,name,' . $plan->id . ',id,role,' . $role,
            'original_price' => 'required|numeric|min:0',
            'offer_price' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0|max:100',
            'validity_days' => 'required|integer|min:1',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string',
        ];

        if ($role === 'buyer') {
            $rules['contacts_to_unlock'] = 'required|integer|min:0';
            $rules['persona'] = 'nullable|string';
        } elseif (in_array($role, ['owner', 'agent'])) {
            $rules['max_listings'] = 'nullable|integer|min:0';
            $rules['max_contacts'] = 'nullable|integer|min:0';
            $rules['max_featured_listings'] = 'nullable|integer|min:0';
            $rules['featured_duration_days'] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($rules);

        // Check features for owner/agent
        if ($role !== 'buyer') {
            if (!$request->has('features') || trim($request->features) === '') {
                return redirect()->back()->withErrors(['features' => 'Features are required.'])->withInput();
            }
        }

        // Normalize features to array
        if ($request->has('features')) {
            $features = $request->features;
            if (is_string($features)) {
                $features = array_filter(array_map('trim', explode("\n", $features)));
            } elseif (is_array($features)) {
                $features = array_filter(array_map('trim', $features));
            } else {
                $features = [$features];
            }
            $validated['features'] = $features;
        }

        $price = $validated['offer_price'] ?? $validated['original_price'];
        $validity_period_days = $validated['validity_days'];
        $capabilities = [];
        if ($role === 'buyer') {
            $capabilities['max_contacts'] = $validated['contacts_to_unlock'];
        } elseif (in_array($role, ['owner', 'agent'])) {
            $capabilities['max_listings'] = $validated['max_listings'] ?? 0;
            $capabilities['max_contacts'] = $validated['max_contacts'] ?? 0;
            $capabilities['max_featured_listings'] = $validated['max_featured_listings'] ?? 0;
            $capabilities['featured_duration_days'] = $validated['featured_duration_days'] ?? 0;
        }

        $plan->update([
            'name' => $validated['name'],
            'price' => $price,
            'original_price' => $validated['original_price'],
            'offer_price' => $validated['offer_price'],
            'discount' => $validated['discount'],
            'validity_period_days' => $validity_period_days,
            'validity_days' => $validated['validity_days'],
            'role' => $validated['role'] ?? $plan->role,
            'capabilities' => $capabilities,
            'features' => $role !== 'buyer' ? $validated['features'] : null,
            'contacts_to_unlock' => $role === 'buyer' ? $validated['contacts_to_unlock'] : null,
            'persona' => $role === 'buyer' ? $validated['persona'] : null,
            'status' => $validated['status'],
            'description' => $validated['description'],
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