<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyReport extends Model
{
    protected $fillable = ['property_id', 'user_id', 'reason', 'details'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
