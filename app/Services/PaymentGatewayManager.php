<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;

class PaymentGatewayManager
{
    protected $gateways = [];
    protected $defaultGateway;

    public function __construct()
    {
        $this->defaultGateway = Setting::get('default_gateway', 'razorpay');
        $this->registerGateway('razorpay', new RazorpayGateway());
        $this->registerGateway('phonepe', new PhonePeGateway());
        $this->registerGateway('upi_static', new UpiStaticUrlGateway());
    }

    public function registerGateway(string $name, PaymentGatewayInterface $gateway): void
    {
        $this->gateways[$name] = $gateway;
    }

    public function getGateway(?string $name = null): PaymentGatewayInterface
    {
        $gatewayName = $name ?: $this->defaultGateway;

        if (!isset($this->gateways[$gatewayName])) {
            Log::warning("Payment gateway '{$gatewayName}' not found, falling back to default");
            $gatewayName = $this->defaultGateway;
        }

        return $this->gateways[$gatewayName];
    }

    public function getAvailableGateways(): array
    {
        $available = [];
        foreach ($this->gateways as $name => $gateway) {
            if ($gateway->isEnabled()) {
                $available[] = $name;
            }
        }
        return $available;
    }

    public function setDefaultGateway(string $gateway): void
    {
        if (isset($this->gateways[$gateway])) {
            $this->defaultGateway = $gateway;
        } else {
            Log::warning("Cannot set default gateway to '{$gateway}', gateway not registered");
        }
    }

    public function getDefaultGateway(): string
    {
        return Setting::get('default_gateway', 'razorpay');
    }
}