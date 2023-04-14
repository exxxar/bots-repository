<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotMenuTemplateStoreRequest extends FormRequest
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
            'type' => ['required', 'integer'],
            'command' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:190', 'unique:bot_menu_templates,slug'],
            'menu' => ['nullable', 'json'],
        ];
    }
}
