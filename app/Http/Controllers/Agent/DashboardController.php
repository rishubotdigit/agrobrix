<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Property;
use App\Models\Visit;
use App\Models\Payment;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $activeClients = Lead::where('agent_id', auth()->id())->whereNotNull('agent_id')->count();
        $managedProperties = Property::where('agent_id', auth()->id())->count();
        $closedDeals = Visit::whereHas('lead.property', function($q) { $q->where('agent_id', auth()->id()); })->where('status', 'completed')->count();
        $totalCommission = Payment::whereHas('property', function($q) { $q->where('agent_id', auth()->id()); })->where('status', 'completed')->sum('amount');

        return view('agent.dashboard', compact('activeClients', 'managedProperties', 'closedDeals', 'totalCommission'));
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
