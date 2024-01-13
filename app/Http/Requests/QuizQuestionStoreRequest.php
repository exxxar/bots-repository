<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizQuestionStoreRequest extends FormRequest
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
            'media_content' => ['nullable', 'json'],
            'content_type' => ['required', 'integer'],
            'is_multiply' => ['required'],
            'is_open' => ['required'],
            'round' => ['required', 'integer'],
        ];
    }
}
