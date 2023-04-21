<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotProductStoreRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:190', 'unique:bot_products,slug'],
            'description' => ['nullable', 'string'],
            'images' => ['nullable', 'json'],
            'base_price' => ['required', 'numeric'],
            'discount_price' => ['required', 'numeric'],
            'weight' => ['required', 'numeric'],
            'count' => ['required', 'string'],
            'in_stock' => ['required'],
            'specifications' => ['nullable', 'json'],
            'variants' => ['nullable', 'json'],
            'owner_id' => ['required', 'integer', 'exists:owners,id'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
        ];
    }
}
