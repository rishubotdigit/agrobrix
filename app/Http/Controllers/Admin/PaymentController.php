<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    /**
     * Display a listing of all payments for plan purchases.
     */
    public function index(Request $request)
    {
        $query = Payment::with(['user', 'planPurchase', 'planPurchase.plan'])
            ->whereNotNull('plan_purchase_id');

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Gateway filter
        if ($request->filled('gateway')) {
            $query->where('gateway', $request->gateway);
        }

        // Approval status filter
        if ($request->filled('approval_status')) {
            $query->where('approval_status', $request->approval_status);
        }

        // User search filter
        if ($request->filled('user_search')) {
            $search = $request->user_search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Amount range filters
        if ($request->filled('amount_min')) {
            $query->where('amount', '>=', $request->amount_min);
        }

        if ($request->filled('amount_max')) {
            $query->where('amount', '<=', $request->amount_max);
        }

        // Date range filters
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        return view('admin.payments.index', compact('payments'));
    }


    /**
     * Get all payments pending admin approval
     */
    public function pendingApprovals()
    {
        $pendingPayments = Payment::with(['user', 'property', 'approvedBy'])
            ->where('status', 'pending_approval')
            ->where('approval_status', 'pending')
            ->where('gateway', 'upi_static')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.payments.pending', compact('pendingPayments'));
    }

    /**
     * Approve a payment
     */
    public function approvePayment(Request $request, Payment $payment)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if (!$payment->isPendingApproval()) {
            return redirect()->back()->with('error', 'Payment is not pending approval');
        }

        $adminId = Auth::id();
        $notes = $request->admin_notes;

        if ($payment->approve($adminId, $notes)) {
            // Handle successful payment processing
            $this->paymentService->handlePaymentApproval($payment);

            return redirect()->back()->with('success', 'Payment approved successfully');
        }

        return redirect()->back()->with('error', 'Failed to approve payment');
    }

    /**
     * Reject a payment
     */
    public function rejectPayment(Request $request, Payment $payment)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        if (!$payment->isPendingApproval()) {
            return redirect()->back()->with('error', 'Payment is not pending approval');
        }

        $adminId = Auth::id();
        $notes = $request->admin_notes;

        if ($payment->reject($adminId, $notes)) {
            return redirect()->back()->with('success', 'Payment rejected successfully');
        }

        return redirect()->back()->with('error', 'Failed to reject payment');
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['user', 'planPurchase', 'planPurchase.plan']);
        return view('admin.payments.show', compact('payment'));
    }
}