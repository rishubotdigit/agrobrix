<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Traits\CapabilityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyPropertyController extends Controller
{
    use CapabilityTrait;

    public function index(Request $request)
    {
        $user = Auth::user();
        $properties = $user->properties()->with('agent')->paginate(10);

        $agents = \App\Models\User::where('role', 'agent')->get();

        $usage = [
            'current_listings' => $properties->total(),
            'max_listings' => $this->getCapabilityValue($user, 'max_listings'),
            'current_featured' => $user->properties()->where('featured', true)->count(),
            'max_featured' => $this->getCapabilityValue($user, 'max_featured_listings')
        ];

        return view('owner.properties.index', compact('properties', 'usage', 'agents'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $maxListings = $this->getCapabilityValue($user, 'max_listings');
        $currentListings = $user->properties()->count();

        $usage = [
            'current_listings' => $currentListings,
            'max_listings' => $maxListings
        ];

        $step = $request->get('step', 1);
        $categories = \App\Models\Category::with('subcategories.amenities')->get();

        return view('owner.properties.create', compact('usage', 'step', 'categories'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        // Check max listings capability
        $currentListings = $user->properties()->count();
        $maxListings = $this->getCapabilityValue($user, 'max_listings');

        if ($currentListings >= $maxListings) {
            return response()->json([
                'error' => 'Maximum listings limit reached',
                'current' => $currentListings,
                'limit' => $maxListings
            ], 403);
        }

        // Validate request
        $isStep4 = $request->input('step') == 4;
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'land_type' => 'required|in:Agriculture,Residential Plot,Commercial Plot',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'full_address' => 'nullable|string',
            'google_map_lat' => ($isStep4 ? 'required' : 'nullable') . '|numeric|between:-90,90',
            'google_map_lng' => ($isStep4 ? 'required' : 'nullable') . '|numeric|between:-180,180',
            'plot_area' => 'required|numeric|min:0',
            'plot_area_unit' => 'required|in:sq ft,sq yd,acre',
            'frontage' => 'nullable|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'road_width' => 'required|numeric|min:0',
            'corner_plot' => 'nullable|boolean',
            'gated_community' => 'nullable|boolean',
            'ownership_type' => 'required|in:Freehold,Leasehold',
            'price' => 'required|numeric|min:0',
            'price_negotiable' => 'nullable|boolean',
            'contact_name' => 'required|string|max:255',
            'contact_mobile' => 'required|string|max:15',
            'description' => 'required|string',
            'property_images' => 'required|array|min:2|max:10',
            'property_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'property_video' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ]);

        // Handle file uploads
        $imagePaths = [];
        if ($request->hasFile('property_images')) {
            foreach ($request->file('property_images') as $image) {
                $path = $image->store('properties/images', 'public');
                $imagePaths[] = $path;
            }
        }

        $videoPath = null;
        if ($request->hasFile('property_video')) {
            $videoPath = $request->file('property_video')->store('properties/videos', 'public');
        }

        // Create property
        $property = Property::create([
            'title' => $validated['title'],
            'land_type' => $validated['land_type'],
            'description' => $validated['description'],
            'state' => $validated['state'],
            'city' => $validated['city'],
            'area' => $validated['area'],
            'full_address' => $validated['full_address'],
            'google_map_lat' => $validated['google_map_lat'] ?? null,
            'google_map_lng' => $validated['google_map_lng'] ?? null,
            'plot_area' => $validated['plot_area'],
            'plot_area_unit' => $validated['plot_area_unit'],
            'frontage' => $validated['frontage'],
            'depth' => $validated['depth'],
            'road_width' => $validated['road_width'],
            'corner_plot' => $validated['corner_plot'] ?? false,
            'gated_community' => $validated['gated_community'] ?? false,
            'ownership_type' => $validated['ownership_type'],
            'price' => $validated['price'],
            'price_negotiable' => $validated['price_negotiable'] ?? false,
            'contact_name' => $validated['contact_name'],
            'contact_mobile' => $validated['contact_mobile'],
            'contact_role' => ucfirst(auth()->user()->role),
            'property_images' => json_encode($imagePaths),
            'property_video' => $videoPath,
            'status' => 'For Sale',
            'owner_id' => $user->id,
        ]);

        // Attach amenities
        if (isset($validated['amenities'])) {
            $property->amenities()->attach($validated['amenities']);
        }

        // Create initial version
        $property->createVersion();

        return redirect()->route('owner.properties.show', $property)->with('success', 'Property created successfully');
    }

    public function show(Property $property)
    {
        $user = Auth::user();

        if ($property->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $property->load('amenities.subcategory.category', 'agent');

        return view('owner.properties.show', compact('property'));
    }

    public function edit(Request $request, Property $property)
    {
        $user = Auth::user();

        if ($property->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $step = $request->get('step', 1);
        $categories = \App\Models\Category::with('subcategories.amenities')->get();

        return view('owner.properties.edit', compact('property', 'step', 'categories'));
    }

    public function update(Request $request, Property $property)
    {
        $user = Auth::user();

        if ($property->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Validate request
        $isStep4 = $request->input('step') == 4;
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'land_type' => 'required|in:Agriculture,Residential Plot,Commercial Plot',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'area' => 'required|string|max:255',
            'full_address' => 'nullable|string',
            'google_map_lat' => ($isStep4 ? 'required' : 'nullable') . '|numeric|between:-90,90',
            'google_map_lng' => ($isStep4 ? 'required' : 'nullable') . '|numeric|between:-180,180',
            'plot_area' => 'required|numeric|min:0',
            'plot_area_unit' => 'required|in:sq ft,sq yd,acre',
            'frontage' => 'nullable|numeric|min:0',
            'depth' => 'nullable|numeric|min:0',
            'road_width' => 'required|numeric|min:0',
            'corner_plot' => 'nullable|boolean',
            'gated_community' => 'nullable|boolean',
            'ownership_type' => 'required|in:Freehold,Leasehold',
            'price' => 'required|numeric|min:0',
            'price_negotiable' => 'nullable|boolean',
            'contact_name' => 'required|string|max:255',
            'contact_mobile' => 'required|string|max:15',
            'description' => 'required|string',
            'property_images' => 'nullable|array|min:2|max:10',
            'property_images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'property_video' => 'nullable|file|mimes:mp4,mov,avi|max:51200',
            'amenities' => 'nullable|array',
            'amenities.*' => 'exists:amenities,id',
        ]);

        // Handle file uploads
        $imagePaths = $property->property_images ? json_decode($property->property_images, true) : [];
        if ($request->hasFile('property_images')) {
            // Delete old images if new ones are uploaded
            if (!empty($imagePaths)) {
                foreach ($imagePaths as $oldImage) {
                    $oldImagePath = storage_path('app/public/' . $oldImage);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }

            $imagePaths = [];
            foreach ($request->file('property_images') as $image) {
                $path = $image->store('properties/images', 'public');
                $imagePaths[] = $path;
            }
        }

        $videoPath = $property->property_video;
        if ($request->hasFile('property_video')) {
            // Delete old video if new one is uploaded
            if ($videoPath) {
                $oldVideoPath = storage_path('app/public/' . $videoPath);
                if (file_exists($oldVideoPath)) {
                    unlink($oldVideoPath);
                }
            }
            $videoPath = $request->file('property_video')->store('properties/videos', 'public');
        }

        // Update property
        $property->update([
            'title' => $validated['title'],
            'land_type' => $validated['land_type'],
            'description' => $validated['description'],
            'state' => $validated['state'],
            'city' => $validated['city'],
            'area' => $validated['area'],
            'full_address' => $validated['full_address'],
            'google_map_lat' => $validated['google_map_lat'] ?? null,
            'google_map_lng' => $validated['google_map_lng'] ?? null,
            'plot_area' => $validated['plot_area'],
            'plot_area_unit' => $validated['plot_area_unit'],
            'frontage' => $validated['frontage'],
            'depth' => $validated['depth'],
            'road_width' => $validated['road_width'],
            'corner_plot' => $validated['corner_plot'] ?? false,
            'gated_community' => $validated['gated_community'] ?? false,
            'ownership_type' => $validated['ownership_type'],
            'price' => $validated['price'],
            'price_negotiable' => $validated['price_negotiable'] ?? false,
            'contact_name' => $validated['contact_name'],
            'contact_mobile' => $validated['contact_mobile'],
            'contact_role' => ucfirst(auth()->user()->role),
            'property_images' => json_encode($imagePaths),
            'property_video' => $videoPath,
        ]);

        // Update amenities
        if (isset($validated['amenities'])) {
            $property->amenities()->sync($validated['amenities']);
        } else {
            $property->amenities()->detach();
        }

        return redirect()->route('owner.properties.show', $property)->with('success', 'Property updated successfully');
    }

    public function destroy(Property $property)
    {
        $user = Auth::user();

        if ($property->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Delete associated files
        if ($property->property_images) {
            $images = json_decode($property->property_images, true);
            foreach ($images as $image) {
                $imagePath = storage_path('app/public/' . $image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        if ($property->property_video) {
            $videoPath = storage_path('app/public/' . $property->property_video);
            if (file_exists($videoPath)) {
                unlink($videoPath);
            }
        }

        $property->delete();

        return redirect()->route('owner.properties.index')->with('success', 'Property deleted successfully');
    }

    public function assignAgent(Request $request, $propertyId)
    {
        $user = Auth::user();

        // Find property and check ownership
        $property = Property::findOrFail($propertyId);
        if ($property->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Validate request
        $validated = $request->validate([
            'agent_id' => 'required|exists:users,id',
        ]);

        // Check if agent exists and has agent role
        $agent = \App\Models\User::findOrFail($validated['agent_id']);
        if (!$agent->hasRole('agent')) {
            return response()->json([
                'error' => 'Selected user is not an agent'
            ], 422);
        }

        // Assign agent and update contact information
        $property->update([
            'agent_id' => $agent->id,
            'contact_name' => $agent->name,
            'contact_mobile' => $agent->mobile ?? $agent->phone,
            'contact_role' => 'Agent'
        ]);

        return redirect()->back()->with('success', 'Agent assigned successfully');
    }

    public function unassignAgent($propertyId)
    {
        $user = Auth::user();

        // Find property and check ownership
        $property = Property::findOrFail($propertyId);
        if ($property->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        // Unassign agent
        $property->update(['agent_id' => null]);

        return redirect()->back()->with('success', 'Agent unassigned successfully');
    }
    public function featureProperty($id)
    {
        $user = Auth::user();
        $property = Property::findOrFail($id);

        if ($property->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        if (!$user->canFeatureProperty()) {
            return response()->json([
                'error' => 'Featured listing limit reached. Please upgrade your plan.',
                'upgrade_required' => true
            ], 403);
        }

        $activePurchases = $user->activePlanPurchases();
        $activePurchase = $activePurchases->first();
        $property->update([
            'featured' => true,
            'featured_until' => now()->addDays($activePurchase->plan->getFeaturedDurationDays())
        ]);

        $activePurchase->increment('used_featured_listings');

        return response()->json(['success' => 'Property featured successfully']);
    }

    public function unfeatureProperty($id)
    {
        $user = Auth::user();
        $property = Property::findOrFail($id);

        if ($property->owner_id !== $user->id) {
            abort(403, 'Unauthorized');
        }

        $property->update([
            'featured' => false,
            'featured_until' => null
        ]);

        $activePurchases = $user->activePlanPurchases();
        $activePurchase = $activePurchases->first();
        if ($activePurchase) {
            $activePurchase->decrement('used_featured_listings');
        }

        return response()->json(['success' => 'Property unfeatured successfully']);
    }
}
