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

        // Rate limiting for purchase attempts
        $rateLimitKey = 'plan_purchase_' . auth()->id();
        $attempts = \Illuminate\Support\Facades\Cache::get($rateLimitKey, 0);

        if ($attempts >= 20) { // Max 20 purchase attempts per hour
            Log::warning('Purchase rate limit exceeded', ['user_id' => auth()->id()]);
            return redirect()->back()->with('error', 'Too many purchase attempts. Please wait before trying again.');
        }

        // Enhanced gateway validation
        $gateway = request('gateway');
        $allowedGateways = ['razorpay', 'phonepe', 'upi_static'];

        if ($gateway && (!in_array($gateway, $allowedGateways) || !$this->paymentService->isGatewayEnabled($gateway))) {
            Log::warning('Invalid or disabled gateway attempted', [
                'gateway' => $gateway,
                'user_id' => auth()->id(),
                'ip' => request()->ip()
            ]);
            return redirect()->back()->with('error', 'Selected payment method is not available');
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

        // Increment rate limit counter
        \Illuminate\Support\Facades\Cache::put($rateLimitKey, $attempts + 1, 3600); // 1 hour

        // For paid plans, initiate payment with additional security context
        $order = $this->paymentService->createOrder($plan->price, 'INR', [
            'plan_id' => $plan->id,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId(),
        ], $gateway);

        if (isset($order['error'])) {
            Log::error('Order creation failed', [
                'error' => $order['error'],
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'gateway' => $gateway
            ]);
            return redirect()->back()->with('error', 'Payment initialization failed. Please try again.');
        }

        // Determine the actual gateway used for the order
        $usedGateway = $gateway ?: $this->paymentService->getDefaultGateway();

        // For UPI static, create payment record and plan purchase
        if ($usedGateway === 'upi_static') {
            Log::info('Creating payment record for UPI static plan purchase', [
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'amount' => $plan->price,
                'order_id_from_order' => $order['order_id'] ?? 'not set',
                'used_gateway' => $usedGateway
            ]);

            $payment = $this->paymentService->createPaymentRecord(
                auth()->id(),
                null,
                $plan->price,
                'plan_purchase',
                ['plan_id' => $plan->id],
                $usedGateway
            );

            Log::info('Payment record created', ['payment_id' => $payment->id, 'status' => $payment->status]);

            $updateSuccess = $this->paymentService->updatePaymentWithGatewayDetails($payment, $order['order_id']);

            if ($updateSuccess) {
                Log::info('Payment updated with gateway details', ['payment_id' => $payment->id, 'order_id' => $order['order_id']]);
            } else {
                Log::error('Failed to update payment with gateway details, setting order_id manually', ['payment_id' => $payment->id, 'order_id' => $order['order_id']]);
                $payment->order_id = $order['order_id'];
                $payment->save();
            }

            // Create plan purchase record
            $purchase = PlanPurchase::create([
                'user_id' => auth()->id(),
                'plan_id' => $plan->id,
                'payment_id' => $payment->id,
                'status' => 'pending',
            ]);

            // Link payment to plan purchase
            $payment->update(['plan_purchase_id' => $purchase->id]);

            Log::info('Plan purchase created for UPI static', ['purchase_id' => $purchase->id, 'user_id' => auth()->id(), 'plan_id' => $plan->id, 'status' => 'pending']);

            // Dispatch event to notify admins
            PlanPurchaseCreated::dispatch($purchase);
        }

        // Return view with security headers
        return response()
            ->view('plans.purchase', compact('plan', 'order'))
            ->header('X-Frame-Options', 'DENY')
            ->header('X-Content-Type-Options', 'nosniff')
            ->header('X-XSS-Protection', '1; mode=block')
            ->header('Referrer-Policy', 'strict-origin-when-cross-origin')
            ->header('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' https://checkout.razorpay.com https://cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https:; font-src 'self' data: https://fonts.gstatic.com; connect-src 'self'");
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
            return redirect()->route('payment.failure')
                            ->with('error', 'Payment verification failed. Please contact support if amount was debited.');
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

        return redirect()->route('payment.success', $payment->id)
                        ->with('success', "Successfully subscribed to {$plan->name} plan!");
    }
}