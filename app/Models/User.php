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
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens,HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role_id',
        'user_type',
        'is_verified',
        'country',
        'city',
        'state',
        'zip',
        'address',
        'latitude',
        'longitude',
        'google_id',
        'facebook_id',
        'apple_id',
        'otp',
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
            'password' => 'hashed',
        ];
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

      
}
