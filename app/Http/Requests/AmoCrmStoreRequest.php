<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AmoCrmStoreRequest extends FormRequest
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
            'client_id' => ['required', 'string', 'max:255'],
            'client_secret' => ['required', 'string', 'max:255'],
            'auth_code' => ['required', 'string', 'max:1000'],
            'redirect_uri' => ['required', 'string', 'max:255'],
            'subdomain' => ['required', 'string', 'max:255'],
        ];
    }
}
