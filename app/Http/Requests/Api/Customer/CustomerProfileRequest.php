<?php

namespace App\Http\Requests\Api\Customer;

use Illuminate\Foundation\Http\FormRequest;

class CustomerProfileRequest extends FormRequest
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
            'name'           => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255|unique:users,email,' . auth()->id(),
            'avatar'         => 'nullable|file|mimes:jpg,jpeg,png|max:51200',
            'cover_photo'    => 'nullable|file|mimes:jpg,jpeg,png|max:51200',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:255',
            'country'        => 'nullable|string|max:100',
            'city'           => 'nullable|string|max:100',
            'state'          => 'nullable|string|max:100',
            'zip'            => 'nullable|string|max:20', 
            'latitude'       => 'nullable|numeric',
            'longitude'      => 'nullable|numeric',
            'fcm_token'      => 'nullable|string',
        ];
    }
}

