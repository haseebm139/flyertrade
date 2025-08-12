<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
            'role'     => 'required|in:customer,provider',
            'latitude'  => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'country'   => ['nullable', 'string', 'max:100'],
            'city'      => ['nullable', 'string', 'max:100'],
            'state'     => ['nullable', 'string', 'max:100'],
            'zip'       => ['nullable', 'string', 'max:20'],
            'address'   => ['nullable', 'string', 'max:255'],
        ];
    }

    public function messages()
    {
        return [
            'name.required'      => 'The name field is required.',
            'name.string'        => 'The name must be a valid string.',
            'email.required'     => 'The email field is required.',
            'email.email'        => 'Please enter a valid email address.',
            'email.unique'       => 'This email address is already taken.',
            'password.required'  => 'The password field is required.',
            'password.min'       => 'Password must be at least 8 characters.',
            'role.required'      => 'The role field is required.',
            'role.in'            => 'The role must be either customer, provider.',
            'latitude.numeric'   => 'Latitude must be a valid number.',
            'latitude.between'   => 'Latitude must be between -90 and 90.',
            'longitude.numeric'  => 'Longitude must be a valid number.',
            'longitude.between'  => 'Longitude must be between -180 and 180.',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => [],
            ], 422)
        );

    }
}
