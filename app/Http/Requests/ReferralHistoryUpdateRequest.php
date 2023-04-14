<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReferralHistoryUpdateRequest extends FormRequest
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
            'user_sender_id' => ['required', 'integer', 'exists:user_senders,id'],
            'user_recipient_id' => ['required', 'integer', 'exists:user_recipients,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'activated' => ['required'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
