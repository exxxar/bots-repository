<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotDialogCommandStoreRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:255'],
            'pre_text' => ['nullable', 'string'],
            'post_text' => ['nullable', 'string'],
            'error_text' => ['nullable', 'string'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'input_pattern' => ['nullable', 'string', 'max:255'],
            'inline_keyboard_id' => ['nullable', 'integer', 'exists:inline_keyboards,id'],
            'images' => ['nullable', 'json'],
            'next_bot_dialog_command_id' => ['nullable', 'integer', 'exists:next_bot_dialog_commands,id'],
            'result_channel' => ['nullable', 'string', 'max:255'],
        ];
    }
}
