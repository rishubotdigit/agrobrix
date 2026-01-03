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
        'profile_photo',
        'address',
        'google_id',
        'facebook_id',
        'deletion_requested_at',
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
            'deletion_requested_at' => 'datetime',
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

    public function isPendingDeletion(): bool
    {
        return !is_null($this->deletion_requested_at);
    }

    public function requestDeletion(): void
    {
        $this->update(['deletion_requested_at' => now()]);
    }

    public function cancelDeletionRequest(): void
    {
        $this->update(['deletion_requested_at' => null]);
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
        return $this->planPurchases()->where('status', 'activated')->where('expires_at', '>', now())->orderBy('activated_at', 'desc')->first();
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
        $sources = [];

        // Collect capabilities from active plan purchases
        foreach ($this->activePlanPurchases() as $purchase) {
            if ($purchase->plan && $purchase->plan->capabilities) {
                $sources[] = $purchase->plan->capabilities;
            }
        }

        // Collect capabilities from active addons
        foreach ($this->activeAddons()->with('addon')->get() as $userAddon) {
            if ($userAddon->addon->capabilities) {
                $sources[] = $userAddon->addon->capabilities;
            }
        }

        \Illuminate\Support\Facades\Log::info('Calculating combined capabilities', [
            'user_id' => $this->id,
            'active_plan_purchases_count' => $this->activePlanPurchases()->count(),
            'active_addons_count' => $this->activeAddons()->count(),
            'sources_count' => count($sources)
        ]);

        // Merge capabilities properly
        foreach ($sources as $source) {
            foreach ($source as $key => $value) {
                if (isset($capabilities[$key])) {
                    if (is_numeric($capabilities[$key]) && is_numeric($value)) {
                        $capabilities[$key] += $value;
                    } elseif (is_bool($capabilities[$key]) && is_bool($value)) {
                        $capabilities[$key] = $capabilities[$key] || $value;
                    } else {
                        $capabilities[$key] = $value; // overwrite for others
                    }
                } else {
                    $capabilities[$key] = $value;
                }
            }
        }

        \Illuminate\Support\Facades\Log::info('Combined capabilities calculated', [
            'user_id' => $this->id,
            'capabilities' => $capabilities
        ]);

        return $capabilities;
    }

    public function activePlanPurchases()
    {
        // Log all plan purchases for debugging
        $allPurchases = $this->planPurchases()->get();
        \Illuminate\Support\Facades\Log::info('All plan purchases for user', [
            'user_id' => $this->id,
            'count' => $allPurchases->count(),
            'purchases' => $allPurchases->map(function($p) {
                return [
                    'id' => $p->id,
                    'plan_id' => $p->plan_id,
                    'status' => $p->status,
                    'expires_at' => $p->expires_at,
                    'used_contacts' => $p->used_contacts
                ];
            })
        ]);

        $purchases = $this->planPurchases()->where('status', 'activated')->where(function($q) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->get();
        \Illuminate\Support\Facades\Log::info('Active plan purchases for user (fixed query)', [
            'user_id' => $this->id,
            'count' => $purchases->count(),
            'purchases' => $purchases->map(function($p) {
                return [
                    'id' => $p->id,
                    'plan_id' => $p->plan_id,
                    'status' => $p->status,
                    'expires_at' => $p->expires_at,
                    'used_contacts' => $p->used_contacts
                ];
            })
        ]);
        return $purchases;
    }

}
