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
            return response()->json([
                'success' => false,
                'message' => 'Payment is not pending approval'
            ], 400);
        }

        $adminId = Auth::id();
        $notes = $request->admin_notes;

        if ($payment->approve($adminId, $notes)) {
            // Handle successful payment processing
            $this->paymentService->handlePaymentApproval($payment);

            return response()->json([
                'success' => true,
                'message' => 'Payment approved successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to approve payment'
        ], 500);
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
            return response()->json([
                'success' => false,
                'message' => 'Payment is not pending approval'
            ], 400);
        }

        $adminId = Auth::id();
        $notes = $request->admin_notes;

        if ($payment->reject($adminId, $notes)) {
            return response()->json([
                'success' => true,
                'message' => 'Payment rejected successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to reject payment'
        ], 500);
    }
}