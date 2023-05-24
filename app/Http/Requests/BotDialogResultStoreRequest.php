<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotDialogResultStoreRequest extends FormRequest
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
            'bot_user_id' => ['nullable', 'integer', 'exists:bot_users,id'],
            'bot_dialog_command_id' => ['nullable', 'integer', 'exists:bot_dialog_commands,id'],
            'current_input_data' => ['nullable', 'json'],
            'summary_input_data' => ['nullable', 'json'],
            'completed_at' => ['nullable'],
        ];
    }
}
