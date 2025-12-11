<?php

namespace App\Http\Controllers;

use App\Events\PlanPurchaseCreated;
use App\Models\Plan;
use App\Models\PlanPurchase;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PlanController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function index()
    {
        $plans = Plan::all();

        return view('plans.index', compact('plans'));
    }

    public function purchase(Plan $plan)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('message', 'Please login to continue with the purchase.')->with('url.intended', request()->url());
        }

        // Validate gateway parameter
        $gateway = request('gateway');
        if ($gateway && !$this->paymentService->isGatewayEnabled($gateway)) {
            return redirect()->back()->with('error', 'Selected payment gateway is not available');
        }

        // For free plans, create purchase directly
        if ($plan->price == 0) {
            $purchase = PlanPurchase::create([
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'status' => 'activated',
                'activated_at' => now(),
                'expires_at' => now()->addMonth(),
            ]);

            Log::info('Free plan purchase created', ['purchase_id' => $purchase->id, 'user_id' => auth()->id(), 'plan_id' => $plan->id]);

            return redirect()->route(auth()->user()->role . '.dashboard')
                            ->with('success', "Successfully subscribed to {$plan->name} plan!");
        }

        // For paid plans, initiate payment
        $order = $this->paymentService->createOrder($plan->price, 'INR', [
            'plan_id' => $plan->id,
            'user_id' => auth()->id(),
        ], $gateway);

        if (isset($order['error'])) {
            return redirect()->back()->with('error', $order['error']);
        }

        return view('plans.purchase', compact('plan', 'order'));
    }

    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $plan = Plan::findOrFail($request->plan_id);

        // Verify payment with Razorpay
        $verified = $this->paymentService->verifyPayment(
            $request->razorpay_payment_id,
            $request->razorpay_order_id,
            $request->razorpay_signature
        );

        if (!$verified) {
            return response()->json(['success' => false, 'message' => 'Payment verification failed']);
        }

        // Create payment record
        $payment = \App\Models\Payment::create([
            'user_id' => auth()->id(),
            'amount' => $plan->price,
            'payment_method' => 'razorpay',
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_signature' => $request->razorpay_signature,
            'status' => 'completed',
        ]);

        // Create plan purchase
        $purchase = PlanPurchase::create([
            'user_id' => auth()->id(),
            'plan_id' => $plan->id,
            'payment_id' => $payment->id,
            'status' => 'pending',
        ]);

        Log::info('Paid plan purchase created', ['purchase_id' => $purchase->id, 'user_id' => auth()->id(), 'plan_id' => $plan->id, 'status' => 'pending']);

        // Dispatch event to notify admins
        PlanPurchaseCreated::dispatch($purchase);

        return response()->json([
            'success' => true,
            'message' => "Successfully subscribed to {$plan->name} plan!",
            'redirect' => route(auth()->user()->role . '.dashboard')
        ]);
    }
}