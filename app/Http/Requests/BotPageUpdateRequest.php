<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotPageUpdateRequest extends FormRequest
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
            'bot_menu_slug_id' => ['required', 'integer', 'exists:bot_menu_slugs,id'],
            'content' => ['nullable', 'string'],
            'images' => ['nullable', 'json'],
            'reply_keyboard_id' => ['nullable', 'integer', 'exists:reply_keyboards,id'],
            'inline_keyboard_id' => ['nullable', 'integer', 'exists:inline_keyboards,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
        ];
    }
}
