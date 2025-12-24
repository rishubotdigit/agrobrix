<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Visit;
use App\Models\Payment;
use App\Models\FollowUp;
use App\Models\ViewedContact;

class DashboardController extends Controller
{
    private function getRecentActivities()
    {
        $agentId = auth()->id();
        $activities = collect();

        // Leads
        $leads = Lead::where('agent_id', $agentId)->with('property')->latest()->limit(20)->get();
        foreach ($leads as $lead) {
            $activities->push([
                'type' => 'New Lead',
                'description' => "New lead for {$lead->property->title} by {$lead->buyer_name}",
                'timestamp' => $lead->created_at,
                'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>'
            ]);
        }

        // Properties
        $properties = Property::where('agent_id', $agentId)->latest()->limit(20)->get();
        foreach ($properties as $property) {
            $activities->push([
                'type' => 'Property Added',
                'description' => "Added property {$property->title}",
                'timestamp' => $property->created_at,
                'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>'
            ]);
        }

        // Payments
        $payments = Payment::whereHas('property', fn($q) => $q->where('agent_id', $agentId))->where('status', 'completed')->with('property')->latest('approved_at')->limit(20)->get();
        foreach ($payments as $payment) {
            $activities->push([
                'type' => 'Payment Received',
                'description' => "Payment of ₹{$payment->amount} received for {$payment->property->title}",
                'timestamp' => $payment->approved_at ?? $payment->created_at,
                'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>'
            ]);
        }

        // Visits
        $visits = Visit::whereHas('lead.property', fn($q) => $q->where('agent_id', $agentId))->with('lead.property')->latest()->limit(20)->get();
        foreach ($visits as $visit) {
            if ($visit->status === 'completed') {
                $activities->push([
                    'type' => 'Visit Completed',
                    'description' => "Visit completed for {$visit->lead->property->title}",
                    'timestamp' => $visit->updated_at,
                    'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                ]);
            } else {
                $activities->push([
                    'type' => 'Visit Scheduled',
                    'description' => "Visit scheduled for {$visit->lead->property->title} on {$visit->scheduled_at->format('M d, Y')}",
                    'timestamp' => $visit->created_at,
                    'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>'
                ]);
            }
        }

        // FollowUps
        $followups = FollowUp::whereHas('lead.property', fn($q) => $q->where('agent_id', $agentId))->with('lead.property')->latest()->limit(20)->get();
        foreach ($followups as $followup) {
            $activities->push([
                'type' => 'Follow-up Added',
                'description' => "Follow-up added for lead {$followup->lead->buyer_name} on {$followup->lead->property->title}",
                'timestamp' => $followup->created_at,
                'icon' => '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>'
            ]);
        }

        return $activities->sortByDesc('timestamp')->take(10)->values();
    }

    public function dashboard()
    {
        $agentId = auth()->id();
        $activeClients = Lead::where('agent_id', $agentId)->whereNotNull('agent_id')->count();
        $managedProperties = Property::where('agent_id', $agentId)->count();
        $closedDeals = Visit::whereHas('lead.property', function($q) use ($agentId) { $q->where('agent_id', $agentId); })->where('status', 'completed')->count();
        $totalCommission = Payment::whereHas('property', function($q) use ($agentId) { $q->where('agent_id', $agentId); })->where('status', 'completed')->sum('amount');
        $recentActivities = $this->getRecentActivities();

        // Check for analytics access
        $user = auth()->user();
        $activePlanPurchases = $user->activePlanPurchases();
        $hasAnalyticsAccess = $activePlanPurchases->contains(function($purchase) {
            return in_array($purchase->plan->name, ['Professional', 'Business', 'Enterprise']);
        });

        $analyticsData = [];
        if ($hasAnalyticsAccess) {
            // Property views
            $propertyViews = \App\Models\ViewedContact::whereHas('property', function($q) use ($agentId) {
                $q->where('agent_id', $agentId);
            })->count();

            // Leads (inquiries)
            $totalLeads = Lead::where('agent_id', $agentId)->count();

            // Monthly data for charts (last 12 months)
            $monthlyLeads = [];
            $monthlyViews = [];
            $monthlyDeals = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $start = $date->startOfMonth();
                $end = $date->endOfMonth();

                $monthlyLeads[] = Lead::where('agent_id', $agentId)->whereBetween('created_at', [$start, $end])->count();
                $monthlyViews[] = \App\Models\ViewedContact::whereHas('property', function($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })->whereBetween('viewed_at', [$start, $end])->count();
                $monthlyDeals[] = Visit::whereHas('lead.property', function($q) use ($agentId) {
                    $q->where('agent_id', $agentId);
                })->where('status', 'completed')->whereBetween('updated_at', [$start, $end])->count();
            }

            $analyticsData = [
                'propertyViews' => $propertyViews,
                'totalLeads' => $totalLeads,
                'monthlyLeads' => $monthlyLeads,
                'monthlyViews' => $monthlyViews,
                'monthlyDeals' => $monthlyDeals,
            ];
        }

        return view('agent.dashboard', compact('activeClients', 'managedProperties', 'closedDeals', 'totalCommission', 'recentActivities', 'hasAnalyticsAccess', 'analyticsData'));
    }

    public function plan()
    {
        $user = auth()->user();
        $activePlanPurchases = $user->activePlanPurchases();
        $combinedCapabilities = $user->getCombinedCapabilities();

        return view('agent.plan', compact('activePlanPurchases', 'combinedCapabilities'));
    }

    public function getStats()
    {
        $activeClients = Lead::where('agent_id', auth()->id())->whereNotNull('agent_id')->count();
        $managedProperties = Property::where('agent_id', auth()->id())->count();
        $closedDeals = Visit::whereHas('lead.property', function($q) { $q->where('agent_id', auth()->id()); })->where('status', 'completed')->count();
        $totalCommission = Payment::whereHas('property', function($q) { $q->where('agent_id', auth()->id()); })->where('status', 'completed')->sum('amount');

        return response()->json([
            'activeClients' => $activeClients,
            'managedProperties' => $managedProperties,
            'closedDeals' => $closedDeals,
            'totalCommission' => $totalCommission,
        ]);
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

        return view('agent.payments', compact('payments'));
    }
}
