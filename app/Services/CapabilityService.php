<?php

namespace App\Services;

use App\Traits\CapabilityTrait;

class CapabilityService
{
    use CapabilityTrait;

    public static function getValue($user, string $capability): int
    {
        return self::getCapabilityValueStatic($user, $capability);
    }
}