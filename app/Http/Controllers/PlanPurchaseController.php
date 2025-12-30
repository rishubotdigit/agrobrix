<?php

namespace App\Http\Controllers;

use App\Events\PlanPurchaseCreated;
use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanPurchaseController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Display a listing of the user's plan purchases.
     */
    public function index()
    {
        $user = Auth::user();
        $purchases = $user->planPurchases()->with(['plan', 'payment'])->whereHas('plan', function($q) {
            $q->where('price', '>', 0);
        })->get();

        return response()->json($purchases);
    }

    /**
     * Initiate a plan purchase payment.
     */
    public function initiatePurchase(Request $request, Plan $plan)
    {
        $user = Auth::user();

        // Prevent purchase of free plans
        if ($plan->price == 0) {
            return response()->json([
                'error' => 'Free plans cannot be purchased manually'
            ], 400);
        }

        // Enforce role-based restrictions
        if ($user->role !== $plan->role) {
            return response()->json([
                'error' => 'You can only purchase plans that match your account role (' . ucfirst($user->role) . '). This plan is for ' . ucfirst($plan->role) . 's.'
            ], 403);
        }

        // Validate gateway parameter
        $request->validate([
            'gateway' => 'nullable|string|in:razorpay,phonepe,upi_static',
        ]);

        $gateway = $request->gateway;

        // Check if gateway is enabled
        if ($gateway && !$this->paymentService->isGatewayEnabled($gateway)) {
            return response()->json([
                'error' => 'Selected payment gateway is not available'
            ], 400);
        }

        $amount = $plan->price;

        try {
            // Create payment record first
            $payment = $this->paymentService->createPaymentRecord(
                $user->id,
                null, // No property for plan purchase
                $amount,
                'plan_purchase',
                ['plan_name' => $plan->name],
                $gateway
            );

            // Create plan purchase record
            $planPurchase = PlanPurchase::create([
                'user_id' => $user->id,
                'plan_id' => $plan->id,
                'payment_id' => $payment->id,
                'status' => 'pending',
            ]);

            \Illuminate\Support\Facades\Log::info('Plan purchase initiated', [
                'plan_purchase_id' => $planPurchase->id,
                'user_id' => $user->id,
                'plan_id' => $plan->id
            ]);

            // Fire event for admin notification
            event(new PlanPurchaseCreated($planPurchase));

            // Link payment to plan purchase
            $payment->update(['plan_purchase_id' => $planPurchase->id]);

            // Create order using specified gateway
            $orderData = $this->paymentService->createOrder($amount, 'INR', [], $gateway);

            if (isset($orderData['error'])) {
                // Clean up on failure
                $planPurchase->delete();
                $payment->delete();
                return response()->json([
                    'error' => $orderData['error']
                ], 400);
            }

            // Update payment with gateway details
            $this->paymentService->updatePaymentWithGatewayDetails($payment, $orderData['order_id']);

            // Get gateway-specific response data
            $gatewayInstance = $this->paymentService->gatewayManager->getGateway($gateway);
            $gatewayName = $gatewayInstance->getGatewayName();

            $responseData = [
                'plan_purchase' => $planPurchase,
                'payment' => $payment,
                'order' => $orderData,
                'gateway' => $gatewayName,
                'amount' => $amount * 100, // Amount in paisa for frontend
                'currency' => 'INR',
                'name' => 'AgroBrix',
                'description' => 'Payment for ' . $plan->name . ' plan',
                'prefill' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ]
            ];

            // Add gateway-specific data
            if ($gatewayName === 'razorpay') {
                $responseData['razorpay_key'] = \App\Models\Setting::get('razorpay_key_id', config('services.razorpay.key_id', ''));
            } elseif ($gatewayName === 'phonepe' && isset($orderData['payment_url'])) {
                $responseData['payment_url'] = $orderData['payment_url'];
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            // Clean up on failure
            if (isset($planPurchase)) {
                $planPurchase->delete();
            }
            if (isset($payment)) {
                $payment->delete();
            }

            return response()->json([
                'error' => 'Failed to initiate plan purchase'
            ], 500);
        }
    }

    /**
     * Display the specified plan purchase.
     */
    public function show(PlanPurchase $planPurchase)
    {
        // Ensure user owns the purchase
        if ($planPurchase->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json($planPurchase->load(['plan', 'payment']));
    }
}
