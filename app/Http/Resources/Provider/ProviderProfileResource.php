<?php

namespace App\Http\Resources\Provider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Provider\ServiceResource;
class ProviderProfileResource extends JsonResource
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
            'user_id'       => $this->user_id,
            'about_me'      => $this->about_me,
            'profile_photo'      => $this->profile_photo,
            'country'      => $this->country,
            'city'      => $this->city,
            'state'      => $this->state,
            'zip'      => $this->zip,
            'office_address'      => $this->office_address,
            'id_photo'      => $this->id_photo,
            'passport'      => $this->passport,
            'work_permit'      => $this->work_permit,
            'id_photo_status'      => $this->id_photo_status,
            'passport_status'      => $this->passport_status,
            'work_permit_status'      => $this->work_permit_status,
            'availability_status'      => $this->availability_status,
            'is_completed'      => $this->is_completed, 
            'services_count' => $this->services->count(),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
        ];
    }
}
