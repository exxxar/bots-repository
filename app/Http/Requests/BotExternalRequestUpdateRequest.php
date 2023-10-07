<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotExternalRequestUpdateRequest extends FormRequest
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
            'command' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable'],
        ];
    }
}
