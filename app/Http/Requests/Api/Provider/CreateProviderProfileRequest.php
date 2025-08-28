<?php

namespace App\Http\Requests\Api\Provider;

use Illuminate\Foundation\Http\FormRequest;

class CreateProviderProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Profile


            'avatar'         => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'country'        => 'nullable|string|max:100',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'zip'            => 'nullable|string|max:20',
            'office_address' => 'nullable|string|max:255',
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',

            // Services
            'services'                   => 'nullable|array',
            'services.about'          => 'nullable|string|max:1000',
            'services.service_id'      => 'required|exists:services,id',
            'services.title'   => 'nullable|string|max:255',
            'services.description' => 'nullable|string',
            'services.staff_count' => 'nullable|integer|min:1',
            'services.rate_min'        => 'nullable|numeric|min:0',
            'services.rate_max'        => 'nullable|numeric|min:0',
            'services.is_primary'      => 'boolean',

            // Media
            'services.photos.*'        => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'services.videos.*'        => 'nullable|file|mimes:mp4,mov,avi|max:10240',

            // Certificates
            'services.certificates.*'        => 'nullable|file|mimes:jpg,jpeg,png|max:5120',

            // Document
            'id_photo'                   => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'passport'                   => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'work_permit'                => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
