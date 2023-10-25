<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotMediaStoreRequest extends FormRequest
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
            'rating' => ['required', 'integer'],
            'file_id' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:255'],
        ];
    }
}
