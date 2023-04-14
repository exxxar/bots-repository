<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:190', 'unique:users,email'],
            'phone' => ['nullable', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'password' => ['nullable', 'password', 'max:255'],
            'fio_from_telegram' => ['nullable', 'string', 'max:255'],
            'telegram_chat_id' => ['nullable', 'string', 'max:255'],
            'age' => ['required', 'integer'],
            'birthday' => ['nullable', 'string', 'max:255'],
            'sex' => ['required', 'integer'],
            'avatar_url' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
            'blocked_at' => ['nullable'],
            'blocked_message' => ['nullable', 'string', 'max:255'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
