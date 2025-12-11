<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = [
        'lead_id',
        'follow_up_date',
        'notes',
        'status',
    ];

    protected $casts = [
        'follow_up_date' => 'datetime',
    ];

    public function lead()
    {
        return $this->belongsTo(Lead::class);
    }
}
