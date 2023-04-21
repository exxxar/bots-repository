<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BotProductCategoryUpdateRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:190', 'unique:bot_product_categories,slug'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
        ];
    }
}
