<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class Property extends Model
{
    protected static function booted()
    {
        static::deleting(function ($property) {
            // Delete related records before deleting the property
            $property->amenities()->detach();
            $property->viewedContacts()->delete();
            $property->versions()->delete();
            $property->payments()->delete();
            $property->leads()->delete();
        });
    }
    protected $fillable = [
        'title', 'land_type', 'city_id', 'description', 'area', 'full_address',
        'google_map_lat', 'google_map_lng', 'plot_area', 'plot_area_unit', 'frontage',
        'road_width', 'corner_plot', 'gated_community',
        'price', 'price_negotiable', 'contact_name', 'contact_mobile', 'contact_role',
        'property_images', 'property_video', 'status', 'owner_id', 'agent_id', 'featured', 'featured_until'
    ];

    protected $casts = [
        'google_map_lat' => 'decimal:8',
        'google_map_lng' => 'decimal:8',
        'plot_area' => 'decimal:2',
        'frontage' => 'decimal:2',
        'road_width' => 'decimal:2',
        'price' => 'decimal:2',
        'corner_plot' => 'boolean',
        'gated_community' => 'boolean',
        'price_negotiable' => 'boolean',
        'property_images' => 'array',
        'featured' => 'boolean',
        'featured_until' => 'datetime',
    ];

    public function getLatitudeAttribute()
    {
        return $this->google_map_lat;
    }

    public function getLongitudeAttribute()
    {
        return $this->google_map_lng;
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function versions()
    {
        return $this->hasMany(PropertyVersion::class);
    }

    public function viewedContacts()
    {
        return $this->hasMany(ViewedContact::class);
    }

    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'property_amenities');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function leads()
    {
        return $this->hasMany(Lead::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true)->where('featured_until', '>', now());
    }

    public function createVersion($data = null)
    {
        if (!$data) {
            $data = $this->toArray();
        }
        $latestVersion = $this->versions()->max('version') ?? 0;
        return $this->versions()->create([
            'version' => $latestVersion + 1,
            'data' => $data,
        ]);
    }
}
