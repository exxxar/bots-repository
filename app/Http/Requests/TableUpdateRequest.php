<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TableUpdateRequest extends FormRequest
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
            'bot_id' => ['nullable', 'integer', 'exists:bots,id'],
            'creator_id' => ['nullable', 'integer', 'exists:creators,id'],
            'officiant_id' => ['nullable', 'integer', 'exists:officiants,id'],
            'number' => ['nullable', 'string', 'max:255'],
            'closed_at' => ['nullable'],
            'config' => ['nullable', 'json'],
            'bot_user_id' => ['required', 'integer', 'exists:bot_users,id'],
        ];
    }
}
