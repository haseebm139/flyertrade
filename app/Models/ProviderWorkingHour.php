<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Auth;
class ProviderWorkingHour extends Model
{
    protected $guarded = [];


    public static function seedDefaultHours($userId,$provider_profile_id)
    {
        $days = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];

        foreach ($days as $day) {
            static::firstOrCreate(
                ['user_id' => $userId, 'day' => $day],
                [
                    'provider_profile_id' => $provider_profile_id,  
                    'start_time' => null,
                    'end_time'   => null,
                    'is_active'  => false,
                ]
            );
        }
    }
    public function profile(): BelongsTo
    {
        return $this->belongsTo(ProviderProfile::class, 'provider_profile_id');
    }
}
