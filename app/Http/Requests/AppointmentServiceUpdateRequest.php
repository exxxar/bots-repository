<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentServiceUpdateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'appointment_event_id' => ['required', 'integer', 'exists:appointment_events,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'json'],
            'price' => ['required', 'numeric'],
            'discount_price' => ['required', 'numeric'],
            'need_prepayment' => ['required'],
        ];
    }
}
