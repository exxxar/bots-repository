<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotCustomFieldSettingUpdateRequest extends FormRequest
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
            'type' => ['nullable', 'integer'],
            'label' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'required' => ['required'],
            'validate_pattern' => ['nullable', 'string', 'max:255'],
        ];
    }
}
