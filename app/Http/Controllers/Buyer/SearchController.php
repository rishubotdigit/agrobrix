<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\PlanPurchase;
use App\Models\Property;
use App\Models\Setting;
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

        // Bypass owner contact limit checks for buyers - they have unlimited free access
        if ($user->role !== 'buyer') {
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

            // Only increment used_contacts if not a buyer (buyers have free unlimited access)
            if ($user->role !== 'buyer') {
                $activePurchases = $property->owner->activePlanPurchases();
                $activePurchases->first()->increment('used_contacts');
            }
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

    public function submitInquiry(Request $request, Property $property)
    {
        $validator = Validator::make($request->all(), [
            'buyer_name' => 'required|string|max:255',
            'buyer_email' => 'nullable|email|max:255',
            'buyer_phone' => 'required|string|max:15',
            'buyer_type' => 'required|in:agent,buyer',
            'buying_purpose' => 'nullable|string|max:255',
            'buying_timeline' => 'nullable|in:3 months,6 months,More than 6 months',
            'interested_in_site_visit' => 'nullable|boolean',
            'additional_message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Store inquiry data in session
        Session::put('inquiry_data', [
            'property_id' => $property->id,
            'buyer_name' => $request->buyer_name,
            'buyer_email' => $request->buyer_email,
            'buyer_phone' => $request->buyer_phone,
            'buyer_type' => $request->buyer_type,
            'buying_purpose' => $request->buying_purpose,
            'buying_timeline' => $request->buying_timeline,
            'interested_in_site_visit' => $request->interested_in_site_visit ?? false,
            'additional_message' => $request->additional_message,
        ]);

        // Check if OTP is enabled
        if (Setting::get('otp_verification_enabled') == '1') {
            $otpService = new OtpService();
            $otp = $otpService->generateOtp();
            $result = $otpService->sendOtpToMobile($request->buyer_phone, $otp);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP. Please try again.'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'otp_required' => true,
                'message' => 'Inquiry submitted. Please verify your phone number with the OTP sent.'
            ]);
        }

        // If OTP not enabled, proceed to create lead
        return $this->createLeadFromSession($property);
    }

    public function verifyOtp(Request $request, Property $property)
    {
        $validator = Validator::make($request->all(), [
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $otpService = new OtpService();
        if (!$otpService->verifyOtpFromSession($request->otp)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP.'
            ], 400);
        }

        return $this->createLeadFromSession($property);
    }

    private function createLeadFromSession(Property $property)
    {
        $inquiryData = Session::get('inquiry_data');

        if (!$inquiryData || $inquiryData['property_id'] != $property->id) {
            return response()->json([
                'success' => false,
                'message' => 'Inquiry data not found.'
            ], 400);
        }

        // Check if lead already exists
        $existingLead = Lead::where('property_id', $property->id)
            ->where('buyer_email', $inquiryData['buyer_email'])
            ->first();

        if ($existingLead) {
            // Update existing lead
            $existingLead->update(array_merge($inquiryData, [
                'agent_id' => $property->agent_id ?? null,
            ]));
        } else {
            // Create new lead
            Lead::create(array_merge($inquiryData, [
                'agent_id' => $property->agent_id ?? null,
            ]));
        }

        // Capture inquiry data before clearing session
        $inquiry = $inquiryData;

        // Clear session data
        Session::forget('inquiry_data');

        // Return contact information and inquiry data
        return response()->json([
            'success' => true,
            'contact' => [
                'owner_name' => $property->owner->name,
                'owner_email' => $property->owner->email,
                'owner_mobile' => $property->owner->mobile,
            ],
            'inquiry' => $inquiry
        ]);
    }
}