<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{

    public function index(Request $request)
    {
        $user = Auth::user();
        $properties = Wishlist::where('user_id', $user->id)
            ->with('property')
            ->get()
            ->pluck('property')
            ->filter(); // Remove nulls if any

        // Determine view based on user role
        $viewPath = match($user->role) {
            'buyer' => 'buyer.wishlist.index',
            'owner' => 'owner.wishlist.index',
            'agent' => 'agent.wishlist.index',
            'admin' => 'admin.wishlist.index',
            default => 'buyer.wishlist.index',
        };

        return view($viewPath, ['properties' => $properties]);
    }

    public function add(Request $request)
    {
        $user = Auth::user();
        $propertyId = $request->input('property_id');

        // Check if property exists
        $property = \App\Models\Property::find($propertyId);
        if (!$property) {
            return back()->with('error', 'Property not found.');
        }

        // Role-based validation: Owners cannot save their own properties
        if ($user->role === 'owner' && $property->owner_id === $user->id) {
            return back()->with('error', 'You cannot save your own property.');
        }

        // Role-based validation: Agents cannot save properties they manage
        if ($user->role === 'agent' && $property->agent_id === $user->id) {
            return back()->with('error', 'You cannot save properties you manage.');
        }

        // Check if already in wishlist
        $existing = Wishlist::where('user_id', $user->id)->where('property_id', $propertyId)->first();
        if ($existing) {
            return back()->with('info', 'Property is already in your saved properties.');
        }

        // Add to wishlist
        Wishlist::create(['user_id' => $user->id, 'property_id' => $propertyId]);

        return back()->with('success', 'Property saved successfully.');
    }

    public function remove(Request $request, $propertyId)
    {
        $user = Auth::user();

        // Find the wishlist item
        $wishlist = Wishlist::where('user_id', $user->id)->where('property_id', $propertyId)->first();

        if (!$wishlist) {
            return response()->json(['error' => 'Wishlist item not found.'], 404);
        }

        // Remove from wishlist
        $wishlist->delete();

        return response()->json(['success' => 'Removed from wishlist']);
    }
}