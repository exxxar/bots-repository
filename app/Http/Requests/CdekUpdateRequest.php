<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CdekUpdateRequest extends FormRequest
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
            'account' => ['nullable', 'string', 'max:255'],
            'secure_password' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required'],
            'config' => ['nullable', 'json'],
        ];
    }
}
