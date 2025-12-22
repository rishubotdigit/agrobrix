<?php

namespace App\Traits;

trait CapabilityTrait
{
    /**
     * Get the total capability value for a user from their plan and addons
     */
    protected function getCapabilityValue($user, string $capability): int
    {
        return self::getCapabilityValueStatic($user, $capability);
    }

    /**
     * Static version for use in views or other contexts
     */
    public static function getCapabilityValueStatic($user, string $capability): int
    {
        $totalCapabilities = $user->getCombinedCapabilities();

        // Get the capability value
        $value = $totalCapabilities[$capability] ?? 0;
        \Illuminate\Support\Facades\Log::info('Capability value retrieved', [
            'user_id' => $user->id,
            'capability' => $capability,
            'value' => $value
        ]);
        return $value;
    }
}