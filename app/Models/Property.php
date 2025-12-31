<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $fillable = [
        'title', 'slug', 'land_type', 'description', 'state', 'district_id', 'area', 'full_address',
        'google_map_lat', 'google_map_lng', 'plot_area', 'plot_area_unit', 'frontage',
        'road_width', 'corner_plot', 'gated_community',
        'price', 'price_negotiable', 'contact_name', 'contact_mobile',
        'property_images', 'property_video', 'status', 'owner_id', 'agent_id', 'featured', 'featured_until',
        'meta_title', 'meta_description', 'meta_keywords'
    ];

    protected $casts = [
        'google_map_lat' => 'decimal:8',
        'google_map_lng' => 'decimal:8',
        'plot_area' => 'float',
        'frontage' => 'float',
        'road_width' => 'float',
        'price' => 'float',
        'corner_plot' => 'boolean',
        'gated_community' => 'boolean',
        'price_negotiable' => 'boolean',
        'property_images' => 'array',
        'featured' => 'boolean',
        'featured_until' => 'datetime',
        'district_id' => 'integer',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected static function booted()
    {
        static::creating(function ($property) {
            if (empty($property->slug)) {
                $property->slug = static::generateUniqueSlug($property->title);
            }
        });

        static::updating(function ($property) {
            if ($property->isDirty('title') && empty($property->slug)) {
                 $property->slug = static::generateUniqueSlug($property->title);
            }
        });

        static::deleting(function ($property) {
            // Delete related records before deleting the property
            $property->amenities()->detach();
            $property->viewedContacts()->delete();
            $property->versions()->delete();
            $property->payments()->delete();
            $property->leads()->delete();
        });
    }

    public static function generateUniqueSlug($title)
    {
        $slug = \Illuminate\Support\Str::slug($title);
        $originalSlug = $slug;
        $count = 2;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

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

    public function district()
    {
        return $this->belongsTo(District::class);
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
