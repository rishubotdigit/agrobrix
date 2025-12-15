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

class DashboardController extends Controller
{
    use CapabilityTrait;

    public function dashboard()
    {
        $user = auth()->user();
        $properties = $user->properties()->count();
        $maxListings = $this->getCapabilityValue($user, 'max_listings');

        $totalInquiries = ViewedContact::whereHas('property', function($q) { $q->where('owner_id', auth()->id()); })->count();
        $activeListings = Property::where('owner_id', auth()->id())->where('status', 'active')->count();
        $totalEarnings = Payment::whereHas('property', function($q) { $q->where('owner_id', auth()->id()); })->where('status', 'completed')->sum('amount');
        $recentProperties = Property::where('user_id', auth()->id())->orderBy('created_at', 'desc')->limit(5)->get();

        return view('owner.dashboard', compact('properties', 'maxListings', 'totalInquiries', 'activeListings', 'totalEarnings', 'recentProperties'));
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
        })->with('property')->orderBy('created_at', 'desc')->get();

        return view('owner.leads.index', compact('leads'));
    }

    public function showLead($id)
    {
        $lead = Lead::where('id', $id)
            ->whereHas('property', function($q) {
                $q->where('owner_id', auth()->id());
            })
            ->with(['property', 'visits', 'followUps'])
            ->firstOrFail();

        return view('owner.leads.show', compact('lead'));
    }

}