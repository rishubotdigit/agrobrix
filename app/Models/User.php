<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'password',
        'otp_code',
        'otp_expiry',
        'last_otp_resend_at',
        'otp_resend_count',
        'otp_session_id',
        'verified_at',
        'role',
        'plan_id',
        'profile_photo',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'otp_expiry' => 'datetime',
            'last_otp_resend_at' => 'datetime',
            'verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isOtpExpired(): bool
    {
        return $this->otp_expiry && $this->otp_expiry->isPast();
    }

    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    public function isVerified(): bool
    {
        return !is_null($this->verified_at);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function userAddons()
    {
        return $this->hasMany(UserAddon::class);
    }

    public function activeAddons()
    {
        return $this->userAddons()->where('active', true)->where(function ($query) {
            $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
        });
    }

    public function properties()
    {
        return $this->hasMany(Property::class, 'owner_id');
    }

    public function viewedContacts()
    {
        return $this->hasMany(ViewedContact::class, 'buyer_id');
    }

    public function planPurchases()
    {
        return $this->hasMany(PlanPurchase::class);
    }

    public function activePlanPurchase()
    {
        return $this->planPurchases()->where('status', 'activated')->where('expires_at', '>', now())->first();
    }

    public function canFeatureProperty()
    {
        $activePurchases = $this->activePlanPurchases();
        $maxFeatured = $this->getCombinedCapabilities()['max_featured_listings'] ?? 0;
        $totalUsed = $activePurchases->sum('used_featured_listings');
        return $activePurchases->isNotEmpty() && $totalUsed < $maxFeatured;
    }

    public function getCombinedCapabilities()
    {
        $capabilities = [];
        foreach ($this->activePlanPurchases() as $purchase) {
            if ($purchase->plan->capabilities) {
                $capabilities = array_merge($capabilities, $purchase->plan->capabilities);
            }
        }
        foreach ($this->activeAddons()->with('addon')->get() as $userAddon) {
            if ($userAddon->addon->capabilities) {
                $capabilities = array_merge($capabilities, $userAddon->addon->capabilities);
            }
        }
        return $capabilities;
    }

    public function activePlanPurchases()
    {
        return $this->planPurchases()->where('status', 'activated')->where('expires_at', '>', now())->get();
    }

}
