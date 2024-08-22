<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IikoStoreRequest extends FormRequest
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
            'api_login' => ['nullable', 'string', 'max:255'],
            'organization_id' => ['nullable', 'string', 'max:255'],
            'terminal_group_id' => ['nullable', 'string', 'max:255'],
        ];
    }
}
