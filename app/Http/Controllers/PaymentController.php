<?php

namespace App\Http\Controllers;

use App\Events\PaymentCreated;
use App\Events\PaymentSubmittedForApproval;
use App\Models\Payment;
use App\Models\Property;
use App\Models\ViewedContact;
use App\Services\PaymentService;
use App\Traits\CapabilityTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    use CapabilityTrait;

    const CONTACT_VIEW_AMOUNT = 10.00; // ₹10 for contact view

    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Initiate a payment for viewing contact
     */
    public function initiateContactPayment(Request $request, Property $property)
    {
        $user = Auth::user();

        if ($user->role === 'buyer') {
            return response()->json([
                'error' => 'Buyers cannot initiate contact payments'
            ], 403);
        }

        // Validate gateway parameter
        $request->validate([
            'gateway' => 'nullable|string|in:razorpay,phonepe,upi_static',
        ]);

        $gateway = $request->gateway;

        // Check if user has already viewed this contact
        $alreadyViewed = \App\Models\ViewedContact::where('buyer_id', $user->id)
            ->where('property_id', $property->id)
            ->exists();

        if ($alreadyViewed) {
            return response()->json([
                'error' => 'Contact already viewed'
            ], 400);
        }

        // Check if user has reached limit (skip for buyers)
        if ($user->role !== 'buyer') {
            $currentContactsViewed = $user->viewedContacts()->count();
            $maxContactsViewed = $this->getCapabilityValue($user, 'max_contacts');
        
            if ($currentContactsViewed < $maxContactsViewed) {
                return response()->json([
                    'error' => 'User has not reached contact limit yet'
                ], 400);
            }
        }

        // Check if gateway is enabled
        if ($gateway && !$this->paymentService->isGatewayEnabled($gateway)) {
            return response()->json([
                'error' => 'Selected payment gateway is not available'
            ], 400);
        }

        // Define payment amount
        $amount = self::CONTACT_VIEW_AMOUNT;

        try {
            // Create payment record
            $payment = $this->paymentService->createPaymentRecord(
                $user->id,
                $property->id,
                $amount,
                'contact_view',
                ['property_title' => $property->title],
                $gateway
            );

            PaymentCreated::dispatch($payment);

            // Create order using specified gateway
            $orderData = $this->paymentService->createOrder($amount, 'INR', [], $gateway);

            if (isset($orderData['error'])) {
                // Clean up on failure
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
                'payment' => $payment,
                'order' => $orderData,
                'gateway' => $gatewayName,
                'amount' => $amount * 100, // Amount in paisa for frontend
                'currency' => 'INR',
                'name' => 'AgroBrix',
                'description' => 'Payment for viewing contact details',
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
            } elseif ($gatewayName === 'upi_static') {
                $responseData['upi_url'] = $orderData['upi_url'] ?? null;
                $responseData['qr_code'] = $orderData['qr_code'] ?? null;
                $responseData['instructions'] = 'Scan the QR code or use the UPI URL to make payment. After payment, submit the transaction ID to complete the process.';
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Payment initiation failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to initiate payment'
            ], 500);
        }
    }

    /**
     * Handle payment success callback
     */
    public function paymentSuccess(Request $request)
    {
        // Validate common required fields
        $request->validate([
            'order_id' => 'required|string',
            'payment_id' => 'nullable|string',
            'signature' => 'nullable|string',
        ]);

        try {
            $orderId = $request->order_id;
            $paymentId = $request->payment_id;
            $signature = $request->signature;

            // Find payment by order ID
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Verify payment using the gateway associated with the payment
            if (!$this->paymentService->verifyPayment($orderId, $paymentId, $signature, $payment->gateway)) {
                return response()->json(['error' => 'Payment verification failed'], 400);
            }

            // Update payment with gateway details
            $this->paymentService->updatePaymentWithGatewayDetails($payment, $orderId, $paymentId, $signature);

            // Handle successful payment
            $this->paymentService->handleSuccessfulPayment($payment);

            // If it's a plan purchase, activate the plan
            if ($payment->type === 'plan_purchase' && $payment->planPurchase) {
                $payment->planPurchase->activate();
            }

            return response()->json([
                'success' => true,
                'message' => 'Payment successful',
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            Log::error('Payment success handling failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Payment processing failed'
            ], 500);
        }
    }

    /**
     * Submit transaction ID for UPI static payments
     */
    public function submitTransactionId(Request $request)
    {
        Log::info('submitTransactionId called', ['user_id' => Auth::id(), 'ip' => $request->ip()]);

        // Rate limiting: max 10 attempts per minute per user
        $rateLimitKey = 'transaction_id_submit_' . Auth::id();
        $attempts = \Illuminate\Support\Facades\Cache::get($rateLimitKey, 0);

        if ($attempts >= 10) {
            Log::warning('Rate limit exceeded for transaction ID submission', ['user_id' => Auth::id()]);
            return redirect()->back()->with('error', 'Too many attempts. Please wait a few minutes before trying again.');
        }

        // Enhanced validation with security checks
        $request->validate([
            'order_id' => 'required|string|regex:/^[a-zA-Z0-9_-]+$/|max:100',
            'transaction_id' => [
                'required',
                'string',
                'min:10',
                'max:50',
                'regex:/^[A-Za-z0-9]+$/',
                function ($attribute, $value, $fail) {
                    // Check for suspicious patterns
                    if (preg_match('/(.)\1{5,}/', $value)) {
                        $fail('Transaction ID contains suspicious repeated characters.');
                    }
                    if (preg_match('/^0+$/', $value)) {
                        $fail('Transaction ID cannot be all zeros.');
                    }
                    if (preg_match('/^[a-zA-Z]+$/', $value) && strlen($value) < 15) {
                        $fail('Transaction ID must contain both letters and numbers.');
                    }
                },
            ],
        ]);

        $orderId = trim($request->order_id);
        $transactionId = trim(strtoupper($request->transaction_id)); // Normalize to uppercase

        Log::info('Processing transaction ID submission', [
            'order_id' => $orderId,
            'transaction_id_length' => strlen($transactionId),
            'user_id' => Auth::id()
        ]);

        // Increment rate limit counter
        \Illuminate\Support\Facades\Cache::put($rateLimitKey, $attempts + 1, 60); // 1 minute

        try {
            // Find payment by order ID with additional security checks
            $payment = Payment::where('order_id', $orderId)
                ->where('gateway', 'upi_static')
                ->where('user_id', Auth::id())
                ->first();

            if (!$payment) {
                Log::warning('Payment not found or access denied', [
                    'order_id' => $orderId,
                    'user_id' => Auth::id(),
                    'ip' => $request->ip()
                ]);
                return redirect()->back()->with('error', 'Invalid payment reference. Please check your order details.');
            }

            // Additional security checks
            if ($payment->created_at->diffInHours(now()) > 24) {
                Log::warning('Payment attempt too old', ['payment_id' => $payment->id, 'created_at' => $payment->created_at]);
                return redirect()->back()->with('error', 'Payment session has expired. Please initiate a new payment.');
            }

            // Check if payment is still pending
            if (!$payment->isPending()) {
                Log::warning('Payment not in pending status', ['payment_id' => $payment->id, 'status' => $payment->status]);
                return redirect()->back()->with('error', 'Payment has already been processed.');
            }

            // Check if transaction ID is already submitted
            if ($payment->hasTransactionId()) {
                Log::warning('Transaction ID already submitted', ['payment_id' => $payment->id]);
                return redirect()->back()->with('error', 'Transaction ID has already been submitted for this payment.');
            }

            // Check for duplicate transaction IDs across recent payments (prevent reuse)
            $duplicateCheck = Payment::where('transaction_id', $transactionId)
                ->where('user_id', Auth::id())
                ->where('created_at', '>', now()->subHours(24))
                ->exists();

            if ($duplicateCheck) {
                Log::warning('Duplicate transaction ID detected', [
                    'transaction_id' => substr($transactionId, 0, 4) . '****',
                    'user_id' => Auth::id()
                ]);
                return redirect()->back()->with('error', 'This transaction ID has already been used. Please check your transaction details.');
            }

            // Submit transaction ID with additional metadata
            $payment->transaction_id = $transactionId;
            $payment->transaction_submitted_at = now();
            $payment->transaction_submitted_ip = $request->ip();
            $payment->transaction_submitted_user_agent = $request->userAgent();

            if (!$payment->save()) {
                Log::error('Failed to save transaction ID', ['payment_id' => $payment->id]);
                return redirect()->back()->with('error', 'Failed to process transaction ID. Please try again.');
            }

            // Set payment status to pending approval for admin review
            $payment->status = 'pending_approval';
            $payment->approval_status = 'pending';
            $payment->save();

            PaymentSubmittedForApproval::dispatch($payment);

            // Clear rate limit on success
            \Illuminate\Support\Facades\Cache::forget($rateLimitKey);

            Log::info('Transaction ID submitted successfully', [
                'payment_id' => $payment->id,
                'user_id' => Auth::id(),
                'transaction_id_prefix' => substr($transactionId, 0, 4) . '****'
            ]);

            // Redirect to static success page
            return redirect()->route('payment.static.success', $payment)->with('success', 'Transaction ID submitted successfully. Your payment is pending admin approval.');

        } catch (\Exception $e) {
            Log::error('Transaction ID submission failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'order_id' => $orderId,
                'ip' => $request->ip()
            ]);
            return redirect()->back()->with('error', 'An error occurred while processing your request. Please try again.');
        }
    }

    /**
     * Handle payment gateway webhooks
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $event = json_decode($payload, true);

        // Determine gateway from request headers or payload
        $gateway = $this->determineGatewayFromWebhook($request, $event);

        if (!$gateway) {
            Log::warning('Unable to determine gateway from webhook', ['headers' => $request->headers->all()]);
            return response()->json(['error' => 'Unable to determine gateway'], 400);
        }

        // Verify webhook based on gateway
        if (!$this->verifyWebhookForGateway($request, $payload, $gateway)) {
            return response()->json(['error' => 'Invalid signature'], 400);
        }

        Log::info('Webhook received', ['gateway' => $gateway, 'event' => $event]);

        // Handle webhook based on gateway
        if ($gateway === 'razorpay') {
            $this->handleRazorpayWebhook($event);
        } elseif ($gateway === 'phonepe') {
            $this->handlePhonePeWebhook($event);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Determine gateway from webhook request
     */
    private function determineGatewayFromWebhook(Request $request, array $event): ?string
    {
        // Check for Razorpay signature header
        if ($request->header('X-Razorpay-Signature')) {
            return 'razorpay';
        }

        // Check for PhonePe headers or payload structure
        if ($request->header('X-VERIFY') || (isset($event['merchantId']) && isset($event['transactionId']))) {
            return 'phonepe';
        }

        return null;
    }

    /**
     * Verify webhook signature for specific gateway
     */
    private function verifyWebhookForGateway(Request $request, string $payload, string $gateway): bool
    {
        if ($gateway === 'razorpay') {
            $webhookSecret = \App\Models\Setting::get('razorpay_webhook_secret', config('services.razorpay.webhook_secret', ''));
            $signature = $request->header('X-Razorpay-Signature');
            return $this->verifyWebhookSignature($payload, $signature, $webhookSecret);
        } elseif ($gateway === 'phonepe') {
            // PhonePe webhook verification - implement based on PhonePe documentation
            $signature = $request->header('X-VERIFY');
            $webhookSecret = \App\Models\Setting::get('phonepe_webhook_secret', '');
            if (!$webhookSecret) {
                Log::warning('PhonePe webhook secret not configured');
                return false;
            }
            // PhonePe uses different verification method
            return $this->verifyPhonePeWebhookSignature($payload, $signature, $webhookSecret);
        }

        return false;
    }

    /**
     * Handle Razorpay webhook
     */
    private function handleRazorpayWebhook(array $event): void
    {
        if ($event['event'] === 'payment.captured') {
            $paymentId = $event['data']['payment']['id'];
            $orderId = $event['data']['payment']['order_id'];

            // Find and update payment
            $payment = Payment::where('order_id', $orderId)->first();

            if ($payment && $payment->isPending()) {
                $this->paymentService->updatePaymentWithGatewayDetails($payment, $orderId, $paymentId);
                $this->paymentService->handleSuccessfulPayment($payment);

                // If it's a plan purchase, activate the plan
                if ($payment->type === 'plan_purchase' && $payment->planPurchase) {
                    $payment->planPurchase->activate();
                }
            }
        }
    }

    /**
     * Handle PhonePe webhook
     */
    private function handlePhonePeWebhook(array $event): void
    {
        // PhonePe webhook handling - implement based on PhonePe webhook structure
        if (isset($event['transactionId']) && isset($event['state'])) {
            $transactionId = $event['transactionId'];
            $state = $event['state'];

            // Find payment by order ID (transactionId)
            $payment = Payment::where('order_id', $transactionId)->first();

            if ($payment && $payment->isPending() && $state === 'COMPLETED') {
                $this->paymentService->updatePaymentWithGatewayDetails($payment, $transactionId, null, null, $event);
                $this->paymentService->handleSuccessfulPayment($payment);

                // If it's a plan purchase, activate the plan
                if ($payment->type === 'plan_purchase' && $payment->planPurchase) {
                    $payment->planPurchase->activate();
                }
            }
        }
    }

    /**
     * Verify webhook signature
     */
    private function verifyWebhookSignature($payload, $signature, $secret): bool
    {
        $expectedSignature = hash_hmac('sha256', $payload, $secret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Verify PhonePe webhook signature
     */
    private function verifyPhonePeWebhookSignature($payload, $signature, $secret): bool
    {
        // PhonePe webhook verification implementation
        // This may need to be adjusted based on PhonePe's exact verification method
        $expectedSignature = hash('sha256', $payload . $secret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Show payment success page
     */
    public function success(Payment $payment)
    {
        // Ensure user owns the payment
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        return view('payments.success', compact('payment'));
    }

    /**
     * Show static QR payment success page
     */
    public function staticSuccess(Payment $payment)
    {
        // Ensure user owns the payment
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        // Ensure this is a static QR payment
        if ($payment->gateway !== 'upi_static') {
            abort(404);
        }

        return view('payments.static_success', compact('payment'));
    }

    /**
     * Show payment failure page
     */
    public function failure(Request $request)
    {
        $error = session('error') ?? $request->get('error', 'Payment was cancelled or failed.');
        return view('payments.failure', compact('error'));
    }
}