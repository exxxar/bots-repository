<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentStoreRequest extends FormRequest
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
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'bot_user_id' => ['required', 'integer', 'exists:bot_users,id'],
            'appointment_schedule_id' => ['required', 'integer', 'exists:appointment_schedules,id'],
            'status' => ['required', 'integer'],
        ];
    }
}
