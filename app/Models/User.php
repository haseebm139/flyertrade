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
use App\Models\UserPaymentMethod;

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

    public function paymentMethods()
    {
        return $this->hasMany(UserPaymentMethod::class);
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

    /**
     * Get bookings where user is the provider
     */
    public function providerBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'provider_id');
    }

    /**
     * Get bookings where user is the customer
     */
    public function customerBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'customer_id');
    }

    /**
     * Get reviews written by this user (as a customer)
     */
    public function reviewsWritten(): HasMany
    {
        return $this->hasMany(Review::class, 'sender_id');
    }

    /**
     * Get reviews received by this user (as a provider)
     */
    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'receiver_id');
    }

    /**
     * Get published reviews received by this user (as a provider)
     */
    public function publishedReviews(): HasMany
    {
        return $this->hasMany(Review::class, 'receiver_id')
            ->where('status', 'published');
    }

    /**
     * Get the overall rating attribute (average of published reviews)
     *
     * @return float
     */
    public function getOverallRatingAttribute(): float
    {
        // If already loaded via withAvg, use that value
        if (isset($this->attributes['published_reviews_avg_rating'])) {
            $rating = $this->attributes['published_reviews_avg_rating'];
            return round((float) $rating, 2);
            // return round((float) $this->attributes['published_reviews_avg_rating'], 2);
        }
        
        // Otherwise calculate on the fly
        $rating = $this->publishedReviews()->avg('rating') ?? 0;
        return round((float) $rating, 2);
    }

    /**
     * Get the total bookings attribute (as provider)
     *
     * @return int
     */
    public function getTotalBookingsAttribute(): int
    {
        // If already loaded via withCount, use that value
        if (isset($this->attributes['provider_bookings_count'])) {
            return (int) $this->attributes['provider_bookings_count'];
        }
        
        // Otherwise calculate on the fly
        return $this->providerBookings()->count();
    }

    /**
     * Get the services offered attribute
     *
     * @return int
     */
    public function getServicesOfferedAttribute(): int
    {
        // If already loaded via withCount, use that value
        if (isset($this->attributes['provider_services_count'])) {
            return (int) $this->attributes['provider_services_count'];
        }
        
        // Otherwise calculate on the fly
        return $this->providerServices()->count();
    }

    /**
     * Get the total reviews attribute (published reviews count)
     *
     * @return int
     */
    public function getTotalReviewsAttribute(): int
    {
        // If already loaded via withCount, use that value
        if (isset($this->attributes['published_reviews_count'])) {
            return (int) $this->attributes['published_reviews_count'];
        }
        
        // Otherwise calculate on the fly
        return $this->publishedReviews()->count();
    }

}
