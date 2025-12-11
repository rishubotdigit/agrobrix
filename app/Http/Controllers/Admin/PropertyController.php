<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyVersion;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::with('owner')->paginate(20);
        return view('admin.properties.index', compact('properties'));
    }

    public function show(Property $property)
    {
        $property->load(['owner', 'agent', 'amenities', 'versions']);
        $versions = $property->versions()->orderBy('version', 'desc')->get();
        return view('admin.properties.show', compact('property', 'versions'));
    }

    public function versions(Property $property)
    {
        $versions = $property->versions()->orderBy('version', 'desc')->paginate(20);
        return view('admin.properties.versions', compact('property', 'versions'));
    }

    public function approveVersion(PropertyVersion $version)
    {
        $version->update(['status' => 'approved']);
        // Apply the approved version to the property
        $property = $version->property;
        $property->update(array_merge($version->data, ['status' => 'approved']));
        return redirect()->back()->with('success', 'Version approved.');
    }

    public function rejectVersion(PropertyVersion $version)
    {
        $version->update(['status' => 'rejected']);
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
            $version->property->update(array_merge($version->data, ['status' => 'approved']));
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
}
