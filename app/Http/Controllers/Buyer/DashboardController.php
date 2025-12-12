<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\ViewedContact;
use App\Models\Wishlist;
use App\Models\Payment;
use App\Traits\CapabilityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use CapabilityTrait;

    public function dashboard()
    {
        $user = auth()->user();
        $contactsViewed = $user->viewedContacts()->count();
        $savedProperties = Wishlist::where('user_id', auth()->id())->count();
        $totalSpent = Payment::where('user_id', auth()->id())->where('status', 'completed')->sum('amount');
        $activeSearches = ViewedContact::where('user_id', auth()->id())->count();

        // Fetch recent activities
        $recentViewedContacts = ViewedContact::where('buyer_id', $user->id)
            ->with('property')
            ->orderBy('viewed_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'viewed_contact',
                    'date' => $item->viewed_at,
                    'property' => $item->property,
                ];
            });

        $recentWishlist = Wishlist::where('user_id', $user->id)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'wishlist_addition',
                    'date' => $item->created_at,
                    'property' => $item->property,
                ];
            });

        $recentLeads = Lead::where('buyer_email', $user->email)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'lead',
                    'date' => $item->created_at,
                    'property' => $item->property,
                ];
            });

        $recentActivities = collect([$recentViewedContacts, $recentWishlist, $recentLeads])
            ->flatten(1)
            ->sortByDesc('date')
            ->take(10);

        return view('buyer.dashboard', compact('contactsViewed', 'savedProperties', 'totalSpent', 'activeSearches', 'recentActivities'));
    }

    public function getStats()
    {
        $user = auth()->user();
        $contactsViewed = $user->viewedContacts()->count();
        $savedProperties = Wishlist::where('user_id', auth()->id())->count();
        $totalSpent = Payment::where('user_id', auth()->id())->where('status', 'completed')->sum('amount');
        $activeSearches = ViewedContact::where('user_id', auth()->id())->count();

        return response()->json([
            'contactsViewed' => $contactsViewed,
            'savedProperties' => $savedProperties,
            'totalSpent' => $totalSpent,
            'activeSearches' => $activeSearches,
        ]);
    }

    public function inquiries()
    {
        $user = auth()->user();
        $inquiries = Lead::where('buyer_email', $user->email)
            ->with('property')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('buyer.inquiries.index', compact('inquiries'));
    }
}