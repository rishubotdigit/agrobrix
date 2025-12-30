<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Traits\CapabilityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ViewedContact;
use App\Models\Property;
use App\Models\Payment;
use App\Models\Lead;
use App\Models\PropertyVersion;

class DashboardController extends Controller
{
    use CapabilityTrait;

    private function getRecentActivities()
    {
        $ownerId = auth()->id();
        $activities = collect();

        // Properties Added
        $properties = Property::where('user_id', $ownerId)->latest()->limit(20)->get();
        foreach ($properties as $property) {
            $activities->push([
                'type' => 'Property Added',
                'description' => "Added property {$property->title}",
                'timestamp' => $property->created_at,
                'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'
            ]);
        }

        // Inquiries Received
        $inquiries = ViewedContact::whereHas('property', fn($q) => $q->where('owner_id', $ownerId))->with('property')->latest()->limit(20)->get();
        foreach ($inquiries as $inquiry) {
            $activities->push([
                'type' => 'Inquiry Received',
                'description' => "Inquiry for {$inquiry->property->title} from {$inquiry->name}",
                'timestamp' => $inquiry->created_at,
                'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>'
            ]);
        }

        // Payments Received
        $payments = Payment::whereHas('property', fn($q) => $q->where('owner_id', $ownerId))->where('status', 'completed')->with('property')->latest('approved_at')->limit(20)->get();
        foreach ($payments as $payment) {
            $activities->push([
                'type' => 'Payment Received',
                'description' => "Payment of ₹{$payment->amount} received for {$payment->property->title}",
                'timestamp' => $payment->approved_at ?? $payment->created_at,
                'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>'
            ]);
        }

        // Leads Generated
        $leads = Lead::whereHas('property', fn($q) => $q->where('owner_id', $ownerId))->with('property')->latest()->limit(20)->get();
        foreach ($leads as $lead) {
            $activities->push([
                'type' => 'Lead Generated',
                'description' => "Lead generated for {$lead->property->title} by {$lead->buyer_name}",
                'timestamp' => $lead->created_at,
                'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
            ]);
        }

        // Property Status Changes
        $statusChanges = PropertyVersion::whereHas('property', fn($q) => $q->where('owner_id', $ownerId))->whereIn('status', ['approved', 'rejected'])->with('property')->latest()->limit(20)->get();
        foreach ($statusChanges as $change) {
            $type = $change->status === 'approved' ? 'Property Approved' : 'Property Rejected';
            $icon = $change->status === 'approved' ? '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' : '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
            $activities->push([
                'type' => $type,
                'description' => "{$change->property->title} has been {$change->status}",
                'timestamp' => $change->created_at,
                'icon' => $icon
            ]);
        }

        return $activities->sortByDesc('timestamp')->take(5)->values();
    }

    public function dashboard()
    {
        $user = auth()->user();
        $properties = $user->properties()->count();
        $maxListings = $this->getCapabilityValue($user, 'max_listings');

        $totalInquiries = ViewedContact::whereHas('property', function($q) { $q->where('owner_id', auth()->id()); })->count();
        $activeListings = Property::where('owner_id', auth()->id())->where('status', 'active')->count();
        $totalEarnings = Payment::whereHas('property', function($q) { $q->where('owner_id', auth()->id()); })->where('status', 'completed')->sum('amount');
        $recentActivities = $this->getRecentActivities();

        return view('owner.dashboard', compact('properties', 'maxListings', 'totalInquiries', 'activeListings', 'totalEarnings', 'recentActivities'));
    }

    public function getStats()
    {
        $user = auth()->user();
        $properties = $user->properties()->count();
        $maxListings = $this->getCapabilityValue($user, 'max_listings');

        $totalInquiries = ViewedContact::whereHas('property', function($q) { $q->where('owner_id', auth()->id()); })->count();
        $activeListings = Property::where('owner_id', auth()->id())->where('status', 'active')->count();
        $totalEarnings = Payment::whereHas('property', function($q) { $q->where('owner_id', auth()->id()); })->where('status', 'completed')->sum('amount');

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

        \Illuminate\Support\Facades\Log::info('Owner plan page loaded', [
            'user_id' => $user->id,
            'active_plan_purchases_count' => $activePlanPurchases->count(),
            'combined_capabilities' => $combinedCapabilities
        ]);

        return view('owner.plan', compact('activePlanPurchases', 'combinedCapabilities'));
    }

    public function payments()
    {
        $user = auth()->user();
        $gatewayInfo = [
            'razorpay' => ['name' => 'Razorpay'],
            'phonepe' => ['name' => 'PhonePe'],
            'upi_static' => ['name' => 'UPI Static'],
        ];
        $payments = Payment::where('user_id', $user->id)
            ->with(['planPurchase.plan', 'property'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) use ($gatewayInfo) {
                $payment->gateway_name = $gatewayInfo[$payment->gateway]['name'] ?? ucfirst($payment->gateway);
                $payment->payment_mode = ucfirst(str_replace('_', ' ', $payment->type));
                return $payment;
            });

        return view('owner.payments', compact('payments'));
    }

    public function leads()
    {
        $leads = Lead::whereHas('property', function($q) {
            $q->where('owner_id', auth()->id());
        })->with('property.district.state')->orderBy('created_at', 'desc')->get();

        return view('owner.leads.index', compact('leads'));
    }

    public function showLead($id)
    {
        $lead = Lead::where('id', $id)
            ->whereHas('property', function($q) {
                $q->where('owner_id', auth()->id());
            })
            ->with(['property.district.state', 'visits', 'followUps'])
            ->firstOrFail();

        return view('owner.leads.show', compact('lead'));
    }

    public function viewedContacts()
    {
        $user = auth()->user();

        // Check if user has Premium or Elite plan
        $activePlans = $user->activePlanPurchases()->pluck('plan.name')->toArray();
        if (!in_array('Premium', $activePlans) && !in_array('Elite', $activePlans)) {
            abort(403, 'This feature is only available for Premium and Elite plan users.');
        }

        $viewedContacts = ViewedContact::whereHas('property', function($q) {
            $q->where('owner_id', $user->id);
        })->with(['buyer', 'property'])->orderBy('viewed_at', 'desc')->get();

        return view('owner.viewed-contacts', compact('viewedContacts'));
    }

}