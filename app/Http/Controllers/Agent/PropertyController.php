<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $properties = Property::where('agent_id', $user->id)->with('owner')->paginate(20);

        $activePurchase = $user->activePlanPurchase();
        $usage = [
            'current_featured' => Property::where('agent_id', $user->id)->where('featured', true)->count(),
            'max_featured' => $activePurchase && $activePurchase->plan ? $activePurchase->plan->getMaxFeaturedListings() : 0
        ];

        return view('agent.properties.index', compact('properties', 'usage'));
    }

    public function show(Property $property)
    {
        if ($property->agent_id !== auth()->id()) {
            abort(403, 'Unauthorized access to property.');
        }
        return view('agent.properties.show', compact('property'));
    }
   
}