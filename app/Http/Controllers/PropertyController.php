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
        // Public property listing
        $properties = Property::with(['owner', 'agent', 'amenities'])->where('status', 'approved')
            ->orderByRaw('CASE WHEN featured_until > ? THEN 1 ELSE 0 END DESC', [now()])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Add wishlist status for authenticated buyers
        if (Auth::check() && Auth::user()->role === 'buyer') {
            $user = Auth::user();
            $wishlistPropertyIds = Wishlist::where('user_id', $user->id)->pluck('property_id')->toArray();

            $properties->getCollection()->transform(function ($property) use ($wishlistPropertyIds) {
                $property->is_in_wishlist = in_array($property->id, $wishlistPropertyIds);
                return $property;
            });
        }

        return view('properties', compact('properties'));
    }

    public function show(Property $property)
    {
        // Public property details
        return view('properties.show', compact('property'));
    }
}