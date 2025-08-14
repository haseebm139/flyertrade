<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
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
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'code'                  => 'required|string|max:5',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required'        => 'Email is required.',
            'email.email'           => 'Please enter a valid email address.',
            'email.exists'          => 'This email address is not registered.',
            'password.required'     => 'Password is required.',
            'password.min'          => 'Password must be at least 8 characters.',
            'password.confirmed'    => 'Password confirmation does not match.',
            'code.required'         => 'Verification code is required.',
        ];
    }
}
