<?php

namespace App\Http\Controllers;

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

        // Check if user has reached limit
        $currentContactsViewed = $user->viewedContacts()->count();
        $maxContactsViewed = $this->getCapabilityValue($user, 'max_contacts');

        if ($currentContactsViewed < $maxContactsViewed) {
            return response()->json([
                'error' => 'User has not reached contact limit yet'
            ], 400);
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
        // Validate request
        $request->validate([
            'order_id' => 'required|string',
            'transaction_id' => 'required|string|min:10|max:50',
        ]);

        $orderId = $request->order_id;
        $transactionId = $request->transaction_id;

        try {
            // Find payment by order ID
            $payment = Payment::where('order_id', $orderId)->first();

            if (!$payment) {
                return response()->json(['error' => 'Payment not found'], 404);
            }

            // Check if payment belongs to authenticated user
            if ($payment->user_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Check if payment is for UPI static gateway
            if ($payment->gateway !== 'upi_static') {
                return response()->json(['error' => 'This method is only for UPI static payments'], 400);
            }

            // Check if payment is still pending
            if (!$payment->isPending()) {
                return response()->json(['error' => 'Payment is not in pending status'], 400);
            }

            // Check if transaction ID is already submitted
            if ($payment->hasTransactionId()) {
                return response()->json(['error' => 'Transaction ID already submitted'], 400);
            }

            // Submit transaction ID
            if (!$payment->submitTransactionId($transactionId)) {
                return response()->json(['error' => 'Failed to submit transaction ID'], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaction ID submitted successfully. Payment is now pending admin approval.',
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            Log::error('Transaction ID submission failed: ' . $e->getMessage());
            return response()->json([
                'error' => 'Transaction ID submission failed'
            ], 500);
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
}