<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class YClientUpdateRequest extends FormRequest
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
            'login' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'password', 'max:255'],
            'partner_token' => ['nullable', 'string', 'max:255'],
            'need_debug' => ['required'],
            'debug_log_file' => ['nullable', 'string', 'max:255'],
            'throttle' => ['required', 'integer'],
        ];
    }
}
