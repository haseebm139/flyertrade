<?php

namespace App\Http\Resources\Shared;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Provider\ProviderProfileResource;
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'email'         => $this->email,
            'avatar'        => $this->avatar,
            'phone'         => $this->phone,
            'role_id'       => $this->role_id,
            'user_type'     => $this->user_type,
            'is_verified'   => $this->is_verified,
            'country'       => $this->country,
            'city'          => $this->city,
            'state'         => $this->state,
            'zip'           => $this->zip,
            'address'       => $this->address,
            'latitude'      => $this->latitude,
            'longitude'     => $this->longitude,
            'google_id'     => $this->google_id,
            'facebook_id'   => $this->facebook_id,
            'apple_id'      => $this->apple_id,
            'otp'           => $this->otp,
            'is_guest'      => $this->is_guest,
            'otp'           => $this->otp,
            'is_guest'           => $this->is_guest,
            'fcm_token'           => $this->fcm_token,
            'is_booking_notification'           => $this->is_booking_notification,
            'is_promo_option_notification'           => $this->is_promo_option_notification, 
            'profile'  => new ProviderProfileResource($this->whenLoaded('providerProfile')) ,
        ];
    }
}
