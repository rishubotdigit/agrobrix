<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAddon extends Model
{
    protected $fillable = [
        'user_id',
        'addon_id',
        'purchased_at',
        'expires_at',
        'active',
    ];

    protected function casts(): array
    {
        return [
            'purchased_at' => 'datetime',
            'expires_at' => 'datetime',
            'active' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addon(): BelongsTo
    {
        return $this->belongsTo(Addon::class);
    }

    public function isActive(): bool
    {
        return $this->active && (!$this->expires_at || $this->expires_at->isFuture());
    }
}
