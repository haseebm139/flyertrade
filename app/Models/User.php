<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

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


    public function providerServices()
    {
        return $this->hasMany(\App\Models\ProviderService::class);
    }

    public function services()
    {
        return $this->belongsToMany(\App\Models\Service::class, 'provider_services')
                    ->withPivot(['id','is_primary','title','description','staff_count','service_photos','service_video','rate_min','rate_max'])
                    ->withTimestamps();
    }
}
