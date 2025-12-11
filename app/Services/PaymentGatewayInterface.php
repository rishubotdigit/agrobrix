<?php

namespace App\Services;

interface PaymentGatewayInterface
{
    /**
     * Check if the gateway is enabled and configured
     */
    public function isEnabled(): bool;

    /**
     * Create an order for payment
     */
    public function createOrder(float $amount, string $currency = 'INR', array $metadata = []): array;

    /**
     * Verify payment signature
     */
    public function verifyPayment(string $orderId, string $paymentId, string $signature): bool;

    /**
     * Fetch payment details
     */
    public function fetchPayment(string $paymentId): array;

    /**
     * Get the gateway name
     */
    public function getGatewayName(): string;
}