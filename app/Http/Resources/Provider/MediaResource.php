<?php

namespace App\Http\Resources\Provider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id'               => $this->user_id,
            'provider_profile_id'   => $this->provider_profile_id, // adjust based on your model
            'provider_service_id'   => $this->provider_service_id,
            'file_path'             => $this->title,
            'type'                  => $this->file_path, 
        ];
    }
}
