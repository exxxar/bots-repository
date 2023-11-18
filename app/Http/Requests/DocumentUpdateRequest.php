<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentUpdateRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'file_id' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'integer'],
            'params' => ['nullable', 'json'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'bot_user_id' => ['required', 'integer', 'exists:bot_users,id'],
            'verified_at' => ['nullable'],
        ];
    }
}
