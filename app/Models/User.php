<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $guarded = [];

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
            'password' => 'hashed',
            'is_bookmarked' => 'boolean',
        ];
    }
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->referral_code)) {
                $user->referral_code = self::generateReferralCode();
            }
        });
    }

    public static function generateReferralCode()
    {
        do {
            // Example: 8-character alphanumeric code
            $code = strtoupper(Str::random(6));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }

    /**
     * Scope for customer users (including multi).
     */
    public function scopeCustomers($query)
    {
        return $query->whereIn('user_type', ['customer', 'multi']);
    }

    /**
     * Scope for provider users (including multi).
     */
    public function scopeProviders($query)
    {
        return $query->whereIn('user_type', ['provider', 'multi']);
    }
    public function workingHours()
    {
        return $this->hasMany(ProviderWorkingHour::class, 'user_id');
    }
    public function providerProfileId(): HasOne
    {
        return $this->hasOne(ProviderProfile::class);
    }

    public function providerProfile(): HasOne
    {
        return $this->hasOne(ProviderProfile::class);
    }

    public function providerServices(): HasMany
    {
        return $this->hasMany(ProviderService::class);
    }

    public function providerCertificates(): HasMany
    {
        return $this->hasMany(ProviderCertificate::class);
    }

    public function providerServiceMedia(): HasMany
    {
        return $this->hasMany(ProviderServiceMedia::class);
    }

    /**
     * Get the bookmarks for the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */

    public function bookmarks()
    {
        return $this->hasMany(Bookmark::class);
    }

    // ✅ Outgoing bookmarks (customer → providers)
    public function myBookmarks()
    {
        return $this->hasMany(Bookmark::class, 'user_id');  // who I bookmarked
    }

    public function bookmarkedBy()
    {
        return $this->hasMany(Bookmark::class, 'provider_id'); // who bookmarked me
    }

    public function bookmarkedProviders()
    {
        return $this->belongsToMany(User::class, 'bookmarks', 'user_id', 'provider_id');
    }

    // public function bookings()
    // {
    //     return $this->hasMany(Booking::class, 'provider_id');
    // }

    // public function reviews()
    // {
    //     return $this->hasMany(Review::class, 'provider_id');
    // }


}
