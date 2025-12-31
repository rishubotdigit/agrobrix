<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'template_id',
        'gateway',
        'description',
        'variables',
        'is_active',
    ];

    protected $casts = [
        'variables' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Scope to get only active templates
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by gateway
     */
    public function scopeGateway($query, $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    /**
     * Get template by slug
     */
    public static function getBySlug($slug, $gateway = null)
    {
        $query = self::where('slug', $slug)->where('is_active', true);
        
        if ($gateway) {
            $query->where('gateway', $gateway);
        }
        
        return $query->first();
    }

    /**
     * Get all template types as options
     */
    public static function getTemplateTypes()
    {
        return [
            'otp' => 'OTP Verification',
            'registration' => 'Registration Welcome',
            'forgot_password' => 'Forgot Password',
            'password_reset' => 'Password Reset Success',
            'property_approved' => 'Property Approved',
            'property_rejected' => 'Property Rejected',
            'plan_activated' => 'Plan Activated',
            'plan_expired' => 'Plan Expired',
        ];
    }
}
