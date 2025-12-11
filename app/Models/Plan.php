<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'capabilities',
        'validity_period_days',
    ];

    protected function casts(): array
    {
        return [
            'capabilities' => 'array',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function planPurchases()
    {
        return $this->hasMany(PlanPurchase::class);
    }

    public function getMaxFeaturedListings()
    {
        return $this->capabilities['max_featured_listings'] ?? 0;
    }

    public function getFeaturedDurationDays()
    {
        return $this->capabilities['featured_duration_days'] ?? 0;
    }
}
