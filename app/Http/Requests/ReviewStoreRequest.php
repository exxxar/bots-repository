<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewStoreRequest extends FormRequest
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
            'text' => ['nullable', 'string'],
            'images' => ['nullable', 'json'],
            'rating' => ['required', 'integer'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'deleted_at' => ['nullable'],
        ];
    }
}
