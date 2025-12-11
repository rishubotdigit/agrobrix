<?php

namespace App\Traits;

trait CapabilityTrait
{
    /**
     * Get the total capability value for a user from their plan and addons
     */
    protected function getCapabilityValue($user, string $capability): int
    {
        $totalCapabilities = $user->getCombinedCapabilities();

        // Get the capability value
        return $totalCapabilities[$capability] ?? 0;
    }
}