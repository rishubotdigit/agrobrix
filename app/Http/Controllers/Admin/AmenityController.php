<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Amenity;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;

class AmenityController extends Controller
{
    public function index()
    {
        $amenities = Amenity::with('subcategory.category')->orderBy('id', 'desc')->paginate(10);
        $categories = Category::with('subcategories')->get();
        return view('admin.amenities.index', compact('amenities', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subcategory_id' => 'required|exists:subcategories,id'
        ]);

        Amenity::create($request->all());

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity created successfully.');
    }

    public function update(Request $request, Amenity $amenity)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subcategory_id' => 'required|exists:subcategories,id'
        ]);

        $amenity->update($request->all());

        return redirect()->route('admin.amenities.index')->with('success', 'Amenity updated successfully.');
    }

    public function destroy(Amenity $amenity)
    {
        $amenity->delete();
        return redirect()->route('admin.amenities.index')->with('success', 'Amenity deleted successfully.');
    }
}
