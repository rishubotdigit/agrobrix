<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name', 'code', 'image', 'icon'];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}