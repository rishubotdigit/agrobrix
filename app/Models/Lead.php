<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected static function booted()
    {
        static::deleting(function ($lead) {
            $lead->visits()->delete();
            $lead->followUps()->delete();
        });
    }
    protected $fillable = [
        'property_id',
        'agent_id',
        'buyer_name',
        'buyer_email',
        'buyer_phone',
        'status',
        'buying_purpose',
        'buying_timeline',
        'interested_in_site_visit',
        'additional_message',
        'buyer_type'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    public function followUps()
    {
        return $this->hasMany(FollowUp::class);
    }
}
