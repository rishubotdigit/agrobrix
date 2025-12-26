<?php

namespace App\Events;

use App\Models\Lead;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InquiryCreated
{
    use Dispatchable, SerializesModels;

    public Lead $lead;

    /**
     * Create a new event instance.
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }
}