<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\District;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('district.state')->get();
        $districts = District::with('state')->get();
        return view('admin.cities.index', compact('cities', 'districts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id'
        ]);

        City::create($request->only(['name', 'district_id']));

        return response()->json(['message' => 'City created successfully']);
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'district_id' => 'required|exists:districts,id'
        ]);

        $city->update($request->only(['name', 'district_id']));

        return response()->json(['message' => 'City updated successfully']);
    }

    public function destroy(City $city)
    {
        $city->delete();

        return response()->json(['message' => 'City deleted successfully']);
    }
}