<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'role',
        'capabilities',
        'original_price',
        'offer_price',
        'discount',
        'contacts_to_unlock',
        'validity_days',
        'features',
        'persona',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'capabilities' => 'array',
            'features' => 'array',
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
    public function scopeForRole($query, $role)
    {
        return $query->where('role', $role);
    }

    public function getValidityDays()
    {
        return $this->validity_days ?? $this->validity_period_days ?? 0;
    }
    
    public function getMaxListings()
    {
        return $this->capabilities['max_listings'] ?? 0;
    }

    public function getMaxContacts()
    {
        return $this->capabilities['max_contacts'] ?? 0;
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
