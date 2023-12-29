<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentScheduleUpdateRequest extends FormRequest
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
            'start_time' => ['nullable', 'string', 'max:5'],
            'end_time' => ['nullable', 'string', 'max:5'],
            'day' => ['required', 'string'],
        ];
    }
}
