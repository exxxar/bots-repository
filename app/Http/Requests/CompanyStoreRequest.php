<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyStoreRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:190', 'unique:companies,slug'],
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'phones' => ['nullable', 'json'],
            'links' => ['nullable', 'json'],
            'email' => ['nullable', 'email', 'max:255'],
            'schedule' => ['nullable', 'json'],
            'manager' => ['nullable', 'string', 'max:255'],
            'is_active' => ['required'],
            'blocked_message' => ['nullable', 'string'],
            'blocked_at' => ['nullable'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
