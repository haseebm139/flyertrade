<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SocialRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'social_id' => 'required',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email',
            'password'  => 'nullable|string|min:8',
            'role'      => 'nullable|in:customer,provider',
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'country'   => ['nullable', 'string', 'max:100'],
            'city'      => ['nullable', 'string', 'max:100'],
            'state'     => ['nullable', 'string', 'max:100'],
            'zip'       => ['nullable', 'string', 'max:20'],
            'address'   => ['nullable', 'string', 'max:255'],

        ];
    }
}
