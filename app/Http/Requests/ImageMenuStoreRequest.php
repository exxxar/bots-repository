<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageMenuStoreRequest extends FormRequest
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
            'image' => ['nullable', 'json'],
            'info_link' => ['nullable', 'string', 'max:255'],
            'bot_id' => ['required', 'integer', 'exists:bots,id'],
            'product_count' => ['required', 'integer'],
            'location_id' => ['required', 'integer', 'exists:locations,id'],
            'created_at' => ['nullable'],
            'updated_at' => ['nullable'],
            'deleted_at' => ['nullable'],
        ];
    }
}
