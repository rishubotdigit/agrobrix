<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyVersion extends Model
{
    protected $fillable = ['property_id', 'version', 'data', 'status'];

    protected $casts = [
        'data' => 'array',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
