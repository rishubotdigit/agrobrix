<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Addon extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
        'capabilities',
    ];

    protected function casts(): array
    {
        return [
            'capabilities' => 'array',
        ];
    }

    public function userAddons()
    {
        return $this->hasMany(UserAddon::class);
    }
}
