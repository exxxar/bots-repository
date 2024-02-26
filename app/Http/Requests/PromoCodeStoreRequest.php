<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PromoCodeStoreRequest extends FormRequest
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
            'code' => ['required', 'string', 'max:255', 'unique:promo_codes,code'],
            'description' => ['nullable', 'string'],
            'slot_amount' => ['required', 'integer'],
            'cashback_amount' => ['required', 'numeric'],
            'max_activation_count' => ['required', 'integer'],
            'is_active' => ['required'],
        ];
    }
}
