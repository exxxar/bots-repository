<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ActionStatusUpdateRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'script' => ['required', 'string', 'max:255'],
            'max_attempts' => ['required', 'integer'],
            'current_attempts' => ['required', 'integer'],
            'completed_at' => ['nullable'],
            'data' => ['nullable', 'json'],
        ];
    }
}
