<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\PropertyReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PropertyReportController extends Controller
{
    public function store(Request $request, Property $property)
    {
        $request->validate([
            'reason' => 'required|string|in:Sold Out,Incorrect Information,Owner Contact Incorrect',
            'details' => 'nullable|string|max:1000',
        ]);

        // Check if user has already reported this property? Maybe allow multiple reports or limit one per user.
        // Assuming one report per reason or just one report per property for now to avoid spam.
        $existingReport = PropertyReport::where('property_id', $property->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($existingReport) {
            return back()->with('error', 'You have already reported this property.');
        }

        $report = PropertyReport::create([
            'property_id' => $property->id,
            'user_id' => Auth::id(),
            'reason' => $request->reason,
            'details' => $request->details,
        ]);

        // Notify Admins
        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\PropertyReportedNotification($report));

        return back()->with('success', 'Property reported successfully. We will investigate the issue.');
    }
}
