<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IngredientStoreRequest extends FormRequest
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
            'description' => ['nullable', 'string'],
            'image' => ['nullable', 'string', 'max:255'],
            'weight' => ['nullable', 'string', 'max:255'],
            'price' => ['nullable', 'string', 'max:255'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'food_constructor_id' => ['required', 'integer', 'exists:food_constructors,id'],
            'sub' => ['nullable', 'json'],
            'ingredient_category_id' => ['required', 'integer', 'exists:ingredient_categories,id'],
            'is_checked' => ['required'],
            'is_disabled' => ['required'],
            'is_global' => ['required'],
        ];
    }
}
