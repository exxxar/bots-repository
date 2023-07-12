<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
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
            'article' => ['nullable', 'string', 'max:255'],
            'vk_product_id' => ['nullable', 'string', 'max:255'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'json'],
            'type' => ['required', 'integer'],
            'old_price' => ['required', 'numeric'],
            'current_price' => ['required', 'numeric'],
            'variants' => ['nullable', 'json'],
            'in_stop_list_at' => ['nullable'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
        ];
    }
}
