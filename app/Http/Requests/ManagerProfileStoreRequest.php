<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerProfileStoreRequest extends FormRequest
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
            'bot_user_id' => ['required', 'integer', 'exists:bot_users,id'],
            'info' => ['nullable', 'string'],
            'referral' => ['nullable', 'string', 'max:255'],
            'strengths' => ['nullable', 'json'],
            'weaknesses' => ['nullable', 'json'],
            'educations' => ['nullable', 'json'],
            'social_links' => ['nullable', 'json'],
            'skills' => ['nullable', 'json'],
            'stable_personal_discount' => ['required', 'numeric'],
            'permanent_personal_discount' => ['required', 'numeric'],
            'max_company_slot_count' => ['required', 'integer'],
            'max_bot_slot_count' => ['required', 'integer'],
            'balance' => ['required', 'integer'],
            'verified_at' => ['nullable'],
        ];
    }
}
