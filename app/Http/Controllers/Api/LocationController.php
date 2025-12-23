<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\District;
use App\Models\City;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function getDistricts($stateId)
    {
        $districts = District::where('state_id', $stateId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($districts);
    }

    public function getCities($districtId)
    {
        $cities = City::where('district_id', $districtId)
            ->orderBy('name')
            ->get(['id', 'name']);

        return response()->json($cities);
    }
}