<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlanPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanPurchaseController extends Controller
{
    /**
     * Display a listing of all plan purchases.
     */
    public function index(Request $request)
    {
        $query = PlanPurchase::with(['user', 'plan', 'payment']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $purchases = $query->orderBy('created_at', 'desc')->get();
        return view('admin.plan-purchases.index', compact('purchases'));
    }

    /**
     * Display the specified plan purchase.
     */
    public function show(PlanPurchase $planPurchase)
    {
        $planPurchase->load(['user', 'plan', 'payment']);
        return view('admin.plan-purchases.show', compact('planPurchase'));
    }

    /**
     * Activate a plan purchase manually.
     */
    public function activate(PlanPurchase $planPurchase)
    {
        if ($planPurchase->status !== 'approved') {
            return redirect()->back()->with('error', 'Only approved plans can be activated');
        }

        $planPurchase->activate();

        Log::info('Plan purchase activated', ['purchase_id' => $planPurchase->id, 'admin_id' => auth()->id()]);

        return redirect()->back()->with('success', 'Plan purchase activated successfully');
    }

    /**
     * Expire a plan purchase manually.
     */
    public function expire(PlanPurchase $planPurchase)
    {
        $planPurchase->update([
            'status' => 'expired',
            'expires_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Plan purchase expired successfully');
    }

    /**
     * Approve a plan purchase.
     */
    public function approve(PlanPurchase $planPurchase)
    {
        if ($planPurchase->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending purchases can be approved');
        }

        $planPurchase->update(['status' => 'approved']);

        Log::info('Plan purchase approved', ['purchase_id' => $planPurchase->id, 'admin_id' => auth()->id()]);

        return redirect()->back()->with('success', 'Plan purchase approved successfully');
    }

    /**
     * Reject a plan purchase.
     */
    public function reject(PlanPurchase $planPurchase)
    {
        if ($planPurchase->status !== 'pending') {
            return redirect()->back()->with('error', 'Only pending purchases can be rejected');
        }

        $planPurchase->update(['status' => 'rejected']);

        return redirect()->back()->with('success', 'Plan purchase rejected successfully');
    }
}
