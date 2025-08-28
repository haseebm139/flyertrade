<?php

namespace App\Http\Resources\Provider;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Provider\MediaResource;
use App\Http\Resources\Provider\CertificateResource;
class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'user_id'        => $this->user_id,
            'service_id'        => $this->service_id,
            'service_name' => $this->service ? $this->service->name : null, // ✅ get service name
            'provider_profile_id'        => $this->provider_profile_id,
            'is_primary'        => $this->is_primary,
            'show_certificate'        => $this->show_certificate,
            'title'        => $this->title,
            'about' => $this->about,
            'description' => $this->description,
            'staff_count' => $this->staff_count,
            'rate_min' => $this->rate_min,
            'rate_max' => $this->rate_max,
            'certificates'=> CertificateResource::collection($this->whenLoaded('certificates')),
            'media'       => MediaResource::collection($this->whenLoaded('media')),
        ];
    }
}
