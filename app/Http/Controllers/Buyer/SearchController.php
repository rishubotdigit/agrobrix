<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\PlanPurchase;
use App\Models\Property;
use App\Models\Wishlist;
use App\Traits\CapabilityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    use CapabilityTrait;

    public function properties(Request $request)
    {
        return view('buyer.properties');
    }

    public function index(Request $request)
    {
        // Handle search functionality
        return view('buyer.properties');
    }

    public function advanced(Request $request)
    {
        $query = $request->get('q', '');
        $state = $request->get('state', '');
        $city = $request->get('city', '');
        $minPrice = $request->get('min_price', '');
        $maxPrice = $request->get('max_price', '');
        $landType = $request->get('land_type', '');
        $minArea = $request->get('min_area', '');
        $maxArea = $request->get('max_area', '');
        $amenities = $request->get('amenities', []);

        $properties = Property::with(['owner', 'agent', 'amenities'])
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('city', 'like', "%{$query}%")
                        ->orWhere('state', 'like', "%{$query}%")
                        ->orWhere('area', 'like', "%{$query}%");
                });
            })
            ->when($state, function ($q) use ($state) {
                $q->where('state', $state);
            })
            ->when($city, function ($q) use ($city) {
                $q->where('city', $city);
            })
            ->when($minPrice, function ($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice);
            })
            ->when($maxPrice, function ($q) use ($maxPrice) {
                $q->where('price', '<=', $maxPrice);
            })
            ->when($landType, function ($q) use ($landType) {
                $q->where('land_type', $landType);
            })
            ->when($minArea, function ($q) use ($minArea) {
                $q->where('plot_area', '>=', $minArea);
            })
            ->when($maxArea, function ($q) use ($maxArea) {
                $q->where('plot_area', '<=', $maxArea);
            })
            ->when($amenities, function ($q) use ($amenities) {
                if (is_array($amenities) && !empty($amenities)) {
                    $q->whereHas('amenities', function ($amenityQuery) use ($amenities) {
                        $amenityQuery->whereIn('amenities.id', $amenities);
                    });
                }
            })
            ->where('status', 'approved')
            ->paginate(12);

        // Add wishlist status for authenticated buyers
        if (Auth::check() && Auth::user()->role === 'buyer') {
            $wishlistPropertyIds = Wishlist::where('user_id', Auth::id())->pluck('property_id')->toArray();
            $properties->getCollection()->transform(function ($property) use ($wishlistPropertyIds) {
                $property->is_in_wishlist = in_array($property->id, $wishlistPropertyIds);
                return $property;
            });
        }

        // Get filter options
        $states = Property::distinct()->pluck('state')->filter()->sort();
        $cities = Property::distinct()->pluck('city')->filter()->sort();
        $landTypes = Property::distinct()->pluck('land_type')->filter()->sort();
        $categoriesWithAmenities = \App\Models\Category::with('amenities')->get();

        return view('search.advanced', compact(
            'properties',
            'query',
            'state',
            'city',
            'minPrice',
            'maxPrice',
            'landType',
            'minArea',
            'maxArea',
            'amenities',
            'states',
            'cities',
            'landTypes',
            'categoriesWithAmenities'
        ));
    }

    public function getPropertyOptions(Request $request)
    {
        $landTypes = Property::distinct()->pluck('land_type')->filter()->sort()->values();

        return response()->json([
            'land_types' => $landTypes
        ]);
    }

    public function getPropertiesApi(Request $request)
    {
        $user = Auth::user();
        $query = Property::with('owner');

        // Apply filters
        $minPrice = $request->get('min_price');
        $maxPrice = $request->get('max_price');
        $location = $request->get('location');
        $category = $request->get('category');

        if ($minPrice) {
            $query->where('price', '>=', $minPrice);
        }
        if ($maxPrice) {
            $query->where('price', '<=', $maxPrice);
        }
        if ($location) {
            $query->where(function ($q) use ($location) {
                $q->where('city', 'like', "%{$location}%")
                  ->orWhere('state', 'like', "%{$location}%");
            });
        }
        if ($category) {
            $query->where('land_type', $category);
        }

        $properties = $query->where('status', 'approved')->paginate(10);

        // Get user's wishlist property IDs
        $wishlistPropertyIds = Wishlist::where('user_id', $user->id)->pluck('property_id')->toArray();

        // Add wishlist status to each property
        $properties->getCollection()->transform(function ($property) use ($wishlistPropertyIds) {
            $property->is_in_wishlist = in_array($property->id, $wishlistPropertyIds);
            return $property;
        });

        return response()->json([
            'properties' => $properties,
            'usage' => [
                'contacts_viewed' => $user->viewedContacts()->count(),
                'max_contacts' => $this->getCapabilityValue($user, 'max_contacts')
            ]
        ]);
    }

    public function viewContact(Request $request, Property $property)
    {
        $user = Auth::user();

        // Get owner's active plan purchases
        $activePurchases = $property->owner->activePlanPurchases();

        if ($activePurchases->isEmpty()) {
            return response()->json([
                'error' => 'Owner has no active plan.'
            ], 403);
        }

        // Check max_contacts capability
        $maxContacts = $this->getCapabilityValue($property->owner, 'max_contacts');
        $totalUsedContacts = $activePurchases->sum('used_contacts');

        if ($totalUsedContacts >= $maxContacts) {
            return response()->json([
                'error' => 'Owner has reached contact limit, please try later.'
            ], 403);
        }

        // Check if lead already exists
        $existingLead = Lead::where('property_id', $property->id)
            ->where('buyer_name', $user->name)
            ->where('buyer_email', $user->email)
            ->exists();

        if (!$existingLead) {
            // Create lead for assigned agent or owner
            $agentId = $property->agent_id ?? null;

            Lead::create([
                'property_id' => $property->id,
                'agent_id' => $agentId,
                'buyer_name' => $user->name,
                'buyer_email' => $user->email,
                'buyer_phone' => $user->mobile ?? '',
            ]);

            // Increment used_contacts on the first active purchase
            $activePurchases->first()->increment('used_contacts');
        }

        // Return contact information
        return response()->json([
            'contact' => [
                'owner_name' => $property->owner->name,
                'owner_email' => $property->owner->email,
                'owner_mobile' => $property->owner->mobile,
            ]
        ]);
    }
}