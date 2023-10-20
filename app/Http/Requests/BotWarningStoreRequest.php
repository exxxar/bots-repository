<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotWarningStoreRequest extends FormRequest
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
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'rule_key' => ['required', 'string', 'max:50'],
            'rule_value' => ['required', 'integer'],
            'is_active' => ['required'],
        ];
    }
}
