<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotDialogAnswerUpdateRequest extends FormRequest
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
            'bot_dialog_command_id' => ['required', 'integer', 'exists:bot_dialog_commands,id'],
            'answer' => ['nullable', 'string', 'max:255'],
            'pattern' => ['nullable', 'string', 'max:255'],
            'next_bot_dialog_command_id' => ['required', 'integer', 'exists:next_bot_dialog_commands,id'],
        ];
    }
}
