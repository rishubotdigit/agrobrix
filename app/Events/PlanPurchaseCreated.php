<?php

namespace App\Events;

use App\Models\PlanPurchase;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlanPurchaseCreated
{
    use Dispatchable, SerializesModels;

    public PlanPurchase $planPurchase;

    /**
     * Create a new event instance.
     */
    public function __construct(PlanPurchase $planPurchase)
    {
        $this->planPurchase = $planPurchase;
    }
}
