<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChatLogStoreRequest extends FormRequest
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
            'text' => ['nullable', 'string'],
            'media_content' => ['nullable', 'json'],
            'content_type' => ['required', 'integer'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'form_bot_user_id' => ['required', 'integer', 'exists:form_bot_users,id'],
            'to_bot_user_id' => ['required', 'integer', 'exists:to_bot_users,id'],
        ];
    }
}
