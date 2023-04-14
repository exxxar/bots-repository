<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotUpdateRequest extends FormRequest
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
            'company_id' => ['required', 'integer', 'exists:companies,id'],
            'bot_domain' => ['required', 'string', 'max:190', 'unique:bots,bot_domain'],
            'bot_token' => ['nullable', 'string', 'max:255'],
            'bot_token_dev' => ['nullable', 'string', 'max:255'],
            'order_channel' => ['nullable', 'string', 'max:255'],
            'balance' => ['required', 'numeric'],
            'tax_per_day' => ['required', 'numeric'],
            'image' => ['nullable', 'json'],
            'description' => ['nullable', 'string'],
            'info_link' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required'],
            'bot_type_id' => ['required', 'integer', 'exists:bot_types,id'],
            'level_1' => ['nullable', 'numeric'],
            'level_2' => ['nullable', 'numeric'],
            'level_3' => ['nullable', 'numeric'],
            'blocked_message' => ['nullable', 'string'],
            'blocked_at' => ['nullable'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
