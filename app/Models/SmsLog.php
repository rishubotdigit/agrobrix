<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
    protected $fillable = [
        'user_id',
        'mobile',
        'message',
        'template_slug',
        'gateway',
        'status',
        'response',
        'error',
        'type',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    /**
     * Get the user that owns the SMS log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to filter by status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope to filter by gateway
     */
    public function scopeGateway($query, $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    /**
     * Scope to filter by type
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
