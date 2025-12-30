<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\State;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    public function index()
    {
        $states = State::with(['districts'])->get();
        return view('admin.districts.index', compact('states'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id'
        ]);

        District::create($request->only(['name', 'state_id']));

        return response()->json(['message' => 'District created successfully']);
    }

    public function update(Request $request, District $district)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'state_id' => 'required|exists:states,id'
        ]);

        $district->update($request->only(['name', 'state_id']));

        return response()->json(['message' => 'District updated successfully']);
    }

    public function destroy(District $district)
    {
        $district->delete();

        return response()->json(['message' => 'District deleted successfully']);
    }
}