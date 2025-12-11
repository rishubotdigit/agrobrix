<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Traits\CapabilityTrait;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewedContact;
use App\Models\Property;
use App\Models\Payment;

class DashboardController extends Controller
{
    use CapabilityTrait;

    public function dashboard()
    {
        $user = auth()->user();
        $properties = $user->properties()->count();
        $maxListings = $this->getCapabilityValue($user, 'max_listings');

        $totalInquiries = ViewedContact::whereHas('property', function($q) { $q->where('user_id', auth()->id()); })->count();
        $activeListings = Property::where('user_id', auth()->id())->where('status', 'active')->count();
        $totalEarnings = Payment::whereHas('property', function($q) { $q->where('user_id', auth()->id()); })->where('status', 'completed')->sum('amount');
        $recentProperties = Property::where('user_id', auth()->id())->orderBy('created_at', 'desc')->limit(5)->get();

        return view('owner.dashboard', compact('properties', 'maxListings', 'totalInquiries', 'activeListings', 'totalEarnings', 'recentProperties'));
    }

    public function getStats()
    {
        $user = auth()->user();
        $properties = $user->properties()->count();
        $maxListings = $this->getCapabilityValue($user, 'max_listings');

        $totalInquiries = ViewedContact::whereHas('property', function($q) { $q->where('user_id', auth()->id()); })->count();
        $activeListings = Property::where('user_id', auth()->id())->where('status', 'active')->count();
        $totalEarnings = Payment::whereHas('property', function($q) { $q->where('user_id', auth()->id()); })->where('status', 'completed')->sum('amount');

        return response()->json([
            'properties' => $properties,
            'maxListings' => $maxListings,
            'totalInquiries' => $totalInquiries,
            'activeListings' => $activeListings,
            'totalEarnings' => $totalEarnings,
        ]);
    }

    public function plan()
    {
        $user = auth()->user();
        $activePlanPurchases = $user->activePlanPurchases();
        $combinedCapabilities = $user->getCombinedCapabilities();

        return view('owner.plan', compact('activePlanPurchases', 'combinedCapabilities'));
    }
}