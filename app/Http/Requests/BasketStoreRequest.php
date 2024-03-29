<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasketStoreRequest extends FormRequest
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
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'count' => ['required', 'integer'],
            'bot_user_id' => ['required', 'integer', 'exists:bot_users,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'ordered_at' => ['nullable'],
        ];
    }
}
