<?php

namespace App\Http\Controllers\Admin;

use App\Events\PropertyApproved;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePropertyRequest;
use App\Http\Requests\UpdatePropertyRequest;
use App\Models\Property;
use App\Models\PropertyVersion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PropertyController extends Controller
{
    public function index(Request $request)
    {
        // Handle view mode
        $viewMode = $request->get('view_mode', session('admin_properties_view_mode', 'grid'));
        session(['admin_properties_view_mode' => $viewMode]);

        // Build base queries
        $myPropertiesQuery = Property::where('owner_id', auth()->id())->with(['owner', 'district.state']);
        $propertiesQuery = Property::with(['owner', 'district.state']);

        // Apply filters
        $this->applyFilters($request, $myPropertiesQuery);
        $this->applyFilters($request, $propertiesQuery);

        // Paginate
        $myProperties = $myPropertiesQuery->paginate(9)->appends($request->query());
        $properties = $propertiesQuery->paginate(9)->appends($request->query());

        // Get filter data for dropdowns
        $states = \App\Models\State::orderBy('name')->get();
        $districts = \App\Models\District::orderBy('name')->get();

        return view('admin.properties.index', compact('properties', 'myProperties', 'viewMode', 'states', 'districts'));
    }

    private function applyFilters(Request $request, $query)
    {
        // General search across title and address
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('full_address', 'like', "%{$search}%");
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // State filter
        if ($request->filled('state_id')) {
            $query->whereHas('district', function($q) use ($request) {
                $q->where('state_id', $request->state_id);
            });
        }

        // District filter
        if ($request->filled('district_id')) {
            $query->where('district_id', $request->district_id);
        }

        // Land type filter
        if ($request->filled('land_type')) {
            $query->where('land_type', $request->land_type);
        }

        // Featured filter
        if ($request->filled('featured')) {
            if ($request->featured === 'yes') {
                $query->where('featured', true);
            } elseif ($request->featured === 'no') {
                $query->where('featured', false);
            }
        }

        // Price filters
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }

        // Area filters (using plot_area)
        if ($request->filled('area_min')) {
            $query->where('plot_area', '>=', $request->area_min);
        }
        if ($request->filled('area_max')) {
            $query->where('plot_area', '<=', $request->area_max);
        }

        // Date filters
        if ($request->filled('created_from')) {
            $query->whereDate('created_at', '>=', $request->created_from);
        }
        if ($request->filled('created_to')) {
            $query->whereDate('created_at', '<=', $request->created_to);
        }

        // Owner name filter
        if ($request->filled('owner_name')) {
            $query->whereHas('owner', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->owner_name . '%');
            });
        }
    }

    public function create(Request $request)
    {
        $step = $request->get('step', 1);
        $categories = \App\Models\Category::with('subcategories.amenities')->get();

        return view('admin.properties.create', compact('step', 'categories'));
    }

    public function store(StorePropertyRequest $request)
    {
        Log::info('Admin PropertyController store started', ['user_id' => auth()->id(), 'is_ajax' => $request->ajax()]);

        // Validate request
        $validated = $request->validated();
        Log::info('Validation passed', ['validated_keys' => array_keys($validated), 'featured_value' => $validated['featured'] ?? null, 'user_role' => 'admin']);

        // Handle file uploads
        $imagePaths = [];
        if ($request->hasFile('property_images')) {
            foreach ($request->file('property_images') as $image) {
                $path = $image->store('properties/images', 'public');
                $imagePaths[] = $path;
            }
        }
        Log::info('Image uploads handled', ['image_count' => count($imagePaths)]);

        $videoPath = null;
        if ($request->hasFile('property_video')) {
            $videoPath = $request->file('property_video')->store('properties/videos', 'public');
        }
        Log::info('Video upload handled', ['video_path' => $videoPath]);

        // Get state name from ID
        $state = \App\Models\State::find($validated['state']);
        
        // Create property
        $propertyData = [
            'title' => $validated['title'],
            'land_type' => $validated['land_type'],
            'description' => $validated['description'],
            'state' => $state ? $state->name : null,
            'district_id' => $validated['district'],
            'area' => $validated['area'],
            'full_address' => $validated['full_address'],
            'google_map_lat' => $validated['google_map_lat'] ?? null,
            'google_map_lng' => $validated['google_map_lng'] ?? null,
            'plot_area' => $validated['plot_area'],
            'plot_area_unit' => $validated['plot_area_unit'],
            'frontage' => $validated['frontage'],
            'road_width' => $validated['road_width'],
            'corner_plot' => $validated['corner_plot'] ?? false,
            'gated_community' => $validated['gated_community'] ?? false,
            'price' => $validated['price'],
            'price_negotiable' => $validated['price_negotiable'] ?? false,
            'contact_name' => $validated['contact_name'],
            'contact_mobile' => $validated['contact_mobile'],
            'contact_role' => 'Admin',
            'property_images' => json_encode($imagePaths),
            'property_video' => $videoPath,
            'status' => 'approved',
            'owner_id' => auth()->id(),
            'featured' => $validated['featured'] ?? false,
            'featured_until' => ($validated['featured'] ?? false) ? now()->addDays(30) : null,
        ];
        Log::info('Creating property with data', ['property_data' => $propertyData]);

        $property = Property::create($propertyData);
        Log::info('Property created', ['property_id' => $property->id, 'contact_name' => $property->contact_name, 'contact_mobile' => $property->contact_mobile, 'contact_role' => $property->contact_role]);

        // Attach amenities
        if (isset($validated['amenities'])) {
            $property->amenities()->attach($validated['amenities']);
            Log::info('Amenities attached', ['amenity_count' => count($validated['amenities'])]);
        }

        // Create initial version
        $property->createVersion();
        Log::info('Initial version created');

        Log::info('Firing PropertyApproved event for property ID: ' . $property->id);
        event(new PropertyApproved($property, auth()->id()));

        Log::info('Admin PropertyController store completed successfully');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Property created successfully',
                'redirect' => route('admin.properties.show', $property)
            ]);
        }

        return redirect()->route('admin.properties.show', $property)->with('success', 'Property created successfully');
    }

    public function show(Property $property)
    {
        $property->load(['owner', 'agent', 'amenities', 'versions', 'district.state']);
        $versions = $property->versions()->orderBy('version', 'desc')->get();
        Log::info('Showing property', ['property_id' => $property->id, 'contact_name' => $property->contact_name, 'contact_mobile' => $property->contact_mobile, 'contact_role' => $property->contact_role]);
        return view('admin.properties.show', compact('property', 'versions'));
    }

    public function versions(Property $property)
    {
        $versions = $property->versions()->orderBy('version', 'desc')->paginate(9);
        return view('admin.properties.versions', compact('property', 'versions'));
    }

    public function approveVersion(PropertyVersion $version)
    {
        $version->update(['status' => 'approved']);
        // Apply the approved version to the property
        $property = $version->property;
        $property->update(array_merge($version->data, ['status' => 'approved']));

        Log::info('Firing PropertyApproved event for property ID: ' . $property->id);
        event(new PropertyApproved($property, auth()->id()));

        return redirect()->back()->with('success', 'Version approved.');
    }

    public function rejectVersion(PropertyVersion $version)
    {
        $version->update(['status' => 'rejected']);
        // Update property status to rejected
        $property = $version->property;
        $property->update(['status' => 'rejected']);

        Log::info('Firing PropertyRejected event for property ID: ' . $property->id);
        event(new \App\Events\PropertyRejected($property, auth()->id()));

        return redirect()->back()->with('success', 'Version rejected.');
    }

    public function bulkApprove(Request $request)
    {
        $versionIds = $request->input('versions', []);
        if (empty($versionIds)) {
            return redirect()->back()->with('error', 'No versions selected.');
        }
        PropertyVersion::whereIn('id', $versionIds)->update(['status' => 'approved']);
        // Apply approved versions to properties
        PropertyVersion::whereIn('id', $versionIds)->each(function ($version) {
            $property = $version->property;
            $property->update(array_merge($version->data, ['status' => 'approved']));
            Log::info('Firing PropertyApproved event for property ID: ' . $property->id . ' in bulk approve');
            event(new PropertyApproved($property, auth()->id()));
        });
        return redirect()->back()->with('success', 'Selected versions approved.');
    }

    public function bulkReject(Request $request)
    {
        $versionIds = $request->input('versions', []);
        if (empty($versionIds)) {
            return redirect()->back()->with('error', 'No versions selected.');
        }
        PropertyVersion::whereIn('id', $versionIds)->update(['status' => 'rejected']);
        // Update properties status to rejected and fire events
        PropertyVersion::whereIn('id', $versionIds)->each(function ($version) {
            $property = $version->property;
            $property->update(['status' => 'rejected']);
            Log::info('Firing PropertyRejected event for property ID: ' . $property->id . ' in bulk reject');
            event(new \App\Events\PropertyRejected($property, auth()->id()));
        });
        return redirect()->back()->with('success', 'Selected versions rejected.');
    }

    public function diff(PropertyVersion $version)
    {
        $previous = PropertyVersion::where('property_id', $version->property_id)
            ->where('version', '<', $version->version)
            ->orderBy('version', 'desc')
            ->first();

        return view('admin.properties.diff', compact('version', 'previous'));
    }

    public function destroy(Property $property)
    {
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

        return redirect()->route('admin.properties.index')->with('success', 'Property deleted successfully');
    }

    public function cancelVersion(PropertyVersion $version)
    {
        Log::info('Canceling version ID: ' . $version->id . ' for property ID: ' . $version->property_id);
        $version->update(['status' => 'canceled']);
        $property = $version->property;
        $property->update(['status' => 'canceled']);
        Log::info('Property status updated to canceled for property ID: ' . $property->id);

        Log::info('Firing PropertyRejected event for property ID: ' . $property->id);
        event(new \App\Events\PropertyRejected($property, auth()->id()));

        return redirect()->back()->with('success', 'Version canceled.');
    }

    public function disable(Property $property)
    {
        $property->update(['status' => 'disabled']);
        return redirect()->back()->with('success', 'Property disabled.');
    }

    public function reApprove(Property $property)
    {
        $property->update(['status' => 'approved']);
        Log::info('Firing PropertyApproved event for property ID: ' . $property->id);
        event(new PropertyApproved($property, auth()->id()));
        return redirect()->back()->with('success', 'Property re-approved.');
    }

    public function reEnable(Property $property)
    {
        $property->update(['status' => 'approved']);
        Log::info('Firing PropertyApproved event for property ID: ' . $property->id);
        event(new PropertyApproved($property, auth()->id()));
        return redirect()->back()->with('success', 'Property re-enabled.');
    }

    public function edit(Request $request, Property $property)
    {
        $step = $request->get('step', 1);
        $categories = \App\Models\Category::with('subcategories.amenities')->get();

        return view('admin.properties.edit', compact('property', 'step', 'categories'));
    }

    public function update(UpdatePropertyRequest $request, Property $property)
    {
        // Validate request
        $validated = $request->validated();

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
            'area' => $validated['area'],
            'full_address' => $validated['full_address'],
            'google_map_lat' => $validated['google_map_lat'] ?? null,
            'google_map_lng' => $validated['google_map_lng'] ?? null,
            'plot_area' => $validated['plot_area'],
            'plot_area_unit' => $validated['plot_area_unit'],
            'frontage' => $validated['frontage'],
            'road_width' => $validated['road_width'],
            'corner_plot' => $validated['corner_plot'] ?? false,
            'gated_community' => $validated['gated_community'] ?? false,
            'price' => $validated['price'],
            'price_negotiable' => $validated['price_negotiable'] ?? false,
            'contact_name' => $validated['contact_name'],
            'contact_mobile' => $validated['contact_mobile'],
            'contact_role' => $validated['contact_role'] ?? 'Admin',
            'property_images' => json_encode($imagePaths),
            'property_video' => $videoPath,
        ]);

        // Update amenities
        if (isset($validated['amenities'])) {
            $property->amenities()->sync($validated['amenities']);
        } else {
            $property->amenities()->detach();
        }

        return redirect()->route('admin.properties.show', $property)->with('success', 'Property updated successfully');
    }
}
