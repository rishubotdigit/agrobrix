<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'type', 'message', 'data', 'seen'];

    protected $casts = [
        'data' => 'array',
        'seen' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
