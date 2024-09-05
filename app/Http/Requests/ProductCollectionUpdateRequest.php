<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCollectionUpdateRequest extends FormRequest
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
            'bot_id' => ['nullable', 'integer', 'exists:bots,id'],
            'owner_id' => ['nullable', 'integer', 'exists:owners,id'],
            'title' => ['nullable', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_public' => ['required'],
            'is_active' => ['required'],
            'discount' => ['required', 'integer'],
            'order_position' => ['required', 'integer'],
            'config' => ['nullable', 'json'],
            'bot_user_id' => ['required', 'integer', 'exists:bot_users,id'],
        ];
    }
}
