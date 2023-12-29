<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AppointmentEventStoreRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'json'],
            'is_group' => ['required'],
            'max_people' => ['nullable', 'integer'],
            'mix_people' => ['nullable', 'integer'],
            'on_start_appointment' => ['nullable', 'string'],
            'on_cancel_appointment' => ['nullable', 'string'],
            'on_after_appointment' => ['nullable', 'string'],
            'on_repeat_appointment' => ['nullable', 'string'],
        ];
    }
}
