<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotUserStoreRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'parent_id' => ['nullable', 'integer', 'exists:parents,id'],
            'is_admin' => ['required'],
            'is_work' => ['required'],
            'user_in_location' => ['required'],
        ];
    }
}
