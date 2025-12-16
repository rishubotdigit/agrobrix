<?php

namespace App\Events;

use App\Models\Property;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PropertyApproved
{
    use Dispatchable, SerializesModels;

    public Property $property;
    public int $admin_id;

    /**
     * Create a new event instance.
     */
    public function __construct(Property $property, int $admin_id)
    {
        $this->property = $property;
        $this->admin_id = $admin_id;
    }
}
