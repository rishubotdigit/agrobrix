<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyAnalytics extends Model
{
    protected $fillable = [
        'property_id',
        'total_clicks',
        'total_contact_views',
        'total_saves',
    ];

    protected $casts = [
        'total_clicks' => 'integer',
        'total_contact_views' => 'integer',
        'total_saves' => 'integer',
    ];

    /**
     * Get the property that owns the analytics.
     */
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    /**
     * Increment the click count for this property.
     */
    public function incrementClicks()
    {
        $this->increment('total_clicks');
    }

    /**
     * Increment the contact view count for this property.
     */
    public function incrementContactViews()
    {
        $this->increment('total_contact_views');
    }

    /**
     * Increment the saves count for this property.
     */
    public function incrementSaves()
    {
        $this->increment('total_saves');
    }

    /**
     * Decrement the saves count for this property (when removed from wishlist).
     */
    public function decrementSaves()
    {
        if ($this->total_saves > 0) {
            $this->decrement('total_saves');
        }
    }
}
