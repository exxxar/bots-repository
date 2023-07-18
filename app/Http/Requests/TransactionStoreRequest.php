<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'payload' => ['required', 'string', 'max:128', 'unique:transactions,payload'],
            'currency' => ['required', 'string', 'max:5'],
            'total_amount' => ['required', 'integer'],
            'status' => ['required', 'integer'],
            'order_info' => ['nullable', 'json'],
            'products_info' => ['nullable', 'json'],
            'shipping_address' => ['nullable', 'json'],
            'telegram_payment_charge_id' => ['nullable', 'string', 'max:255'],
            'provider_payment_charge_id' => ['nullable', 'string', 'max:255'],
            'completed_at' => ['nullable'],
        ];
    }
}
