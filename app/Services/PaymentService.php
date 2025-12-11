// Modified for UPI static gateway
<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $gatewayManager;

    public function __construct(PaymentGatewayManager $gatewayManager)
    {
        $this->gatewayManager = $gatewayManager;
    }

    /**
     * Check if a specific gateway is enabled
     */
    public function isGatewayEnabled(string $gateway = null): bool
    {
        return $this->gatewayManager->getGateway($gateway)->isEnabled();
    }

    /**
     * Check if Razorpay is enabled (backward compatibility)
     */
    public function isRazorpayEnabled(): bool
    {
        return $this->isGatewayEnabled('razorpay');
    }

    /**
     * Check if PhonePe is enabled
     */
    public function isPhonePeEnabled(): bool
    {
        return $this->isGatewayEnabled('phonepe');
    }

    /**
     * Get available payment gateways
     */
    public function getAvailableGateways(): array
    {
        return $this->gatewayManager->getAvailableGateways();
    }

    /**
     * Get the default payment gateway
     */
    public function getDefaultGateway(): string
    {
        return $this->gatewayManager->getDefaultGateway();
    }

    /**
     * Create an order for payment using specified gateway
     */
    public function createOrder(float $amount, string $currency = 'INR', array $metadata = [], string $gateway = null): array
    {
        $gatewayInstance = $this->gatewayManager->getGateway($gateway);

        $order = $gatewayInstance->createOrder($amount, $currency, $metadata);

        if (isset($order['error'])) {
            Log::warning('Payment order creation failed due to configuration', [
                'gateway' => $gatewayInstance->getGatewayName(),
                'amount' => $amount,
                'error' => $order['error']
            ]);
            return $order;
        }

        Log::info('Payment order created', [
            'gateway' => $gatewayInstance->getGatewayName(),
            'order_id' => $order['order_id'],
            'amount' => $amount
        ]);
        return $order;
    }
    /**
     * Verify payment using specified gateway
     */
    public function verifyPayment(string $orderId, string $paymentId, string $signature, string $gateway = null): bool
    {
        $gatewayInstance = $this->gatewayManager->getGateway($gateway);

        try {
            $verified = $gatewayInstance->verifyPayment($orderId, $paymentId, $signature);
            Log::info('Payment verification', [
                'gateway' => $gatewayInstance->getGatewayName(),
                'order_id' => $orderId,
                'payment_id' => $paymentId,
                'verified' => $verified
            ]);
            return $verified;
        } catch (\Exception $e) {
            Log::error('Payment verification failed', [
                'gateway' => $gatewayInstance->getGatewayName(),
                'order_id' => $orderId,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Fetch payment details using specified gateway
     */
    public function fetchPayment(string $paymentId, string $gateway = null): array
    {
        $gatewayInstance = $this->gatewayManager->getGateway($gateway);

        try {
            $payment = $gatewayInstance->fetchPayment($paymentId);
            Log::info('Payment fetched', [
                'gateway' => $gatewayInstance->getGatewayName(),
                'payment_id' => $paymentId
            ]);
            return $payment;
        } catch (\Exception $e) {
            Log::error('Payment fetch failed', [
                'gateway' => $gatewayInstance->getGatewayName(),
                'payment_id' => $paymentId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Create payment record in database
     */
    public function createPaymentRecord(int $userId, ?int $propertyId, float $amount, string $type = 'contact_view', array $metadata = [], string $gateway = null): Payment
    {
        $gatewayName = $gateway ?: $this->gatewayManager->getDefaultGateway();

        return Payment::create([
            'user_id' => $userId,
            'property_id' => $propertyId,
            'amount' => $amount,
            'status' => 'pending',
            'type' => $type,
            'metadata' => json_encode($metadata),
            'gateway' => $gatewayName,
        ]);
    }

    /**
     * Update payment with gateway details
     */
    public function updatePaymentWithGatewayDetails(Payment $payment, string $orderId, string $paymentId = null, string $signature = null, array $gatewayResponse = null): bool
    {
        $updateData = [
            'order_id' => $orderId,
        ];

        if ($paymentId) {
            $updateData['payment_id'] = $paymentId;
        }

        if ($signature) {
            $updateData['signature'] = $signature;
            $updateData['status'] = 'completed';
        }

        if ($gatewayResponse) {
            $updateData['gateway_response'] = json_encode($gatewayResponse);
        }

        return $payment->update($updateData);
    }

    /**
     * Update payment with Razorpay details (backward compatibility)
     */
    public function updatePaymentWithRazorpay(Payment $payment, string $orderId, string $paymentId = null, string $signature = null): bool
    {
        return $this->updatePaymentWithGatewayDetails($payment, $orderId, $paymentId, $signature);
    }

    /**
     * Handle successful payment
     */
    public function handleSuccessfulPayment(Payment $payment): void
    {
        // Update payment status
        $payment->update(['status' => 'completed']);

        // Handle specific payment types
        switch ($payment->type) {
            case 'contact_view':
                $this->handleContactViewPayment($payment);
                break;
            case 'plan_purchase':
                $this->handlePlanPurchasePayment($payment);
                break;
            // Add more cases for other payment types
        }
    }

    /**
     * Handle contact view payment success
     */
    private function handleContactViewPayment(Payment $payment): void
    {
        // Record the contact view
        \App\Models\ViewedContact::create([
            'buyer_id' => $payment->user_id,
            'property_id' => $payment->property_id,
        ]);
    }

    /**
     * Handle plan purchase payment success
     */
    private function handlePlanPurchasePayment(Payment $payment): void
    {
        // Activate the plan purchase
        if ($payment->planPurchase) {
            $payment->planPurchase->activate();
        }
    }

    /**
     * Handle payment approval for UPI static payments
     */
    public function handlePaymentApproval(Payment $payment): void
    {
        if (!$payment->isApproved()) {
            return;
        }

        // Handle successful payment processing
        $this->handleSuccessfulPayment($payment);
    }
}