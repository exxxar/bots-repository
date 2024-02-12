<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InlineQueryItemUpdateRequest extends FormRequest
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
            'inline_query_slug_id' => ['required', 'integer', 'exists:inline_query_slugs,id'],
            'type' => ['required', 'integer'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'input_message_content' => ['nullable', 'json'],
            'inline_keyboard_id' => ['required', 'integer', 'exists:inline_keyboards,id'],
            'custom_settings' => ['nullable', 'json'],
        ];
    }
}
