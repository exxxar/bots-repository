<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FrontPadUpdateRequest extends FormRequest
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
            'hook_url' => ['nullable', 'string', 'max:255'],
            'channel' => ['nullable', 'string', 'max:255'],
            'affiliate' => ['nullable', 'string', 'max:255'],
            'point' => ['nullable', 'string', 'max:255'],
            'token' => ['nullable', 'string', 'max:255'],
        ];
    }
}
