<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        // Public property listing with filtering
        $query = Property::with(['owner', 'amenities', 'district.state'])->where('status', 'approved');

        // Apply filters
        if ($request->filled('q')) {
            $searchTerm = '%' . $request->q . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'LIKE', $searchTerm)
                  ->orWhere('description', 'LIKE', $searchTerm)
                  ->orWhere('full_address', 'LIKE', $searchTerm)
                  ->orWhere('meta_keywords', 'LIKE', $searchTerm);
            });
        }

        if ($request->filled('state_id')) {
            $query->whereHas('district', function($q) use ($request) {
                $q->where('state_id', $request->state_id);
            });
        }

        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        if ($request->filled('land_type')) {
            $query->where('land_type', $request->land_type);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->filled('min_area')) {
            $query->where('plot_area', '>=', $request->min_area);
        }

        if ($request->filled('max_area')) {
            $query->where('plot_area', '<=', $request->max_area);
        }

        $properties = $query->orderByRaw('CASE WHEN featured_until > ? THEN 1 ELSE 0 END DESC', [now()])
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString();

        // Add wishlist status for all authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            $wishlistPropertyIds = Wishlist::where('user_id', $user->id)->pluck('property_id')->toArray();

            $properties->getCollection()->transform(function ($property) use ($wishlistPropertyIds) {
                $property->is_in_wishlist = in_array($property->id, $wishlistPropertyIds);
                return $property;
            });
        }

        $states = \App\Models\State::orderBy('name')->get();
        $districts = $request->filled('state_id') 
            ? \App\Models\District::where('state_id', $request->state_id)->orderBy('name')->get()
            : collect();

        return view('properties.index', compact('properties', 'states', 'districts'));
    }

    public function show(Property $property)
    {
        // Public property details
        $property->load(['owner', 'amenities', 'district.state']);
        
        if (Auth::check()) {
            $property->is_in_wishlist = Wishlist::where('user_id', Auth::id())
                ->where('property_id', $property->id)
                ->exists();
            $hasContacted = \App\Models\ViewedContact::where('buyer_id', Auth::id())
                ->where('property_id', $property->id)
                ->exists();
        } else {
            $hasContacted = false;
        }
        
        // Eager load analytics
        $property->load('analytics');

        return view('properties.show', compact('property', 'hasContacted'));
    }
}