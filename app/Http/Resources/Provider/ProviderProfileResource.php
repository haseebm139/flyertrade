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
            'id'            => $this->id ?? null,
            'user_id'       => $this->user_id ?? null,
            'about_me'      => $this->about_me ?? null,
            'profile_photo' => $this->profile_photo ?? null,
            'country'       => $this->country ?? null,
            'city'          => $this->city ?? null,
            'state'          => $this->state ?? null,
            'zip'           => $this->zip ?? null,
            'office_address' => $this->office_address ?? null,
            'id_photo'      => $this->id_photo ?? null,
            'passport'      => $this->passport ?? null,
            'work_permit'   => $this->work_permit ?? null,
            'id_photo_status' => $this->id_photo_status ?? null,
            'passport_status' => $this->passport_status ?? null,
            'work_permit_status' => $this->work_permit_status ?? null,
            'availability_status' => $this->availability_status ?? null,
            'is_completed'  => $this->is_completed ?? false, 
            'services_count' => $this->when(isset($this->services), fn() => $this->services->count(), 0),
            'services' => ServiceResource::collection($this->whenLoaded('services')),
        ];
    }
}
