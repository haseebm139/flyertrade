<?php

namespace App\Http\Requests\Api\Booking;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreBookingRequest extends FormRequest
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
            'provider_id' => 'required|exists:users,id',
            'service_id' => 'required|exists:services,id',
            'booking_address' => 'required|string|max:255',
            'booking_description' => 'nullable|string',
            'total_price' => 'required|numeric|min:0',
            'service_charges' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3', // e.g. usd
            'payment_method_id' => 'nullable|string', // pm_xxx from Flutter

            // Multiple-day slots:
            'slots' => 'required|array|min:1',
            'slots.*.service_date' => 'required|date_format:Y-m-d',
            'slots.*.start_time' => 'required|date_format:H:i',
            'slots.*.end_time' => 'required|date_format:H:i|after:slots.*.start_time',
        ];
    }

    public function messages(): array
    {
        return [
            'slots.*.end_time.after' => 'End time must be after start time.',
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
