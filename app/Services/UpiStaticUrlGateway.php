<?php

namespace App\Services;

use App\Models\Setting;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\Config;

class UpiStaticUrlGateway implements PaymentGatewayInterface
{
    protected $upiId;
    protected $merchantName;

    public function __construct()
    {
        $this->upiId = Setting::get('upi_static_upi_id', Config::get('services.upi_static.upi_id', ''));
        $this->merchantName = Setting::get('upi_static_merchant_name', Config::get('services.upi_static.merchant_name', 'Merchant'));
    }

    public function isEnabled(): bool
    {
        return Setting::get('upi_static_enabled', '0') === '1' &&
               !empty($this->upiId);
    }

    public function createOrder(float $amount, string $currency = 'INR', array $metadata = []): array
    {
        if (!$this->isEnabled()) {
            throw new \Exception('UPI Static URL payment gateway is not enabled or not configured properly.');
        }

        $orderId = 'upi_order_' . time() . '_' . rand(1000, 9999);

        // Generate UPI URL
        $upiUrl = $this->generateUpiUrl($amount, $currency, $orderId, $metadata);

        // Generate QR Code
        $qrCode = $this->generateQrCode($upiUrl);

        return [
            'order_id' => $orderId,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'created',
            'upi_url' => $upiUrl,
            'qr_code' => $qrCode,
        ];
    }

    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool
    {
        // For static UPI, verification happens when transaction ID is submitted
        // Check if payment exists and has transaction ID submitted
        $payment = \App\Models\Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            return false;
        }

        // Payment is verified if transaction ID has been submitted
        return $payment->hasTransactionId() && $payment->transaction_id === $paymentId;
    }

    public function fetchPayment(string $paymentId): array
    {
        // For static UPI, check if payment exists and has transaction ID
        $payment = \App\Models\Payment::where('transaction_id', $paymentId)->first();

        if ($payment) {
            return [
                'id' => $paymentId,
                'amount' => $payment->amount,
                'currency' => 'INR',
                'status' => $payment->status,
                'order_id' => $payment->order_id,
                'method' => 'upi_static',
            ];
        }

        // Return basic information if payment not found
        return [
            'id' => $paymentId,
            'amount' => null,
            'currency' => 'INR',
            'status' => 'unknown',
            'order_id' => null,
            'method' => 'upi_static',
        ];
    }

    public function getGatewayName(): string
    {
        return 'upi_static';
    }

    protected function generateUpiUrl(float $amount, string $currency, string $orderId, array $metadata = []): string
    {
        $params = [
            'pa' => $this->upiId, // Payee address
            'pn' => $this->merchantName, // Payee name
            'am' => number_format($amount, 2, '.', ''), // Amount
            'cu' => $currency, // Currency
            'tn' => 'Payment for order ' . $orderId, // Transaction note
        ];

        // Add optional UPI parameters
        if (!empty($metadata['tn'])) {
            $params['tn'] = $metadata['tn'];
        }

        $queryString = http_build_query($params);

        return 'upi://pay?' . $queryString;
    }

    protected function generateQrCode(string $upiUrl): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(400),
            new SvgImageBackEnd()
        );

        $writer = new Writer($renderer);
        return $writer->writeString($upiUrl);
    }
}