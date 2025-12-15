<?php

namespace App\Events;

use App\Models\Property;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PropertySubmittedForApproval
{
    use Dispatchable, SerializesModels;

    public Property $property;

    /**
     * Create a new event instance.
     */
    public function __construct(Property $property)
    {
        $this->property = $property;
    }
}