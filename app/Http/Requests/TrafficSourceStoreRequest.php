<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrafficSourceStoreRequest extends FormRequest
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
            'bot_id' => ['nullable', 'integer', 'exists:bots,id'],
            'bot_user_id' => ['nullable', 'integer', 'exists:,id'],
            'comment' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
        ];
    }
}
