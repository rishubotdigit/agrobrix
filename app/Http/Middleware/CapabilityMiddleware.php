<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CapabilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $capability, int $requiredValue = 1): Response
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Check if user has an active plan purchase
        if (!$this->hasActivePlan($user)) {
            return response()->json([
                'error' => 'No active plan found. Please purchase or renew a plan to access this feature.',
                'plan_required' => true
            ], 403);
        }

        if (!$this->hasCapability($user, $capability, $requiredValue)) {
            return response()->json([
                'error' => 'Capability limit exceeded',
                'capability' => $capability,
                'required' => $requiredValue,
                'current' => $this->getCurrentCapabilityValue($user, $capability)
            ], 403);
        }

        return $next($request);
    }

    private function hasCapability($user, string $capability, int $requiredValue): bool
    {
        $currentValue = $this->getCurrentCapabilityValue($user, $capability);
        return $currentValue >= $requiredValue;
    }

    private function getCurrentCapabilityValue($user, string $capability): int
    {
        $totalCapabilities = $user->getCombinedCapabilities();

        // Get the capability value (for additive capabilities like max_listings)
        return $totalCapabilities[$capability] ?? 0;
    }

    private function hasActivePlan($user): bool
    {
        return $user->activePlanPurchases()->isNotEmpty();
    }
}
