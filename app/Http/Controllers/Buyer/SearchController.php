<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\PlanPurchase;
use App\Models\Property;
use App\Models\Setting;
use App\Models\ViewedContact;
use App\Models\Wishlist;
use App\OtpService;
use App\Traits\CapabilityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

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
        $minPrice = $request->get('min_price', '');
        $maxPrice = $request->get('max_price', '');
        $landType = $request->get('land_type', '');
        $minArea = $request->get('min_area', '');
        $maxArea = $request->get('max_area', '');
        $amenities = $request->get('amenities', []);

        $properties = Property::with(['owner', 'amenities', 'district.state'])
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('area', 'like', "%{$query}%")
                        ->orWhereHas('district.state', function ($stateQuery) use ($query) {
                            $stateQuery->where('name', 'like', "%{$query}%");
                        });
                });
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
            ->paginate(10);

        // Add wishlist and contact viewed status for authenticated buyers
        if (Auth::check() && Auth::user()->role === 'buyer') {
            $wishlistPropertyIds = Wishlist::where('user_id', Auth::id())->pluck('property_id')->toArray();
            $viewedContactPropertyIds = ViewedContact::where('buyer_id', Auth::id())->pluck('property_id')->toArray();
            $properties->getCollection()->transform(function ($property) use ($wishlistPropertyIds, $viewedContactPropertyIds) {
                $property->is_in_wishlist = in_array($property->id, $wishlistPropertyIds);
                $property->contact_viewed = in_array($property->id, $viewedContactPropertyIds);
                return $property;
            });
        }

        // Get filter options
        $states = \App\Models\State::orderBy('name')->get();
        $landTypes = Property::distinct()->pluck('land_type')->filter()->sort();
        $categoriesWithAmenities = \App\Models\Category::with('amenities')->get();

        return view('search.advanced', compact(
            'properties',
            'query',
            'minPrice',
            'maxPrice',
            'landType',
            'minArea',
            'maxArea',
            'amenities',
            'states',
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
        $query = Property::with(['owner', 'district.state']);

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
            $query->where('state', 'like', "%{$location}%");
        }
        if ($category) {
            $query->where('land_type', $category);
        }

        $properties = $query->where('status', 'approved')->paginate(10);

        // Get user's wishlist and viewed contact property IDs
        $wishlistPropertyIds = Wishlist::where('user_id', $user->id)->pluck('property_id')->toArray();
        $viewedContactPropertyIds = ViewedContact::where('buyer_id', $user->id)->pluck('property_id')->toArray();
        
        // Add wishlist and contact viewed status to each property
        $properties->getCollection()->transform(function ($property) use ($wishlistPropertyIds, $viewedContactPropertyIds) {
            $property->is_in_wishlist = in_array($property->id, $wishlistPropertyIds);
            $property->contact_viewed = in_array($property->id, $viewedContactPropertyIds);
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

}