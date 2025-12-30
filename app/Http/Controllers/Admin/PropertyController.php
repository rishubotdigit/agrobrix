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
        $propertiesQuery = Property::with(['owner', 'district.state']);

        // Apply filters
        $this->applyFilters($request, $propertiesQuery);

        // Paginate
        $properties = $propertiesQuery->paginate(20)->appends($request->query());

        // Get filter data for dropdowns
        $states = \App\Models\State::orderBy('name')->get();
        $districts = \App\Models\District::orderBy('name')->get();

        return view('admin.properties.index', compact('properties', 'viewMode', 'states', 'districts'));
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

    public function bulkDestroy(Request $request)
    {
        $ids = $request->input('ids');
        
        if (empty($ids)) {
            return redirect()->back()->with('error', 'No properties selected.');
        }

        $properties = Property::whereIn('id', $ids)->get();

        foreach ($properties as $property) {
            // Delete associated files
            if ($property->property_images) {
                $images = json_decode($property->property_images, true);
                if (is_array($images)) {
                    foreach ($images as $image) {
                        $imagePath = storage_path('app/public/' . $image);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
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
        }

        return redirect()->route('admin.properties.index')->with('success', 'Selected properties deleted successfully');
    }

    public function bulkPropertyApprove(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) return redirect()->back()->with('error', 'No properties selected.');
        
        $properties = Property::whereIn('id', $ids)->get();
        foreach ($properties as $property) {
            $property->update(['status' => 'approved']);
            event(new PropertyApproved($property, auth()->id()));
        }

        return redirect()->route('admin.properties.index')->with('success', 'Selected properties approved successfully');
    }

    public function bulkPropertyReject(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) return redirect()->back()->with('error', 'No properties selected.');
        
        $properties = Property::whereIn('id', $ids)->get();
        foreach ($properties as $property) {
            $property->update(['status' => 'rejected']);
            event(new \App\Events\PropertyRejected($property, auth()->id()));
        }

        return redirect()->route('admin.properties.index')->with('success', 'Selected properties rejected successfully');
    }

    public function bulkPropertyEnable(Request $request)
    {
        $ids = $request->input('ids');
        if (empty($ids)) return redirect()->back()->with('error', 'No properties selected.');
        
        $properties = Property::whereIn('id', $ids)->get();
        foreach ($properties as $property) {
            $property->update(['status' => 'approved']);
            // reEnable fires PropertyApproved event in individual method, so we do it here too
            event(new PropertyApproved($property, auth()->id()));
        }

        return redirect()->route('admin.properties.index')->with('success', 'Selected properties enabled successfully');
    }

    public function bulkPropertyDisable(Request $request) {
        $ids = $request->input('ids');
        if (empty($ids)) return redirect()->back()->with('error', 'No properties selected.');
        
        // Disable does not fire an event in the simple method, so we can use bulk update or loop. 
        // Keeping it consistent with others:
        Property::whereIn('id', $ids)->update(['status' => 'disabled']);
        
        return redirect()->route('admin.properties.index')->with('success', 'Selected properties disabled successfully');
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

        // Get state name from ID
        $state = \App\Models\State::find($validated['state']);

        // Update property
        $property->update([
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

    public function export()
    {
        $fileName = 'properties_' . date('Y-m-d_H-i-s') . '.csv';
        $properties = Property::with('district')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = [
            'Title', 'Land Type', 'Description', 'Price', 'Area', 'State', 'District', 
            'Full Address', 'Plot Area', 'Plot Area Unit', 'Frontage', 'Road Width', 
            'Corner Plot', 'Gated Community', 'Contact Name', 'Contact Mobile', 'Status'
        ];

        $callback = function() use($properties, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($properties as $property) {
                $row = [
                    $property->title,
                    $property->land_type,
                    $property->description,
                    $property->price,
                    $property->area,
                    $property->state,
                    $property->district->name ?? '',
                    $property->full_address,
                    $property->plot_area,
                    $property->plot_area_unit,
                    $property->frontage,
                    $property->road_width,
                    $property->corner_plot ? 'Yes' : 'No',
                    $property->gated_community ? 'Yes' : 'No',
                    $property->contact_name,
                    $property->contact_mobile,
                    $property->status
                ];

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');
        
        // Skip header
        $header = fgetcsv($handle);
        
        $count = 0;
        $errors = [];
        $warnings = [];
        $rowNum = 1;

        // Valid Enum Values
        $validLandTypes = ['Agriculture', 'Residential Plot', 'Commercial Plot'];
        $validUnits = ['sq ft', 'sq yd', 'acre'];

        // District Mapping
        $districtMap = [
            'Tumkur' => 'Tumakuru',
            'Sivagangai' => 'Sivaganga',
            'Kanchipuram' => 'Kancheepuram',
            'Mysore' => 'Mysuru',
            'Belgaum' => 'Belagavi',
            'Chamraja Nagar' => 'Chamarajanagar',
            'Thiruvallur' => 'Tiruvallur',
            'Bangalore' => 'Bengaluru Urban',
        ];

        while (($data = fgetcsv($handle)) !== FALSE) {
            $rowNum++;
            if (count($data) < 16) {
                $errors[] = "Row $rowNum: Invalid column count.";
                continue;
            }

            $rowWarnings = [];

            // 1. Map Columns
            $title = trim($data[0]);
            
            // Land Type
            $landTypeInput = trim($data[1]);
            $landType = null;
            
            // Normalize common variations
            if (strtolower($landTypeInput) === 'agricultural') {
                $landTypeInput = 'Agriculture';
            }
            
            // Match against valid values case-insensitively
            $matchFound = false;
            foreach ($validLandTypes as $validType) {
                if (strcasecmp($validType, $landTypeInput) === 0) {
                    $landType = $validType;
                    $matchFound = true;
                    break;
                }
            }
            if (!empty($landTypeInput) && !$matchFound) {
                $rowWarnings[] = "Invalid Land Type '$landTypeInput' skipped";
            }

            $description = trim($data[2]);
            $price = trim($data[3]);
            // Sanitize price (non-nullable)
            $price = str_replace(',', '', $price);
            $price = (is_numeric($price) && $price !== '') ? $price : 0;

            $area = trim($data[4]);
            
            // State
            $stateName = trim($data[5]);
            $stateObject = null;
            $state = null;
            if (!empty($stateName)) {
                $stateObject = \App\Models\State::where('name', $stateName)->first();
                if ($stateObject) {
                    $state = $stateObject->name;
                } else {
                    $rowWarnings[] = "State '$stateName' not found";
                }
            }

            // District
            $districtName = trim($data[6]);
            // Apply mapping
            if (isset($districtMap[$districtName])) {
                $districtName = $districtMap[$districtName];
            }
            
            $districtId = null;
            if (!empty($districtName) && $stateObject) {
                $district = \App\Models\District::where('name', $districtName)
                    ->where('state_id', $stateObject->id)
                    ->first();
                
                if ($district) {
                    $districtId = $district->id;
                } else {
                    $rowWarnings[] = "District '$districtName' not found in '$stateName'";
                }
            } elseif (!empty($districtName) && !$stateObject) {
                 $rowWarnings[] = "District '$districtName' skipped (State invalid)";
            }

            $fullAddress = trim($data[7]);
            
            $plotArea = trim($data[8]);
            // Sanitize plot_area (nullable)
            $plotArea = str_replace(',', '', $plotArea);
            $plotArea = (is_numeric($plotArea) && $plotArea !== '') ? $plotArea : null;
            
            // Unit
            $plotAreaUnitInput = trim($data[9]);
            $plotAreaUnit = null;
            $unitMatch = false;
            foreach ($validUnits as $validUnit) {
                 if (strcasecmp($validUnit, $plotAreaUnitInput) === 0) {
                    $plotAreaUnit = $validUnit;
                    $unitMatch = true;
                    break;
                }
            }
            if (!empty($plotAreaUnitInput) && !$unitMatch) {
                $rowWarnings[] = "Invalid Unit '$plotAreaUnitInput' skipped";
            }
            
            // Sanitize frontage (nullable)
            $frontage = trim($data[10]);
            $frontage = str_replace(',', '', $frontage);
            $frontage = (is_numeric($frontage) && $frontage !== '') ? $frontage : null;

            // Sanitize road_width (nullable)
            $roadWidth = trim($data[11]);
            $roadWidth = str_replace(',', '', $roadWidth);
            $roadWidth = (is_numeric($roadWidth) && $roadWidth !== '') ? $roadWidth : null;

            $cornerPlot = strtolower(trim($data[12])) === 'yes';
            $gatedCommunity = strtolower(trim($data[13])) === 'yes';
            $contactName = trim($data[14]);
            $contactMobile = trim($data[15]);

            try {
                Property::create([
                    'title' => $title,
                    'land_type' => $landType,
                    'description' => $description,
                    'price' => $price,
                    'area' => $area,
                    'state' => $state,
                    'district_id' => $districtId,
                    'full_address' => $fullAddress,
                    'plot_area' => $plotArea,
                    'plot_area_unit' => $plotAreaUnit,
                    'frontage' => $frontage,
                    'road_width' => $roadWidth,
                    'corner_plot' => $cornerPlot,
                    'gated_community' => $gatedCommunity,
                    'contact_name' => $contactName,
                    'contact_mobile' => $contactMobile,
                    'status' => 'pending',
                    'owner_id' => auth()->id(),
                ]);
                $count++;
                
                if (!empty($rowWarnings)) {
                    $warnings[] = "Row $rowNum: " . implode('; ', $rowWarnings);
                }
            } catch (\Exception $e) {
                $errors[] = "Row $rowNum: " . $e->getMessage();
            }
        }

        fclose($handle);

        $message = "Successfully imported $count properties.";
        $status = 'success';

        if (!empty($warnings) || !empty($errors)) {
            $status = 'warning';
            $message .= " Some issues encountered:";
        }

        return redirect()->back()->with($status, $message)
            ->with('import_errors', $errors)
            ->with('import_warnings', $warnings);
    }
}
