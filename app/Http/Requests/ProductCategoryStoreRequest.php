<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryStoreRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:190', 'unique:product_categories,slug'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
