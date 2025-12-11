<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PhonePeGateway implements PaymentGatewayInterface
{
    protected $merchantId;
    protected $key;
    protected $keyIndex;
    protected $baseUrl;
    protected $callbackUrl;

    public function __construct()
    {
        $this->merchantId = Setting::get('phonepe_merchant_id', Config::get('services.phonepe.merchant_id', ''));
        $this->key = Setting::get('phonepe_salt_key', Config::get('services.phonepe.salt_key', ''));
        $this->keyIndex = Setting::get('phonepe_salt_index', Config::get('services.phonepe.salt_index', '1'));
        $this->baseUrl = Setting::get('phonepe_base_url', Config::get('services.phonepe.base_url', 'https://api.phonepe.com/apis/hermes'));
        $this->callbackUrl = Setting::get('phonepe_callback_url', Config::get('services.phonepe.callback_url', ''));
    }

    public function isEnabled(): bool
    {
        return Setting::get('phonepe_enabled', '0') === '1' &&
               !empty($this->merchantId) &&
               !empty($this->key) &&
               !empty($this->keyIndex);
    }

    public function createOrder(float $amount, string $currency = 'INR', array $metadata = []): array
    {
        if (!$this->isEnabled()) {
            return [
                'error' => true,
                'message' => 'PhonePe payment gateway is not properly configured. Please contact support or try a different payment method.'
            ];
        }

        try {
            $orderId = 'order_' . time() . '_' . rand(1000, 9999);
            $amountInPaise = (int)($amount * 100);

            $payload = [
                'merchantId' => $this->merchantId,
                'merchantTransactionId' => $orderId,
                'merchantUserId' => $metadata['user_id'] ?? 'user_' . time(),
                'amount' => $amountInPaise,
                'redirectUrl' => $this->callbackUrl,
                'redirectMode' => 'POST',
                'callbackUrl' => $this->callbackUrl,
                'mobileNumber' => $metadata['mobile'] ?? '',
                'paymentInstrument' => [
                    'type' => 'PAY_PAGE'
                ]
            ];

            $payloadJson = json_encode($payload);
            $checksum = $this->generateChecksum($payloadJson, '/pg/v1/pay');

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'X-VERIFY' => $checksum
            ])->post($this->baseUrl . '/pg/v1/pay', $payload);

            if ($response->successful()) {
                $data = $response->json();
                Log::info('PhonePe order created', ['order_id' => $orderId, 'response' => $data]);

                return [
                    'order_id' => $orderId,
                    'amount' => $amountInPaise,
                    'currency' => $currency,
                    'status' => 'created',
                    'payment_url' => $data['data']['instrumentResponse']['redirectInfo']['url'] ?? null,
                ];
            } else {
                Log::error('PhonePe order creation failed', ['response' => $response->body()]);
                return [
                    'error' => true,
                    'message' => 'Failed to create PhonePe order'
                ];
            }
        } catch (\Exception $e) {
            Log::error('PhonePe createOrder error', ['error' => $e->getMessage()]);
            return [
                'error' => true,
                'message' => 'Failed to create PhonePe order: ' . $e->getMessage()
            ];
        }
    }

    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool
    {
        try {
            // For PhonePe, verification is done via status check
            $status = $this->checkPaymentStatus($orderId);
            return $status['success'] && $status['data']['state'] === 'COMPLETED';
        } catch (\Exception $e) {
            Log::error('PhonePe verifyPayment error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function fetchPayment(string $paymentId): array
    {
        try {
            return $this->checkPaymentStatus($paymentId);
        } catch (\Exception $e) {
            Log::error('PhonePe fetchPayment error', ['error' => $e->getMessage()]);
            throw new \Exception('Failed to fetch payment: ' . $e->getMessage());
        }
    }

    protected function checkPaymentStatus(string $transactionId): array
    {
        $endpoint = '/pg/v1/status/' . $this->merchantId . '/' . $transactionId;
        $checksum = $this->generateChecksum('', $endpoint);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-VERIFY' => $checksum,
            'X-MERCHANT-ID' => $this->merchantId
        ])->get($this->baseUrl . $endpoint);

        if ($response->successful()) {
            return $response->json();
        } else {
            throw new \Exception('Failed to check payment status');
        }
    }

    protected function generateChecksum(string $payload, string $endpoint): string
    {
        $data = $payload . $endpoint . $this->key;
        return hash('sha256', $data) . '###' . $this->keyIndex;
    }

    public function getGatewayName(): string
    {
        return 'phonepe';
    }
}