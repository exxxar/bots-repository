<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BasketUpdateRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'order_id' => ['required', 'integer', 'exists:orders,id'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
