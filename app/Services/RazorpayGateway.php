<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Razorpay\Api\Api;

class RazorpayGateway implements PaymentGatewayInterface
{
    protected $api;

    public function __construct()
    {
        $keyId = Setting::get('razorpay_key_id', Config::get('services.razorpay.key_id', ''));
        $keySecret = Setting::get('razorpay_key_secret', Config::get('services.razorpay.key_secret', ''));
        if ($keyId && $keySecret) {
            $this->api = new Api($keyId, $keySecret);
        }
    }

    public function isEnabled(): bool
    {
        return Setting::get('razorpay_enabled', '0') === '1' &&
               !empty(Setting::get('razorpay_key_id', '')) &&
               !empty(Setting::get('razorpay_key_secret', ''));
    }

    public function createOrder(float $amount, string $currency = 'INR', array $metadata = []): array
    {
        if (!$this->isEnabled()) {
            throw new \Exception('Razorpay payment gateway is not enabled or not configured properly.');
        }

        if (!$this->api) {
            throw new \Exception('Razorpay API not initialized. Please check your configuration.');
        }

        try {
            $orderData = [
                'receipt' => 'rcpt_' . time(),
                'amount' => $amount * 100, // Amount in paisa
                'currency' => $currency,
                'payment_capture' => 1, // Auto capture
            ];

            $razorpayOrder = $this->api->order->create($orderData);

            return [
                'order_id' => $razorpayOrder->id,
                'amount' => $razorpayOrder->amount,
                'currency' => $razorpayOrder->currency,
                'status' => $razorpayOrder->status,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Razorpay order: ' . $e->getMessage());
        }
    }

    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool
    {
        try {
            $attributes = [
                'razorpay_order_id' => $orderId,
                'razorpay_payment_id' => $paymentId,
                'razorpay_signature' => $signature
            ];

            $this->api->utility->verifyPaymentSignature($attributes);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function fetchPayment(string $paymentId): array
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);
            return [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'currency' => $payment->currency,
                'status' => $payment->status,
                'order_id' => $payment->order_id,
                'method' => $payment->method,
            ];
        } catch (\Exception $e) {
            throw new \Exception('Failed to fetch payment: ' . $e->getMessage());
        }
    }

    public function getGatewayName(): string
    {
        return 'razorpay';
    }
}