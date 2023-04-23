<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotTextContentStoreRequest extends FormRequest
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
            'value' => ['nullable', 'string'],
            'key' => ['required', 'string', 'max:255'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
        ];
    }
}
