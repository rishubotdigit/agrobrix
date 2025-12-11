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
        $maxContacts = $this->getCapabilityValue($user, 'max_contacts');
        $savedProperties = Wishlist::where('user_id', auth()->id())->count();
        $totalSpent = Payment::where('user_id', auth()->id())->where('status', 'completed')->sum('amount');
        $activeSearches = ViewedContact::where('user_id', auth()->id())->count();

        return view('buyer.dashboard', compact('contactsViewed', 'maxContacts', 'savedProperties', 'totalSpent', 'activeSearches'));
    }

    public function getStats()
    {
        $user = auth()->user();
        $contactsViewed = $user->viewedContacts()->count();
        $maxContacts = $this->getCapabilityValue($user, 'max_contacts');
        $savedProperties = Wishlist::where('user_id', auth()->id())->count();
        $totalSpent = Payment::where('user_id', auth()->id())->where('status', 'completed')->sum('amount');
        $activeSearches = ViewedContact::where('user_id', auth()->id())->count();

        return response()->json([
            'contactsViewed' => $contactsViewed,
            'maxContacts' => $maxContacts,
            'savedProperties' => $savedProperties,
            'totalSpent' => $totalSpent,
            'activeSearches' => $activeSearches,
        ]);
    }

    public function inquiries()
    {
        $user = auth()->user();
        $viewedContacts = ViewedContact::where('buyer_id', $user->id)
            ->with(['property.owner', 'property.agent'])
            ->orderBy('viewed_at', 'desc')
            ->get();

        $inquiries = $viewedContacts->map(function($viewedContact) {
            $property = $viewedContact->property;
            $contact = $property->agent ?? $property->owner;
            $lead = Lead::where('property_id', $property->id)->first();

            return [
                'property' => $property,
                'unlock_date' => $viewedContact->viewed_at,
                'contact_name' => $contact->name,
                'contact_email' => $contact->email,
                'contact_mobile' => $contact->mobile,
                'status' => $lead ? $lead->status : 'Contact Unlocked'
            ];
        });

        return view('buyer.inquiries.index', compact('inquiries'));
    }
}