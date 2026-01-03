<?php

namespace App\Http\Controllers\Buyer;

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

        return view('buyer.wishlist.index', ['properties' => $properties]);
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

        // Check if already in wishlist
        $existing = Wishlist::where('user_id', $user->id)->where('property_id', $propertyId)->first();
        if ($existing) {
            return back()->with('info', 'Property is already in your wishlist.');
        }

        // Add to wishlist
        Wishlist::create(['user_id' => $user->id, 'property_id' => $propertyId]);

        return back()->with('success', 'Property added to your wishlist.');
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